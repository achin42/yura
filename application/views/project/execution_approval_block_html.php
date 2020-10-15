<div class="side-block-container approval-block-container">
    <div class="sb-head">
        <h6><span class="sb-head-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/approval-icon-white.svg" alt=""></span>Approval</h6>
        <div class="sb-right-box">
            <?php if($block_data[0]->block_no != ''){ ?><span class="sb-text"><?php echo $block_data[0]->block_no; ?></span><?php } ?>            
        </div>
    </div>                                              
    <div class="sb-body phase-sb-body">
        <form name="frm_update_block" id="frm_update_block" action="<?php echo $this->config->item("site_url");?>project/fn_update_execution_block" method="post" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
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
                            <div class="text-box execution-text-box"><?php echo $block_data[0]->block_title; ?></div>
                        </div>
                    
                    
                        <div class="form-group">
                            <label for="description">Description</label>
                            <div class="text-box execution-text-area"><?php echo nl2br($block_data[0]->block_description); ?></div>
                        </div>
                    
                    
                        <div class="form-group">
                            <label for="input-daterange-1">Date Range</label>
                            <div class="input-daterange-1 from-to-date-picker-div">
                                <div class="text-box execution-text-box from-date-picker"><?php echo $this->general->display_date_format_in_input($block_data[0]->start_date); ?></div>
                                <div class="to-text-date-picker">to</div>
                                <div class="text-box execution-text-box to-date-picker"><?php echo $this->general->display_date_format_in_input($block_data[0]->end_date); ?></div>
                            </div>
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
            <?php if(!empty($active_task_list_arr) && count($active_task_list_arr) > 0){ ?>
                <div class="pahse-sb-lower">
                    <h6><em>Tasks</em></h6>
                    <?php for($i=0;$i<count($active_task_list_arr);$i++){ ?>
                        <div class="task-card">
                            <h5>Task - <?php echo $active_task_list_arr[$i]['task_type_title'];?></h5>
                            <div class="card-text"><?php echo $active_task_list_arr[$i]['description'];?></div>
                            <div class="task-btn btn-block">
                                <?php if($active_task_list_arr[$i]['action_name'] == 'pay_now' && $active_task_list_arr[$i]['payment_action'] == '1'){ ?>
                                    <a href="javascript:void(0);">pay now</a>
                                <?php }elseif($active_task_list_arr[$i]['action_name'] == 'mark_as_done'){ ?>
                                    <?php if($active_task_list_arr[$i]['task_done'] == '0'){ ?>
                                        <a id="mark_as_done_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $project_sk;?>');" >mark as done</a>
                                        <a style="display:none;" id="mark_as_incomplete_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $project_sk;?>');" >mark as incomplete</a>
                                    <?php }else{ ?>
                                        <a style="display:none;" id="mark_as_done_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $project_sk;?>');" >mark as done</a>
                                        <a id="mark_as_incomplete_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $project_sk;?>');" >mark as incomplete</a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>    
                    <?php } ?>                    
                </div>
            <?php } ?>
        </form>
    </div>    
</div>