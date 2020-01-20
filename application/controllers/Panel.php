<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/16/17
 * Time: 10:45 AM
 */
class Panel extends CI_Controller
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

    function current_enrolled_list()
    {
        $current_user = current_user();





        $sql = " SELECT * FROM enrolled_student ";

        $config["base_url"] = site_url('invoice_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);

        $this->data['enrolled_list'] = $this->db->query($sql . " ORDER BY enrolled_student.id")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'invoice_list/', 'title' => 'Enrolled List');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/enrolled_list';
        $this->load->view('template', $this->data);

    }


    function invoice_list()
    {
        $current_user = current_user();
        $ega_auth=$this->db->query("select * from ega_auth")->row();
        if(isset($_GET['resubmit_selected']) )
        {
            if(isset($_GET['txtSelect']))
            {
                $selected_invoices=$_GET['txtSelect'];
                $i=0;
                foreach ($selected_invoices as $key=>$invoice_id)
                {
                    $invoice_info=$this->db->query("select * from invoices where id=".$invoice_id)->row();

                    if($invoice_info->status==0 ) {
                        $refference=$ega_auth->prefix.$invoice_id;
                        $fee_type=$invoice_info->type;
                       // $invoice_student_info=$this->db->query("select * from application where id='".$invoice_info->student_id."'")->row();
                        //$student_name=$invoice_student_info->FirstName. ''.$invoice_student_info->MiddleName. ''.$invoice_student_info->LastName;
                        $student_email=$invoice_info->student_email;
                        $postdata = array(
                            "customer" => $ega_auth->username,
                            "reference" => $ega_auth->prefix.$invoice_id,
                            "student_name" =>$invoice_info->student_name,
                            "student_id" => $invoice_info->student_id,
                            "student_email"=>$student_email,
                            "student_mobile"=>$invoice_info->student_mobile,
                            "GfsCode"=>$invoice_info->GfsCode,
                            "amount"=>$invoice_info->amount,
                            "type"=>$invoice_info->type,
                            "secret"=>$ega_auth->api_secret,
                            "action"=>'SEND_INVOICE'
                        );
                        $url=$ega_auth->call_url;
                        $result=sendDataOverPost($url,$postdata);
                        $result_array=json_decode($result,true);
                        $log_data_array=array(
                            'request'=>print_r($postdata,true),
                            'responce'=>$result,
                            'status'=>$result_array['status'],
                            'description'=>$result_array['description'],
                            'type'=>'invoice'
                        );
                        $this->db->insert('ega_logs',$log_data_array);
                    }

                }
                $this->session->set_flashdata("message", show_alert("Selected Invoice Successfully Resubmited", 'info'));
                redirect(site_url('invoice_list/'),'refresh');
            }else
            {
                $this->session->set_flashdata("message", show_alert("Please select at  list one invoice", 'danger'));
                redirect(site_url('invoice_list/'),'refresh');
            }
        }

        if(isset($_GET['cancel_selected']) )
        {
            if(isset($_GET['txtSelect']))
            {
                $selected_invoices=$_GET['txtSelect'];
                $i=0;
                $refference='';
                foreach ($selected_invoices as $key=>$invoice_id)
                {
                    if($i==0)
                        $refference=$ega_auth->prefix.$invoice_id;
                    else
                        $refference = $refference.','.$ega_auth->prefix.$invoice_id;
                    $i+=1;
                }

                $invoince_cancel= array(
                    'references'=>$refference,
                    "secret" =>$ega_auth->api_secret,
                    "customer" => $ega_auth->username,
                    "action"   => 'CANCEL_INVOICE'
                );

                $url=$ega_auth->call_url;
                $result=sendDataOverPost($url,$invoince_cancel);
                $result_array=json_decode($result,true);
                $log_data_array=array(
                    'request'=>print_r($invoince_cancel,true),
                    'responce'=>$result,
                    'status'=>$result_array['status'],
                    'description'=>$result_array['description'],
                    'type'=>'invoice'
                );
                $this->db->insert('ega_logs',$log_data_array);
                if($result_array['status']=='1')
                {
                    $update_invoice=array(
                        'status'=>100
                    );

                    foreach ($selected_invoices as $key=>$invoice_id)
                    {
                        $this->db->update('invoices', $update_invoice, array('id'=>$invoice_id));

                    }

                }

                $this->session->set_flashdata("message", show_alert("Selected Invoice Successfully Cancelled", 'info'));
                redirect(site_url('invoice_list/'),'refresh');
            }else
            {
                $this->session->set_flashdata("message", show_alert("Please select at  list one invoice", 'danger'));
                redirect(site_url('invoice_list/'),'refresh');
            }

        }

        $where = ' WHERE 1=1';

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND member_type='" . $_GET['type'] . "' ";
        }

        if (isset($_GET['name']) && $_GET['name'] != '') {

            // $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
            $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR registration_number='".$_GET['name']."' OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(timestamp)>='" . format_date($_GET['from']) . "' ";
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(timestamp)<='" . format_date($_GET['to']) . "' ";
        }

        $sql = " SELECT * FROM invoices  $where ";

        $sql2 = "SELECT count(id) as counter FROM invoices $where ";

        $config["base_url"] = site_url('invoice_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;


        $this->data['invoice_list'] = $this->db->query($sql . " ORDER BY invoices.id DESC")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'invoice_list/', 'title' => 'Invoice List');

        $this->data['active_menu'] = 'invoice_list';
        $this->data['content'] = 'panel/invoice_list';
        $this->load->view('template', $this->data);

    }

    function payment_list()
    {

        if(isset($_GET['pull_transctions']) )
        {

            execInBackground('panel pull_payment ' );

            $this->session->set_flashdata("message", show_alert("Pull payments succesifully sent you can refresh the page afer 5 second to view transactions", 'info'));
            redirect(site_url('payment_list/'),'refresh');

        }

        $current_user = current_user();
        $ega_auth=$this->db->query("select * from ega_auth")->row();



        $where = ' WHERE 1=1';

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND member_type='" . $_GET['type'] . "' ";
        }

        if (isset($_GET['name']) && $_GET['name'] != '') {

            // $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
            $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR registration_number='".$_GET['name']."' OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(timestamp)>='" . format_date($_GET['from']) . "' ";
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(timestamp)<='" . format_date($_GET['to']) . "' ";
        }

        if (isset($_GET['fee']) && $_GET['fee'] != '') {
            $where .= " AND type='" . trim($_GET['fee']) . "' ";
        }

        $sql = " SELECT payment.*,student_name,type FROM payment  left join invoices on invoices.id=payment.invoice_number   $where ";


        $config["base_url"] = site_url('payment_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");



        $this->data['payment_list'] = $this->db->query($sql . " ORDER BY payment.id DESC")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'invoice_list/', 'title' => 'Invoice List');

        $this->data['active_menu'] = 'payment_list';
        $this->data['content'] = 'panel/payment_list';
        $this->load->view('template', $this->data);

    }

    function applicant_list()
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_list')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_LIST :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_list/', 'title' => 'Applicant List');

        $this->load->library('pagination');

        $ayear = $this->common_model->get_academic_year()->row()->AYear;
        // $date = "2018-09-24";
        $where = " WHERE 1=1 AND AYear='$ayear' ";
        if (isset($_GET['status']) && $_GET['status'] != '') {
            $where .= " AND submitted='" . $_GET['status'] . "' ";
        }


        if (isset($_GET['entry']) && $_GET['entry'] != '') {
            $where .= " AND entry_category='" . $_GET['entry'] . "' ";
        }

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND application_type='" . $_GET['type'] . "' ";
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
          $frm = $_GET['from'];
          $from = format_date($frm, true);
            $where .= " AND DATE(createdon) >='" . $from . "' ";
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
          $t = $_GET['to'];
          $to = format_date($t, true);
            $where .= " AND DATE(createdon) <='" . $to . "' ";
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND AYear='" . $_GET['year'] . "' ";
        }

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (form4_index LIKE '%" . $_GET['key'] . "%' OR  form6_index LIKE '%" . $_GET['key'] . "%' OR FirstName LIKE '%" . $_GET['key'] . "%' OR  LastName LIKE '%" . $_GET['key'] . "%')";
        }


        $sql = " SELECT * FROM application  $where ";
        $sql2 = " SELECT count(id) as counter FROM application  $where ORDER BY createdon DESC ";

        $config["base_url"] = site_url('applicant_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY DATE(createdon) ASC ")->result();


        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/applicant_list';
        $this->load->view('template', $this->data);
    }

    function change_status()
    {
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('applicant_id', 'Applicant ID', 'required');

        if ($this->form_validation->run() == true) {
            $array_data = array(
                'status' => $this->input->post('status')
            );

            $applicant_id = $this->input->post('applicant_id');

            $register = $this->applicant_model->update_applicant($array_data, array('id' => $applicant_id));

            echo '<div style="color: #0000cc">Status updated..</div>';
        } else {
            echo $this->input->post('status') . '|' . $this->input->post('applicant_id') . ' The Status field is required';
        }


    }

    function popup_applicant_info($id)
    {
        $id = decode_id($id);
        $APPLICANT = $this->applicant_model->get_applicant($id);
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $next_kin = $this->applicant_model->get_nextkin_info($APPLICANT->id)->result();
            if (count($next_kin) > 0) {
                $this->data['next_kin'] = $next_kin;
            }

            $referee = $this->applicant_model->get_applicant_referee($APPLICANT->id)->result();
            if (count($referee) > 0) {
                $this->data['academic_referee'] = $referee;
            }

            $sponsor = $this->applicant_model->get_applicant_sponsor($APPLICANT->id)->row();
            if ($sponsor) {
                $this->data['sponsor_info'] = $sponsor;
            }

            $employer = $this->applicant_model->get_applicant_employer($APPLICANT->id)->row();
            if ($employer) {
                $this->data['employer_info'] = $employer;
            }

            $this->data['education_bg'] = $this->applicant_model->get_education_bg(null, $APPLICANT->id);
            $this->data['attachment_list'] = $this->applicant_model->get_attachment($APPLICANT->id);
            $mychoice = $this->applicant_model->get_programme_choice($APPLICANT->id);
            if ($mychoice) {
                $this->data['mycoice'] = $mychoice;
            }
            if (isset($_GET) && isset($_GET['status'])) {
                $this->data['change_status'] = 1;
            }
            $this->load->view('panel/popup_applicant_info', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }

    function manage_criteria($type = 1)
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'CRITERIA', 'manage_criteria')) {
            $this->session->set_flashdata("message", show_alert("SELECTION_CRITERIA :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Criteria');
        $this->data['bscrum'][] = array('link' => 'manage_criteria/', 'title' => 'Eligibility Criteria');
        $this->data['selected'] = $type;
        $this->data['programme_list'] = $this->common_model->get_programme(null, $type)->result();
        $this->data['active_menu'] = 'manage_criteria';
        $this->data['content'] = 'panel/manage_criteria';
        $this->load->view('template', $this->data);
    }

    function programme_setting_panel($code = null)
    {
        $current_user = current_user();
        $this->data['CODE'] = $code;
        $ENTRY = (isset($_GET) && isset($_GET['entry']) ? $_GET['entry'] : null);
        if (!is_null($code)) {
            $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
            if ($row_year) {

                if (isset($_GET['sub_id']) && isset($_GET['cat']) && isset($_GET['row_id'])) {
                    //remove one row in the setting configurations
                    $get_row = $this->db->where('id', $_GET['row_id'])->get('application_criteria_setting')->row();
                    if ($get_row) {
                        $column = 'form6_inclusive';
                        if ($_GET['cat'] == 'IV') {
                            $column = 'form4_inclusive';
                        }

                        $column_data = json_decode($get_row->{$column}, true);
                        unset($column_data[$_GET['sub_id']]);

                        $this->db->update('application_criteria_setting', array($column => json_encode($column_data)), array('id' => $_GET['row_id']));
                        $this->session->set_flashdata('message', show_alert('Setting Data updated successfully', 'info'));
                        redirect(remove_query_string(array('sub_id', 'cat', 'row_id')), 'refresh');

                    }

                }

                //if(isset($_GET) && isset($_GET['type'])) {
                $this->form_validation->set_rules('save_data', 'Save Data', 'required');
                $this->form_validation->set_rules('form4_pass', '', 'integer');

                if ($this->form_validation->run() == true) {

                    $form4_data = array();
                    $form6_data = array();
                    $subject_form4 = $this->input->post('subjectIV');
                    $grade_form4 = $this->input->post('gradeIV');

                    $subject_form6 = $this->input->post('subjectVI');
                    $grade_form6 = $this->input->post('gradeVI');

                    $subjectIVOR = $this->input->post('subjectIVOR');
                    $gradeIVOR = $this->input->post('gradeIVOR');
                    $gradeIVORNO = $this->input->post('gradeIVORNO');

                    $subjectVIOR = $this->input->post('subjectVIOR');
                    $gradeVIOR = $this->input->post('gradeVIOR');
                    $gradeVIORNO = $this->input->post('gradeVIORNO');


                    if ($subject_form4) {
                        foreach ($subject_form4 as $k => $v) {
                            if ($grade_form4[$k] <> '' && $v <> '') {
                                $form4_data[$v] = $grade_form4[$k];
                            }
                        }
                    }

                    if ($subject_form6) {
                        foreach ($subject_form6 as $k => $v) {
                            if ($grade_form6[$k] <> '' && $v <> '') {
                                $form6_data[$v] = $grade_form6[$k];
                            }
                        }
                    }


                    $array_data = array(
                        'AYear' => $row_year->AYear,
                        'entry' => $this->input->post('entry'),
                        'form4_inclusive' => json_encode($form4_data),
                        'form4_exclusive' => ($this->input->post('subject4_exclusive') ? implode(',', $this->input->post('subject4_exclusive')) : ''),
                        'form4_pass' => trim($this->input->post('form4_pass')),
                        'form6_inclusive' => json_encode($form6_data),
                        'form6_exclusive' => ($this->input->post('subject6_exclusive') ? implode(',', $this->input->post('subject6_exclusive')) : ''),
                        'min_point' => ($this->input->post('min_point') ? $this->input->post('min_point') : ''),
                        'form6_pass' => trim($this->input->post('form6_pass')),
                        'gpa_pass' => trim($this->input->post('gpa_pass')),
                        'keyword1' => trim($this->input->post('keyword1')),
                        'ProgrammeCode' => $code,
                        'createdby' => $current_user->id,
                        'createdon' => date('Y-m-d H:i:s'),
                        'form4_or_subject' => ($gradeIVOR ? json_encode(array($gradeIVOR . '|' . $gradeIVORNO => $subjectIVOR)) : ''),
                        'form6_or_subject' => ($gradeVIOR ? json_encode(array($gradeVIOR . '|' . $gradeVIORNO => $subjectVIOR)) : ''),
                    );


                    $conf = $this->setting_model->programme_setting_criteria($array_data);
                    if ($conf) {
                        $this->session->set_flashdata('message', show_alert('Selection Criteria Saved successfully', 'success'));
                        redirect(current_full_url(), 'refresh');
                    } else {
                        $this->data['message'] = show_alert('Fail to save Criteria Information', 'info');
                    }
                }

                $setting_info = $this->setting_model->get_selection_criteria($code, $row_year->AYear, $ENTRY);

                if ($setting_info) {
                    $this->data['setting_info'] = $setting_info;
                }
                $this->data['content_view'] = "panel/set_criteria_rules";
                //}else{
                //  $this->data['content_view'] = "panel/set_criteria_category";
                //}
            } else {
                $this->data['message'] = show_alert('No active Year created, No Configuration allowed', 'info');
            }
            $this->data['programme_info'] = $this->db->where('Code', $code)->get('programme')->row();
            $this->data['subject_listIV'] = $this->setting_model->get_sec_subject(null, 1, 1)->result();
            $this->data['subject_listVI'] = $this->setting_model->get_sec_subject(null, 1, 2)->result();


            $this->load->view("panel/programme_setting_panel", $this->data);

        } else {
            echo "Please use link in the left side to start setting";
        }
    }

    function short_listed()
    {
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'short_listed')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_SHORT_LISTED :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'short_listed/', 'title' => 'Applicant Short Listed');


        $this->data['programme_list'] = $this->common_model->get_programme(null, null)->result();
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/short_listed';
        $this->load->view('template', $this->data);

    }

    function run_eligibility()
    {
        $programme_list = $this->common_model->get_programme()->result();
        foreach ($programme_list as $key => $value) {
            $current_round=$this->db->query("select * from application_round where application_type=".$value->type)->row();
            if($current_round)
            {
                $round=$current_round->round;
            }else{
                $round=1;
            }
            $new = $this->db->insert('run_eligibility', array('ProgrammeCode' => $value->Code,'round'=>$round));
            $last_id = $this->db->insert_id();
            if ($last_id) {
                execInBackground('response run_eligibility ' . $last_id);
            }
        }
        $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please Wait ...', 'info'));
        redirect('short_listed', 'refresh');
    }

    function run_eligibility_active()
    {
        $check = $this->db->get("run_eligibility")->row();
        if (!$check) {
            $this->session->set_flashdata('message', show_alert('Run Eligibility completed, Please Continue with other activities ', 'success'));

        } else {
            $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please still wait ...', 'info'));
        }
        echo '1';
    }

    function collection()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'collection/', 'title' => 'Application Fee');

        $where = " WHERE 1=1 AND p.msisdn <> '' ";

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(p.createdon) >='" . format_date($_GET['from']) . "' ";
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(p.createdon) <='" . format_date($_GET['to']) . "' ";
        }

        if (isset($_GET['ayear']) && $_GET['ayear'] != '') {
            $where .= " AND p.AYear ='" . trim($_GET['ayear']) . "' ";
        }

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (a.form4_index LIKE '%" . $_GET['key'] . "%' OR  p.msisdn LIKE '%" . $_GET['key'] . "%'
             OR p.reference LIKE '%" . $_GET['key'] . "%' OR a.FirstName LIKE '%" . $_GET['key'] . "%' OR  a.LastName LIKE '%" . $_GET['key'] . "%')";
        }

        $sql = " SELECT p.*,a.FirstName,a.MiddleName,a.LastName FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id)  $where ";
        $sql2 = " SELECT count(p.id) as counter FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id)  $where ";

        $total_amount = " SELECT SUM(p.amount) as total_amount FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id)  $where ";
        $total_charges = " SELECT SUM(p.charges) as total_charges FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id)  $where ";

        $this->load->library('pagination');
        $config["base_url"] = site_url('collection/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['collection_list'] = $this->db->query($sql . " ORDER BY p.createdon DESC ")->result();
        $this->data['total_amount'] = $this->db->query($total_amount)->row()->total_amount;
        $this->data['total_charges'] = $this->db->query($total_charges)->row()->total_charges;


        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/collection';
        $this->load->view('template', $this->data);
    }

    function selection_criteria($type = 1)
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'CRITERIA', 'selection_criteria')) {
            $this->session->set_flashdata("message", show_alert("SELECTION_CRITERIA :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Criteria');
        $this->data['bscrum'][] = array('link' => 'selection_criteria/', 'title' => 'Selection Criteria');
        $this->data['selected'] = $type;
        $this->data['programme_list'] = $this->common_model->get_programme(null, $type)->result();
        $this->data['active_menu'] = 'manage_criteria';
        $this->data['content'] = 'panel/selection_criteria';
        $this->load->view('template', $this->data);
    }

    function programme_setting_selection($code = null)
    {


        $current_user = current_user();
        $this->data['CODE'] = $code;

        $CATEGORY = (isset($_GET) && isset($_GET['category']) ? $_GET['category'] : null);
        if (!is_null($code)) {

            $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
            if ($row_year) {

                if(isset($_GET['sub_id'])) {
                    //remove one row in the setting configurations
                    $get_row = $this->db->where('id', $_GET['sub_id'])->get('application_selection_criteria_filter')->row();

                    if ($get_row) {
                        $this->db->delete('application_selection_criteria_filter', array('id' => $get_row->id));
                        $this->session->set_flashdata('message', show_alert('Setting Data updated successfully', 'info'));
                        redirect(remove_query_string(array('sub_id')), 'refresh');

                    }
                }

                if ($this->input->post('capacity')) {

                    $capacity = $this->input->post('capacity');
                    $direct = $this->input->post('direct');

                    if (is_numeric($capacity) && is_numeric($direct)) {
                        $capacity_array = array(
                            'capacity' => $capacity,
                            'code' => $code,
                            'direct' => $direct
                        );
                        $row_check = $this->db->where('code', $code)->get('application_selection_criteria')->row();
                        if ($row_check) {
                            echo "update";
                            //update
                            $this->db->update('application_selection_criteria',$capacity_array,array('code'=>$code));
                        } else {
                            //insert
                            $this->db->insert('application_selection_criteria',$capacity_array);
                        }
                    }
                }
                $row_check = $this->db->where('code', $code)->get('application_selection_criteria')->row();
                if($row_check){
                    $this->data['setting_info'] = $row_check;
                    $this->data['CATEGORY'] = $CATEGORY;

                    if($this->input->post('applicant_category')){
                        $subjectIV_submitted = $this->input->post('subjectIV[]');
                        $subjectIV_submitted_order = $this->input->post('gradeIV[]');
                        foreach ($subjectIV_submitted as $ky=>$vy){
                            if($vy <> '' && $subjectIV_submitted_order[$ky] <> '' && is_numeric($subjectIV_submitted_order[$ky])) {
                                $array_subjectIV = array(
                                    'selection_id' => $row_check->id,
                                    'code' => $code,
                                    'category' => $CATEGORY,
                                    'filter_type' => 'FORM_IV',
                                    'filter_item' => $vy,
                                );

                                $check_case = $this->db->where($array_subjectIV)->get('application_selection_criteria_filter')->row();
                                if($check_case){
                                    //update
                                    $array_subjectIV['order_number'] = $subjectIV_submitted_order[$ky];
                                    $this->db->update('application_selection_criteria_filter',$array_subjectIV,array('id'=>$check_case->id));
                                }else{
                                    //insert['
                                    $array_subjectIV['order_number'] = $subjectIV_submitted_order[$ky];
                                    $this->db->insert('application_selection_criteria_filter',$array_subjectIV);
                                }

                            }
                        }

                        $subjectVI_submitted = $this->input->post('subjectVI[]');
                        $subjectVI_submitted_order = $this->input->post('gradeVI[]');
                        foreach ($subjectVI_submitted as $ky=>$vy){
                            if($vy <> '' && $subjectVI_submitted_order[$ky] <> '' && is_numeric($subjectVI_submitted_order[$ky])) {
                                $array_subjectVI = array(
                                    'selection_id' => $row_check->id,
                                    'code' => $code,
                                    'category' => $CATEGORY,
                                    'filter_type' => 'FORM_VI',
                                    'filter_item' => $vy,
                                );

                                $check_case = $this->db->where($array_subjectVI)->get('application_selection_criteria_filter')->row();
                                if($check_case){
                                    //update
                                    $array_subjectVI['order_number'] = $subjectVI_submitted_order[$ky];
                                    $this->db->update('application_selection_criteria_filter',$array_subjectVI,array('id'=>$check_case->id));
                                }else{
                                    //insert['
                                    $array_subjectVI['order_number'] = $subjectVI_submitted_order[$ky];
                                    $this->db->insert('application_selection_criteria_filter',$array_subjectVI);
                                }

                            }
                        }

                        $point_submitted = $this->input->post('point');
                        if($point_submitted <> '') {
                            $array_point = array(
                                'selection_id' => $row_check->id,
                                'code' => $code,
                                'category' => $CATEGORY,
                                'filter_type' => 'POINT',
                                'filter_item' => 'POINT',
                            );
                            $check_case = $this->db->where($array_point)->get('application_selection_criteria_filter')->row();
                            if ($check_case) {
                                //update
                                $array_point['order_number'] = $point_submitted;
                                $this->db->update('application_selection_criteria_filter', $array_point, array('id' => $check_case->id));
                            } else {
                                //insert'
                                $array_point['order_number'] = $point_submitted;
                                $this->db->insert('application_selection_criteria_filter', $array_point);
                            }
                        }

                        $gender_submitted = $this->input->post('gender');
                        $gender_order_submitted = $this->input->post('gender_order');
                        if($gender_submitted <> '' && $gender_order_submitted <> '') {
                            $array_gender = array(
                                'selection_id' => $row_check->id,
                                'code' => $code,
                                'category' => $CATEGORY,
                                'filter_type' => 'GENDER'
                            );

                            $check_case = $this->db->where($array_gender)->get('application_selection_criteria_filter')->row();
                            if ($check_case) {
                                //update
                                $array_gender['order_number'] = $gender_order_submitted;
                                $array_gender['filter_item'] = $gender_submitted;
                                $this->db->update('application_selection_criteria_filter', $array_gender, array('id' => $check_case->id));
                            } else {
                                //insert'
                                $array_gender['order_number'] = $gender_order_submitted;
                                $array_gender['filter_item'] = $gender_submitted;
                                $this->db->insert('application_selection_criteria_filter', $array_gender);
                            }
                        }

                        $fifo_submitted = $this->input->post('fifo');
                        if($fifo_submitted <> '') {
                            $array_fifo = array(
                                'selection_id' => $row_check->id,
                                'code' => $code,
                                'category' => $CATEGORY,
                                'filter_type' => 'FIFO',
                                'filter_item' => 'FIFO',
                            );
                            $check_case = $this->db->where($array_fifo)->get('application_selection_criteria_filter')->row();

                            if($check_case) {
                                //update
                                $array_fifo['order_number'] = $fifo_submitted;
                                $this->db->update('application_selection_criteria_filter', $array_fifo, array('id' => $check_case->id));
                            }else {
                                //insert'
                                $array_fifo['order_number'] = $fifo_submitted;
                                $this->db->insert('application_selection_criteria_filter', $array_fifo);
                            }
                        }
                    }

                    $this->data['subject_listIV'] = $this->setting_model->get_sec_subject(null, 1, 1)->result();
                    $this->data['subject_listVI'] = $this->setting_model->get_sec_subject(null, 1, 2)->result();
                }

                $this->data['content_view'] = "panel/set_criteria_rules";
            } else {
                $this->data['message'] = show_alert('No active Year created, No Configuration allowed', 'info');
            }
            $this->data['programme_info'] = $this->db->where('Code', $code)->get('programme')->row();
            $this->data['subject_list'] = $this->setting_model->get_sec_subject(null, 1)->result();

            $this->load->view("panel/programme_setting_selection", $this->data);

        } else {
            echo "Please use link in the left side to start setting";
        }
    }

    function applicant_selection()
    {
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_selection')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_SELECTION :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_selection/', 'title' => 'Applicant Selection');


        $this->data['programme_list'] = $this->common_model->get_programme(null,null)->result();
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/applicant_selection';
        $this->load->view('template', $this->data);

    }

    function run_selection($choice)
    {
        $programme_list = $this->common_model->get_programme()->result();
        foreach ($programme_list as $key => $value) {
            $new = $this->db->insert('run_selection', array('ProgrammeCode' => $value->Code,'choice'=>$choice));
            $last_id = $this->db->insert_id();
            if ($last_id) {
                execInBackground('response run_selection '.$last_id);
            }
        }

        $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please Wait ...', 'info'));redirect('applicant_selection', 'refresh');


    }

    function run_selection_active()
    {
        $check = $this->db->get("run_selection")->row();
        if (!$check) {
            $this->session->set_flashdata('message', show_alert('Run Selection completed, Please Continue with other activities ', 'success'));

        } else {
            $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please still wait ...', 'info'));
        }
        echo '1';
    }

    function record_bank_trans(){
    $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Application Fee');
        $this->data['bscrum'][] = array('link' => 'record_bank_trans/', 'title' => 'Record Bank Transaction');

        $this->form_validation->set_rules('receipt', 'Receipt', 'required|is_unique[application_payment.receipt]');
        $this->form_validation->set_rules('reference', 'Reference', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');




        if ($this->form_validation->run() == true) {
            $reference = trim($this->input->post('reference'));
            $myreference = substr($reference,3);
            $client = $this->applicant_model->get_applicant($myreference);
            if($client){
                $payment = array(
                    'msisdn'=>'BANK',
                    'reference'=>$reference,
                    'applicant_id'=> $myreference,
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'receipt'=>$this->input->post('receipt'),
                    'amount'=> trim($this->input->post('amount')),
                    'charges'=>0,
                    'channel'=>2,
                    'cretatedby'=>$current_user->id
                );

                $this->db->insert('application_payment',$payment);
                $this->session->set_flashdata('message',show_alert('Information saved successfuuly','success'));
                redirect('record_bank_trans','refresh');
            }else{
                $this->data['message'] = show_alert('Invalid Reference Number !!','warning');
            }
        }

        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/record_bank_trans';
        $this->load->view('template', $this->data);
    }


    function SubmitEnrolledStudents()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $excel_upload = TRUE;
        $upload_error = '';


        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        // $this->form_validation->set_rules('course','Course', 'required');


        if ($this->form_validation->run() == true) {
            //echo "hapa nafika";exit;

            //$code_course = $this->input->post('course');
            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload excel file.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'xlsx' && get_file_extension($_FILES['userfile']['name']) != 'xls')) {
                $upload_error = '<div class="required">File uploaded must be in xls or xlxs format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {
                $dest_name = time() . 'result_sheet.xlsx';
                move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/temp/' . $dest_name);
                // set file path
                $file = './uploads/temp/' . $dest_name;

                //load the excel library
                $this->load->library('excel');

                //read file from path
                $objPHPExcel = IOFactory::load($file);
                //get only the Cell Collection
                // echo "hapa nafika"; exit;
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = trim($data_value);
                    } else {
                        $arr_data[$row][$column] = trim($data_value);
                    }
                }

                foreach ($arr_data as $row) {
                    // echo "hapa nafika";exit;
                    $data = array();
                    $courseError = array();
                    if (trim($row['A']) <> '') {
                        $Fname = $row['A'];
                        $Mname = $row['B'];
                        $Surname = $row['C'];
                        $F4indexno=$row['D'];
                        $Gender = $row['E'];
                        $Nationality = $row['F'];
                        $DateOfBirth = $row['G'];
                        $ProgrammeCategory = $row['H'];
                        $Specialization = $row['I'];
                        $AdmissionYear = $row['J'];
                        $ProgrammeCode = $row['K'];
                        $RegistrationNumber = $row['L'];
                        $ProgrammeName = $row['M'];
                        $YearOfStudy = $row['N'];
                        $StudyMode = $row['O'];
                        $IsYearRepeat = $row['P'];
                        $EntryMode = $row['Q'];
                        $Sponsorship = $row['R'];
                        $PhysicalChallenges = $row['S'];
                        $xml_data = SubmitEnrolledStudentsRequest(TCU_USERNAME, TCU_TOKEN,$F4indexno,$Fname,$Mname,$Surname,
                        $Gender,$Nationality,$DateOfBirth,$ProgrammeCategory,$Specialization,$AdmissionYear,$ProgrammeCode,$RegistrationNumber,
                        $ProgrammeName,$YearOfStudy,$StudyMode,$IsYearRepeat,$EntryMode,$Sponsorship,$PhysicalChallenges);
                        $sendRequest = sendXmlOverPost('http://api.tcu.go.tz/applicants/submitEnrolledStudents', $xml_data);
                        $responce=RetunMessageString($sendRequest,'ResponseParameters');
                        $data = simplexml_load_string($responce);
                        $json = json_encode($data);
                        $array_data = json_decode($json,TRUE);
                        $status_code = $array_data['StatusCode'];
                        $regno = $array_data['RegistrationNumber'];
                        $description = $array_data['StatusDescription'];
                        $insert = $this->db->query("insert into enrolled_student(regno,code,description) values('".$RegistrationNumber . "','".$status_code."','".$description."')");
                    }

                }

                //delete excell here
                unlink('./uploads/temp/' . $dest_name);
                $this->session->set_flashdata('message', show_alert('Enrolled students successfully submited ', 'success'));
                redirect('current_enrolled_list', 'refresh');
            }
        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Import');
        $this->data['bscrum'][] = array('link' => 'SubmitEnrolledStudents/', 'title' => 'Submit Enrolled students');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/submit_enrolled_student';
        $this->load->view('template', $this->data);
    }


    function import()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $excel_upload = TRUE;
        $upload_error = '';


        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        // $this->form_validation->set_rules('course','Course', 'required');


        if ($this->form_validation->run() == true) {
           //echo "hapa nafika";exit;

            //$code_course = $this->input->post('course');
            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload excel file.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'xlsx' && get_file_extension($_FILES['userfile']['name']) != 'xls')) {
                $upload_error = '<div class="required">File uploaded must be in xls or xlxs format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {
                $dest_name = time() . 'result_sheet.xlsx';
                move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/temp/' . $dest_name);
                // set file path
                $file = './uploads/temp/' . $dest_name;

                //load the excel library
                $this->load->library('excel');

                //read file from path
                $objPHPExcel = IOFactory::load($file);
                //get only the Cell Collection
               // echo "hapa nafika"; exit;
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = trim($data_value);
                    } else {
                        $arr_data[$row][$column] = trim($data_value);
                    }
                }

                foreach ($arr_data as $row) {
                    // echo "hapa nafika";exit;
                    $data = array();
                    $courseError = array();
                    if (trim($row['A']) <> '') {
                        $data['ApplicantName'] = $row['A'];
                        // echo ($data['ApplicantName']); exit;
                        $data['F4INDEX'] = $row['B'];
                        $applicant = $this->db->get_where('application_education_authority', array('index_number' => $data['F4INDEX']))->row()->applicant_id;
                        // echo $applicant; exit;
                        $applicant_info = $this->db->get_where('application', array('id'=> $applicant))->row();
                        $entry=$applicant_info->entry_category;
                         // echo $entry; exit;
                        if ($entry == 2) {
                            $data['f6indexno'] = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 2))->row()->index_number;
                        } else if ($entry   == 4) {
                            $data['f6indexno'] = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 4))->row()->avn;

                        }

                        if($entry==2)
                        {
                            $category="A";
                        }elseif($entry==4)
                        {
                            $category="D";
                        }
                        // $data['f6indexno'] = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant, 'certificate' => 2))->row()->index_number;
                        // echo $data['f6indexno']; exit;
                        $data['programChoices'] = $this->db->query("SELECT GROUP_CONCAT(programmecode) as programmes FROM application_elegibility where applicant_id='$applicant' group by applicant_id")->row()->programmes;
                        // echo $data['programChoices']; exit;
                        $data['mobileNumber'] = $applicant_info->Mobile1;

                        //new added field
                        $data['otherMobileNumber'] = $applicant_info->Mobile2;
                        $data['nationality'] = $this->db->get_where('nationality', array('id'=> $applicant_info->Nationality))->row()->name;
                        if(trim($data['nationality'])=='')
                        {
                            $data['nationality']='Tanzanian';
                        }
                        $data['impairment'] = $this->db->get_where('disability', array('id'=> $applicant_info->Disability))->row()->name;
                        $data['dateOfbirth'] = $applicant_info->dob;

                        // echo $data['mobileNumber']; exit;
                        $data['emailAddress'] = $applicant_info->Email;
                        // echo $data['emailAddress']; exit;
                        $data['AdmissionStatus'] = $row['C'];
                        // echo $data['AdmissionStatus']; exit;
                        $data['AdmittedProgramme'] = $row['D'];
                        // echo $data['AdmittedProgramme']; exit;
                        $data['Reason'] = $row['E'];
                        // echo $data['Reason']; exit;
                        $data['round'] = $row['F'];

                        $data_to_import = array(
                            'f4index' => $data['F4INDEX'],
                            'f6index' => $data['f6indexno'],
                            'programmechoices' => $data['programChoices'],
                            'mobile' => $data['mobileNumber'],
                            'email' => $data['emailAddress'],
                            'admissionstatus' => $row['C'],
                            'admittedprogramme' => $row['D'],
                            'reason' => $row['E'],
                            'round' => $row['F']

                        );

                        $import = $this->db->insert('tcu_admitted', $data_to_import);

                        if ($import) {
                            $selectoradmit = $this->input->post('selectoradmitted');
                            $round = $this->input->post('round');
                            if ($selectoradmit == 1) {
                                $other_f4indexno = $row['G'];
                                $other_f6indexno = $row['H'];
                                if($round == 2){
                                    $other_f4indexno = $row['G'];
                                    $other_f6indexno = $row['H'];
                                    $xml_data = ResubmitApplicantDetailsRequest(TCU_USERNAME, TCU_TOKEN, $data['F4INDEX'], $data['f6indexno'],
                                        $data['programChoices'], $data['mobileNumber'],$data['otherMobileNumber'], $data['emailAddress'],$category,$row['C'], $row['D'], $row['E'],$data['nationality'],$data['impairment'],$data['dateOfbirth'], $other_f4indexno, $other_f6indexno);
                                    $sendRequest = sendXmlOverPost('http://api.tcu.go.tz/applicants/resubmit', $xml_data);
                                }else{
                                    $xml_data = SubmitApplicantProgramChoicesRequest(TCU_USERNAME, TCU_TOKEN,  $data['F4INDEX'], $data['f6indexno'],
                                        $data['programChoices'], $data['mobileNumber'],$data['otherMobileNumber'], $data['emailAddress'],$category, $row['C'], $row['D'],$row['E'],$data['nationality'],$data['impairment'],$data['dateOfbirth'],$other_f4indexno,$other_f6indexno);
                                    $sendRequest = sendXmlOverPost('http://api.tcu.go.tz/applicants/submitProgramme', $xml_data);
                                }
                                $responce=RetunMessageString($sendRequest,'ResponseParameters');
                                $data = simplexml_load_string($responce);
                                $json = json_encode($data);
                                $array_data = json_decode($json,TRUE);
                                $tcu_response = $array_data['StatusCode'];
                                $f4index = $array_data['f4indexno'];
                                $status = $array_data['StatusCode'];
                                $description = $array_data['StatusDescription'];;
                                if($status==200){
                                    $tcu_status=$this->db->query("update application set tcu_status=2,tcu_status_description='Choices Submitted' where id=". $applicant);
                                    $update = array(
                                        'status' => 1,
                                        'description' => $description,
                                        'response' => $sendRequest,
                                    );
                                    $this->db->where('f4index', $f4index);
                                    $this->db->update('tcu_admitted', $update);
                                    $this->session->set_flashdata('message', show_alert('Member Imported successfully!!', 'success'));
                                    // unlink('./uploads/temp/' . $dest_name);

                                }else{
                                    $update = array(
                                        'status' => 0,
                                        'description' => $description,
                                        'response' => $sendRequest,
                                    );
                                    $this->db->where('f4index', $data['F4INDEX']);
                                    $this->db->update('tcu_admitted', $update);
                                    $this->session->set_flashdata('message', show_alert('Member Imported successfully!!', 'success'));


                                }

                            }
                        }

                    }
                    //delete excell here
                    unlink('./uploads/temp/' . $dest_name);
                }
        }
        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Import');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Import Admitted students');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/import';
        $this->load->view('template', $this->data);
    }


    function populate_dashboard()
    {

        // echo "hapa nafika";exit;
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $excel_upload = TRUE;
        $upload_error = '';


        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        // $this->form_validation->set_rules('course','Course', 'required');


        if ($this->form_validation->run() == true) {
            // echo "hapa nafika";exit;

            //$code_course = $this->input->post('course');
            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload excel file.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'xlsx' && get_file_extension($_FILES['userfile']['name']) != 'xls')) {
                $upload_error = '<div class="required">File uploaded must be in xls or xlxs format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {
                $dest_name = time() . 'result_sheet.xlsx';
                move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/temp/' . $dest_name);
                // set file path
                $file = './uploads/temp/' . $dest_name;
                //load the excel library
                $this->load->library('excel');
                //read file from path
                $objPHPExcel = IOFactory::load($file);
                //get only the Cell Collection
                // echo "hapa nafika"; exit;
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = trim($data_value);
                    } else {
                        $arr_data[$row][$column] = trim($data_value);
                    }
                }

                foreach ($arr_data as $row) {
                    $data = array();
                    $courseError = array();
                    if (trim($row['A']) <> '') {
                        $data['programcode'] = $row['A'];
                        $data['male'] = $row['B'];
                        $data['female'] = $row['C'];
                       echo $xml_data = PopulateDashboardRequest(TCU_USERNAME, TCU_TOKEN,$data['programcode'],$data['male'],$data['female']);
                        echo  $sendRequest = sendXmlOverPost('http://api.tcu.go.tz/dashboard/populate', $xml_data);

                    }
                    //delete excell here
                    unlink('./uploads/temp/' . $dest_name);
                }
                $this->session->set_flashdata('message', show_alert('Submitted Summary  successfully imported!!', 'success'));



            }
        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Populate Dashboard');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Submited to TCU summary');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/populate_dashboard';
        $this->load->view('template', $this->data);


    }



    function download_admitted_pplicant()
    {
        $username = "UDSM";
        $token = "xfcgvbbjbhn";
        $institution = "ZTL";
        $programme = 1001;
        $xml_data = GetAdmittedApplicantRequest($username, $token, $programme);
        $sendRequest = sendXmlOverPost('http://127.0.0.1/tcu/GetAdmittedApplicant_server.php', $xml_data);
        $data = simplexml_load_string($sendRequest);
        for($i=0; $i<count($data->ResponseParameters->Applicant); $i++)
        {
            echo "<br>";
            echo $data->ResponseParameters->Applicant[$i]->f4indexno;
            echo $data->ResponseParameters->Applicant[$i]->f6indexno;
            echo $data->ResponseParameters->Applicant[$i]->MobileNumber;
            echo $data->ResponseParameters->Applicant[$i]->EmailaAdress;
            echo $data->ResponseParameters->Applicant[$i]->AdmissionStatus;
        }


    }


    function getprogrammeswithcandidates()
    {
        $username = "UDSM";
        $token = "xfcgvbbjbhn";
        $institution = "ZTL";
        $xml_data = GetProgrammesWithAdmittedCandidatesRequest($username, $token, $institution);
        $sendRequest = sendXmlOverPost('http://127.0.0.1/tcu/GetProgrammesWithAdmittedCandidates_server.php', $xml_data);
        echo $sendRequest;exit;
        $data = simplexml_load_string($sendRequest);

        echo $data->Response->ResponseParameters->Programmes;
        exit;
    }


    function applicantReports()
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_list')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_LIST :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_list/', 'title' => 'Applicant List');

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';

        if (isset($_GET['entry']) && $_GET['entry'] != '') {
            $where .= " AND entry_category='" . $_GET['entry'] . "' ";
        }

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND application_type='" . $_GET['type'] . "' ";
        }

        if (isset($_GET['status']) && $_GET['status'] != '') {
            $where .= " AND submitted='" . $_GET['status'] . "' ";
        }

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (form4_index LIKE '%" . $_GET['key'] . "%' OR  form6_index LIKE '%" . $_GET['key'] . "%' OR FirstName LIKE '%" . $_GET['key'] . "%' OR  LastName LIKE '%" . $_GET['key'] . "%')";
        }


        $sql = " SELECT * FROM application  $where ";
        $sql2 = " SELECT count(id) as counter FROM application  $where ";

        $config["base_url"] = site_url('applicant_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY FirstName ASC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/applicantReports';
        $this->load->view('template', $this->data);
    }

    function applicant_status_admited_multiple($program_code)
    {
        $xml_data = GetApplicantVerificationStatus(TCU_USERNAME, TCU_TOKEN,$program_code);
        $response_data = sendXmlOverPost('http://api.tcu.go.tz/applicants/getApplicantVerificationStatus', $xml_data);
        $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);
        if($joson_data['Response']['ResponseParameters']['StatusCode']==200) {
            $applicants=$joson_data['Response']['ResponseParameters']['Applicant'];

            for ($i = 0; $i < count($applicants); $i++) {
                $applicant_id = $this->db->get_where('application_education_authority', array('index_number' => $applicants[$i]['f4indexno']))->row()->applicant_id;
                if($applicants[$i]['AdmissionStatus']=='Multiple Admission')
                {
                    //has multiple selection
                    $where_array=array(
                        'id'=>$applicant_id,
                        'tcu_status !='=>4
                    );

                    $data=array(
                        'tcu_status'=>4,
                        "tcu_status_description"=>$applicants[$i]['AdmissionStatus']
                    );

                    $this->db->where($where_array);
                    $this->db->update('application',$data);


                }elseif($applicants[$i]['AdmissionStatus']=='Qualified')
                {
                    //has a single selection
                    $where_array=array(
                        'id'=>$applicant_id,
                        'tcu_status !='=>3
                    );

                    $where=array(
                        'Code'=>$program_code
                    );
                    $data=array(
                        'tcu_status'=>3,
                        "tcu_status_description"=>get_value('programme',$where,'Name')
                    );

                    $this->db->where($where_array);
                    $this->db->update('application',$data);

                }else{
                    //Not qualify
                    //has a single selection
                    $where_array=array(
                        'id'=>$applicant_id,
                    );

                    $data=array(
                        'tcu_status'=>7,
                        'tcu_status_description'=>$applicants[$i]['AdmissionStatus']
                    );

                    $this->db->where($where_array);
                    $this->db->update('application',$data);
                }

            }


        }




    }


    function applicant_reports()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        $this->form_validation->set_rules('status','Status', 'required');

        if ($this->form_validation->run() == true) {
            $programme = $this->input->post('programme');
            execInBackground("panel applicant_status_admited_multiple ".$programme);

            // echo $programme;exit;
            $status = $this->input->post('status');
            if($status == 1)
            {
                //confirmed else whrere
                $xml_data = GetListOfConfirmedApplicantsRequest(TCU_USERNAME, TCU_TOKEN,$programme);
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/applicants/getConfirmed', $xml_data);
                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);

                //print_r($joson_data);
                //exit;
                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else
                {
                    $applicants=$joson_data['Response']['ResponseParameters']['Applicant'];
                    include_once 'report/listofconfirmed.php';
                    exit;
                }

            }
            else if ($status==2)
            {
                //confirmed and admited
                $xml_data = GetAdmittedApplicantRequest(TCU_USERNAME, TCU_TOKEN,$programme);
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/admission/getAdmitted', $xml_data);


                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);
                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else {
                    $applicants = $joson_data['Response']['ResponseParameters']['Applicant'];
                    include_once 'report/admittedcandidates.php';
                    exit;
                }
            }
            else if ($status==3)
            {

                //who have multiple selection and single selection
                $xml_data = GetApplicantsAdmissionStatusRequest(TCU_USERNAME, TCU_TOKEN, $programme);
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/applicants/getStatus', $xml_data);
                // var_dump($response_data);exit;
                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);
                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else {
                    $applicants = $joson_data['Response']['ResponseParameters']['Applicant'];
                    include_once 'report/admissionstatus.php';
                    exit;
                }
            }else if ($status==4)
            {
                //programe with no of admitted applicants
                $xml_data = GetProgrammesWithAdmittedCandidatesRequest(TCU_USERNAME, TCU_TOKEN);
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/admission/getProgrammes', $xml_data);
                // var_dump($response_data);exit;
                //$responce=RetunMessageString($response_data,'ResponseParameters');

                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);
                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else {
                    $applicants = $joson_data['Response']['ResponseParameters']['Programme'];
                    include_once 'report/programmeswithadmittedcandidated.php';
                    exit;
                }
            }

        }

