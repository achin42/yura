<?php
$logged_in_users_sk = $this->session->userdata('fe_user');
$user_login_mode = $this->session->userdata('fe_user_login_mode');
$company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
$company_rows = $company_result['rows']; 
$company_data = $company_result['data'];

$logged_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
$logged_user_rows = $logged_user_result['rows']; 
$logged_user_data = $logged_user_result['data'];
$logged_user_type = $logged_user_data[0]->user_type;

?>
<ul class="pm-list d-flex align-items-center">
    <li <?php if($page_name == 'profile_detail'){ ?>class="active"<?php } ?> ><a href="<?php echo $this->config->item("site_url");?>profile-detail"><span>MY DETAILS</span></a></li>
    <?php if($user_login_mode == 'AGENCY'){ ?>
        <li <?php if($page_name == 'agency_company_detail'){ ?>class="active"<?php } ?> ><a href="<?php echo $this->config->item("site_url");?>agency-company-detail"><span>COMPANY DETAILS</span></a></li>
    <?php }else{ ?>
        <?php if($logged_user_type == 'agency' || $logged_user_type == 'both'){ ?>
            <li <?php if($page_name == 'agency_company_detail'){ ?>class="active"<?php } ?> ><a href="<?php echo $this->config->item("site_url");?>agency-company-detail"><span>COMPANY DETAILS</span></a></li>            
        <?php }else{ ?>
            <li <?php if($page_name == 'client_company_detail' || $page_name == 'client_become_agency_detail'){ ?>class="active"<?php } ?> ><a href="<?php echo $this->config->item("site_url");?>client-company-detail"><span>COMPANY DETAILS</span></a></li>
        <?php } ?>            
    <?php } ?>    
    <li><a href="#"><span>AGREEMENT DEFAULTS</span></a></li>
    <!--li><a href="#"><span>TRANSACTIONS</span></a></li-->
</ul>