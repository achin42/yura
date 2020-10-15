<!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?php echo $this->config->item("site_url");?>assets/images/favicon.png" sizes="32x32" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/plugin.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/style.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/developer.css"> 
        <title><?php echo $page_title;?></title>
        <script type="text/javascript">
            var site_url = '<?php echo $this->config->item("site_url")?>';
        </script>
    </head>
    <body class="member-page">
        <!-- ============================ Signin Start =========================== -->
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
                    <div class="signin-form" id="sign_in_div">
                        <h2>Sign In</h2>
                        <form name="frm_login" id="frm_login" action="<?php echo $this->config->item("site_url");?>login/fn_check_login" method="post" data-toggle="validator" novalidate="true" >
                            <div class="form-group">
                                <label for="email"><strong>Email</strong></label>
                                <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="sign_in_email" id="sign_in_email" class="form-control" placeholder="" required="required" data-required-error="Email cannot be blank." data-error="Email is invalid." >
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="form-group">
                                <label for="email"><strong>Password</strong><a href="javascript:void(0)" id="forgot_password_link"><em>forgot password?</em></a></label>                    
                                <div class="input-group">                                  
                                    <input type="password" name="sign_in_password" id="sign_in_password_field" class="form-control" required="required" data-required-error="Password cannot be blank." >      
                                    <span><i class="fas fa-eye toggle-password"  toggle="#sign_in_password_field"></i></span>
                                </div>
                                <span class="help-block with-errors"></span> 
                            </div>
                            <div class="form-group form-button">
                                <input type="button" name="submit" id="signin_btn" class="btn btn-default" value="Submit" onclick="funAjaxPostSignin(this.form)">
                                <button type="button" id="wait_signin_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>
                            </div>
                        </form>
                        <!--p class="agree-text">Don't have an account? <a href="javascript:void(0)" id="sign_up_link"><b>Sign Up!</b></a></p-->
                        <p class="agree-text">Don't have an account? <a href="<?php echo $this->config->item("site_url");?>sign-up" ><b>Sign Up!</b></a></p>
                    </div>
                    <div class="signin-form display-hide" id="sign_up_div">
                        <h2>Sign Up</h2>
                        <form name="frm_signup" id="frm_signup" action="<?php echo $this->config->item("site_url");?>login/fn_insert_signup" method="post" data-toggle="validator" novalidate="true">
                            <div class="form-group">
                                <label for="email"><strong>Email</strong></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="" required="required">
                            </div>
                            <div class="form-group">
                                <label for="name"><strong>Name</strong></label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label for="cmpny name"><strong>Company Name</strong></label>
                                <input type="text" name="company_name" id="company_name" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label for="email"><strong>Password</strong></label>
                                <div class="input-group">                                  
                                    <input type="password" name="password" id="sign_up_password_field" class="form-control" required="required" >
                                    <span><i class="fas fa-eye toggle-password"  toggle="#sign_up_password_field"></i></span>
                                </div> 
                            </div>
                            <div class="form-group form-button">
                                <input type="button" name="submit" id="signup_btn" class="btn btn-default" value="Submit" onclick="funAjaxPostSignup(this.form)">
                                <button type="button" id="wait_signup_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>
                            </div>
                        </form>
                        <p class="agree-text">Already have an account?  <a href="javascript:void(0)" id="sign_in_link"><b>Sign In!</b></a></p>
                    </div>
                    <div class="display-hide" id="forgot_password_div">
                        <div class="signin-form recover-form">
                            <h2>Recover Password</h2>
                            <form name="frm_forgot_password" id="frm_forgot_password" action="<?php echo $this->config->item("site_url");?>login/fn_action_forgot_password" method="post" data-toggle="validator" novalidate="true" >
                                <div class="form-group">
                                    <label for="email" class="d-flex align-items-center justify-content-between">
                                        <strong>Email</strong>
                                        <em>A one time password will be sent</em>
                                    </label>
                                    <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="forgot_password_email" id="forgot_password_email" class="form-control" placeholder="" required="required" data-required-error="Email cannot be blank." data-error="Email is invalid." >
                                </div>                    
                                <div class="form-group form-button">
                                    <input type="button" name="submit" id="forgot_password_btn" class="btn btn-default" value="Submit" onclick="funAjaxPostForgotPassword(this.form)">
                                    <button type="button" id="wait_forgot_password_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>
                                </div>
                            </form>                            
                        </div>
                        <div class="backtologin"> <a href="javascript:void(0)" id="back_to_login_link"><b>Login</b></a></div>
                    </div>
                    <div class="display-hide" id="recover_password_div"></div>                        
                </div>
            </div>                  
        </div>
        <!-- ============================ Signup Start =========================== -->
        <script src="<?php echo $this->config->item("site_url");?>assets/js/jquery-3.4.1.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrapvalidator.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/custom.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/home.js"></script>        
        <?php $this->load->view('inc/js/common-js');?> 
    </body>
</html>