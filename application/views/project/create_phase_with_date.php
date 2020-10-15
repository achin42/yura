<form name="frm_create_phase" id="frm_create_phase" action="<?php echo $this->config->item("site_url");?>project/fn_insert_phase" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
    <input type="hidden" name="cluster_sk" id="cluster_sk" class="form-control" value="<?php echo $cluster_sk;?>" >
    <input type="hidden" name="project_sk" id="project_sk" class="form-control" value="<?php echo $project_sk;?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-top d-flex flex-wrap justify-content-center align-items-center">
            <div class="fc-intro d-flex">
                <div class="fc-content1"><strong>New Phase</strong></div>  
            </div>
        </div>
    </div>
    <div class="modal-body d-flex justify-content-center flex-column align-items-center text-center">
        <div class="full-width">
            <div class="form-group client-input">
                <label for="contact-person"><strong>Name</strong></label>
                <input type="text" name="phase_name" id="phase_name" class="form-control" required="required" data-required-error="Name cannot be blank." >
                <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
                <label><strong>Duration</strong></label>
                <div class="input-daterange-1 from-to-date-picker-div">
                    <div class="form-group">
                    <input type="text" class="form-control from-date-picker" style="width:100%;" name="start_date" id="start_date" required="required" data-required-error="Start Date cannot be blank." placeholder="mm/dd/yy" >
                    <span class="help-block with-errors"></span>
                    </div>
                    <div class="to-text-date-picker">to</div>
                    <div class="form-group">
                    <input type="text" class="form-control to-date-picker" style="width:100%;" name="end_date" id="end_date" required="required" data-required-error="End Date cannot be blank." placeholder="mm/dd/yy" >
                    <span class="help-block with-errors"></span>
                    </div>                            
                </div>                                        
            </div>
            <div class="form-group form-button d-flex justify-content-end" style="margin-bottom: 0;">
                <input type="button" name="submit" id="create_phase_btn" class="btn btn-primary" value="Create Phase" onclick="funAjaxPostCreatePhase(this.form,'<?php echo $project_sk;?>')" >
                <button type="button" id="wait_create_phase_btn" class="btn btn-primary display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Create Phase</button>
            </div>
        </div>            
    </div>
</form>