<?php

class Users_model extends CI_Model {

    function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
    function fn_get_all_site_constants() { 
        $this->db->select('*');
		$this->db->from('site_constants');
        $this->db->where('site_constants_id','1'); 
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();                    		
		#echo $this->db->last_query(); 
		return $result;
	}
    
    function fn_get_user_detail_by_email($email) {
        $this->db->select('*');
		$this->db->from('users');
        $this->db->where('email',$email);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		#echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_user_detail_by_users_sk($users_sk) {
        $this->db->select('*');
		$this->db->from('users');
        $this->db->where('users_sk',$users_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_company_detail_by_company_sk($company_sk) {
        $this->db->select('*');
		$this->db->from('company');
        $this->db->where('company_sk',$company_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_user_company_detail_by_users_sk($users_sk) {
        $this->db->select('*');
		$this->db->from('company');
        $this->db->where('users_sk',$users_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_check_user_email_exists($email,$users_sk) {
        $this->db->select('*');
		$this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('users_sk != ',$users_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_check_login($sign_in_email,$sign_in_password) {
        $this->db->select('*');
		$this->db->from('users');
        $this->db->where('email',$sign_in_email);
        $this->db->where('password',md5($sign_in_password));
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query();exit; 
		return $result;
    }
    
	function fn_insert_signup() {
        $users_sk = $this->general->generate_primary_key();
		$record = array(
            'users_sk'=>$users_sk,
            'email'=>$this->input->post('email'),
            'first_name'=>$this->input->post('first_name'),
            'password'=>md5($this->input->post('password')),
            'is_active'=>'1',
            'created_by'=>$users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('users', $record);
                
        /*$update_users_record = array(
            'created_by'=>$users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));*/
        
        $company_sk = $this->general->generate_primary_key();
        $company_insert_record = array(
            'company_sk'=>$company_sk,
            'users_sk'=>$users_sk,
            'company_name'=>$this->input->post('company_name'),
            'contact_person_name'=>$this->input->post('first_name'),
            'created_by'=>$users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('company', $company_insert_record);
                
		return $users_sk;
	}

    function fn_update_activate_user_account($users_sk){
        $update_users_record = array(
            'is_active'=>'1',
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_update_user_profile(){
        $user_email = $this->input->post('email'); 
        $user_first_name = $this->input->post('first_name');
        $users_sk = $this->input->post('users_sk');
            
        $update_users_record = array(
            'email'=>$user_email,
            'first_name'=>$user_first_name,
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_update_company_profile(){
        $company_name = $this->input->post('company_name'); 
        $contact_person_name = $this->input->post('contact_person_name');
        $users_sk = $this->input->post('users_sk');
            
        $update_company_record = array(
            'company_name'=>$company_name,
            'contact_person_name'=>$contact_person_name,
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('company', $update_company_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_update_user_image($user_image,$users_sk){
        $update_users_record = array(
            'user_image'=>$user_image,
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_update_company_image($company_image,$users_sk){
        $update_company_record = array(
            'company_image'=>$company_image,
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('company', $update_company_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_get_all_users($users_sk='') {
        $this->db->select('*');
		$this->db->from('users');
        if($users_sk != '' && !empty($users_sk)){
            $this->db->where('users_sk != ',$users_sk);
        }   
        $this->db->where('is_active','1');         
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_all_users_for_create_project($users_sk='') {
        $this->db->select('users.*,company.company_name,company.company_sk');
		$this->db->from('users');
        $this->db->join('company','company.users_sk = users.users_sk','inner');
        if($users_sk != '' && !empty($users_sk)){
            $this->db->where('users.users_sk != ',$users_sk);
        }   
        $this->db->where('is_active','1');         
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_all_users_for_project_filter($users_sk='',$user_login_mode) {
        $this->db->select('users.*,company.company_name,company.company_sk,company.company_image');
		$this->db->from('users');
        $this->db->join('company','company.users_sk = users.users_sk','inner');
        if($user_login_mode == 'AGENCY'){
            $this->db->join('clusters','clusters.client_sk = users.users_sk','inner');
            $this->db->where('clusters.agency_sk',$users_sk);
        }else{
            $this->db->join('clusters','clusters.agency_sk = users.users_sk','inner');
            $this->db->where('clusters.client_sk',$users_sk);                
        }
        if($users_sk != '' && !empty($users_sk)){
            $this->db->where('users.users_sk != ',$users_sk);
        }   
        #$this->db->where('is_active','1');
        $this->db->group_by('users.users_sk');         
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_payment_method_detail_by_users_sk($users_sk) {
        $this->db->select('*');
        $this->db->from('payment_methods');
        $this->db->where('users_sk',$users_sk);
        $query = $this->db->get();
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();     
        return $result;
    }

    function fn_insert_payment_method() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $payment_sk = $this->general->generate_primary_key();
        
        $record = array(
            'payment_sk' => $payment_sk,
            'users_sk' => $logged_in_users_sk,
            'card_number' => $this->input->post('card_number'),
            'expiry_month' => $this->input->post('expiry_month'),
            'expiry_year' => $this->input->post('expiry_year'),
            'cvc' => $this->input->post('cvc'),
            'name_on_card'=>$this->input->post('name_on_card'),
            'created_by' => $logged_in_users_sk,
            'created_on' => date('Y-m-d H:i:s'),
            'modified_by' => $logged_in_users_sk,
            'modified_on' => date('Y-m-d H:i:s')
        );        

        $query = $this->db->insert('payment_methods', $record);
                         
        return $payment_sk;
    }          

    function fn_delete_payment_method() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $payment_sk = $this->input->get('payment_sk');   
        $this->db->where('payment_sk', $payment_sk);
        $this->db->where('users_sk', $logged_in_users_sk);
        $this->db->delete("payment_methods");
        return $payment_sk;
    }

    function fn_get_bank_method_detail_by_users_sk($users_sk) {
        $this->db->select('*');
        $this->db->from('bank_account');
        $this->db->where('users_sk',$users_sk);
        $query = $this->db->get();
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();     
        return $result;
    }

    function fn_insert_bank_account() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $bank_sk = $this->general->generate_primary_key();
        
        $record = array(
            'bank_sk' => $bank_sk,
            'users_sk' => $logged_in_users_sk,
            'account_number' => $this->input->post('account_number'),
            'bank_name' => $this->input->post('bank_name'),
            'account_name' => $this->input->post('account_name'),
            'account_type' => "Individual",
            'currency'=>"USD",
            'created_by' => $logged_in_users_sk,
            'created_on' => date('Y-m-d H:i:s'),
            'modified_by' => $logged_in_users_sk,
            'modified_on' => date('Y-m-d H:i:s')
        );        

        $query = $this->db->insert('bank_account', $record);
                         
        return $bank_sk;
    }          

    function fn_delete_bank_account() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $bank_sk = $this->input->get('bank_sk');   
        $this->db->where('bank_sk', $bank_sk);
        $this->db->where('users_sk', $logged_in_users_sk);
        $this->db->delete("bank_account");
        return $bank_sk;
    }

    function fn_update_payent_method(){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $payment_sk = $this->input->get('payment_sk');
        
        $update_payment_record = array(
            'is_active'=>0,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $this->db->where('users_sk', $logged_in_users_sk);
        $query = $this->db->update('payment_methods', $update_payment_record);

        $update_payment_record = array(
            'is_active'=>1,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $this->db->where('payment_sk', $payment_sk);
        $this->db->where('users_sk', $logged_in_users_sk);
        $query = $this->db->update('payment_methods', $update_payment_record);  
        
    }

    function fn_update_bank_account(){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $bank_sk = $this->input->get('bank_sk');
        
        $update_account_record = array(
            'is_active'=>0,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $this->db->where('users_sk', $logged_in_users_sk);
        $query = $this->db->update('bank_account', $update_account_record);

        $update_account_record = array(
            'is_active'=>1,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $this->db->where('bank_sk', $bank_sk);
        $this->db->where('users_sk', $logged_in_users_sk);
        $query = $this->db->update('bank_account', $update_account_record);    
    }

    function fn_get_transactions_detail_by_users_sk($users_sk) {
        $this->db->select('*');
        $this->db->from('transactions');
        $this->db->where('users_sk',$users_sk);
        $query = $this->db->get();
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();     
        return $result;
    }

    function fn_insert_debit_transactions() {
        $logged_in_users_sk = $this->session->userdata('fe_user');

        $this->db->select('*');
        $this->db->from('bank_account');
        $this->db->where('users_sk',$logged_in_users_sk);
        $this->db->where('is_active',"1");
        $query = $this->db->get();
        $numrow = $query->num_rows();
        $bankresult = $query->result();

        if ($numrow > 0) {
            $transaction_sk = $this->general->generate_primary_key();
            
            $record = array(
                'transactions_sk' => $transaction_sk,
                'users_sk' => $logged_in_users_sk,
                'acc_card_number' => $bankresult[0]->account_number,
                'name' => $bankresult[0]->account_name,
                'amount' => "2400.00",
                'transaction_type'=>"Debit",
                'created_by' => $logged_in_users_sk,
                'created_on' => date('Y-m-d H:i:s'),
                'modified_by' => $logged_in_users_sk,
                'modified_on' => date('Y-m-d H:i:s')
            );        

            $query = $this->db->insert('transactions', $record);
                             
            return 1;
        } else {
            return 0;
        }
    }

    function fn_insert_credit_transactions() {
        $logged_in_users_sk = $this->session->userdata('fe_user');

        $this->db->select('*');
        $this->db->from('payment_methods');
        $this->db->where('users_sk',$logged_in_users_sk);
        $this->db->where('is_active',"1");
        $query = $this->db->get();
        $numrow = $query->num_rows();
        $bankresult = $query->result();

        if ($numrow > 0) {
            $transaction_sk = $this->general->generate_primary_key();
            
            $record = array(
                'transactions_sk' => $transaction_sk,
                'users_sk' => $logged_in_users_sk,
                'acc_card_number' => $bankresult[0]->card_number,
                'name' => $bankresult[0]->name_on_card,
                'amount' => "2400.00",
                'transaction_type'=>"Credit",
                'created_by' => $logged_in_users_sk,
                'created_on' => date('Y-m-d H:i:s'),
                'modified_by' => $logged_in_users_sk,
                'modified_on' => date('Y-m-d H:i:s')
            );        

            $query = $this->db->insert('transactions', $record);
                             
            return 1;
        } else {
            return 0;
        }
    }
    
    function fn_insert_dial_code($insert_record) {                
        $query = $this->db->insert('dial_code', $insert_record);                
		return true;
	}
    
    function fn_get_all_dial_code() { 
        $this->db->select('*');
		$this->db->from('dial_code');
        $this->db->order_by('dial_code.dial_code_country_name ASC'); 
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();                    		
		#echo $this->db->last_query(); 
		return $result;
	}
    
    function fn_insert_new_signup($insert_record) {                
        $query = $this->db->insert('users', $insert_record);                
		return true;
	}
    
    function fn_update_user_record_by_email($update_users_record,$user_email){
        $query = $this->db->update('users', $update_users_record, array('email'=>$user_email));
        return true;    
    }
    
    function fn_insert_company_signup($insert_record) {                
        $query = $this->db->insert('company', $insert_record);                
		return true;
	}
    
    function fn_update_company_detail($update_company_record,$company_sk){
        $query = $this->db->update('company', $update_company_record, array('company_sk'=>$company_sk));
        return true;    
    }
    
    function fn_update_profile_detail(){
        $user_first_name = $this->input->post('first_name');
        $user_last_name = $this->input->post('last_name');
        $users_sk = $this->input->post('users_sk');
            
        $update_users_record = array(
            'first_name'=>$user_first_name,
            'last_name'=>$user_last_name,
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_update_password(){
        $password = $this->input->post('new_password_field');
        $users_sk = $this->input->post('change_password_users_sk');
            
        $update_users_record = array(
            'password'=>md5($password),
            'modified_by'=>$users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('users', $update_users_record, array('users_sk'=>$users_sk));    
    }
    
    function fn_get_company_detail_by_name($company_name,$users_sk='') {
        $this->db->select('*');
		$this->db->from('company');
        $this->db->where('company_name',strtolower($company_name));
        if(!empty($users_sk) && $users_sk != ''){
            $this->db->where('users_sk != ',$users_sk);
        }            
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		#echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_delete_user($users_sk){
        $data = array('users_sk'=>$users_sk);
		$result = $this->db->delete('users', $data);
        $result = $this->db->delete('company', $data);
    }
    
    function fn_update_user_detail($update_user_record,$users_sk){
        $query = $this->db->update('users', $update_user_record, array('users_sk'=>$users_sk));
        return true;    
    }
                            
}

?>