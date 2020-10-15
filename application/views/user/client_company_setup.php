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
    <body class="pt-0">
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
                                <?php /*?><div class="back-arrow-icon" id="back_from_company_detail"><img src="<?php echo $this->config->item("site_url");?>assets/images/iconsicadd.png" alt="" /></div><?php */ ?>
                                <div class="ncd-head-desc">
                                    <h2>Company detail</h2>
                                    <p>Enter your company's legal details. We will verify these details to make sure of your authenticity.</p>
                                </div>
                            </div>
                            <div class="ncd-body">
                                <div class="new-card-container px-0 py-0">
                                    <form name="frm_client_company_setup" id="frm_client_company_setup" action="<?php echo $this->config->item("site_url");?>user/fn_insert_client_company_setup" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
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
                                                        <input type="text" name="company_name" id="company_name" required data-required-error="Company name cannot be blank." class="form-control" />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="company_domain"><strong>Domain</strong></label>
                                                        <select name="company_domain" id="company_domain" disabled class="form-control disabled-color">
                                                            <option value="">Applies only to agencies</option>
                                                            <option value="Designing">Designing</option>
                                                            <option value="Development">Development</option>
                                                            <option value="Marketing">Marketing</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="chamber_of_commerce_number"><strong>Chamber of commerce number</strong></label>
                                                        <input type="text" name="chamber_of_commerce_number" id="chamber_of_commerce_number" disabled class="form-control" placeholder="Applies only to agencies" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="vat_number"><strong>VAT number</strong></label>
                                                        <input type="text" name="vat_number" id="vat_number" disabled class="form-control" placeholder="Applies only to agencies" />
                                                    </div>
                                                </li>
                                            </ul>
                                            <!--p><em>Below fields are optional</em></p-->
                                            <span class="hr-line"></span>
                                            <h3>LOCATION</h3>
                                            <ul class="new-form-fields-list d-flex flex-wrap">
                                                <li>
                                                    <div class="form-group">
                                                        <label for="street_address"><strong>Street Address</strong></label>
                                                        <input type="text" name="street_address" id="street_address" class="form-control" />
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
                                                        <input type="text" name="city" id="city" class="form-control" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="state"><strong>State / Province</strong></label>
                                                        <input type="text" name="state" id="state" class="form-control" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="zip_code"><strong>Zip code</strong></label>
                                                        <input type="text" name="zip_code" id="zip_code" class="form-control" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="country_name"><strong>Country</strong></label>
                                                        <select name="country_code_name" id="country_code_name" class="form-control" onChange="get_dial_code_selection(this.value)" >
                                                            <option value="">Select Country</option>
                                                            <?php if(count($country_list_arr) > 0){ ?>
                                                                <?php for($i=0;$i<count($country_list_arr);$i++){ ?>
                                                                    <option value="<?php echo $country_list_arr[$i]['country_code'].'###'.$country_list_arr[$i]['country_name'];?>"><?php echo $country_list_arr[$i]['country_name'];?></option>
                                                                <?php } ?>                                                    
                                                            <?php } ?>    
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>    
                                            <span class="hr-line"></span>
                                            <h3>PHONE NUMBER</h3>
                                            <ul class="new-form-fields-list d-flex flex-wrap">
                                                <li>
                                                    <div class="form-group">
                                                        <label for="area_code"><strong>Area Code</strong></label>
                                                        <select name="area_code" id="area_code" class="form-control">
                                                            <option value="">Select Area Code</option>
                                                            <?php if($get_all_dial_code_record_count > 0){ ?>
                                                                <?php foreach($q_get_all_dial_code as $get_all_dial_code){ ?>
                                                                    <option value="<?php echo $get_all_dial_code->dial_code_country_code;?>"><?php echo $get_all_dial_code->dial_code_country_name;?> <?php echo $get_all_dial_code->dial_code;?></option>
                                                                <?php } ?>                                                    
                                                            <?php } ?>                                                        
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="phone_no"><strong>Phone number</strong></label>
                                                        <input type="text" pattern="\d*" name="phone_no" id="phone_no" data-error="Phone number is invalid." minlength="10" maxlength="10" class="form-control" />
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="website_url"><strong>Website URL</strong></label>
                                                        <input type="text" pattern="^(https?://)?([a-zA-Z0-9]([a-zA-ZäöüÄÖÜ0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$" name="website_url" id="website_url" class="form-control" data-error="Website URL is invalid." />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group">
                                                        <label for="contact_email"><strong>Contact email </strong></label>
                                                        <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="contact_email" id="contact_email" class="form-control" data-error="Contact email is invalid." />
                                                        <span class="help-block with-errors"></span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <!--div class="varify-btn">
                                                <a href="#!" class="btn btn-default">GO TO DASHBOARD</a>
                                            </div-->  
                                            <div class="varify-btn form-group form-button">
                                                <input type="button" name="submit" id="btn_client_company_setup" class="btn btn-default" value="GO TO DASHBOARD" onclick="funAjaxPostClientCompanySetup(this.form)">
                                                <button type="button" id="wait_btn_client_company_setup" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> GO TO DASHBOARD</button>
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
        <script src="<?php echo $this->config->item("site_url");?>assets/js/jquery-3.4.1.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/bootstrapvalidator.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/custom.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/plugin/toastr/build/toastr.min.js"></script>
        <script src="<?php echo $this->config->item("site_url");?>assets/js/croppie.js"></script>
        <?php $this->load->view('inc/js/common-js');?>
        <?php $this->load->view('inc/js/client-company-setup-js');?>         
    </body>
</html>