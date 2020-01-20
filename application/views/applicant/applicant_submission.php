<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title clearfix">
            <h5> <?php echo(is_section_used('SUBMIT', $APPLICANT_MENU) ? 'Review' : 'Submit') ?> Application</h5>
            <?php if ($APPLICANT->submitted != 0) { ?>
                <a href="<?php echo site_url('print_application/' . encode_id($APPLICANT->id)) ?>"
                   class="btn btn-success pull-right">Print Application</a>
            <?php } ?>
        </div>
    </div>

    <div class="ibox-content">

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Person Particulars
        </div>
        <div class="row">
            <div class="col-md-5">
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 40%;">First Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->FirstName; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Last Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->LastName; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Other Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->MiddleName; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Sex :</th>
                        <td class="no-borders"><?php echo $APPLICANT->Gender; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Birth Date :</th>
                        <td class="no-borders"><?php echo format_date($APPLICANT->dob, false); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Place of Birth :</th>
                        <td class="no-borders"><?php echo $APPLICANT->birth_place; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Marital Status :</th>
                        <td class="no-borders"><?php echo get_value('maritalstatus', $APPLICANT->marital_status, 'name'); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Nationality :</th>
                        <td class="no-borders"><?php echo get_value('nationality', $APPLICANT->Nationality, 'Name'); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5">
                <table class="table">
                    <tr>
                        <th class="no-borders">Country of Residence :</th>
                        <td class="no-borders"><?php echo get_value('nationality', $APPLICANT->residence_country, 'Country'); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Disability :</th>
                        <td class="no-borders"><?php echo get_value('disability', $APPLICANT->Disability, 'name'); ?></td>
                    </tr>

                    <tr>
                        <th class="no-borders" style="width: 40%;">Application Year :</th>
                        <td class="no-borders"><?php echo $APPLICANT->AYear; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Application Type :</th>
                        <td class="no-borders"><?php echo application_type($APPLICANT->application_type); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Entry Category :</th>
                        <td class="no-borders"><?php echo entry_type($APPLICANT->entry_category); ?></td>
                    </tr>

                </table>


            </div>
            <div class="col-md-2">
                <img src="<?php echo HTTP_PROFILE_IMG . $APPLICANT->photo; ?>" style="width: 130px;"/>
            </div>
        </div>


        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Contact Information
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 30%;">Mobile 1:</th>
                        <td class="no-borders"><?php echo $APPLICANT->Mobile1; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Mobile 2 :</th>
                        <td class="no-borders"><?php echo $APPLICANT->Mobile2; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Email :</th>
                        <td class="no-borders"><?php echo $APPLICANT->Email; ?></td>
                    </tr>

                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 40%;">Postal Address :</th>
                        <td class="no-borders"><?php echo $APPLICANT->postal; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Physcal Address :</th>
                        <td class="no-borders"><?php echo $APPLICANT->physical; ?></td>
                    </tr>

                </table>


            </div>
        </div>

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Next of Kin Information
        </div>
        <div class="row">
            <div class="col-md-6">
                <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Next of Kin
                    1
                </div>
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 30%;">Name:</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->name : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Mobile 1 :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->mobile1 : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Mobile 2 :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->mobile2 : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Email :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->email : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Postal :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->postal : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Relation :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[0]->relation : ''); ?></td>
                    </tr>

                </table>
            </div>
            <div class="col-md-6">
                <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Next of Kin
                    2
                </div>
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 30%;">Name:</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->name : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Mobile 1 :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->mobile1 : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Mobile 2 :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->mobile2 : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Email :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->email : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Postal :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->postal : ''); ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Relation :</th>
                        <td class="no-borders"><?php echo(isset($next_kin) ? $next_kin[1]->relation : ''); ?></td>
                    </tr>

                </table>

            </div>
        </div>

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Application Fee Payment
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Your Reference Number for payment is : <?php echo REFERENCE_START . $APPLICANT->id ?></h4>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 50px;">S/No</th>
                        <th style="text-align: center; width: 100px;">Reference</th>
                        <th style="text-align: center; width: 100px;">Mobile No</th>
                        <th style="text-align: center; width: 100px;">Receipt</th>
                        <th style="text-align: center; width: 100px;">Amount</th>
                        <th style="text-align: center; width: 150px;">Trans Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $payment_transaction = $this->db->where('applicant_id', $APPLICANT->id)->get("application_payment")->result();
                    $total_amount = 0;
                    $total_charge = 0;

                    foreach ($payment_transaction as $key => $value) {
                        $total_amount += $value->amount;
                        $total_charge += $value->charges;
                        ?>
                        <tr>
                            <td style="text-align: right;"><?php echo $key + 1; ?> .</td>
                            <td style="text-align: center;"><?php echo $value->reference ?></td>
                            <td style="text-align: center;"><?php echo $value->msisdn ?></td>
                            <td style="text-align: center;"><?php echo $value->receipt ?></td>
                            <td style="text-align: right;"><?php echo number_format($value->amount + $value->charges, 2) ?></td>
                            <td style="text-align: center;"><?php echo $value->createdon ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="text-align: right;" colspan="4">Total</td>
                        <td style="text-align: right;"><?php echo number_format($total_amount + $total_charge, 2) ?></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Education Background
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                foreach ($education_bg as $rowkey => $rowvalue) {
                    ?>

                    <div class="applicant_education_bg" style="margin-bottom: 50px;">

                        <div style="font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid blue; width: 70%; color: blue; font-weight: bold;"><?php echo entry_type_certificate($rowvalue->certificate); ?></div>

                        <table class="mytable2_educatiobbg">

                            <tr>
                                <th>Examination Authority : &nbsp; &nbsp;</th>
                                <td><?php echo $rowvalue->exam_authority; ?></td>
                            </tr>
                            <?php
                            if ($rowvalue->technician_type > 0) {
                                ?>
                                <tr>
                                    <th> Category :</th>
                                    <td><?php echo get_value('technician_type', $rowvalue->technician_type, 'name'); ?></td>
                                </tr>
                            <?php }
                            if ($rowvalue->programme_title <> '') { ?>
                                <tr>
                                    <th> Programme Title :</th>
                                    <td><?php echo $rowvalue->programme_title; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th> <?php echo(($rowvalue->certificate < 3 && $rowvalue->certificate != 1.5) ? 'Division' : ($rowvalue->certificate > 6 ? 'G.P.A/Degree Class':'G.P.A')) ?>
                                    :
                                </th>
                                <td><?php echo $rowvalue->division; ?></td>
                            </tr>
                            <tr>
                                <th> <?php echo(($rowvalue->certificate < 3 && $rowvalue->certificate != 1.5) ? 'Centre/School' : 'College/Institution/University') ?>
                                    :
                                </th>
                                <td><?php echo $rowvalue->school; ?></td>
                            </tr>
                            <tr>
                                <th>Country :</th>
                                <td><?php echo get_country($rowvalue->country); ?></td>
                            </tr>
                            <tr>
                                <th>Index Number :</th>
                                <td><?php echo $rowvalue->index_number; ?></td>
                            </tr>
                                <tr>
                                    <th>Completed Year:</th>
                                    <td><?php echo $rowvalue->completed_year; ?></td>
                                </tr>

                        </table>

                        <br/>
                        <?php if ($rowvalue->certificate < 3 && $rowvalue->certificate != 1.5) { ?>
                            <strong>SUBJECT LIST</strong>
                            <br/>
                            <table cellpadding="0" cellspacing="0" class="table table-bordered"
                                   id="mytable" style="width: ">
                                <thead>
                                <tr>
                                    <th style="width: 70px;">S/No.</th>
                                    <th>SUBJECT</th>
                                    <th style="width: 150px; text-align: center;">GRADE</th>
                                    <th style="width: 150px; text-align: center;">YEAR</th>
                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $sno = 1;
                                $subject_saved = $this->applicant_model->get_education_subject($rowvalue->applicant_id, $rowvalue->id);
                                foreach ($subject_saved as $k => $v) {
                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle; text-align: center"><?php echo($k + 1); ?></td>
                                        <td><?php echo get_value('secondary_subject', $v->subject, 'name'); ?></td>
                                        <td style="text-align: center"><?php echo $v->grade; ?></td>
                                        <td style="text-align: center"><?php echo $v->year; ?></td>
                                    </tr>
                                    <?php $sno++;
                                } ?>
                                </tbody>
                            </table>
                        <?php } ?>

                    </div>
                <?php } ?>


            </div>
        </div>

        <?php if ($APPLICANT->application_type == 3) { ?>
            <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
                Professional Experience
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach (experience() as $key1 => $value1) { ?>
                        <div style="font-size: 14px; margin-bottom: 10px; border-bottom: 1px solid blue; width: 70%; color: blue; font-weight: bold;"><?php echo $value1; ?></div>
                        <?php
                        $tmp = '';
                        switch ($key1) {
                            case 1:
                                $tmp = '<table class="table" style="font-size: 13px;">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 40%;">Hospital/Institute</th>
        <th>Address</th>
                          </tr>
                          </thead>
                          <tbody>';
                                $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 1)->result();
                                if (count($data_list) > 0) {
                                    foreach ($data_list as $dk => $dv) {
                                        $tmp .= '<tr>
                                      <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                      <td>' . $dv->name . '</td>
                                      <td>' . $dv->column1 . '</td>
                                      
                                  </tr>';
                                    }
                                } else {
                                    $tmp .= '<tr>
                                  <td colspan="3">No data found !!</td>
                              </tr>';
                                }
                                $tmp .= '</tbody>
                          </table>';
                                break;

                            case 2:
                                $tmp .= '<table class="table" style="font-size: 13px;">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 40%;">Name of Institution</th>
        <th  style="width:20%;">Award Given</th>
        <th  style="width:20%;">Year of Completion</th>
                                </tr>
                                </thead>
                                <tbody>';

                                $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 2)->result();
                                if (count($data_list) > 0) {
                                    foreach ($data_list as $dk => $dv) {
                                        $tmp .= '<tr>
                                            <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                            <td>' . $dv->name . '</td>
                                            <td>' . $dv->column1 . '</td>
                                            <td>' . $dv->column2 . '</td>
                                        </tr>';
                                    }
                                } else {
                                    $tmp .= '<tr>
                                        <td colspan="3">No data found !!</td>
                                    </tr>';
                                }
                                $tmp .= '</tbody>
                                </table>';

                                break;

                            case 3:
                                $tmp = '<table class="table" style="font-size: 13px;">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 30%;">Post Held</th>
        <th  style="width:30%;">Employer</th>
        <th  style="width:20%;">When (Month/Year)</th>
                                </tr>
                                </thead>
                                <tbody>';

                                $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 3)->result();
                                if (count($data_list) > 0) {
                                    foreach ($data_list as $dk => $dv) {
                                        $tmp .= '<tr>
                                            <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                            <td>' . $dv->name . '</td>
                                            <td>' . $dv->column1 . '</td>
                                            <td>' . $dv->column2 . '</td>
                                        </tr>';
                                    }
                                } else {
                                    $tmp .= '<tr>
                                        <td colspan="5">No data found !!</td>
                                    </tr>';
                                }
                                $tmp .= '</tbody>
                                </table>';

                                break;

                            default:
                                break;
                        }
                        echo $tmp.'<br/>';
                        /***************************END OF SWITCH **************************/

                        ?>

                    <?php } ?>
                </div>
            </div>

            <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
                Academic Referees
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Referee 1
                    </div>
                    <table class="table">
                        <tr>
                            <th class="no-borders" style="width: 30%;">Name:</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[0]->name : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 1 :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[0]->mobile1 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 2 :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[0]->mobile2 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Email :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[0]->email : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Address :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[0]->address : ''); ?></td>
                        </tr>


                    </table>
                </div>
                <div class="col-md-6">
                    <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Referee 2
                    </div>
                    <table class="table">
                        <tr>
                            <th class="no-borders" style="width: 30%;">Name:</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[1]->name : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 1 :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[1]->mobile1 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 2 :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[1]->mobile2 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Email :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[1]->email : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Address :</th>
                            <td class="no-borders"><?php echo(isset($academic_referee) ? $academic_referee[1]->address : ''); ?></td>
                        </tr>

                    </table>

                </div>
            </div>

            <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
               Sponsor &  Current Employer Information
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Sponsor Information
                    </div>
                    <table class="table">
                        <tr>
                            <th class="no-borders" style="width: 30%;">Name:</th>
                            <td class="no-borders"><?php echo(isset($sponsor_info) ? $sponsor_info->name : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 1 :</th>
                            <td class="no-borders"><?php echo(isset($sponsor_info) ? $sponsor_info->mobile1 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 2 :</th>
                            <td class="no-borders"><?php echo(isset($sponsor_info) ? $sponsor_info->mobile2 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Email :</th>
                            <td class="no-borders"><?php echo(isset($sponsor_info) ? $sponsor_info->email : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Address :</th>
                            <td class="no-borders"><?php echo(isset($sponsor_info) ? $sponsor_info->address : ''); ?></td>
                        </tr>


                    </table>
                </div>
                <div class="col-md-6">
                    <div style="font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 15px;">Current Employer Information
                    </div>
                    <table class="table">
                        <tr>
                            <th class="no-borders" style="width: 30%;">Name:</th>
                            <td class="no-borders"><?php echo(isset($employer_info) ? $employer_info->name : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 1 :</th>
                            <td class="no-borders"><?php echo(isset($employer_info) ? $employer_info->mobile1 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Mobile 2 :</th>
                            <td class="no-borders"><?php echo(isset($employer_info) ? $employer_info->mobile2 : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Email :</th>
                            <td class="no-borders"><?php echo(isset($employer_info) ? $employer_info->email : ''); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders">Address :</th>
                            <td class="no-borders"><?php echo(isset($employer_info) ? $employer_info->address : ''); ?></td>
                        </tr>

                    </table>

                </div>
            </div>


        <?php } ?>

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Attachment
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered " id="mytable">
                    <thead>
                    <tr>
                        <th style="width: 50px;">S/No</th>
                        <th style="width: 150px;">Certificate</th>
                        <th>Comment</th>
                        <th style="width: 100px;">Attachment</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($attachment_list as $rowkey => $rowvalue) { ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;"><?php echo($rowkey + 1); ?></td>
                            <td><?php echo entry_type_certificate($rowvalue->certificate) ?></td>
                            <td><?php echo $rowvalue->comment ?></td>
                            <td><a href="javascript:void(0);" class="view_image"
                                   title="<?php echo $rowvalue->filename; ?>" W="800" H="500"
                                   type="<?php echo get_file_mimetype($rowvalue->attachment) ?>"
                                   url="<?php echo HTTP_UPLOAD_FOLDER . 'attachment/' . $rowvalue->attachment ?>"><i
                                            class="fa fa-eye"></i> View</a></td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>


                </table>

            </div>
        </div>


        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Programme Choices
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php
                if (isset($mycoice)) { ?>
                    <table class="table">
                        <tr>
                            <th class="no-borders" style="width: 20%;">First Choice :</th>
                            <td class="no-borders"><?php echo get_value('programme', array("Code" => $mycoice->choice1), 'Name'); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders" style="width: 20%;">Second Choice :</th>
                            <td class="no-borders"><?php echo get_value('programme', array("Code" => $mycoice->choice2), 'Name'); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders" style="width: 20%;">Third Choice :</th>
                            <td class="no-borders"><?php echo get_value('programme', array("Code" => $mycoice->choice3), 'Name'); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders" style="width: 20%;">Fourth Choice :</th>
                            <td class="no-borders"><?php echo get_value('programme', array("Code" => $mycoice->choice4), 'Name'); ?></td>
                        </tr>
                        <tr>
                            <th class="no-borders" style="width: 20%;">Fifth Choice :</th>
                            <td class="no-borders"><?php echo get_value('programme', array("Code" => $mycoice->choice5), 'Name'); ?></td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        </div>


        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Declaration
        </div>
        <div class="row">
            <div class="col-md-12">
                I, <b><?php echo $APPLICANT->FirstName . ' ' . $APPLICANT->LastName; ?></b> hereby declare that the
                information given in this form is true
                to the best of my knowledge
                <?php if ($APPLICANT->submitted == 0){ ?>
                <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid" id="uploadresult4"') ?>

                <div class="form-group" style="margin-top: 20px;">

                    <div class="col-lg-offset-1" style="font-weight: bold; color: red; font-size: 14px;">
                        NOTE :
                        <br/>1. Please confirm all information is correct before click button below
                        <br/>2. You will be able to make changes in your application e.g. change Programme Choices before the application deadline  <br/>

                    </div>
                </div>
                <input type="hidden" value="1" name="submit_app"/>

                <div class="form-group" style="margin-top: 10px;">
                    <div class="col-lg-offset-9">
                        <input class="btn btn-sm btn-success" id="submit_app" type="submit"
                               value=" Submit Application"/>
                    </div>

                    <?php echo form_close(); ?>
                    <?php } else {
                        ?>
                        <div style="text-align: right; margin-right: 50px; margin-top: 20px;"><a
                                    href="<?php echo site_url('print_application/' . encode_id($APPLICANT->id)) ?>"
                                    class="btn btn-success">Print Application</a></div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>

    </div>


</div>

<script>
    $(document).ready(function () {
        var form = $("#uploadresult4");
        $("#submit_app").confirm({
            title: "Confirm Submission",
            content: "Are you sure you want to submit this Application ?",
            confirmButton: 'YES',
            cancelButton: 'NO',
            confirmButtonClass: 'btn-success',
            cancelButtonClass: 'btn-success',
            confirm: function () {
                form.submit();
            },

            cancel: function () {
                return true;
            }
        });


        $(".view_image").click(function () {
            var title = $(this).attr("title");
            var url = $(this).attr("url");
            var type = $(this).attr("type");
            var width = $(this).attr("W");
            var height = $(this).attr("H");

            var model = $("#myModal");
            model.find(".modal-content").css({
                height: (parseInt(height) + 120) + "px",
                width: (parseInt(width) + 5) + "px"
            });
            model.find("#model_header").html(title);
            model.find("#model_header").show();

            var object = '<object data="{FileName}" type="' + type + '" width="' + width + 'px" height="' + height + 'px">';
            object += 'If you are unable to view file, you can download from <a href="{FileName}">here</a>';
            object += '</object>';
            object = object.replace(/{FileName}/g, url);
            model.find("#modal_body").css({height: height + "px", width: width + "px", padding: 0});
            model.find("#modal_body").html(object);
            model.find("#model_footer").show();
            model.modal();
        });

    })
</script>