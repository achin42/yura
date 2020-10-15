<!doctype html>
    <html lang="en">
    <head>
        <?php $this->load->view('inc/header-include');?>
        <style>
        
        </style>
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
                                <div class="payment-box">
                                    <div class="member-form-continer">  
                                        <div class="mf-left">
                                			<div class="mf-box">
                                				<h4>My Details</h4>
                                				<form name="frm_edit_user_profile" id="frm_edit_user_profile" action="<?php echo $this->config->item("site_url");?>user/fn_update_user_profile" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                                					<input type="hidden" name="users_sk" id="users_sk" value="<?php echo $user_data[0]->users_sk;?>">
                                                    <div class="form-group input-icon right">
                                                        <div class="fileinput fileinput-new image-upload-main-div" data-provides="fileinput">
                                                            <div class="edit-profile-icon">
                                                                <span class="btn default btn-file select-file-btn">
                                                                    <!--span class="fileinput-new display-show"><i class="fas fa-camera mr-0"></i></span-->
                                                                    <span class="fileinput-new display-show"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-edit.png" alt="" /></span>
                                                                    <input type="file" class="form-control" id="user_image" name="user_image" > 
                                                                </span>
                                                            </div>
                                                            <?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                                                                <div class="mf-img fileinput-new thumbnail" style="background: none;">
                                                                    <img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" />
                                                                </div>    
                                                            <?php } else {?>
                                                                <div class="mf-img fileinput-new thumbnail">
                                                                    <img src="<?php echo $this->config->item("site_url");?>assets/images/user-default-icon.svg" />
                                                                </div>    
                                                            <?php } ?>    
                                                            <div class="mf-img fileinput-preview fileinput-exists thumbnail"></div>                                                            
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <!--div class="mf-img"><img src="assets/images/oval.png" alt="" /></div-->
                                					<div class="form-group">
                                						<label for="email"><strong>Email</strong></label>
                                						<input type="email" name="email" id="email" value="<?php echo $user_data[0]->email;?>" class="form-control" required="required">
                                					</div>
                                					<div class="form-group">
                                						<label for="name"><strong>Name</strong></label>
                                						<input type="text" name="first_name" id="first_name" value="<?php echo $user_data[0]->first_name;?>" class="form-control" required="required">
                                					</div>
                                					<div class="form-group form-button">
                                						<input type="button" name="submit" id="edit_user_profile_btn" class="btn btn-default" value="Save Changes" onclick="funAjaxPostEditProfile(this.form)">                                						
                                                        <button type="button" id="wait_edit_user_profile_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Save Changes</button>
                                					</div>
                                				</form>
                                			</div>
                                		</div> 
                                        <div class="mf-right">
                                			<div class="mf-box">
                                				<h4>Company Details</h4>
                                				<form name="frm_edit_company_profile" id="frm_edit_company_profile" action="<?php echo $this->config->item("site_url");?>user/fn_update_company_profile" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                                					<input type="hidden" name="users_sk" id="users_sk" value="<?php echo $user_data[0]->users_sk;?>">
                                                    <div class="form-group input-icon right">
                                                        <div class="fileinput fileinput-new image-upload-main-div" data-provides="fileinput">
                                                            <div class="edit-profile-icon">
                                                                <span class="btn default btn-file select-file-btn">
                                                                    <!--span class="fileinput-new display-show"><i class="fas fa-camera mr-0"></i></span-->
                                                                    <span class="fileinput-new display-show"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-edit.png" alt="" /></span>
                                                                    <input type="file" class="form-control" id="company_image" name="company_image" > 
                                                                </span>
                                                            </div>
                                                            <?php $full_file_path = $this->config->item("UPLOAD_DIR").'company_image/'.$company_data[0]->company_image; if($company_data[0]->company_image != '' && file_exists($full_file_path) ) {?>
                                                                <div class="mf-img fileinput-new thumbnail" style="background: none;">
                                                                    <img src="<?php echo $this->config->item("UPLOAD_URL");?>company_image/<?php echo $company_data[0]->company_image; ?>" />
                                                                </div>    
                                                            <?php } else {?>
                                                                <div class="mf-img fileinput-new thumbnail">
                                                                    <img src="<?php echo $this->config->item("site_url");?>assets/images/company-default-icon.svg" />
                                                                </div>    
                                                            <?php } ?>    
                                                            <div class="mf-img fileinput-preview fileinput-exists thumbnail"></div>                                                            
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                					<div class="form-group">
                                						<label for="name"><strong>Company Name</strong></label>
                                						<input type="text" name="company_name" id="company_name" value="<?php echo $company_data[0]->company_name;?>" class="form-control" required="required">
                                					</div>
                                					<div class="form-group">
                                						<label for="concernedperson"><strong>Contact Person</strong></label>
                                						<input type="text" name="contact_person_name" id="contact_person_name" value="<?php echo $company_data[0]->contact_person_name;?>" class="form-control" required="required">
                                					</div>
                                					<div class="form-group form-button">
                                						<input type="button" name="submit" id="edit_company_profile_btn" class="btn btn-default" value="Save Changes" onclick="funAjaxPostCompanyProfile(this.form)">
                                						<button type="button" id="wait_edit_company_profile_btn" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Save Changes</button>
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
            </div>
        </div>
        <!-- ======================== Main container end ========================== -->
    
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/edit-profile-js');?>
  </body>
</html>