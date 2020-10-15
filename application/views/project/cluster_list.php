<!doctype html>
    <html lang="en">
    <head>
        <?php $this->load->view('inc/header-include');?>                
    </head>
    <body <?php if($filter_company_rows > 0 && $project_rows > 0){ ?>class="show-notification"<?php } ?> >
        <!-- ======================== Topbar start ========================== -->
            <?php $this->load->view('inc/header-menu');?>
        <!-- ======================== Topbar end ========================== -->

        <!-- ======================== Main container start ========================== -->
        <?php if($filter_company_rows > 0 && $project_rows > 0){ ?>
            <div class="main-container">
                <div class="main-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="filter-content-box ajax-loader-container">
                    				<div class="ajax-loader-div" id="ajax-loader-div">
                    					<img class="ajax-loader-img loader-img fa-spin" src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg">
                    				</div>
                    				<div id="project_list_container"></div>                            
                    			</div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }else{ ?>
            <div class="main-container dashboard-container d-flex">
                <div class="dashboard-content">
                    <div class="dashboard-left d-flex align-items-center">
                        <div class="dl-box">
                            <?php 
                                if($user_login_mode == 'AGENCY'){
                                    $all_default_desc_text = 'Clients';                                                    
                                }else{
                                    $all_default_desc_text = 'Agencies';                                                                                    
                                }
                            ?>
                            <div class="dl-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/Project.svg" alt="" /></div>
                            <h5>Glad you're here. Create a project!</h5>
                            <p class="cluster-pink-text">A <strong>CLUSTER</strong> would let you manage multiple PROJECTS with it.<br /><strong>PROJECTS</strong> are visually composed through C-FLOWS (C for Collaboration).<br /><strong>C-FLOWS</strong> can be changed. Also after signing.</p>
                            <p><strong>For example:</strong> A Cluster title could be: 'Rebranding 2020'<br />Then Project titles could be: 'Brandbook' and 'Website' as these are part of 'Rebranding 2020'. Both projects will have separate C-flows. If you don't need to use a cluster as an umbrella above your projects, then we advise you to repeat the CLIENT NAME.</p>
                            <div class="cp-btn"><a href="javascript:void(0)" class="btn btn-primary" onClick="openCreateClusterModal();" >Create your first project</a></div>
                        </div>
                    </div> 
                    <div class="dashboard-right">
                        <div class="dr-box add-border">
                            <div class="dr-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/Tasks.svg" alt="" /></div>
                            <p>So this is where we'll list your <strong>Tasks</strong> once you create and start executing C-Flows.</p>
                            <p>At some stage we will create integrations with your favorite task management platform like <strong>Jira,</strong> <strong> Asana</strong> or <strong>Trello</strong> and will make your life sweet as honey!</p>
                        </div>
                    </div>             
                </div>
            </div>
        <?php } ?>    
        <!-- ======================== Main container end ========================== -->
        <?php if($filter_company_rows > 0 && $project_rows > 0){ ?>
            <?php if( (!empty($overdue_task_list_arr) && count($overdue_task_list_arr) > 0 && $overdue_not_done_task_cnt > 0) || (!empty($active_task_list_arr) && count($active_task_list_arr) > 0) || (!empty($upcoming_task_list_arr) && count($upcoming_task_list_arr) > 0) ){ ?>
                <div class="notification-container">
                    <div class="notification-head d-flex align-items-center justify-content-between">
                        <h6><span class="notification-head-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/tasks-icon.svg" alt="" /></span>Tasks</h6>
                        <!--strong><a href="#">all done</a></strong-->
                    </div>
                    <div class="notification-body">
                        <?php if(!empty($overdue_task_list_arr) && count($overdue_task_list_arr) > 0 && $overdue_not_done_task_cnt > 0){ ?>
                            <div class="task-box overdue-task-box">
                                <div class="task-heading"><em>Overdue tasks</em></div>
                                <ul class="tasks-list">
                                    <?php for($i=0;$i<count($overdue_task_list_arr);$i++){ ?>
                                        <?php if($overdue_task_list_arr[$i]['task_done'] == '0'){ ?>
                                            <li class="task-item" id="remove_task_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>">
                                                <div class="tasks-list-box">
                                                    <div class="task-color" style="background-color: <?php echo $overdue_task_list_arr[$i]['color_code'];?>;"></div>
                                                    <?php if($overdue_task_list_arr[$i]['action_name'] == 'pay_now' && $overdue_task_list_arr[$i]['payment_action'] == '1'){ ?>
                                                        <?php if($overdue_task_list_arr[$i]['block_payment_amount'] != '' && $overdue_task_list_arr[$i]['block_payment_amount'] > '0'){ ?>
                                                            <div class="tasks-text">Task - <?php echo $overdue_task_list_arr[$i]['task_type_title'];?></div>
                                                            <div class="tasks-note">Pay $<?php echo number_format($overdue_task_list_arr[$i]['block_payment_amount'],2);?> for the Phase '<?php echo $overdue_task_list_arr[$i]['phase_name'];?>' by <?php echo $this->general->display_date_format_front($overdue_task_list_arr[$i]['block_end_date']);?></div>
                                                            <div class="tasks-date"><?php echo $this->general->display_overdue_date_in_task_list($overdue_task_list_arr[$i]['block_end_date']);?></div>
                                                            <div class="tasks-btn">
                                                                <?php if($overdue_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                                    <a class="paynow-btn" href="javascript:void(0);" id="pay_now_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>','<?php echo $overdue_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                    <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="unpaid_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>','<?php echo $overdue_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                                <?php }else{ ?>
                                                                    <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="pay_now_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>','<?php echo $overdue_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                    <a class="paynow-btn" href="javascript:void(0);" id="unpaid_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>','<?php echo $overdue_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                                <?php } ?>    
                                                            </div>
                                                         <?php }else{ ?>
                                                            <div class="tasks-text">Task - <?php echo $overdue_task_list_arr[$i]['task_type_title'];?></div>
                                                            <div class="tasks-note"><?php echo $overdue_task_list_arr[$i]['description'];?></div>
                                                            <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($overdue_task_list_arr[$i]['block_end_date']);?></div>
                                                            <div class="tasks-btn"><a href="javascript:" class="paynow-btn">pay now</a></div>
                                                         <?php } ?>   
                                                    <?php }elseif($overdue_task_list_arr[$i]['action_name'] == 'mark_as_done'){ ?>
                                                        <div class="tasks-text">Task - <?php echo $overdue_task_list_arr[$i]['task_type_title'];?></div>
                                                        <div class="tasks-note"><?php echo $overdue_task_list_arr[$i]['description'];?></div>
                                                        <div class="tasks-date"><?php echo $this->general->display_overdue_date_in_task_list($overdue_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-btn">
                                                            <?php if($overdue_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                                <a class="paynow-btn" id="mark_as_done_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                                <a class="paynow-btn" style="display:none;" id="mark_as_incomplete_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                            <?php }else{ ?>
                                                                <a class="paynow-btn" style="display:none;" id="mark_as_done_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                                <a class="paynow-btn" id="mark_as_incomplete_<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $overdue_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                            <?php } ?>                                                        
                                                        </div>
                                                    <?php } ?>                                                    
                                                </div>
                                            </li>
                                        <?php } ?>                                            
                                    <?php } ?>                                        
                                </ul>
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty($active_task_list_arr) && count($active_task_list_arr) > 0){ ?>
                            <div class="task-box">
                                <div class="task-heading"><em>Active tasks</em></div>                        
                                <ul class="tasks-list">
                                    <?php for($i=0;$i<count($active_task_list_arr);$i++){ ?>
                                        <li class="task-item">
                                            <div class="tasks-list-box">
                                                <div class="task-color" style="background-color: <?php echo $active_task_list_arr[$i]['color_code'];?>;"></div>                                                
                                                <?php if($active_task_list_arr[$i]['action_name'] == 'pay_now' && $active_task_list_arr[$i]['payment_action'] == '1'){ ?>
                                                    <?php if($active_task_list_arr[$i]['block_payment_amount'] != '' && $active_task_list_arr[$i]['block_payment_amount'] > '0'){ ?>
                                                        <div class="tasks-text">Task - <?php echo $active_task_list_arr[$i]['task_type_title'];?></div>
                                                        <div class="tasks-note">Pay $<?php echo number_format($active_task_list_arr[$i]['block_payment_amount'],2);?> for the Phase '<?php echo $active_task_list_arr[$i]['phase_name'];?>' by <?php echo $this->general->display_date_format_front($active_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($active_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-btn">
                                                            <?php if($active_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                                <a class="paynow-btn" href="javascript:void(0);" id="pay_now_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $active_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="unpaid_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $active_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                            <?php }else{ ?>
                                                                <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="pay_now_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $active_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                <a class="paynow-btn" href="javascript:void(0);" id="unpaid_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>','<?php echo $active_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                            <?php } ?>    
                                                        </div>
                                                     <?php }else{ ?>
                                                        <div class="tasks-text">Task - <?php echo $active_task_list_arr[$i]['task_type_title'];?></div>
                                                        <div class="tasks-note"><?php echo $active_task_list_arr[$i]['description'];?></div>
                                                        <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($active_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-btn"><a href="javascript:" class="paynow-btn">pay now</a></div>
                                                     <?php } ?>   
                                                <?php }elseif($active_task_list_arr[$i]['action_name'] == 'mark_as_done'){ ?>
                                                    <div class="tasks-text">Task - <?php echo $active_task_list_arr[$i]['task_type_title'];?></div>
                                                    <div class="tasks-note"><?php echo $active_task_list_arr[$i]['description'];?></div>
                                                    <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($active_task_list_arr[$i]['block_end_date']);?></div>
                                                    <div class="tasks-btn">
                                                        <?php if($active_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                            <a class="paynow-btn" id="mark_as_done_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                            <a class="paynow-btn" style="display:none;" id="mark_as_incomplete_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                        <?php }else{ ?>
                                                            <a class="paynow-btn" style="display:none;" id="mark_as_done_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                            <a class="paynow-btn" id="mark_as_incomplete_<?php echo $active_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $active_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                        <?php } ?>                                                        
                                                    </div>
                                                <?php } ?>
                                                <!--div class="dropdown">
                                                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#">Option 1</a>                      
                                                    </div>
                                                </div-->
                                            </div>
                                        </li>
                                    <?php } ?>            
                                </ul>                                                    
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty($upcoming_task_list_arr) && count($upcoming_task_list_arr) > 0){ ?>                
                            <div class="task-box">
                                <div class="task-heading"><em>Upcoming tasks</em></div>
                                <ul class="tasks-list upcoming-task-list">
                                    <?php for($i=0;$i<count($upcoming_task_list_arr);$i++){ ?>
                                        <li class="task-item">
                                            <div class="tasks-list-box">
                                                <div class="task-color" style="background-color: <?php echo $upcoming_task_list_arr[$i]['color_code'];?>;"></div>                                                
                                                <?php if($upcoming_task_list_arr[$i]['action_name'] == 'pay_now' && $upcoming_task_list_arr[$i]['payment_action'] == '1'){ ?>
                                                    <?php if($upcoming_task_list_arr[$i]['block_payment_amount'] != '' && $upcoming_task_list_arr[$i]['block_payment_amount'] > '0'){ ?>
                                                        <div class="tasks-text">Task - <?php echo $upcoming_task_list_arr[$i]['task_type_title'];?></div>
                                                        <div class="tasks-note">Pay $<?php echo number_format($upcoming_task_list_arr[$i]['block_payment_amount'],2);?> for the Phase '<?php echo $upcoming_task_list_arr[$i]['phase_name'];?>' by <?php echo $this->general->display_date_format_front($upcoming_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($upcoming_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-btn">
                                                            <?php if($upcoming_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                                <a class="paynow-btn" href="javascript:void(0);" id="pay_now_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>','<?php echo $upcoming_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="unpaid_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>','<?php echo $upcoming_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                            <?php }else{ ?>
                                                                <a class="paynow-btn" href="javascript:void(0);" style="display:none;" id="pay_now_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" onclick="pay_now('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>','<?php echo $upcoming_task_list_arr[$i]['block_payment_amount'];?>');" >pay now</a>
                                                                <a class="paynow-btn" href="javascript:void(0);" id="unpaid_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" onclick="unpaid('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>','<?php echo $upcoming_task_list_arr[$i]['block_payment_amount'];?>');" >mark as unpaid</a>
                                                            <?php } ?>    
                                                        </div>
                                                     <?php }else{ ?>
                                                        <div class="tasks-text">Task - <?php echo $upcoming_task_list_arr[$i]['task_type_title'];?></div>
                                                        <div class="tasks-note"><?php echo $upcoming_task_list_arr[$i]['description'];?></div>
                                                        <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($upcoming_task_list_arr[$i]['block_end_date']);?></div>
                                                        <div class="tasks-btn"><a href="javascript:" class="paynow-btn">pay now</a></div>
                                                     <?php } ?>   
                                                <?php }elseif($upcoming_task_list_arr[$i]['action_name'] == 'mark_as_done'){ ?>
                                                    <div class="tasks-text">Task - <?php echo $upcoming_task_list_arr[$i]['task_type_title'];?></div>
                                                    <div class="tasks-note"><?php echo $upcoming_task_list_arr[$i]['description'];?></div>
                                                    <div class="tasks-date"><?php echo $this->general->display_due_date_in_task_list($upcoming_task_list_arr[$i]['block_end_date']);?></div>
                                                    <div class="tasks-btn">
                                                        <?php if($upcoming_task_list_arr[$i]['task_done'] == '0'){ ?>
                                                            <a class="paynow-btn" id="mark_as_done_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                            <a class="paynow-btn" style="display:none;" id="mark_as_incomplete_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                        <?php }else{ ?>
                                                            <a class="paynow-btn" style="display:none;" id="mark_as_done_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_completed('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>');" >mark as done</a>
                                                            <a class="paynow-btn" id="mark_as_incomplete_<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>" href="javascript:void(0);" onclick="mark_as_incomplete('<?php echo $upcoming_task_list_arr[$i]['tasks_sk'];?>');" >mark as incomplete</a>
                                                        <?php } ?>                                                        
                                                    </div>
                                                <?php } ?>
                                                <!--div class="dropdown">
                                                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#">Option 1</a>                      
                                                    </div>
                                                </div-->
                                            </div>
                                        </li>
                                    <?php } ?>            
                                </ul>                                                    
                            </div>
                        <?php } ?>                                                            
                    </div>
                </div>
            <?php }else{ ?>
                <div class="notification-container no-task-to-display">
                    <div class="dr-box add-border">
                        <div class="dr-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/Tasks.svg" alt=""></div>
                        <p>So this is where we'll list your <strong>Tasks</strong> once you create and start executing C-Flows.</p>
                        <p>At some stage we will create integrations with your favorite task management platform like <strong>Jira,</strong> <strong> Asana</strong> or <strong>Trello</strong> and will make your life sweet as honey!</p>
                    </div>
                </div>                
            <?php } ?>
        <?php } ?>
        <!-- ======================= Modal start ======================= -->
        <div class="modal fade client-detail-popup" id="create_project_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content" id="create_project_content"></div>
            </div>
        </div>
        <div class="modal fade create-agreement-popup" id="create_agreement_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content" id="create_agreement_content"></div>
            </div>
        </div> 
        <div class="modal fade cluster-popup-modal" id="create_cluster_popup" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content cluster-content" id="create_cluster_content">
                </div>
            </div>
        </div>
        <div class="modal fade cluster-popup-modal" id="create_agreement_with_phases_popup" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content cluster-content" id="create_agreement_with_phases_content">
                </div>
            </div>
        </div>        
        <!-- ======================= Modal end ======================= -->
        
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/dashboard-js');?>
        <?php $this->load->view('inc/js/cluster-list-js');?>
  </body>
</html>