<?php
$sess_admin_users_sk = $this->session->userdata('fe_admin_user');
$logged_in_user_result = $this->admin_users_model->fn_get_admin_user_detail($sess_admin_users_sk);
$data['logged_in_user_rows'] = $logged_in_user_rows = $logged_in_user_result['rows'];
$data['logged_in_user_data'] = $logged_in_user_data = $logged_in_user_result['data'];
/*echo "<pre>";
print_r($logged_in_user_data);exit;*/
$default_user_image = $this->config->item("site_url").'assets/admin/images/default-user.jpg';     
/*$user_profile_image = $logged_in_user_data[0]->profile_image;                                    
$user_profile_image_path = $this->config->item('UPLOAD_DIR').'/user_images/'.$user_profile_image;
$user_profile_image_url = $this->config->item('UPLOAD_URL').'/user_images/'.$user_profile_image;
$display_user_profile_image = '';
if(file_exists($user_profile_image_path) && $user_profile_image != ''){
    $default_user_image = $user_profile_image_url;    
}else{
    $default_user_image = $default_user_image;
}*/
        
?>
<div class="kt-aside__primary">
    <!-- begin::Aside Top -->
    <div class="kt-aside__top">
        <a class="kt-aside__brand expand-nav-icon" href="javascript:void(0)">
            <img alt="Logo" src="<?php echo $this->config->item("site_url");?>assets/admin/media/logos/yura_w_logo.svg"/>
        </a>
    </div>
    <!-- end:: Aside Top -->
    <!-- begin::Aside Middle -->
    <div class="kt-aside__middle">
        <ul class="kt-aside__nav">
            <li class="kt-aside__nav-item">
                <a title="Users" class="kt-aside__nav-link <?php if($viewname == 'users'){ ?>active<?php } ?>" href="<?php echo $this->config->item("site_url");?>admin/users/"> <i class="flaticon-users"></i> <b>Users</b> </a>
            </li>
        </ul>
    </div>
    <!-- end::Aside Middle -->
    <!-- begin::Aside Bottom -->
    <div class="kt-aside__middle" style="position: absolute;bottom: 0;">
        <ul class="kt-aside__nav">
            <li class="kt-aside__nav-item">
                <a title="Logout" class="kt-aside__nav-link logout-left" href="<?php echo $this->config->item("site_url");?>admin/admin_logout/"><i class="flaticon-logout"></i> <b>Logout</b> </a>
	        </li>
        </ul>
    </div>
    <!-- end::Aside Bottom -->
</div>