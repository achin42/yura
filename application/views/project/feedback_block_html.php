<div class="side-block-container feedback-block-container">
    <div class="sb-head">
        <h6><span class="sb-head-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/feedback-icon-white.svg" alt=""></span>Feedback Window</h6>
        <div class="sb-right-box">
            <?php if($block_data[0]->block_no != ''){ ?><span class="sb-text"><?php echo $block_data[0]->block_no; ?></span><?php } ?>
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                <div class="dropdown-menu"><a class="dropdown-item" href="javascript:void(0)" onclick="fun_delete_block('<?php echo $project_sk;?>','<?php echo $block_sk;?>','<?php echo $phase_sk;?>')" >Delete</a></div>
            </div>
        </div>
    </div>                                              
    <div class="sb-body phase-sb-body">
        <form name="frm_update_block" id="frm_update_block" action="<?php echo $this->config->item("site_url");?>project/fn_update_block" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
            <input type="hidden" name="block_sk" id="block_sk" class="form-control" value="<?php echo $block_sk;?>" >
            <input type="hidden" name="phase_sk" id="phase_sk" class="form-control" value="<?php echo $phase_sk;?>" >
            <input type="hidden" name="project_sk" id="project_sk" class="form-control" value="<?php echo $project_sk;?>" >
            <input type="hidden" name="first_task" id="first_task" class="form-control" value="no" >
            <input type="hidden" name="second_task" id="second_task" class="form-control" value="no" >
            <div class="pahse-sb-upper">
                <div class="phase-form-container">
                    <div class="form-group"><div class="sub-text"><?php echo $block_data[0]->block_type_description; ?></div></div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="block_title" id="block_title" required="required" data-required-error="Title cannot be blank." value="<?php echo $block_data[0]->block_title; ?>" >
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="block_description" id="block_description" ><?php echo $block_data[0]->block_description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="input-daterange-1">Date Range</label>
                        <div class="input-daterange-1 from-to-date-picker-div">
                            <input type="text" class="form-control from-date-picker" readonly name="start_date" id="block_start_date" required="required" data-required-error="Date Range cannot be blank." value="<?php echo $this->general->display_date_format_in_input($block_data[0]->start_date); ?>">
                            <div class="to-text-date-picker">to</div>
                            <input type="text" class="form-control to-date-picker" readonly name="end_date" id="block_end_date" required="required" data-required-error="Date Range cannot be blank." value="<?php echo $this->general->display_date_format_in_input($block_data[0]->end_date); ?>">
                        </div>
                        <span class="help-block with-errors"></span>
                    </div>                    
                    <!--div class="form-group">
                        <label>Dependencies <span style="color: #0034DA;padding-right: 12px;"><i class="fas fa-plus"></i></span></label>
                        <div class="input-group form-control   d-flex align-items-center ">
                            <div class="phase-desc" style="width: 48px;    height: 25px;">
                                <span class="phase-desc-icon"><img src="assets/images/input.png"></span><b>1</b>                                    
                            </div>
                            <input type="text" value="" class="form-control">
                        </div>
                    </div-->
                </div>
            </div>
            <?php if($task_rows > 0){ ?>
                <input type="hidden" name="task_cnt" id="task_cnt" class="form-control" value="<?php echo $task_rows;?>" >
                <div class="pahse-sb-lower">
                    <h6><em>Tasks</em></h6>
                    <?php $i = 0; foreach($q_task_data as $task_data){ ?>
                        <input type="hidden" name="task_sks[]" class="form-control" value="<?php echo $task_data->tasks_sk;?>" >
                        <div class="task-card">
                            <h5>Task - <?php echo $task_data->task_type_title;?></h5>
                            <div class="card-text"><?php echo nl2br($task_data->description);?></div>
                            <div class="card-link-box d-flex align-items-center">
                                <h3><em>Task Owner</em></h3>
                                <ul class="card-link-list d-flex">
                                    <?php if($task_rows == '1'){ ?>
                                        <li id="sel_task_agency_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'agency'){ ?>class="active remove-task-active"<?php }else{ ?>class="remove-task-active"<?php } ?> ><a href="javascript:void(0)" onclick="funSelectTask('agency','<?php echo $task_data->tasks_sk;?>')" >agency</a></li>
                                        <li id="sel_task_client_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'client'){ ?>class="active remove-task-active"<?php }else{ ?>class="remove-task-active"<?php } ?> ><a href="javascript:void(0)" onclick="funSelectTask('client','<?php echo $task_data->tasks_sk;?>')" >client</a></li>
                                    <?php }else{ ?>
                                        <?php if($i == '0'){ ?>
                                            <li id="sel_task_agency_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'agency'){ ?>class="active remove-task-active"<?php }else{ ?>class="remove-task-active"<?php } ?> ><a href="javascript:void(0)" onclick="funSelectTask('agency','<?php echo $task_data->tasks_sk;?>')" >agency</a></li>
                                            <li id="sel_task_client_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'client'){ ?>class="active remove-task-active"<?php }else{ ?>class="remove-task-active"<?php } ?> ><a href="javascript:void(0)" onclick="funSelectTask('client','<?php echo $task_data->tasks_sk;?>')" >client</a></li>
                                        <?php }else{ ?>
                                            <li id="sel_task_agency_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'agency'){ ?>class="active remove-task-active other_agency"<?php }else{ ?>class="remove-task-active other_agency"<?php } ?> ><a href="javascript:void(0)" style="cursor: default;" >agency</a></li>
                                            <li id="sel_task_client_<?php echo $task_data->tasks_sk;?>" <?php if($task_data->assignee_users_type == 'client'){ ?>class="active remove-task-active other_client"<?php }else{ ?>class="remove-task-active other_client"<?php } ?> ><a href="javascript:void(0)" style="cursor: default;" >client</a></li>                                            
                                        <?php } ?>                                            
                                    <?php } ?>    
                                </ul>
                            </div>
                            <!--div class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu"><a class="dropdown-item" href="#">option1</a></div>
                            </div-->
                        </div>    
                    <?php $i++; } ?>                    
                </div>
            <?php } ?>
        </form>
    </div>
    <div class="sb-footer">
        <a href="javascript:void(0)" class="cancel-link" onclick="cancelUpdateBlock('<?php echo $project_sk;?>','<?php echo $phase_sk;?>','<?php echo $block_cancel_mode;?>','<?php echo $block_sk;?>')" >Cancel</a>
        <a href="javascript:void(0)" id="save_changes_btn" class="savechange-btn" onclick="funAjaxPostUpdateBlock('<?php echo $project_sk;?>','<?php echo $phase_sk;?>')" >Save Changes</a>        
        <a href="javascript:void(0)" id="wait_save_changes_btn" class="savechange-btn display-hide" ><b class="btn-loader-side-block"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b>Save Changes</a>
    </div>
</div>