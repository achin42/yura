<!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?php echo $this->config->item("site_url");?>assets/images/favicon.png" sizes="32x32" />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/plugin.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/style.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/developer.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/croppie.css"> 
        <title><?php echo $page_title;?></title>
        <script type="text/javascript">
            var site_url = '<?php echo $this->config->item("site_url")?>';
        </script>
    </head>
    <body class="member-page">
        <!-- ============================ Signin Start =========================== -->
        <div id="signup_html"> 
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
                                <p>You've been invited to join as a client. Please set your password and continue</p>
                                <form name="frm_invited_signup_step_1" id="frm_invited_signup_step_1" action="<?php echo $this->config->item("site_url");?>login/fn_invited_signup_step_1" method="post" data-toggle="validator" novalidate="true">
                                    <input type="hidden" name="invited_users_sk" id="invited_users_sk" value="<?php echo $invited_users_sk;?>" />
                                    <input type="hidden" name="invited_email" id="invited_email" value="<?php echo $invited_email;?>" /> 
                                    <div class="form-group">
                                        <label for="email" class="d-flex align-items-center justify-content-between">
                                            <strong>Email</strong>                                            
                                        </label>
                                        <div class="field-with-icon">
                                            <input type="email" name="sign_up_email" id="sign_up_email" class="form-control with-icon" disabled value="<?php echo $invited_email;?>" >
                                            <span class="lock-icon"><i><img src="<?php echo $this->config->item("site_url");?>assets/images/lock-icon.svg" alt="" /></i></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="invited_password_field"><strong>Password</strong></label>
                                        <div class="input-group">                                  
                                            <input type="password" name="invited_password_field" id="invited_password_field" required minlength="6" class="form-control with-icon" data-required-error="Password cannot be blank." data-error="Password is invalid." >
                                            <span><i class="fas fa-eye toggle-password"  toggle="#invited_password_field"></i></span>
                                        </div>
                                        <span class="help-block with-errors"></span> 
                                    </div>
                                    <div class="form-group form-button">
                                        <input type="button" name="submit" id="btn_invited_signup_step_1" class="btn btn-default" value="Submit" onclick="funAjaxPostInvitedStep1(this.form)">
                                        <button type="button" id="wait_btn_invited_signup_step_1" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>                    
                                    </div>
                                </form>                                     
                                <p class="agree-text">Already have an account?  <a href="<?php echo $this->config->item("site_url");?>"><b>Sign In!</b></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
        <!-- ============================ Signup Start =========================== -->
        <script src="<?php echo $this->config->item("site_url");?>assets/js/jquery-3.4.1.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/popper.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrapvalidator.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/custom.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/croppie.js"></script>
        <?php $this->load->view('inc/js/common-js');?> 
        <?php $this->load->view('inc/js/invited-signup-js');?>
        <?php $this->load->view('inc/js/signup-js');?>
    </body>
</html>