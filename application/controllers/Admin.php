<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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
		$this->load->model('admin_users_model');
        $this->load->model('users_model');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('upload');
		$this->load->library("pagination");
		$this->load->library('Ajax_pagination');
        $this->perPage = 50;
        header("Cache-Control: max-age=1800, must-revalidate");
    }
    
    function fn_send_ajax_error_handling_mail(){
        $jqxhr_status = $this->input->post('jqxhr_status');
        $jqxhr_responsetext = $this->input->post('jqxhr_responsetext');
        $exception = $this->input->post('exception');
        $err_msg = $this->input->post('err_msg');
        $function_name = $this->input->post('function_name');
        
        $result = $this->users_model->fn_get_all_site_constants();
		$data['get_all_site_constants_record_count'] = $result['rows'];
		$data['q_get_all_site_constants'] = $result['data'];
        
        $error_handling_email = $data['q_get_all_site_constants'][0]->error_handling_email;
        $error_handling_env = $data['q_get_all_site_constants'][0]->env;
        $from_email = $data['q_get_all_site_constants'][0]->from_email;
        $from_name = $data['q_get_all_site_constants'][0]->from_name;
        $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
        $subject = $from_name.' | An Error Occurred on Yura Live server!';
        if(strtolower($error_handling_env) == 'test'){
            $subject = $from_name.' | An Error Occurred on Yura Test server!';
        }elseif(strtolower($error_handling_env) == 'live'){
            $subject = $from_name.' | An Error Occurred on Yura Live server!';
        }                     
        $to_email = $error_handling_email;
        $message = $this->general->fn_email_error_handling_mail_html($jqxhr_status,$jqxhr_responsetext,$exception,$err_msg,$function_name);
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
                
    }
             
	public function fn_get_login(){
        $data['page_title'] = 'Login';
        
		$this->load->view('admin/login',$data);
	}
    
    public function fn_check_login_process(){
		$errors = array();
		$check = true;
		$this->form_validation->set_rules('sign_in_email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run())
		{
			$result=$this->admin_users_model->fn_check_login_process();
            if( $result['rows'] > 0 )
			{
                $admindata = array(
								   'fe_admin_user'  => $result['data'][0]->admin_users_sk,
                                   'fe_admin_first_name'  => $result['data'][0]->first_name,
                                   'fe_admin_last_name'  => $result['data'][0]->last_name,
								   'fe_admin_email'  => $result['data'][0]->email
							   );
				$this->session->set_userdata($admindata);
                $data = array();
                fun_redirect($this->config->item('site_url').'admin/',$data);
			}
			else
			{
                //$check = false;
				//array_push($errors, "Invalid email and password!");
                $data = array();
                $data["errors"] = "Invalid email and password!";
                fun_redirect($this->config->item('site_url').'admin/',$data);                                
			}
			if (!$check)
			{
                $data["errors"] = $errors;
				$this->load->view('admin/login',$data);
			}
		}
		else
		{            
			$this->load->view('admin/login');
		}
	} 
    
    public function admin_logout(){
        $this->session->unset_userdata('fe_admin_user');
		$this->session->unset_userdata('fe_admin_first_name');
		$this->session->unset_userdata('fe_admin_last_name');
        $this->session->unset_userdata('fe_admin_email');
        $this->session->sess_destroy();       
        redirect($this->config->item('site_url').'admin/');            
	}
    
    function fn_get_users_list(){
        $data = array();
		$data['viewname'] = "users";
		$data['pagetitle'] = "Users";
        
        $data['search_users_sk'] = $search_users_sk = $this->input->get('u_id') ? $this->input->get('u_id') : '';
        $data['filter_agency'] = $this->input->get('filter_agency') ? $this->input->get('filter_agency') : 'all';
        $data['page_limit'] = $this->perPage;
		$data['sort_by'] = 'U.created_on';
		$data['sort_order'] = 'desc';
		$this->load->view("admin/users",$data);		
	}
    
    function fn_get_all_users_ajax_pagination_data(){
        $conditions = array();
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
		
		$page_limit = $this->input->post('page_limit');
        if(!$page_limit){
            $post_per_page = $this->perPage;
        }else{
            $post_per_page = $page_limit;
        }
        $data['page_limit'] = $post_per_page;
        //set conditions for search
        $filter_agency = $this->input->post('filter_agency');
		$search_users_keyword = $this->input->post('search_users_keyword');
        $search_users_sk = $this->input->post('search_users_sk');
        $sort_by = $this->input->post('sort_by');
		$sort_order = $this->input->post('sort_order');
        if(!empty($search_users_keyword)){
            $conditions['search']['search_users_keyword'] = $search_users_keyword;
        }
        if(!empty($search_users_sk)){
            $conditions['search']['search_users_sk'] = $search_users_sk;
        }
        if(!empty($sort_by)){
            $conditions['search']['sort_by'] = $sort_by;
        }
		if(!empty($sort_order)){
            $conditions['search']['sort_order'] = $sort_order;
        }
        $conditions['search']['filter_agency'] = $filter_agency;
        //total rows count
        //$totalRec = count($this->post->getRows($conditions));
		$totalRec = $this->admin_users_model->fn_get_all_users_count($conditions);
		
        //pagination configuration
        $config['target']      = '#users_wrapper';
        $config['base_url']    = $this->config->item('site_url').'admin/fn_get_all_users_ajax_pagination_data';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $post_per_page;
        $config['link_func']   = 'fun_search_users';
        $this->ajax_pagination->initialize($config);
        $data['curr_page'] = $offset;
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $post_per_page;
        
        //get posts data
        //$data['posts'] = $this->post->getRows($conditions);
        $result = $this->admin_users_model->fn_get_all_users($conditions);
		$data['recordcount_users']=$result['rows'];
		$data['get_all_users']=$result['data'];
        
        //load the view
        $this->load->view('admin/ajax-users-pagination-data', $data, false);
    }
    
    function fn_approve_agency_company(){
        $company_sk = $this->input->post('company_sk');
        $this->admin_users_model->fn_approve_agency_company($company_sk);
        
        $user_company_result = $this->admin_users_model->fn_get_admin_user_and_company_detail($company_sk);
        $user_company_rows = $user_company_result['rows'];
        $user_company_data = $user_company_result['data'];
        if($user_company_rows > 0){
            $result = $this->users_model->fn_get_all_site_constants();
            $data['get_all_site_constants_record_count'] = $result['rows'];
            $data['q_get_all_site_constants'] = $result['data'];

            $from_email = $data['q_get_all_site_constants'][0]->from_email;
            $from_name = $data['q_get_all_site_constants'][0]->from_name;
            $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
            $to_email = $user_company_data[0]->email;
            #$to_email = 'mayur.webexcellis@gmail.com';
            $subject = $from_name.' | Verification successful';
            $message = $this->general->fn_email_agency_approved_mail_html();
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
        }
        
        echo '^^1^^';
        exit;
    }
    
    function fn_open_reject_agency_company_side_div_panel(){
        $company_sk = $this->input->post('company_sk');
        
        $reason_for_rejection = '';
        $mode = 'update';
        $side_panel_title = "Reason for Rejection";
        
        echo $side_panel_title.'^^1^^';
        ?>
        <form name="frm_update_agency" id="frm_update_agency" data-toggle="validator" class="kt-form form-horizontal form-label-left" action="<?php echo $this->config->item('site_url');?>admin/fn_update_agency/" method="post" enctype="multipart/form-data" >
            <input type="hidden" id="company_sk" name="company_sk" value="<?php echo $company_sk;?>" class="form-control">
            <div class="container-fluid">
              	<div class="row">
					<div class="col-lg-12 col-md-12 col-xs-12 removepadding">
						<div class="form-group">
							
							<textarea class="form-control" rows="4" required="required" name="reason_for_rejection" id="reason_for_rejection"><?php echo $reason_for_rejection;?></textarea>
						</div>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 removepadding">
                                <button type="button" class="btn btn-primary btn-sm pull-right" id="update_agency" onClick="funAjaxPostUpdateAgency(this.form);">Submit</button>
                                <button type="button" id="wait_update_agency" class="btn btn-primary btn-sm pull-right display-hide">Please wait...</button>
                            </div>
                        </div>                                                            
					</div>
				</div>
			</div>
        </form>                        
        <?php        
    }
    
    function fn_update_agency(){  
        $company_sk = $this->input->post('company_sk');    
		$reason_for_rejection = $this->input->post('reason_for_rejection');
		if(!empty($reason_for_rejection)){
			$result = $suppliers_sk = $this->admin_users_model->fn_reject_agency_company($company_sk,$reason_for_rejection);
            
            $user_company_result = $this->admin_users_model->fn_get_admin_user_and_company_detail($company_sk);
            $user_company_rows = $user_company_result['rows'];
            $user_company_data = $user_company_result['data'];
            if($user_company_rows > 0){
                $result = $this->users_model->fn_get_all_site_constants();
                $data['get_all_site_constants_record_count'] = $result['rows'];
                $data['q_get_all_site_constants'] = $result['data'];
    
                $from_email = $data['q_get_all_site_constants'][0]->from_email;
                $from_name = $data['q_get_all_site_constants'][0]->from_name;
                $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
                $to_email = $user_company_data[0]->email;
                #$to_email = 'mayur.webexcellis@gmail.com';
                $subject = $from_name.' | Verification failed';
                $message = $this->general->fn_email_agency_rejected_mail_html($reason_for_rejection);
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
            }
            
			echo $company_sk.'^^1';exit;
		}else{
			echo 'Please add reject reason.';exit;
		}
	}
                                                                        
}
