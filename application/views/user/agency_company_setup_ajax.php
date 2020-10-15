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
                        <div class="back-arrow-icon" id="back_from_company_detail"><img src="<?php echo $this->config->item("site_url");?>assets/images/iconsicadd.png" alt="" /></div>
                        <div class="ncd-head-desc">
                            <h2>Company detail</h2>
                            <p>Enter your company's legal details. We will verify these details to make sure of your authenticity.</p>
                        </div>
                    </div>                      
                    <div class="ncd-body">
                        <div class="new-card-container px-0 py-0">
                            <form name="frm_agency_company_setup" id="frm_agency_company_setup" action="<?php echo $this->config->item("site_url");?>user/fn_insert_agency_company_setup" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data" >
                                <div class="user-profile-desc">
                                    <div class="user-profile-img">
                                        <img src="<?php echo $this->config->item("site_url");?>assets/images/no-logo-image.svg" alt="" id="result_img" />
                                    </div>
                                    <div class="file-upload">
                                        <label for="company_logo_image" class="upload-btn">Upload company logo</label>
                                        <input type="file" id="company_logo_image" name="company_logo_image">
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
                                                <label for="company_name"><strong>Company name</strong></label>
                                                <input type="text" name="company_name" id="company_name" required data-required-error="Company name cannot be blank." class="form-control" <?php if($company_rows > 0){ ?>value="<?php echo $company_data[0]->company_name; ?>"<?php } ?> />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="company_domain"><strong>Domain</strong></label>
                                                <select name="company_domain" id="company_domain" required data-required-error="Domain cannot be blank." class="form-control">
                                                    <option value="Designing">Designing</option>
                                                    <option value="Development">Development</option>
                                                    <option value="Marketing">Marketing</option>
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="chamber_of_commerce_number"><strong>Chamber of commerce number</strong></label>
                                                <input type="text" name="chamber_of_commerce_number" id="chamber_of_commerce_number" required data-required-error="Chamber of commerce number cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="vat_number"><strong>VAT number</strong></label>
                                                <input type="text" name="vat_number" id="vat_number" required data-required-error="VAT number cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                    </ul>
                                    <span class="hr-line"></span>
                                    <h3>LOCATION</h3>
                                    <ul class="new-form-fields-list d-flex flex-wrap">
                                        <li>
                                            <div class="form-group">
                                                <label for="street_address"><strong>Street Address</strong></label>
                                                <input type="text" name="street_address" id="street_address" required data-required-error="Street Address cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="apt_suite" class="d-flex justify-content-start"><strong>Apt / Suite</strong><span class="sub-intro">optional</span></label>
                                                <input type="text" name="apt_suite" id="apt_suite" class="form-control" />
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="city"><strong>City</strong></label>
                                                <input type="text" name="city" id="city" required data-required-error="City cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="state"><strong>State / Province</strong></label>
                                                <input type="text" name="state" id="state" required data-required-error="State / Province cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="zip_code"><strong>Zip code</strong></label>
                                                <input type="text" name="zip_code" id="zip_code" required data-required-error="Zip code number cannot be blank." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="country_name"><strong>Country</strong></label>
                                                <select name="country_code_name" id="country_code_name" required data-required-error="Country cannot be blank." class="form-control" onChange="get_dial_code_selection(this.value)" >
                                                    <option value="">Select Country</option>
                                                    <?php if(count($country_list_arr) > 0){ ?>
                                                        <?php for($i=0;$i<count($country_list_arr);$i++){ ?>
                                                            <option value="<?php echo $country_list_arr[$i]['country_code'].'###'.$country_list_arr[$i]['country_name'];?>"><?php echo $country_list_arr[$i]['country_name'];?></option>
                                                        <?php } ?>                                                    
                                                    <?php } ?>    
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                    </ul>
                                    <span class="hr-line"></span>
                                    <h3>PHONE NUMBER</h3>
                                    <ul class="new-form-fields-list d-flex flex-wrap">
                                        <li>
                                            <div class="form-group">
                                                <label for="area_code"><strong>Area Code</strong></label>
                                                <select name="area_code" id="area_code" required data-required-error="Area Code cannot be blank." class="form-control">
                                                    <option value="">Select Area Code</option>
                                                    <?php if($get_all_dial_code_record_count > 0){ ?>           
                                                        <?php foreach($q_get_all_dial_code as $get_all_dial_code){ ?>
                                                            <option value="<?php echo $get_all_dial_code->dial_code_country_code;?>"><?php echo $get_all_dial_code->dial_code_country_name;?> <?php echo $get_all_dial_code->dial_code;?></option>
                                                        <?php } ?>                                                    
                                                    <?php } ?>                                                        
                                                </select>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="phone_no"><strong>Phone number</strong></label>
                                                <input type="text" pattern="\d*" name="phone_no" id="phone_no" required data-required-error="Phone number cannot be blank." data-error="Phone number is invalid." minlength="10" maxlength="10" class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="website_url"><strong>Website URL</strong></label>
                                                <input type="text" pattern="^(https?://)?([a-zA-Z0-9]([a-zA-ZäöüÄÖÜ0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$" name="website_url" id="website_url" data-error="Website URL is invalid." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group">
                                                <label for="contact_email"><strong>Contact email </strong></label>
                                                <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="contact_email" id="contact_email" data-error="Contact email is invalid." class="form-control" />
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </li>
                                    </ul>    
                                    <!--div class="varify-btn">
                                        <a href="#!" class="btn btn-default">VERIFY</a>
                                    </div-->  
                                    <div class="varify-btn d-flex align-items-center justify-content-between">
                                        <div class="varify-left d-flex align-items-center justify-content-between">
                                            <input type="button" name="submit" id="btn_agency_company_setup" class="btn btn-default" value="VERIFY" onclick="funAjaxPostAgencyCompanySetup(this.form)">
                                            <button type="button" id="wait_btn_agency_company_setup" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> VERIFY</button>
                                            <input type="button" name="submit" id="btn_agency_company_setup_verify_later" class="btn no-shadow link-btn verify_later_loader_button ml-0" value="I'll verify later" onclick="funAjaxPostAgencyCompanySetupVerifyLater(this.form)">
                                            <button type="button" id="wait_btn_agency_company_setup_verify_later" class="btn no-shadow link-btn verify_later_loader_button display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> I'll verify later</button>                                            
                                        </div>
                                        <div class="varify-right">
                                            <div class="dropdown">
                                                <a href="javascript:void(0)" class="link-btn dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Why we verify company details?</a>
                                                <div class="dropdown-menu dropdown-menu-right" style="min-height: 207px;">
                                                    <p>To provide a seamless contract management experience, where users can sign agreements and even send and receive payments, we verify the authenticity of each agency. You can still explore the other features of the platform before verifying.</p>
                                                    <div class="gotit-btn"><a href="javascript:void(0)" class="btn btn-third btn-block">Got it!</a></div>
                                                </div>
                                            </div>
                                        </div>
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
<!-- ======================= Modal start ======================= -->
<div class="modal fade" id="varify_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="varification-popup">
                    <div class="varify-has-process">
                        <h4>Verifying company details</h4>
                        <p>Please wait while we are verifying the company details you have provided. Verified entities boost mutual trust on the platform.</p>
                        <div class="load-msg">
                            <b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" /></b> Won't take too long...
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- ======================= Modal end ======================= -->
<!-- ======================= Modal start ======================= -->
<div class="modal fade" id="failed_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="varification-popup">              
                    <div class="varify-has-failed">
                        <h4>Verification failed</h4>
                        <p>Please check your company details and try again.</p>
                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-primary" style="min-width: 185px;float: right;">Okay</a>
                    </div>              
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- ======================= Modal end ======================= -->
<!-- ======================= Modal start ======================= -->
<div class="modal fade" id="succ_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="varification-popup">             
                    <div class="varify-has-sucess">
                        <h4>Verification successful!</h4>
                        <p>Congratulations! Now you can explore YuraApp and create C-Flows.</p>
                        <a href="<?php echo $this->config->item("site_url");?>dashboard" class="btn btn-primary" style="min-width: 185px;float: right;">Okay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- ======================= Modal end ======================= -->
