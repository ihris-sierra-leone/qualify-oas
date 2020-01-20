<?php

if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins" xmlns="http://www.w3.org/1999/html">
    <div class="ibox-title clearfix">

        <h5 style="font-size:xx-large"">Application Fee</h5>

    </div>
    <div class="ibox-content">
 <div class="row">
     <!--<div class="col-md-4">-->
 <div class="col-md-12">
 <?php
 //echo'$professional_info->category'.$professional_info->category;
 $uid = $CURRENT_USER->id;
 $ActiveYear=$this->common_model->get_academic_year()->row()->AYear;
 $Paid_amount = $this->applicant_model->get_paid_amount($APPLICANT->id,1);
 $amount_required = 100;
 if($invoice_info)
 {
      $file=$invoice_info->control_number.'.png';
     $url='http://41.59.225.216/qr/';
     $exists = remoteFileExists($url.'/Qrcode/images/'.$file);
     if(!$exists)
     {
             //$url=base_url()."Qrcode/qrcode.php";
            $this->collegeinfo = get_collage_info();

            //$student_info=$this->db->query("select * from students where id=".$invoice_info->student_id)->row();
             $date=date("Y-m-d",strtotime($invoice_info->timestamp));
             $message=array(
                 "opType"=>2,
                 "shortCode"=>"001001",
                 "billReference"=>$invoice_info->control_number,
                 "amount"=>$invoice_info->amount,
                 "billCcy"=>'TZS',
                 "billExprDt"=>date("Y-m-d",strtotime(($date .' + 360days'))),
                 "billPayOpt"=>3,
                 "billRsv01"=>$this->collegeinfo->Name.$APPLICANT->FirstName. ' ' . $APPLICANT->LastName
             );
             $message=json_encode($message);

             $postdata = array(
                 "title" => $message,
                 "control" =>$invoice_info->control_number ,

             );

             sendDataOverPost($url,$postdata);

     }
 }

 if($APPLICANT->application_type!=3)
 {
     $application_fee_current=APPLICATION_FEE;
 }else
 {
     $application_fee_current=APPLICATION_FEE_POSTGRADUATE;
 }
      if($Paid_amount < $amount_required){
 ?>

        <!-- <div class="col-md-8">-->
     <div class="col-md-12">

         <h2 style="font-size:xx-large"" >Use below control number for application payments </h2>
         <strong><p style="color:green;font-size:xx-large;font-weight: bold" >Control number :
                 <span><?php echo isset($invoice_info) ? $invoice_info->control_number : ''; ?></span></p></strong>
         <strong><p style="color:green;font-size:xx-large"">Amount required:
                 <span><?php /*if($professional_info->category==3){ echo number_format(STUDENT_APPLICATION_FEE); } else { echo number_format(APPLICATION_FEE);}*/
                     echo number_format($application_fee_current); ?></span> TSH </p></strong>
         <?php
         if($invoice_info)
         {
           if($invoice_info->control_number)
           {
               $file=$invoice_info->control_number.'.png';
               $url='http://41.59.225.216/qr/';
               $exists = remoteFileExists($url.'/Qrcode/images/'.$file);
               if($exists) {
                   echo'<img src="'.$url.'Qrcode/images/'.$file.'" width="260">';
               }
           }
         }

         ?>
     </div>

     <div class="col-md-8">

         <?php
         }else
         {
         ?>

         <div class="col-md-12">
             <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">
                 <thead>
                 <tr>
                     <th style="width: 50px;">S/No</th>
                     <th style="width: 100px;">Date</th>
                     <th style="width: 100px;">Control No</th>
                     <th style="width: 100px;">Mobile</th>
                     <th style="width: 100px;">Receipt</th>
                     <th style="width: 100px;">Amount</th>
                     <th style="width: 100px;">Payment For</th>
                     <th style="width: 100px;">Channel</th>
                     <th style="width: 100px;">Payment Provider</th>
                     <?php if($Paid_amount > $amount_required){
                         ?>
                         <th style="width: 100px;">Print Receipt</th>
                     <?php
                     } ?>


                 </tr>
                 </thead>
                 <tbody>
                 <?php
                 $page = 1;
                 foreach ($payments as $key => $value) {
                     $invoice_payed=$this->db->query("select * from invoices where id=" . $value->invoice_number)->row();
                     if($invoice_payed)
                     {
                         $payed_for=$invoice_payed->type;
                     }

                     ?>
                     <tr>
                         <td><?php echo $page; ?></td>
                         <td><?php echo $value->transaction_date; ?></td>
                         <td><?php echo $value->control_number; ?></td>
                         <td><?php echo $value->payer_mobile; ?></td>
                         <td><?php echo $value->receipt_number; ?></td>
                         <td><?php echo $value->paid_amount; ?></td>
                         <td><?php echo (isset($invoice_payed->type))?$invoice_payed->type:''; ?></td>
                         <td><?php echo $value->payment_channell; ?></td>
                         <td><?php echo $value->channel_name; ?></td>
                         <?php if($Paid_amount > $amount_required){
                             ?>
                             <td> <a href=" <?php  echo ($value->id)? site_url('print_receipt/'.encode_id($value->id)) : '#'; ?>/"<i class="fa fa-print"></i>Print Receipt</a></td>
                             <?php
                         } ?>
                         <!-- <td><?php// echo $value->msisdn; ?></td> -->
                     </tr>
                     <?php
                     $page++;
                 }
                 }?>
           </tbody>
       </table>



       </div>

  </div>

  <div class="form-group">
      <div class="col-lg-12 clearfix">
          <?php
          if (!isset($invoice_info) and $Paid_amount < $amount_required ) {
          ?>
                    <a class="btn-info btn-lg" href="javascript:void(0);"
                    id="request_control_number">Request Payment Control Number</a>
          <?php } ?>

      </div>
      <div class="col-lg-12 clearfix">
          <?php
          if ($Paid_amount < $amount_required and isset($invoice_info)) {
              ?>
              <div class=" col-lg-8 clearfix" align="right">
                  <a href=" <?php echo (isset($invoice_info->status) and $invoice_info->status==1)? site_url('print_invoice/'.encode_id($invoice_info->id)) : '#'; ?>/"<i class="fa fa-print fa-2x"></i>Print Invoice</a></td>
              </div>
              <div class="col-lg-4 clearfix">
                  <a href=" <?php echo (isset($invoice_info->status) and $invoice_info->status==1)? site_url('print_transfer/'.encode_id($invoice_info->id)) : '#'; ?>/"<i class="fa fa-print  fa-2x"></i>Print Transfer</a>
              </div>

          <?php } ?>





      </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <?php if($Paid_amount < $amount_required and isset($invoice_info)){ ?>
        <hr>
           <h5 style="color: blue;">NOTE : After Payment other link will be available for you to continue to fill your application form. If you fail to pay the application fee within 4 days, then your basic details will be deleted in our system</h5>
          <div style="padding-top: 20px;">
              <h2>Choose Method to Pay</h2>
            <div class="clearfix">

                <div class="col-md-4" style="text-align: center;">
                    <img style="width: 200px; height: 80px;"  src="<?php echo base_url() ?>/icon/tigo_pesa.png" class="pay_method1" title="tigopesa" >
                    <div style="margin-top: 10px;"><input type="radio"  value="tigopesa" name="pay_method" class="pay_method"/></div>
                </div>
                <div class="col-md-4" style="text-align: center;">
                    <img style="width: 170px; height: 80px;" src="<?php echo base_url() ?>/icon/mpesa.jpg" class="pay_method1" title="mpesa" >
                    <div style="margin-top: 10px;"><input type="radio"  value="mpesa" name="pay_method" class="pay_method"/></div>

                </div>

                <div class="col-md-3" style="text-align: center;">
                    <img style="width: 170px; height: 80px;" src="<?php echo base_url() ?>/icon/airtel.jpg" class="pay_method1" title="airtel" >
                    <div style="margin-top: 10px;"><input type="radio" value="airtel" name="pay_method" class="pay_method"/></div>
                </div>

            </div>

              <div id="tigopesa" style="display: none;">
                  <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Tigo Pesa : Follow steps to pay</div>
                   <div style="padding-left: 100px; font-size: 15px;">
                       1. Dial <b>*150*01#</b><br/>
                       2. Select  4  <b>" Pay Bill "</b> <br/>
                       3. Select 3  <b>" Enter Busness Number "</b><br/>
                       4. Enter <b>001001</b> <br/>
                       5. Enter Reference Number <b>" Enter your control number <?php echo isset($invoice_info) ? $invoice_info->control_number : ''; ?>"</b><br/>
                       6. Enter amount  <b>" Enter <?php echo $application_fee_current ?> "</b> <br/>
                       7. Enter Password  <b>" Enter your account Password "</b>
                   </div>
              </div>

              <div id="mpesa" style="display: none;">
                  <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">M-Pesa : Follow steps to pay</div>
                  <div style="padding-left: 100px; font-size: 15px;">
                      1. Dial <b>*150*00#</b><br/>
                      2. Select  4  <b>" Pay Bill "</b> <br/>
                      3. Select 4  <b>" Enter Busness Number "</b><br/>
                      4. Enter <b>001001</b> <br/>
                      5. Enter Reference Number <b>" Enter  your control number <?php echo isset($invoice_info) ? $invoice_info->control_number : ''; ?>"</b><br/>
                      6. Enter amount  <b>" Enter <?php echo $application_fee_current ?> "</b> <br/>
                      7. Enter Password  <b>" Enter your account Password "</b><br/>
                      8. Enter 1 <b>" To agree "</b>
                  </div>
              </div>

              <div id="airtel" style="display: none;" >
                  <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Airtel Money : Follow steps to pay</div>
                  <div style="padding-left: 100px; font-size: 15px;">
                      1. Dial <b>*150*60#</b><br/>
                      2. Select  5  <b>" Make Payments "</b> <br/>
                      3. Select 3  <b>" Enter Busness Number "</b><br/>
                      4. Enter <b>001001</b> <br/>
                      5. Enter Reference Number <b>" Enter your control number <?php echo isset($invoice_info) ? $invoice_info->control_number : ''; ?>"</b><br/>
                      6. Enter amount  <b>" Enter <?php echo $application_fee_current ?> "</b> <br/>
                      7. Enter Password  <b>" Enter your account Password "</b><br/>
                  </div>
              </div>
          </div>
        <?php } ?>


    </div>

  </div>
  </div>
     <?php if ($Paid_amount < $amount_required and isset($invoice_info)) { ?>
<!---->
<!--    <h2> To pay: Visit any branch or bank agent of CRDB or you can use CRDB internet banking as well as CRDB SimBanking-->
<!--      Reference number: <b>--><?php //echo isset($invoice_info) ? $invoice_info->control_number : ''; ?><!-- </b></h2>-->


     <h2><b>In Summary payment Procedures through
             mobile networks are as follows:</b></h2>
     <div style="padding-left: 90px; font-size: 20px;">
    <b> 1. Dial *150*01#, or *150*00# or *150*60# or *150*88# or *150*71#
     or *150*02# for (Tigo Pesa, M-Pesa, Airtel Money, Halo Pesa TTCL Pesa
     and Ezy Pesa) respectively.</b><br/>
     2. Select Pay bills</b><br/>
     3. Enter Busness Number <b>001001</b></b><br/>
         4. Enter Control Number <b><?php echo isset($invoice_info) ? $invoice_info->control_number : ''; ?></b><br/>
         5. Enter Due Amount <b><?php echo $application_fee_current ?> </b><br/>
     6. Confirm (By entering your pin number)
     <?php }?>
     </div>
 </div>


