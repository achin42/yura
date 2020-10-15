<div class="signin-form recover-form onboard-form">
    <h2>Change Password</h2>
    <form name="frm_change_password" id="frm_change_password" action="<?php echo $this->config->item("site_url");?>login/fn_action_change_password" method="post" data-toggle="validator" novalidate="true" >
        <input type="hidden" name="forgot_password_user_email" id="forgot_password_user_email" value="<?php echo $forgot_password_user_email; ?>" >
        <input type="hidden" name="forgot_password_user_verification_code" id="forgot_password_user_verification_code" value="<?php echo $forgot_password_user_verification_code; ?>" >
        <div class="form-group">
            <label for="email"><strong>New Password</strong></label>
            <div class="input-group">                                  
                <input type="password" name="change_password_field" id="change_password_field" required minlength="6" class="form-control with-icon" data-required-error="Password cannot be blank" data-error="Password is invalid." >
                <span><i class="fas fa-eye toggle-password"  toggle="#change_password_field"></i></span>
            </div>
            <span class="help-block with-errors"></span> 
        </div>
        <div class="form-group">
            <label for="email" class="d-flex align-items-center justify-content-between">
                <strong>One Time Password</strong>
            </label>
            <div class="verif-code d-flex align-items-center justify-content-between">
                <div class="verif-code-box">
                    <input type="text" name="forgot_password_verification_code_1" id="forgot_password_verification_code_1" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                    <input type="text" name="forgot_password_verification_code_2" id="forgot_password_verification_code_2" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                    <input type="text" name="forgot_password_verification_code_3" id="forgot_password_verification_code_3" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                    <input type="text" name="forgot_password_verification_code_4" id="forgot_password_verification_code_4" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                    <input type="text" name="forgot_password_verification_code_5" id="forgot_password_verification_code_5" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                    <input type="text" name="forgot_password_verification_code_6" id="forgot_password_verification_code_6" required maxlength="1" min="0" max="9" pattern="[0-9]{1}" data-required-error="One Time Password cannot be blank." />
                </div>
                <label><em id="resend_change_password_email">resend email</em></label>
            </div> 
            <span class="help-block with-errors"></span>
        </div>                    
        <div class="form-group form-button">
            <input type="button" name="submit" id="change_password_btn" class="btn btn-default" value="Submit" onclick="funAjaxPostChangePassword(this.form)">
            <button type="button" id="wait_change_password_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>
        </div>
    </form>                            
</div>