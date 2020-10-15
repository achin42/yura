<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    
    function __construct() {
        parent::__construct();
		$this->load->model('users_model');
        $this->load->model('project_model');
        $this->load->library('upload');
        $this->load->library('email');
        $this->load->helper('cookie');
        $this->load->library('Signrequest');
        if(empty($this->session->userdata('fe_user'))){
			redirect($this->config->item('site_url'));
			exit;
		}
		header("Cache-Control: max-age=1800, must-revalidate");
    }
    
    function logout(){
		$this->session->sess_destroy();
		redirect($this->config->item('site_url'));
	}
    
    function fn_change_user_login_mode(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'CLIENT';
        }else{
            $user_login_mode_off = 'AGENCY';
        }
        $userdata = array('fe_user_login_mode'  => $user_login_mode_off);
        $this->session->set_userdata($userdata);
        echo "1^^1";exit;
    }
             
	function fn_get_dashboard(){
        $data['page_title'] = 'Dashboard';
        $data['page_name'] = 'dashboard';        
        $data['display_dashboard_instruction'] = $display_dashboard_instruction = get_cookie('dashboard_instruction');
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $first_name = '';
        $last_name = '';
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url').'profile-setup');
			exit;    
        }
        if($user_rows > 0){
            $first_name = $user_data[0]->first_name;
            $last_name = $user_data[0]->last_name;
        }
        if($user_rows > 0 && $first_name == '' || $last_name == ''){
            redirect($this->config->item('site_url').'profile-setup');
			exit;
        }
        
        $user_type = $user_data[0]->user_type;
        $verify_later = '0';
        $applied_for_manual_verification = '0';                
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            if($user_type == 'client'){
                redirect($this->config->item('site_url').'client-company-setup');
            }else{
                redirect($this->config->item('site_url').'agency-company-setup');
            }            
			exit;    
        }
        if($company_rows > 0){
            $verify_later = $company_data[0]->verify_later;
            $applied_for_manual_verification = $company_data[0]->applied_for_manual_verification;                
            if($user_type != 'client' && $verify_later == '0' && $applied_for_manual_verification == '0' ){
                redirect($this->config->item('site_url').'agency-company-setup');
                exit;
            }                
        }
        
        $filter_via_company = $this->input->post('filter_via_company');
        $project_result = $this->project_model->fn_get_all_clusters_list($user_login_mode,$filter_via_company);
        $project_rows = $project_result['rows']; 
        $q_project_data = $project_result['data'];
        
        if($project_rows > 0){
			redirect($this->config->item('site_url').'cluster-list/');
			exit;
		}
        
        $data['logged_in_user_type'] = $logged_in_user_type = $user_data[0]->user_type;
        $data['user_login_mode'] = $user_login_mode;
        $data['user_login_mode_off'] = $user_login_mode_off;
		$this->load->view('user/dashboard',$data);
	}
    
    function fn_set_dashboard_cookie(){
        $cookie= array( 'name'   => 'dashboard_instruction', 'value'  => 'no', 'expire' => time()+(3600*24*2) );
		$this->input->set_cookie($cookie);
    }        
    
    function fn_get_user_profile(){
        $data['page_title'] = 'Profile';
        $data['page_name'] = 'edit_profile';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $data['user_profile_image_url'] = '';
		$this->load->view('user/edit_profile',$data);
	}
    
    function fn_update_user_profile(){
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_email = $this->input->post('email'); 
            $user_first_name = $this->input->post('first_name');
            $users_sk = $this->input->post('users_sk'); 
            
            $user_result = $this->users_model->fn_check_user_email_exists($user_email,$users_sk);
            $user_rows = $user_result['rows']; 
            $user_data = $user_result['data'];
            if($user_rows > 0){
                echo $user_data[0]->users_sk.'^^2';exit; 
            }else{
                $result = $this->users_model->fn_update_user_profile();
                /* image upload start */
                $user_image = '';
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/user_image';
                if(!is_dir($create_dir_path)){
                    mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_FILES['user_image']['name'])){
                    $config['upload_path'] = $this->config->item('UPLOAD_DIR').'/user_image/';
        			$tmp = explode(".", $_FILES['user_image']['name']);
                    $ext = end($tmp);
        			$config['file_name'] = 'user_image_'.$this->general->generate_random_letters(4).'.'.$ext;
        			
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->set_allowed_types('*');
                    $data['upload_data'] = '';
                }
                //if not successful, set the error message
        		if ($this->upload->do_upload('user_image')) {
                    $upload_data = $this->upload->data();
                    $uploaded_file_name = $upload_data['file_name'];
                    $result = $this->users_model->fn_update_user_image($uploaded_file_name,$users_sk);
                }            
                /* image upload end */                
                echo $result.'^^1';exit;
            }    
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_update_company_profile(){
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('contact_person_name', 'Contact Person', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $company_name = $this->input->post('company_name'); 
            $contact_person_name = $this->input->post('contact_person_name');
            $users_sk = $this->input->post('users_sk'); 
            
            $result = $this->users_model->fn_update_company_profile();
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_FILES['company_image']['name'])){
                $config['upload_path'] = $this->config->item('UPLOAD_DIR').'/company_image/';
    			$tmp = explode(".", $_FILES['company_image']['name']);
                $ext = end($tmp);
    			$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;
    			
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $this->upload->set_allowed_types('*');
                $data['upload_data'] = '';
            }
            //if not successful, set the error message
    		if ($this->upload->do_upload('company_image')) {
                $upload_data = $this->upload->data();
                $uploaded_file_name = $upload_data['file_name'];
                $result = $this->users_model->fn_update_company_image($uploaded_file_name,$users_sk);
            }            
            /* image upload end */
            echo $result.'^^1';exit;
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    

    function fn_get_payment_method(){
        $data['page_title'] = 'Payment Methods';
        $data['page_name'] = 'payment_methods';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_payment_method_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $this->load->view('user/payment_methods',$data);
    }

    function fn_get_payment_method_ajax(){
        $data['page_title'] = 'Payment Methods';
        $data['page_name'] = 'payment_methods';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_payment_method_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        echo $this->load->view('user/payment_methods_ajax',$data,true);
        echo '^^1^^';
        echo $user_rows;
        exit;
    }        

    function fn_insert_payment_method() {
        $this->form_validation->set_rules('card_number', 'Card Number', 'required');
        $this->form_validation->set_rules('expiry_month', 'Expiry Month', 'required');
        $this->form_validation->set_rules('expiry_year', 'Expiry Year', 'required');
        $this->form_validation->set_rules('cvc', 'CVC', 'required');
        $this->form_validation->set_rules('name_on_card', 'Name on Card', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if ($this->form_validation->run()){
            
            $result = $this->users_model->fn_insert_payment_method();
            
            echo $result.'^^1';exit;
        }else{
            $data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
        }        
    }

    function fn_delete_payment_method() {

        $result = $this->users_model->fn_delete_payment_method();
        echo $result.'^^1';exit;
    }

    function fn_get_bank_account(){
        $data['page_title'] = 'Bank Accounts';
        $data['page_name'] = 'bank_accounts';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_bank_method_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $this->load->view('user/bank_accounts',$data);
    }

    function fn_get_bank_account_ajax(){
        $data['page_title'] = 'Bank Accounts';
        $data['page_name'] = 'bank_accounts';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_bank_method_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        echo $this->load->view('user/bank_accounts_ajax',$data,true);
        echo '^^1^^';
        echo $user_rows;
        exit;
    }        

    function fn_insert_bank_account() {
        $this->form_validation->set_rules('account_number', 'Account Number', 'required');
        $this->form_validation->set_rules('bank_name', 'Bank NAme', 'required');
        $this->form_validation->set_rules('account_name', 'Account Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if ($this->form_validation->run()){
            
            $result = $this->users_model->fn_insert_bank_account();
            
            echo $result.'^^1';exit;
        }else{
            $data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
        }        
    }

    function fn_delete_bank_account() {
        
        $result = $this->users_model->fn_delete_bank_account();
        echo $result.'^^1';exit;
    }

    function fn_set_active_payment_method() {
        $result = $this->users_model->fn_update_payent_method();
        echo $result.'^^1';exit;   
    }

    function fn_set_active_bank_acccount() {
        $result = $this->users_model->fn_update_bank_account();
        echo $result.'^^1';exit;   
    }

    function fn_get_transaction(){
        $data['page_title'] = 'Transactions';
        $data['page_name'] = 'transactions';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_transactions_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $this->load->view('user/transactions',$data);
    }

    function fn_get_transaction_ajax(){
        $data['page_title'] = 'Transactions';
        $data['page_name'] = 'transactions';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_transactions_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $this->load->view('user/transactions_ajax',$data);
    }

    function fn_insert_debit_transactions() {
        $result = $this->users_model->fn_insert_debit_transactions();
        if ($result != 0) {
            echo $result.'^^1';exit;
        } else {
            echo $result.'^^2';exit;
        }
    }

    function fn_insert_credit_transactions() {
        $result = $this->users_model->fn_insert_credit_transactions();
        if ($result != 0) {
            echo $result.'^^1';exit;
        } else {
            echo $result.'^^2';exit;
        }
    }
    
    function fn_get_profile_setup(){
        $data['page_title'] = 'Profile';
        $data['page_name'] = 'profile_setup';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;        
		$this->load->view('user/profile_setup',$data);
	}
    
    function fn_get_agency_company_setup(){
        $data['page_title'] = 'Company Detail';
        $data['page_name'] = 'agency_company_setup';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        
        $country_list_arr = array();
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
        $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$brex_api_user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $brex_country_list_arr = json_decode($result_country_list,true);
        if(isset($brex_country_list_arr[0]['country_code'])){
            $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
        
        /*$country_list_arr[0]['country_code'] = 'AT';
        $country_list_arr[0]['country_name'] = 'Austria';
        $country_list_arr[1]['country_code'] = 'AU';
        $country_list_arr[1]['country_name'] = 'Australia';
        $country_list_arr[2]['country_code'] = 'BE';
        $country_list_arr[2]['country_name'] = 'Belgium';*/
        if(!isset($country_list_arr[0]['country_code'])){
            $country_list_arr = array();    
        }
        $data['country_list_arr'] = $country_list_arr;            
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;        
		$this->load->view('user/agency_company_setup',$data);
	}
    
    function fn_get_client_company_setup(){
        $data['page_title'] = 'Company Detail';
        $data['page_name'] = 'client_company_setup';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $country_list_arr = array();
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
        $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$brex_api_user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $brex_country_list_arr = json_decode($result_country_list,true);
        if(isset($brex_country_list_arr[0]['country_code'])){
            $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
        
        /*$country_list_arr[0]['country_code'] = 'AT';
        $country_list_arr[0]['country_name'] = 'Austria';
        $country_list_arr[1]['country_code'] = 'AU';
        $country_list_arr[1]['country_name'] = 'Australia';
        $country_list_arr[2]['country_code'] = 'BE';
        $country_list_arr[2]['country_name'] = 'Belgium';*/
        if(!isset($country_list_arr[0]['country_code'])){
            $country_list_arr = array();    
        }
        $data['country_list_arr'] = $country_list_arr;
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;        
		$this->load->view('user/client_company_setup',$data);
	}
    
    function fn_insert_profile_setup(){
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $sess_user_type = $this->session->userdata('fe_user_login_mode');
            $sess_user_email = $this->session->userdata('fe_user_email');
            $sess_users_sk = $this->session->userdata('fe_user');
            
            $update_users_record = array(
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'modified_by'=>$sess_users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_user_record_by_email($update_users_record,$sess_user_email);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/user_image';
            if(!is_dir($create_dir_path)){
            	mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
            	$cropped_data = $_POST['cropped_img'];                                
            	$config['upload_path'] = $create_dir_path;
            	$config['allowed_types'] = 'gif|jpg|png|jpeg';
            	//$ext = end((explode(".", $_FILES['user_profile_image']['name'])));
                $ext_arr = (explode(".", $_FILES['user_profile_image']['name']));
                $ext = end($ext_arr);
            	$config['file_name'] = 'user_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
            	$this->load->library('upload', $config);
            	$this->upload->initialize($config);
            	$this->upload->set_allowed_types('*');                
            	if ($this->upload->do_upload('user_profile_image')) {
            		$upload_data = $this->upload->data();
            		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
            		
            		list($type, $cropped_data) = explode(';', $cropped_data);
            		list(, $cropped_data)      = explode(',', $cropped_data);
            		$upload_path = $create_dir_path.'/';
            		$cropped_data = base64_decode($cropped_data);
            		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
            		
            		$result = $this->users_model->fn_update_user_image($thumbnail_name,$sess_users_sk);
            	}
            }
            /* image upload end */
            
            $userdata = array('sess_user_type'=>$sess_user_type,'sess_users_sk'=>$sess_users_sk,'sess_user_email'=>$sess_user_email,'sess_last_name'=>$last_name,'sess_first_name'=>$first_name);
            $this->session->set_userdata($userdata);
            
            
        
            $country_list_arr = array();
            $result = $this->users_model->fn_get_all_site_constants();
            $get_all_site_constants_record_count = $result['rows'];
			$q_get_all_site_constants = $result['data'];
            $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
            $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
            
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'accept: application/json',
                'user_key: '.$brex_api_user_key
            ));
            $result_country_list = curl_exec($cURLConnection);
            curl_close($cURLConnection);        
            $brex_country_list_arr = json_decode($result_country_list,true);
            if(isset($brex_country_list_arr[0]['country_code'])){
                $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
            }
            
            $result = $this->users_model->fn_get_all_dial_code();
            $data['get_all_dial_code_record_count'] = $result['rows'];
			$data['q_get_all_dial_code'] = $result['data'];
            
            /*$country_list_arr[0]['country_code'] = 'AT';
            $country_list_arr[0]['country_name'] = 'Austria';
            $country_list_arr[1]['country_code'] = 'AU';
            $country_list_arr[1]['country_name'] = 'Australia';
            $country_list_arr[2]['country_code'] = 'BE';
            $country_list_arr[2]['country_name'] = 'Belgium';*/
            if(!isset($country_list_arr[0]['country_code'])){
                $country_list_arr = array();    
            }
            $data['country_list_arr'] = $country_list_arr;                                            
            $data['user_email'] = $sess_user_email;    
            
            $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($sess_users_sk);
            $data['company_rows'] = $company_rows = $company_result['rows']; 
            $data['company_data'] = $company_data = $company_result['data'];
            
            if($sess_user_type == 'CLIENT'){
                echo $this->load->view('user/client_company_setup_ajax',$data,true);
            }else{
                echo $this->load->view('user/agency_company_setup_ajax',$data,true);
            }        
            echo '^^1';exit;        
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}            
    }
    
    function fn_get_profile_setup_ajax(){
        $sess_user_email = $this->session->userdata('sess_user_email');
        $sess_user_type = $this->session->userdata('sess_user_type');
        $sess_first_name = $this->session->userdata('sess_first_name');
        $sess_last_name = $this->session->userdata('sess_last_name');
        
        $user_result = $this->users_model->fn_get_user_detail_by_email($sess_user_email);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;                 
        $data['user_email'] = $sess_user_email;
        $data['first_name'] = $sess_first_name;
        $data['last_name'] = $sess_last_name;
        echo $this->load->view('user/profile_setup_ajax',$data,true);
        echo '^^1';exit;        
    }
    
    function fn_get_dial_code_selection(){
        $country_code_name = $this->input->post('country_code_name');
        $country_code = '';
        $country_name = '';
        if($country_code_name != ''){
            $country_code_name_arr = explode('###',$country_code_name);
            $country_code = $country_code_name_arr[0];
            $country_name = $country_code_name_arr[1];
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
            
        $data['country_code'] = $country_code;
        $data['country_name'] = $country_name;
        echo $this->load->view('signup/get_dial_code_selection',$data,true);
        echo '^^1';exit;
    }
    
    function fn_insert_agency_company_setup(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
            
            $result = $this->users_model->fn_get_all_site_constants();
            $get_all_site_constants_record_count = $result['rows'];
			$q_get_all_site_constants = $result['data'];
            $brex_api_company_url = $q_get_all_site_constants[0]->brex_api_company_url;
            $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
            
            $brex_api_company_url = $brex_api_company_url.$country_code.'/'.$company_name;
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_company_url);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'accept: application/json',
                'user_key: '.$brex_api_user_key
            ));
            $result_company_list = curl_exec($cURLConnection);
            curl_close($cURLConnection);        
            $company_list_arr = json_decode($result_company_list,true);
            
            /*$company_list_arr[0]['id'] = '11';
            $company_list_arr[0]['name'] = 'Austria';
            $company_list_arr[1]['id'] = '22';
            $company_list_arr[1]['name'] = 'Solo';*/            
            
            $company_sk = '';
            $sess_users_sk = $this->session->userdata('fe_user');
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($sess_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;  
                $user_first_name = $user_data[0]->first_name;
                $user_last_name = $user_data[0]->last_name;
                $user_email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
                if($user_type == 'agency' || $user_type == 'both'){
                    $user_login_mode = "AGENCY";
                }else{
                    $user_login_mode = "CLIENT";
                }                        
                
                $contact_person_name = $user_first_name.' '.$user_last_name;                                      
                $company_domain = $this->input->post('company_domain');
                $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
                $vat_number = $this->input->post('vat_number');
                $street_address = $this->input->post('street_address');
                $apt_suite = $this->input->post('apt_suite');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip_code = $this->input->post('zip_code');
                $country_name = $country_name;
                $country_code = $country_code;
                $area_code = $this->input->post('area_code');
                $phone_no = $this->input->post('phone_no');
                $website_url = $this->input->post('website_url');
                $contact_email = $this->input->post('contact_email');
                
                $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
                $company_rows = $company_result['rows']; 
                $company_data = $company_result['data'];
                if($company_rows > 0){        
                    $users_sk = $company_data[0]->users_sk;
                    $sess_company_sk = $company_sk = $company_data[0]->company_sk;
                    $update_company_record = array(
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'company_domain'=>$company_domain,
                        'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                        'vat_number'=>$vat_number,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'is_agency_verified'=>'0',
                        'verify_later'=>'1',
                        'verify_later_date'=>date('Y-m-d H:i:s'),
                        'applied_for_manual_verification'=>'0',
                        'applied_for_manual_verification_date'=>'',                        
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                }else{
                    $sess_company_sk = $company_sk = $this->general->generate_primary_key();
            		$insert_record = array(
                        'company_sk'=>$company_sk,
                        'users_sk'=>$users_sk,
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'company_domain'=>$company_domain,
                        'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                        'vat_number'=>$vat_number,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'is_agency_verified'=>'0',
                        'verify_later'=>'1',
                        'verify_later_date'=>date('Y-m-d H:i:s'),
                        'applied_for_manual_verification'=>'0',
                        'applied_for_manual_verification_date'=>'',                        
                        'created_by'=>$users_sk,
                        'created_on'=>date('Y-m-d H:i:s'),
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $insert_result = $this->users_model->fn_insert_company_signup($insert_record);
                }
                
                /* image upload start */
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
                if(!is_dir($create_dir_path)){
                	mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_POST['cropped_img'])){
                	$cropped_data = $_POST['cropped_img'];                                
                	$config['upload_path'] = $create_dir_path;
                	$config['allowed_types'] = 'gif|jpg|png|jpeg';
                	//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                    $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                    $ext = end($ext_arr);
                	$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                	$this->load->library('upload', $config);
                	$this->upload->initialize($config);
                	$this->upload->set_allowed_types('*');                
                	if ($this->upload->do_upload('company_logo_image')) {
                		$upload_data = $this->upload->data();
                		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                		
                		list($type, $cropped_data) = explode(';', $cropped_data);
                		list(, $cropped_data)      = explode(',', $cropped_data);
                		$upload_path = $create_dir_path.'/';
                		$cropped_data = base64_decode($cropped_data);
                		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                		
                		$result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                	}
                }
                /* image upload end */    
            }else{
                echo 'verify_failed^^2';exit;
            }        
            
            $verification = 'failed';
            if(!empty($company_list_arr) && count($company_list_arr) > 0){
                for($i=0;$i<count($company_list_arr);$i++){
                    $loop_company_name = $company_list_arr[$i]['name'];
                    if(strtolower($company_name) == strtolower($loop_company_name)){
                        $verification = 'succ';
                        break;
                    }
                }
                if($verification == 'succ'){
                    $sess_users_sk = $this->session->userdata('fe_user');
                    $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($sess_users_sk);
                    $user_rows = $user_email_result['rows']; 
                    $user_data = $user_email_result['data'];
                    if($user_rows > 0){        
                        $users_sk = $user_data[0]->users_sk;  
                        $user_first_name = $user_data[0]->first_name;
                        $user_last_name = $user_data[0]->last_name;
                        $user_email = $user_data[0]->email;
                        $user_type = $user_data[0]->user_type;
                        if($user_type == 'agency' || $user_type == 'both'){
                            $user_login_mode = "AGENCY";
                        }else{
                            $user_login_mode = "CLIENT";
                        }    
                        
                        if($company_sk != ''){
                        	$update_company_record = array(
                        		'is_agency_verified'=>'1',
                        		'modified_by'=>$users_sk,
                        		'modified_on'=>date('Y-m-d H:i:s')
                        	);
                        	$update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                        }
                                                     
                        //create session and redirect to dashboard
                        $userdata = array('fe_user'  => $users_sk, 
    									'fe_first_name'  => $user_first_name,
                                        'fe_last_name'  => $user_last_name,
    									'fe_user_email'  => $user_email,
                                        'fe_user_login_mode'  => $user_login_mode
    									);
                        $this->session->set_userdata($userdata);                
                        echo $this->config->item('site_url').'dashboard/^^1';exit;
                    }else{
                        echo 'verify_failed^^2';exit;
                    }
                }else{
                    echo 'verify_failed^^2';exit;
                }    
            }else{
                echo 'verify_failed^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_insert_client_company_setup(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        /*$this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');*/
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
            $sess_users_sk = $this->session->userdata('fe_user');
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($sess_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){        
                $users_sk = $user_data[0]->users_sk;  
                $user_first_name = $user_data[0]->first_name;
                $user_last_name = $user_data[0]->last_name;
                $user_email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
                if($user_type == 'agency' || $user_type == 'both'){
                    $user_login_mode = "AGENCY";
                }else{
                    $user_login_mode = "CLIENT";
                }                        
                
                $contact_person_name = $user_first_name.' '.$user_last_name;                                      
                $street_address = $this->input->post('street_address');
                $apt_suite = $this->input->post('apt_suite');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip_code = $this->input->post('zip_code');
                $country_name = $country_name;
                $country_code = $country_code;
                $area_code = $this->input->post('area_code');
                $phone_no = $this->input->post('phone_no');
                $website_url = $this->input->post('website_url');
                $contact_email = $this->input->post('contact_email');
                
                $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
                $company_rows = $company_result['rows']; 
                $company_data = $company_result['data'];
                if($company_rows > 0){        
                    $users_sk = $company_data[0]->users_sk;
                    $sess_company_sk = $company_sk = $company_data[0]->company_sk;
                    $update_company_record = array(
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                }else{
                    $sess_company_sk = $company_sk = $this->general->generate_primary_key();
            		$insert_record = array(
                        'company_sk'=>$company_sk,
                        'users_sk'=>$users_sk,
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'created_by'=>$users_sk,
                        'created_on'=>date('Y-m-d H:i:s'),
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $insert_result = $this->users_model->fn_insert_company_signup($insert_record);
                }
                
                /* image upload start */
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
                if(!is_dir($create_dir_path)){
                	mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_POST['cropped_img'])){
                	$cropped_data = $_POST['cropped_img'];                                
                	$config['upload_path'] = $create_dir_path;
                	$config['allowed_types'] = 'gif|jpg|png|jpeg';
                	//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                    $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                    $ext = end($ext_arr);
                	$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                	$this->load->library('upload', $config);
                	$this->upload->initialize($config);
                	$this->upload->set_allowed_types('*');                
                	if ($this->upload->do_upload('company_logo_image')) {
                		$upload_data = $this->upload->data();
                		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                		
                		list($type, $cropped_data) = explode(';', $cropped_data);
                		list(, $cropped_data)      = explode(',', $cropped_data);
                		$upload_path = $create_dir_path.'/';
                		$cropped_data = base64_decode($cropped_data);
                		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                		
                		$result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                	}
                }
                /* image upload end */
                 
                //create session and redirect to dashboard
                $userdata = array('fe_user'  => $users_sk, 
							'fe_first_name'  => $user_first_name,
                            'fe_last_name'  => $user_last_name,
							'fe_user_email'  => $user_email,
                            'fe_user_login_mode'  => $user_login_mode
							);
                $this->session->set_userdata($userdata);                
                echo $this->config->item('site_url').'dashboard/^^1';exit;
            }else{
                echo 'verify_failed^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_profile_detail(){
        $data['page_title'] = 'Profile Detail';
        $data['page_name'] = 'profile_detail';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            //redirect($this->config->item('site_url'));
            redirect($this->config->item('site_url').'profile-setup');
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            //redirect($this->config->item('site_url'));
            $user_type = $user_data[0]->user_type;
            if($user_type == 'client'){
                redirect($this->config->item('site_url').'client-company-setup');
            }else{
                redirect($this->config->item('site_url').'agency-company-setup');
            }            
			exit;    
        }
        
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $data['user_profile_image_url'] = '';
		$this->load->view('user/profile_detail',$data);
	}
    
    function fn_update_profile_detail(){
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_first_name = $this->input->post('first_name');
            $user_last_name = $this->input->post('last_name');
            $users_sk = $this->input->post('users_sk'); 
            
            $result = $this->users_model->fn_update_profile_detail();
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/user_image';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
                $cropped_data = $_POST['cropped_img'];                                
                $config['upload_path'] = $create_dir_path;
        		$config['allowed_types'] = 'gif|jpg|png|jpeg';
        		//$ext = end((explode(".", $_FILES['user_profile_image']['name'])));
                $ext_arr = (explode(".", $_FILES['user_profile_image']['name']));
                $ext = end($ext_arr);
        		$config['file_name'] = 'user_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                $this->load->library('upload', $config);
        		$this->upload->initialize($config);
        		$this->upload->set_allowed_types('*');                
                if ($this->upload->do_upload('user_profile_image')) {
                    $upload_data = $this->upload->data();
                    $thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                    
                    list($type, $cropped_data) = explode(';', $cropped_data);
                    list(, $cropped_data)      = explode(',', $cropped_data);
                    $upload_path = $create_dir_path.'/';
                    $cropped_data = base64_decode($cropped_data);
                    file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                    
                    $result = $this->users_model->fn_update_user_image($thumbnail_name,$users_sk);
                }
            }
            /* image upload end */
            echo $result.'^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_update_password(){
        $this->form_validation->set_rules('new_password_field', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $password = $this->input->post('new_password_field');
            
            $result = $this->users_model->fn_update_password();
            echo $result.'^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_agency_company_detail(){
        $data['page_title'] = 'Agency Company Detail';
        $data['page_name'] = 'agency_company_detail';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $country_list_arr = array();
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
        $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$brex_api_user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $brex_country_list_arr = json_decode($result_country_list,true);
        if(isset($brex_country_list_arr[0]['country_code'])){
            $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
        
        /*$country_list_arr[0]['country_code'] = 'BE';
        $country_list_arr[0]['country_name'] = 'Belgium';
        $country_list_arr[1]['country_code'] = 'AT';
        $country_list_arr[1]['country_name'] = 'Austria';
        $country_list_arr[2]['country_code'] = 'AU';
        $country_list_arr[2]['country_name'] = 'Australia';*/
        if(!isset($country_list_arr[0]['country_code'])){
            $country_list_arr = array();    
        }
        $data['country_list_arr'] = $country_list_arr;
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $data['user_profile_image_url'] = '';
		$this->load->view('user/agency_company_detail',$data);
	}
    
    function fn_update_agency_company_detail(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
                
            $company_domain = $this->input->post('company_domain');
            $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
            $vat_number = $this->input->post('vat_number');
            $street_address = $this->input->post('street_address');
            $apt_suite = $this->input->post('apt_suite');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $zip_code = $this->input->post('zip_code');
            $country_name = $country_name;
            $country_code = $country_code;
            $area_code = $this->input->post('area_code');
            $phone_no = $this->input->post('phone_no');
            $website_url = $this->input->post('website_url');
            $contact_email = $this->input->post('contact_email');
            
            $company_sk = $this->input->post('company_sk');
            $users_sk = $this->input->post('users_sk');
    		$update_company_record = array(
                //'company_name'=>$company_name,
                'company_domain'=>$company_domain,
                'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                'vat_number'=>$vat_number,
                'street_address'=>$street_address,
                'apt_suite'=>$apt_suite,
                'city'=>$city,
                'state'=>$state,
                'zip_code'=>$zip_code,
                'country_name'=>$country_name,
                'country_code'=>$country_code,
                'area_code'=>$area_code,
                'phone_no'=>$phone_no,
                'website_url'=>$website_url,
                'contact_email'=>$contact_email,
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
                $cropped_data = $_POST['cropped_img'];                                
                $config['upload_path'] = $create_dir_path;
        		$config['allowed_types'] = 'gif|jpg|png|jpeg';
        		//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                $ext = end($ext_arr);
        		$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                $this->load->library('upload', $config);
        		$this->upload->initialize($config);
        		$this->upload->set_allowed_types('*');                
                if ($this->upload->do_upload('company_logo_image')) {
                    $upload_data = $this->upload->data();
                    $thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                    
                    list($type, $cropped_data) = explode(';', $cropped_data);
                    list(, $cropped_data)      = explode(',', $cropped_data);
                    $upload_path = $create_dir_path.'/';
                    $cropped_data = base64_decode($cropped_data);
                    file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                    
                    $result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                }
            }
            /* image upload end */
            echo $this->config->item('site_url').'agency-company-detail/^^1';exit;                    
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_update_verify_agency_company_detail(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
            
            $result = $this->users_model->fn_get_all_site_constants();
            $get_all_site_constants_record_count = $result['rows'];
			$q_get_all_site_constants = $result['data'];
            $brex_api_company_url = $q_get_all_site_constants[0]->brex_api_company_url;
            $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
            
            $brex_api_company_url = $brex_api_company_url.$country_code.'/'.$company_name;
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_company_url);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'accept: application/json',
                'user_key: '.$brex_api_user_key
            ));
            $result_company_list = curl_exec($cURLConnection);
            curl_close($cURLConnection);        
            $company_list_arr = json_decode($result_company_list,true);
            
            /*$company_list_arr[0]['id'] = '11';
            $company_list_arr[0]['name'] = 'Austria';
            $company_list_arr[1]['id'] = '22';
            $company_list_arr[1]['name'] = 'Solo';*/            
            
            $company_domain = $this->input->post('company_domain');
            $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
            $vat_number = $this->input->post('vat_number');
            $street_address = $this->input->post('street_address');
            $apt_suite = $this->input->post('apt_suite');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $zip_code = $this->input->post('zip_code');
            $country_name = $country_name;
            $country_code = $country_code;
            $area_code = $this->input->post('area_code');
            $phone_no = $this->input->post('phone_no');
            $website_url = $this->input->post('website_url');
            $contact_email = $this->input->post('contact_email');
            
            $company_sk = $this->input->post('company_sk');
            $users_sk = $this->input->post('users_sk');
    		$update_company_record = array(
                'company_name'=>$company_name,
                'company_domain'=>$company_domain,
                'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                'vat_number'=>$vat_number,
                'street_address'=>$street_address,
                'apt_suite'=>$apt_suite,
                'city'=>$city,
                'state'=>$state,
                'zip_code'=>$zip_code,
                'country_name'=>$country_name,
                'country_code'=>$country_code,
                'area_code'=>$area_code,
                'phone_no'=>$phone_no,
                'website_url'=>$website_url,
                'contact_email'=>$contact_email,                
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
                $cropped_data = $_POST['cropped_img'];                                
                $config['upload_path'] = $create_dir_path;
        		$config['allowed_types'] = 'gif|jpg|png|jpeg';
        		//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                $ext = end($ext_arr);
        		$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                $this->load->library('upload', $config);
        		$this->upload->initialize($config);
        		$this->upload->set_allowed_types('*');                
                if ($this->upload->do_upload('company_logo_image')) {
                    $upload_data = $this->upload->data();
                    $thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                    
                    list($type, $cropped_data) = explode(';', $cropped_data);
                    list(, $cropped_data)      = explode(',', $cropped_data);
                    $upload_path = $create_dir_path.'/';
                    $cropped_data = base64_decode($cropped_data);
                    file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                    
                    $result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                }
            }
            /* image upload end */
            
            $verification = 'failed';
            if(!empty($company_list_arr) && count($company_list_arr) > 0){
                for($i=0;$i<count($company_list_arr);$i++){
                    $loop_company_name = $company_list_arr[$i]['name'];
                    if(strtolower($company_name) == strtolower($loop_company_name)){
                        $verification = 'succ';
                        break;
                    }
                }
                if($verification == 'succ'){
                    if($company_sk != ''){
                    	$update_company_record = array(
                    		'is_agency_verified'=>'1',
                    		'modified_by'=>$users_sk,
                    		'modified_on'=>date('Y-m-d H:i:s')
                    	);
                    	$update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                    }
                
                    echo $this->config->item('site_url').'agency-company-detail/^^1';exit;                    
                }else{
                    echo 'verify_failed^^2';exit;
                }    
            }else{
                echo 'verify_failed^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_client_company_detail(){
        $data['page_title'] = 'Client Company Detail';
        $data['page_name'] = 'client_company_detail';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $country_list_arr = array();
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
        $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$brex_api_user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $brex_country_list_arr = json_decode($result_country_list,true);
        if(isset($brex_country_list_arr[0]['country_code'])){
            $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
        
        /*$country_list_arr[0]['country_code'] = 'AT';
        $country_list_arr[0]['country_name'] = 'Austria';
        $country_list_arr[1]['country_code'] = 'AU';
        $country_list_arr[1]['country_name'] = 'Australia';
        $country_list_arr[2]['country_code'] = 'BE';
        $country_list_arr[2]['country_name'] = 'Belgium';*/
        if(!isset($country_list_arr[0]['country_code'])){
            $country_list_arr = array();    
        }
        $data['country_list_arr'] = $country_list_arr;
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $data['user_profile_image_url'] = '';
		$this->load->view('user/client_company_detail',$data);
	}
    
    function fn_update_client_company_detail(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        /*$this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');*/
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
                                                      
            $street_address = $this->input->post('street_address');
            $apt_suite = $this->input->post('apt_suite');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $zip_code = $this->input->post('zip_code');
            $country_name = $country_name;
            $country_code = $country_code;
            $area_code = $this->input->post('area_code');
            $phone_no = $this->input->post('phone_no');
            $website_url = $this->input->post('website_url');
            $contact_email = $this->input->post('contact_email');
            
            $company_sk = $this->input->post('company_sk');
            $users_sk = $this->input->post('users_sk');
    		$update_company_record = array(
                'company_name'=>$company_name,
                'street_address'=>$street_address,
                'apt_suite'=>$apt_suite,
                'city'=>$city,
                'state'=>$state,
                'zip_code'=>$zip_code,
                'country_name'=>$country_name,
                'country_code'=>$country_code,
                'area_code'=>$area_code,
                'phone_no'=>$phone_no,
                'website_url'=>$website_url,
                'contact_email'=>$contact_email,
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
            	mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
            	$cropped_data = $_POST['cropped_img'];                                
            	$config['upload_path'] = $create_dir_path;
            	$config['allowed_types'] = 'gif|jpg|png|jpeg';
            	//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                $ext = end($ext_arr);
            	$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
            	$this->load->library('upload', $config);
            	$this->upload->initialize($config);
            	$this->upload->set_allowed_types('*');                
            	if ($this->upload->do_upload('company_logo_image')) {
            		$upload_data = $this->upload->data();
            		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
            		
            		list($type, $cropped_data) = explode(';', $cropped_data);
            		list(, $cropped_data)      = explode(',', $cropped_data);
            		$upload_path = $create_dir_path.'/';
            		$cropped_data = base64_decode($cropped_data);
            		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
            		
            		$result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
            	}
            }
            /* image upload end */
            
            echo $this->config->item('site_url').'client-company-detail/^^1';exit;            
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_client_become_agency_detail(){
        $data['page_title'] = 'Client Becode Agency Company Detail';
        $data['page_name'] = 'client_become_agency_detail';
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        if($user_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows == 0){
            redirect($this->config->item('site_url'));
			exit;    
        }
        
        $country_list_arr = array();
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $brex_api_country_url = $q_get_all_site_constants[0]->brex_api_country_url;
        $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_country_url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$brex_api_user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $brex_country_list_arr = json_decode($result_country_list,true);
        if(isset($brex_country_list_arr[0]['country_code'])){
            $country_list_arr = $this->general->brex_country_filter($brex_country_list_arr);
        }
        
        $result = $this->users_model->fn_get_all_dial_code();
        $data['get_all_dial_code_record_count'] = $result['rows'];
        $data['q_get_all_dial_code'] = $result['data'];
        
        /*$country_list_arr[0]['country_code'] = 'AT';
        $country_list_arr[0]['country_name'] = 'Austria';
        $country_list_arr[1]['country_code'] = 'AU';
        $country_list_arr[1]['country_name'] = 'Australia';
        $country_list_arr[2]['country_code'] = 'BE';
        $country_list_arr[2]['country_name'] = 'Belgium';*/
        if(!isset($country_list_arr[0]['country_code'])){
            $country_list_arr = array();    
        }
        $data['country_list_arr'] = $country_list_arr;
        $data['user_rows'] = $user_rows;
        $data['user_data'] = $user_data;
        $data['company_rows'] = $company_rows;
        $data['company_data'] = $company_data;
        $data['logged_in_users_sk'] = $logged_in_users_sk;
        $data['user_profile_image_url'] = '';
		$this->load->view('user/client_become_agency_detail',$data);
	}
    
    function fn_update_client_become_agency_company_detail(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
            
            $result = $this->users_model->fn_get_all_site_constants();
            $get_all_site_constants_record_count = $result['rows'];
			$q_get_all_site_constants = $result['data'];
            $brex_api_company_url = $q_get_all_site_constants[0]->brex_api_company_url;
            $brex_api_user_key = $q_get_all_site_constants[0]->brex_api_user_key;
            
            $brex_api_company_url = $brex_api_company_url.$country_code.'/'.$company_name;
            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $brex_api_company_url);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'accept: application/json',
                'user_key: '.$brex_api_user_key
            ));
            $result_company_list = curl_exec($cURLConnection);
            curl_close($cURLConnection);        
            $company_list_arr = json_decode($result_company_list,true);
            
            /*$company_list_arr[0]['id'] = '11';
            $company_list_arr[0]['name'] = 'Austria';
            $company_list_arr[1]['id'] = '22';
            $company_list_arr[1]['name'] = 'Solo';*/            
            
            $company_domain = $this->input->post('company_domain');
            $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
            $vat_number = $this->input->post('vat_number');
            $street_address = $this->input->post('street_address');
            $apt_suite = $this->input->post('apt_suite');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $zip_code = $this->input->post('zip_code');
            $country_name = $country_name;
            $country_code = $country_code;
            $area_code = $this->input->post('area_code');
            $phone_no = $this->input->post('phone_no');
            $website_url = $this->input->post('website_url');
            $contact_email = $this->input->post('contact_email');
            
            $company_sk = $this->input->post('company_sk');
            $users_sk = $this->input->post('users_sk');
    		$update_company_record = array(
                'company_name'=>$company_name,
                'company_domain'=>$company_domain,
                'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                'vat_number'=>$vat_number,
                'street_address'=>$street_address,
                'apt_suite'=>$apt_suite,
                'city'=>$city,
                'state'=>$state,
                'zip_code'=>$zip_code,
                'country_name'=>$country_name,
                'country_code'=>$country_code,
                'area_code'=>$area_code,
                'phone_no'=>$phone_no,
                'website_url'=>$website_url,
                'contact_email'=>$contact_email,                
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
            
            $update_users_record = array(
                'user_type'=>'both',
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_user_detail($update_users_record,$users_sk);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
            	mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
            	$cropped_data = $_POST['cropped_img'];                                
            	$config['upload_path'] = $create_dir_path;
            	$config['allowed_types'] = 'gif|jpg|png|jpeg';
            	//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                $ext = end($ext_arr);
            	$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
            	$this->load->library('upload', $config);
            	$this->upload->initialize($config);
            	$this->upload->set_allowed_types('*');                
            	if ($this->upload->do_upload('company_logo_image')) {
            		$upload_data = $this->upload->data();
            		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
            		
            		list($type, $cropped_data) = explode(';', $cropped_data);
            		list(, $cropped_data)      = explode(',', $cropped_data);
            		$upload_path = $create_dir_path.'/';
            		$cropped_data = base64_decode($cropped_data);
            		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
            		
            		$result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
            	}
            }
            /* image upload end */
            
            $verification = 'failed';
            if(!empty($company_list_arr) && count($company_list_arr) > 0){
                for($i=0;$i<count($company_list_arr);$i++){
                    $loop_company_name = $company_list_arr[$i]['name'];
                    if(strtolower($company_name) == strtolower($loop_company_name)){
                        $verification = 'succ';
                        break;
                    }
                }
                if($verification == 'succ'){
                    if($company_sk != ''){
                    	$update_company_record = array(
                    		'is_agency_verified'=>'1',
                    		'modified_by'=>$users_sk,
                    		'modified_on'=>date('Y-m-d H:i:s')
                    	);
                    	$update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                    }
                    
                    //change user login mode
                    $user_login_mode = "AGENCY";
                    $userdata = array('fe_user_login_mode'  => $user_login_mode);
                    $this->session->set_userdata($userdata);
                                    
                    echo $this->config->item('site_url').'agency-company-detail/^^1';exit;                    
                }else{
                    echo 'verify_failed^^2';exit;
                }    
            }else{
                echo 'verify_failed^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_check_agency_verified_or_not(){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        $is_agency_verified = $company_data[0]->is_agency_verified;
        
        $user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $user_rows = $user_result['rows']; 
        $user_data = $user_result['data'];
        $user_type = $user_data[0]->user_type;

        if($is_agency_verified == '1'){
            echo "verified";exit;
        }else{
            if($user_type != 'client'){
                echo "verified";exit;                
            }else{
                echo "pending_verification";exit;                    
            }                
        }
    }
    
    function fn_check_company_name_exists(){
        $sess_users_sk = $this->session->userdata('fe_user');
        $company_name = $this->input->post('company_name');
        $company_name_result = $this->users_model->fn_get_company_detail_by_name($company_name,$sess_users_sk);
        $company_name_rows = $company_name_result['rows']; 
        $company_name_data = $company_name_result['data'];
        if($company_name_rows > 0){
        	echo 'company_exists^^1';exit; 
        }else{
            echo 'company_not_exists^^1';exit;
        }
    }
    
    function fn_apply_for_manual_verification_agency_company_detail(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            //check third party validation here
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
                
            $company_domain = $this->input->post('company_domain');
            $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
            $vat_number = $this->input->post('vat_number');
            $street_address = $this->input->post('street_address');
            $apt_suite = $this->input->post('apt_suite');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $zip_code = $this->input->post('zip_code');
            $country_name = $country_name;
            $country_code = $country_code;
            $area_code = $this->input->post('area_code');
            $phone_no = $this->input->post('phone_no');
            $website_url = $this->input->post('website_url');
            $contact_email = $this->input->post('contact_email');
            
            $company_sk = $this->input->post('company_sk');
            $users_sk = $this->input->post('users_sk');
            if($users_sk == '' && empty($users_sk)){
                $users_sk = $this->session->userdata('fe_user');
            }
            
            $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
            $company_rows = $company_result['rows']; 
            $company_data = $company_result['data'];
            if($company_rows > 0){
                $company_sk = $company_data[0]->company_sk;
        		$update_company_record = array(
                    //'company_name'=>$company_name,
                    'company_domain'=>$company_domain,
                    'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                    'vat_number'=>$vat_number,
                    'street_address'=>$street_address,
                    'apt_suite'=>$apt_suite,
                    'city'=>$city,
                    'state'=>$state,
                    'zip_code'=>$zip_code,
                    'country_name'=>$country_name,
                    'country_code'=>$country_code,
                    'area_code'=>$area_code,
                    'phone_no'=>$phone_no,
                    'website_url'=>$website_url,
                    'contact_email'=>$contact_email,
                    'is_agency_verified'=>'0',
                    'verify_later'=>'0',
                    'verify_later_date'=>'',
                    'applied_for_manual_verification'=>'1',
                    'applied_for_manual_verification_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );
                $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
            }else{
                $user_first_name = '';
                $user_last_name = '';
                $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($users_sk);
                $user_rows = $user_email_result['rows']; 
                $user_data = $user_email_result['data'];
                if($user_rows > 0){        
                    $user_first_name = $user_data[0]->first_name;
                    $user_last_name = $user_data[0]->last_name;
                }                    
                $contact_person_name = $user_first_name.' '.$user_last_name;
                        
            	$sess_company_sk = $company_sk = $this->general->generate_primary_key();
            	$insert_record = array(
            		'company_sk'=>$company_sk,
            		'users_sk'=>$users_sk,
            		'company_name'=>$company_name,
            		'contact_person_name'=>$contact_person_name,
            		'company_domain'=>$company_domain,
            		'chamber_of_commerce_number'=>$chamber_of_commerce_number,
            		'vat_number'=>$vat_number,
            		'street_address'=>$street_address,
            		'apt_suite'=>$apt_suite,
            		'city'=>$city,
            		'state'=>$state,
            		'zip_code'=>$zip_code,
            		'country_name'=>$country_name,
            		'country_code'=>$country_code,
            		'area_code'=>$area_code,
            		'phone_no'=>$phone_no,
            		'website_url'=>$website_url,
            		'contact_email'=>$contact_email,
            		'is_agency_verified'=>'0',
                    'verify_later'=>'0',
                    'verify_later_date'=>'',
                    'applied_for_manual_verification'=>'1',
                    'applied_for_manual_verification_date'=>date('Y-m-d H:i:s'),
            		'created_by'=>$users_sk,
            		'created_on'=>date('Y-m-d H:i:s'),
            		'modified_by'=>$users_sk,
            		'modified_on'=>date('Y-m-d H:i:s')
            	);
            	$insert_result = $this->users_model->fn_insert_company_signup($insert_record);
            }                
            
            $update_users_record = array(
                'user_type'=>'both',
                'modified_by'=>$users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_user_detail($update_users_record,$users_sk);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
                $cropped_data = $_POST['cropped_img'];                                
                $config['upload_path'] = $create_dir_path;
        		$config['allowed_types'] = 'gif|jpg|png|jpeg';
        		//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                $ext = end($ext_arr);
        		$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                $this->load->library('upload', $config);
        		$this->upload->initialize($config);
        		$this->upload->set_allowed_types('*');                
                if ($this->upload->do_upload('company_logo_image')) {
                    $upload_data = $this->upload->data();
                    $thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                    
                    list($type, $cropped_data) = explode(';', $cropped_data);
                    list(, $cropped_data)      = explode(',', $cropped_data);
                    $upload_path = $create_dir_path.'/';
                    $cropped_data = base64_decode($cropped_data);
                    file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                    
                    $result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                }
            }
            /* image upload end */
            echo $this->config->item('site_url').'agency-company-detail/^^1';exit;                    
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_agency_company_setup_verify_later(){
        $this->form_validation->set_rules('company_name', 'Company name', 'required');
        $this->form_validation->set_rules('company_domain', 'Domain', 'required');
        $this->form_validation->set_rules('chamber_of_commerce_number', 'Chamber of commerce number', 'required');
        $this->form_validation->set_rules('vat_number', 'VAT number', 'required');
        $this->form_validation->set_rules('street_address', 'Street Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip_code', 'Zip code', 'required');
        $this->form_validation->set_rules('country_code_name', 'Country', 'required');
        $this->form_validation->set_rules('area_code', 'Area Code', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $company_name = $this->input->post('company_name');
            $country_code_name = $this->input->post('country_code_name');
            $country_code = '';
            $country_name = '';
            if($country_code_name != ''){
                $country_code_name_arr = explode('###',$country_code_name);
                $country_code = $country_code_name_arr[0];
                $country_name = $country_code_name_arr[1];
            }
        
            $sess_users_sk = $this->session->userdata('sess_users_sk');
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($sess_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){        
                $users_sk = $user_data[0]->users_sk;  
                $user_first_name = $user_data[0]->first_name;
                $user_last_name = $user_data[0]->last_name;
                $user_email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
                if($user_type == 'agency' || $user_type == 'both'){
                    $user_login_mode = "AGENCY";
                }else{
                    $user_login_mode = "CLIENT";
                }                        
                
                $contact_person_name = $user_first_name.' '.$user_last_name;                                      
                $company_domain = $this->input->post('company_domain');
                $chamber_of_commerce_number = $this->input->post('chamber_of_commerce_number');
                $vat_number = $this->input->post('vat_number');
                $street_address = $this->input->post('street_address');
                $apt_suite = $this->input->post('apt_suite');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip_code = $this->input->post('zip_code');
                $country_name = $country_name;
                $country_code = $country_code;
                $area_code = $this->input->post('area_code');
                $phone_no = $this->input->post('phone_no');
                $website_url = $this->input->post('website_url');
                $contact_email = $this->input->post('contact_email');
                
                $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
                $company_rows = $company_result['rows']; 
                $company_data = $company_result['data'];
                if($company_rows > 0){        
                    $users_sk = $company_data[0]->users_sk;
                    $sess_company_sk = $company_sk = $company_data[0]->company_sk;
                    $update_company_record = array(
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'company_domain'=>$company_domain,
                        'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                        'vat_number'=>$vat_number,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'is_agency_verified'=>'0',
                        'verify_later'=>'1',
                        'verify_later_date'=>date('Y-m-d H:i:s'),
                        'applied_for_manual_verification'=>'0',
                        'applied_for_manual_verification_date'=>'',
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                }else{
                    $sess_company_sk = $company_sk = $this->general->generate_primary_key();
            		$insert_record = array(
                        'company_sk'=>$company_sk,
                        'users_sk'=>$users_sk,
                        'company_name'=>$company_name,
                        'contact_person_name'=>$contact_person_name,
                        'company_domain'=>$company_domain,
                        'chamber_of_commerce_number'=>$chamber_of_commerce_number,
                        'vat_number'=>$vat_number,
                        'street_address'=>$street_address,
                        'apt_suite'=>$apt_suite,
                        'city'=>$city,
                        'state'=>$state,
                        'zip_code'=>$zip_code,
                        'country_name'=>$country_name,
                        'country_code'=>$country_code,
                        'area_code'=>$area_code,
                        'phone_no'=>$phone_no,
                        'website_url'=>$website_url,
                        'contact_email'=>$contact_email,
                        'is_agency_verified'=>'0',
                        'verify_later'=>'1',
                        'verify_later_date'=>date('Y-m-d H:i:s'),
                        'applied_for_manual_verification'=>'0',
                        'applied_for_manual_verification_date'=>'',
                        'created_by'=>$users_sk,
                        'created_on'=>date('Y-m-d H:i:s'),
                        'modified_by'=>$users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );
                    $insert_result = $this->users_model->fn_insert_company_signup($insert_record);
                }
                
                /* image upload start */
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/company_image';
                if(!is_dir($create_dir_path)){
                	mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_POST['cropped_img'])){
                    #print_r($_FILES);
                	$cropped_data = $_POST['cropped_img'];                                
                	$config['upload_path'] = $create_dir_path;
                	$config['allowed_types'] = 'gif|jpg|png|jpeg';
                	//$ext = end((explode(".", $_FILES['company_logo_image']['name'])));
                    $ext_arr = (explode(".", $_FILES['company_logo_image']['name']));
                    $ext = end($ext_arr);
                	$config['file_name'] = 'company_image_'.$this->general->generate_random_letters(4).'.'.$ext;                
                	$this->load->library('upload', $config);
                	$this->upload->initialize($config);
                	$this->upload->set_allowed_types('*');                
                	if ($this->upload->do_upload('company_logo_image')) {
                		$upload_data = $this->upload->data();
                		$thumbnail_name = $upload_data['raw_name'].'_th'.$upload_data['file_ext'];
                		
                		list($type, $cropped_data) = explode(';', $cropped_data);
                		list(, $cropped_data)      = explode(',', $cropped_data);
                		$upload_path = $create_dir_path.'/';
                		$cropped_data = base64_decode($cropped_data);
                		file_put_contents($upload_path.$thumbnail_name, $cropped_data);
                		
                		$result = $this->users_model->fn_update_company_image($thumbnail_name,$users_sk);
                	}
                }
                /* image upload end */
                 
                //create session and redirect to dashboard
                $userdata = array('fe_user'  => $users_sk, 
                                'fe_first_name'  => $user_first_name,
                                'fe_last_name'  => $user_last_name,
                                'fe_user_email'  => $user_email,
                                'fe_user_login_mode'  => $user_login_mode
                            );
                $this->session->set_userdata($userdata);                
                echo $this->config->item('site_url').'dashboard/^^1';exit;
            }else{
                echo 'verify_failed^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
                                                                
}
