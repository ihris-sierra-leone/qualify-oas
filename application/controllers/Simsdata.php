<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 3:22 PM
 */
class Simsdata extends CI_Controller
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


    function school_list()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'school_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'school_list/', 'title' => 'Colleges or Schools List');
        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('school');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_school($remote_data->data);
                    $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('school_list', 'refresh');
        }

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/school_list';
        $this->load->view('template', $this->data);
    }

    function manage_school($id = null)
    {
        $current_user = current_user();

        $this->data['id'] = $id;
        if (!is_null($id)) {
            $id = decode_id($id);
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'school_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'manage_school/' . $this->data['id'], 'title' => 'Manage Colleges or Schools');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('type1', 'Type', 'required');


        if ($this->form_validation->run() == true) {
            $schoolinfo = array(
                'name' => trim($this->input->post('name')),
                'type1' => trim($this->input->post('type1')),
                'createdby' => $current_user->id,
                'modifiedon' => date('Y-m-d H:i:s')
            );
            $this->common_model->add_schools($schoolinfo, $id);
            $this->session->set_flashdata('message', show_alert('Information saved !!', 'success'));
            redirect('school_list', 'refresh');
        }

        if (!is_null($id)) {
            $schoolinfo = $this->common_model->get_college_school($id)->row();
            if ($schoolinfo) {
                $this->data['schoolinfo'] = $schoolinfo;
            }
        }


        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/manage_school';
        $this->load->view('template', $this->data);
    }

    function department_list()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'department_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_DEPARTMENT :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'department_list/', 'title' => 'Department List');
        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('department');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_department($remote_data->data);
                    $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('department_list', 'refresh');
        }

        $this->data['department_list'] = $this->common_model->get_department()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/department_list';
        $this->load->view('template', $this->data);
    }

    function manage_department($id = null)
    {
        $current_user = current_user();

        $this->data['id'] = $id;
        if (!is_null($id)) {
            $id = decode_id($id);
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'department_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_DEPARTMENT :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'manage_department/', 'title' => 'Manage Department');
        //$this->form_validation->set_rules('head', 'Head', 'required');
        $this->form_validation->set_rules('school_id', 'College/School', 'required');
        if (is_null($id)) {
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[department.Name]');
        } else {
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique_edit[department.Name.' . $id . ']');
        }
        if ($this->form_validation->run() == true) {
            $departmentinfo = array(
                'Name' => trim($this->input->post('name')),
                'school_id' => trim($this->input->post('school_id')),
            );
            $this->common_model->add_department($departmentinfo, $id);
            $this->session->set_flashdata('message', show_alert('Information saved !!', 'success'));
            redirect('department_list', 'refresh');
        }

        if (!is_null($id)) {
            $departmentinfo = $this->common_model->get_department($id)->row();
            if ($departmentinfo) {
                $this->data['departmentinfo'] = $departmentinfo;
            }
        }


        $this->data['school_list'] = $this->common_model->get_college_school()->result();

        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/manage_department';
        $this->load->view('template', $this->data);
    }


    function programme_list(){
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('programme');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_programme($remote_data->data);
                    $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('programme_list', 'refresh');
        }


        $this->load->library('pagination');

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'programme_list/', 'title' => 'Programme List');

        $department_list = get_user_department($current_user);

        $where = " WHERE 1=1";

        if (!is_null($department_list)) {
            if (!is_array($department_list)) {
                $department_list = array($department_list);
            }

            $where .= " AND Departmentid IN ( " . implode(',', $department_list) . " ) ";
        }


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  Name LIKE '%" . $_GET['key'] . "%'";
        }

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND  type = ". $_GET['type'];
        }

        $sql = " SELECT * FROM programme  $where ";

        $sql2 = " SELECT count(id) as counter FROM programme  $where ";

        $config["base_url"] = site_url('programme_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 20;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['programme_list'] = $this->db->query($sql . " ORDER BY Name ASC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/programme_list';
        $this->load->view('template', $this->data);
    }

    function add_programme($id=null){
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_programme/'.$id, 'title' => 'Programme');


        if(is_null($id)) {
            $this->form_validation->set_rules('code', 'Code', 'required|is_unique[programme.Code]');
        }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('department', 'Department', 'required');
        $this->form_validation->set_rules('cat', 'Category', 'required');

        if ($this->form_validation->run() == true) {
            $array_data = array(
                'Name' => trim($this->input->post('name')),
                'Departmentid' => trim($this->input->post('department')),
                'type' =>trim($this->input->post('cat')),
                'active' =>trim($this->input->post('active'))
            );
            if(is_null($id)){
                $array_data['Code'] = trim($this->input->post('code'));
            }

            $add = $this->common_model->add_programme($array_data,$id);
            if($add){
                $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
                redirect('programme_list','refresh');
            }else{
                $this->data['message'] = show_alert('Fail to save Information','info');
            }


        }
        if(!is_null($id)){
            $check = $this->common_model->get_programme($id)->row();
            if($check){
                $this->data['programme_info'] = $check;
            }
        }
        $this->data['department_list'] =$this->common_model->get_department()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_programme';
        $this->load->view('template', $this->data);
    }



    function fee_structure_list()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }



        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'fee_structure_list/', 'title' => 'Fee structure List');

        $this->data['fee_structure_list'] = $this->db->query("select * from fee_structure")->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/fee_structure_list';
        $this->load->view('template', $this->data);
    }
    function manage_fee_structure($id = null)
    {
        $current_user = current_user();

        $this->data['id'] = $id;
        if (!is_null($id)) {
            $id = decode_id($id);
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data Source');
        $this->data['bscrum'][] = array('link' => 'manage_department/', 'title' => 'Manage Fee Structure');
        $this->form_validation->set_rules('amount', 'Fee  Amount', 'required');
        $this->form_validation->set_rules('name', 'Fee  Name', 'required');
        $this->form_validation->set_rules('percentage', 'Percentage Option', 'required');
        $this->form_validation->set_rules('gepg_category_code', 'GEPG Category', 'required');



        //$this->form_validation->set_rules('head', 'Head', 'required');
        //$this->form_validation->set_rules('school_id', 'College/School', 'required');
//        if (is_null($id)) {
//            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[department.Name]');
//        } else {
//            $this->form_validation->set_rules('name', 'Name', 'required|is_unique_edit[department.Name.' . $id . ']');
//        }
        if ($this->form_validation->run() == true) {
            $feestructureinfo = array(
                'name' => trim($this->input->post('name')),
                'percentage' => trim($this->input->post('percentage')),
                'amount' => trim($this->input->post('amount')),
                'fee_code'=>trim($this->input->post('gepg_category_code'))
            );
            $this->common_model->add_fee_structure($feestructureinfo, $id);
            $this->session->set_flashdata('message', show_alert('Information saved !!', 'success'));
            redirect('fee_structure_list', 'refresh');
        }

        if (!is_null($id)) {
            $feestructureinfo = $this->db->query("select * from fee_structure where id=".$id)->row();
            if ($feestructureinfo) {
                $this->data['feestructureinfo'] = $feestructureinfo;
            }
        }

        $this->data['ayear_list'] = $this->db->query("select * from ayear")->result();


        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/manage_fee_structure';
        $this->load->view('template', $this->data);
    }


}