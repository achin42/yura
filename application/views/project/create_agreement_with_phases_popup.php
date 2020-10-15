<form class="popup-fix-height" name="frm_create_agreement" id="frm_create_agreement" action="<?php echo $this->config->item("site_url");?>project/fn_insert_agreement" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
    <input type="hidden" name="cluster_sk" id="cluster_sk" class="form-control" value="<?php echo $cluster_sk;?>" >
    <div class="cluster-content-head nav-head d-flex align-items-start  justify-content-between create-projects-with-phases">
        <div class="cluster-nav-outer">
            <ul class="cluster-nav d-flex">
                <li class="cluster-item"><?php echo $company_data[0]->company_name;?></li>
                <li class="cluster-item"><?php echo $project_data[0]->cluster_title;?></li>
            </ul>
            <h4>New Project</h4>
        </div>
        <button type="button" class="close" data-dismiss="modal"><img src="<?php echo $this->config->item("site_url");?>assets/images/close-icon.svg" alt=""></button>
    </div>
    <div class="cluster-content-body phase-content-body1">
        <div class="phase-content-upper">
            <div class="form-group">
                <label for="contact-person"><b>Project Title</b></label>
                <input type="text" name="project_title" id="project_title" class="form-control" value="" placeholder="">
                <span class="help-block with-errors display-hide" id="project_title_required" ><ul class="list-unstyled"><li>Project Title cannot be blank.</li></ul></span>
                <em>Name of the project OR the agreement</em>                
            </div>            
        </div>
        <div class="phase-content-middle">
            <div class="pcm-head d-flex justify-content-between align-items-center">
                <b>Phases</b>
                <div class="cluster-info-box">
                    <div>
                        <a href="javascript:void(0)" class="btn-text">What is a phase?</a>
                        <div class="cluster-intro-dropdown">
                            <p>A project can be organised into phases. A phase should club together a set of related tasks. Such as: 'Discovery phase' or 'Wireframing'. Sometimes you should just call them Phase 1, 2 etc.</p>
                            <div class="gotit-btn text-center">
                                <a href="javascript:void(0)" class="link-btn" id="gotits">Got it!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="help-block with-errors display-hide" id="one_phase_required" ><ul class="list-unstyled"><li>Phase cannot be blank.</li></ul></span>
            <div class="pcm-body d-flex align-items-center" id="no_phases_block">
                <div>
                    <img class="chart-icon" src="<?php echo $this->config->item("site_url");?>assets/images/chart.png" alt="" />
                    <h3>You haven't added any phase yet.</h3>
                    <p>A project can be organised into phases. A phase should club together a set of related tasks. Such as: 'Discovery phase' or 'Wireframing'. Sometimes you should just call them Phase 1, 2 etc.</p>
                    <p class="color-para-text">You can add one or more phases right now but you can always add more later while creating the C-Flow</p>
                </div>
            </div>
            <div class="pcm-body display-hide" id="list_of_phases_block"></div>
        </div>        
    </div>
    <div class="cluster-content-footer">
        <div class="phase-content-lower">
            <div class="add-phase-box  d-flex align-items-center justify-content-between">
                <div class="ap-left">
                    <input type="text" name="phase_title" id="phase_title" class="form-control" value="" placeholder="New Phase"> 
                </div>
                <div class="ap-right d-flex align-items-center">
                    <div class="ap-date-picker d-flex align-items-center">
                        <div class="adp-input">
                            <input type="text" name="start_date" id="start_date" class="form-control" value="" placeholder="mm/dd/yy"> 
                        </div>
                        <span class="picker-seprator">to</span>
                        <div class="adp-input">
                            <input type="text" name="end_date" id="end_date" class="form-control" value="" placeholder="mm/dd/yy"> 
                        </div>
                    </div>
                    <div class="add-plus-icon">
                        <img onclick="funAddPhaseFromAgreementPopup()" src="<?php echo $this->config->item("site_url");?>assets/images/add-plus-icon.png" alt="" />
                    </div>
                </div>
            </div>
            <span class="help-block with-errors display-hide" id="phase_required" ><ul class="list-unstyled"><li>New Phase cannot be blank.</li></ul></span>
            <span class="help-block with-errors display-hide" id="start_date_required" ><ul class="list-unstyled"><li>Start Date cannot be blank.</li></ul></span>
            <span class="help-block with-errors display-hide" id="end_date_required" ><ul class="list-unstyled"><li>End Date cannot be blank.</li></ul></span>
        </div>
        <div class="ccf-content">              
            <div class="cc-btn text-right">
                <input type="button" name="submit" id="create_agreement_phases_btn" class="btn btn-primary" value="Done" onclick="funAjaxPostCreateAgreementPhases(this.form)">
                <button type="button" id="wait_create_agreement_phases_btn" class="btn btn-primary display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Done</button>
            </div>
        </div>
    </div>
</form>