//        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant Reports');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Get Applicants Reports');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['programme_list'] = $this->common_model->get_programme(null, $type=2)->result();
        $this->data['content'] = 'panel/applicant_reports';
        $this->load->view('template', $this->data);
    }

    public function receive_payments()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Receive payments');
        $this->data['bscrum'][] = array('link' => 'Existing Member/', 'title' => 'Application Payments');

        $this->form_validation->set_rules('reference', 'Reference Number', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');

        if ($this->form_validation->run() == true) {
            $variablereference = $this->input->post('reference');
            $applicant = substr($variablereference, 3);
            $unique = $this->db->query("select * from application where id='$applicant'")->row()->user_id;
            if (is_null($unique)) {
                $this->session->set_flashdata('message', show_alert('Application with this Reference Number does not exist, check it carefully and try again!', 'warning'));
                redirect('reveive_payments', 'refresh');
            } else {
                $data = array(
                  // 'msisdn' => '255656121885',
                  'reference' => trim($this->input->post('reference')),
                  'applicant_id' => $applicant,
                  'timestamp' => date('Y-m-d H:i:s'),
                  'receipt' => generatePIN(6),
                  'amount' => trim($this->input->post('amount')),
                  'createdon' => date('Y-m-d H:i:s'),
                  // 'AYear' => '2018'
                );
                $save = $this->db->insert('application_payment', $data);
                if ($save) {
                    $this->session->set_flashdata('message', show_alert('Information Saved successfully', 'success'));
                    redirect('receive_payments', 'refresh');
                }else{
                  $this->session->set_flashdata('message', show_alert('Failed to save information, Try again', 'warning'));
                  redirect('receive_payments', 'refresh');
                }

          }
        }

        $this->data['active_menu'] = 'feesetup';
        $this->data['content'] = 'panel/receive_payments';
        $this->load->view('template', $this->data);
    }


    function applicant_transfers()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        // validate form input
        $this->form_validation->set_rules('o_level_index_no', 'Form Four Index', 'required');
        $this->form_validation->set_rules('a_level_index_no', 'Form six  Index', 'required');
        $this->form_validation->set_rules('prev_prog_code', 'Previous Programme Code', 'required');
        $this->form_validation->set_rules('current_prog_code', 'Current Programme Code', 'required');

        $this->form_validation->set_rules('status','Transfer Type', 'required');

        if ($this->form_validation->run() == true) {
            $f4indexno = trim($this->input->post('o_level_index_no'));
            $f6indexno = trim($this->input->post('a_level_index_no'));
            $curProCode = trim($this->input->post('current_prog_code'));
            $prevProgCode = trim($this->input->post('prev_prog_code'));
            $status = $this->input->post('status');
            if($status == 1)
            {
                $xml_data = SubmitInternalTransferRequest(TCU_USERNAME, TCU_TOKEN,$f4indexno,$f6indexno,$prevProgCode,$curProCode);
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/admission/submitInternalTransfers', $xml_data);
                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);

                $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';

                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else {
                    $this->session->set_flashdata('message', show_alert($error_message, 'info'));
                }
            }
            else if ($status==2)
            {
                $xml_data = SubmitInterIstitutionalTransferRequest(TCU_USERNAME, TCU_TOKEN,$f4indexno,$f6indexno,$prevProgCode,$curProCode);
                // var_dump($xml_data);exit;
                $response_data = sendXmlOverPost('http://api.tcu.go.tz/admission/submitInterInstitutionalTransfers', $xml_data);
                // var_dump($response_data);exit;
                $joson_data=json_decode(json_encode(simplexml_load_string($response_data)),true);
                if($joson_data['Response']['ResponseParameters']['StatusCode']!=200)
                {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'warning'));
                }else {
                    $error_message=$joson_data['Response']['ResponseParameters']['StatusDescription'].'('.$joson_data['Response']['ResponseParameters']['StatusCode'].')';
                    $this->session->set_flashdata('message', show_alert($error_message, 'info'));
                }
            }


        }

//        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant Transfers');
        $this->data['bscrum'][] = array('link' => 'applicant_transfers/', 'title' => 'TCU Transfers');

        $this->data['active_menu'] = 'applicant_list';
        $this->data['programme_list'] = $this->common_model->get_programme(null, $type=2)->result();
        $this->data['content'] = 'panel/applicant_transfers';
        $this->load->view('template', $this->data);
    }

}
