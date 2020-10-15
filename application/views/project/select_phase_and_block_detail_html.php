<div class="side-block-container  tab-init">
    <div class="sb-head">
        <div class="sb-head-upper">
            <h6><?php echo $q_phase_data[0]->phase_name;?></h6>
            <div class="sb-right-box d-flex">
                <span class="del-outline-icon phase-close-icon" onClick="fun_delete_phase('<?php echo $cluster_sk;?>','<?php echo $project_sk;?>','<?php echo $phase_sk;?>');" ><img src="<?php echo $this->config->item("site_url");?>assets/images/delete-outline.png" alt="" /></span>
                <div class="phase-close-icon"><img onclick="cancelUpdatePhase('<?php echo $project_sk;?>')" src="<?php echo $this->config->item("site_url");?>assets/images/close1.png" alt="" /></div>
            </div>
        </div>
        <div class="sb-head-lower">
            <ul id="tabs" class="nav  block-navtab" role="tablist" >
                <li class="nav-item"><a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab"><span>add block</span></a></li>
                <li class="nav-item"><a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab"><span>details</span></a></li>
            </ul>
        </div>
    </div>
    <div id="content" class="tab-content single-block" role="tablist">
        <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
            <div class="tab-content-box">
                <div class="sb-body scrollbar-dynamic">
                    <ul class="tasks-list">
                        <?php if($pre_defined_block_type_rows > 0){ ?>
                            <?php $i = 1; foreach($q_pre_defined_block_type_data as $pre_defined_block_type_data){ 
                                $block_type_sk = $pre_defined_block_type_data->block_type_sk;
                                $block_type_name = $pre_defined_block_type_data->block_type_name;
                                $block_type_code = $pre_defined_block_type_data->block_type_code; 
                                $description = $pre_defined_block_type_data->description;  
                                
                                $icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon.svg';
                                $white_icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon-white.svg';
                                if($block_type_code != ''){
                                    $image_name = $block_type_code;
                                    if($image_name == 'input'){
                                        $icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon.svg';
                                        $white_icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon-white.svg';
                                    }elseif($image_name == 'feedback-cycle'){
                                        $icon_image_url = $this->config->item("site_url").'assets/images/feedback-cycle.png';
                                        $white_icon_image_url = $this->config->item("site_url").'assets/images/feedback-cycle1.png';
                                    }elseif($image_name == 'milestone'){
                                        $icon_image_url = $this->config->item("site_url").'assets/images/milestone.png';
                                        $white_icon_image_url = $this->config->item("site_url").'assets/images/milestone1.png';
                                    }else{
                                        $icon_image_url = $this->config->item("site_url").'assets/images/'.$image_name.'-icon.svg';
                                        $white_icon_image_url = $this->config->item("site_url").'assets/images/'.$image_name.'-icon-white.svg';
                                    }    
                                }                              
                            ?>                        
                                <li class="tasks-item task-left-right-margin task-bottom-margin <?php if($i == 1){?>first-task-top-margin<?php } ?><?php if($i == 8){?>last-task-bottom-margin<?php } ?>">
                                    <div class="tasks-box <?php echo $block_type_code;?>">
                                        <div class="task-box-left">
                                			<div class="tasks-box-icon"><img src="<?php echo $icon_image_url;?>" alt="" /></div>
                                            <?php if($block_type_code == 'payment'){ ?>                                                
                                                <?php if($logged_in_is_agency_verified == '0'){ ?>
                                                    <?php if($logged_in_verify_later == '1'){ ?>
                                                        <div data-toggle="popover" class="not-verified-popover">
                                                            <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                                                        <div data-toggle="popover" class="pending-popover">
                                                            <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                                        </div>
                                                    <?php } ?>
                                                <?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                                                    <div data-toggle="popover" class="not-verified-popover">
                                                        <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                                    </div>        
                                                <?php }else{ ?>    
                                                    <a href="javascript:void(0)" onClick="createBlock('<?php echo $project_sk;?>','<?php echo $block_type_sk;?>','<?php echo $block_type_code;?>');" >
                                                        <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                                    </a>
                                                <?php } ?>
                                            <?php }else{ ?>    
                                                <a href="javascript:void(0)" onClick="createBlock('<?php echo $project_sk;?>','<?php echo $block_type_sk;?>','<?php echo $block_type_code;?>');" >
                                                    <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                                </a>
                                            <?php } ?>                                                
                                		</div>
                                        <div class="task-box-right">
                                			<div class="tasks-box-upper">
                                				<h5><?php echo $block_type_name;?></h5>
                                				<strong>2 actions</strong>
                                			</div>
                                			<div class="task-box-lower"><?php echo $description;?></div>
                                		</div>
                                    </div>
                                </li>
                            <?php $i++; } ?>
                        <?php } ?>                            
                    </ul>      
                </div>
            </div>
        </div>
        <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
            <div class="tab-content-box">
                <div class="sb-body phase-sb-body scrollbar-dynamic">
                    <form name="frm_update_phase_side_panel" id="frm_update_phase_side_panel" action="<?php echo $this->config->item("site_url");?>project/fn_update_phase_side_panel" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
                        <input type="hidden" name="cluster_sk" id="cluster_sk" class="form-control" value="<?php echo $cluster_sk;?>" >
                        <input type="hidden" name="project_sk" id="project_sk" class="form-control" value="<?php echo $project_sk;?>" >
                        <input type="hidden" name="phase_sk" id="phase_sk" class="form-control" value="<?php echo $phase_sk;?>" >
                        <div class="pahse-sb-upper">
                            <div class="phase-form-container">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="phase_name" id="phase_name" required="required" data-required-error="Title cannot be blank." value="<?php echo $q_phase_data[0]->phase_name; ?>" >
                                    <span class="help-block with-errors"></span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="phase_description" id="phase_description" ><?php echo $q_phase_data[0]->phase_description; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="input-daterange-1">Date Range</label>
                                    <div class="input-daterange-1 from-to-date-picker-div">
                                        <input type="text" class="form-control from-date-picker" name="start_date" id="start_date" required="required" data-required-error="Start Date Range cannot be blank." value="<?php echo $this->general->display_date_format_in_input($q_phase_data[0]->start_date); ?>">
                                        <div class="to-text-date-picker">to</div>
                                        <input type="text" class="form-control to-date-picker" name="end_date" id="end_date" required="required" data-required-error="End Date Range cannot be blank." value="<?php echo $this->general->display_date_format_in_input($q_phase_data[0]->end_date); ?>">                            
                                    </div>
                                    <span class="help-block with-errors"></span>                        
                                </div>                                                                    
                            </div>
                        </div>
                        <div class="pahse-sb-lower">
                            <h5>Blocks</h5>
                            <?php 
                                $block_list_result = $this->project_model->fn_get_all_blocks_by_phase_sk_in_side_div_panel($phase_sk);
                                $block_list_rows = $block_list_result['rows']; 
                                $q_block_list_data = $block_list_result['data'];
                            ?>
                            <?php if($block_list_rows > 0){ ?>
                                <?php foreach($q_block_list_data as $block_list_data){ ?>
                                    <?php 
                                        $today_date = date('Y-m-d');
                                        $start_date = $block_list_data->start_date;
                                        $end_date = $block_list_data->end_date;
                                        $block_start_date = $this->general->display_date_format_front($block_list_data->start_date);
                                        $block_end_date = $this->general->display_date_format_front($block_list_data->end_date);
                                        $block_sk = $block_list_data->block_sk;
                                        $block_type = $block_list_data->block_type_code;
                                        $block_title = $block_list_data->block_title;
                                        $icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon.svg';
                                        $white_icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon-white.svg';
                                        if($block_type != ''){
                                            $image_name = $block_type;
                                            if($image_name == 'input'){
                                                $icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon.svg';
                                                $white_icon_image_url = $this->config->item("site_url").'assets/images/multiple-input-icon-white.svg';
                                            }elseif($image_name == 'feedback-cycle'){
                                                $icon_image_url = $this->config->item("site_url").'assets/images/feedback-cycle.png';
                                                $white_icon_image_url = $this->config->item("site_url").'assets/images/feedback-cycle1.png';
                                            }elseif($image_name == 'milestone'){
                                                $icon_image_url = $this->config->item("site_url").'assets/images/milestone.png';
                                                $white_icon_image_url = $this->config->item("site_url").'assets/images/milestone1.png';
                                            }else{
                                                $icon_image_url = $this->config->item("site_url").'assets/images/'.$image_name.'-icon.svg';
                                                $white_icon_image_url = $this->config->item("site_url").'assets/images/'.$image_name.'-icon-white.svg';
                                            }    
                                        }
                                        if($block_type == 'feedback'){
                                            $block_type = 'feedback-window';
                                        }         
                                        
                                        $task_done = '0';
                                        $task_result = $this->project_model->fn_get_all_assigned_task_by_block_sk($block_sk);
                                        $task_rows = $task_result['rows']; 
                                        $q_task_list_data = $task_result['data'];                                
                                        if($task_rows > 0){
                                        	foreach($q_task_list_data as $task_list_data){
                                                $task_done = $task_list_data->task_done;    
                                            }
                                        }                                                                                                                                  
                    				?>
                                    <div class="new-phase-detail <?php echo $block_type;?>">
                                        <div class="npd-head d-flex justify-content-between">
                                            <div class="npd-icon d-flex align-items-center">
                                                <img src="<?php echo $icon_image_url;?>" alt="" />
                                                <span> <?php echo $block_title;?></span>
                                            </div>
                                            <?php if( (strtotime($end_date) < strtotime($today_date)) && $task_done == '0' ) { ?>
                                            <?php }else{ ?>
                                                <?php if(strtotime($end_date) < strtotime($today_date)){ ?>
                                                    <span class="new-check-icon"><i class="fas fa-check"></i></span>
                                                <?php } ?>    
                                            <?php } ?>                                            
                                        </div>
                                        <p class="new-meta-date"><span><?php echo $block_start_date;?></span><em>to</em><span><?php echo $block_end_date;?></span></p>
                                    </div>
                                <?php } ?>
                            <?php }else{ ?>
                                <p class="empty-phase-desc">You haven't added any blocks to this phase yet.</p>
                                <div class="please-add-block btn" onclick="cancelUpdateBlock('<?php echo $project_sk;?>','<?php echo $phase_sk;?>','','')" >Add a block</div>                                                                      
                            <?php } ?>                            
                        </div>                              
                    </form>
                </div>
            </div>
            <div class="sb-footer">
                <a href="javascript:void(0)" class="cancel-link" onclick="cancelUpdatePhase('<?php echo $project_sk;?>')" >Cancel</a>
                <a href="javascript:void(0)" id="phase_save_changes_btn" class="savechange-btn" onclick="funAjaxPostUpdatePhaseSideDiv('<?php echo $project_sk;?>','<?php echo $phase_sk;?>')" >Save Changes</a>        
                <a href="javascript:void(0)" id="wait_phase_save_changes_btn" class="savechange-btn display-hide" ><b class="btn-loader-side-block"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Save Changes</a>
            </div>
        </div>
    </div>
</div>