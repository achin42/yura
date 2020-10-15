<?php

class Admin_users_model extends CI_Model {

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
    
    function fn_check_login_process() {
		$this->db->select('admin_users.*');
		$this->db->where('admin_users.is_active',1);
		$this->db->where('admin_users.email',$this->input->post('sign_in_email'));
		$this->db->where('admin_users.password',md5($this->input->post('password')));
		$this->db->from('admin_users');
		$query=$this->db->get();
		$result['rows']=$query->num_rows();
		$result['data']=$query->result();
		#echo $this->db->last_query();exit;
		return $result;
    }
    
    function fn_get_admin_user_detail($sess_admin_users_sk){
        $this->db->select('*');
        $this->db->where('admin_users.admin_users_sk',$sess_admin_users_sk);
        $this->db->from('admin_users');
        $query = $this->db->get();
        #echo $this->db->last_query(); 
		#exit;
        $result['rows'] = $query->num_rows();        
        $result['data'] = $query->result();				
		return $result;
    }
    
    function fn_get_all_users_count($params = array()){
        $this->db->select('count(*) as curows');
        $this->db->from('users AS U');
        $this->db->join('company','company.users_sk=U.users_sk');
        //filter data by searched keywords
        if(!empty($params['search']['search_users_keyword'])){
            if($params['search']['search_users_keyword']) {
                $this->db->where('(U.first_name LIKE "%'.$params['search']['search_users_keyword'].'%" OR U.last_name LIKE "%'.$params['search']['search_users_keyword'].'%")');
            }
        }
        if(!empty($params['search']['search_users_sk'])){
            if($params['search']['search_users_sk']) {
                $this->db->where('U.created_by = "'.$params['search']['search_users_sk'].'" ');
            }
        } 
        if($params['search']['filter_agency'] == 'auto_approved') {
			$this->db->where("company.is_agency_verified","1");
            $this->db->where("company.applied_for_manual_verification","0");
		}elseif($params['search']['filter_agency'] == 'manually_approved') {
			$this->db->where("company.is_agency_verified","1");
            $this->db->where("company.applied_for_manual_verification","1");
		}elseif($params['search']['filter_agency'] == 'rejected') {
			$this->db->where("company.is_agency_verified","2");
		}elseif($params['search']['filter_agency'] == 'pending') {
			$this->db->where("company.is_agency_verified","0");
		}else{
            
        }
        $this->db->where("(U.user_type = 'agency' OR U.user_type = 'both')");
        $this->db->order_by('U.created_on','DESC');	
		$query=$this->db->get();
		$result=$query->result();
		//echo $this->db->last_query(); 
		//exit;
		return $result[0]->curows;
	}
    
    function fn_get_all_users($params = array()){
		$this->db->select('U.*,company.company_sk,company.company_name,company.company_domain,company.chamber_of_commerce_number,company.vat_number,company.country_name,company.is_agency_verified,company.verify_later,company.applied_for_manual_verification,company.reason_for_rejection');
        $this->db->from('users AS U');
		$this->db->join('company','company.users_sk=U.users_sk');
		//filter data by searched keywords
        if(!empty($params['search']['search_users_keyword'])){
            if($params['search']['search_users_keyword']) {
                $this->db->where('(U.first_name LIKE "%'.$params['search']['search_users_keyword'].'%" OR U.last_name LIKE "%'.$params['search']['search_users_keyword'].'%")');
            }
        }            
        if(!empty($params['search']['search_users_sk'])){
            if($params['search']['search_users_sk']) {
                $this->db->where('U.created_by = "'.$params['search']['search_users_sk'].'" ');
            }
        } 
        if($params['search']['filter_agency'] == 'auto_approved') {
			$this->db->where("company.is_agency_verified","1");
            $this->db->where("company.applied_for_manual_verification","0");
		}elseif($params['search']['filter_agency'] == 'manually_approved') {
			$this->db->where("company.is_agency_verified","1");
            $this->db->where("company.applied_for_manual_verification","1");
		}elseif($params['search']['filter_agency'] == 'rejected') {
			$this->db->where("company.is_agency_verified","2");
		}elseif($params['search']['filter_agency'] == 'pending') {
			$this->db->where("company.is_agency_verified","0");
		}else{
            
        }
        $this->db->where("(U.user_type = 'agency' OR U.user_type = 'both')");
        
		//sort data by ascending or desceding order
		if(!empty($params['search']['sort_by']) && !empty($params['search']['sort_order'])){
			$this->db->order_by($params['search']['sort_by'],$params['search']['sort_order']);	
		}else{
			$this->db->order_by('U.created_on','DESC');
		}
		//$this->db->order_by('date_open','DESC');
		//set start and limit
		if($params['limit'] != 'all')
		{
			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
				$this->db->limit($params['limit'],$params['start']);
			}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
				$this->db->limit($params['limit']);
			}
		}
			
		$query=$this->db->get();
		#echo $this->db->last_query(); 
		#exit;
		//return ($query->num_rows() > 0)?$query->result_array():FALSE;
		$result['rows']=$query->num_rows();
		$result['data']=$query->result();
		return $result;
	}
    
    function fn_approve_agency_company($company_sk) {
        $reason_for_rejection= '';
        $record = array( 'is_agency_verified'=>'1','admin_agency_approved_date'=>date('Y-m-d H:i:s'),'reason_for_rejection'=>$reason_for_rejection,'modified_by'=>'1','modified_on'=>date('Y-m-d H:i:s') );
        $query = $this->db->update('company', $record, array('company_sk'=>$company_sk));
        
        return $company_sk;
	}
    
    function fn_reject_agency_company($company_sk,$reason_for_rejection) {
        $record = array( 'is_agency_verified'=>'2','admin_agency_rejected_date'=>date('Y-m-d H:i:s'),'reason_for_rejection'=>$reason_for_rejection,'modified_by'=>'1','modified_on'=>date('Y-m-d H:i:s') );
        $query = $this->db->update('company', $record, array('company_sk'=>$company_sk));
        
        return $company_sk;
	}
    
    function fn_get_admin_user_and_company_detail($company_sk){
        $this->db->select('users.users_sk,users.first_name,users.last_name,users.email,company.company_name');
        $this->db->from('company');
        $this->db->join('users','users.users_sk=company.users_sk');
        $this->db->where('company.company_sk',$company_sk);
        $query = $this->db->get();
        #echo $this->db->last_query(); 
		#exit;
        $result['rows'] = $query->num_rows();        
        $result['data'] = $query->result();				
		return $result;
    }
                
}

?>