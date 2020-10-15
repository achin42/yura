<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
        $this->load->library('Signrequest');
    }
         
	public function index()
	{
		$this->load->view('welcome_message');
	}
    
    function send_sign_request(){
        $this->signrequest->send_sign_request_demo();    
    }
        
    function check_sign_request(){
        $this->signrequest->check_sign_request_demo();    
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
                
}
