<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agreement_signed extends CI_Controller {

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
        $this->load->library('email');
        $this->load->library('Signrequest');
    }
         
	function fn_set_first_signer_signed_agreement(){
        $agreement_sign_sk = $this->uri->segment('2');
        $first_signer_sk = $this->uri->segment('3');
        
        $agreement_sign_result = $this->project_model->fn_get_agreement_sign_detail($agreement_sign_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['agreement_sign_data'] = $agreement_sign_data = $agreement_sign_result['data'];
        if($agreement_sign_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        $data['project_sk'] = $project_sk = $agreement_sign_data[0]->project_sk;
        $data['first_signer_sk'] = $first_signer_sk = $agreement_sign_data[0]->first_signer_sk;
        $data['second_signer_sk'] = $second_signer_sk = $agreement_sign_data[0]->second_signer_sk;
        $data['second_signer_embed_url'] = $second_signer_embed_url = $agreement_sign_data[0]->second_signer_embed_url;
        $data['agreement_contract_name'] = $agreement_contract_name = $agreement_sign_data[0]->agreement_contract_name;
        
        $update_agreement_sign['first_signer_viewed'] = '1';
        $update_agreement_sign['first_signer_signed'] = '1';
        $update_agreement_sign['first_signer_document_sign_date'] = date('Y-m-d H:i:s');
        $update_agreement_sign['first_signer_document_sign_status'] = 'Signed';
        $update_agreement_sign['modified_by'] = $first_signer_sk;
        $update_agreement_sign['modified_on'] = date('Y-m-d H:i:s');
        $this->project_model->fn_update_agreement_sign($update_agreement_sign,$agreement_sign_sk);
        
        /*start send mail to second signer for document sign */
        $result = $this->users_model->fn_get_all_site_constants();
        $data['get_all_site_constants_record_count'] = $get_all_site_constants_record_count = $result['rows'];
        $data['q_get_all_site_constants'] = $q_get_all_site_constants = $result['data'];
        
        $first_signer_result = $this->users_model->fn_get_user_detail_by_users_sk($first_signer_sk);
        $first_signer_record_count = $first_signer_result['rows'];
        $get_first_signer_data = $first_signer_result['data'];
        $first_signer_email = $get_first_signer_data[0]->email;
        $first_signer_fullname = $get_first_signer_data[0]->first_name.' '.$get_first_signer_data[0]->last_name;
        
        $second_signer_result = $this->users_model->fn_get_user_detail_by_users_sk($second_signer_sk);
        $second_signer_record_count = $second_signer_result['rows'];
        $get_second_signer_data = $second_signer_result['data'];                
        $second_signer_email = $get_second_signer_data[0]->email;
        $second_signer_fullname = $get_second_signer_data[0]->first_name.' '.$get_second_signer_data[0]->last_name;
        
        
        $from_email = $data['q_get_all_site_constants'][0]->from_email;
        $from_name = $data['q_get_all_site_constants'][0]->from_name;
        $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
        $to_email = $second_signer_email;
        $subject = 'Signature request by '.$first_signer_fullname;
        $message = $this->general->fn_email_second_agreement_signer_mail_html($agreement_contract_name,$first_signer_fullname,$second_signer_embed_url);        
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
        /*end send mail to second signer for document sign */
        
        $redirect_url = $this->config->item('site_url').'project-execution/'.$project_sk;
        $ret_data["success"] = "Great! You've successfully signed the contract. We have sent the signed copy to the client for their signature.";
        fun_redirect($redirect_url,$ret_data);
        exit;
    }
    
    function fn_set_first_signer_declined_agreement(){
        $agreement_sign_sk = $this->uri->segment('2');
        $first_signer_sk = $this->uri->segment('3');
        
        $agreement_sign_result = $this->project_model->fn_get_agreement_sign_detail($agreement_sign_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['agreement_sign_data'] = $agreement_sign_data = $agreement_sign_result['data'];
        if($agreement_sign_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        $data['project_sk'] = $project_sk = $agreement_sign_data[0]->project_sk;
        
        $update_agreement_sign['first_signer_viewed'] = '1';
        $update_agreement_sign['first_signer_signed'] = '0';        
        $update_agreement_sign['first_signer_document_decline_date'] = date('Y-m-d H:i:s');
        $update_agreement_sign['first_signer_document_sign_status'] = 'Declined';
        $update_agreement_sign['modified_by'] = $first_signer_sk;
        $update_agreement_sign['modified_on'] = date('Y-m-d H:i:s');
        $this->project_model->fn_update_agreement_sign($update_agreement_sign,$agreement_sign_sk);
        
        $redirect_url = $this->config->item('site_url').'project-execution/'.$project_sk;
        $ret_data["errors"] = "You've declined the contract.";        
        fun_redirect($redirect_url,$ret_data);
        exit;            
    }
    
    function fn_set_second_signer_signed_agreement(){
        $agreement_sign_sk = $this->uri->segment('2');
        $second_signer_sk = $this->uri->segment('3');
        
        $agreement_sign_result = $this->project_model->fn_get_agreement_sign_detail($agreement_sign_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['agreement_sign_data'] = $agreement_sign_data = $agreement_sign_result['data'];
        if($agreement_sign_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        $data['project_sk'] = $project_sk = $agreement_sign_data[0]->project_sk;
        
        $update_agreement_sign['second_signer_viewed'] = '1';
        $update_agreement_sign['second_signer_signed'] = '1';
        $update_agreement_sign['second_signer_document_sign_date'] = date('Y-m-d H:i:s');
        $update_agreement_sign['second_signer_document_sign_status'] = 'Signed';
        $update_agreement_sign['modified_by'] = $second_signer_sk;
        $update_agreement_sign['modified_on'] = date('Y-m-d H:i:s');
        $this->project_model->fn_update_agreement_sign($update_agreement_sign,$agreement_sign_sk);
        
        $redirect_url = $this->config->item('site_url').'project-execution/'.$project_sk;
        $ret_data["success"] = "Great! Both the parties have successfully signed the contract.";
        fun_redirect($redirect_url,$ret_data);
        exit;        
    }
    
    function fn_set_second_signer_declined_agreement(){
        $agreement_sign_sk = $this->uri->segment('2');
        $second_signer_sk = $this->uri->segment('3');
        
        $agreement_sign_result = $this->project_model->fn_get_agreement_sign_detail($agreement_sign_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['agreement_sign_data'] = $agreement_sign_data = $agreement_sign_result['data'];
        if($agreement_sign_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        $data['project_sk'] = $project_sk = $agreement_sign_data[0]->project_sk;
        
        $update_agreement_sign['second_signer_viewed'] = '1';        
        $update_agreement_sign['second_signer_document_decline_date'] = date('Y-m-d H:i:s');
        $update_agreement_sign['second_signer_document_sign_status'] = 'Declined';
        $update_agreement_sign['modified_by'] = $second_signer_sk;
        $update_agreement_sign['modified_on'] = date('Y-m-d H:i:s');
        $this->project_model->fn_update_agreement_sign($update_agreement_sign,$agreement_sign_sk); 
        
        $redirect_url = $this->config->item('site_url').'project-execution/'.$project_sk;
        $ret_data["errors"] = "You've declined the contract.";        
        fun_redirect($redirect_url,$ret_data);
        exit;   
    }
                
}