<!-- ======================= Modal start ======================= -->
<div id="verification_failed_popup" class="profile-progress-detail has-failed display-hide">      
    <h4>Verification Failed</h4>
    <p>You can recheck your details and try again.</p>
    <p><strong>OR</strong> if you are sure your details are correct, you can apply for manual verification.</p>
    <div class="pp-action-btn text-center">
        <a href="javascript:void(0)" id="apply_for_manual_verification" class="btn btn-primary btn-block">Apply for manual verification</a>
        <a href="javascript:void(0)" id="wait_apply_for_manual_verification" class="btn btn-primary btn-block ml-0 display-hide"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Apply for manual verification</a>
        <a href="javascript:void(0)" id="close_verification_detail" class="gray-btn-link btn-block">Close and recheck details</a>   
    </div>   
</div> 
<div id="verification_failed_overlay" class="overlay display-hide"></div>
<!-- ======================= Modal end ======================= -->
<!-- ======================= Modal start ======================= -->
<div id="manual_verification_succ_popup" class="profile-progress-detail display-hide">      
    <h4>We'll get back to you soon</h4>
    <p>It usually takes us up to 24 hours to verify the details you've submitted. Feel free to explore the platform and checkout the C-Flow features.</p>     
    <div class="pp-action-btn text-right"><a href="<?php echo $this->config->item("site_url");?>dashboard" class="btn btn-primary">Okay</a></div>   
</div>
<div id="manual_verification_succ_overlay" class="overlay display-hide"></div>
<!-- ======================= Modal end ======================= -->