<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">
            <h5>Create Invoice/Bill</h5>      <div class=" pull-right"> <?php echo' '. form_error('amount'); ?></div>   <font color="blue">   <div  id="payment-footer" align="center" style="font-size:25px; color:#006"></div></font>
        </div>

    </div>

    <div class="ibox-content">

        <?php  echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>



<!--        <div class="form-group"><label class="col-lg-3 control-label">Invoice Amount  : <span class="required">*</span></label>-->
<!---->
<!---->
<!--            <div class="col-lg-7">-->
<!--                <input type="text"-->
<!--                       value=""-->
<!--                       class="form-control"  onKeyPress="return numbersonly(event,this.value)" name="amount"/>-->
<!--                --><?php //echo form_error('amount'); ?>
<!--            </div>-->
<!--        </div>-->

        <input type="hidden"  id="txtGrandTotal" name="amount"/>
        <input type="hidden"  id="actual_amount" name="actual_amount"/>

        <!--Dynamic table start here-->
<!--        <table class="table table-bordered table-hover" id="tab_logic">-->
<!--            <thead>-->
<!--            <tr >-->
<!--                <th class="text-center">-->
<!--                    #-->
<!--                </th>-->
<!---->
<!---->
<!--                <th class="text-center">-->
<!--                   Fee Name-->
<!--                </th>-->
<!---->
<!--                <th class="text-center">-->
<!--                    Amount-->
<!--                </th>-->
<!---->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr id='addr0'>-->
<!--                <td>-->
<!--                    1-->
<!--                </td>-->
<!---->
<!---->
<!--                <td>-->
<!---->
<!--                    <select class="form-control" style="width: 100%;" name="txtFeeName[]" id="txtFeeName_0"  onchange="ShowAmount(this.value,'txtAmount_0')">-->
<!--                        <option value="">Select Fee </option>-->
<!--                            --><?php
//
//
//                            $fee_list=$this->db->query("select * from fee_structure")->result();
//
//                            foreach($fee_list as $key=>$value)
//                            { ?>
<!--                        <option  value="--><?php //echo $value->id.'_'.$value->amount; ?><!--">--><?php //echo $value->name.' ( '.get_value('ayear',$value->a_year,'AYear').'-'.get_value('ayear',$value->a_year,'semester').' )'; ?><!--</option>-->
<!--                        --><?php
//
//                            }
//                    ?>
<!--                    </select>-->
<!--                </td>-->
<!---->
<!---->
<!--                <td>-->
<!---->
<!--                    <input type="text"-->
<!--                           value=""-->
<!--                           class="form-control payment"  onKeyPress="return numbersonly(event,this.value)" id="txtAmount_0" name="txtAmount[]" readonly/>-->
<!---->
<!---->
<!--                </td>-->
<!---->
<!---->
<!--            </tr>-->
<!--            <tr id='addr1'></tr>-->
<!--            </tbody>-->
<!--        </table>-->
<!---->
<!---->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-sm-3" for="Content"></label>-->
<!--            <div class="col-sm-5 ">-->
<!--                <br/>-->
<!--                <a id="add_row_openstock" class="btn btn-default pull-left">Add ++</a><a id='delete_row_openstock' class="pull-right btn btn-default">Delete --</a>-->
<!--            </div>-->
<!--        </div>-->

        <!--Dynamic table end here-->

        <div class="form-group"><label class="col-lg-3 control-label">Fee Category :  <span class="required">*</span></label>


            <div class="col-lg-7">
                <select class="form-control" style="width: 100%;" name="txtFeeName" id="txtFeeName"  onchange="ShowAmount(this.value,'txtGrandTotal')">
                    <option value="">Select Fee Category </option>
                    <?php
                    $fee_list=$this->db->query("select * from fee_structure")->result();
                    foreach($fee_list as $key=>$value)
                    { ?>
                        <option  value="<?php echo $value->id.'_'.$value->amount.'_'.$value->percentage; ?>"><?php echo $value->name; ?></option>
                        <?php

                    }
                    ?>
                </select>
                <?php echo form_error('txtFeeName'); ?>
            </div>
        </div>


    <div id="show_percentage" style="display:none">
        <div class="form-group"><label class="col-lg-3 control-label">Percentage to Pay :  <span class="required">*</span></label>
            <div class="col-lg-7">
                <select name="percentage" class="select2_search1 form-control" onchange="CalculatePercentage(this.value)" >
                    <option value="100" selected>100%</option>
                    <option value="75">75%</option>
                    <option value="50">50%</option>
                    <option value="25">25%</option>
                </select>

            </div>
        </div>
    </div>



        <div class="form-group"><label class="col-lg-3 control-label">Registration Number : </label>


            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="regno" />
                <?php echo form_error('regno'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Surname  : <span class="required">*</span></label>


            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="surname" />
                <?php echo form_error('surname'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Fist Name  : <span class="required">*</span></label>


            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="firstname" />
                <?php echo form_error('firstname'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Other Name  : <span class="required"></span></label>
            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="othername" />
                <?php echo form_error('othername'); ?>
            </div>
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Email   : <span class="required"></span></label>
            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="email" />
                <?php echo form_error('email'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Mobile   : <span class="required">*</span></label>
            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="mobile"  placeholder="Eg. 0xxxxxxxxx" onKeyPress="return numbersonly(event,this.value)" maxlength="10" />
                <?php echo form_error('mobile'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Address   : <span class="required"></span></label>
            <div class="col-lg-7">
                <input type="text"
                       value=""
                       class="form-control"  name="address" />
                <?php echo form_error('address'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Description   : <span class="required"></span></label>
            <div class="col-lg-7">
                <textarea name="description" rows="3" cols="87" class="form-control"></textarea>
                <?php echo form_error('description'); ?>
            </div>
        </div>


        <div class="form-group" style="margin-top: 10px;">
            <div class=" col-lg-12">
                <input class="btn btn-sm btn-success pull-right" type="submit" value="Get Control Number"/>
            </div>
        </div>


        <?php echo form_close(); ?>


<br/>

        <br/><br/>
        <br/><br/>
    </div>
</div>

<script>
    $(document).ready(function () {
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
    });



    var i=1;
    $("#add_row_openstock").click(function(){


        $('table#tab_logic tr:last').index()
        var currenttable;


            currenttable= "<td>" +
                '<select  name="txtFeeName[]"  id="txtFeeName_'+ i +'" class="form-control select2" style="width: 100%;" onchange=\'ShowAmount(this.value,"txtAmount_' +  i + '")\' required></select>'

        currenttable += "<td>" +
            '<input type="text" class="form-control payment" id="txtAmount_'+ i +'" name="txtAmount[]"  onblur=\'loadAjaxData(this.value,"txtUnit_' +  i + '","txtItemName_' + i +'","oppenitemid","txtQuantity_' + i + '")\'  required="required" readonly/>'
            +"</td>"


        $('#addr'+i).html("<td>"+ (i+1) +"</td>" + currenttable);

        var $options = $("#txtFeeName_0 > option").clone();
        $('#txtFeeName_' + i).append($options);


        $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
        i++;
        $('.select2').select2();

    });
    $("#delete_row_openstock").click(function(){
        if(i>1){
            $("#addr"+(i-1)).html('');
            i--;
        }
        GranTotal();
    });

    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }


    function CalculatePercentage(percentage) {

        if(!isNaN(percentage)) {
            amount=parseFloat($("#actual_amount").val());
            percentage=parseFloat(percentage);
            amount=parseFloat((amount * percentage)/100);
            $("#payment-footer").html("Total Amount=" + addCommas(amount.toFixed(0)));

            $("#txtGrandTotal").val(amount.toFixed(0));
        }
    }
    function GranTotal(amount)
    {

        var sum = 0;
        //Loop through all the textboxes of datatable and make sum of the values of it.
        $(".payment").each(function(){
            sum = sum + parseFloat($(this).val());
        });
        //Replace the latest sum.
        if(!isNaN(sum))
        {
            $("#payment-footer").html("Total Amount=" + addCommas(sum.toFixed(0)));

            $("#txtGrandTotal").val(sum.toFixed(0));
        }

       // alert(amount)
        if(!isNaN(amount)) {
            amount=parseFloat(amount)
            $("#payment-footer").html("Bill Amount=" + addCommas(amount.toFixed(0)));
            $("#txtGrandTotal").val(amount.toFixed(0));
        }
        // alert($("#txtGrandTotal").val());
    }


    function ShowAmount(data,amount_field) {
        var my_data_array;
        my_data_array= data.split("_");
        my_array_field=amount_field.split("_");
        var already;
        var i;
        already=0;
        if(my_array_field[1]>=1)
        {

            for(i=0;i<my_array_field[1];i++)
            {

                if($("#txtFeeName_" + i).val()==data)
                {

                    already=1;
                    break;
                }

            }


            if(already==0)
            {
                $("#" + amount_field).val(my_data_array[1]);
            }else{
                already=0;
            }

        }else{
            $("#" + amount_field).val(my_data_array[1]);
        }
        if(my_data_array[2]==1)
        {
            $('#show_percentage').show();
        }else{
            $('#show_percentage').css('display','none');
        }

        $("#actual_amount").val(my_data_array[1]);
        GranTotal(my_data_array[1]);
    }

</script>