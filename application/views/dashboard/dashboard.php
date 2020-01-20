<?php
$ayear = $this->common_model->get_academic_year()->row()->AYear;


?>
<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 navy-bg">
            <span style="font-size: 13px;" class="font-bold">ACCOUNT CREATED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(id) as counter FROM application WHERE AYear='$ayear' ")->row()->counter); ?></h2>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 blue-bg">
            <span style="font-size: 13px;" class="font-bold">APPLICATION SUBMITTED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(id) as counter FROM application WHERE AYear='$ayear' AND submitted=1 ")->row()->counter); ?></h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 yellow-bg">
            <span style="font-size: 13px;" class="font-bold">NO. OF APPLICATION PAID</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(p.id) as counter FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id) WHERE p.AYear='$ayear' ")->row()->counter); ?></h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 red-bg">
            <span style="font-size: 13px;" class="font-bold">FEE COLLECTED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT SUM(p.amount) as amount FROM application_payment as p INNER JOIN application as a ON (p.applicant_id=a.id) WHERE p.AYear='$ayear' AND p.msisdn <> '' ")->row()->amount); ?></h2>
        </div>
    </div>
</div>


<div class="row">

    <div class="ibox">
        <div class="ibox-content">
            <h3>Applicant Choice By Programmes</h3>
            <?php
            $programme_list = $this->common_model->get_programme()->result();
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">S/No</th>
                    <th>Programme Name</th>
                    <th style="width: 100px; text-align: center;">1<sup>st</sup> Choice</th>
                    <th style="width: 100px; text-align: center;">2<sup>nd</sup> Choice</th>
                    <th style="width: 100px; text-align: center;">3<sup>rd</sup> Choice</th>

                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($programme_list as $key=>$value){

                    $current_round=$this->db->query("select * from application_round where application_type=".$value->type)->row();
                    if($current_round)
                    {
                        $round=$current_round->round;
                    }else{
                        $round=1;
                    }
                    $first_choice =  $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice1='$value->Code' AND AYear='$ayear' AND round='$round' ")->row();
                    $second_choice = $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice2='$value->Code' AND AYear='$ayear' AND round='$round' ")->row();
                    $third_choice =  $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice3='$value->Code' AND AYear='$ayear' AND round='$round'")->row();

                    ?>
                <tr>
                    <td style="text-align: right;"><?php echo ($key+1) ?>.</td>
                    <td><?php echo $value->Name; ?></td>
                    <td style="text-align: right;"><?php echo number_format($first_choice->counter); ?></td>
                    <td style="text-align: right;"><?php echo number_format($second_choice->counter); ?></td>
                    <td style="text-align: right;"><?php echo number_format($third_choice->counter); ?></td>

                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>


    </div>
</div>
