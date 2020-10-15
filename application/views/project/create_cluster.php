<form name="frm_create_cluster" id="frm_create_cluster" action="<?php echo $this->config->item("site_url");?>project/fn_insert_cluster" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
    <div class="cluster-content-head d-flex align-items-center  justify-content-between">
        <ul class="cc-list d-flex flex-wrap">
            <li class="cc-item active d-flex align-items-center">
                <span class="cc-sequence">1</span>
                <h3>Create Cluster</h3>
            </li>
            <li class="cc-item  d-flex align-items-center">
                <span class="cc-sequence">2</span>
                <h3>Create Project</h3>
            </li>
        </ul>
        <button type="button" class="close" data-dismiss="modal"><img src="<?php echo $this->config->item("site_url");?>assets/images/close-icon.svg" alt=""></button>
    </div>
    <div class="cluster-content-body">
        <div class="ccb-upper">
            <div class="ccb-box d-flex align-items-center">
                <div class="ccb-icon">
                    <div class="form-group input-icon right">
                        <div class="fileinput fileinput-new image-upload-main-div" data-provides="fileinput">
                            <div class="edit-profile-icon">
                                <span class="btn default btn-file select-file-btn">
                                    <!--span class="fileinput-new display-show"><i class="fas fa-camera mr-0"></i></span-->
                                    <span class="fileinput-new display-show"><img class="edit-icon-width" src="<?php echo $this->config->item("site_url");?>assets/images/profile-edit.png" alt="" /></span>
                                    <input type="file" class="form-control" id="cluster_image" name="cluster_image" > 
                                </span>
                            </div>
                            <div class="mf-img fileinput-new thumbnail new-product-img">
                                <?php $full_file_path = $this->config->item("UPLOAD_DIR").'company_image/'.$company_image; if($company_image != '' && file_exists($full_file_path) ) {?>
                                    <img src="<?php echo $this->config->item("UPLOAD_URL");?>company_image/<?php echo $company_image; ?>" />
                                <?php } else {?>
                                    <img src="<?php echo $this->config->item("site_url");?>assets/images/no-product-icon.svg" />
                                <?php } ?>    
                            </div>
                            <div class="mf-img fileinput-preview fileinput-exists thumbnail new-product-img"></div>                                                            
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="ccb-intro">This is cluster image. By default it's client's logo. Click to edit.</div>
            </div>
            <div class="form-group">
                <div class="label-box d-flex align-items-center justify-content-between">
                    <label for="name"><b>Cluster Title</b></label>
                    <div class="cluster-info-box">
                        <div>
                            <a href="javascript:void(0)" class="btn-text">What is a cluster?</a>  
                            <div class="cluster-intro-dropdown">
                                <p>Clusters are bigger than projects. A cluster neatly bundles projects/agreements for the same client. Cluster title could be 'ReBranding 2020' when project titles are 'Brandbook' and 'Website'. If you don't have clusters, just repeat the client's name.</p>
                                <div class="gotit-btn text-center">
                                    <a href="javascript:void(0)" class="link-btn" id="gotit">Got it!</a>
                                </div>
                            </div>            
                        </div>
                    </div>
                </div>
                <input type="text" name="cluster_title" id="cluster_title" class="form-control" required="required" data-required-error="Cluster Title cannot be blank." >
                <span class="help-block with-errors"></span>
            </div>
        </div>
        <?php if(strtolower($user_login_mode) == 'agency'){ ?>
            <div class="ccb-lower">
                <div class="ccb-lower-box">
                    <div class="form-check active" id="existing_clients">
                        <input type="radio" name="client_option" id="select-existing-client" value="sel_existing_client" checked="checked">
                        <label for="select-existing-client">                    
                            <span class="label-text">Select existing <?php echo $user_login_mode_off;?></span>
                        </label>
                    </div>
                    <div class="clients-type-dropdown" id="existing_clients_disabled">
                        <div class="form-group">
                            <select name="sel_client" id="sel_client" required="required" class="form-control" data-required-error="<?php echo $user_login_mode_off;?> cannot be blank." onchange="getCompanyContactPersonName(this.value)">
                                <option value="">Select <?php echo $user_login_mode_off;?></option>
                                <?php if($user_rows > 0){ ?>
                                    <?php for($i=0;$i<count($user_data);$i++){ ?>
                                        <option value="<?php echo $user_data[$i]->users_sk;?>"><?php echo $user_data[$i]->company_name;?></option>
                                    <?php } ?>    
                                <?php } ?>
                            </select>
                            <span class="help-block with-errors"></span>    
                        </div>
                    </div>
                </div>
                <div class="ccb-lower-box">
                    <div class="form-check" id="new_clients">
                        <input type="radio" name="client_option" id="insert-new-client" value="new_client" >
                        <label for="insert-new-client">                    
                            <span class="label-text">Create a new client</span>
                        </label>
                    </div>
                    <div class="clients-type-dropdown disabled"  id="new_clients_disabled">
                        <div class="form-group">
                            <label for="contact-person"><b>Client's Email</b></label>
                            <input type="email" pattern=".*@\w{1,}\.\w{2,}" name="new_client_email" id="new_client_email" data-required-error="Client's Email cannot be blank." data-error="Please enter work email address." class="form-control" placeholder="Client's email id" disabled="" >
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label for="contact-person"><b>Client's Company Name</b></label>
                            <input type="text" name="new_client_company_name" id="new_client_company_name" class="form-control" data-required-error="Client's Company Name cannot be blank." placeholder="Type Client's Name" disabled="" >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php }else{ ?>
            <input type="hidden" name="new_client_email" id="new_client_email" />
            <input type="hidden" name="new_client_company_name" id="new_client_company_name" />
            <div class="ccb-lower">
                <div class="ccb-lower-box">
                    <div class="form-group">
                        <label for="contact-person"><b>Select existing <?php echo $user_login_mode_off;?></b></label>
                        <select name="sel_client" id="sel_client" required="required" class="form-control" data-required-error="<?php echo $user_login_mode_off;?> cannot be blank." onchange="getCompanyContactPersonName(this.value)">
                            <option value="">Select <?php echo $user_login_mode_off;?></option>
                            <?php if($user_rows > 0){ ?>
                                <?php for($i=0;$i<count($user_data);$i++){ ?>
                                    <option value="<?php echo $user_data[$i]->users_sk;?>"><?php echo $user_data[$i]->company_name;?></option>
                                <?php } ?>    
                            <?php } ?>
                        </select>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>                
            </div>
        <?php } ?>            
    </div>
    <div class="cluster-content-footer ">
        <div class="ccf-content">
            <div class="form-group">
                <label for="contact-person"><b>Contact Person</b></label>
                <input type="text" name="contact_person_name" id="contact_person_name" class="form-control" required="required" data-required-error="Contact Person cannot be blank."  >
                <span class="help-block with-errors"></span>
            </div>
            <div class="cc-btn text-right">
                <input type="button" name="submit" id="create_cluster_btn" class="btn btn-primary" value="Next" onclick="funAjaxPostCreateCluster(this.form)">
                <button type="button" id="wait_create_cluster_btn" class="btn btn-primary display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Next</button>
            </div>
        </div>
    </div>
</form>