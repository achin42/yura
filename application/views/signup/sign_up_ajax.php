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
            <div class="form-logo">
                <img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" />
            </div>
            <div class="signin-form onboard-form">
                <div class="signin-form-outer">
                    <h2>Sign Up</h2>
                    <p>We only allow work email ids to make sure we are working with authentic businesses</p>
                    <form name="frm_new_signup_step_1" id="frm_new_signup_step_1" action="<?php echo $this->config->item("site_url");?>login/fn_insert_new_signup_step_1" method="post" data-toggle="validator" novalidate="true">
                        <div class="form-group">
                            <label for="email"><strong>Email</strong></label>
                            <div class="input-group">                                  
                                <input type="email" name="sign_up_email" id="sign_up_email" value="<?php echo $user_email;?>" class="form-control with-icon" required placeholder="Type email id and press enter">
                                <span class="enter-press">
                                    <img src="<?php echo $this->config->item("site_url");?>assets/images/enter-icon.svg" alt="" onclick="submitEntrIconClick()" />
                                </span>
                            </div>
                            <span class="help-block display-hide">Please enter your work email address</span>
                        </div>
                    </form>
                    <p class="agree-text">Already have an account?  <a href="<?php echo $this->config->item("site_url");?>"><b>Sign In!</b></a></p>
                </div>
            </div>
        </div>
    </div>
</div>        