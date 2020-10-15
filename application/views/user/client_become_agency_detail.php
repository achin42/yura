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
                                <div class="new-card-container beacome-a-agency-card">
                                    <form name="frm_client_become_agency_company_detail" id="frm_client_become_agency_company_detail" action="<?php echo $this->config->item("site_url");?>user/fn_update_client_become_agency_company_detail" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                                        <input type="hidden" name="company_sk" id="company_sk" value="<?php echo $company_data[0]->company_sk;?>">
                                        <input type="hidden" name="users_sk" id="users_sk" value="<?php echo $company_data[0]->users_sk;?>">
                                        <p><b>Provide all the required company details and verify them to become an Agency.</b></p>
                                        <div class="user-profile-desc">
                                            <div class="user-profile-img">
                                                <?php $full_file_path = $this->config->item("UPLOAD_DIR").'company_image/'.$company_data[0]->company_image; if($company_data[0]->company_image != '' && file_exists($full_file_path) ) {?>
                                                    <img src="<?php echo $this->config->item("UPLOAD_URL");?>company_image/<?php echo $company_data[0]->company_image; ?>" alt="" id="result_img" />
                                                <?php } else {?>
                                                    <img src="<?php echo $this->config->item("site_url");?>assets/images/no-profile-image.svg" alt="" id="result_img" />
                                                <?php } ?>                                                    
                                            </div>
                                            <div class="file-upload">
                                                <?php if($company_data[0]->company_image != '' && file_exists($full_file_path) ) { ?>
                                                    <label for="company_logo_image" class="upload-btn">Change company logo</label>
                                                <?php }else{ ?>
                                                    <label for="company_logo_image" class="upload-btn">Upload company logo</label>
                                                <?php } ?>
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
                                                        <input type="text" name="company_name" id="company_name" value="<?php echo $company_data[0]->company_name;?>" required data-required-error="Company name cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="company_domain"><strong>Domain</strong></label>
                                                        <select name="company_domain" id="company_domain" required data-required-error="Domain cannot be blank." class="form-control">
                                                            <option value="Designing" <?php if($company_data[0]->company_domain == 'Designing'){ ?>selected<?php } ?> >Designing</option>
                                                            <option value="Development" <?php if($company_data[0]->company_domain == 'Development'){ ?>selected<?php } ?> >Development</option>
                                                            <option value="Marketing" <?php if($company_data[0]->company_domain == 'Marketing'){ ?>selected<?php } ?> >Marketing</option>
                                                        </select>
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="chamber_of_commerce_number"><strong>Chamber of commerce number</strong></label>
                                                        <input type="text" name="chamber_of_commerce_number" id="chamber_of_commerce_number" value="<?php echo $company_data[0]->chamber_of_commerce_number;?>" required data-required-error="Chamber of commerce number cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="vat_number"><strong>VAT number</strong></label>
                                                        <input type="text" name="vat_number" id="vat_number" value="<?php echo $company_data[0]->vat_number;?>" required data-required-error="VAT number cannot be blank." class="form-control" />
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
                                                        <input type="text" name="street_address" id="street_address" value="<?php echo $company_data[0]->street_address;?>" required data-required-error="Street Address cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="apt_suite" class="d-flex justify-content-start"><strong>Apt / Suite</strong><span class="sub-intro">optional</span></label>
                                                        <input type="text" name="apt_suite" id="apt_suite" value="<?php echo $company_data[0]->apt_suite;?>" class="form-control" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="city"><strong>City</strong></label>
                                                        <input type="text" name="city" id="city" value="<?php echo $company_data[0]->city;?>" required data-required-error="City cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="state"><strong>State / Province</strong></label>
                                                        <input type="text" name="state" id="state" value="<?php echo $company_data[0]->state;?>" required data-required-error="State / Province cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="zip_code"><strong>Zip code</strong></label>
                                                        <input type="text" name="zip_code" id="zip_code" value="<?php echo $company_data[0]->zip_code;?>" required data-required-error="Zip code number cannot be blank." class="form-control" />
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
                                                                    <option value="<?php echo $country_list_arr[$i]['country_code'].'###'.$country_list_arr[$i]['country_name'];?>" <?php if($company_data[0]->country_code == $country_list_arr[$i]['country_code']){ ?>selected<?php } ?> ><?php echo $country_list_arr[$i]['country_name'];?></option>
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
                                                                    <option value="<?php echo $get_all_dial_code->dial_code_country_code;?>" <?php if($company_data[0]->area_code == $get_all_dial_code->dial_code_country_code){ ?>selected<?php } ?> ><?php echo $get_all_dial_code->dial_code_country_name;?> <?php echo $get_all_dial_code->dial_code;?></option>
                                                                <?php } ?>                                                    
                                                            <?php } ?>                                                        
                                                        </select>
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="phone_no"><strong>Phone number</strong></label>
                                                        <input type="text" pattern="\d*" name="phone_no" id="phone_no" required data-required-error="Phone number cannot be blank." data-error="Phone number is invalid." minlength="10" maxlength="10" value="<?php echo $company_data[0]->phone_no;?>" class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="website_url"><strong>Website URL</strong></label>
                                                        <input type="text" pattern="^(https?://)?([a-zA-Z0-9]([a-zA-ZäöüÄÖÜ0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$" name="website_url" id="website_url" value="<?php echo $company_data[0]->website_url;?>" data-error="Website URL is invalid." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="contact_email"><strong>Contact email </strong></label>
                                                        <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="contact_email" id="contact_email" value="<?php echo $company_data[0]->contact_email;?>" data-error="Contact email is invalid." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                            </ul>    
                                            <!--div class="varify-btn">
                                                <a href="#!" class="btn btn-default">VERIFY</a>
                                            </div-->  
                                            <div class="varify-btn form-group form-button remove-bottom-margin">
                                                <input type="button" name="submit" id="btn_client_become_agency_company_detail" class="btn btn-default" value="VERIFY" onclick="funAjaxPostClientBecomeAgencyCompanyDetail(this.form)">
                                                <button type="button" id="wait_btn_client_become_agency_company_detail" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> VERIFY</button>
                                                <a href="<?php echo $this->config->item("site_url");?>client-company-detail" class="btn btn-link2">Cancel</a>
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
        <!-- ======================== Main container end ========================== -->
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
            <div class="pp-action-btn text-right"><a href="<?php echo $this->config->item("site_url");?>agency-company-detail" class="btn btn-primary">Okay</a></div>   
        </div>
        <div id="manual_verification_succ_overlay" class="overlay display-hide"></div>
        <!-- ======================= Modal end ======================= -->
        
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/client-become-agency-detail-js');?>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/croppie.js"></script>
  </body>
</html>