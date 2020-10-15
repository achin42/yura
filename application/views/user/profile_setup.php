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
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/plugin/sweetalert/sweetalert.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/plugin/bootstrap-fileinput/bootstrap-fileinput.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/developer.css">
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/css/croppie.css"> 
        <title><?php echo $page_title;?></title>
        <script type="text/javascript">
            var site_url = '<?php echo $this->config->item("site_url")?>';
        </script>
    </head>
    <body class="pt-0">
        <div id="signup_html">
            <div class="new-logo-container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="new-logo-box"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="new-company-detail-container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="new-company-detail-box">
                                <div class="ncd-head d-flex align-items-center remove-left-padding">
                                    <?php /*?><div class="back-arrow-icon" id="back_from_profile_setup"><img src="<?php echo $this->config->item("site_url");?>assets/images/iconsicadd.png" alt="" /></div><?php */ ?>
                                    <div class="ncd-head-desc">
                                        <h2>Profile</h2>
                                        <p>These profile details would let your client know about you.</p>
                                    </div>
                                </div>
                                <div class="ncd-body">
                                    <div class="new-card-container px-0 py-0">
                                        <form name="frm_profile_setup" id="frm_profile_setup" action="<?php echo $this->config->item("site_url");?>user/fn_insert_profile_setup" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data" >
                                            <div class="user-profile-desc">
                                                <div class="user-profile-img">
                                                    <?php if($user_rows > 0){ ?>
                                                        <?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                                                            <img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" alt="" id="result_img" />
                                                        <?php } else {?>
                                                            <img src="<?php echo $this->config->item("site_url");?>assets/images/no-profile-image.svg" alt="" id="result_img" />
                                                        <?php } ?>                                                    
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
                                            <div class="new-form-container">
                                                <ul class="new-form-fields-list d-flex flex-wrap">
                                                    <li>
                                                        <div class="form-group">
                                                            <label for="first_name"><strong>First name</strong></label>
                                                            <input type="text" name="first_name" id="first_name" required data-required-error="First name cannot be blank." class="form-control" />
                                                            <span class="help-block with-errors"></span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-group">
                                                            <label for="last_name"><strong>Last name</strong></label>
                                                            <input type="text" name="last_name" id="last_name" required data-required-error="Last name cannot be blank." class="form-control" />
                                                            <span class="help-block with-errors"></span>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <!--div class="varify-btn">
                                                    <a href="#!" class="btn btn-default">NEXT</a>
                                                </div-->  
                                                <div class="varify-btn form-group form-button">
                                                    <input type="button" name="submit" id="btn_profile_setup" class="btn btn-default" value="NEXT" onclick="funAjaxPostProfileSetup(this.form)">
                                                    <button type="button" id="wait_btn_profile_setup" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> NEXT</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
        <script src="<?php echo $this->config->item("site_url");?>assets/js/jquery-3.4.1.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/popper.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrapvalidator.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/custom.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/croppie.js"></script>
        <?php $this->load->view('inc/js/common-js');?>
        <?php $this->load->view('inc/js/profile-setup-js');?>         
    </body>
</html>