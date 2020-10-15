<div class="side-block-container execution-phase">
    <div class="sb-head d-flex align-items-center justify-content-between">
        <h6><?php echo $q_phase_data[0]->phase_name;?></h6>
        <div class="sb-right-box d-flex"><div class="phase-close-icon"><img onclick="cancelDisplayPhase('<?php echo $project_sk;?>')" src="<?php echo $this->config->item("site_url");?>assets/images/close1.png" alt="" /></div></div>                          
    </div>
    <div class="sb-body">
        <div class="sb-body-upper add-margin-top-15">
            <p><?php echo nl2br($q_phase_data[0]->phase_description); ?></p>
            <p class="new-meta-date"><span><b><?php echo $this->general->display_date_format_front($q_phase_data[0]->start_date);?></b></span><em>to</em><span><b><?php echo $this->general->display_date_format_front($q_phase_data[0]->end_date);?></b></span></p>
        </div>
        <div class="sb-body-lower">
            <?php 
                $block_list_result = $this->project_model->fn_get_all_blocks_by_phase_sk_in_side_div_panel($phase_sk);
                $block_list_rows = $block_list_result['rows']; 
                $q_block_list_data = $block_list_result['data'];
            ?>
            <?php if($block_list_rows > 0){ ?>
                <h5>Blocks</h5>
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
                                                                                     
            <?php } ?>
        </div>
    </div>
</div>