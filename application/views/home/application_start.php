<?php
include VIEWPATH.'include/pbscrum.php';
?>

<div class="col-lg-12 text-center">
    <h1>Registration Process step 1</h1>
    <p>Application Deadline Date : <?php echo date('F d, Y', strtotime(get_application_deadline())); ?> </p>
    <?php
    if (isset($message)) {
        echo $message;
    } else if ($this->session->flashdata('message') != '') {
        echo $this->session->flashdata('message');
    }
    ?>
</div>


<div class="row gray-bg">
    <div class="container">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5 style="color: brown;"> IMPORTANT NOTE</h5></div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tr>
                            <td class="no-borders"> 1. Your name must be the same as in  <?php echo ($_GET['entry'] > 6 ? 'your Academic' : 'Form IV') ?> Certificate</td>
                        </tr>
                        <tr>
                            <td> 2. Date of Birth must be the same as  in  Birth Certificate</td>
                        </tr>
                        <tr>
                            <?php if($_GET['entry'] > 6 ){ ?>
                                <td> 3. Index Number must be the same as  in  Entry Mode Certificate</td>
                            <?php }else{ ?>
                            <td> 3. Form IV index Number must be the same as  in  Form IV Certificate</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td> 4. <strong>Failure to any of the above, Your application will be disqualified  </strong></td>
                        </tr>
                        <tr>
                            <td> 5. Make sure <b>Application Type</b> and <b>Entry Type</b> are correct before submit form in the right side because you will not be able to start any  new application</td>
                        </tr>
                        <tr>
                            <td> 6. Once <?php echo ($_GET['entry'] > 6 ? '' : 'Form IV' ); ?>  Index Number Registered, you will not be able to change it.</td>
                        </tr>
                        <tr>
                            <td> 7. <strong>Application fee must be paid within four (4) days from the first day of filling an application, otherwise your account will be deleted permanent.</strong></td>
                        </tr>
                        <tr>
                            <td> 8. <strong>Make sure you read Admission requirement before selecting/Choose programmes.</strong></td>
                        </tr>
                        <tr>
                            <td> 9. Online support : <?php echo ONLINE_SUPPORT; ?> </td>
                        </tr>
                    </table>



                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5>Applicant Basic Information</h5></div>
                </div>
                <div class="ibox-content">

                    <?php  echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>


                    <div class="form-group"><label class="col-lg-3 control-label">Application Type  : <span class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo application_type($type); ?>"
                                   class="form-control" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Entry Type  : <span class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo entry_type($entry); ?>"
                                   class="form-control" disabled="disabled"/>
                        </div>
                    </div>

                    <?php if($entry==1 or $entry==2 or $entry==4){

                        ?>
                        <div class="form-group"><label class="col-lg-4 control-label">O-level Index Number : <span
                                        class="required">*</span></label>

                            <div class="col-lg-6">
                                <input type="text" value="<?php echo set_value('o_level_index_no'); ?>"
                                       class="form-control" name="o_level_index_no" id="o_level_index_no"  onblur="loadAjaxData(this.value,'','','o-level')">
                                <div  >Eg <b>S0125/0000/2005 or P0125/0000/2005 </b></div>
                                <?php echo form_error('o_level_index_no'); ?>
                            </div>
                        </div>


                        <div class="form-group"><label class="col-lg-3 control-label">Year Completed : <span
                                        class="required">*</span></label>
                            <div class="col-lg-7">
                                <input type="text"
                                       value="<?php echo set_value('o_completed_year'); ?>"
                                       class="form-control" name="o_completed_year" id="o_completed_year"  onKeyPress="return numbersonly(event,this.value)" maxlength="4" readonly onblur="checkYear(this.value,$('#o_level_index_no').val(),'o-level')">
                                <?php echo form_error('o_completed_year'); ?>
                            </div>
                        </div>


                        <?php
                    } ?>

                    <div class="form-group"><label class="col-lg-3 control-label">First Name : <span
                                    class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text" value="<?php echo set_value('firstname'); ?>"
                                   class="form-control" name="firstname" id="firstname" <?php echo ($entry==1 or $entry==2 or $entry==4)?'readonly':''; ?> >
                            <?php echo form_error('firstname'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Last Name : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('lastname'); ?>"
                                   class="form-control " name="lastname"  id="lastname" <?php echo ($entry==1 or $entry==2 or $entry==4)?'readonly':''; ?>>
                            <?php echo form_error('lastname'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Middle Names : </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('middlename'); ?>"
                                   class="form-control " name="middlename"  id="middlename" <?php echo ($entry==1 or $entry==2 or $entry==4)?'readonly':''; ?>>
                            <?php echo form_error('middlename'); ?>
                        </div>
                    </div>
                    <?php if( $entry==2 ){
                        ?>
                        <div class="form-group"><label class="col-lg-4 control-label">A-level Index Number : <span
                                        class="required">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" value="<?php echo set_value('a_level_index_no'); ?>"
                                       class="form-control" name="a_level_index_no" id="a_level_index_no"  onblur="loadAjaxData(this.value,'','','a-level')">
                                <div id="sample_index" >Eg <b>S0125/0000/2005 or P0125/0000/2005</b>    </div>
                                <div id="center_name"></div>
                                <?php echo form_error('a_level_index_no'); ?>
                            </div>
                        </div>

                        <div class="form-group"><label class="col-lg-3 control-label">Year Completed : <span
                                        class="required">*</span></label>
                            <div class="col-lg-7">
                                <input type="text"
                                       value="<?php echo set_value('a_completed_year'); ?>"
                                       class="form-control" name="a_completed_year" id="a_completed_year"  onKeyPress="return numbersonly(event,this.value)" maxlength="4" readonly onblur="checkYear(this.value,$('#a_level_index_no').val(),'a-level')">
                                <?php echo form_error('a_completed_year'); ?>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">School : <span
                                        class="required">*</span></label>

                            <div class="col-lg-7">
                                <input type="text" value="<?php echo set_value('school'); ?>"
                                       class="form-control" name="school"  id="school" readonly>
                                <div id="center_name"></div>
                                <?php echo form_error('school'); ?>
                            </div>
                        </div>

                        <?php
                    } ?>


                    <?php if($entry==4){
                        ?>
                        <div class="form-group"><label class="col-lg-4 control-label">NACTE Award Verification Number : <span
                                        class="required">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" value="<?php echo set_value('avn'); ?>"
                                       class="form-control" name="avn"  onblur="loadAjaxData(this.value,'','','avn')">
                                <div id="center_name"></div>
                                <?php echo form_error('avn'); ?>
                            </div>
                        </div>

                        <div class="form-group"><label class="col-lg-3 control-label">Institution : <span
                                        class="required">*</span></label>
                            <div class="col-lg-7">
                                <input type="text" value="<?php echo set_value('institution'); ?>"
                                       class="form-control" name="institution" id="institution" readonly>
                                <div id="center_name"></div>
                                <?php echo form_error('institution'); ?>
                            </div>
                        </div>

                        <?php
                    } ?>


                    <div class="form-group"><label class="col-lg-3 control-label">Gender : <span
                                    class="required">*</span></label>

                        <div class="col-lg-7">
                            <select name="gender" class="form-control">
                                <option value=""> [ Select Gender ]</option>
                                <?php
                                $sel =  set_value('gender');
                                foreach ($gender_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->code ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('gender'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Birth Date : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <input type="text" placeholder="DD-MM-YYYY"
                                   value="<?php echo  set_value('dob'); ?>"
                                   class="form-control  mydate_input" name="dob" autocomplete="off">
                            <?php echo form_error('dob'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Place of Birth : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('birth_place'); ?>"
                                   class="form-control" name="birth_place" >
                            <?php echo form_error('birth_place'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Marital Status : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="marital_status" class="form-control ">
                                <option value=""> [ Select Marital Status ]</option>
                                <?php
                                $sel =  set_value('marital_status');
                                foreach ($marital_status_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('marital_status'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label" style="font-size: 13px;">Country of Residence : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="residence_country" class="form-control select50">
                                <option value=""> [ Select Country ]</option>
                                <?php
                                $sel =  set_value('residence_country',(isset($_GET['NT']) ? ($_GET['NT'] == 1 ? 220 :''):''));
                                foreach ($nationality_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->Country; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('residence_country'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Nationality : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="nationality" class="form-control select51 ">
                                <option value=""> [ Select Nationality ]</option>
                                <?php
                                $sel =  set_value('nationality',(isset($_GET['NT']) ? ($_GET['NT'] == 1 ? 220 :''):''));
                                foreach ($nationality_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('nationality'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Disability : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="disability" class="form-control ">
                                <option value=""> [ Select Disability ]</option>
                                <?php
                                $sel =  set_value('disability');
                                foreach ($disability_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('disability'); ?>
                        </div>
                    </div>


                    <div class="form-group"><label class="col-lg-3 control-label">Email : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('email'); ?>"
                                   class="form-control" name="email"  id="email">
                            <?php echo form_error('email'); ?>
                        </div>
                    </div>


                    <div style="color: brown; font-weight: bold; margin-bottom: 15px; margin-top: 10px;  border-bottom: 1px solid brown; font-size: 15px;">Login Credentials</div>

                    <div class="form-group"><label class="col-lg-3 control-label">Username : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"  name="username"  id="username" value="<?php echo set_value('username'); ?>"  class="form-control" readonly>
                            <div style="font-size: 11px;">N.B Username is your valid email address</div>
                            <?php echo form_error('username'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Password : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="password"
                                   value=""
                                   class="form-control" name="password">
                            <?php echo form_error('password'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Confirm Password : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="password"
                                   value=""
                                   class="form-control" name="conf_password">
                            <?php echo form_error('conf_password'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Are you a human ?<span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <img src="<?php echo site_url('home/capture/'.$captcha_num); ?>"/>
                            <input type="text"
                                   value="" placeholder="Type six digit code as shown above"
                                   class="form-control" name="capture">
                            <?php echo form_error('capture'); ?>
                        </div>
                    </div>





                    <div class="form-group" style="margin-top: 10px;">
                        <div class="col-lg-offset-4 col-lg-6">
                            <input class="btn btn-sm btn-success" type="submit" value="Save Information"/>
                        </div>
                    </div>

                    <input id="olevel_name" name="olevel_name" type="hidden">
                    <input id="alevel_name" name="alevel_name" type="hidden">

                    <?php echo form_close(); ?>



                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('.mydate_input').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            endDate:"30-12-2004"
        });


            $(".select50").select2({
                theme:'bootstrap',
                placeholder:'[ Select Country ]',
                allowClear:true
            });
            $(".select51").select2({
                theme:'bootstrap',
                placeholder:'[ Select Nationality ]',
                allowClear:true
            });

        $("#email").blur(function(){
            $('input[name="username"]').val(this.value);
            $("#username").prop("readonly", true);


        });



    })

    function checkYear(value_year,indexno,level)
    {

        if(value_year!="")
        {
           // alert(value_year + indexno + level);

            //alert("nipooo" + value_year + indexno);
            loadAjaxData(indexno,value_year,'',level);




        }

    }

    function loadAjaxData(value,target_field,get_focused_field,action) {
       // alert("nipoo");
        if($.trim(value)=='')
        {
            exit;
        }

        $.ajax({
            type:"post",
            url:"<?php echo site_url('loadEducationData') ?>",
            data: {
                target:target_field,
                id:value,
                ffocus:get_focused_field,
                action:action
            },
            datatype:"text",
            success:function(data)
            {
                var my_data_array;
                my_data_array= data.split("_");
                if(action=='o-level')
                {

//alert(data);


                    if(my_data_array[0]=="EQ")
                    {

                        $("#o_completed_year").prop("readonly", false);
                    }else
                    {
                        $("#o_completed_year").prop("readonly", true);
                    }
                    if(my_data_array.length>1)
                    {
                        $("#firstname").val(my_data_array[0]);
                        $("#lastname").val(my_data_array[1]);
                        $("#middlename").val(my_data_array[2]);
                        $("#o_completed_year").val(my_data_array[3]);
                        $("#olevel_name").val(my_data_array[4]);
                        //alert($("#olevel_name").val())
                    }



                }
                if(action=='a-level')
                {
                  // alert(data);

                    if(my_data_array[0]=="EQ")
                    {

                        $("#a_completed_year").prop("readonly", false);
                    }else
                    {
                        $("#a_completed_year").prop("readonly", true);
                    //alert(data +'ok');
                    }
                    if(data!="" &&  data!='EQ')
                    {
                        $("#school").val(my_data_array[0]);
                        $("#alevel_name").val(my_data_array[1]);
                        $("#a_completed_year").val(my_data_array[2]);
                        // alert($("#alevel_name").val())
                    }
                }



                if(action=='avn')
                {


                    $("#institution").val(my_data_array[0]);
                    $("#alevel_name").val(my_data_array[1]);
                    //alert($("#alevel_name").val())

                }
            }
        });

    }
</script>
