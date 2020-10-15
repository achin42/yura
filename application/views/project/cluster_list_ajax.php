<?php
$logged_in_users_sk = $this->session->userdata('fe_user');
$user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
$user_rows = $user_result['rows']; 
$user_data = $user_result['data'];
if($user_rows == 0){
    redirect($this->config->item('site_url'));
    exit;    
}
$logged_in_user_type = $user_data[0]->user_type;
$user_login_mode = $this->session->userdata('fe_user_login_mode');
?>
<?php if($project_rows > 0){ ?>
<div class="filter-container">
    <div class="clients-filter-box d-flex">
    	<ul class="cf-list d-flex align-items-center flex-wrap">
            <?php 
                if($user_login_mode == 'AGENCY'){
                    $all_default_text = 'all clients';                                                    
                }else{
                    $all_default_text = 'all agencies';                                                                                    
                }
            ?>                 
    		<li class="active" id="li_1"><a href="javascript:void(0)" onClick="applyFilter('');" class="cf-box project_filter"><span><?php echo $all_default_text;?></span></a></li>
    		<?php if($filter_company_rows > 0 && $project_rows > 0){ ?>
    			<?php foreach($q_filter_company_data as $filter_company_data){ ?>
    				<li id="li_<?php echo $filter_company_data->users_sk?>">
    					<a href="javascript:void(0)" onClick="applyFilter('<?php echo $filter_company_data->users_sk?>');" class="cf-box project_filter">
    						<div class="cf-img">
    							<?php $full_file_path = $this->config->item("UPLOAD_DIR").'company_image/'.$filter_company_data->company_image; if($filter_company_data->company_image != '' && file_exists($full_file_path) ) {?>
    								<img src="<?php echo $this->config->item("UPLOAD_URL");?>company_image/<?php echo $filter_company_data->company_image; ?>" style="width: 15px;" alt="" />
    							<?php }else{ ?>
    								<img src="<?php echo $this->config->item("site_url");?>assets/images/default-project.svg" style="width: 15px;" alt="" />
    							<?php } ?>    
    						</div>
    						<span><?php echo $filter_company_data->company_name;?></span>
    					</a>
    				</li>
    			<?php } ?>
    		<?php } ?>                                    
    	</ul>
        <?php if(strtolower($user_login_mode) == 'agency'){ ?>
        	<div class="right-filter-bax ml-auto d-flex align-items-center">
        		<span class="v-line"></span>
        		<a href="javascript:void(0)" onClick="openCreateClusterModal();" class="btn btn-second">Add Cluster</a>
        	</div>
        <?php } ?>
    </div>                                    
    <div class="fc-head">
        <ul class="fch-list d-flex align-items-center">
            <li><?php echo $project_rows;?> <?php if($project_rows > 1){?>clusters<?php }else{ ?>cluster<?php } ?></li>
            <?php $total_agreement_count = '0'; foreach($q_project_data as $project_data){
                $cluster_sk = $project_data->cluster_sk;
                $agreement_result = $this->project_model->fn_get_project_list_by_cluster_sk($cluster_sk);
                $agreement_rows = $agreement_result['rows']; 
                $q_agreement_data = $agreement_result['data'];
                $total_agreement_count = $total_agreement_count + $agreement_rows;                                
            } ?>
            <li><?php echo $total_agreement_count;?> <?php if($total_agreement_count > 1){?>projects<?php }else{ ?>project<?php } ?></li>
        </ul>
    </div>
</div>
<div class="fc-body">
    <div class="fc-container">
        <?php foreach($q_project_data as $project_data){ ?>
            <?php
                $user_login_mode = $this->session->userdata('fe_user_login_mode');
                if($user_login_mode == 'AGENCY'){
                    $users_sk = $project_data->client_sk;
                }else{
                    $users_sk = $project_data->agency_sk;
                }
                $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
                $company_rows = $company_result['rows']; 
                $company_data = $company_result['data'];
                
                $cluster_sk = $project_data->cluster_sk;
                $agreement_result = $this->project_model->fn_get_project_list_by_cluster_sk($cluster_sk);
                $agreement_rows = $agreement_result['rows']; 
                $q_agreement_data = $agreement_result['data'];
                $project_start_date = '';
                $project_end_date = '';
                if($agreement_rows > 0){
                    $first_agreement_arr = reset($q_agreement_data);
                    $last_agreement_arr = end($q_agreement_data);
                    $project_start_date = $this->general->display_date_format_front($first_agreement_arr->start_date);
                    $project_end_date = $this->general->display_date_format_front($last_agreement_arr->end_date);                     
                }
                
                $phase_result = $this->project_model->fn_get_phase_list_by_cluster_sk($cluster_sk);
                $phase_rows = $phase_result['rows']; 
                $q_phase_data = $phase_result['data'];
                
                $project_start_date = '';
                $project_end_date = '';
                $total_project_payment = '0.00';
                $first_inserted_block_result = $this->project_model->fn_get_first_inserted_block_by_cluster($cluster_sk);
                $first_inserted_block_rows = $first_inserted_block_result['rows']; 
                $q_first_inserted_block_data = $first_inserted_block_result['data'];                
                if($first_inserted_block_rows > 0){
                    $start_date = $q_first_inserted_block_data[0]->start_date; 
                    $project_start_date = $this->general->display_date_format_front($start_date);
                }
                $last_inserted_block_result = $this->project_model->fn_get_last_inserted_block_by_cluster($cluster_sk);
                $last_inserted_block_rows = $last_inserted_block_result['rows']; 
                $q_last_inserted_block_data = $last_inserted_block_result['data'];                
                if($last_inserted_block_rows > 0){
                    $end_date = $q_last_inserted_block_data[0]->end_date; 
                    $project_end_date = $this->general->display_date_format_front($end_date);
                } 
                $total_payment_result = $this->project_model->fn_get_total_payment_of_cluster($cluster_sk);
                $total_payment_rows = $total_payment_result['rows']; 
                $q_total_payment_data = $total_payment_result['data'];                
                if($total_payment_rows > 0 && !empty($q_total_payment_data[0]->total_payment) && $q_total_payment_data[0]->total_payment != ''){
                    $total_project_payment = $q_total_payment_data[0]->total_payment; 
                }                                                                
            ?>
            
            <div class="fc-box">
                <div class="fc-project-color" style="background-color:<?php echo $project_data->color_code;?>;"></div>
                <div class="fc-header d-flex  align-items-center justify-content-between">
                    <div class="fc-intro d-flex">
                        <span class="fc-icon">
                            <?php $full_file_path = $this->config->item("UPLOAD_DIR").'cluster_image/'.$project_data->cluster_image; if($project_data->cluster_image != '' && file_exists($full_file_path) ) {?>
                                <img src="<?php echo $this->config->item("UPLOAD_URL");?>cluster_image/<?php echo $project_data->cluster_image; ?>" alt="" />
                            <?php }else{ ?>
                                <img src="<?php echo $this->config->item("site_url");?>assets/images/default-project.svg" alt="" />
                            <?php } ?>
                        </span>
                        <div class="fc-content"><strong><?php echo $project_data->cluster_title;?></strong><?php if($company_rows > 0){ ?><br><?php echo $company_data[0]->company_name;?>  /  <?php } ?><?php echo $project_data->contact_person_name;?></div>
                        <?php if(strtolower($user_login_mode) == 'agency'){ ?>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="fun_delete_cluster('<?php echo $project_data->cluster_sk;?>')">Delete</a>
                                </div>
                            </div>
                        <?php } ?>                            
                    </div>
                    <div class="fc-date d-flex flex-wrap flex-column">
                        <?php if($phase_rows > 0 && $project_start_date != '' && $project_end_date != ''){ ?><span><?php echo $project_start_date;?> - <?php echo $project_end_date;?></span><?php } ?>
                        <small>$<?php echo number_format($total_project_payment,2);?></small>
                    </div>
                    <div class="fc-icon d-flex align-items-center">
                        <h6><?php echo $agreement_rows;?> <?php if($agreement_rows > 1){?>projects<?php }else{ ?>project<?php } ?></h6>
                        <?php if(strtolower($user_login_mode) == 'agency'){ ?>                        
                            <a href="javascript:void(0)" onClick="openCreateAgreementWithPhasesModal('<?php echo $project_data->cluster_sk;?>');" class="plus-icon"><i class="fas fa-plus"></i></a>
                        <?php } ?>                            
                    </div>                                               
                </div>
                <?php if($agreement_rows == 0){ ?>
                    <div class="agreements-box">
                        <div class="agreement-left">
                            <a href="javascript:void(0)" onClick="openCreateAgreementWithPhasesModal('<?php echo $project_data->cluster_sk;?>');" class="agreement-link add-border">
                                <div class="add-icon"><i class="fas fa-plus"></i></div>
                                <p>Create a project</p>
                            </a>
                        </div>
                        <div class="agreement-right">
                            <div class="agreement-content d-flex  add-border">
                                <div class="agreement-img"><img src="<?php echo $this->config->item("site_url");?>assets/images/history.png" alt="" /></div>
                                <div class="agreement-desc">A <strong>project</strong> is at the very center of YuraApp. It's a visual representation of a usual static contract of work between clients and agencies and makes it dynamic.</div>
                            </div>
                        </div>
                    </div>                      
                <?php }else{ ?>
                    <ul class="fc-list-box d-flex flex-wrap">
                        <?php foreach($q_agreement_data as $agreement_data){ ?>
                            <?php
                                $agreement_start_date = '';
                                $agreement_end_date = '';
                                $total_agreement_payment = '0.00';
                                $no_of_milestone_block = '0';
                                $project_sk = $agreement_data->project_sk;
                                $first_inserted_block_result = $this->project_model->fn_get_first_inserted_block_by_project_sk($project_sk);
                                $first_inserted_block_rows = $first_inserted_block_result['rows']; 
                                $q_first_inserted_block_data = $first_inserted_block_result['data'];                
                                if($first_inserted_block_rows > 0){
                                    $start_date = $q_first_inserted_block_data[0]->start_date; 
                                    $agreement_start_date = $this->general->display_date_format_front($start_date);
                                }
                                $last_inserted_block_result = $this->project_model->fn_get_last_inserted_block_by_project_sk($project_sk);
                                $last_inserted_block_rows = $last_inserted_block_result['rows']; 
                                $q_last_inserted_block_data = $last_inserted_block_result['data'];                
                                if($last_inserted_block_rows > 0){
                                    $end_date = $q_last_inserted_block_data[0]->end_date; 
                                    $agreement_end_date = $this->general->display_date_format_front($end_date);
                                }
                                $total_agreement_payment_result = $this->project_model->fn_get_total_payment_of_project($project_sk);
                                $total_agreement_payment_rows = $total_agreement_payment_result['rows']; 
                                $q_total_agreement_payment_data = $total_agreement_payment_result['data'];                
                                if($total_agreement_payment_rows > 0 && !empty($q_total_agreement_payment_data[0]->total_payment) && $q_total_agreement_payment_data[0]->total_payment != ''){
                                    $total_agreement_payment = $q_total_agreement_payment_data[0]->total_payment; 
                                }
                                $milestone_block_result = $this->project_model->fn_get_milestone_blocks_by_project_sk($project_sk);
                                $milestone_block_rows = $milestone_block_result['rows']; 
                                $q_milestone_block_data = $milestone_block_result['data'];                
                                if($milestone_block_rows > 0){
                                    $no_of_milestone_block = $milestone_block_rows; 
                                }
                                
                                /*start check agreement signed or not*/
                                $agreement_signed = 'no';
                                $agreement_sign_result = $this->project_model->fn_get_latest_agreement_sign_detail_by_project_sk($agreement_data->project_sk);
                                $agreement_sign_rows = $agreement_sign_result['rows']; 
                                $q_agreement_sign_data = $agreement_sign_result['data'];
                                if($agreement_sign_rows > 0){
                                    $first_signer_signed = $q_agreement_sign_data[0]->first_signer_signed;
                                    $second_signer_signed = $q_agreement_sign_data[0]->second_signer_signed;
                                    if($first_signer_signed == '1' || $second_signer_signed == '1'){
                                        $agreement_signed = 'yes';
                                    }
                                }
                                /*end check agreement signed or not*/
                            ?>             
                            <li class="fcl-item">
                                <div class="fcl-box">                                   
                                    <div class="fcl-box-inner" style="cursor:pointer;" <?php if($agreement_signed == 'no' && ($agreement_data->created_by == $this->session->userdata('fe_user')) ){ ?>onClick="linkToProjectDetail('<?php echo $agreement_data->project_sk;?>');"<?php }else{ ?>onClick="linkToProjectExecution('<?php echo $agreement_data->project_sk;?>');"<?php } ?>  >
                                        <div class="fcl-text"><?php echo $agreement_data->project_name;?></div>
                                        <div class="fcl-note d-flex align-items-center justify-content-between">
                                            <div class="fcl-note-box">
                                                <div class="fcl-desc">$<?php echo number_format($total_agreement_payment,2);?> <?php if($no_of_milestone_block > 0){ ?>over <?php echo $no_of_milestone_block;?> <?php if($no_of_milestone_block > 1){ ?>milestones<?php }else{ ?>milestone<?php } ?><?php } ?></div>
                                                <span class="fcl-date"><?php if($agreement_start_date != '' && $agreement_end_date != ''){ ?><?php echo $agreement_start_date;?> - <?php echo $agreement_end_date;?><?php }else{ ?>&nbsp;<?php } ?></span>
                                            </div>
                                            <span  data-attr="<?php echo $agreement_data->project_sk;?>" class="download-button"><img data-attr="<?php echo $agreement_data->project_sk;?>" src="<?php echo $this->config->item("site_url");?>assets/images/download-icon.svg" alt=""></span>
                                                <!-- download popup start -->
                                                <div class="download-popup" id="popup_<?php echo $agreement_data->project_sk;?>">
                                                  <div class="popup-desc">Read the <span> General Terms and Conditions </span> before downloading this agreement. </div>
                                                  <div class="popup-btn">
                                                    <a data-attr="<?php echo $agreement_data->project_sk;?>" href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $agreement_data->project_sk;?>"  class="download-new-button btn blue-btn btn-block">Download</a>
                                                  </div>
                                                </div>
                                              <!-- download popup end -->
                                        </div>
                                        <?php echo $this->general->display_agreement_status($agreement_data->project_sk);?>
                                    </div>                
                                    <?php if(strtolower($user_login_mode) == 'agency'){ ?>                                                        
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                            <div class="dropdown-menu" style="">
                                                <?php if($agreement_signed == 'no' && ($agreement_data->created_by == $this->session->userdata('fe_user')) ){ ?>    
                                                    <a class="dropdown-item" href="<?php echo $this->config->item("site_url");?>project-detail/<?php echo $agreement_data->project_sk;?>" >Edit</a>
                                                <?php } ?>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="fn_delete_project('<?php echo $agreement_data->project_sk;?>')" >Delete</a>                                            
                                            </div>
                                        </div>
                                    <?php } ?>                                                                            
                                </div>
                            </li> 
                        <?php } ?>                            
                    </ul>    
                <?php } ?>                    
            </div>
        <?php } ?>           
    </div>
</div>
<?php }else{ ?>
    <div class="norecord-icon-border add-margin-top-20 add-margin-bottom-20" role="alert">
        <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
        <div class="alert-text">Not Available.</div>
    </div>
<?php } ?>