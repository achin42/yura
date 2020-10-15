<!DOCTYPE html>
<html>
<head>
<meta name='robots' content='noindex' />
<title><?php echo $pagetitle; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php $this->load->view('admin/include/head'); ?>
</head>
<body class="kt-sweetalert2--nopadding kt-header-mobile--fixed kt-page-content-white kt-subheader--fixed kt-subheader--enabled kt-offcanvas-panel--left kt-aside--left kt-page--loading" >
<?php $this->load->view('admin/include/header');?>
	 <!-- begin:: Root -->
        <div class="kt-grid kt-grid--hor kt-grid--root">
            <!-- begin:: Page -->
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                <!-- begin:: Aside -->
                <button class="kt-aside-close kt-hidden " id="kt_aside_close_btn"><i class="la la-close"></i></button>
                <div class="kt-aside kt-grid__item kt-grid kt-grid--ver" id="kt_aside">
                    <!-- begin::Aside Primary -->
					<?php $this->load->view('admin/include/left-menu');?>
                    <!-- end::Aside Primary -->
                </div>
                <!-- end:: Aside -->
                <!-- begin:: Wrapper -->
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
                        <!-- begin:: Subheader -->
                        <div class="kt-subheader kt-grid__item" id="kt_subheader">
                            <div class="kt-subheader__main">
                                <h3 class="kt-subheader__title">Users</h3>
                                <span class="kt-subheader__separator kt-subheader__separator--v"></span> 
                                <div class="kt-subheader__breadcrumbs">
									<a href="#" class="kt-subheader__breadcrumbs-link">Home</a>
									<span class="kt-subheader__breadcrumbs-separator"></span>
									<a href="" class="kt-subheader__breadcrumbs-link">Users </a>
								</div>
                            </div>
							<div class="kt-subheader__toolbar">
								<div class="kt-subheader__wrapper">
                                    <div class="kt-subheader__wrapper">
    									
    								</div>
								</div>
							</div>
                        </div>
                        <!-- end:: Subheader -->
                        <!-- begin:: Content -->
                        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
                            <div class="kt-portlet kt-portlet--mobile">
                                <form class="" role="form" method="get" name="frm_users_list" id="frm_users_list">
								<input type="hidden" value="" name="admin_users_sk" />
                                <input type="hidden" value="<?php echo $search_users_sk;?>" name="search_users_sk" id="search_users_sk" />
                                <input type="hidden" name="filter_agency1" id="filter_agency" value="<?php echo $filter_agency; ?>" />
								<input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by; ?>" />
								<input type="hidden" name="sort_order" id="sort_order" value="<?php echo $sort_order; ?>" />
									<div class="kt-portlet__body">
										<div class="kt-form kt-fork--label-right kt-margin-t-20 kt-margin-b-10">
											<div class="row align-items-center">
												<div class="col-xl-12 order-2 order-xl-1">
													<div class="row align-items-center">
														<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
															<div class="kt-input-icon kt-input-icon--right">
                                                                <div class="input-group">
																    <input type="text" class="form-control listing-search-text-box-color" placeholder="Search" id="search_users_keyword" name="search_users_keyword" >
																    <div class="input-group-append listing-search-icon">
                                                                        <span class="input-group-text" id="basic-addon2">
                                                                            <i class="listing-search-icon-color flaticon2-search-1" onClick="fun_search_users();" ></i>
                                                                        </span>
                                                                    </div>
                                                                </div>    
															</div>
														</div>
                                                        <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
															<div class="kt-input-icon kt-input-icon--right">
                                                                <div class="form-group">
																	<label class="text-left">&nbsp;</label>
																	<div class="kt-radio-inline">
																		<label class="kt-radio">
																			<input type="radio" onClick="fn_filter_agency(this.value)" name="filter_agency" value="all" <?php if($filter_agency == 'all'){?>checked="checked"<?php }?>> All
																			<span></span>
																		</label>
																		<label class="kt-radio">
																			<input type="radio" onClick="fn_filter_agency(this.value)" name="filter_agency" value="auto_approved" <?php if($filter_agency == 'auto_approved'){?>checked="checked"<?php }?>>Auto Approved
																			<span></span>
																		</label>
                                                                        <label class="kt-radio">
																			<input type="radio" onClick="fn_filter_agency(this.value)" name="filter_agency" value="manually_approved" <?php if($filter_agency == 'manually_approved'){?>checked="checked"<?php }?>>Manually Approved
																			<span></span>
																		</label>
																		<label class="kt-radio">
																			<input type="radio" onClick="fn_filter_agency(this.value)" name="filter_agency" value="rejected" <?php if($filter_agency == 'rejected'){?>checked="checked"<?php }?>> Rejected
																			<span></span>
																		</label>
                                                                        <label class="kt-radio">
																			<input type="radio" onClick="fn_filter_agency(this.value)" name="filter_agency" value="pending" <?php if($filter_agency == 'pending'){?>checked="checked"<?php }?>> Pending
																			<span></span>
																		</label>
																	</div>
																</div>    
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="loading" style="display: none;"><div id="preloader-inner"></div></div>
										<div id="users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
										   <input name="page_limit" id="page_limit" value="<?php echo $page_limit; ?>" type="hidden">
										</div>
									</div>
								</form>
                            </div>
                        </div>
                        <!-- end:: Content -->
                    </div>
                    <!-- begin:: Footer -->
                   <?php // $this->load->view('admin/include/footer');?>
                    <!-- end:: Footer -->
                </div>
                <!-- end:: Wrapper -->
            </div>
            <!-- end:: Page -->
        </div>
        <!-- end:: Root -->
        <!-- end:: Page -->
        
        <div id="multiple_side_div_panel" class="open_side_div">
            <div class="side_div_panel_head">
                <h3 id="multiple_side_div_panel_title" class="side_div_panel_title"></h3>
                <a href="javascript:void(0);" class="side_div_panel_close" onClick="closeMultipleSideDivPanel()"><i class="flaticon2-delete"></i></a>
            </div>
            <div class="side_div_panel_body">
                <div class="side_div_panel_wrapper">
                    <hr />
                    <div id="multiple_side_div_panel_wrapper_html" class="kt-scroll side_div_panel_body_scroll" data-scroll="true"></div>
                </div>                                            
            </div>                
        </div>
        <div class="multiple_open_side_div_overlay"></div>
        
        <?php $this->load->view('admin/include/left_flayer');?>
        <?php $this->load->view('admin/include/footer-js');?>
        <?php $this->load->view('admin/include/pages_js/users-js');?>
	</body>
</html>
