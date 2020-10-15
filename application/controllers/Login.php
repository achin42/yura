<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
        $this->load->library('email');
        if(!empty($this->session->userdata('fe_user'))){
			redirect($this->config->item('site_url').'dashboard');
			exit;
		}
        header("Cache-Control: max-age=1800, must-revalidate");
    }
         
	public function index(){
        $data['page_title'] = 'Home';
        
		$this->load->view('sign_in',$data);
	}
    
    function fn_check_login(){
        $this->form_validation->set_rules('sign_in_email', 'Email', 'required');
        $this->form_validation->set_rules('sign_in_password', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $sign_in_email = $this->input->post('sign_in_email'); 
            $sign_in_password = $this->input->post('sign_in_password');            
            $user_result = $this->users_model->fn_check_login($sign_in_email,$sign_in_password);
            $user_rows = $user_result['rows']; 
            $user_data = $user_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;
                $first_name = $user_data[0]->first_name;
                $last_name = $user_data[0]->last_name;
                $email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
                $is_active = $user_data[0]->is_active;
                
                $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
                $company_rows = $company_result['rows']; 
                $company_data = $company_result['data'];
                
                if($is_active == '0'){
                    echo 'You have not verified your email yet. Please check your email and click verification link to activate your account.^^2';exit;    
                }else{
                    if($user_type == 'agency' || $user_type == 'both'){
                        $user_login_mode = "AGENCY";
                    }else{
                        $user_login_mode = "CLIENT";
                    }                        
                    $userdata = array('fe_user'  => $users_sk, 
									'fe_first_name'  => $first_name,
                                    'fe_last_name'  => $last_name,
									'fe_user_email'  => $email,
                                    'fe_user_login_mode'  => $user_login_mode,
									);
                    $this->session->set_userdata($userdata);                    
                    if($first_name == '' || $last_name == ''){
                        echo $this->config->item('site_url').'profile-setup/^^1';exit;
                    }else{
                        if($company_rows == 0){
                            if($user_type == 'agency' || $user_type == 'both'){
                                echo $this->config->item('site_url').'agency-company-setup/^^1';exit;
                            }else{
                                echo $this->config->item('site_url').'client-company-setup/^^1';exit;
                            }                                    
                        }else{
                            echo $this->config->item('site_url').'dashboard/^^1';exit;
                        }                        
                    }                                                                
                }
            }else{
                echo 'Login failed: Invalid email or password^^2';exit;
            }        
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_insert_signup(){
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_email = $this->input->post('email'); 
            $user_first_name = $this->input->post('first_name');
            $user_company_name = $this->input->post('company_name');
            
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($user_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                echo $user_data[0]->users_sk.'^^2';exit; 
            }else{
                $insert_result = $this->users_model->fn_insert_signup();
                
                $result = $this->users_model->fn_get_all_site_constants();
				$data['get_all_site_constants_record_count'] = $result['rows'];
				$data['q_get_all_site_constants'] = $result['data'];
                
                /*send activation email to user start*/
                $to_email = $user_email;
                $subject = $data['q_get_all_site_constants'][0]->from_name.' | User Registration ';
				$message = $this->general->fn_email_user_signup_mail_html($to_email);
                $config = array (
				   'mailtype' => 'html',
				   'charset'  => 'utf-8',
				   'priority' => '1'
					);
				$this->email->initialize($config);
				$this->email->from($data['q_get_all_site_constants'][0]->from_email,$data['q_get_all_site_constants'][0]->from_name);
				$this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($message);
                $this->email->send();
                /*send activation email to user end*/
                
                /*mail to admin[start]*/
                $to_email = $data['q_get_all_site_constants'][0]->admin_email;
                $subject = $data['q_get_all_site_constants'][0]->from_name.' | User Registration ';
                $message = $this->general->fn_email_admin_signup_mail_html($user_first_name,$user_email,$user_company_name);
                #echo $message;exit;        
				$config = array (
				   'mailtype' => 'html',
				   'charset'  => 'utf-8',
				   'priority' => '1'
					);
				$this->email->initialize($config);
				$this->email->from($data['q_get_all_site_constants'][0]->from_email,$data['q_get_all_site_constants'][0]->from_name);
				$this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($message);
                $this->email->send();
				/*mail to admin[end]*/
                 
                echo $insert_result.'^^1';exit;
            }    
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_activate_user(){
        $link = $this->input->get('link');
        if($link != ''){
            $base64_decode_email = base64_decode($link); 
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($base64_decode_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;
                $result = $this->users_model->fn_update_activate_user_account($users_sk);
                $data["success"] = "Your account has been activated successfully.";
                fun_redirect($this->config->item('site_url'),$data);
            }else{
                $data["errors"] = "This request is invalid.";
                fun_redirect($this->config->item('site_url'),$data);                    
            }    
        }else{
            $data["errors"] = "This link is invalid.";
            fun_redirect($this->config->item('site_url'),$data);                
        }                 
    }
    
    function fn_check_api(){
        $url = 'https://api.brex.io/api/v1/system/countries';
        $user_key = '017e816eeaf8812cb712da04e6d83e1b';
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$user_key
        ));
        $result_country_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $country_list_arr = json_decode($result_country_list,true);
        echo "<pre>";
        print_r($country_list_arr);
        
        $url = 'https://api.brex.io/api/v1/company/search/name/DK/SOLO';
        
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'user_key: '.$user_key
        ));
        $result_company_list = curl_exec($cURLConnection);
        curl_close($cURLConnection);        
        $company_list_arr = json_decode($result_company_list,true);
        echo "<pre>";
        print_r($company_list_arr);
    }
    
    function fn_get_signup(){
        $data['page_title'] = 'Sign Up';
        $data['page_name'] = 'sign_up';        
                                        
        $this->load->view('signup/sign_up',$data);
	}
    
    function fn_get_change_email(){
        $data['user_email'] = $this->session->userdata('sess_user_email');
        echo $this->load->view('signup/sign_up_ajax',$data,true);
        echo '^^1';exit;        
    }
    
    function fn_get_signup_selection(){
        $sess_user_email = $user_email = $this->session->userdata('sess_user_email');
        $sess_user_type = $user_type = $this->session->userdata('sess_user_type');
        $sess_signup_invite_flow = $this->session->userdata('sess_signup_invite_flow');
        if($sess_signup_invite_flow == 'yes'){
            $invited_users_sk = $this->session->userdata('sess_users_sk');            
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($invited_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $user_email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
            }                        
            $data['user_email'] = $user_email;
            $data['users_sk'] = $invited_users_sk;
            $data['user_type'] = $user_type;
            $data['sess_signup_invite_flow'] = $sess_signup_invite_flow;
            echo $this->load->view('signup/invited_signup_selection_ajax',$data,true);
            echo '^^1^^'.$sess_signup_invite_flow;exit;    
        }else{
            $data['user_email'] = $sess_user_email;
            $data['sess_user_type'] = $sess_user_type;
            $data['sess_signup_invite_flow'] = $sess_signup_invite_flow;
            echo $this->load->view('signup/signup_selection',$data,true);
            echo '^^1^^'.$sess_signup_invite_flow;exit;
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
        echo $this->load->view('signup/profile_setup_ajax',$data,true);
        echo '^^1';exit;        
    }
    
    function fn_send_resend_email(){
        $user_email = $this->session->userdata('sess_user_email');
        
        $verification_code = $this->general->generate_random_code('6');
        $sess_signup_invite_flow = 'no';
        $userdata = array('sess_signup_invite_flow'=>$sess_signup_invite_flow,'sess_user_email'=>$user_email,'sess_user_verification_code'=>$verification_code);
        $this->session->set_userdata($userdata);
        
        $result = $this->users_model->fn_get_all_site_constants();
        $data['get_all_site_constants_record_count'] = $result['rows'];
        $data['q_get_all_site_constants'] = $result['data'];
        
        $from_email = $data['q_get_all_site_constants'][0]->from_email;
        $from_name = $data['q_get_all_site_constants'][0]->from_name;
        $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
        $to_email = $user_email;
        $subject = $from_name.' | Verify Your Account';
        $message = $this->general->fn_email_user_verify_account_mail_html($verification_code);            
        if(!empty($send_grid_api_key) && $send_grid_api_key != ''){
            include APPPATH . 'third_party/sendgrid-php.php';
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($from_email,$from_name);
            $email->setSubject($subject);
            $email->addTo($to_email, "User");
            $email->addContent("text/plain", "subject");
            $email->addContent("text/html",$message);    
            $sendgrid = new \SendGrid(($send_grid_api_key));
            try {
                $response = $sendgrid->send($email);
                echo '1^^1';exit;
                /*echo "<pre>";
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";*/
            } catch (Exception $e) {
                echo '2^^2';exit;
            }
        }else{                                        
            $config = array (
                'mailtype' => 'html',
                'charset'  => 'utf-8',
                'priority' => '1'
            );
            $this->email->initialize($config);
            $this->email->from($from_email,$from_name);
            $this->email->to($to_email);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            echo '1^^1';exit;
        }                                
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
    
    function fn_insert_new_signup_step_1(){
        $this->form_validation->set_rules('sign_up_email', 'Email', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_email = $this->input->post('sign_up_email'); 
            $insert_signup = 'no';
            $users_sk = '';
                        
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($user_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;
                $user_email = $user_data[0]->email;  
                $is_active = $user_data[0]->is_active;
                $is_invited = $user_data[0]->is_invited;
                if($is_active == '1'){
                    $insert_signup = 'no';
                }elseif($is_invited == '1'){
                    $insert_signup = 'no_verification_code';
                }else{
                    $insert_signup = 'yes';
                }                 
            }else{
                $insert_signup = 'yes';
            }
            
            if($insert_signup == 'yes'){
                $verification_code = $this->general->generate_random_code('6');
                $sess_signup_invite_flow = 'no';
                $userdata = array('sess_signup_invite_flow'=>$sess_signup_invite_flow,'sess_user_email'=>$user_email,'sess_user_verification_code'=>$verification_code);
                $this->session->set_userdata($userdata);
                
                $result = $this->users_model->fn_get_all_site_constants();
				$data['get_all_site_constants_record_count'] = $result['rows'];
				$data['q_get_all_site_constants'] = $result['data'];
                
                $from_email = $data['q_get_all_site_constants'][0]->from_email;
                $from_name = $data['q_get_all_site_constants'][0]->from_name;
                $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
                $to_email = $user_email;
                $subject = $from_name.' | Verify Your Account';
                $message = $this->general->fn_email_user_verify_account_mail_html($verification_code);
                if(!empty($send_grid_api_key) && $send_grid_api_key != ''){
                	include APPPATH . 'third_party/sendgrid-php.php';
                	$email = new \SendGrid\Mail\Mail();
                	$email->setFrom($from_email,$from_name);
                	$email->setSubject($subject);
                	$email->addTo($to_email, "User");
                	$email->addContent("text/plain", "subject");
                	$email->addContent("text/html",$message);    
                	$sendgrid = new \SendGrid(($send_grid_api_key));
                	try {
                		$response = $sendgrid->send($email);
                	} catch (Exception $e) {
                		                		
                	}
                }else{                                        
                	$config = array (
                		'mailtype' => 'html',
                		'charset'  => 'utf-8',
                		'priority' => '1'
                	);
                	$this->email->initialize($config);
                	$this->email->from($from_email,$from_name);
                	$this->email->to($to_email);
                	$this->email->subject($subject);
                	$this->email->message($message);
                	$this->email->send();                	
                } 
                $data['verification_code'] = $verification_code;
                echo $this->load->view('signup/verify_signup',$data,true);
                echo '^^1';exit;
            }elseif($insert_signup == 'no_verification_code'){
                $data['invited_users_sk'] = $users_sk;
                $data['invited_email'] = $user_email;
                $data['page_title'] = 'Sign Up';
                $data['page_name'] = 'sign_up';                
                echo $this->load->view('signup/invited_activate_user_ajax',$data,true);
                echo '^^3';exit;                                       
            }else{
                echo $users_sk.'^^2';exit;    
            }                            
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_insert_new_signup_step_2(){
        $this->form_validation->set_rules('sign_up_password_field', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $password = $this->input->post('sign_up_password_field'); 
            $verification_code_1 = $this->input->post('verification_code_1');
            $verification_code_2 = $this->input->post('verification_code_2');
            $verification_code_3 = $this->input->post('verification_code_3');
            $verification_code_4 = $this->input->post('verification_code_4');
            $verification_code_5 = $this->input->post('verification_code_5');
            $verification_code_6 = $this->input->post('verification_code_6');
            
            if($verification_code_1 == '' || $verification_code_2 == '' || $verification_code_3 == '' || $verification_code_4 == '' || $verification_code_5 == '' || $verification_code_6 == ''){
                echo "Please enter verification code.";exit;
            }else{
                $sess_user_email = $this->session->userdata('sess_user_email');
                $sess_user_verification_code = $this->session->userdata('sess_user_verification_code');
                $post_verification_code = $verification_code_1.$verification_code_2.$verification_code_3.$verification_code_4.$verification_code_5.$verification_code_6;
                if($sess_user_verification_code == $post_verification_code){
                    $sess_signup_invite_flow = 'no';
                    $userdata = array('sess_signup_invite_flow'=>$sess_signup_invite_flow,'sess_user_email'=>$sess_user_email,'sess_user_verification_code'=>$sess_user_verification_code,'sess_user_password'=>$password);
                    $this->session->set_userdata($userdata);
                
                    $data['user_email'] = $sess_user_email;
                    $data['sess_user_type'] = 'agency';
                    echo $this->load->view('signup/signup_selection',$data,true);
                    echo '^^1';exit;    
                }else{
                    echo "Invalid verification code. Please try again.";exit;    
                }                                                
            }            
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}                
    }
    
    function fn_insert_new_signup_step_3(){
        $this->form_validation->set_rules('user_type', 'Agency or Client', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_type = $this->input->post('user_type');
            $marketing_allowed = $this->input->post('marketing_allowed');
            if(!empty($marketing_allowed)){
                $marketing_allowed = '1';
            }else{
                $marketing_allowed = '0';
            }
            $user_first_name = '';
            $user_last_name = '';
            
            $sess_user_email = $this->session->userdata('sess_user_email');
            $sess_user_password = $this->session->userdata('sess_user_password');
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($sess_user_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $sess_users_sk = $user_data[0]->users_sk;  
                $user_email = $user_data[0]->email;
                $user_first_name = $user_data[0]->first_name; 
                $user_last_name = $user_data[0]->last_name;
                $update_users_record = array(
                    'email'=>$sess_user_email,
                    'password'=>md5($sess_user_password),
                    'user_type'=>$user_type,
                    'marketing_allowed'=>$marketing_allowed,
                    'is_active'=>'1',
                    'modified_by'=>$users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );
                $update_result = $this->users_model->fn_update_user_record_by_email($update_users_record,$user_email);     
            }else{
                $users_sk = $sess_users_sk = $this->general->generate_primary_key();
        		$insert_record = array(
                    'users_sk'=>$users_sk,
                    'email'=>$sess_user_email,
                    'password'=>md5($sess_user_password),
                    'user_type'=>$user_type,
                    'marketing_allowed'=>$marketing_allowed,
                    'is_active'=>'1',
                    'created_by'=>$users_sk,
                    'created_on'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );
                $insert_result = $this->users_model->fn_insert_new_signup($insert_record);
            }            
            $userdata = array('sess_user_type'=>$user_type,'sess_users_sk'=>$sess_users_sk);
            $this->session->set_userdata($userdata);
            
            $data['user_rows'] = $user_rows;
            $data['user_data'] = $user_data;
            $data['user_email'] = $sess_user_email;
            $data['first_name'] = $user_first_name;
            $data['last_name'] = $user_last_name;
            echo $this->load->view('signup/profile_setup_ajax',$data,true);
            echo '^^1';exit;
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}                    
    }
    
    function fn_insert_new_signup_step_4(){
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $sess_user_type = $this->session->userdata('sess_user_type');
            $sess_user_email = $this->session->userdata('sess_user_email');
            $sess_users_sk = $this->session->userdata('sess_users_sk');
            
            $update_users_record = array(
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'modified_by'=>$sess_users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );
            $update_result = $this->users_model->fn_update_user_record_by_email($update_users_record,$sess_user_email);
            
            $userdata = array('sess_user_type'=>$sess_user_type,'sess_users_sk'=>$sess_users_sk,'sess_user_email'=>$sess_user_email,'sess_last_name'=>$last_name,'sess_first_name'=>$first_name);
            $this->session->set_userdata($userdata);
            
            /* image upload start */
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/user_image';
            if(!is_dir($create_dir_path)){
            	mkdir($create_dir_path, 0777, true);
            }
            if(!empty($_POST['cropped_img'])){
                $cropped_data = $_POST['cropped_img'];                                
            	$config['upload_path'] = $create_dir_path;
            	$config['allowed_types'] = 'gif|jpg|png|jpeg';
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
            #print_r($country_list_arr);exit;
            /*$country_list_arr[0]['country_code'] = 'AT';
            $country_list_arr[0]['country_name'] = 'Austria';
            $country_list_arr[1]['country_code'] = 'AU';
            $country_list_arr[1]['country_name'] = 'Australia';
            $country_list_arr[2]['country_code'] = 'BE';
            $country_list_arr[2]['country_name'] = 'Belgium';*/
            if(!isset($country_list_arr[0]['country_code'])){
                $country_list_arr = array();    
            }                                                     
            
            $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($sess_users_sk);
            $data['company_rows'] = $company_rows = $company_result['rows']; 
            $data['company_data'] = $company_data = $company_result['data'];
                                                
            $data['country_list_arr'] = $country_list_arr;                                            
            $data['user_email'] = $sess_user_email;
            if($sess_user_type == 'client'){
                echo $this->load->view('signup/client_company_detail_ajax',$data,true);
            }else{
                echo $this->load->view('signup/agency_company_detail_ajax',$data,true);
            }        
            echo '^^1';exit;        
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}            
    }
    
    function fn_insert_new_signup_step_5(){
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
    
    function fn_insert_new_signup_step_6(){
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
                
                /*start check company exists*/
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
    
    function fn_check_company_name_exists(){
        $sess_users_sk = $this->session->userdata('sess_users_sk');
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
    
    function fn_delete_user(){
        $data['page_title'] = 'Delete User';
        $data['page_name'] = 'fn_delete_user';        
        
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $project_env = $q_get_all_site_constants[0]->env;
        if(strtolower($project_env) == 'live'){
            redirect($this->config->item('site_url').'show_404');
			exit;                
        }
        
        $this->load->view('signup/delete_user',$data);
	}
    
    function fn_delete_users_instance(){
        $this->form_validation->set_rules('delete_email', 'Email', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_email = $this->input->post('delete_email'); 
                        
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($user_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk; 
                $result = $this->users_model->fn_delete_user($users_sk);                
                echo '1^^1';exit;                     
            }else{
                echo '2^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_invited_activate_user(){
        $link = $this->input->get('link');
        if($link != ''){
            $base64_decode_email = base64_decode($link); 
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($base64_decode_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;
                $user_email = $user_data[0]->email;
                $is_active = $user_data[0]->is_active;
                $is_invited = $user_data[0]->is_invited;
                if($is_active == '0' && $is_invited == '1'){
                    $data['invited_users_sk'] = $users_sk;
                    $data['invited_email'] = $user_email;
                    $data['page_title'] = 'Sign Up';
                    $data['page_name'] = 'sign_up';
                    $this->load->view('signup/invited_activate_user',$data);
                }elseif($is_active == '1' && $is_invited == '1'){
                    $data["success"] = "Your account is already active. Please login.";
                    fun_redirect($this->config->item('site_url'),$data);
                }else{
                    $data["errors"] = "This request is invalid.";
                    fun_redirect($this->config->item('site_url'),$data);
                }
            }else{
                $data["errors"] = "This request is invalid.";
                fun_redirect($this->config->item('site_url'),$data);                    
            }    
        }else{
            $data["errors"] = "This link is invalid.";
            fun_redirect($this->config->item('site_url'),$data);                
        }                 
    }
    
    function fn_invited_signup_step_1(){
        $this->form_validation->set_rules('invited_password_field', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $password = $this->input->post('invited_password_field'); 
            $invited_users_sk = $users_sk = $this->input->post('invited_users_sk');
            $invited_email = $this->input->post('invited_email');
            $user_first_name = '';
            $user_last_name = '';
                        
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($invited_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $user_email = $user_data[0]->email;
                $user_type = $user_data[0]->user_type;
                
                $update_users_record = array(
                    'password'=>md5($password),
                    'modified_by'=>$users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );
                $update_result = $this->users_model->fn_update_user_record_by_email($update_users_record,$user_email);
                
                /*start set session data as like normal signup flow*/
                $sess_user_email = $user_email;
                $sess_user_password = $password;
                $sess_user_type = $user_type;
                $sess_users_sk = $users_sk;
                $sess_first_name = $user_first_name;
                $sess_last_name = $user_last_name;          
                $sess_signup_invite_flow = 'yes';      
                $userdata = array('sess_signup_invite_flow'=>$sess_signup_invite_flow,'sess_user_email'=>$sess_user_email,'sess_user_password'=>$sess_user_password,'sess_user_type'=>$sess_user_type,'sess_users_sk'=>$sess_users_sk,'sess_first_name'=>$sess_first_name,'sess_last_name'=>$sess_last_name);
                $this->session->set_userdata($userdata);                
                /*end set session data as like normal signup flow*/
                                                
                $data['user_email'] = $user_email;
                $data['users_sk'] = $invited_users_sk;
                $data['user_type'] = $user_type;
                echo $this->load->view('signup/invited_signup_selection_ajax',$data,true);
                echo '^^1';exit;            
            }else{
                $data['errors'] = 'This request is invalid.';
                echo strip_tags($data['errors']);exit;
            }                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_invited_signup_step_2(){
        $this->form_validation->set_rules('user_type', 'Agency or Client', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_type = $this->input->post('user_type');
            $marketing_allowed = $this->input->post('marketing_allowed');
            if(!empty($marketing_allowed)){
                $marketing_allowed = '1';
            }else{
                $marketing_allowed = '0';
            }
            $user_first_name = '';
            $user_last_name = '';
            
            $invited_users_sk = $this->session->userdata('sess_users_sk');
            $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($invited_users_sk);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;  
                $user_email = $user_data[0]->email;
                $user_first_name = $user_data[0]->first_name; 
                $user_last_name = $user_data[0]->last_name; 
                $update_users_record = array(
                    'user_type'=>$user_type,
                    'marketing_allowed'=>$marketing_allowed,
                    'is_active'=>'1',
                    'modified_by'=>$users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );
                $update_result = $this->users_model->fn_update_user_record_by_email($update_users_record,$user_email);
                /*start set session data as like normal signup flow*/
                $sess_user_email = $user_email;
                $sess_user_type = $user_type;
                $sess_users_sk = $users_sk;
                $sess_first_name = $user_first_name;
                $sess_last_name = $user_last_name;   
                $sess_signup_invite_flow = 'yes';             
                $userdata = array('sess_signup_invite_flow'=>$sess_signup_invite_flow,'sess_user_email'=>$sess_user_email,'sess_user_type'=>$sess_user_type,'sess_users_sk'=>$sess_users_sk,'sess_first_name'=>$sess_first_name,'sess_last_name'=>$sess_last_name);
                $this->session->set_userdata($userdata);                
                /*end set session data as like normal signup flow*/
                
                $data['user_rows'] = $user_rows;
                $data['user_data'] = $user_data;
                $data['user_email'] = $user_email;
                $data['users_sk'] = $users_sk;
                $data['first_name'] = $user_first_name;
                $data['last_name'] = $user_last_name;         
                echo $this->load->view('signup/profile_setup_ajax',$data,true);
                echo '^^1';exit;     
            }                        
        }else{                  
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}                    
    }
    
    function fn_get_invited_signup_selection(){
        $invited_users_sk = $this->input->post('data_users_sk');
        $user_email = '';
        $user_type = '';
         
        $user_email_result = $this->users_model->fn_get_user_detail_by_users_sk($invited_users_sk);
        $user_rows = $user_email_result['rows']; 
        $user_data = $user_email_result['data'];
        if($user_rows > 0){
            $user_email = $user_data[0]->email;
            $user_type = $user_data[0]->user_type;        
        }
        
        $data['user_email'] = $user_email;
        $data['users_sk'] = $invited_users_sk;
        $data['user_type'] = $user_type;
        echo $this->load->view('signup/invited_signup_selection_ajax',$data,true);
        echo '^^1';exit;        
    }
    
    function fn_change_company_name(){
        $data['page_title'] = 'Change Company Name';
        $data['page_name'] = 'fn_change_company_name';        
        
        $result = $this->users_model->fn_get_all_site_constants();
        $get_all_site_constants_record_count = $result['rows'];
        $q_get_all_site_constants = $result['data'];
        $project_env = $q_get_all_site_constants[0]->env;
        if(strtolower($project_env) == 'live'){
            redirect($this->config->item('site_url').'show_404');
			exit;                
        }
        
        $this->load->view('signup/change_company_name',$data);
	}
    
    function fn_change_company_name_instance(){
        $this->form_validation->set_rules('existing_company_name', 'Existing Company Name', 'required');
        $this->form_validation->set_rules('new_company_name', 'New Company Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $existing_company_name = $this->input->post('existing_company_name');
            $new_company_name = $this->input->post('new_company_name'); 
                        
            $company_result = $this->users_model->fn_get_company_detail_by_name($existing_company_name);
            $company_rows = $company_result['rows']; 
            $q_company_data = $company_result['data'];
            if($company_rows > 0){
                //$users_sk = $company_data[0]->company_sk; 
                //$result = $this->users_model->fn_delete_user($users_sk);                
                if($company_rows > 0){
                    foreach($q_company_data as $company_data){
                        $company_sk = $company_data->company_sk;        
                        $update_company_record = array(
                            'company_name'=>$new_company_name                            
                        );
                        $update_result = $this->users_model->fn_update_company_detail($update_company_record,$company_sk);
                    }
                }
                echo '1^^1';exit;                     
            }else{
                echo '2^^2';exit;
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_check_php_setting(){
        echo phpinfo();exit;               
    }
    
    function fn_insert_new_signup_step_5_verify_later(){
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
    
    function fn_signup_apply_for_manual_verification_agency_company_detail(){
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
                        'verify_later'=>'0',
                        'verify_later_date'=>'',
                        'applied_for_manual_verification'=>'1',
                        'applied_for_manual_verification_date'=>date('Y-m-d H:i:s'),
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
    
    function fn_action_forgot_password(){
        $this->form_validation->set_rules('forgot_password_email', 'Email', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $user_email = $this->input->post('forgot_password_email'); 
                        
            $user_email_result = $this->users_model->fn_get_user_detail_by_email($user_email);
            $user_rows = $user_email_result['rows']; 
            $user_data = $user_email_result['data'];
            if($user_rows > 0){
                $users_sk = $user_data[0]->users_sk;
                $user_email = $user_data[0]->email;  
                
                $forgot_password_user_verification_code = $this->general->generate_random_code('6');
                $userdata = array('sess_forgot_password_user_email'=>$user_email,'sess_forgot_password_user_verification_code'=>$forgot_password_user_verification_code);
                $this->session->set_userdata($userdata);
                
                $result = $this->users_model->fn_get_all_site_constants();
				$data['get_all_site_constants_record_count'] = $result['rows'];
				$data['q_get_all_site_constants'] = $result['data'];
                
                $from_email = $data['q_get_all_site_constants'][0]->from_email;
                $from_name = $data['q_get_all_site_constants'][0]->from_name;
                $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
                $to_email = $user_email;
                $subject = $from_name.' | Password Reset OTP';
                $message = $this->general->fn_forgot_password_mail_html($forgot_password_user_verification_code);
                #echo $message;exit;            
                if(!empty($send_grid_api_key) && $send_grid_api_key != ''){
                	include APPPATH . 'third_party/sendgrid-php.php';
                	$email = new \SendGrid\Mail\Mail();
                	$email->setFrom($from_email,$from_name);
                	$email->setSubject($subject);
                	$email->addTo($to_email, "User");
                	$email->addContent("text/plain", "subject");
                	$email->addContent("text/html",$message);    
                	$sendgrid = new \SendGrid(($send_grid_api_key));
                	try {
                		$response = $sendgrid->send($email);
                		/*echo "<pre>";
                		print $response->statusCode() . "\n";
                		print_r($response->headers());
                		print $response->body() . "\n";*/
                	} catch (Exception $e) {
                		//echo 'Caught exception: '. $e->getMessage() ."\n";                		
                	}
                }else{                                        
                	$config = array (
                		'mailtype' => 'html',
                		'charset'  => 'utf-8',
                		'priority' => '1'
                	);
                	$this->email->initialize($config);
                	$this->email->from($from_email,$from_name);
                	$this->email->to($to_email);
                	$this->email->subject($subject);
                	$this->email->message($message);
                	$this->email->send();                	
                } 
                $data['forgot_password_user_email'] = $user_email;
                $data['forgot_password_user_verification_code'] = $forgot_password_user_verification_code;
                echo $this->load->view('recover_password',$data,true);
                echo '^^1';exit;
            }else{
                echo '2^^2';exit;    
            }
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_action_change_password(){
        $this->form_validation->set_rules('change_password_field', 'New Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $change_password = $this->input->post('change_password_field'); 
            $forgot_password_verification_code_1 = $this->input->post('forgot_password_verification_code_1');
            $forgot_password_verification_code_2 = $this->input->post('forgot_password_verification_code_2');
            $forgot_password_verification_code_3 = $this->input->post('forgot_password_verification_code_3');
            $forgot_password_verification_code_4 = $this->input->post('forgot_password_verification_code_4');
            $forgot_password_verification_code_5 = $this->input->post('forgot_password_verification_code_5');
            $forgot_password_verification_code_6 = $this->input->post('forgot_password_verification_code_6');
            
            if($forgot_password_verification_code_1 == '' || $forgot_password_verification_code_2 == '' || $forgot_password_verification_code_3 == '' || $forgot_password_verification_code_4 == '' || $forgot_password_verification_code_5 == '' || $forgot_password_verification_code_6 == ''){
                echo "Please enter One Time Password.";exit;
            }else{
                $sess_forgot_password_user_email = $this->session->userdata('sess_forgot_password_user_email');
                $sess_forgot_password_user_verification_code = $this->session->userdata('sess_forgot_password_user_verification_code');
                $post_verification_code = $forgot_password_verification_code_1.$forgot_password_verification_code_2.$forgot_password_verification_code_3.$forgot_password_verification_code_4.$forgot_password_verification_code_5.$forgot_password_verification_code_6;
                if($sess_forgot_password_user_verification_code == $post_verification_code){
                    $user_email_result = $this->users_model->fn_get_user_detail_by_email($sess_forgot_password_user_email);
                    $user_rows = $user_email_result['rows']; 
                    $user_data = $user_email_result['data'];
                    if($user_rows > 0){
                        $users_sk = $user_data[0]->users_sk;
                        $update_users_record = array(
                            'password'=>md5($change_password),
                            'modified_by'=>$users_sk,
                            'modified_on'=>date('Y-m-d H:i:s')
                        );
                        $update_result = $this->users_model->fn_update_user_detail($update_users_record,$users_sk);
                        echo '^^1';exit;    
                    }else{
                        echo '2^^2';exit;    
                    }                        
                }else{
                    echo "Invalid verification code. Please try again.";exit;    
                }                                                
            }            
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}                
    }
    
    function fn_send_resend_change_password_email(){
        $user_email = $this->session->userdata('sess_forgot_password_user_email');
        
        $forgot_password_user_verification_code = $this->general->generate_random_code('6');
        $userdata = array('sess_forgot_password_user_email'=>$user_email,'sess_forgot_password_user_verification_code'=>$forgot_password_user_verification_code);
        $this->session->set_userdata($userdata);
        
        $result = $this->users_model->fn_get_all_site_constants();
        $data['get_all_site_constants_record_count'] = $result['rows'];
        $data['q_get_all_site_constants'] = $result['data'];
        
        $from_email = $data['q_get_all_site_constants'][0]->from_email;
        $from_name = $data['q_get_all_site_constants'][0]->from_name;
        $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
        $to_email = $user_email;
        $subject = $from_name.' | Password Reset OTP';
        $message = $this->general->fn_forgot_password_mail_html($forgot_password_user_verification_code);
        #echo $send_grid_api_key;exit;            
        if(!empty($send_grid_api_key) && $send_grid_api_key != ''){
        	include APPPATH . 'third_party/sendgrid-php.php';
        	$email = new \SendGrid\Mail\Mail();
        	$email->setFrom($from_email,$from_name);
        	$email->setSubject($subject);
        	$email->addTo($to_email, "User");
        	$email->addContent("text/plain", "subject");
        	$email->addContent("text/html",$message);    
        	$sendgrid = new \SendGrid(($send_grid_api_key));
        	try {
        		$response = $sendgrid->send($email);
                echo '1^^1';exit;
        		/*echo "<pre>";
        		print $response->statusCode() . "\n";
        		print_r($response->headers());
        		print $response->body() . "\n";*/
        	} catch (Exception $e) {
                #echo '1^^1';exit;
        		//echo 'Caught exception: '. $e->getMessage() ."\n";                		
        	}
        }else{                                        
        	$config = array (
        		'mailtype' => 'html',
        		'charset'  => 'utf-8',
        		'priority' => '1'
        	);
        	$this->email->initialize($config);
        	$this->email->from($from_email,$from_name);
        	$this->email->to($to_email);
        	$this->email->subject($subject);
        	$this->email->message($message);
        	$this->email->send();   
            echo '1^^1';exit;             	
        } 
        
    }
                                                                            
}
