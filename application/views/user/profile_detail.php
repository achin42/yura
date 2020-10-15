<!doctype html>
    <html lang="en">
    <head>
        <?php $this->load->view('inc/header-include');?>
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/croppie.css">
    </head>
    <body>
        <!-- ======================== Topbar start ========================== -->
            <?php $this->load->view('inc/header-menu');?>
        <!-- ======================== Topbar end ========================== -->
        <?php
            $logged_in_users_sk = $this->session->userdata('fe_user');
            $logged_in_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
            $logged_in_user_rows = $logged_in_user_result['rows']; 
            $logged_in_user_data = $logged_in_user_result['data'];
            
            $logged_in_company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
            $logged_in_company_rows = $logged_in_company_result['rows']; 
            $logged_in_company_data = $logged_in_company_result['data'];
            
            $logged_in_user_type = $logged_in_user_data[0]->user_type;
            $logged_in_is_agency_verified = $logged_in_company_data[0]->is_agency_verified;
            $logged_in_verify_later = $logged_in_company_data[0]->verify_later;
            $logged_in_applied_for_manual_verification = $logged_in_company_data[0]->applied_for_manual_verification;
        ?>
        <!-- ======================== Main container start ========================== -->
        <div class="main-container">
            <div class="main-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="payment-mode-box">
                                <div class="payment-mode-header">
                                    <?php $this->load->view('inc/profile-common-menu');?>                                                      
                                </div>
                                <div class="new-card-container">
                                    <div class="new-form-container">
                                        <form name="frm_profile_detail" id="frm_profile_detail" action="<?php echo $this->config->item("site_url");?>user/fn_update_profile_detail" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                                            <input type="hidden" name="users_sk" id="users_sk" value="<?php echo $user_data[0]->users_sk;?>">
                                            <div class="user-profile-desc">
                                                <div class="user-profile-img" id="result_div">
                                                    <?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                                                        <img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" alt="" id="result_img" />
                                                    <?php } else {?>
                                                        <img src="<?php echo $this->config->item("site_url");?>assets/images/no-profile-image.svg" alt="" id="result_img" />
                                                    <?php } ?>                                                    
                                                </div>
                                                <div class="file-upload">
                                                    <?php if($user_data[0]->user_image != '' && file_exists($full_file_path) ) { ?>
                                                        <label for="user_profile_image" class="upload-btn">Change photo</label>
                                                    <?php }else{ ?>
                                                        <label for="user_profile_image" class="upload-btn">Upload a photo</label>
                                                    <?php } ?>    
                                                    <input type="file" id="user_profile_image" name="user_profile_image">
                                                </div> 
                                                <input type="hidden" name="cropped_img" id="cropped_img" >
                                                <div class="crop-image-popup display-hide" id="crop_image_popup">
                                                    <div class="crop-image-head">Crop Image</div>
                                                    <div class="crop-image-body">
                                                        <div id="crop_image_div">
                                          					<img id="crop_image" src="#" class="display-hide" />
                                          				</div>
                                                    </div>
                                                    <div class="crop-image-foot">  
                                                        <a href="javascript:void(0)" class="link link2 btn btn-xlg no-shadow" id="btn_crop_cancel" ><strong>Cancel</strong></a>
                                                        <div class="display-hide" id="crop_button_div">     
                                    					   <button type="button" id="btn_crop_image" class="btn btn-primary">Use Photo</button>
                                    					   <!--button type="button" id="rotate_image_left" class="btn btn-primary" data-rotate="-90">Rotate Left</button>
                                    					   <button type="button" id="rotate_image_right" class="btn btn-primary" data-rotate="90">Rotate Right</button-->
                                    				    </div>
                                                    </div>                                                        
                                                </div> 
                                                <div class="modal-backdrop display-hide" id="crop_image_overlay"></div>   
                                                <!--div class="display-hide" id="cropagain_button_div">
                                                    <button type="button" id="cropped_again" class="btn btn-primary">Crop Again</button>
                                				</div-->                                                                                                                     
                                            </div>
                                            <ul class="new-form-fields-list d-flex flex-wrap">
                                                <li>
                                                    <div class="form-group">
                                                        <label for="firstname"><strong>First Name</strong></label>
                                                        <input type="text" name="first_name" id="first_name" value="<?php echo $user_data[0]->first_name;?>" required data-required-error="First name cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="lastname"><strong>Last Name</strong></label>
                                                        <input type="text" name="last_name" id="last_name" value="<?php echo $user_data[0]->last_name;?>" required data-required-error="Last name cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li class="add-margin-bottom-60">
                                                    <div class="form-group">
                                                        <label for="email"><strong>Email</strong></label>
                                                        <div class="field-with-icon">
                                                            <input type="email" name="email" id="email" disabled value="<?php echo $user_data[0]->email;?>" class="form-control with-icon" >
                                                            <span class="lock-icon"><i><img src="<?php echo $this->config->item("site_url");?>assets/images/lock-icon.svg" alt="" /></i></span>    
                                                        </div>
                                                    </div>
                                                </li>                                            
                                            </ul> 
                                            <div class="form-group form-button remove-bottom-margin">
                          						<input type="button" name="submit" id="btn_profile_detail" class="btn btn-default" value="Save Changes" onclick="funAjaxPostProfileDetail(this.form)">
                          						<button type="button" id="wait_btn_profile_detail" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Save Changes</button>
                          					</div>
                                        </form>                                          
                                        <span class="hr-line"></span>
                                        <h3>CHANGE PASSWORD</h3>
                                        <form name="frm_change_password" id="frm_change_password" action="<?php echo $this->config->item("site_url");?>user/fn_update_password" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                                            <input type="hidden" name="change_password_users_sk" id="change_password_users_sk" value="<?php echo $user_data[0]->users_sk;?>">
                                            <ul class="new-form-fields-list d-flex flex-wrap">
                                                <li>
                                                    <div class="form-group">
                                                        <label for="password"><strong>New password</strong></label>                    
                                                        <div class="input-group">                                  
                                                            <input type="password" name="new_password_field" id="new_password_field" required minlength="6" data-required-error="Password cannot be blank" data-error="Password is invalid." class="form-control with-icon" >
                                                            <span><i class="fas fa-eye toggle-password-profile"  toggle="#new_password_field"></i></span>
                                                        </div>
                                                        <span class="help-block with-errors"></span> 
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="form-group cp-submit-btn">
                                                <input type="button" name="submit" id="btn_change_password" class="btn blue-btn ml-0" value="Change Password" onclick="funAjaxPostChangePassword(this.form)">
                          						<button type="button" id="wait_btn_change_password" class="btn blue-btn ml-0 display-hide"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Change Password</button>
                                            </div>
                                        </form>    
                                        <span class="hr-line"></span>
                                        <div class="logout-btn">
                                            <a href="<?php echo $this->config->item("site_url");?>user/logout" class="btn btn-logout"><i><img src="<?php echo $this->config->item("site_url");?>assets/images/logout-icon.svg" alt="" /></i>Logout</a>
                                        </div>
                                    </div>
                                    
                                    <?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>  
                                        <?php if($logged_in_is_agency_verified == '0'){ ?>
                                            <?php if($logged_in_verify_later == '1'){ ?>
                                                <div class="new-varify-box d-flex align-items-center justify-content-between">
                                                    <div class="nv-content">
                                                        <strong>Company details are not verified yet</strong>
                                                        <div class="dropdown">
                                                            <em class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Why we verify company details?</em>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <p>To provide a seamless contract management experience, where users can sign agreements and even send and receive payments, we verify the authenticity of each agency. You can still explore the other features of the platform before verifying.</p>
                                                                <div class="gotit-btn"><a href="javascript:void(0)" class="btn btn-third btn-block">Got it!</a></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="nv-btn">
                                                        <a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="btn btn-primary">VERIFY NOW</a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                                                <div class="new-varify-box profile-pending d-flex align-items-center justify-content-between">
                                                    <div class="nv-content">
                                                        <strong>Awaiting manual verification of Company details</strong>
                                                        <div class="dropdown">
                                                            <em class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Why we verify company details?</em>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <p>To provide a seamless contract management experience, where users can sign agreements and even send and receive payments, we verify the authenticity of each agency. You can still explore the other features of the platform before verifying.</p>
                                                                <div class="gotit-btn"><a href="#!" class="btn btn-third btn-block">Got it!</a></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                                            <div class="new-varify-box d-flex align-items-center justify-content-between">
                                                <div class="nv-content">
                                                    <strong>Company details are not verified yet</strong>
                                                    <div class="dropdown">
                                                        <em class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Why we verify company details?</em>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <p>To provide a seamless contract management experience, where users can sign agreements and even send and receive payments, we verify the authenticity of each agency. You can still explore the other features of the platform before verifying.</p>
                                                            <div class="gotit-btn"><a href="#!" class="btn btn-third btn-block">Got it!</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nv-btn">
                                                    <a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="btn btn-primary">VERIFY NOW</a>
                                                </div>
                                            </div>                                                        
                                        <?php } ?>                                                
                                    <?php } ?>
                                    
                                    
                  
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
        </div>
        <!-- ======================== Main container end ========================== -->
    
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/profile-detail-js');?>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/croppie.js"></script>
  </body>
</html>