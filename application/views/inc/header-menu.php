<?php
$logged_in_users_sk = $this->session->userdata('fe_user');
$user_login_mode = $this->session->userdata('fe_user_login_mode');
$user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
$user_rows = $user_result['rows']; 
$user_data = $user_result['data'];
if($user_rows == 0){
    redirect($this->config->item('site_url'));
    exit;    
}
if($user_login_mode == 'AGENCY'){
    $user_login_mode_off = 'CLIENT';
}else{
    $user_login_mode_off = 'AGENCY';
}
if($page_name == 'project_detail' || $page_name == 'project_execution'){
    $project_sk = $agreement_data[0]->project_sk;
    
    $agreement_start_date = '';
    $agreement_end_date = '';
    $total_agreement_payment = '0.00';
    $no_of_milestone_block = '0';    
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
}

$logged_in_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
$logged_in_user_rows = $logged_in_user_result['rows']; 
$logged_in_user_data = $logged_in_user_result['data'];

$logged_in_company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
$logged_in_company_rows = $logged_in_company_result['rows']; 
$logged_in_company_data = $logged_in_company_result['data'];

$logged_in_user_type = $logged_in_user_data[0]->user_type;
$logged_in_is_agency_verified = $logged_in_company_data[0]->is_agency_verified;
$logged_in_verify_later = $logged_in_company_data[0]->verify_later;
$logged_in_applied_for_manual_verification = $logged_in_company_data[0]->applied_for_manual_verification;

