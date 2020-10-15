<div class="member-container d-flex">
    <div class="member-left">
        <div class="member-desc">
            <h1>Get your best and most work done</h1>
            <p>by taking all the friction out of the agency-client relationship</p>
        </div>
        <div class="ml-image"><img src="<?php echo $this->config->item("site_url");?>assets/images/illustration.png"></div>
    </div>
    <div class="member-right">
        <div class="form-container">
            <div class="form-logo"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></div>
            <div class="signin-form onboard-form">
                <h2>Sign Up</h2>
                <p>We only allow work email ids to make sure we are working with authentic businesses</p>
                <form name="frm_new_signup_step_2" id="frm_new_signup_step_2" action="<?php echo $this->config->item("site_url");?>login/fn_insert_new_signup_step_2" method="post" data-toggle="validator" novalidate="true">
                    <div class="form-group">
                        <label for="email" class="d-flex align-items-center justify-content-between">
                            <strong>Email</strong>
                            <em id="change_email_address">change</em>
                        </label>
                        <div class="field-with-icon">
                            <input type="email" name="sign_up_email" id="sign_up_email" value="<?php echo $this->session->userdata('sess_user_email');?>" disabled class="form-control with-icon" >
                            <span class="lock-icon"><i><img src="<?php echo $this->config->item("site_url");?>assets/images/lock-icon.svg" alt="" /></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="d-flex align-items-center justify-content-between">
                            <strong>Verification code</strong>
                        </label>
                        <div class="verif-code d-flex align-items-center justify-content-between">
                            <div class="verif-code-box">
                                <input type="text" name="verification_code_1" id="verification_code_1" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                                <input type="text" name="verification_code_2" id="verification_code_2" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                                <input type="text" name="verification_code_3" id="verification_code_3" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                                <input type="text" name="verification_code_4" id="verification_code_4" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                                <input type="text" name="verification_code_5" id="verification_code_5" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                                <input type="text" name="verification_code_6" id="verification_code_6" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="Verification code cannot be blank." />
                            </div>
                            <label><em id="resend_email">resend email</em></label>
                        </div> 
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label for="email"><strong>Create Password</strong></label>
                        <div class="input-group">                                  
                            <input type="password" name="sign_up_password_field" id="sign_up_password_field" required minlength="6" class="form-control with-icon" data-required-error="Password cannot be blank" data-error="Password is invalid." >
                            <span><i class="fas fa-eye toggle-password"  toggle="#sign_up_password_field"></i></span>
                        </div>
                        <span class="help-block with-errors"></span> 
                    </div>
                    <div class="form-group form-button">
                        <input type="button" name="submit" id="btn_signup_step_2" class="btn btn-default" value="Submit" onclick="funAjaxPostNewSignUpStep2(this.form)">
                        <button type="button" id="wait_btn_signup_step_2" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>                    
                    </div>
                </form>
                <p class="agree-text">Already have an account?  <a href="<?php echo $this->config->item("site_url");?>"><b>Sign In!</b></a></p>
            </div>
        </div>
    </div>
</div>