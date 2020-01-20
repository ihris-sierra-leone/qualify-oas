<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 8/3/17
 * Time: 10:42 AM
 */
class Logs extends CI_Controller
{
    private $MODULE_ID = '';
    private $GROUP_ID = '';

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();

        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

        $this->data['title'] = 'Administrator';

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }


    function api_issues()
    {
        $current_user = current_user();



        $this->data['bscrum'][] = array('link' => '#', 'title' => 'NECTA & NACTE API');
        $this->data['bscrum'][] = array('link' => 'api_issues/', 'title' => 'API Issues');

        $this->load->library('pagination');

        $where = ' WHERE ed.api_status != 1 ';


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (ed.index_number LIKE '%" . $_GET['key'] . "%' OR ap.MiddleName LIKE '%" . $_GET['key'] . "%'  OR ap.FirstName LIKE '%" . $_GET['key'] . "%' OR  ap.LastName LIKE '%" . $_GET['key'] . "%')";
        }


        $sql = " SELECT ed.*,ap.FirstName,ap.MiddleName,ap.LastName FROM application_education_authority as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";
        $sql2 = " SELECT count(ed.id) as counter FROM application_education_authority as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";

        $config["base_url"] = site_url('logs/api_issues/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY ap.FirstName ASC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'logs';
        $this->data['content'] = 'panel/api_issues_logs';
        $this->load->view('template', $this->data);
    }


    function retry($id){
        $id = decode_id($id);
        $this->db->delete('necta_tmp_result',array('authority_id'=>$id));
        $row = $this->db->where('id',$id)->get('application_education_authority')->row();
        if($row){
            $this->applicant_model->trigger_necta_results($row->certificate, $id,'NEW');
            $this->session->set_flashdata('message',show_alert('Background process initiated, Refresh page after 30 sec and see the row if exist...','success'));
            redirect('logs/api_issues','refresh');
        }else{
            $this->session->set_flashdata('message',show_alert('No changes take place, Row already affected ','info'));
            redirect('logs/api_issues','refresh');
        }
    }

    function change_name(){
        $firstname = ucfirst(strtolower(trim($this->input->post('firstname'))));
        $middlename = ucfirst(strtolower(trim($this->input->post('middlename'))));
        $lastname = ucfirst(strtolower(trim($this->input->post('lastname'))));
        $id = trim($this->input->post('applicant_id'));
        $this->db->update("application", array('FirstName'=>$firstname,'MiddleName'=>$middlename,'LastName'=>$lastname), array('id'=>$id));
        $this->db->update("users", array('firstname'=>$firstname,'lastname'=>$lastname), array('applicant_id'=>$id));

    }


    function tcu_issues(){
      $current_user = current_user();

      $this->data['bscrum'][] = array('link' => '#', 'title' => 'NECTA & NACTE API');
      $this->data['bscrum'][] = array('link' => 'api_issues/', 'title' => 'TCU Issues');

      $this->load->library('pagination');

      // $where = ' WHERE ed.api_status != 1 ';
      $where = " WHERE 1 AND (ed.f4indexno='' OR ed.request_status=0) ";

      if (isset($_GET['key']) && $_GET['key'] != '') {
          $where .= " AND  (ed.index_number LIKE '%" . $_GET['key'] . "%' OR ap.MiddleName LIKE '%" . $_GET['key'] . "%'  OR ap.FirstName LIKE '%" . $_GET['key'] . "%' OR  ap.LastName LIKE '%" . $_GET['key'] . "%')";
      }

      $sql = " SELECT ed.*,ap.FirstName,ap.MiddleName,ap.LastName,ap.response FROM tcu_records as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";
      $sql2 = " SELECT count(ed.id) as counter FROM tcu_records as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";

      $config["base_url"] = site_url('logs/tcu_issues/');

      $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
      if (count($_GET) > 0)
      $config['suffix'] = '?' . http_build_query($_GET, '', "&");

      $config["total_rows"] = $this->db->query($sql2)->row()->counter;
      $config["per_page"] = 50;
      $config["uri_segment"] = 3;

      include 'include/config_pagination.php';

      $this->pagination->initialize($config);
      $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
      $this->data['pagination_links'] = $this->pagination->create_links();

      $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY ap.FirstName ASC LIMIT $page," . $config["per_page"])->result();

      $this->data['active_menu'] = 'logs';
      $this->data['content'] = 'panel/tcu_issues_logs';
      $this->load->view('template', $this->data);
    }


    function tcu_retry($id)
    {
      if(!is_null($id)){
      $id = decode_id($id);
      $applicant = $this->db->get_where('tcu_records', array('id'=> $id))->row()->applicant_id;
      $type = $this->db->get_where('application', array('id'=>$applicant))->row()->application_type;

      if($type==1){

        $this->db->delete('tcu_records',array('id'=>$id));
        $this->session->set_flashdata('message', show_alert('Application was for certificate, hence deleted !!', 'success'));
        redirect('logs/tcu_issues', 'refresh');

      }else if($type==3){
        $this->db->delete('tcu_records',array('id'=>$id));
        $this->session->set_flashdata('message', show_alert('Application was for postgraduate, hence deleted !!', 'success'));
        redirect('logs/tcu_issues', 'refresh');

      }else if($type == 2) {
          $entry = $this->db->get_where('application', array('id'=> $applicant))->row()->entry_category;
          // echo $entry; exit;
          if ($entry == 2) {
              $f4indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 1))->row()->index_number;
              $f6indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 2))->row()->index_number;
              $entry_category = "A";
              // echo $f4indexno; exit;
          } else if ($entry == 4) {
              $f4indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 1))->row()->index_number;
              $f6indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 4))->row()->avn;
              $entry_category = "D";

              // echo $f6indexno; exit;
          }
          $xml_data = AddApplicantRequest(TCU_USERNAME, TCU_TOKEN,$f4indexno, $f6indexno, $entry_category, '', '');
          // echo $xml_data; exit;
          $Response = sendXmlOverPost('http://api.tcu.go.tz/applicants/add', $xml_data);
          $Response=RetunMessageString($Response,'ResponseParameters');
          $data = simplexml_load_string($Response);
          $json = json_encode($data);
          $array = json_decode($json,TRUE);

          $error_code = $array['StatusCode'];
          $f4index = $f4indexno;
          $status = $array['StatusCode'];
          $description = $array['StatusDescription'];
          $date = date('Y-m-d H:i:s');
          if ($status == "SUCCESS") {
              $request_status = 1;
//              header('content-Type: application/json');
//              $result = str_replace(array("\n", "\r", "\t"), '', $Response);
//              $xml = simplexml_load_string($result);
//              $object = new stdclass();
//              $object = $xml;
              $response_result = $json;
              $insert = $this->db->query("insert into tcu_records values('','" . $applicant . "','Add','$error_code','$f4index','$status','$description','$request_status','$xml_data','$response_result','$date')");
              if ($insert) {
                $this->db->delete('tcu_records',array('id'=>$id));
                  $datatoupdate = array(
                      'response' => 1,
                  );
                  $this->db->where('id', $applicant);
                  $this->db->update('application', $datatoupdate);
                  $this->session->set_flashdata('message', show_alert('Application submitted successfully !!', 'success'));
                  redirect('logs/tcu_issues', 'refresh');

              }
          } else {
              $request_status = 0;
//              header('content-Type: application/json');
//              $result = str_replace(array("\n", "\r", "\t"), '', $Response);
//              $xml = simplexml_load_string($result);
//              $object = new stdclass();
//              $object = $xml;
              $response_result = $json;
              $insert = $this->db->query("insert into tcu_records values('','" . $applicant . "','Add','$error_code','$f4index','$status','$description','$request_status','$xml_data','$response_result','$date')");
              if ($insert) {
                $this->db->delete('tcu_records',array('id'=>$id));
                  $datatoupdate = array(
                      'response' => 0,
                  );
                  $this->db->where('id', $applicant);
                  $this->db->update('application', $datatoupdate);
                  $this->session->set_flashdata('message', show_alert('Process failed, Recheck the form something is missing !!', 'warning'));
                  redirect('logs/tcu_issues', 'refresh');

              }
          }

        }
      }
    }

    function change_status($id)
    {
      if(!is_null($id)){
        $id = decode_id($id);
        $datatoupdate = array(
            'response' => 0,
            'submitted' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('application', $datatoupdate);
        $this->session->set_flashdata('message', show_alert('Process Complete, Status changed successfully !!', 'success'));
        redirect('logs/tcu_issues', 'refresh');
      }
    }

}
