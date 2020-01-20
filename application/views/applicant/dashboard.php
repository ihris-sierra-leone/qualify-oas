<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">Dashboard</div>
    </div>
    <div class="ibox-content">Welcome !, Please use menu at the left side to navigate in the system

        <br/><br/>
        <?php
       // application_elegibility
        $userid = $CURRENT_USER->id;
        $check_submitted = $this->db->get_where('application', array('submitted'=>1, 'user_id'=>$userid))->row();
        $current_round=$this->db->query("select * from application_round")->row();
        if($current_round)
        {
            $cur_round=$current_round->round;
        }else{
            $cur_round=1;
        }

        $eligibility=$this->db->query("select * from application_elegibility where  applicant_id=".$check_submitted->id." order by id DESC")->row();
        if($eligibility)
        {
            $elegible_status=$eligibility->status;
            $round=$eligibility->round;
        }else
        {
            $elegible_status=100;
            $round=1;
        }
        if($check_submitted){
            if($check_submitted->tcu_status==2)
            {?>
                <p><strong> YOUR APPLICATION CHOICES HAS BEEN SUBMITTED TO TCU AWAITING FOR APPROVAL</strong></p>
                <?php
            }elseif($check_submitted->tcu_status==3){
                ?>
                <p><strong> CONGRATULATION YOUR HAVE BEEN SELECTED TO JOIN <i> <?php echo strtoupper($check_submitted->tcu_status_description); ?></i>  PROGRAM</strong>
                    <a href="<?php echo site_url('reject_admission'); ?> "  onclick="return confirm('Are you sure you want to Reject admission !!!');" style="font-weight: bold; text-decoration: underline;">You can reject your admission here</a>
                </p>
                <?php
            }elseif($check_submitted->tcu_status==4)
            {?>
                <ul>
                    <li>DEAR APPLICANT YOU HAVE MULTIPLE SELECTION, TO CONFIRM <a href="<?php echo site_url('confirmationcode'); ?>" style="font-weight: bold; text-decoration: underline;">Enter confirmation code here</a></li>
                </ul>
                <p>
                    <a href="<?php echo site_url('GetComfirmationCode/'.$check_submitted->id); ?>" style="font-weight: bold; text-decoration: underline;">You can request confirmation code here</a>
                </p>
                <?php
            }elseif($check_submitted->tcu_status==5)
            {
                ?>
                <p><strong>CONGRATULATION, YOU HAVE ALREADY CONFIRMED  <a href="<?php echo site_url('unconfirmationcode'); ?>" style="font-weight: bold; text-decoration: underline;">You can unconfirm  here</a></strong></p>
                <?php
            }elseif($check_submitted->tcu_status==6)
            {?>
                <p><strong>YOU REJECTION HAS BEEN APPROVED </strong></p>
                <?php
            }elseif(($check_submitted->tcu_status==7 or   $elegible_status==0)  and $round!=$cur_round and $check_submitted->application_type==2)
            {?>
                <ul>
                    <li>DEAR APPLICANT WE ARE VERY SORRY YOUR APPLICATION HAS BEEN REJECTED  <i><?php echo strtoupper($check_submitted->tcu_status_description)   ?></i><br/><a href="<?php echo($check_submitted->application_type==2) ? site_url('applicant_choose_programme') : ''; ?>" style="font-weight: bold; text-decoration: underline;">You can apply for subsequent round   here</a></li>
                </ul>
                <?php
            }
            ?>

            <!--        <ul>-->
            <!--          --><?php
//          $applicant_id = $this->db->get_where('users', array('id'=>$userid))->row()->applicant_id;
//          $get_f4index = $this->db->get_where('tcu_records', array('applicant_id'=>$applicant_id, 'type'=>'Confirm'))->row()->f4indexno;
//          if($get_f4index){
//            echo show_alert('Congratulation, you have already confirmed..!!!', 'info');
//          }else{
////          ?>
            <!--            <li>Do you have multiple selection?: <a href="--><?php //echo site_url('confirmationcode'); ?><!--" style="font-weight: bold; text-decoration: underline;">Enter confirmation code here</a>.  </li>-->
            <!--          --><?php //} ?>
            <!--            <li>Reject Admission?: <a href="--><?php //echo site_url('rejectAdmission'); ?><!--" style="font-weight: bold; text-decoration: underline;">Click here to reject your admission</a>.  </li>-->
            <!---->
            <!--        </ul>-->


        <?php } ?>

    </div>
</div>
