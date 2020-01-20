<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:17 AM
 */
class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Home';
        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

    }

    function index()
    {

        $this->data['content'] = 'auth/login';
        $this->load->view('public_template', $this->data);
    }


    function registration_start()
    {
        $this->data['content'] = 'home/registration_start';
        $this->load->view('public_template', $this->data);
    }


    function application_start()
    {
        if (isset($_GET) && isset($_GET['type']) && isset($_GET['entry']) && isset($_GET['NT']) && isset($_GET['CSEE'])) {
            $this->data['type'] = $application_type = $_GET['type'];
            $this->data['entry'] = $entry_category = $_GET['entry'];
            $this->data['CSEE'] = $CSEE = $_GET['CSEE'];
            $this->data['NT'] = $NT = $_GET['NT'];

            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('dob', 'Birth Date', 'required|valid_date');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required');
            $this->form_validation->set_rules('disability', 'Disability', 'required');
            $this->form_validation->set_rules('birth_place', 'Place of Birth', 'required');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
            $this->form_validation->set_rules('residence_country', 'Country of Residence', 'required');
            $this->form_validation->set_rules('o_completed_year', 'O level Completed Year', 'required|regex_match[/^[0-9]{4}$/]');


            if($entry_category==1 ||  $entry_category==2 || $entry_category==4)
            {
                $this->form_validation->set_rules('o_level_index_no', 'O level Index Number', 'required');
                if($entry_category==2)
                {
                    $this->form_validation->set_rules('school', 'A level School', 'required');
                    $this->form_validation->set_rules('a_level_index_no', 'A level Index Number', 'required');
                }elseif($entry_category==4)
                {
                    $this->form_validation->set_rules('institution', 'Institution', 'required');
                    $this->form_validation->set_rules('avn', 'NACTE Award Verification Number', 'required');

                }
            }

            /* if($application_type > 2){
                $this->form_validation->set_rules('form4_index', ($application_type == 3 ? 'Certificate ' : 'Form IV ').' Index No', 'required|is_unique[application.form4_index]|is_unique[users.username]');
            }else{
                $this->form_validation->set_rules('form4_index', ($application_type == 3 ? 'Certificate ' : 'Form IV ').' Index No', 'required|valid_indexNo|is_unique[application.form4_index]|is_unique[users.username]');
            }

            if ($entry_category == 2) {
                $this->form_validation->set_rules('form6_index', 'Form VI Index', 'required|is_unique[application.form6_index]');
            } else if ($entry_category == 3) {
                $this->form_validation->set_rules('diploma_number', 'Certificate Number', 'required');
            } else if ($entry_category == 4) {
                $this->form_validation->set_rules('diploma_number', 'Diploma Number', 'required');
            }
            */
//            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[application.email]|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[conf_password]');
            $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required');
            $this->form_validation->set_rules('capture', 'Capture', 'required');

            $capture = $this->session->userdata('capture_code');
            $capture2 = $this->input->post('capture');
            $required = false;
            if ($this->input->post('capture')) {
                if ($capture2 == $capture) {
                    $required = true;
                } else {
                    $required = false;
                    $this->data['message'] = show_alert('Invalid value in capture, Please enter correct value', 'warning');
                    //$this->form_validation->set_rules('capture','Capture','');
                }
            }

            if($required==true and ($entry_category==2 or $entry_category==4) )
            {

                if (trim($this->input->post('olevel_name'))!= trim($this->input->post('alevel_name'))) {
                    $required = false;
                    if($entry_category==2){
                        $this->data['message'] = show_alert('O level and A level names mismatch', 'warning');
                    }else
                    {
                        $this->data['message'] = show_alert('O level and Diploma names mismatch', 'warning');

                    }
                }

            }


            if ($this->form_validation->run() == true && ($required == true)) {
                if($application_type==2)
                {
                    $index_number_check=trim($this->input->post('o_level_index_no'));
                    $xml=CheckSingleApplicantStatusRequest(TCU_USERNAME,TCU_TOKEN,$index_number_check);
                    $url=TCU_DOMAIN."/applicants/checkStatus";
                    $responce=sendXmlOverPost($url,$xml);
                    $responce=RetunMessageString($responce,'ResponseParameters');
                    $xml=simplexml_load_string( $responce);
                    $json = json_encode($xml);
                    $array = json_decode($json,TRUE);
                    if($array['StatusCode']==203)
                    {

                        echo "Sorry you can't  make admission at moment because you have already admitted  in current admission cycle";
                        //$this->data['message']="Sorry you can't  make admission at moment because you have already admitted  in current admission cycle";
                        exit;

                    }

                    /*print_r($array);
                    echo"description=".$array['StatusDescription'];
                    echo"<br/><br/>";
                    print_r($responce);*/

                }

                $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
                if ($row_year) {
                    $array_data = array(
                        'AYear' => $row_year->AYear,
                        'Semester' => $row_year->semester,
                        'application_type' => $application_type,
                        'entry_category' => $entry_category,
                        'CSEE' => $CSEE,
                        'NT' => $NT,
                        'duration' => programme_duration($application_type, $entry_category),
                        'FirstName' => ucfirst(strtolower(trim($this->input->post('firstname')))),
                        'MiddleName' => ucfirst(strtolower(trim($this->input->post('middlename')))),
                        'LastName' => ucfirst(strtolower(trim($this->input->post('lastname')))),
                        'Email' => strtolower(trim($this->input->post('email'))),
                        'Gender' => trim($this->input->post('gender')),
                        'Disability' => trim($this->input->post('disability')),
                        'Nationality' => trim($this->input->post('nationality')),
                        'birth_place' => trim($this->input->post('birth_place')),
                        'marital_status' => trim($this->input->post('marital_status')),
                        'residence_country' => trim($this->input->post('residence_country')),
                        'dob' => format_date(trim($this->input->post('dob'))),
                        'createdon' => date('Y-m-d H:i:s'),

                    );
                    if($entry_category == 1 || $entry_category == 2 || $entry_category == 4)
                    {
                        $array_data['form4_index'] = trim($this->input->post('o_level_index_no'));
                        if( $entry_category == 2)
                        {
                            $array_data['form6_index'] = trim($this->input->post('a_level_index_no'));
                        }
                        if( $entry_category == 4)
                        {
                            $array_data['diploma_number'] = trim($this->input->post('avn'));
                        }

                    }
                        /* if ($entry_category == 2) {
                         $array_data['form6_index'] = trim($this->input->post('form6_index'));
                     } else if ($entry_category == 3 || $entry_category == 4) {
                         $array_data['diploma_number'] = trim($this->input->post('diploma_number'));
                     }*/
                    $username = trim($this->input->post('username'));
                    $array_data['username'] = $username;
                    $register = $this->applicant_model->new_applicant($array_data, trim($this->input->post('password')));
                    if ($register) {
                        $this->ion_auth_model->login($username, trim($this->input->post('password')), true);




                        $this->session->set_flashdata('message', show_alert('Information saved successfully, Please add Contact Information !!', 'success'));
                        redirect('applicant_contact', 'refresh');
                    } else {
                        $this->data['message'] = show_alert("Fail to save Information, Please try again !!", 'info');
                    }
                } else {
                    $this->data['message'] = show_alert("Configuration not set, Please use " . anchor(site_url('contact'), 'this link', 'style="color:red; text-decoration:underline;"') . "  to report", 'info');
                }
            } else {
                $captcha_num = '1234567890';
                $captcha_num = substr(str_shuffle($captcha_num), 0, 6);
                $this->session->set_userdata('capture_code', $captcha_num);
                $this->data['captcha_num'] = $captcha_num;
            }

            $this->data['gender_list'] = $this->common_model->get_gender()->result();
            $this->data['disability_list'] = $this->common_model->get_disability()->result();
            $this->data['nationality_list'] = $this->common_model->get_nationality()->result();
            $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
            $this->data['content'] = 'home/application_start';
            $this->load->view('public_template', $this->data);
        } else {
            redirect('application_start', 'refresh');
        }
    }


    function capture($code)
    {
//        $captcha_num = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        //      $captcha_num = substr(str_shuffle($captcha_num), 0, 6);
        $captcha_num = $code;
        //$_SESSION['capture_code']=$captcha_num;
        $font_size = 20;
        $img_width = 300;
        $img_height = 50;

        header('Content-type: image/jpeg');
        $image = imagecreate($img_width, $img_height); // create background image with dimensions
        imagecolorallocate($image, 255, 255, 255); // set background color
        $text_color = imagecolorallocate($image, 0, 0, 0); // set captcha text color
        imagettftext($image, $font_size, 0, 15, 30, $text_color, './media/NIT.ttf', $captcha_num);
        imagejpeg($image);

    }


    function recommendation()
    {
        if (isset($_GET) && isset($_GET['key']) && isset($_GET['referee_id']) && isset($_GET['code'])) {
            $applicant_id = decode_id($_GET['key']);
            $referee_id = decode_id($_GET['referee_id']);
            $rec_code = $_GET['code'];
            $applicant_info = $this->data['APPLICANT'] = $this->db->where('id', $applicant_id)->get('application')->row();
            $referee_info = $this->data['REFEREE'] = $this->db->where('id', $referee_id)->get('application_referee')->row();
            if ($applicant_info && $referee_info && $applicant_info->id == $referee_info->applicant_id && $rec_code == $referee_info->rec_code) {
                $this->data['recommendation_area'] = $recommendation_area= $this->common_model->get_recommendation_area()->result();

                $this->form_validation->set_rules('recommend_overall', 'above', 'required');
                $this->form_validation->set_rules('applicant_known', 'above', 'required');
                $this->form_validation->set_rules('weakness', 'above', 'required');
                $this->form_validation->set_rules('description_for_qn3', 'above', 'required');
                $this->form_validation->set_rules('other_degree', 'above', 'required');
                $this->form_validation->set_rules('producing_org_work', 'above', 'required');
                 $recommendation_area_value = array();
                foreach ($recommendation_area as $rec_key => $rec_value) {
                    $this->form_validation->set_rules('recommend_'.$rec_value->id, $rec_value->name, 'required');
                    $recommendation_area_value[$rec_value->id] = trim($this->input->post('recommend_'.$rec_value->id));
                }

                if ($this->form_validation->run() == true) {

                    $array = array(
                        'applicant_id'=>$applicant_id,
                        'referee_id' =>$referee_id,
                        'recommend_overall'=>trim($this->input->post('recommend_overall')),
                        'applicant_known'=>trim($this->input->post('applicant_known')),
                        'description_for_qn3'=>trim($this->input->post('description_for_qn3')),
                        'weakness'=>trim($this->input->post('weakness')),
                        'other_degree'=>trim($this->input->post('other_degree')),
                        'producing_org_work'=>trim($this->input->post('producing_org_work')),
                        'other_capability'=>trim($this->input->post('other_capability')),
                        'anything'=>trim($this->input->post('anything')),
                        'recommendation_arrea'=>json_encode($recommendation_area_value)
                    );

                     $record_recommendation = $this->applicant_model->record_referee_recomendation($array);
                     if($record_recommendation){
    $this->session->set_flashdata('message',show_alert('Recommendation saved successfully','success'));
                         redirect(current_full_url(),'refresh');
                     }else{
                         $this->data['message'] = show_alert('Fail to save recomendation, Please try again later','warning');
                     }

                }


             $row_data = $this->applicant_model->get_referee_recomendation($applicant_id,$referee_id)->row();
                if($row_data){
                    $this->data['recommendation_info'] = $row_data;
                }


                $this->data['allowed'] = 1;
            } else {
                $this->data['allowed'] = 0;
            }
        } else {
            $this->data['allowed'] = 0;
        }

        $this->data['applicant_dashboard'] = 'applicant_dashboard';
        $this->data['content'] = 'home/recommendation';
        $this->load->view('public_template', $this->data);
    }


        function loadEducationData()
        {

            $action = trim($this->input->post('action'));
            if($action=='o-level')
            {
                $completed_year="";
                $target=trim($this->input->post('target'));
                $o_index_number=trim($this->input->post('id'));
                $where_array=array('index_number'=>$o_index_number,'api_status'=>1);
                $education_row = $this->db->where($where_array)->get('application_education_authority')->row();
                $check_equivalent = substr($o_index_number, 0,2);
                if($education_row)
                {
                    $response=$education_row->response;
                    $completed_year=$education_row->completed_year;
                    $responsedata =  json_decode($response);
                    $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                    echo $responsedata->particulars->first_name.'_'.$responsedata->particulars->last_name.'_'.$responsedata->particulars->middle_name.'_'.$completed_year.'_'.$full_name;
                }else{
                    if($check_equivalent=="EQ" and $target=='') {
                        echo "EQ";
                        exit;
                    }elseif($target!=''){
                         $completed_year=$target;

                    }
                    $this->curl->create(NECTA_API . 'auth/' . NECTA_KEY);
                    $response_token = $this->curl->execute();
                    if ($response_token) {
                        if($check_equivalent=="EQ")
                        {
                            $index_number=$o_index_number;

                        }else{
                            $index_number_tmp = explode('/', $o_index_number);
                            $index_number = $index_number_tmp[0] . '-' . $index_number_tmp[1];
                            $completed_year=$index_number_tmp[2];
                        }

                        $responsedata_key = json_decode($response_token);
                        $this->curl->create(NECTA_API . 'results/' . $index_number . '/1/' . $completed_year . '/' . $responsedata_key->token);
                        $response = $this->curl->execute();
                        if ($response) {
                            $responsedata = json_decode($response);
                            if ($responsedata->status->code == 1) {
                                $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                                echo $responsedata->particulars->first_name . '_' . $responsedata->particulars->last_name . '_' . $responsedata->particulars->middle_name.'_'.$completed_year.'_'.$full_name;
                                $data = array(
                                    'certificate' => 1,
                                    'exam_authority' => 'NECTA',
                                    'index_number' => $o_index_number,
                                    'applicant_id' => 0,
                                    'response' => $response,
                                    'center_number' => $responsedata->particulars->center_number,
                                    'school' => $responsedata->particulars->center_name,
                                    'division' => $responsedata->results->division->division,
                                    'country' =>220,
                                    'division_point' => $responsedata->results->division->points,
                                    'api_status' => 1,
                                    'createdby' => 0,
                                    'comment' => 'Success',
                                    'createdon' => date('Y-m-d H:i:s'),
                                    'completed_year' => $index_number_tmp[2],
                                    'hide'=>1
                                );
                                $check_if_exist = $this->db->where('index_number',$o_index_number)->get('application_education_authority')->row();
                                if($check_if_exist)
                                {
                                    $data = array(
                                        'response' => $response,
                                        'api_status' => 1,
                                        'comment' => 'Success',
                                        'hide'=>1
                                    );

                                    $this->db->update('application_education_authority', $data, array('index_number'=>trim($o_index_number)));
                                }else {
                                    $this->db->insert("application_education_authority", $data);
                                }
                            }else
                            {
                                $this->curl->create(NECTA_API . 'particulars/' . $index_number . '/1/' . $completed_year . '/' . $responsedata_key->token);
                                $response = $this->curl->execute();
                                $responsedata = json_decode($response);
                                if ($responsedata->status->code == 1) {
                                    $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                                    echo $responsedata->particulars->first_name . '_' . $responsedata->particulars->last_name . '_' . $responsedata->particulars->middle_name.'_'.$completed_year.'_'.$full_name;
                                    //okay
                                }

                            }
                        }
                    }

                }

            }elseif($action=='a-level'){
                $completed_year="";
                $target=trim($this->input->post('target'));
                $a_index_number=trim($this->input->post('id'));
                $where_array=array('index_number'=>$a_index_number,'api_status'=>1);
                $education_row = $this->db->where($where_array)->get('application_education_authority')->row();
                $check_equivalent = substr($a_index_number, 0,2);
                if($education_row)
                {
                    $response=$education_row->response;
                    $responsedata =  json_decode($response);
                    $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                    if($check_equivalent=="EQ")
                    {
                        echo 'EQUIVALENT'.'_'.$full_name.'_'.$education_row->completed_year;
                    }else
                        echo $responsedata->particulars->center_name.'_'.$full_name.'_'.$education_row->completed_year;
                }else{
                    if($check_equivalent=="EQ" and $target=='') {
                        echo "EQ";
                        exit;
                    }elseif($target!='' and $check_equivalent=="EQ"){
                         $completed_year=$target;

                    }

                    $this->curl->create(NECTA_API . 'auth/' . NECTA_KEY);

                    $response_token = $this->curl->execute();

                    if ($response_token) {

                        if($check_equivalent=="EQ")
                        {
                             $index_number=$a_index_number;

                        }else{
                            $index_number_tmp = explode('/', $a_index_number);
                            $index_number = $index_number_tmp[0] . '-' . $index_number_tmp[1];
                            $completed_year=$index_number_tmp[2];
                        }


                        $responsedata_key = json_decode($response_token);
                        $this->curl->create(NECTA_API . 'results/' . $index_number . '/2/' . $completed_year . '/' . $responsedata_key->token);
                        $response = $this->curl->execute();
                        if ($response) {
                            $responsedata = json_decode($response);
                            if ($responsedata->status->code == 1) {
                                $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                                if($check_equivalent=="EQ") {
                                    echo 'EQUIVALENT'.'_'.$full_name.'_'.$completed_year;
                                }else
                                    echo $responsedata->particulars->center_name.'_'.$full_name.'_'.$completed_year;
                                $data = array(
                                    'certificate' => 2,
                                    'exam_authority' => 'NECTA',
                                    'index_number' => $a_index_number,
                                    'applicant_id' => 0,
                                    'response' => $response,
                                    'center_number' => $responsedata->particulars->center_number,
                                    'school' => $responsedata->particulars->center_name,
                                    'division' => $responsedata->results->division->division,
                                    'country' => 220,
                                    'division_point' => $responsedata->results->division->points,
                                    'api_status' => 1,
                                    'createdby' => 0,
                                    'comment' => 'Success',
                                    'createdon' => date('Y-m-d H:i:s'),
                                    'completed_year' => $index_number_tmp[2],
                                    'hide'=>1
                                );

                                $check_if_exist = $this->db->where('index_number',$a_index_number)->get('application_education_authority')->row();
                                if($check_if_exist)
                                {
                                    $data = array(
                                        'response' => $response,
                                        'api_status' => 1,
                                        'comment' => 'Success',
                                        'hide'=>1
                                    );
                                    $this->db->update('application_education_authority', $data, array('index_number'=>$a_index_number));

                                }else {
                                    $this->db->insert("application_education_authority", $data);
                                }
                            }else{
                                $this->curl->create(NECTA_API . 'particulars/' . $index_number . '/2/' . $completed_year . '/' . $responsedata_key->token);
                                $response = $this->curl->execute();
                                $responsedata = json_decode($response);
                                if ($responsedata->status->code == 1) {
                                    $full_name=$responsedata->particulars->first_name.$responsedata->particulars->last_name;
                                    if($check_equivalent=="EQ")
                                    {
                                        echo"EQUIVALENT_".$full_name.'_'.$completed_year;
                                    }else
                                        echo $responsedata->particulars->center_name.'_'.$full_name.'_'.$completed_year;
                                    //okay
                                }
                            }

                        }
                    }

                }


            }elseif($action=='avn')
            {
                //
                $avn=trim($this->input->post('id'));
                $where_array=array('avn'=>$avn,'api_status'=>1);
                $education_row = $this->db->where($where_array)->get('application_education_authority')->row();
                if($education_row)
                {
                    $response=$education_row->response;
                    $responsedata =  json_decode($response);
                    $full_name=$responsedata->params[0]->firstname.$responsedata->params[0]->surname;
                    echo $responsedata->params[0]->institution.'_'.$full_name;
                }else{
                        $this->curl->create(NACTE_API . NACTE_API_KEY . '/' . NACTE_TOKEN . '/' . NACTE_API_EXTRA . '/' . $avn);
                        $response = $this->curl->execute();
                        if ($response) {
                            $responsedata = json_decode($response);
                            if ($responsedata->status->code == 200)  {
                                $full_name=$responsedata->params[0]->firstname.$responsedata->params[0]->surname;
                                echo $responsedata->params[0]->institution.'_'.$full_name;
                                $data = array(
                                    'certificate' => 4,
                                    'exam_authority' => "NACTE",
                                    'applicant_id' => 0,
                                    'school' => $responsedata->params[0]->institution,
                                    'division' => $responsedata->params[0]->diploma_gpa,
                                    'country' =>220,
                                    'index_number' => $responsedata->params[0]->registration_number,
                                    'createdby' => 0,
                                    'createdon' => date('Y-m-d H:i:s'),
                                    'programme_title' => $responsedata->params[0]->programme,
                                    'programme_category' => $responsedata->params[0]->diploma_category,
                                    'completed_year' => $responsedata->params[0]->diploma_graduation_year,
                                    'avn'=>$avn,
                                    'response'=>$response,
                                    'center_number' => '',
                                    'diploma_code' => $responsedata->params[0]->diploma_code,
                                    'division_point' => '',
                                    'api_status' => 1,
                                    'comment' => 'Success',
                                    'hide'=>1
                                );
                                $check_if_exist = $this->db->where('avn',$avn)->get('application_education_authority')->row();
                                if($check_if_exist)
                                {
                                    $data = array(
                                        'response' => $response,
                                        'api_status' => 1,
                                        'comment' => 'Success',
                                        'hide'=>1
                                    );
                                    $this->db->update('application_education_authority', $data, array('avn' => $avn));

                                }else{
                                    $this->db->insert("application_education_authority", $data);
                                }
                            }

                        }


                }



            }



        }

    function TestApplicant_avn()
    {

        $avn='19NA1019467ME';
        $this->curl->create(NACTE_API . NACTE_API_KEY . '/' . NACTE_TOKEN . '/' . NACTE_API_EXTRA . '/' . $avn);
        $response = $this->curl->execute();
        if ($response) {
            $responsedata = json_decode($response);
            if ($responsedata->status->code == 200)  {
                print_r($responsedata);
                $fullname=$responsedata->params[0]->firstname." ".$responsedata->params[0]->middlename." ".$responsedata->params[0]->surname;
            }

        }

    }


        function TestApplicant_status()
        {

        $o_index_number="EQ2019000874";
        $o_index_number="S0326/0711/2006";

        $check_equivalent = substr($o_index_number, 0,2);
        $this->curl->create(NECTA_API . 'auth/' . NECTA_KEY);
        $response_token = $this->curl->execute();
       // print_r($response_token);
        $responsedata_key = json_decode($response_token);
        $responsedata_key->token;


if($check_equivalent=="EQ")
{
    $index_number=$o_index_number;
    $year= substr($o_index_number, 2,4);
    $year=2016  ;
}else{
    $index_number_tmp = explode('/', $o_index_number);
    $index_number = $index_number_tmp[0] . '-' . $index_number_tmp[1];
    $year=$index_number_tmp[2];
}
echo $index_number .'year='.$year;

        $responsedata_key = json_decode($response_token);
        $this->curl->create(NECTA_API . 'particulars/' . $index_number . '/1/' . $year . '/' . $responsedata_key->token);
        $response = $this->curl->execute();

        print_r($response);
        $particulars_array=json_decode($response,true);

        echo"Full name=". $particulars_array['results ']['first_name']." ".$particulars_array['particulars']['middle_name']." ".$particulars_array['particulars']['last_name'];
        exit;


        $index="S2258/0125/2015";
        echo  $xml=CheckSingleApplicantStatusRequest(TCU_USERNAME,TCU_TOKEN,$index);
           $url=TCU_DOMAIN."/applicants/checkStatus";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string( $responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestAddApplicant()
    {

        $f4index="S4002/0138/2010";
        $f6index="19NA1023888ME";
        $category="D";
        $other_f6="";
        $other_f4="";
        $xml=AddApplicantRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$f6index,$category,$other_f4,$other_f6);
        $url=TCU_DOMAIN."/applicants/add";
        $responce=sendXmlOverPost($url,$xml);
       // print_r($responce);

        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
       echo"json=". $json = json_encode($xml);
       exit;
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestAddApplicantProgramChoice()
    {

        $f4index="S4002/0138/2010";
        $f6index="19NA1023888ME";
        $category="D";
        $other_f6="";
        $other_f4="";
        $selected_program='CFR01';
        $mobilenumber='0773091870';
        $othermobilenumber='0676091870';
        $email='abdulhafidh000@gmail.com';
        $category='D';
        $admission_status='provisional admission';
        $program_admited='CFR01';
        $reason='eligible';
        $nationality='Tanzanian';
        $impairment='none';
        $dob='1992-02-04';
        $xml=SubmitApplicantProgramChoicesRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$f6index,$selected_program,
            $mobilenumber,$othermobilenumber,$email,$category,$admission_status,$program_admited,$reason,$nationality,$impairment,
            $dob,$other_f4,$other_f6);
        $url=TCU_DOMAIN."/applicants/submitProgramme";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);

        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }
    function TestResubmitApplicantDetails()
    {

        $f4index="S2258/0125/2015";
        $f6index="P0134/0535/2018 ";
        $category="A";
        $other_f6="";
        $other_f4="";
        $selected_program='UD023, UD038, UD022';
        $mobilenumber='0712677004';
        $othermobilenumber='0785469198';
        $email='mudi_sam@yahoo.com';
        $category='A';
        $admission_status='provisional admission';
        $program_admited='UD038';
        $reason='eligible';
        $nationality='Tanzanian';
        $impairment='none';
        $dob='1981-03-04';
        $xml=ResubmitApplicantDetailsRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$f6index,$selected_program,
            $mobilenumber,$othermobilenumber,$email,$category,$admission_status,$program_admited,$reason,$nationality,$impairment,
            $dob,$other_f4,$other_f6);
        $url=TCU_DOMAIN."/applicants/resubmit";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);

        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }


    function TestComfirmApplication()
    {

        $f4index="S2258/0125/2015";
        $code='A5267Y';
        echo $xml=ConfirmApplicationRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$code);
        $url=TCU_DOMAIN."/admission/confirm";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);

        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestGetAdmitedApplicant()
    {

        $f4index="S2258/0125/2015";
        $code='A5267Y';
        echo $xml=GetAdmittedApplicantRequest(TCU_USERNAME,TCU_TOKEN,'DM023');
        $url=TCU_DOMAIN."/admission/getAdmitted";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestGetComfirmedApplicant()
    {

        $f4index="S2258/0125/2015";
        $code='A5267Y';
        echo $xml=GetListOfConfirmedApplicantsRequest(TCU_USERNAME,TCU_TOKEN,'DM023');
        $url=TCU_DOMAIN."/applicants/getConfirmed";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestRejectAdmission()
    {

        $f4index="S2258/0125/2015";

        echo $xml=RejectAdmissionRequest(TCU_USERNAME,TCU_TOKEN,$f4index);
        $url=TCU_DOMAIN."/admission/reject";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }

    function TestPopulateDashBoard()
    {

        $f4index="S2258/0125/2015";
        $program='DM038';
        $male=45;
        $female=60;
        echo $xml=PopulateDashboardRequest(TCU_USERNAME,TCU_TOKEN,$program,$male,$female);
        $url=TCU_DOMAIN."/dashboard/populate";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        echo"description=".$array['StatusDescription'];
        echo"<br/><br/>";
        print_r($responce);
        exit;
    }
    function TestGetProgramWithAdmitedApplicant()
    {

        $f4index="S2258/0125/2015 ";
        $program='DM038';
        $male=45;
        $female=60;
        $xml=GetProgrammesWithAdmittedCandidatesRequest(TCU_USERNAME,TCU_TOKEN);
        $url=TCU_DOMAIN."/admission/getProgrammes";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array['Programme']);
        //echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }

    function TestGetApplicantAdmissionStatus()
    {

        $f4index="S2258/0125/2015";
        $program='DM038';
        $male=45;
        $female=60;
        $xml=GetApplicantsAdmissionStatusRequest(TCU_USERNAME,TCU_TOKEN,$program);
        $url=TCU_DOMAIN."/applicants/getStatus";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');

        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }
    function TestGetComfirmationCode()
    {

        $f4index="S0491/0053/2016";
        $program='DM038';
        $male=45;
        $female=60;
          $xml=GetApplicantComfirmationCodeRequest(TCU_USERNAME,TCU_TOKEN,$f4index,'0679020572');
          $url=TCU_DOMAIN."/admission/requestConfirmationCode";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');

        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }

    function TestSubmitInternalTransferRequest()
    {

        $f4index="S2258/0125/2015";
        $f6index="S0137/1000/2005";
        $prev_pro='DM001';
        $cur_pro="DM002";
        $xml=SubmitInternalTransferRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$f6index,$prev_pro,$cur_pro);
        $url=TCU_DOMAIN."/admission/submitInternalTransfers";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }

    function TestSubmitInterInstitutionalTransferRequest()
    {

        $f4index="S2258/0125/2015";
        $f6index="P0134/0535/2018";
        $prev_pro='DM001';
        $cur_pro="DM002";
        $xml=SubmitInterIstitutionalTransferRequest(TCU_USERNAME,TCU_TOKEN,$f4index,$f6index,$prev_pro,$cur_pro);
        $url=TCU_DOMAIN."/admission/submitInterInstitutionalTransfers";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }

    function TestGetApplicantVerificationStatus()
    {
        $program='DM001';
        $xml=GetApplicantVerificationStatus(TCU_USERNAME,TCU_TOKEN,$program);
        $url=TCU_DOMAIN."/applicants/getApplicantVerificationStatus";
        $responce=sendXmlOverPost($url,$xml);
        print_r($responce);
        $responce=RetunMessageString($responce,'ResponseParameters');
        $xml=simplexml_load_string($responce);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        print_r($array);
        echo"description=".$array['StatusDescription'];
        // echo"<br/><br/>";
        // print_r($responce);
        exit;
    }

    function SubmitAllToTCU_ON_Background()
    {
        execInBackground("home SubmitAllToTCU");
        echo "successfully";
    }
        function SubmitAllToTCU()
    {
        $ayear = $this->common_model->get_academic_year()->row()->AYear;

        $submited_applicants=$this->db->query("select * from application where submitted=1 and AYear='$ayear'")->result();
        if($submited_applicants){

            foreach ($submited_applicants as $key=>$value)
            {
                $applicant_id=$value->id;
                $tcu_records=$this->db->query("select * from tcu_records  where  	applicant_id='".$applicant_id."'")->row();

                if($tcu_records)
                {
                    if($tcu_records->error_code==200 or $tcu_records->error_code==208)
                    {
                        continue;
                    }
                }
                if($value->entry_category==2)
                {
                    $f4indexno = $this->db->get_where('application_education_authority', array('applicant_id' =>$applicant_id, 'certificate' => 1))->row()->index_number;
                    $f6indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant_id, 'certificate' => 2))->row()->index_number;
                    $entry_category = "A";
                }elseif($value->entry_category==4)
                {
                    $f4indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant_id, 'certificate' => 1))->row()->index_number;
                    $f6indexno = $this->db->get_where('application_education_authority', array('applicant_id' => $applicant_id, 'certificate' => 4))->row()->avn;
                    $entry_category = "D";
                }

                $xml_data = AddApplicantRequest(TCU_USERNAME, TCU_TOKEN, $f4indexno, $f6indexno, $entry_category, '', '');
                $Response = sendXmlOverPost('http://api.tcu.go.tz/applicants/add', $xml_data);
                $Response_orign= $Response;
                $Response=RetunMessageString($Response,'ResponseParameters');
                $data = simplexml_load_string($Response);
                $json = json_encode($data);
                $json2 = json_encode(simplexml_load_string($Response_orign));
                $array = json_decode($json,TRUE);

                $error_code = $array['StatusCode'];
                $f4index = $f4indexno;
                $status = $array['StatusCode'];
                $description = $array['StatusDescription'];
                $date = date('Y-m-d H:i:s');
                if ($status == 200 || $status == 208) {
                    $request_status = 1;
                    $tcu_status=$this->db->query("update application set tcu_status=1,tcu_status_description='Registered' where id=". $applicant_id);
                } else {
                    $request_status = 0;
                }

                /* $result = str_replace(array("\n", "\r", "\t"), '', $Response);
                 $xml = simplexml_load_string($result);
                 $object = new stdclass();
                 $object = $xml;*/
                //$response_result = json_encode($object);
                $insert = $this->db->query("insert into tcu_records values('','" .$applicant_id . "','Add','$error_code','$f4index','$status','$description','$request_status','$xml_data','$json2','$date')");
                if ($insert) {
                    if($request_status == 1)
                    {
                        $datatoupdate = array(
                            'response' => 1,
                        );
                        $this->db->where('id', $applicant_id);
                        $this->db->update('application', $datatoupdate);
                    }


                }

            }
        }

    }



    function PopulateEducation($indexNumber)
    {

        $where_array=array('index_number'=>$indexNumber,'api_status'=>1);
        $education_row_f4 = $this->db->where($where_array)->get('application_education_authority')->row();

    }


    function student_invoices()
    {
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

                    if($invoice_info->status==0) {
                        $refference=$ega_auth->prefix.$invoice_id;
                        $fee_type=$invoice_info->type;
                        $invoice_student_info=$this->db->query("select * from application where id=".$invoice_info->student_id)->row();
                        $student_name=$invoice_student_info->FirstName. ''.$invoice_student_info->MiddleName. ''.$invoice_student_info->LastName;
                        $student_email=$invoice_student_info->Email;
                        $postdata = array(
                            "customer" => $ega_auth->username,
                            "reference" => $ega_auth->prefix.$invoice_id,
                            "student_name" =>$student_name,
                            "student_id" => $invoice_student_info->id,
                            "student_email"=>$student_email,
                            "student_mobile"=>$invoice_student_info->Mobile1,
                            "GfsCode"=>GetFeeTypeDetails(2)->gfscode,
                            "amount"=>$invoice_info->amount,
                            "type"=>GetFeeTypeDetails(2)->name,
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
                redirect(site_url('student_invoices/'),'refresh');
            }else
            {
                $this->session->set_flashdata("message", show_alert("Please select at  list one invoice", 'danger'));
                redirect(site_url('student_invoices/'),'refresh');
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
                redirect(site_url('student_invoices/'),'refresh');
            }else
            {
                $this->session->set_flashdata("message", show_alert("Please select at  list one invoice", 'danger'));
                redirect(site_url('student_invoices/'),'refresh');
            }

        }


        $where = ' WHERE student_id="'.$_GET['regno'].'" and status<>2 ';


        $sql = " SELECT * FROM invoices  $where ";

        $sql2 = "SELECT count(id) as counter FROM invoices $where ";

        $config["base_url"] = site_url('student_invoices/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;


        $this->data['invoice_list'] = $this->db->query($sql . " ORDER BY invoices.id DESC")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'student_invoices/', 'title' => 'Invoice List');


        $this->data['middle_content'] = 'home/invoicelist2';
        $this->data['sub_link'] = 'invoicelist';
        $this->data['content'] = 'home/home2';
        $this->load->view('public_template_student', $this->data);
    }


    function print_student_receipt($id){
        $id = decode_id($id);

        $payment=$this->db->query("select payment.*,student_name,type from payment left join invoices on invoices.id=payment.invoice_number where payment.id=".$id)->row();
//        if($payment)
//            $invoice=$this->db->query("select * from invoices where control_number=".$payment->control_number)->row();
        // $user=current_user();

        // echo $APPLICANT->user_id;exit;
        if($payment ) {
            include_once 'report/print_receipt.php';
        }else{
            $this->session->set_flashdata('message',show_alert('This request did not pass our security checks.','info'));
            $current_user_group = get_user_group();
            if($current_user_group->id == 4){
                redirect(site_url('dashboard'), 'refresh');
            }else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }
    }

    function print_student_invoice($id){
        $id = decode_id($id);
        $invoice=$this->db->query("select * from invoices where id=".$id)->row();
        $ega_auth=$this->db->query("select * from ega_auth")->row();
        //$user=current_user();

        $payer = $this->db->query("select * from application where id=".$invoice->student_id)->row();
        // echo $APPLICANT->user_id;exit;
        if($invoice) {
            include_once 'report/print_invoice2.php';
        }else{
            $this->session->set_flashdata('message',show_alert('This request did not pass our security checks.','info'));
            $current_user_group = get_user_group();
            if($current_user_group->id == 4){
                redirect(site_url('applicant_dashboard'), 'refresh');
            }else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }
    }

    function print_student_transfer($id){
        $id = decode_id($id);
        $invoice=$this->db->query("select * from invoices where id=".$id)->row();
        $ega_auth=$this->db->query("select * from ega_auth")->row();


        $payer = $this->db->query("select * from application where id=".$invoice->student_id)->row();
        // echo $APPLICANT->user_id;exit;
        if($invoice) {
            include_once 'report/print_transfer2.php';
        }else{
            $this->session->set_flashdata('message',show_alert('This request did not pass our security checks.','info'));
            $current_user_group = get_user_group();
            if($current_user_group->id == 4){
                redirect(site_url('applicant_dashboard'), 'refresh');
            }else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }
    }


    function student_create_invoice()
    {
        $this->form_validation->set_rules('mobile', 'First Name', 'required');
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('surname', 'Surname', 'required');
        $this->form_validation->set_rules('amount', 'Invoice Amount', 'required');
        $this->form_validation->set_rules('txtFeeName', 'Fee Name', 'required');

        if ($this->form_validation->run() == true) {

            $reg_no=trim($this->input->post('regno'));
            $amount=trim($this->input->post('amount'));
            $mobile= '255'.ltrim(trim($this->input->post('mobile')),'0');
            $firstname=trim($this->input->post('firstname'));
            $surname=trim($this->input->post('surname'));
            $othername=trim($this->input->post('othername'));
            $email=trim($this->input->post('email'));
            $address=trim($this->input->post('address'));
            $description=trim($this->input->post('description'));
            $feeid=trim($this->input->post('txtFeeName'));
            $student_fee_array=explode('_',$feeid);
            $student_fee_id=$student_fee_array[0];
            if($reg_no=='')
            {
                $reg_no=time();
            }

//
//            $postdata=array(
//              "regno"=>  $reg_no
//            );
//            $url="http://212.71.252.209/ega/ega-slads/checkregno.php";
//            $data_json= sendDataOverPost($url,$postdata);
//            $data=json_decode($data_json,true);
//            $code=$data['code'];
//            $description=$data['description'];
            if(1==1)
            {
                $name=trim(strtoupper($surname).' '.ucfirst($firstname). ' '.ucfirst($othername));
//                for($i=0;$i<count($txtFeeName);$i++)
//                {
//                    $student_fee_array=explode('_',$txtFeeName[$i]);
//                    $student_fee_id=$student_fee_array[0];
//                    $check_fee_exist=$this->db->query("select * from student_fee where student_regno='$reg_no' and fee_id=".$student_fee_id)->row();
//                    if($check_fee_exist){
//                        $amount=$amount-get_value('fee_structure',$check_fee_exist->fee_id,'amount');
//                    }
//
//                }
//
//                if((int)$amount==0)
//                {
//
//                    $this->session->set_flashdata('message',show_alert("The invoice/invoices for  elected fees exist  please choose another fees", 'warning'));
//                    redirect('student_create_invoice', 'refresh');
//                    exit;
//
//                };

                //CREATE INVOICE
                $ega_auth=$this->db->query("select * from ega_auth")->row();
                $url=$ega_auth->call_url;
                //create new invoice

                $fee_code=2;
                $select_fee_cod=$this->db->query("select * from fee_structure where id=".$student_fee_id)->row();
                if($select_fee_cod)
                {
                    $fee_code=$select_fee_cod->fee_code;
                }

                $invoice_data_array=array(
                    'student_id'=>$reg_no,
                    'type'=>GetFeeTypeDetails($fee_code)->name,
                    'amount'=>$amount,
                    'GfsCode'=>GetFeeTypeDetails($fee_code)->gfscode,
                    'student_name'=>$name,
                    'student_mobile'=>$mobile,
                    'student_email'=>$email,
                    'student_address'=>$address,
                    'description'=>$description,
                    'fee_id'=>$student_fee_id
                );

                $this->db->insert('invoices',$invoice_data_array);
                $invoice_id=$this->db->insert_id();
//
//                //student fee
//                for($i=0;$i<count($txtFeeName);$i++)
//                {
//                    if((int)$txtAmount[$i]==0)
//                        continue;
//                    $student_fee_array=explode('_',$txtFeeName[$i]);
//                    $student_fee_id=$student_fee_array[0];
//                    $student_fee=array(
//                        'invoice_number'=>$invoice_id,
//                        'student_regno'=>$reg_no,
//                        'fee_id'=>$student_fee_id
//
//                    );
//                    $this->db->insert('student_fee',$student_fee);
//
//                }


                $postdata = array(
                    "customer" => $ega_auth->username,
                    "reference" => $ega_auth->prefix.$invoice_id,
                    "student_name" => $name,
                    "student_id" => $reg_no,
                    "student_email"=>$email,
                    "student_mobile"=>$mobile,
                    "GfsCode"=>GetFeeTypeDetails($fee_code)->gfscode,
                    "amount"=> $amount,
                    "type"=> GetFeeTypeDetails($fee_code)->name,
                    "secret"=>$ega_auth->api_secret,
                    "action"=>'SEND_INVOICE'
                );
                /*  $postdata = array(
                  "customer" => "IAE",
                  "reference" => $ega_auth->prefix.$invoice_id,
                  "student_name" =>$name,
                  "student_id" => $reg_no,
                  "student_email"=>$email,
                  "student_mobile"=>$mobile,
                  "GfsCode"=>GetFeeTypeDetails(2)->gfscode,
                  "amount"=> $amount,
                  "type"=> GetFeeTypeDetails(2)->name,
                  "secret"=>"sevr/EQLtQvJgrsq1LsPxLGLeYp/IRS687Q5GguNtro=",
                  "action"=>'SEND_INVOICE'
                  );*/
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

                //end create invoice

                $this->data['message'] = show_alert("Invoice successfully created", 'info');
                redirect('student_invoices/?regno='.$reg_no, 'refresh');
            }else{
                $this->data['message'] = show_alert($description, 'warning');
            }

        }
        $this->data['middle_content'] = 'home/createinvoice2';
        $this->data['sub_link'] = 'createinvoice';
        $this->data['content'] = 'home/home2';
        $this->load->view('public_template_student', $this->data);
    }

    function student_dashboard()
    {
        //$current_user = current_user();
        $this->data['middle_content'] = 'home/dashboard';
        $this->data['content'] = 'home/home';
        $this->load->view('public_template_student', $this->data);
    }



}
