<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'welcome';
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['activate.html'] = 'login/fn_activate_user';
$route['dashboard'] = 'user/fn_get_dashboard';
$route['edit-profile'] = 'user/fn_get_user_profile';
$route['cluster-list'] = 'project/fn_get_cluster_list';
$route['project-detail/(:any)'] = 'project/fn_get_project_detail/$1';
$route['project-execution/(:any)'] = 'project/fn_get_project_detail_for_execution/$1';
$route['payment-method'] = 'user/fn_get_payment_method';
$route['bank-account'] = 'user/fn_get_bank_account';
$route['transactions'] = 'user/fn_get_transaction';
$route['download-agreement/(:any)'] = 'project/fn_get_download_agreement/$1';
$route['download-agreement/(:any)/(:any)'] = 'project/fn_get_download_agreement/$1/$2';
$route['first-signer-signed-agreement/(:any)/(:any)'] = 'agreement_signed/fn_set_first_signer_signed_agreement/$1/$2';
$route['first-signer-declined-agreement/(:any)/(:any)'] = 'agreement_signed/fn_set_first_signer_declined_agreement/$1/$2';
$route['second-signer-signed-agreement/(:any)/(:any)'] = 'agreement_signed/fn_set_second_signer_signed_agreement/$1/$2';
$route['second-signer-declined-agreement/(:any)/(:any)'] = 'agreement_signed/fn_set_second_signer_declined_agreement/$1/$2';
$route['sign-up'] = 'login/fn_get_signup';
$route['profile-setup'] = 'user/fn_get_profile_setup';
$route['agency-company-setup'] = 'user/fn_get_agency_company_setup';
$route['client-company-setup'] = 'user/fn_get_client_company_setup';
$route['profile-detail'] = 'user/fn_get_profile_detail';
$route['agency-company-detail'] = 'user/fn_get_agency_company_detail';
$route['agency-company-detail/verify'] = 'user/fn_get_agency_company_detail';
$route['client-company-detail'] = 'user/fn_get_client_company_detail';
$route['client-become-agency'] = 'user/fn_get_client_become_agency_detail';
$route['delete-user'] = 'login/fn_delete_user';
$route['invited-activation.html'] = 'login/fn_invited_activate_user';
$route['change-company-name'] = 'login/fn_change_company_name';
$route['check-php'] = 'login/fn_check_php_setting';
$route['admin'] = 'admin/fn_get_login';
$route['admin/users'] = 'admin/fn_get_users_list';