</div>



<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>
<script>
    $(document).ready(function () {

        $('.mydate_input').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            endDate:"31-12-2004"
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

    })
</script>

<script>
    $(document).ready(function () {



        $("#fake_pay").click(function () {
            $(this).html('Please wait.....');
            $.ajax({
                url:'<?php echo site_url('applicant/fakepay') ?>',
                type:'POST'
            });
        });


        $(".pay_method1").click(function () {
           var title = $(this).attr('title');
            $('input:radio[name=pay_method][value='+title+']').prop("checked","checked").change();
        });


        $(".pay_method").change(function () {
            var pay_method = $(this).val();
           if(pay_method =='tigopesa'){
               $("#tigopesa").show();
               $("#airtel").hide();
               $("#mpesa").hide();
           }else if(pay_method =='airtel'){
               $("#airtel").show();
               $("#tigopesa").hide();
               $("#mpesa").hide();
           }else if(pay_method =='mpesa'){
               $("#mpesa").show();
               $("#tigopesa").hide();
               $("#airtel").hide();
           }
        });

        <?php if(!is_section_used('APPLICATION_FEE',$APPLICANT_MENU)){ ?>
        setInterval(function(){
            $.ajax({
                type:"post",
                url:"<?php echo site_url('is_applicant_pay') ?>",
                datatype:"html",
                success:function(data)
                 {
                    if(data == '1'){
                        window.location.reload();
                    }
                  //do something with response data
                }
            });
        },3000)
        <?php } ?>

        $("#request_control_number").click(function () {
            $(this).html('Please wait.....');
            $.ajax({
                url:'<?php echo site_url('applicant/request_control_number') ?>',
                type:'POST',
                success:function(data)
                {
                        window.location.reload();
                    //do something with response data
                }
            });
        });

    });
</script>
