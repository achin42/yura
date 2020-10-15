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
                    <div class="ncd-head d-flex align-items-center">
                        <div class="back-arrow-icon" id="back_from_profile_setup"><img src="<?php echo $this->config->item("site_url");?>assets/images/iconsicadd.png" alt="" /></div>
                        <div class="ncd-head-desc">
                            <h2>Profile</h2>
                            <p>These profile details would let your client know about you.</p>
                        </div>
                    </div>
                    <div class="ncd-body">
                        <div class="new-card-container px-0 py-0">
                            <form name="frm_new_signup_step_4" id="frm_new_signup_step_4" action="<?php echo $this->config->item("site_url");?>login/fn_insert_new_signup_step_4" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data" >
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
                                        <label for="user_profile_image" class="upload-btn">Upload a photo</label>
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
                                                <input type="text" name="first_name" id="first_name" required data-required-error="First name cannot be blank." class="form-control" value="<?php echo $first_name;?>" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="last_name"><strong>Last name</strong></label>
                                                <input type="text" name="last_name" id="last_name" required data-required-error="Last name cannot be blank." class="form-control" value="<?php echo $last_name;?>" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                    </ul>
                                    <!--div class="varify-btn">
                                        <a href="#!" class="btn btn-default">NEXT</a>
                                    </div-->  
                                    <div class="varify-btn form-group form-button">
                                        <input type="button" name="submit" id="btn_signup_step_4" class="btn btn-default" value="NEXT" onclick="funAjaxPostNewSignUpStep4(this.form)">
                                        <button type="button" id="wait_btn_signup_step_4" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> NEXT</button>
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