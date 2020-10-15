<form name="frm_create_agreement" id="frm_create_agreement" action="<?php echo $this->config->item("site_url");?>project/fn_insert_agreement" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
    <input type="hidden" name="cluster_sk" id="cluster_sk" class="form-control" value="<?php echo $cluster_sk;?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-top d-flex flex-wrap justify-content-center align-items-center">
            <div class="fc-intro d-flex">
                <span class="fc-icon">
                    <?php $full_file_path = $this->config->item("UPLOAD_DIR").'cluster_image/'.$project_data[0]->cluster_image; if($project_data[0]->cluster_image != '' && file_exists($full_file_path) ) {?>
                        <img src="<?php echo $this->config->item("UPLOAD_URL");?>cluster_image/<?php echo $project_data[0]->cluster_image; ?>" alt="" />
                    <?php }else{ ?>
                        <img src="<?php echo $this->config->item("site_url");?>assets/images/default-project.svg" alt="" />
                    <?php } ?>                    
                </span>
                <div class="fc-content"><strong><?php echo $project_data[0]->cluster_title;?></strong><br><?php echo $company_data[0]->company_name;?>  /  <?php echo $project_data[0]->contact_person_name;?></div>  
            </div>
        </div>
    </div>
    <div class="modal-body d-flex justify-content-center flex-column align-items-center text-center">
        <div class="full-width">
            <div class="form-group client-input">
                <label for="name"><strong>Project Title</strong></label>
                <input type="text" name="project_name" id="project_name" class="form-control" required="required" data-required-error="Project Title cannot be blank." >
                <span class="help-block with-errors"></span>
                <span class="d-block bottom-text">Name of the project OR the agreement</span>
            </div>
            <div class="form-group client-input">
                <label for="contact-person"><strong>Name of first Phase</strong></label>
                <input type="text" name="phase_name" id="phase_name" class="form-control" required="required" data-required-error="Name of first Phase cannot be blank." >
                <span class="help-block with-errors"></span>
                <span class="d-block bottom-text">An project can be organised into phases. A phase should club together a set of related tasks. Such as: 'Discovery phase' or 'Wireframing'. Sometimes you should just call them Phase 1, 2 etc.</span>
            </div>
            <div class="form-group form-button d-flex justify-content-end" style="margin-bottom: 0;">
                <input type="button" name="submit" id="create_agreement_btn" class="btn btn-primary" value="Create Agreement" onclick="funAjaxPostCreateAgreement(this.form)" >
                <button type="button" id="wait_create_agreement_btn" class="btn btn-primary display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Create Agreement</button>
            </div>
        </div>            
    </div>
</form>