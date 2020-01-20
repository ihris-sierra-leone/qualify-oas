<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}

?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5><?php
            if (is_null($id) && !isset($action)) {
                echo 'Add New Fee Structure';
            } else if (!is_null($id) && isset($action)) {
                echo 'Edit Fee Structure Information';
            }
            ?></h5>
    </div>
    <div class="ibox-content">


        <?php


            echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>



            <div class="form-group"><label class="col-lg-3 control-label">Name : <span class="required">*</span></label>

                <div class="col-lg-8">
                    <input type="text" name="name"
                           value="<?php echo(isset($feestructureinfo) ? $feestructureinfo->name : set_value('name')) ?>"
                           class="form-control"/>
                    <?php echo form_error('name'); ?>
                </div>
            </div>


        <div class="form-group"><label class="col-lg-3 control-label">Fee Amount : <span class="required">*</span></label>

            <div class="col-lg-8">
                <input type="text" name="amount"
                       value="<?php echo(isset($feestructureinfo) ? $feestructureinfo->amount : set_value('amount')) ?>"
                       class="form-control"  onKeyPress="return numbersonly(event,this.value)"/>
                <?php echo form_error('amount'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Has Percentage Pay ? : <span class="required">*</span></label>
            <div class="col-lg-8">
                <select name="percentage" class="select2_search1 form-control " >
                    <?php
                    $sel = (isset($feestructureinfo) ? $feestructureinfo->percentage : set_value('percentage'));

                    ?>
                    <option  value=""></option>
                    <option <?php echo ($sel == 1 ? 'selected="selected"':''); ?> value="1">Yes</option>
                    <option <?php echo ($sel == 0 ? 'selected="selected"':''); ?> value="0">No</option>
                </select>
                <?php echo form_error('percentage'); ?>
            </div>
        </div>



        <div class="form-group"><label class="col-lg-3 control-label">GEPG Fee Category : <span class="required">*</span></label>
            <div class="col-lg-8">
                <select name="gepg_category_code" class="select2_search1 form-control " >
                    <option value="">[select GEPG category]</option>
                    <?php
                    $fee_list=$this->db->query("select * from fee_type")->result();
                    foreach($fee_list as $key=>$value)
                    {
                        $sel = (isset($feestructureinfo) ? $feestructureinfo->fee_code : set_value('gepg_category_code'));

                        ?>
                        <option <?php echo ($sel == $value->code ? 'selected="selected"':''); ?>  value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                        <?php

                    }
                    ?>           </select>
                <?php echo form_error('gepg_category_code'); ?>
            </div>
        </div>

        <div class="form-group">
                <div class="col-lg-offset-3 col-lg-8">
                    <input class="btn btn-sm btn-success" type="submit" value="Save Information"/>
                </div>
            </div>
            <?php echo form_close();
     ?>

    </div>
</div>

<script>
    $(function(){


        $(".select2_search1").select2({
            theme: "bootstrap",
            placeholder: " [ Select Option ] ",
            allowClear: true
        });

    })
</script>