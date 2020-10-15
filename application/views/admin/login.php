<?php 
if(!empty($this->session->userdata('fe_admin_user')))
{
	redirect($this->config->item('site_url').'admin/users');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name='robots' content='noindex' />
<title><?php echo $page_title;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<script type="text/javascript">
WebFont.load({google: {"families":["Poppins:300,400,500,600,700"]},active: function() {sessionStorage.fonts = true;}});
</script>
<!--end::Fonts -->
<link href="<?php echo $this->config->item("site_url");?>assets/admin/app/custom/user/login-v2.demo6.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/demo/demo6/base/style.bundle.css" rel="stylesheet" type="text/css" />
<!--end::Global Theme Styles -->
<link rel="shortcut icon" href="<?php echo $this->config->item("site_url");?>assets/admin/media/logos/favicon.png" />     
<link href="<?php echo $this->config->item("site_url");?>assets/admin/css/login.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/vendors/custom/vendors/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->config->item("site_url");?>assets/admin/css/developer.css?<?php echo date('His');?>" rel="stylesheet" type="text/css" />       
</head>
<body class="kt-login-v2--enabled kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading" >
	
	<!-- begin:: Page -->
	<div class="kt-grid kt-grid--ver kt-grid--root">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">
			<!--begin::Item-->
			<div class="kt-grid__item kt-grid--hor">
				<!--begin::Heade-->
				<div class="kt-login-v2__head">
					<div class="kt-login-v2__logo">
						<a href="#"><img src="<?php echo $this->config->item("site_url");?>assets/admin/media/logos/yuraio.svg" alt="" /></a>
					</div>
				</div>
				<!--begin::Head-->
			</div>
			<!--end::Item-->
			<!--begin::Item-->
			<div class="kt-grid__item kt-grid kt-grid--ver kt-grid__item--fluid">
				<!--begin::Body-->
				<div class="kt-login-v2__body">
					<!--begin::Wrapper-->
					<div class="kt-login-v2__wrapper">
						<div class="kt-login-v2__container">
							<div class="kt-login-v2__title">
								<h1>Sign In</h1>
							</div>
							<!--begin::Form-->
							<form id="frm_admin_login" class="kt-login-v2__form kt-form" data-toggle="validator" novalidate="true" action="<?php echo $this->config->item("site_url");?>admin/fn_check_login_process" method="post" autocomplete="off">
								<div class="form-group">
									<input class="form-control" type="email" placeholder="Email" name="sign_in_email" id="sign_in_email" autocomplete="off" required>
								</div>
								<div class="form-group">
									<input class="form-control" type="password" placeholder="Password" name="password" id="password" autocomplete="off" required>
								</div>
								<!--begin::Action-->
								<div class="kt-login-v2__actions">
									<a href="#" class="kt-link kt-link--brand hide-visibility"> Forgot Password ? </a>
									<button type="button" id="btn_admin_sign_in" class="btn btn-brand btn-elevate btn-pill1 pull-right btn-border-and-padding" onclick="fn_submit_login();" >Sign In</button>
                                    <button type="button" id="wait_btn_admin_sign_in" class="btn btn-brand btn-elevate btn-pill1 pull-right btn-border-and-padding display-hide disabled" disabled>Please wait...</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
						</div>
					</div>
					<!--end::Wrapper-->
					<!--begin::Image-->
					<div class="kt-login-v2__image">
						<img src="<?php echo $this->config->item("site_url");?>assets/admin/media/misc/illustration.png" alt="" style="position: absolute;bottom: 0;">
					</div>
					<!--begin::Image-->
				</div>
				<!--begin::Body-->
			</div>
			<!--end::Item-->
		</div>
	</div>
	<!-- end:: Page -->
	<?php $this->load->view('admin/include/footer-js');?>    
</body>
</html>
