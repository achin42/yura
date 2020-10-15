<!-- begin::Page loader -->
<!-- end::Page Loader -->
<!-- begin:: Page -->
<!-- begin:: Header Mobile -->
<?php
$sess_admin_users_sk = $this->session->userdata('fe_admin_user');
if(empty($sess_admin_users_sk))
{
	redirect($this->config->item('site_url').'admin/');
	exit;
}
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
<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed " >
	<div class="kt-header-mobile__logo">
		<a href="<?php echo $this->config->item("site_url");?>admin/users/">
			<img alt="Logo" src="<?php echo $this->config->item("site_url");?>assets/admin/media/logos/yura_w_logo.svg"/>
		</a>
	</div>
	<div class="kt-header-mobile__toolbar">
		<a href="#" class="kt-aside__nav-link" data-toggle="dropdown" data-offset="100px, -50px" aria-expanded="true">
			<i class="flaticon2-hourglass-1 kt-hidden"></i>
			<a href="<?php echo $this->config->item("site_url");?>admin/admin_logout/" title="Sign Out"><i class="flaticon-users"></i>Sign Out</a>
			<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
			<span class="kt-aside__nav-username kt-bg-brand kt-hidden">Y</span>
		</a>

		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-anim dropdown-menu-sm">
			<ul class="kt-nav kt-margin-b-10">
				<li class="kt-nav__item">
					<a href="javascript:void(0)" onclick="openAddUpdateUserEditProfileSideDivPanel('40','<?php echo $sess_admin_users_sk;?>');" class="kt-nav__link">
						<span class="kt-nav__link-icon"><i class="flaticon-edit-1"></i></span>
						<span class="kt-nav__link-text">Edit Profile</span>
					</a>
				</li>
				<!--li class="kt-nav__separator kt-nav__separator--fit"></li-->
				<li class="kt-nav__custom kt-space-between">
					<a href="<?php echo base_url();?>projects/admin_logout/" class="btn btn-label-brand btn-upper btn-sm btn-bold">Sign Out</a>
				</li>
			</ul>
		</div>
		<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
	</div>
</div>
<!-- end:: Header Mobile -->