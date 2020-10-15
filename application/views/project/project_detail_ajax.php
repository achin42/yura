<!-- block-main container start -->
<div class="add-phase-container">
    <input type="hidden" name="block_cancel_mode" id="block_cancel_mode" class="form-control" value="<?php echo $block_cancel_mode;?>" >
    <?php $total_block_count = '0'; if($phase_rows > 0){ ?>
        <?php $phase_cnt_for_active = 0; foreach($q_phase_data as $phase_data){ 
            $phase_sk = $phase_data->phase_sk;
            $phase_name = $phase_data->phase_name;
            $phase_start_date = $phase_data->start_date; 
            $phase_end_date = $phase_data->end_date;
            
            $new_calendar_block_arr = $this->project_model->fn_get_all_blocks_by_phase_sk($phase_sk);
            if(!empty($new_calendar_block_arr[$phase_sk])){
                $phase_calendar_block_arr = $new_calendar_block_arr[$phase_sk];
            }else{
                $phase_calendar_block_arr = array();
            }
            
            $today_date = date('Y-m-d');
            $remaining_task_list_result = $this->project_model->fn_get_all_remaining_task_by_phase_sk($phase_sk);
            $remaining_task_list_rows = $remaining_task_list_result['rows']; 
            $q_remaining_task_list_data = $remaining_task_list_result['data'];            
        ?>      
                    
            <?php if($phase_cnt_for_active == 0){?>
                <input type="hidden" name="sel_phase_sk" id="sel_phase_sk" class="form-control" value="<?php echo $sel_phase_sk;?>" >
            <?php } ?>
            <div class="phase-content-box <?php if($sel_phase_sk == $phase_sk){?>selected<?php } ?>" id="sel_phase_content_box_<?php echo $phase_sk;?>" >
                <?php 
                    $phase_date_diff = $this->general->date_diff_in_days($phase_end_date, $phase_start_date); 
					$phase_div_width = ($phase_date_diff+1) * 30;
                    $phase_day_diff = $this->general->date_diff_in_days($agreement_start_date, $phase_start_date);
                    $phase_div_left = $phase_day_diff * 30;
                    $agreement_day_diff = $this->general->date_diff_in_days($agreement_end_date, $agreement_start_date);
                    $agreement_width = $agreement_day_diff * 30;
                    $phase_connector_width = $phase_div_left + $phase_div_width - 30;
                    $phase_connector_left = $phase_div_left + $phase_div_width;
				?>
                <div class="pc-box" style="left: 0px; width: <?php echo $phase_div_width;?>px;position:relative;">
                    <div class="pc-head" style="position:relative;">
                        <a href="javascript:void(0)" class="remove_phase_selection" onclick="funSelectPhase('<?php echo $phase_sk;?>','<?php echo $project_sk;?>')" >
                            <div id="sel_phase_<?php echo $phase_sk;?>" class="phase-btn remove-phase-active <?php if($sel_phase_sk == $phase_sk){?>phase-btn-active<?php } ?>" style="left: <?php echo $phase_div_left;?>px; width: <?php echo $phase_div_width;?>px;">
                                <?php if($phase_date_diff > '5'){ ?><?php echo $phase_name;?><?php }else{ ?>&nbsp;<?php } ?>
                                <?php if($remaining_task_list_rows > 0){ ?>
                                    
                                <?php }else{ ?>     
                                    <?php if(strtotime($phase_end_date) < strtotime($today_date)){ ?>
                                        <div class="action-icon">
                                            <span><i class="fas fa-check"></i></span>
                                        </div>
                                    <?php } ?>    
                                <?php } ?>    
                            </div>                                
                        </a>
                        <em class="phase-connector" style="width: calc(<?php echo $agreement_width;?>px - <?php echo $phase_connector_width;?>px);left: <?php echo $phase_connector_left;?>px;"></em>                                                    
                    </div>
                    <div class="pc-body" style="left: <?php echo $phase_div_left;?>px; width: <?php echo $phase_div_width;?>px;position:relative;">
                        <ul class="phase-list" id="<?php echo $phase_sk;?>" >
                            <?php $phase_cnt_for_active++; for($i=0;$i<count($phase_calendar_block_arr);$i++){ ?>
                            	<?php $inner_calendar_block_arr = $phase_calendar_block_arr[$i];?>
                            	<li>
                            		<div class="phase-list-box">
                            			<?php $last_div_width = 0; $total_block_count_of_current_phase = '0'; for($j=0;$j<count($inner_calendar_block_arr);$j++){ ?>
                            				<?php 
                                                $total_block_count = $total_block_count + 1;
                                                $total_block_count_of_current_phase = $total_block_count_of_current_phase + 1;
                                                $block_sk = $inner_calendar_block_arr[$j]['block_sk'];
                                                $block_type = $inner_calendar_block_arr[$j]['block_type'];
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
                                                $today_date = date('Y-m-d');
                            					$start_date = $inner_calendar_block_arr[$j]['start_date']; 
                            					$end_date = $inner_calendar_block_arr[$j]['end_date'];
                            					$date_diff = $this->general->date_diff_in_days($end_date, $start_date); 
                            					$div_width = ($date_diff+1) * 30;
                            					$day_diff = $this->general->date_diff_in_days($start_date, $agreement_start_date);
                                                $div_left = $day_diff * 30;
                                                $completed_icon_class = "input-completed-icon";
                                                if($block_type == 'input'){
                                                    $clock_icon_color = '#FF9900';
                                                    $completed_icon_class = "input-completed-icon";
                                                }elseif($block_type == 'meeting'){
                                                    $clock_icon_color = '#65269A';
                                                    $completed_icon_class = "meeting-completed-icon";
                                                }elseif($block_type == 'proposal'){
                                                    $clock_icon_color = '#FF6761';
                                                    $completed_icon_class = "proposal-completed-icon";
                                                }elseif($block_type == 'payment'){
                                                    $clock_icon_color = '#00C4B1';
                                                    $completed_icon_class = "payment-completed-icon";
                                                }elseif($block_type == 'feedback'){
                                                    $clock_icon_color = '#B63EEA';
                                                    $completed_icon_class = "feedback-completed-icon";
                                                }elseif($block_type == 'feedback-cycle'){
                                                    $clock_icon_color = '#00AEEF';
                                                    $completed_icon_class = "feedback-cycle-completed-icon";
                                                }elseif($block_type == 'approval'){
                                                    $clock_icon_color = '#43B971';
                                                    $completed_icon_class = "approval-completed-icon";
                                                }elseif($block_type == 'milestone'){
                                                    $clock_icon_color = '#2E5BFF';
                                                    $completed_icon_class = "milestone-completed-icon";
                                                }  
                                                $block_line_width = ($div_left - 30); 
                                                
                                                $task_done = '1';
                                                $task_result = $this->project_model->fn_check_task_done_of_block($block_sk);
                                                $task_rows = $task_result['rows']; 
                                                $q_task_list_data = $task_result['data'];                                
                                                if($task_rows > 0){
                                                	$task_done = '0';
                                                }                                                                                             
                            				?>
                                            <a href="javascript:void(0)" class="remove_block_selection" onClick="openEditBlock('<?php echo $block_sk;?>','<?php echo $project_sk;?>','<?php echo $phase_sk;?>');" >
                                				<div id="block_<?php echo $block_sk;?>" class="phase-desc <?php echo $block_type;?> <?php if( (strtotime($end_date) < strtotime($today_date)) && $task_done == '0') { ?>delay<?php } ?>" style="left: <?php echo $div_left - $phase_div_left;?>px; width: <?php echo $div_width;?>px;">
                                                    <em class="block-connector" style="width: calc(<?php echo $agreement_width;?>px - <?php echo $block_line_width;?>px);" ></em>
                                					<span class="phase-desc-icon"><img src="<?php echo $icon_image_url;?>" class="phase-icon"><img src="<?php echo $white_icon_image_url;?>" class="phase-icon-white"></span>
                                                    <?php //if( (strtotime($end_date) < strtotime($today_date)) && $task_done == '0' ) { ?>
                                                    <?php if( $task_done == '0' ) { ?>
                                                        <span class="clok-icon">                                                                                                                      
                                                          <svg width="14px" height="14px" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                              <g id="Delay-Management" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                  <g id="01-Delay-C-Flow" transform="translate(-431.000000, -289.000000)">
                                                                      <g id="Group-4" transform="translate(431.000000, 289.000000)">
                                                                          <circle id="Oval" fill="<?php echo $clock_icon_color;?>" cx="7" cy="7" r="7"></circle>
                                                                          <g id="clock-time-three" transform="translate(0.777778, 0.777778)" fill="#FFFFFF" fill-rule="nonzero">
                                                                              <path d="M6.22222222,0 C2.8,0 0,2.8 0,6.22222222 C0,9.64444444 2.8,12.4444444 6.22222222,12.4444444 C9.64444444,12.4444444 12.4444444,9.64444444 12.4444444,6.22222222 C12.4444444,2.8 9.64444444,0 6.22222222,0 M9.33333333,6.84444444 L5.6,6.84444444 L5.6,3.11111111 L6.53333333,3.11111111 L6.53333333,5.91111111 L9.33333333,5.91111111 L9.33333333,6.84444444 Z" id="Shape"></path>
                                                                          </g>
                                                                      </g>
                                                                  </g>
                                                              </g>
                                                          </svg>
                                                        </span>
                                                    <?php }else{ ?>
                                                        <?php //if(strtotime($end_date) < strtotime($today_date)){ ?>
                                                            <span class="check-icon <?php echo $completed_icon_class;?>"><i class="fas fa-check"></i></span>
                                                        <?php //} ?>    
                                                    <?php } ?>    
                                					<b <?php if($date_diff <= '1' && (strtotime($end_date) < strtotime($today_date)) ){ ?>style="display:none;"<?php } ?> ><?php if($inner_calendar_block_arr[$j]['block_no'] != ''){ ?><?php echo $inner_calendar_block_arr[$j]['block_no'];?><?php }else{ ?><?php echo $inner_calendar_block_arr[$j]['block_title'];?><?php } ?></b>                                                
                                				</div>
                                            </a>
                            			<?php } ?>
                            		</div>
                            	</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>            
        <?php } ?>                                    
    <?php } ?>                        
</div>
<div class="add-switch-btn">
    <a href="javascript:void(0)" onClick="openCreatePhaseModal('<?php echo $cluster_sk;?>','<?php echo $project_sk;?>');" >
        <span class="switch-block-btn"><i class="fas fa-plus"></i> Add Phase</span>
    </a>
    <?php if($total_block_count == '0'){ ?>
    <div class="blank-label">
        <p>Let's create <strong>Block</strong>. You can see a few different kind of blocks in the panel that just popped out from the right. Each block will generate one or more tasks. Press the '+' button on the suitable block to see it getting added.</p>
    </div>                    
    <?php } ?>
</div>

<!-- block-main container end -->

<div class="block-content">
    <!-- Block Calendar Start -->
    <div class="monthly-block-container d-flex">
        <div class="mb-box">
            <div class="mb-head monthly-block-mb-head">               
                <?php for($i=0;$i<count($month_name_array);$i++){ ?>
                    <?php $div_width = $month_wise_days_count_array[$i] * 30; ?>
                    <h5 class="monthly-block-mb-head-title" style="width:<?php echo $div_width;?>px;" ><?php echo $month_name_array[$i];?></h5>
                <?php } ?>
            </div>
            <div class="mb-body">    
                <ul class="date-block-list">
                    <?php  
                        $display_active = 'no';                  
                    	for($i=0;$i<count($dates_array);$i++){
                            $today_date = date('Y-m-d');
                            $today_day_name = date('j',strtotime($today_date));                        
                    		$day_name = date('j',strtotime($dates_array[$i]));
                            $today_month_name = date('F',strtotime($today_date));                        
                    		$day_month_name = date('F',strtotime($dates_array[$i]));
                            $text_day_name = date('D',strtotime($dates_array[$i]));
                    ?>
                        <li <?php if( ($today_month_name == $day_month_name) && ($today_day_name == $day_name) && $display_active == 'no'){ $display_active = 'yes';?>class="active <?php if(strtolower($text_day_name) == 'sat' || strtolower($text_day_name) == 'sun' ){?>add-backdrop-bg<?php } ?>"<?php }else{ ?><?php if(strtolower($text_day_name) == 'sat' || strtolower($text_day_name) == 'sun' ){?>class="add-backdrop-bg"<?php } ?><?php } ?> ><span><?php echo $day_name;?></span></li>                               
                    <?php } ?>                                             
                </ul>
            </div>
        </div>
    </div>
    <!-- Block Calendar End -->
</div>