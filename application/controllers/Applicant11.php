<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 2:04 PM
 */
class Applicant extends CI_Controller
{
    private $MODULE_ID = '';
    private $GROUP_ID = '';

    private $APPLICANT = false;
    private $APPLICANT_MENU = array();

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();

        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');
        $this->data['applicant_dashboard'] = 'applicant_dashboard';

        $this->data['title'] = 'Applicant';
        $this->APPLICANT = $this->applicant_model->get_applicant($this->data['CURRENT_USER']->applicant_id);
        if(!$this->APPLICANT){
            $current_user_group = get_user_group();
            if($current_user_group->id == 4){
                redirect(site_url('applicant_dashboard'), 'refresh');
            }else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }else{
            $this->data['APPLICANT']  = $this->APPLICANT;
            $this->data['APPLICANT_MENU'] = $this->APPLICANT_MENU = $this->applicant_model->get_applicant_section($this->APPLICANT->id);
        }

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }

   function applicant_dashboard(){
       $current_user = current_user();
      $this->data['middle_content'] = 'applicant/dashboard';
       $this->data['content'] = 'applicant/home';
       $this->load->view('public_template', $this->data);
   }

    function applicant_basic($id){
        $current_user = current_user();

        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('dob', 'Birth Date', 'required|valid_date');
        $this->form_validation->set_rules('nationality', 'Nationality', 'required');
        $this->form_validation->set_rules('disability', 'Disability', 'required');
        $this->form_validation->set_rules('disability', 'Disability', 'required');
        $this->form_validation->set_rules('birth_place', 'Place of Birth', 'required');
        $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
        $this->form_validation->set_rules('residence_country', 'Country of Residence', 'required');

        /*if ($this->APPLICANT->entry_category == 2) {
                $this->form_validation->set_rules('form6_index', 'Form VI Index', 'required|is_unique_edit[application.form6_index.' . $this->APPLICANT->id . ']');
            } else if ($this->APPLICANT->entry_category == 3) {
                $this->form_validation->set_rules('diploma_number', 'Certificate Number', 'required');
            } else if ($this->APPLICANT->entry_category == 4) {
                $this->form_validation->set_rules('diploma_number', 'Diploma Number', 'required');
            }*/


        if ($this->form_validation->run() == true) {
            $array_data = array(
                'FirstName' => ucfirst(trim($this->input->post('firstname'))),
                'MiddleName' => ucfirst(trim($this->input->post('middlename'))),
                'LastName' => ucfirst(trim($this->input->post('lastname'))),
                'Gender' => trim($this->input->post('gender')),
                'Disability' => trim($this->input->post('disability')),
                'Nationality' => trim($this->input->post('nationality')),
                'birth_place' => trim($this->input->post('birth_place')),
                'marital_status' => trim($this->input->post('marital_status')),
                'residence_country' => trim($this->input->post('residence_country')),
                'dob' => format_date(trim($this->input->post('dob'))),
                'modifiedon'=>date('Y-m-d H:i:s'),
                'modifiedby'=> $current_user->id
            );
           /* if($this->APPLICANT->entry_category == 2){
                $array_data['form6_index'] = trim($this->input->post('form6_index'));
            }else if($this->APPLICANT->entry_category == 3 || $this->APPLICANT->entry_category == 4){
                $array_data['diploma_number'] = trim($this->input->post('diploma_number'));
            }*/
            $register = $this->applicant_model->update_applicant($array_data,array('id'=>$this->APPLICANT->id));
            if($register){

                $additional_data = array(
                    'firstname'=>$array_data['FirstName'],
                    'lastname'=>$array_data['LastName']
                );

                $user_id = $this->ion_auth_model->update($current_user->id, $additional_data);

                $this->session->set_flashdata('message',show_alert('Information saved successfully!!','success'));
                redirect('applicant_basic/'.$id,'refresh');
            }

        }

        $this->data['gender_list'] = $this->common_model->get_gender()->result();
        $this->data['disability_list'] = $this->common_model->get_disability()->result();
        $this->data['nationality_list'] = $this->common_model->get_nationality()->result();
        $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
        $this->data['middle_content'] = 'applicant/basic_info';
        $this->data['sub_link'] = 'basic_info';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_contact($id=null){
        $current_user = current_user();
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique_edit[application.Email.'.$this->APPLICANT->id.']|is_unique_edit[users.email.'.$current_user->id.']');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|valid_phone');
        $this->form_validation->set_rules('mobile2', 'Mobile 2', 'valid_phone');

        if ($this->form_validation->run() == true) {

            $array_data = array(
                'Email' => strtolower(trim($this->input->post('email'))),
                'Mobile1' => trim($this->input->post('mobile')),
                'Mobile2' => trim($this->input->post('mobile2')),
                'postal' => trim($this->input->post('postal')),
                'physical' => trim($this->input->post('physical')),
            );

            $register = $this->applicant_model->update_applicant($array_data,array('id'=>$this->APPLICANT->id));

            if($register){

                if(!is_section_used('CONTACT',$this->APPLICANT_MENU)){
                 $this->applicant_model->add_applicant_step($this->APPLICANT->id,'CONTACT',$this->APPLICANT->id);
                }
                $additional_data = array(
                    'phone'=>$array_data['Mobile1'],
                    'email'=>$array_data['Email']
                );

                 $user_id = $this->ion_auth_model->update($current_user->id, $additional_data);
                if(is_null($id)) {
                    $this->db->insert('notify_tmp',array('type'=>'ACTIVE','data'=>json_encode(array('applicant_id'=>$this->APPLICANT->id,'user_id'=>$current_user->id,'resend'=>0))));
                    $last_row = $this->db->insert_id();
                    execInBackground('response send_notification ' . $last_row);
                    $this->session->set_flashdata('message', show_alert('Information saved successfully, Enter Code to verify your account', 'success'));
                    redirect('applicant_activate/', 'refresh');
                }else{
                    $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                    redirect('applicant_contact/' . $id, 'refresh');
                }
            }

            }

        $this->data['middle_content'] = 'applicant/contact_info';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_activate($id=null){
        $current_user = current_user();
        $this->form_validation->set_rules('code', 'Code', 'required|numeric');

        if ($this->form_validation->run() == true) {

            $code = trim($this->input->post('code'));

            if($code == $current_user->activation_code){
                if(!is_section_used('ACTIVATE',$this->APPLICANT_MENU)){
                    $this->applicant_model->add_applicant_step($this->APPLICANT->id,'ACTIVATE',$this->APPLICANT->id);
                }
                $user_id = $this->ion_auth_model->update($current_user->id, array('activation_code'=>''));
                if(is_null($id)) {
                    $this->session->set_flashdata('message', show_alert('Account Verified successfully, Please make Application Payment to continue !! ', 'success'));
                    redirect('applicant_payment/', 'refresh');
                }
            }else{
                $this->data['message'] = show_alert('Invalid Verification Code !!','warning');
            }
        }
        if (isset($_GET['resend'])){
            $this->db->insert('notify_tmp',array('type'=>'ACTIVE','data'=>json_encode(array('applicant_id'=>$this->APPLICANT->id,'user_id'=>$current_user->id,'resend'=>1))));
            $last_row = $this->db->insert_id();
            execInBackground('response send_notification ' . $last_row);
            $this->session->set_flashdata('message', show_alert('Code sent to the email : '.$current_user->email, 'success'));
            redirect('applicant_activate/', 'refresh');
        }

        $this->data['middle_content'] = 'applicant/applicant_activate';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_profile($id=null){
        $current_user = current_user();

        $required = attachment_required('file1', 'Profile Picture');
        if ($required) {

            $extension = getExtension($_FILES['file1']['name']);
            if (!in_array($extension, array('jpg', 'jpeg', 'png'))) {
                $required = false;
                $this->data['upload_error'] = 'The Profile Picture field must contain image with extension .jpg , .jpeg or .png';
            }
        }

        $this->form_validation->set_rules('test', 'Hidden', 'required');


        if ($this->form_validation->run() == true && $required) {
            $filename = uploadFile($_FILES, 'file1', 'profile/');
            if ($filename) {

                $array_data = array(
                    'photo' => $filename
                );
                $register = $this->applicant_model->update_applicant($array_data,array('id'=>$this->APPLICANT->id));

                if($register){

                    if(!is_section_used('PHOTO',$this->APPLICANT_MENU)){
                        $this->applicant_model->add_applicant_step($this->APPLICANT->id,'PHOTO',$this->APPLICANT->id);
                    }
                    $additional_data = array(
                        'profile'=>$array_data['photo']
                    );

                    $user_id = $this->ion_auth_model->update($current_user->id, $additional_data);
                    if(is_null($id)) {
                        $this->session->set_flashdata('message', show_alert('Information saved successfully, Add Next of Kin Informations !!', 'success'));
                        redirect('applicant_next_kin/', 'refresh');
                    }else{
                        $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                        redirect('applicant_profile/' . $id, 'refresh');
                    }
                }

            }else{
                $this->data['message'] = show_alert('Fail to upload Profile Picture','warning');
            }
        }

        $this->data['middle_content'] = 'applicant/profile_photo';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_next_kin($id=null){
        $current_user = current_user();

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('mobile1', 'Mobile Number', 'required|valid_phone');
        $this->form_validation->set_rules('mobile2', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('postal', 'Address', 'required');

        $this->form_validation->set_rules('mobile21', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('mobile22', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email2', 'Email', 'valid_email');

        if ($this->form_validation->run() == true) {

            $primary = array(
                'name' => trim($this->input->post('name')),
                'mobile1' => trim($this->input->post('mobile1')),
                'mobile2' => trim($this->input->post('mobile2')),
                'email' => trim($this->input->post('email')),
                'postal' => trim($this->input->post('postal')),
                'relation' => trim($this->input->post('relation')),
                'is_primary' => 1,
                'applicant_id' => $this->APPLICANT->id
            );
            $this->applicant_model->add_nextkin_info($primary);
            $secondary = array(
                'name' => trim($this->input->post('name2')),
                'mobile1' => trim($this->input->post('mobile21')),
                'mobile2' => trim($this->input->post('mobile22')),
                'email' => trim($this->input->post('email2')),
                'postal' => trim($this->input->post('postal2')),
                'relation' => trim($this->input->post('relation1')),
                'applicant_id' => $this->APPLICANT->id,
                'is_primary' => 0,
            );
            $this->applicant_model->add_nextkin_info($secondary);


            if(!is_section_used('NEXT_KIN',$this->APPLICANT_MENU)){
                $this->applicant_model->add_applicant_step($this->APPLICANT->id,'NEXT_KIN',$this->APPLICANT->id);
            }

            if(is_null($id)) {
                $this->session->set_flashdata('message', show_alert('Information saved successfully, Please Enter Education Background !!', 'success'));
                redirect('applicant_education/', 'refresh');
            }else{
                $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                redirect('applicant_next_kin/' . $id, 'refresh');
            }
        }

        if(is_section_used('NEXT_KIN',$this->APPLICANT_MENU)) {
            $next_kin = $this->applicant_model->get_nextkin_info($this->APPLICANT->id)->result();
            if (count($next_kin) > 0) {
                $this->data['next_kin'] = $next_kin;
            }
        }



        $this->data['middle_content'] = 'applicant/applicant_next_kin';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function is_applicant_pay(){
       $current_user = current_user();
       $amount = $this->applicant_model->get_paid_amount($this->APPLICANT->id);
       $amount_required = APPLICATION_FEE;
       if( $this->APPLICANT->application_type == 3){
           $amount_required = APPLICATION_FEE_POSTGRADUATE;
       }
       if($amount >= 100){
           $this->applicant_model->add_applicant_step($this->APPLICANT->id,'PAYMENT',$this->APPLICANT->id);
           $this->session->set_flashdata('message',show_alert('Payment recognised, Please use menu at the left side to continue','success'));
           echo 1;
       }else {
           echo 0;
       }
    }

    function applicant_payment($id=null){
        $current_user = current_user();


        $this->data['middle_content'] = 'applicant/applicant_payment';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_education($id =null ){
        $current_user = current_user();

        if (isset($_GET) && isset($_GET['action'])) {
            $this->data['action'] = $_GET['action'];
        }


        if(isset($_GET['row_id'])){
            $row_id = decode_id($_GET['row_id']);
            if(!is_null($row_id)){
                $this->db->delete("application_education_subject",array('id'=>$row_id));
                $this->session->set_flashdata('message',show_alert('Row deleted successfully','info'));
                redirect(site_url('applicant_education/'.$id),'refresh');
            }
        }



        $this->form_validation->set_rules('certificate', 'Certificate', 'required');
        $certificate = $this->input->post('certificate');
        $country1 = $this->input->post('country1');
        $this->form_validation->set_rules('country1', 'Country', 'required');
        $this->form_validation->set_rules('completed_year', 'Completed', 'required|integer');



        if(isset($_GET) && isset($_GET['id'])) {
            if ($country1 == 220 && ($certificate < 3 && $certificate != 1.5)) {
                $this->form_validation->set_rules('index_number', 'Index Number', 'required|valid_indexNo|is_unique_edit[application_education_authority.index_number.' . decode_id($_GET['id']) . ']');
            } else {
                $this->form_validation->set_rules('index_number', 'Index Number', 'required|is_unique_edit[application_education_authority.index_number.' . decode_id($_GET['id']) . ']');
            }
        }else {
            if ($country1 == 220 && ($certificate < 3 && $certificate != 1.5)) {
                $this->form_validation->set_rules('index_number', 'Index Number', 'required|valid_indexNo|is_unique[application_education_authority.index_number]');
            }else {
                $this->form_validation->set_rules('index_number', 'Index Number', 'required|is_unique[application_education_authority.index_number]');

            }
        }

        if($certificate == 4){
         $this->form_validation->set_rules('avn', 'NACTE AVN Number', 'required|is_unique[application_education_authority.avn]');
        }

        if($certificate < 3 && $certificate != 1.5) {
            $this->form_validation->set_rules('exam_authority1', 'Examination Authority', 'required');
            $this->form_validation->set_rules('division1', 'Division/Grade', 'required');
            $this->form_validation->set_rules('school1', 'Examination/Centre/School', 'required');

            if($country1 == 220 && ($certificate == 1 || $certificate == 2)){

            }else{
                $this->form_validation->set_rules('subject[]', 'Subject', 'required');
                $this->form_validation->set_rules('year[]', 'Year', 'required');
                $this->form_validation->set_rules('grade[]', 'Grade', 'required');
            }



        }else{
            $this->form_validation->set_rules('exam_authority', 'Examination Authority', 'required');
            $this->form_validation->set_rules('programme_title', 'Programme Title', 'required');
            if($this->APPLICANT->application_type == 3){
                $this->form_validation->set_rules('division', 'G.P.A / Degree Class', 'required');
            }else {
                $this->form_validation->set_rules('division', 'G.P.A', 'required|numeric');
            }
            $this->form_validation->set_rules('school', 'College / Institution / University', 'required');


        }


        if ($this->form_validation->run() == true) {



            if($certificate > 2 || $certificate==1.5) {
                //For Certificates
                $array_data = array(
                    'certificate' => trim($this->input->post('certificate')),
                    'exam_authority' => trim($this->input->post('exam_authority')),
                    'applicant_id' => $this->APPLICANT->id,
                    'school'=> trim($this->input->post('school')),
                    'division'=> trim($this->input->post('division')),
                    'country'=> trim($this->input->post('country1')),
                    'index_number'=> trim($this->input->post('index_number')),
                    'createdby'=> $current_user->id,
                    'createdon'=> date('Y-m-d H:i:s'),
                    'programme_title' => trim($this->input->post('programme_title')),
                    'completed_year' => trim($this->input->post('completed_year'))
                );
                $subject = null;
                $grade = null;
                $year = null;

                if($certificate == 3){
                    $array_data['technician_type'] = '';
                }

                if($certificate == 4){
                    $array_data['avn'] = trim($this->input->post('avn'));
                }

            }else{
                //For O-Level and A-Level
                $array_data = array(
                    'certificate' => trim($this->input->post('certificate')),
                    'exam_authority' => trim($this->input->post('exam_authority1')),
                    'index_number' => trim($this->input->post('index_number')),
                    'applicant_id' => $this->APPLICANT->id,
                    'school'=> trim($this->input->post('school1')),
                    'division'=> trim($this->input->post('division1')),
                    'country'=> trim($this->input->post('country1')),
                    'createdby'=> $current_user->id,
                    'completed_year' => trim($this->input->post('completed_year')),
                    'createdon'=> date('Y-m-d H:i:s'),
                );

                $subject = $this->input->post('subject');
                $grade = $this->input->post('grade');
                $year = $this->input->post('year');
            }

             $edit_id = null;

            if(isset($_GET) && isset($_GET['id'])){
                $edit_id = decode_id($_GET['id']);
            }
            $add_data = $this->applicant_model->add_education($array_data,$subject,$grade,$year,$edit_id);
            if($add_data){
                if(!is_section_used('EDUCATION',$this->APPLICANT_MENU)){
                    $this->applicant_model->add_applicant_step($this->APPLICANT->id,'EDUCATION',$this->APPLICANT->id);
                }

                if(is_null($id)) {
                    $this->session->set_flashdata('message', show_alert('Information saved successfully !!', 'success'));
                    redirect('applicant_education/'.encode_id($this->APPLICANT->id), 'refresh');
                }else{
                    $this->session->set_flashdata('message', show_alert('Information saved successfully !!', 'success'));
                    redirect('applicant_education/'.encode_id($this->APPLICANT->id), 'refresh');
                }
            }
        }

        $this->data['education_bg'] = $this->applicant_model->get_education_bg(null,$this->APPLICANT->id);
       if (isset($_GET) && isset($_GET['id'])) {
           $data_row = $this->applicant_model->get_education_bg(decode_id($_GET['id']));
                    if (count($data_row) > 0) {
                        $this->data['education_info'] = $data_row[0];
                    }
        }

        $this->data['nationality_list'] = $this->common_model->get_nationality()->result();

        $this->data['certificate_list'] = certificate_by_entry_type($this->APPLICANT->entry_category);
        $this->data['subject_list'] = $this->setting_model->get_sec_subject(null,1)->result();
        $this->data['middle_content'] = 'applicant/applicant_education';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_attachment($id=null){
        $current_user = current_user();

        $this->form_validation->set_rules('certificate', 'Certificate Category', 'required');

        $required = attachment_required('file1', 'Attachment');

        if ($required) {

            $extension = getExtension($_FILES['file1']['name']);
            if (!in_array($extension, array('pdf','jpg','jpeg','png'))) {
                $required = false;
                $this->data['upload_error'] = 'The Attachment field must contain file with extension .pdf , .jpg , .jpeg or .png';

            }
        }

        if ($this->form_validation->run() == true && $required == TRUE) {

            $filename = uploadFile($_FILES, 'file1', 'attachment/');
            if ($filename) {

                $array = array(
                    'certificate'=>$this->input->post('certificate'),
                    'comment'=>$this->input->post('comment'),
                    'attachment' => $filename,
                    'filename'=> $_FILES['file1']['name'],
                    'applicant_id'=> $this->APPLICANT->id,
                    'createdby' =>$current_user->id,
                    'createdon' =>date('Y-m-d H:i:s')
                );

                $add = $this->applicant_model->add_attachment($array);
                if(!is_section_used('ATTACHMENT',$this->APPLICANT_MENU)){
                    $this->applicant_model->add_applicant_step($this->APPLICANT->id,'ATTACHMENT',$this->APPLICANT->id);
                }

                if(is_null($id)) {
                    $this->session->set_flashdata('message', show_alert('Information saved successfully !!', 'success'));
                    redirect('applicant_attachment/'.encode_id($this->APPLICANT->id), 'refresh');
                }else{
                    $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                    redirect('applicant_attachment/' . $id, 'refresh');
                }

                } else {
                $this->data['message'] = show_alert('Fail to upload attachment', 'warning');
            }
        }

        if (isset($_GET) && isset($_GET['rmv'])) {
            $delete = $this->db->delete("application_attachment",array('id'=>$_GET['rmv']));
            if($delete){
                $this->session->set_flashdata("message", show_alert('Attachment deleted  successfully !!', 'success'));
                redirect('applicant_attachment/' . $id, 'refresh');
            }else{
                $this->session->set_flashdata("message", show_alert('This action did not pass our security checks. !!', 'info'));
                redirect('applicant_attachment/' . $id, 'refresh');
            }
        }
        $this->data['attachment_list'] = $this->applicant_model->get_attachment($this->APPLICANT->id);
        $this->data['certificate_list'] = certificate_by_entry_type($this->APPLICANT->entry_category);
        $this->data['certificate_list'] =  $this->data['certificate_list']+addition_certificate(null,$this->APPLICANT->application_type);
        //$this->data['certificate_list'][100] = 'Birth Certificate';
        //$this->data['certificate_list'][101] = 'Others';
        $this->data['middle_content'] = 'applicant/applicant_attachment';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_choose_programme($id=null){
        $current_user = current_user();
        $this->form_validation->set_rules('choice1', 'First Choice', 'required');
//        $this->form_validation->set_rules('choice2', 'Second Choice', 'required');
//        $this->form_validation->set_rules('choice3', 'Third Choice', 'required');
//        if($this->APPLICANT->application_type < 3) {
//            $this->form_validation->set_rules('choice4', 'Fourth Choice', 'required');
//            $this->form_validation->set_rules('choice5', 'Fifth Choice', 'required');
//        }
        if ($this->form_validation->run() == true) {
            $array1 = array();
            $choice1 = trim($this->input->post('choice1'));
            $array1[$choice1] = $choice1;
            $choice2 = trim($this->input->post('choice2'));
            $array1[$choice2] = $choice2;
            $choice3 = trim($this->input->post('choice3'));
            $array1[$choice3] = $choice3;
            $counter_data = 3;
            if($this->APPLICANT->application_type < 3) {
                $choice4 = trim($this->input->post('choice4'));
                $array1[$choice4] = $choice4;
                $choice5 = trim($this->input->post('choice5'));
                $array1[$choice5] = $choice5;
                $counter_data = 5;
            }

            if(count($array1) >= 1) {
                $array_data = array(
                    'choice1' => trim($this->input->post('choice1')),
                    'choice2' => trim($this->input->post('choice2')),
                    'choice3' => trim($this->input->post('choice3')),
                    'createdby' => $current_user->id,
                    'createdon' => date('Y-m-d H:i:s'),
                    'applicant_id' => $this->APPLICANT->id
                );
                if($this->APPLICANT->application_type < 3) {

                    $array_data['choice4'] = trim($this->input->post('choice4'));
                    $array_data['choice5'] = trim($this->input->post('choice5'));
                }

                $add = $this->applicant_model->add_programme_choice($array_data);
                if ($add) {
                    if (!is_section_used('PROGRAMME', $this->APPLICANT_MENU)) {
                        $this->applicant_model->add_applicant_step($this->APPLICANT->id, 'PROGRAMME', $this->APPLICANT->id);
                    }

                    if (is_null($id)) {
                        if($this->APPLICANT->application_type != 3){
                            $this->session->set_flashdata('message', show_alert('Information saved successfully, Review your Application and Submit', 'success'));
                            redirect('applicant_submission/', 'refresh');
                        }else{
                            $this->session->set_flashdata('message', show_alert('Information saved successfully, Add Professional Experience !!', 'success'));
                            redirect('applicant_experience/', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                        redirect('applicant_choose_programme/' . $id, 'refresh');
                    }

                } else {
                    $this->data['message'] = show_alert('Fail to save Information !!', 'warning');
                }
            }else{
                $this->data['message'] = show_alert('Selection of the programme must be unique !, Please correct it and save information again', 'warning');
            }
        }
         $mychoice = $this->applicant_model->get_programme_choice($this->APPLICANT->id);
        if($mychoice){
            $this->data['mycoice'] = $mychoice;
        }

        $this->data['department'] = $this->common_model->get_department()->result();
        $this->data['programme'] = $this->applicant_model->get_programme_for_choice($this->APPLICANT->application_type);
        $this->data['middle_content'] = 'applicant/choose_programme';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_submission($id = null)
    {
//        echo "hapa nafika"; exit;
        $current_user = current_user();
        $username = $current_user->username;
        $token = "oTcyMURGMTY5QTRENU3MUJ";

        $applicant_id = $this->APPLICANT->id;
        $f4index = $this->db->query("select * from application_education_authority where applicant_id='$applicant_id' and certificate=1 ")->row()->index_number;
        $f6index = $this->db->query("select * from application_education_authority where applicant_id='$applicant_id' and certificate=2 ")->row()->index_number;
        $institution = $this->db->query("select * from college")->row()->InstitutionCode;
        $choice1 = $this->db->query("select * from application_programme_choice where applicant_id = '$applicant_id'")->row()->choice1;
        $choice2 = $this->db->query("select * from application_programme_choice where applicant_id = '$applicant_id'")->row()->choice2;
        $choice3 = $this->db->query("select * from application_programme_choice where applicant_id = '$applicant_id'")->row()->choice3;
//        $programme_choices = $choice1.' , '.$choice2.' , '.$choice3;
        $entry = $this->APPLICANT->entry_category;
        if($entry == 2)
        {
            $entry_category = "A";
        }else if($entry == 4)
        {
            $entry_category = "D";
        }
        $this->form_validation->set_rules('submit_app', 'Submit Application', 'required');
        if ($this->form_validation->run() == true) {
            $submission = array(
                'submitted' => 1,
                'submitedon' => date('Y-m-d H:i:s')
            );
//         $validate = $this->applicant_model->allow_submission($this->APPLICANT->id);
            $validate = true;
            if ($validate) {
                $register = $this->applicant_model->update_applicant($submission, array('id' => $this->APPLICANT->id));
//                $entry_category,
                if (!is_section_used('SUBMIT', $this->APPLICANT_MENU)) {
                    $this->applicant_model->add_applicant_step($this->APPLICANT->id, 'SUBMIT', $this->APPLICANT->id);
//                    $xml_data = FirstRequest($username, '64jklskllks12345', $institution, $f4index, 's4536', '',$mobile, $email, '', '', 'reason');
                    $xml_data = FirstRequest($username, $token, $institution,$f4index,$f6index,
                        $entry_category,'1234','23456');
//                    $xml_data = FirstRequest($institution, $f4index, 's4536', $mobile, $email, '', '', 'reason');
                    $sendRequest = sendXmlOverPost('http://192.168.43.112/test/saver.php', $xml_data);
                    $data = simplexml_load_string($sendRequest);
                    $tcu_response = $data->ResponseParameters->DESCRIPTION;
//                    $this->db->query("update application set tcu_status='$tcu_response' ");
                    $data_to_update = array(
                        'tcu_response' => $tcu_response,
                    );
                    $this->db->update('application', $data_to_update, array('id' => $applicant_id));
                    echo $tcu_response;
                    echo "seccefully updated ";
                    exit;
                    //        <Response>
//<ResponseParameters>
//<F4INDEXNO>s1234</F4INDEXNO>
//<CODE>200</CODE>
//<DESCRIPTION>Successfully Registered</DESCRIPTION>
//</ResponseParameters>
//</Response>
                }
                $this->session->set_flashdata('message', show_alert('Your application is submitted successfully !!', 'success'));
                redirect('applicant_submission/' . encode_id($this->APPLICANT->id), 'refresh');
            } else {
                $this->session->set_flashdata('message', show_alert(implode('<br/>', $validate), 'warning'));
                redirect('applicant_submission/', 'refresh');
            }
        }
        $next_kin = $this->applicant_model->get_nextkin_info($this->APPLICANT->id)->result();
        if (count($next_kin) > 0) {
            $this->data['next_kin'] = $next_kin;
        }
        $referee = $this->applicant_model->get_applicant_referee($this->APPLICANT->id)->result();
        if (count($referee) > 0) {
            $this->data['academic_referee'] = $referee;
        }
        $this->data['education_bg'] = $this->applicant_model->get_education_bg(null, $this->APPLICANT->id);
        $this->data['attachment_list'] = $this->applicant_model->get_attachment($this->APPLICANT->id);
        $mychoice = $this->applicant_model->get_programme_choice($this->APPLICANT->id);
        if ($mychoice) {
            $this->data['mycoice'] = $mychoice;
        }
        $sponsor = $this->applicant_model->get_applicant_sponsor($this->APPLICANT->id)->row();
        if ($sponsor) {
            $this->data['sponsor_info'] = $sponsor;
        }
        $employer = $this->applicant_model->get_applicant_employer($this->APPLICANT->id)->row();
        if ($employer) {
            $this->data['employer_info'] = $employer;
        }
        $this->data['middle_content'] = 'applicant/applicant_submission';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }


    function applicant_experience($id=null){
        $current_user = current_user();
        if(!is_section_used('EXPERIENCE',$this->APPLICANT_MENU)){
            $this->applicant_model->add_applicant_step($this->APPLICANT->id,'EXPERIENCE',$this->APPLICANT->id);
        }
        if(isset($_GET) && isset($_GET['rmid'])){
            $this->db->delete('application_experience',array('id'=>$_GET['rmid']));
            $this->session->set_flashdata('message', show_alert('Professional Experience Information deleted !!', 'info'));
        redirect('applicant_experience/','refresh');
        }
         $category = $this->input->post('catv');
        if($category) {

            $this->form_validation->set_rules('name', ($category == 1 ? 'Hospital/Institute':($category == 2 ? 'Name of Institution':'Post Held')), 'required');
            $this->form_validation->set_rules('column1', ($category == 1 ? 'Address':($category == 2 ? 'Award Given':'Employer')), 'required');
            if($category > 1){
                $this->form_validation->set_rules('column2', ($category == 2 ? 'Year of Completion':'When (Month/Year)'), 'required');

            }

            if ($this->form_validation->run() == true) {
              $array=array(
                  'type'=>$category,
                  'applicant_id'=>$this->APPLICANT->id,
                  'name'=>trim($this->input->post('name')),
                  'column1'=>trim($this->input->post('column1')),
                  'column2'=>trim($this->input->post('column2')),
              );
              $row_id = (isset($_GET['id']) ? $_GET['id'] : null);
              if(is_null($row_id)){
                  $array['createdby'] = $current_user->id;
                  $array['createdon'] = date('Y-m-d H:i:s');
              }else{
                  $array['modifiedby'] = $current_user->id;
                  $array['modifiedon'] = date('Y-m-d H:i:s');
              }
              $add = $this->applicant_model->add_experience($array,$row_id);
              if($add){
                  if(is_null($row_id)) {
                      $this->session->set_flashdata('message', show_alert('Professional Experience Information saved !!', 'info'));
                  }else{
                      $this->session->set_flashdata('message', show_alert('Professional Experience Information updated !!', 'info'));

                  }
               redirect('applicant_experience/','refresh');
              }

            }

        }

        if(isset($_GET) && isset($_GET['id'])){
            $row = $this->applicant_model->get_experience($this->APPLICANT->id,$_GET['id'])->row();
            if($row){
                $this->data['experience_info'] = $row;
            }
        }

        $this->data['middle_content'] = 'applicant/applicant_experience';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }


    function applicant_referee($id=null){
        $current_user = current_user();

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('mobile1', 'Mobile Number', 'required|valid_phone');
        $this->form_validation->set_rules('mobile2', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('position', 'Position', 'required');
        $this->form_validation->set_rules('organization', 'Organization', 'required');

        $this->form_validation->set_rules('name2', 'Name', 'required');
        $this->form_validation->set_rules('mobile21', 'Mobile Number', 'required|valid_phone');
        $this->form_validation->set_rules('mobile22', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email2', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address2', 'Address', 'required');
        $this->form_validation->set_rules('position2', 'Position', 'required');
        $this->form_validation->set_rules('organization2', 'Organization', 'required');

        if ($this->form_validation->run() == true) {

            $primary = array(
                'name' => trim($this->input->post('name')),
                'mobile1' => trim($this->input->post('mobile1')),
                'mobile2' => trim($this->input->post('mobile2')),
                'email' => trim($this->input->post('email')),
                'address' => trim($this->input->post('address')),
                'position' => trim($this->input->post('position')),
                'organization' => trim($this->input->post('organization')),
                'is_primary' => 1,
                'rec_code'=> generatePIN(8),
                'applicant_id' => $this->APPLICANT->id
            );
            $this->applicant_model->add_applicant_referee($primary);
            $secondary = array(
                'name' => trim($this->input->post('name2')),
                'mobile1' => trim($this->input->post('mobile21')),
                'mobile2' => trim($this->input->post('mobile22')),
                'position' => trim($this->input->post('position2')),
                'organization' => trim($this->input->post('organization2')),
                'email' => trim($this->input->post('email2')),
                'address' => trim($this->input->post('address2')),
                'applicant_id' => $this->APPLICANT->id,
                'is_primary' => 0,
                'rec_code'=> generatePIN(8),
            );
            $this->applicant_model->add_applicant_referee($secondary);


            if(!is_section_used('REFEREE',$this->APPLICANT_MENU)){
                $this->applicant_model->add_applicant_step($this->APPLICANT->id,'REFEREE',$this->APPLICANT->id);
            }

            if(is_null($id)) {
                $referee = $this->applicant_model->get_applicant_referee($this->APPLICANT->id)->result();
                if(count($referee) > 0) {
                    foreach ($referee as $rk => $rv) {
                        $this->db->insert('notify_tmp', array('type' => 'REFEREE', 'data' => json_encode(array('applicant_id' => $this->APPLICANT->id, 'referee_id' => $rv->id,'SITE_URL'=>site_url()))));
                        $last_row = $this->db->insert_id();
                        execInBackground('response send_notification ' . $last_row);
                    }
                }
                $this->session->set_flashdata('message', show_alert('Information saved successfully and Email sent to referees, Please add Sponsor Information !!', 'success'));
                redirect('applicant_sponsor/', 'refresh');
            }else{
                $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                redirect('applicant_referee/' . $id, 'refresh');
            }
        }

        if(is_section_used('REFEREE',$this->APPLICANT_MENU)) {
            $next_kin = $this->applicant_model->get_applicant_referee($this->APPLICANT->id)->result();
            if (count($next_kin) > 0) {
                $this->data['next_kin'] = $next_kin;
            }


        }

        if(isset($_GET) && isset($_GET['resend']) && isset($_GET['id'])){
                $this->db->insert('notify_tmp', array('type' => 'REFEREE', 'data' => json_encode(array('applicant_id' => $this->APPLICANT->id, 'referee_id' => $_GET['id'],'SITE_URL'=>site_url()))));
                $last_row = $this->db->insert_id();
                execInBackground('response send_notification ' . $last_row);
                 $this->session->set_flashdata('message', show_alert('Email Sent successfully !!', 'success'));
                redirect('applicant_referee/' . $id, 'refresh');
        }


        $this->data['middle_content'] = 'applicant/applicant_referee';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function applicant_sponsor($id=null){
        $current_user = current_user();

        $this->form_validation->set_rules('name', 'Sponsor Name', 'required');
        $this->form_validation->set_rules('mobile1', 'Mobile Number', 'required|valid_phone');
        $this->form_validation->set_rules('mobile2', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Address', 'required');

        $this->form_validation->set_rules('name2', 'Employer Name', 'required');
        $this->form_validation->set_rules('mobile21', 'Mobile Number', 'required|valid_phone');
        $this->form_validation->set_rules('mobile22', 'Mobile Number', 'valid_phone');
        $this->form_validation->set_rules('email2', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address2', 'Address', 'required');


        if ($this->form_validation->run() == true) {

            $sponsor = array(
                'name' => trim($this->input->post('name')),
                'mobile1' => trim($this->input->post('mobile1')),
                'mobile2' => trim($this->input->post('mobile2')),
                'email' => trim($this->input->post('email')),
                'address' => trim($this->input->post('address')),
                'applicant_id' => $this->APPLICANT->id
            );
            $this->applicant_model->add_applicant_sponsor($sponsor);

            $employer = array(
                'name' => trim($this->input->post('name2')),
                'mobile1' => trim($this->input->post('mobile21')),
                'mobile2' => trim($this->input->post('mobile22')),
                'email' => trim($this->input->post('email2')),
                'address' => trim($this->input->post('address2')),
                'applicant_id' => $this->APPLICANT->id
            );
            $this->applicant_model->add_applicant_employer($employer);


            if(!is_section_used('SPONSOR',$this->APPLICANT_MENU)){
                $this->applicant_model->add_applicant_step($this->APPLICANT->id,'SPONSOR',$this->APPLICANT->id);
            }

            if(is_null($id)) {
                $this->session->set_flashdata('message', show_alert('Information saved successfully, Review your Application and Submit', 'success'));
                redirect('applicant_submission/', 'refresh');
            }else{
                $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                redirect('applicant_sponsor/' . $id, 'refresh');
            }
        }

        if(is_section_used('SPONSOR',$this->APPLICANT_MENU)) {
            $sponsor = $this->applicant_model->get_applicant_sponsor($this->APPLICANT->id)->row();
            if ($sponsor) {
                $this->data['sponsor_info'] = $sponsor;
            }

            $employer = $this->applicant_model->get_applicant_employer($this->APPLICANT->id)->row();
            if ($employer) {
                $this->data['employer_info'] = $employer;
            }
        }



        $this->data['middle_content'] = 'applicant/applicant_sponsor';
        $this->data['content'] = 'applicant/home';
        $this->load->view('public_template', $this->data);
    }

    function fakepay(){
        $amount_required = APPLICATION_FEE;
        if( $this->APPLICANT->application_type == 3){
            $amount_required = APPLICATION_FEE_POSTGRADUATE;
        }
        $array=array(
            'msisdn'=>'255712765538',
            'reference'=>REFERENCE_START.$this->APPLICANT->id,
            'applicant_id'=>$this->APPLICANT->id,
            'timestamp'=>date('Y-m-d H:i:s'),
            'receipt'=>generatePIN(8).$this->APPLICANT->id,
            'amount'=>$amount_required-1000,
            'charges'=>1000,
            'createdon'=>date('Y-m-d H:i:s')
        );

        $this->db->insert('application_payment',$array);
    }

}