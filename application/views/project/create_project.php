<form name="frm_create_project" id="frm_create_project" action="<?php echo $this->config->item("site_url");?>project/fn_insert_cluster" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-top d-flex flex-wrap justify-content-center align-items-center">
            <div class="modal-google-icon">
                <div class="form-group input-icon right">
                    <div class="fileinput fileinput-new image-upload-main-div" data-provides="fileinput">
                        <div class="edit-profile-icon">
                            <span class="btn default btn-file select-file-btn">
                                <!--span class="fileinput-new display-show"><i class="fas fa-camera mr-0"></i></span-->
                                <span class="fileinput-new display-show"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-edit.png" alt="" /></span>
                                <input type="file" class="form-control" id="cluster_image" name="cluster_image" > 
                            </span>
                        </div>
                        <div class="mf-img fileinput-new thumbnail">
                            <?php $full_file_path = $this->config->item("UPLOAD_DIR").'company_image/'.$company_image; if($company_image != '' && file_exists($full_file_path) ) {?>
                                <img src="<?php echo $this->config->item("UPLOAD_URL");?>company_image/<?php echo $company_image; ?>" />
                            <?php } else {?>
                                <img src="<?php echo $this->config->item("site_url");?>assets/images/no-product-icon.svg" />
                            <?php } ?>    
                        </div>
                        <div class="mf-img fileinput-preview fileinput-exists thumbnail"></div>                                                            
                        <div class="clearfix"></div>
                    </div>
                </div>                    
            </div>
            <div class="modal-top-text">
                <p>This is cluster image. By default it's client's logo. Click to edit.</p>
            </div>
        </div>
    </div>
    <div class="modal-body d-flex justify-content-center flex-column align-items-center text-center">
        <div class="full-width">                    
            <div class="form-group client-input">
                <label for="name"><strong>Cluster Title</strong></label>
                <input type="text" name="cluster_title" id="cluster_title" class="form-control" required="required" data-required-error="Cluster Title cannot be blank." >
                <span class="help-block with-errors"></span>
                <span class="d-block bottom-text">Clusters are bigger than projects. A cluster neatly bundles projects/agreements for the same client. Cluster title could be 'ReBranding 2020' when project titles are 'Brandbook' and 'Website'. If you don't have clusters, just repeat the client's name.</span>
            </div>
            
            <?php if(strtolower($user_login_mode) == 'agency'){ ?>
                <hr />
                <div class="form-group client-input">
                    <label for="client"><strong><?php echo $user_login_mode_off;?></strong></label>
                    <select name="sel_client" id="sel_client" class="form-control" data-required-error="<?php echo $user_login_mode_off;?> cannot be blank." onchange="getCompanyContactPersonName(this.value)">
                        <option value="">Select <?php echo $user_login_mode_off;?></option>
                        <?php if($user_rows > 0){ ?>
                            <?php for($i=0;$i<count($user_data);$i++){ ?>
                                <option value="<?php echo $user_data[$i]->users_sk;?>"><?php echo $user_data[$i]->company_name;?></option>
                            <?php } ?>    
                        <?php } ?>
                    </select>
                    <span class="help-block with-errors"></span>
                </div>
                <div class="display-invite-new-client">
                    <label>OR Invite New Client</label>
                </div>
                <div class="form-group">
                    <label for="contact-person"><strong>New Client's Email</strong></label>
                    <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="new_client_email" id="new_client_email" data-required-error="New Client's Email cannot be blank." data-error="Please enter work email address." class="form-control" >
                    <span class="help-block with-errors"></span>
                </div>
                <div class="form-group">
                    <label for="contact-person"><strong>New Client's Company Name</strong></label>
                    <input type="text" name="new_client_company_name" id="new_client_company_name" class="form-control" data-required-error="New Client's Company Name cannot be blank." >
                    <span class="help-block with-errors"></span>
                </div>
                <hr />  
            <?php }else{ ?>
                <input type="hidden" name="new_client_email" id="new_client_email" />
                <input type="hidden" name="new_client_company_name" id="new_client_company_name" />
                <div class="form-group client-input">
                    <label for="client"><strong><?php echo $user_login_mode_off;?></strong></label>
                    <select name="sel_client" id="sel_client" class="form-control" required="required" data-required-error="<?php echo $user_login_mode_off;?> cannot be blank." onchange="getCompanyContactPersonName(this.value)">
                        <option value="">Select <?php echo $user_login_mode_off;?></option>
                        <?php if($user_rows > 0){ ?>
                            <?php for($i=0;$i<count($user_data);$i++){ ?>
                                <option value="<?php echo $user_data[$i]->users_sk;?>"><?php echo $user_data[$i]->company_name;?></option>
                            <?php } ?>    
                        <?php } ?>
                    </select>
                    <span class="help-block with-errors"></span>
                </div>
            <?php } ?>                            
            <div class="form-group">
                <label for="contact-person"><strong>Contact Person</strong></label>
                <input type="text" name="contact_person_name" id="contact_person_name" class="form-control" required="required" data-required-error="Contact Person cannot be blank."  >
                <span class="help-block with-errors"></span>
            </div>
            <div class="form-group form-button d-flex justify-content-between">
                <!--div class="form-check has-square">
                    <input class="square-checkbox" type="checkbox" value="option1" id="1">
                    <label for="1"></label>
                </div-->
                <div class="display-step-number">
                    <label>Step 1 of 2</label>
                </div>
                <input type="button" name="submit" id="create_project_btn" class="btn btn-primary" value="Next" onclick="funAjaxPostCreateProject(this.form)">
                <button type="button" id="wait_create_project_btn" class="btn btn-primary display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Next</button>
                <!--input type="submit" name="submit" class="btn btn-primary" value="Create Project" style="min-height: 40px;min-width: 237px;"-->
            </div>
        </div>            
    </div>
</form>