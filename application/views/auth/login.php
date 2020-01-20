<div class="container">
<div class="row">
    <div class="col-md-5">
        <h3>Welcome to CFR - ONLINE ADMISSION SYSTEM </h3>
        <hr/>
        <h4>ESTABLISHMENT OF THE CENTRE</h4>
        The Tanzania – Mozambique Centre for Foreign Relations (CFR) is one of the higher learning institutions in Tanzania. It was established in 1978 following the agreement between the governments of the United Republic of Tanzania and the Republic of Mozambique. CFR enjoys a diplomatic status incorporated in Immunities and Privileges Act N0. 5 (1986).
<br/>
        Initially, the CFR used to train Foreign Service Officers of the two countries in the area of International relations and Diplomacy. Overtime, however, the foreign policy constituency expanded, which necessitated admitting students from a broad clientele to reflect both national and global changes. Moreover, new courses namely; Economic Diplomacy, Languages and Strategic Studies were introduced in the CFR’s training programmes.

       <h4>VISION</h4>
        To become a Regional Centre of Excellence, a highly demanded Think Tank in International Relations, Diplomatic and Strategic Studies.
                <h4>MISSION</h4>
        The pursuit of scholarly and strategic teaching, research, policy advocacy and outreach services in International Relations and Diplomatic Studies targeted at strategic national and regional needs.

        <br/>
        <!--            <li>CFR ONLINE ADMISSION SYSTEM is an efficiency system for applicants from different perspective academic areas to apply for different Universities by following simple and easiest steps to fulfil needs of application and admission to a particular University.</li><br>-->
<!--            <li>It is a reliable online application system without any intervening time and immediately  notification and status upon your application.</li><br>-->
            Wise and recommended to read and understand admission requirements before starting application process.
                <a href="javascript:void(0);" data-toggle="popover" role="button" title="" data-original-title="Admission Requirement <a style='float:right;' href='javascript:void(0);' class='close_popover'>X</a>" data-html="true" data-placement="bottom" data-content="1. <a target='_blank' href='<?php echo ADMISSION_REQUIREMENT_UNDERGRADUATE ?>'>Entry Qualifications</a> <br/><br/><br/>" style="font-size: 12px; font-weight: bold; text-decoration: underline;">Admission Requirements</a>
<!--            <li>Click link to start application: <a href="--><?php //echo site_url('registration_start'); ?><!--" style="font-weight: bold; text-decoration: underline;">Start Application</a>.  </li>-->

    </div>
    <div class="col-md-offset-1 col-md-6">
        <h3>Log in to your account </h3>
        <hr/>
        <div class="clear-form">
            <?php echo form_open('login'); ?>
            <div class="form-heading">
                <h3 style="border-bottom: 1px solid #555; padding-bottom: 4px;">Sign In</h3>
            </div>
            <div class="form-body">
                <div style="color: red; font-size: 12px;">
                    <?php if (isset($message)) {
                        echo $message;
                    } else if ($this->session->flashdata('message') != '') {
                        echo $this->session->flashdata('message');
                    }
                    ?>
                </div>

                <input type="text" name="identity" class="col-md-12" placeholder="Username">
                <?php echo form_error('identity'); ?>
                <input type="password" name="password" class="col-md-12" placeholder="Password">
                <?php echo form_error('password'); ?>
                <div class="body-split clearfix" style="margin-left: 30px;">
                    <div class="pull-left">
                        <label class="checkbox" style="font-size: 13px;">
                            <input type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-success pull-right" type="submit">Login</button>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <hr/>

                <p class="center">
                    <label> Don't you have account ?  : <a href="<?php echo site_url('registration_start'); ?>">Create Account</a> </label>
                </p>
                <!--                <p class="center">-->
                <!--                  <label> For applicant only : <a href="--><?php //echo site_url('registration_start'); ?><!--">Create Account</a> </label>-->
                <!--                </p>-->

                <!--                <p class="center">-->
                <!--                    <label> For Existing Member : <a href="--><?php //echo site_url('membership_start'); ?><!--">Click here</a> </label>-->
                <!--                </p>-->
                <p class="center">
                    <label style="font-size: 12px;">Forgot your Password ?  <a href="<?php echo site_url('forgot_password'); ?>">Click here</a> </label>
                </p>
            </div>
            </form>
        </div>
    </div>
</div>

</div>