?>
<?php if($page_name == 'dashboard' || $page_name == 'cluster_list' || $page_name == 'edit_profile' || $page_name == 'payment_methods' || $page_name == 'bank_accounts' || $page_name == 'transactions' || $page_name == 'profile_detail' || $page_name == 'agency_company_detail' || $page_name == 'client_company_detail' || $page_name == 'client_become_agency_detail'){ ?>
<div class="top-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 d-flex flex-wrap align-items-center">
                <div class="right-bar d-flex">
                    <a href="<?php echo $this->config->item("site_url");?>" class="navbar-brand"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></a>
                    <div class="navbar-box d-flex align-items-center">
                        <span class="v-line"></span>
                        <?php if($page_name == 'dashboard' || $page_name == 'cluster_list'){ ?>
                            <ul class="navbar-list d-flex  align-items-center">                                    
                                <li><a href="<?php echo $this->config->item("site_url");?>">Dashboard</a></li>     
                                <li class="height-for-dashboard-popup-instruction">
                                    <div class="switch-toggle-container d-flex align-items-center justify-content-between">
                                          <a href="javascript:void(0)" class="st-link login_mode <?php if(strtolower($user_login_mode) == 'agency'){?>active<?php } ?>">AGENCY</a>
                                          <a href="javascript:void(0)" class="st-link login_mode <?php if(strtolower($user_login_mode) == 'client'){?>active<?php } ?>">CLIENT</a>
                                    </div>                                    
                                    <?php if($display_dashboard_instruction == '' || $display_dashboard_instruction == 'yes'){ ?>  
                                        <div class="switch-popup-box">
                                            <div class="sp-img">
                                                <img src="<?php echo $this->config->item("site_url");?>assets/images/marketing-agency.png" alt="" />
                                                <img src="<?php echo $this->config->item("site_url");?>assets/images/interactive-solutions.svg" alt="" />
                                            </div>
                                            <div class="sp-content">
                                                <h4>Single login for Agency and Client modes</h4>
                                                <p>We understand that you need to act as both an Agency and a Clients, so rather than having two separate logins, we save you all that hassle by providing this awesome switch available at the top of the dashboard.</p>
                                            </div>
                                            <div class="sp-btn"><a href="#" class="link-btn" id="close">Got it!</a></div>
                                        </div>
                                    <?php } ?>
                                    <div class="switch-popup-box display-hide" id="company_verification_popup">
                                        <div class="sp-img">
                                            <img src="<?php echo $this->config->item("site_url");?>assets/images/marketing-agency.svg" alt="" />
                                        </div>
                                        <div class="sp-content">
                                            <h4>Company verification is required for Agencies</h4>
                                            <p>To use Yura as an Agency, you must provide and verify your company. This process helps us maintain authenticity of the platform and improves the experience of everyone here.</p>
                                        </div>
                                        <div class="sp-btn d-flex align-items-center justify-content-end">
                                            <a href="javascript:void(0)" id="hide_company_verification_popup" class="btn-link2" id="close">Cancel</a>
                                            <a href="<?php echo $this->config->item("site_url");?>client-become-agency" class="btn blue-btn">Verify company details</a>
                                        </div>
                                    </div>                                            
                                </li>
                            </ul>
                        <?php }elseif($page_name == 'edit_profile' || $page_name == 'payment_methods' || $page_name == 'bank_accounts' || $page_name == 'transactions' || $page_name == 'profile_detail' || $page_name == 'agency_company_detail' || $page_name == 'client_company_detail' || $page_name == 'client_become_agency_detail'){ ?>
                            <ul class="navbar-list d-flex">
                                <li><a href="#">Profile</a></li>
                            </ul>
                        <?php } ?>                            
                    </div>
                </div>
                <div class="left-bar ml-auto">
                    <!-- <div class="navbar"> -->
                    <ul class="new-navbar d-flex align-items-center">
                        <?php if($page_name == 'dashboard' || $page_name == 'cluster_list'){ ?>
                            <li><a class="new-navbar-link" href="<?php echo $this->config->item("site_url");?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/grid-icon2.svg" class="light-img" alt="" /><span>Dashboard</span></a></li>
                        <?php }else{ ?>
                            <li><a class="new-navbar-link" href="<?php echo $this->config->item("site_url");?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/grid-icon.svg" class="dark-img" alt="" /><span>Dashboard</span></a></li>
                        <?php } ?>                            
                        <li><a class="new-navbar-link" href="javascript:void(0)"><img src="<?php echo $this->config->item("site_url");?>assets/images/notification-icon.svg" alt="" /><span>Notifications</span></a></li>
                        <li>
                            <div class="custom-propover">
                                <a href="<?php echo $this->config->item("site_url");?>profile-detail" class="new-navbar-link">
                                    <div class="new-member-profile">
                                        <?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                                			<img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" />
                                		<?php }else{ ?>
                                			<img src="<?php echo $this->config->item("site_url");?>assets/images/user-80.jpg" alt="" />
                                		<?php } ?>                                            
                                    </div>
                                    <span>Profile</span>
                                </a>
                                <?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>
                                    <div>  
                                    <?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>  
                            			<?php if($logged_in_is_agency_verified == '0'){ ?>
                            				<?php if($logged_in_verify_later == '1'){ ?>
                            					<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            				<?php } ?>
                            				<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                            					<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-pending-icon.svg" alt="" /></div>
                            				<?php } ?>                                                
                            			<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                            				<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            			<?php } ?>                                                
                            		<?php } ?>
                          			<?php if($logged_in_is_agency_verified == '0'){ ?>
                          				<?php if($logged_in_verify_later == '1'){ ?>
                          					<div class="custom-propover-content final-state">
                                                <div class="cpc-body">
                                                    <h5>Company details not verified</h5>
                                                    <p>You need to verify your company details to be able to sign an agreement.</p>
                                                </div>             
                                                <form>                                 
                                                    <div class="tb-action-box d-flex align-items-center justify-content-end">
                                                        <a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                                                        <a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                                                    </div>
                                                </form>
                                            </div>
                          				<?php } ?>
                          				<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                          					<div class="custom-propover-content final-state profile-pending">
                                                <div class="cpc-body">
                                                    <h5>Awaiting company verification</h5>
                                                    <p>You have applied for us manually verify your company details. The process may take up to 24 hours.</p>
                                                </div>             
                                                <form>                                 
                                                    <div class="tb-action-box d-flex align-items-center justify-content-end">
                                                        <a href="javascript:void(0)" class="blue-btn sign-in-later" >Okay</a>
                                                    </div>
                                                </form>
                                            </div>
                          				<?php } ?>                                                
                          			<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                          				<div class="custom-propover-content final-state">
                                            <div class="cpc-body">
                                                <h5>Company details not verified</h5>
                                                <p>You need to verify your company details to be able to sign an agreement.</p>
                                            </div>             
                                            <form>                                 
                                                <div class="tb-action-box d-flex align-items-center justify-content-end">
                                                    <a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                                                    <a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                                                </div>
                                            </form>
                                        </div>
                          			<?php } ?>
                                    </div>                                                                                          
                          		<?php } ?>                                    
                            </div>
                        </li>
                    </ul>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php }elseif($page_name == 'project_detail'){ ?>
<div class="top-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="tb-header-box d-flex align-items-center">
                    <a href="<?php echo $this->config->item("site_url");?>" class="navbar-brand"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></a>
                    <div class="tb-header-content d-flex align-items-center">
                        <span class="v-line v-small-line"></span>
                        <div class="rb-desc">
                            <h5><?php echo $agreement_data[0]->project_name;?></h5>
                            <p>$<?php echo number_format($total_agreement_payment,2);?> <?php if($no_of_milestone_block > 0){ ?>over <?php echo $no_of_milestone_block;?> <?php if($no_of_milestone_block > 1){ ?>milestones<?php }else{ ?>milestone<?php } ?><?php } ?>
                				<?php if($agreement_start_date != '' && $agreement_end_date != ''){ ?><br><?php echo $agreement_start_date;?> - <?php echo $agreement_end_date;?><?php } ?>
                			</p>
                        </div>
                        <span class="v-line v-small-line"></span> 
                        <div class="lb-intro d-flex align-items-center">
                            <span class="lb-icon">
                				<?php $full_file_path = $this->config->item("UPLOAD_DIR").'cluster_image/'.$project_data[0]->cluster_image; if($project_data[0]->cluster_image != '' && file_exists($full_file_path) ) {?>
                					<img style="width:26px;" src="<?php echo $this->config->item("UPLOAD_URL");?>cluster_image/<?php echo $project_data[0]->cluster_image; ?>" alt="" />
                				<?php }else{ ?>
                					<img style="width:26px;" src="<?php echo $this->config->item("site_url");?>assets/images/default-project.svg" alt="" />
                				<?php } ?>
                			</span>
                			<div class="lb-content">
                				<strong><?php echo $project_data[0]->cluster_title;?></strong>
                				<div><?php echo $company_data[0]->company_name;?>  /  <?php echo $project_data[0]->contact_person_name;?></div>
                			</div>
                        </div> 
                    </div>    
                    <div class="tb-header-right ml-auto">
                        <ul class="new-navbar d-flex align-items-center">
                            <li><a class="new-navbar-link" href="<?php echo $this->config->item("site_url");?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/grid-icon.svg" class="dark-img" alt="" /><span>Dashboard</span></a></li>
                            <li><a class="new-navbar-link" href="#"><img src="<?php echo $this->config->item("site_url");?>assets/images/notification-icon.svg" alt="" /><span>Notifications</span></a></li>
                            <li>
                            	<div class="custom-propover">
                            		<a href="<?php echo $this->config->item("site_url");?>profile-detail" class="new-navbar-link">
                            			<div class="new-member-profile">
                            				<?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                            					<img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" />
                            				<?php }else{ ?>
                            					<img src="<?php echo $this->config->item("site_url");?>assets/images/user-80.jpg" alt="" />
                            				<?php } ?>                                            
                            			</div>
                            			<span>Profile</span>
                            		</a>
                            		<?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>
                            			<div>  
                            			<?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>  
                            				<?php if($logged_in_is_agency_verified == '0'){ ?>
                            					<?php if($logged_in_verify_later == '1'){ ?>
                            						<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            					<?php } ?>
                            					<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                            						<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-pending-icon.svg" alt="" /></div>
                            					<?php } ?>                                                
                            				<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                            					<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            				<?php } ?>                                                
                            			<?php } ?>
                            			<?php if($logged_in_is_agency_verified == '0'){ ?>
                            				<?php if($logged_in_verify_later == '1'){ ?>
                            					<div class="custom-propover-content final-state">
                            						<div class="cpc-body">
                            							<h5>Company details not verified</h5>
                            							<p>You need to verify your company details to be able to sign an agreement.</p>
                            						</div>             
                            						<form>                                 
                            							<div class="tb-action-box d-flex align-items-center justify-content-end">
                            								<a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                            								<a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                            							</div>
                            						</form>
                            					</div>
                            				<?php } ?>
                            				<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                            					<div class="custom-propover-content final-state profile-pending">
                            						<div class="cpc-body">
                            							<h5>Awaiting company verification</h5>
                            							<p>You have applied for us manually verify your company details. The process may take up to 24 hours.</p>
                            						</div>             
                            						<form>                                 
                            							<div class="tb-action-box d-flex align-items-center justify-content-end">
                            								<a href="javascript:void(0)" class="blue-btn sign-in-later" >Okay</a>
                            							</div>
                            						</form>
                            					</div>
                            				<?php } ?>                                                
                            			<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                            				<div class="custom-propover-content final-state">
                            					<div class="cpc-body">
                            						<h5>Company details not verified</h5>
                            						<p>You need to verify your company details to be able to sign an agreement.</p>
                            					</div>             
                            					<form>                                 
                            						<div class="tb-action-box d-flex align-items-center justify-content-end">
                            							<a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                            							<a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                            						</div>
                            					</form>
                            				</div>
                            			<?php } ?>
                            			</div>                                                                                          
                            		<?php } ?>                                    
                            	</div>
                            </li>
                        </ul>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }elseif($page_name == 'project_execution'){ ?>     
<div class="top-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="tb-header-box d-flex align-items-center">
                    <a href="<?php echo $this->config->item("site_url");?>" class="navbar-brand"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></a>
                    <div class="tb-header-content d-flex align-items-center">
                        <span class="v-line v-small-line"></span>
                        <div class="rb-desc">
                            <h5><?php echo $agreement_data[0]->project_name;?></h5>
                            <p>$<?php echo number_format($total_agreement_payment,2);?> <?php if($no_of_milestone_block > 0){ ?>over <?php echo $no_of_milestone_block;?> <?php if($no_of_milestone_block > 1){ ?>milestones<?php }else{ ?>milestone<?php } ?><?php } ?>
                				<?php if($agreement_start_date != '' && $agreement_end_date != ''){ ?><br><?php echo $agreement_start_date;?> - <?php echo $agreement_end_date;?><?php } ?>
                			</p>
                        </div>
                        <span class="v-line v-small-line"></span> 
                        <div class="lb-intro d-flex align-items-center">
                            <span class="lb-icon">
                				<?php $full_file_path = $this->config->item("UPLOAD_DIR").'cluster_image/'.$project_data[0]->cluster_image; if($project_data[0]->cluster_image != '' && file_exists($full_file_path) ) {?>
                					<img style="width:26px;" src="<?php echo $this->config->item("UPLOAD_URL");?>cluster_image/<?php echo $project_data[0]->cluster_image; ?>" alt="" />
                				<?php }else{ ?>
                					<img style="width:26px;" src="<?php echo $this->config->item("site_url");?>assets/images/default-project.svg" alt="" />
                				<?php } ?>
                			</span>
                			<div class="lb-content">
                				<strong><?php echo $project_data[0]->cluster_title;?></strong>
                				<div><?php echo $company_data[0]->company_name;?>  /  <?php echo $project_data[0]->contact_person_name;?></div>
                			</div>
                        </div> 
                    </div>    
                    <div class="tb-header-right ml-auto">
                        <ul class="new-navbar d-flex align-items-center">
                            <li><a class="new-navbar-link" href="<?php echo $this->config->item("site_url");?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/grid-icon.svg" class="dark-img" alt="" /><span>Dashboard</span></a></li>
                            <li><a class="new-navbar-link" href="#"><img src="<?php echo $this->config->item("site_url");?>assets/images/notification-icon.svg" alt="" /><span>Notifications</span></a></li>
                            <li>
                            	<div class="custom-propover">
                            		<a href="<?php echo $this->config->item("site_url");?>profile-detail" class="new-navbar-link">
                            			<div class="new-member-profile">
                            				<?php $full_file_path = $this->config->item("UPLOAD_DIR").'user_image/'.$user_data[0]->user_image; if($user_data[0]->user_image != '' && file_exists($full_file_path) ) {?>
                            					<img src="<?php echo $this->config->item("UPLOAD_URL");?>user_image/<?php echo $user_data[0]->user_image; ?>" />
                            				<?php }else{ ?>
                            					<img src="<?php echo $this->config->item("site_url");?>assets/images/user-80.jpg" alt="" />
                            				<?php } ?>                                            
                            			</div>
                            			<span>Profile</span>
                            		</a>
                            		<?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>
                            			<div>  
                            			<?php if($logged_in_user_type == 'agency' || $logged_in_user_type == 'both'){ ?>  
                            				<?php if($logged_in_is_agency_verified == '0'){ ?>
                            					<?php if($logged_in_verify_later == '1'){ ?>
                            						<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            					<?php } ?>
                            					<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                            						<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-pending-icon.svg" alt="" /></div>
                            					<?php } ?>                                                
                            				<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                            					<div class="member-icon  member-alert"><img src="<?php echo $this->config->item("site_url");?>assets/images/profile-not-verified-icon.svg" alt="" /></div>
                            				<?php } ?>                                                
                            			<?php } ?>
                            			<?php if($logged_in_is_agency_verified == '0'){ ?>
                            				<?php if($logged_in_verify_later == '1'){ ?>
                            					<div class="custom-propover-content final-state">
                            						<div class="cpc-body">
                            							<h5>Company details not verified</h5>
                            							<p>You need to verify your company details to be able to sign an agreement.</p>
                            						</div>             
                            						<form>                                 
                            							<div class="tb-action-box d-flex align-items-center justify-content-end">
                            								<a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                            								<a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                            							</div>
                            						</form>
                            					</div>
                            				<?php } ?>
                            				<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                            					<div class="custom-propover-content final-state profile-pending">
                            						<div class="cpc-body">
                            							<h5>Awaiting company verification</h5>
                            							<p>You have applied for us manually verify your company details. The process may take up to 24 hours.</p>
                            						</div>             
                            						<form>                                 
                            							<div class="tb-action-box d-flex align-items-center justify-content-end">
                            								<a href="javascript:void(0)" class="blue-btn sign-in-later" >Okay</a>
                            							</div>
                            						</form>
                            					</div>
                            				<?php } ?>                                                
                            			<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                            				<div class="custom-propover-content final-state">
                            					<div class="cpc-body">
                            						<h5>Company details not verified</h5>
                            						<p>You need to verify your company details to be able to sign an agreement.</p>
                            					</div>             
                            					<form>                                 
                            						<div class="tb-action-box d-flex align-items-center justify-content-end">
                            							<a href="javascript:void(0)" class="btn-link sign-in-later" >I'll do it later</a>
                            							<a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a>
                            						</div>
                            					</form>
                            				</div>
                            			<?php } ?>
                            			</div>                                                                                          
                            		<?php } ?>                                    
                            	</div>
                            </li>
                        </ul>                        
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
<?php } ?>