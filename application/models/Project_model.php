<?php

class Project_model extends CI_Model {

    function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
    function fn_insert_cluster() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $sel_client = $this->input->post('sel_client');
        $new_client_email = $this->input->post('new_client_email');
        $new_client_company_name = $this->input->post('new_client_company_name');
        $cluster_title = $this->input->post('cluster_title');
        $contact_person_name = $this->input->post('contact_person_name');
        #$color_code = '#4285F4';
        $color_code = $this->general->get_random_color_code();
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        $invite_by_user_name = $this->session->userdata('fe_first_name').' '.$this->session->userdata('fe_last_name');
        
        if($user_login_mode == 'AGENCY'){
            if($sel_client == '' && $new_client_email != '' && $new_client_company_name != ''){
                /*insert new client here as a invited*/
                $client_sk = $users_sk = $this->general->generate_primary_key();
                $user_record = array(
                    'users_sk'=>$users_sk,
                    'email'=>$new_client_email,
                    'user_type'=>'client',
                    'is_active'=>'0',
                    'is_invited'=>'1',            
                    'created_by'=>$logged_in_users_sk,
                    'created_on'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->insert('users', $user_record);
                
                $company_sk = $this->general->generate_primary_key();
                $company_record = array(
                    'company_sk'=>$company_sk,
                    'users_sk'=>$users_sk,
                    'company_name'=>$new_client_company_name,
                    'contact_person_name'=>$contact_person_name,
                    'created_by'=>$logged_in_users_sk,
                    'created_on'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->insert('company', $company_record);
                
                $result = $this->users_model->fn_get_all_site_constants();
				$data['get_all_site_constants_record_count'] = $result['rows'];
				$data['q_get_all_site_constants'] = $result['data'];
                
                $from_email = $data['q_get_all_site_constants'][0]->from_email;
                $from_name = $data['q_get_all_site_constants'][0]->from_name;
                $send_grid_api_key = $data['q_get_all_site_constants'][0]->send_grid_api_key;
                $to_email = $new_client_email;
                $subject = $from_name.' | Invitation to Join';
                $message = $this->general->fn_email_invited_user_mail_html($to_email,$invite_by_user_name,$cluster_title);
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
            }else{
                $client_sk = $sel_client;
            }    
            $agency_sk = $logged_in_users_sk;
        }else{
            $client_sk = $logged_in_users_sk;
            $agency_sk = $sel_client;
        }
        
        $cluster_sk = $this->general->generate_primary_key();
		$record = array(
            'cluster_sk'=>$cluster_sk,
            'client_sk'=>$client_sk,
            'agency_sk'=>$agency_sk,
            'cluster_title'=>$cluster_title,
            'contact_person_name'=>$contact_person_name,
            'color_code'=>$color_code,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('clusters', $record);
		return $cluster_sk;
	}
    
    function fn_update_cluster_image($cluster_image,$cluster_sk){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $update_project_record = array(
            'cluster_image'=>$cluster_image,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );
        $query = $this->db->update('clusters', $update_project_record, array('cluster_sk'=>$cluster_sk));    
    }
    
    function fn_get_all_clusters_list($user_login_mode='',$filter_via_company='') {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        if($user_login_mode == 'AGENCY'){
            $this->db->where('agency_sk',$logged_in_users_sk);
        }else{
            $this->db->where('client_sk',$logged_in_users_sk);
        }
        if($filter_via_company != ''){
            if($user_login_mode == 'AGENCY'){
                $this->db->where('client_sk',$filter_via_company);
            }else{
                $this->db->where('agency_sk',$filter_via_company);
            }    
        }
        $this->db->select('clusters.*');
		$this->db->from('clusters');
        $this->db->order_by('clusters.created_on DESC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_delete_cluster($cluster_sk){
        $agreement_result = $this->fn_get_project_list_by_cluster_sk($cluster_sk);
        $agreement_rows = $agreement_result['rows']; 
        $q_agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            foreach($q_agreement_data as $agreement_data){
                $project_sk = $agreement_data->project_sk;
                $this->fn_delete_project($project_sk);     
            }
        }                
        $data = array('cluster_sk'=>$cluster_sk);
		$result = $this->db->delete('clusters', $data);
    }
    
    function fn_get_cluster_detail_by_cluster_sk($cluster_sk) {
        $this->db->select('*');
		$this->db->from('clusters');
        $this->db->where('cluster_sk',$cluster_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_insert_project() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $result = $this->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $default_add_day_in_agreement_start_date_for_end_date = $q_get_all_site_constants[0]->default_add_day_in_agreement_start_date_for_end_date;
        $default_add_day_in_phase_start_date_for_end_date = $q_get_all_site_constants[0]->default_add_day_in_phase_start_date_for_end_date;
        if($default_add_day_in_agreement_start_date_for_end_date != '' && !empty($default_add_day_in_agreement_start_date_for_end_date)){
  			$add_day_in_agreement_start_date_for_end_date = $default_add_day_in_agreement_start_date_for_end_date;
  		}else{
  			$add_day_in_agreement_start_date_for_end_date = '15';
  		}
        if($default_add_day_in_phase_start_date_for_end_date != '' && !empty($default_add_day_in_phase_start_date_for_end_date)){
  			$add_day_in_phase_start_date_for_end_date = $default_add_day_in_phase_start_date_for_end_date;
  		}else{
  			$add_day_in_phase_start_date_for_end_date = '15';
  		}
        $agreement_start_date = date('Y-m-d');
        $agreement_end_date = date('Y-m-d', strtotime($agreement_start_date. ' + '.$add_day_in_agreement_start_date_for_end_date.' days')); 
        $phase_start_date = date('Y-m-d');
        $phase_end_date = date('Y-m-d', strtotime($phase_start_date. ' + '.$add_day_in_phase_start_date_for_end_date.' days'));
        
        $project_sk = $this->general->generate_primary_key();
		$record = array(
            'project_sk'=>$project_sk,
            'cluster_sk'=>$this->input->post('cluster_sk'),
            'project_name'=>$this->input->post('project_name'),
            'project_status'=>'not_started',
            'start_date'=>$agreement_start_date,
            'end_date'=>$agreement_end_date,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('projects', $record);
        
        $phase_position = '1';
        $phase_sk = $this->general->generate_primary_key();
		$record = array(
            'phase_sk'=>$phase_sk,
            'cluster_sk'=>$this->input->post('cluster_sk'),
            'project_sk'=>$project_sk,
            'phase_name'=>$this->input->post('phase_name'),
            'phase_position'=>$phase_position,
            'phase_status'=>'not_started',
            'start_date'=>$phase_start_date,
            'end_date'=>$phase_end_date,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('phases', $record);
        
		return $project_sk;
	}
    
    function fn_get_project_detail_by_project_sk($project_sk) {
        $this->db->select('*');
		$this->db->from('projects');
        $this->db->where('project_sk',$project_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_project_list_by_cluster_sk($cluster_sk) {
        $this->db->select('*');
		$this->db->from('projects');
        $this->db->where('cluster_sk',$cluster_sk);
        $this->db->order_by('created_on ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_all_phases_by_project($project_sk=''){
        $this->db->select('*');
		$this->db->from('phases');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->order_by('created_on ASC');
        $this->db->order_by('phase_position ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_all_blocks_by_project($project_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        //$this->db->order_by('created_on ASC');
        $this->db->order_by('start_date ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_delete_project($project_sk){
        $data = array('project_sk'=>$project_sk);
		$result = $this->db->delete('projects', $data);
        
        $data1 = array('project_sk'=>$project_sk);
		$result = $this->db->delete('phases', $data1);
        
        $data2 = array('project_sk'=>$project_sk);
		$result = $this->db->delete('blocks', $data2);
        
        $data3 = array('project_sk'=>$project_sk);
		$result = $this->db->delete('tasks', $data3);
    }
    
    function fn_insert_phase() {
        /*echo "<pre>";
        print_r($_POST);exit;*/
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        
        $result = $this->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $default_add_day_in_phase_start_date_for_end_date = $q_get_all_site_constants[0]->default_add_day_in_phase_start_date_for_end_date;
        if($default_add_day_in_phase_start_date_for_end_date != '' && !empty($default_add_day_in_phase_start_date_for_end_date)){
  			$add_day_in_phase_start_date_for_end_date = $default_add_day_in_phase_start_date_for_end_date;
  		}else{
  			$add_day_in_phase_start_date_for_end_date = '15';
  		}
        
        $last_phase_result = $this->fn_get_last_inserted_phase_by_project_sk($project_sk);
		$last_phase_rows = $last_phase_result['rows'];
		$last_phase_data = $last_phase_result['data'];
        if($last_phase_rows > 0){
            $last_phase_end_date = $last_phase_data[0]->end_date;
            $phase_start_date = date('Y-m-d', strtotime($last_phase_end_date. ' + 1 days')); 
        }else{
            $phase_start_date = date('Y-m-d');    
        }        
        $phase_end_date = date('Y-m-d', strtotime($phase_start_date. ' + '.$add_day_in_phase_start_date_for_end_date.' days'));
        
        
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $phase_start_date = date('Y-m-d', strtotime($start_date));
        $phase_end_date = date('Y-m-d', strtotime($end_date));
        
                
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        $cluster_sk = '';
        if($agreement_rows > 0){
            $cluster_sk = $agreement_data[0]->cluster_sk;
        }
		$phase_position = '1';
        $phase_sk = $this->general->generate_primary_key();
		$record = array(
            'phase_sk'=>$phase_sk,
            'cluster_sk'=>$cluster_sk,
            'project_sk'=>$project_sk,
            'phase_name'=>$this->input->post('phase_name'),
            'phase_position'=>$phase_position,
            'phase_status'=>'not_started',
            'start_date'=>$phase_start_date,
            'end_date'=>$phase_end_date,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('phases', $record);
        
        //update project end date        
        if($agreement_rows > 0){
            $real_agreement_start_date = $agreement_data[0]->start_date;
            $real_agreement_end_date = $agreement_data[0]->end_date;
            if(strtotime($real_agreement_start_date) > strtotime($phase_start_date)){
        		$update_agreement_record = array(
        			'start_date'=>$phase_start_date,
        			'modified_by'=>$logged_in_users_sk,
        			'modified_on'=>date('Y-m-d H:i:s')
        		);        
        		$query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
        	}
            if(strtotime($real_agreement_end_date) < strtotime($phase_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$phase_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
        }
        
		return $phase_sk;
	}
    
    function fn_get_all_pre_defined_block_type(){
        $this->db->select('*');
		$this->db->from('block_type');
        $this->db->order_by('display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_insert_block() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $block_type_sk = $this->input->post('block_type_sk');
        $task_type_code = $block_type_code = $this->input->post('block_type_code');
        $phase_sk = $this->input->post('phase_sk');
        
        $result = $this->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $default_add_day_in_block_start_date_for_end_date = $q_get_all_site_constants[0]->default_add_day_in_block_start_date_for_end_date;
        if($default_add_day_in_block_start_date_for_end_date != '' && !empty($default_add_day_in_block_start_date_for_end_date)){
  			$add_day_in_block_start_date_for_end_date = $default_add_day_in_block_start_date_for_end_date;
  		}else{
  			$add_day_in_block_start_date_for_end_date = '2';
  		}        
        $block_no = '1';    
        $last_block_result = $this->fn_get_last_inserted_block_by_phase_sk($phase_sk);
		$last_block_rows = $last_block_result['rows'];
		$last_block_data = $last_block_result['data'];
        if($last_block_rows > 0){
            $last_block_no = $last_block_data[0]->block_no;
            $last_block_end_date = $last_block_data[0]->end_date; 
            if($last_block_no != '' && !empty($last_block_no)){
                $block_no = $last_block_no + 1; 
            }
            $block_start_date = date('Y-m-d', strtotime($last_block_end_date. ' + 1 days'));
            $block_end_date = date('Y-m-d', strtotime($block_start_date. ' + '.$add_day_in_block_start_date_for_end_date.' days'));             
        }else{
            $phase_result = $this->fn_get_phase_detail_by_phase_sk($phase_sk);
    		$phase_rows = $phase_result['rows'];
    		$phase_data = $phase_result['data'];
            if($phase_rows > 0){
                $phase_start_date = $phase_data[0]->start_date;
                $block_start_date = date('Y-m-d', strtotime($phase_start_date));
                $block_end_date = date('Y-m-d', strtotime($block_start_date. ' + '.$add_day_in_block_start_date_for_end_date.' days'));     
            }else{
                $block_start_date = date('Y-m-d');
                $block_end_date = date('Y-m-d', strtotime($block_start_date. ' + '.$add_day_in_block_start_date_for_end_date.' days'));    
            }        
        }        
        
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        $cluster_sk = '';
        if($agreement_rows > 0){
            $cluster_sk = $agreement_data[0]->cluster_sk;
        }
        
        $block_sk = $this->general->generate_primary_key();
		$record = array(
            'block_sk'=>$block_sk,
            'block_no'=>$block_no,
            'cluster_sk'=>$cluster_sk,
            'project_sk'=>$project_sk,
            'phase_sk'=>$phase_sk,
            'block_type_sk'=>$block_type_sk,
            'block_title'=>ucfirst($block_type_code),
            'scheduled_start_date'=>$block_start_date,
            'scheduled_end_date'=>$block_end_date,
            'start_date'=>$block_start_date,
            'end_date'=>$block_end_date,
            'block_status'=>'not_started',
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('blocks', $record);
        
        //insert default task
        $default_task_type_result = $this->fn_get_all_task_type_by_task_type_code($task_type_code);
		$default_task_type_rows = $default_task_type_result['rows'];
		$q_default_task_type_data = $default_task_type_result['data'];
        if($default_task_type_rows > 0){
            foreach($q_default_task_type_data as $default_task_type_data){
                $tasks_sk = $this->general->generate_primary_key();
                $task_type_sk = $default_task_type_data->task_type_sk;
        		$record = array(
                    'tasks_sk'=>$tasks_sk,
                    'task_type_sk'=>$task_type_sk,
                    'cluster_sk'=>$cluster_sk,
                    'project_sk'=>$project_sk,
                    'phase_sk'=>$phase_sk,
                    'block_sk'=>$block_sk,
                    'created_by'=>$logged_in_users_sk,
                    'created_on'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->insert('tasks', $record);    
            }
        }        
        
        //update project end date
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            $real_agreement_start_date = $agreement_data[0]->start_date;
            $real_agreement_end_date = $agreement_data[0]->end_date;
            $real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;            
            if(strtotime($real_agreement_start_date) > strtotime($block_start_date)){
                $update_agreement_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
            if(strtotime($real_agreement_end_date) < strtotime($block_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            } 
        }
        
        //update phase end date
        $phase_result = $this->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $real_phase_start_date = $phase_data[0]->start_date;
            $real_phase_end_date = $phase_data[0]->end_date;
            /*if(strtotime($real_phase_start_date) > strtotime($block_start_date)){
                $update_phase_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }
            if(strtotime($real_phase_end_date) < strtotime($block_end_date)){
                $update_phase_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }*/
            /*$first_inserted_block_result = $this->fn_get_first_inserted_block_by_phase($phase_sk);
            $first_inserted_block_rows = $first_inserted_block_result['rows']; 
            $q_first_inserted_block_data = $first_inserted_block_result['data'];                
            if($first_inserted_block_rows > 0){
                $first_inserted_block_start_date = $q_first_inserted_block_data[0]->start_date; 
                $update_phase_record = array(
                    'start_date'=>$first_inserted_block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
            }
            $last_inserted_block_result = $this->fn_get_last_inserted_block_by_phase($phase_sk);
            $last_inserted_block_rows = $last_inserted_block_result['rows']; 
            $q_last_inserted_block_data = $last_inserted_block_result['data'];                
            if($last_inserted_block_rows > 0){
                $last_inserted_block_end_date = $q_last_inserted_block_data[0]->end_date; 
                //if(strtotime($last_inserted_block_end_date) > strtotime($block_end_date)){
                    $update_phase_record = array(
                        'end_date'=>$last_inserted_block_end_date,
                        'modified_by'=>$logged_in_users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );        
                    $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
                //}                    
            }*/               
        }
        
		return $block_sk;
	}
    
    function fn_get_block_detail_by_block_sk($block_sk) {
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color,block_type.description as block_type_description');
		$this->db->from('blocks');
        $this->db->where('block_sk',$block_sk);
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_update_block() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $block_sk = $this->input->post('block_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $block_title = $this->input->post('block_title');
        $block_description = $this->input->post('block_description');
        $contact_person = $this->input->post('contact_person');
        $payment_amount = $this->input->post('payment_amount');
        $payment_currency = $this->input->post('payment_currency');
        $task_cnt = $this->input->post('task_cnt');
        $first_task = $this->input->post('first_task');
        $second_task = $this->input->post('second_task'); 
        $task_sks_arr = $this->input->post('task_sks');
        $block_start_date = date('Y-m-d', strtotime($start_date));
        $block_end_date = date('Y-m-d', strtotime($end_date));
        $record = array(
            'block_title'=>$block_title,
            'block_description'=>$block_description,
            'contact_person'=>$contact_person,
            'scheduled_start_date'=>$block_start_date,
            'scheduled_end_date'=>$block_end_date,
            'start_date'=>$block_start_date,
            'end_date'=>$block_end_date,
            'payment_amount'=>$payment_amount,
            'payment_currency'=>$payment_currency,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('blocks', $record, array('block_sk'=>$block_sk));
        
        //update project end date
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            $real_agreement_start_date = $agreement_data[0]->start_date;
            $real_agreement_end_date = $agreement_data[0]->end_date;
            $real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;            
            if(strtotime($real_agreement_start_date) > strtotime($block_start_date)){
                $update_agreement_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
            if(strtotime($real_agreement_end_date) < strtotime($block_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }
            //update task ids
            if(!empty($real_agreement_cluster_sk) && $real_agreement_cluster_sk != ''){
                $project_result = $this->fn_get_cluster_detail_by_cluster_sk($real_agreement_cluster_sk);
        		$project_rows = $project_result['rows'];
        		$project_data = $project_result['data'];
                if($project_rows > 0){
                    $client_sk = $project_data[0]->client_sk;
                    $agency_sk = $project_data[0]->agency_sk;
                    if($task_cnt == '2'){
                        $first_task_sk = $task_sks_arr[0];
                        $second_task_sk = $task_sks_arr[1];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }    
                    }
                    if($task_cnt == '1'){
                        $first_task_sk = $task_sks_arr[0];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }    
                    }
                }                                
            }            
        }
        
        //update phase end date
        $phase_result = $this->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $real_phase_start_date = $phase_data[0]->start_date;
            $real_phase_end_date = $phase_data[0]->end_date;
            /*if(strtotime($real_phase_start_date) > strtotime($block_start_date)){
                $update_phase_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }
            if(strtotime($real_phase_end_date) < strtotime($block_end_date)){
                $update_phase_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }*/
            /*$first_inserted_block_result = $this->fn_get_first_inserted_block_by_phase($phase_sk);
            $first_inserted_block_rows = $first_inserted_block_result['rows']; 
            $q_first_inserted_block_data = $first_inserted_block_result['data'];                
            if($first_inserted_block_rows > 0){
                $first_inserted_block_start_date = $q_first_inserted_block_data[0]->start_date; 
                $update_phase_record = array(
                    'start_date'=>$first_inserted_block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
            }
            $last_inserted_block_result = $this->fn_get_last_inserted_block_by_phase($phase_sk);
            $last_inserted_block_rows = $last_inserted_block_result['rows']; 
            $q_last_inserted_block_data = $last_inserted_block_result['data'];                
            if($last_inserted_block_rows > 0){
                $last_inserted_block_end_date = $q_last_inserted_block_data[0]->end_date;
                //if(strtotime($last_inserted_block_end_date) > strtotime($block_end_date)){ 
                    $update_phase_record = array(
                        'end_date'=>$last_inserted_block_end_date,
                        'modified_by'=>$logged_in_users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );        
                    $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
                //}                    
            }*/               
        }
        #exit;
        return $block_sk;
	}
    
    function fn_get_phase_detail_by_phase_sk($phase_sk) {
        $this->db->select('*');
		$this->db->from('phases');
        $this->db->where('phase_sk',$phase_sk);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_all_blocks_by_phase_sk($phase_sk) {
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($phase_sk) && $phase_sk != ''){
            $this->db->where('phase_sk',$phase_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        //$this->db->order_by('created_on ASC');
        $this->db->order_by('start_date ASC');
        //$this->db->order_by('end_date ASC');
        $query = $this->db->get();
		$block_rows = $query->num_rows();
		$q_block_data = $query->result();		
		
        $new_calendar_block_arr = array();        
        if($block_rows > 0){
            $i = $j = $k = 0;
            $last_end_date = '';
            foreach($q_block_data as $block_data){
                $phase_sk = $block_data->phase_sk;
                $block_sk = $block_data->block_sk;
                $block_title = $block_data->block_title;
                $block_type = $block_data->block_type_code;
                $start_date = $block_data->start_date;
                $end_date = $block_data->end_date;
                $block_no = $block_data->block_no; 
                #echo $start_date.'##'.$last_end_date.'<br />';
                if(($start_date <= $last_end_date) && $k > 0){
                    $i++;
                    $j = 0;                        
                }else{
                    $i = 0;                
                }                
                if($k > 0){
                    /*if($phase_sk == '858d0cb9-c7e8-4a79-92f1-a62105ab3c25'){
                        echo $i."aa".$j;
                    }*/
                    if(!empty($new_calendar_block_arr[$phase_sk])){
                        if(!empty($new_calendar_block_arr[$phase_sk][$i])){
                            $j = count($new_calendar_block_arr[$phase_sk][$i]);                        
                        }
                    }else{
                        $j = 0;
                        $i = 0;
                    }     
                    /*if($phase_sk == '858d0cb9-c7e8-4a79-92f1-a62105ab3c25'){
                        echo $i."aa".$j;
                    }*/
                }
                $new_calendar_block_arr[$phase_sk][$i][$j]['block_sk'] = $block_sk;             
                $new_calendar_block_arr[$phase_sk][$i][$j]['block_title'] = $block_title;
                $new_calendar_block_arr[$phase_sk][$i][$j]['block_type'] = $block_type;
                $new_calendar_block_arr[$phase_sk][$i][$j]['start_date'] = $start_date;
                $new_calendar_block_arr[$phase_sk][$i][$j]['end_date'] = $end_date;
                $new_calendar_block_arr[$phase_sk][$i][$j]['block_no'] = $block_no;            
                $j++;
                $k++;
                if($last_end_date <= $end_date){
                    $last_end_date = $end_date;
                }else{
                    
                }                
                if($k == 3){
                    /*echo $last_end_date.'<br />';
                    print_r($new_calendar_block_arr);exit;*/
                }
            }
        }
        return $new_calendar_block_arr;
    }
    
    function fn_get_last_inserted_phase_by_project_sk($project_sk){
        $this->db->select('*');
		$this->db->from('phases');
        $this->db->where('project_sk',$project_sk);
        $this->db->order_by('created_on DESC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
                                                      

    function fn_get_all_task_type_by_task_type_code($task_type_code){
        $this->db->select('*');
		$this->db->from('task_type');
        $this->db->where('task_type_code',$task_type_code);
        $this->db->order_by('display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_all_task_by_block_sk($block_sk){
        $this->db->select('tasks.*,task_type.task_type_code,task_type.task_type_title,task_type.description,task_type.action_name,task_type.payment_action');
		$this->db->from('tasks');
        $this->db->join('task_type','task_type.task_type_sk = tasks.task_type_sk','inner');
        $this->db->where('block_sk',$block_sk);
        $this->db->order_by('task_type.display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
                                                            
    function fn_get_all_blocks_by_project_for_download($project_sk=''){
        $this->db->select('blocks.end_date,block_type.block_type_name');
        $this->db->from('blocks');
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');

        $this->db->where('project_sk',$project_sk);
         
        $blocktypecond = " (block_type.block_type_code = 'payment' OR  block_type.block_type_code = 'milestone')";    
        $this->db->where($blocktypecond);
        //$this->db->order_by('created_on ASC');
        $this->db->order_by('start_date ASC');
        $query = $this->db->get();
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();     
        //echo $this->db->last_query(); exit;
        return $result;    
    }
    
    function fn_get_last_inserted_block_by_phase_sk($phase_sk){
        $this->db->select('*');
		$this->db->from('blocks');
        $this->db->where('phase_sk',$phase_sk);
        $this->db->order_by('created_on DESC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fun_delete_block($block_sk){
        $data1 = array('block_sk'=>$block_sk);
		$result = $this->db->delete('blocks', $data1);
        
        $data2 = array('block_sk'=>$block_sk);
		$result = $this->db->delete('tasks', $data2);
    }
    
    function fn_update_phase() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $phase_sk = $this->input->post('phase_sk');
        
        $update_phase_record = array(
            'phase_name'=>$this->input->post('phase_name'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
        
		return $phase_sk;
	}
    
    function fun_delete_phase(){
        $phase_sk = $this->input->post('phase_sk');
        
        $data1 = array('phase_sk'=>$phase_sk);
		$result = $this->db->delete('phases', $data1);
        
        $data2 = array('phase_sk'=>$phase_sk);
		$result = $this->db->delete('blocks', $data2);
        
        $data3 = array('phase_sk'=>$phase_sk);
		$result = $this->db->delete('tasks', $data3);
    }
    
    function fn_get_phase_list_by_cluster_sk($cluster_sk) {
        $this->db->select('*');
		$this->db->from('phases');
        $this->db->where('cluster_sk',$cluster_sk);
        $this->db->order_by('created_on ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;
    }
    
    function fn_get_responsibilities_list_by_project_sk($project_sk, $assignee_users_sk, $user_type) {
        $this->db->select('blocks.start_date, blocks.end_date, blocks.block_title, blocks.block_description,task_type.task_type_title');
        $this->db->from('tasks');

        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','left');
        $this->db->join('task_type','task_type.task_type_sk = tasks.task_type_sk','left');

        $this->db->where('tasks.project_sk',$project_sk);
        $this->db->where('tasks.assignee_users_sk',$assignee_users_sk);
        $this->db->where('tasks.assignee_users_type',$user_type);
        
        $this->db->order_by('tasks.created_on ASC');
        $query = $this->db->get();
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }                    
    
    function fn_get_all_blocks_for_overdue_task_list(){
        $today_date = date('Y-m-d');
        $this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->from('blocks');

        $this->db->join('clusters','clusters.cluster_sk = blocks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = blocks.project_sk','INNER');
        $this->db->join('phases','phases.phase_sk = blocks.phase_sk','INNER');
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','INNER');
        
        $this->db->where('blocks.start_date <= ',$today_date);
        $this->db->where('blocks.end_date <= ',$today_date);        
                
        $this->db->order_by('blocks.end_date ASC');
        $query = $this->db->get();
        #echo $this->db->last_query();exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_blocks_for_active_task_list(){
        $today_date = date('Y-m-d');
        $this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->from('blocks');

        $this->db->join('clusters','clusters.cluster_sk = blocks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = blocks.project_sk','INNER');
        $this->db->join('phases','phases.phase_sk = blocks.phase_sk','INNER');
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','INNER');
        
        $this->db->where('blocks.start_date <= ',$today_date);
        $this->db->where('blocks.end_date >= ',$today_date);        
        
        $this->db->order_by('blocks.end_date ASC');
        $query = $this->db->get();
        #echo $this->db->last_query();exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_blocks_for_upcoming_task_list(){
        $today_date = date('Y-m-d');
        $this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->from('blocks');

        $this->db->join('clusters','clusters.cluster_sk = blocks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = blocks.project_sk','INNER');
        $this->db->join('phases','phases.phase_sk = blocks.phase_sk','INNER');
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','INNER');
        
        $this->db->where('blocks.start_date >= ',$today_date);
        $this->db->where('blocks.end_date >= ',$today_date);        
        
        $this->db->order_by('blocks.end_date DESC');
        $query = $this->db->get();
        #echo $this->db->last_query();exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_assigned_task_by_block_sk($block_sk){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        
        $this->db->select('tasks.*,task_type.task_type_code,task_type.task_type_title,task_type.description,task_type.action_name,task_type.payment_action,clusters.color_code,blocks.start_date as block_start_date,blocks.end_date as block_end_date,blocks.payment_amount as block_payment_amount,phases.phase_name');
		$this->db->from('tasks');
        $this->db->join('task_type','task_type.task_type_sk = tasks.task_type_sk','inner');
        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','inner');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','inner');
        $this->db->join('phases','phases.phase_sk = tasks.phase_sk','inner');
        $this->db->where('tasks.block_sk',$block_sk);
        $this->db->where('tasks.assignee_users_sk',$logged_in_users_sk);
        $this->db->where('tasks.assignee_users_type',strtolower($user_login_mode));
        $this->db->order_by('task_type.display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_check_all_blocks_completed_by_today($project_sk=''){
        $today_date = date('Y-m-d');
        //$today_date = '2020-06-01';
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->where('blocks.end_date >= ',$today_date);
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_first_block_of_project($project_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.start_date ASC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_update_execution_block() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $block_sk = $this->input->post('block_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $block_title = $this->input->post('block_title');
        $block_description = $this->input->post('block_description');
        $contact_person = $this->input->post('contact_person');
        $payment_amount = $this->input->post('payment_amount');
        $payment_currency = $this->input->post('payment_currency');
        $task_cnt = $this->input->post('task_cnt');
        $first_task = $this->input->post('first_task');
        $second_task = $this->input->post('second_task'); 
        $task_sks_arr = $this->input->post('task_sks');
        $block_start_date = date('Y-m-d', strtotime($start_date));
        $block_end_date = date('Y-m-d', strtotime($end_date));
        $record = array(
            'block_title'=>$block_title,
            'block_description'=>$block_description,
            'contact_person'=>$contact_person,
            'scheduled_start_date'=>$block_start_date,
            'scheduled_end_date'=>$block_end_date,
            'start_date'=>$block_start_date,
            'end_date'=>$block_end_date,
            'payment_amount'=>$payment_amount,
            'payment_currency'=>$payment_currency,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        //$query = $this->db->update('blocks', $record, array('block_sk'=>$block_sk));
        
        //update project end date
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            $real_agreement_end_date = $agreement_data[0]->end_date;
            $real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;            
            if(strtotime($real_agreement_end_date) < strtotime($block_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                //$query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
            //update task ids
            if(!empty($real_agreement_cluster_sk) && $real_agreement_cluster_sk != ''){
                $project_result = $this->fn_get_cluster_detail_by_cluster_sk($real_agreement_cluster_sk);
        		$project_rows = $project_result['rows'];
        		$project_data = $project_result['data'];
                if($project_rows > 0){
                    $client_sk = $project_data[0]->client_sk;
                    $agency_sk = $project_data[0]->agency_sk;
                    if($task_cnt == '2'){
                        $first_task_sk = $task_sks_arr[0];
                        $second_task_sk = $task_sks_arr[1];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }    
                    }
                    if($task_cnt == '1'){
                        $first_task_sk = $task_sks_arr[0];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }    
                    }
                }                                
            }            
        }
        
        //update phase end date
        $phase_result = $this->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $real_phase_end_date = $phase_data[0]->end_date;
            if(strtotime($real_phase_end_date) < strtotime($block_end_date)){
                $update_phase_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                //$query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }  
        }
        return $block_sk;
	}
    
    function fn_get_first_inserted_block_by_phase($phase_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($phase_sk) && $phase_sk != ''){
            $this->db->where('phase_sk',$phase_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.start_date ASC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_last_inserted_block_by_phase($phase_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($phase_sk) && $phase_sk != ''){
            $this->db->where('phase_sk',$phase_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.end_date DESC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_first_inserted_block_by_project_sk($project_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.start_date ASC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_last_inserted_block_by_project_sk($project_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.end_date DESC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_first_inserted_block_by_cluster($cluster_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($cluster_sk) && $cluster_sk != ''){
            $this->db->where('cluster_sk',$cluster_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.start_date ASC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_last_inserted_block_by_cluster($cluster_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($cluster_sk) && $cluster_sk != ''){
            $this->db->where('cluster_sk',$cluster_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.end_date DESC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_total_payment_of_cluster($cluster_sk=''){
        $this->db->select('SUM(payment_amount) as total_payment');
		$this->db->from('blocks');
        if(!empty($cluster_sk) && $cluster_sk != ''){
            $this->db->where('cluster_sk',$cluster_sk);
        }    
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		#echo $this->db->last_query(); 
		return $result;    
    }    
    
    function fn_get_total_payment_of_project($project_sk=''){
        $this->db->select('SUM(payment_amount) as total_payment');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    } 
    
    function fn_get_milestone_blocks_by_project_sk($project_sk=''){
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }   
        $this->db->where('block_type_code','milestone'); 
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        $this->db->order_by('blocks.start_date ASC');                
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_task_mark_as_completed($tasks_sk){
        $update_task_record = array(
            'task_done'=>'1',
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('tasks', $update_task_record, array('tasks_sk'=>$tasks_sk));
    }
    
    function fn_task_mark_as_incomplete($tasks_sk){
        $update_task_record = array(
            'task_done'=>'0',
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('tasks', $update_task_record, array('tasks_sk'=>$tasks_sk));
    }
    
    function fn_task_pay_now($tasks_sk,$payment_amount){
        $logged_in_users_sk = $this->session->userdata('fe_user');

        /*$this->db->select('*');
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
                'tasks_sk' => $tasks_sk,
                'users_sk' => $logged_in_users_sk,
                'acc_card_number' => $bankresult[0]->account_number,
                'name' => $bankresult[0]->account_name,
                'amount' => $payment_amount,
                'transaction_type'=>"Debit",
                'created_by' => $logged_in_users_sk,
                'created_on' => date('Y-m-d H:i:s'),
                'modified_by' => $logged_in_users_sk,
                'modified_on' => date('Y-m-d H:i:s')
            );        
            $query = $this->db->insert('transactions', $record);
            
            $update_task_record = array(
                'task_done'=>'1',
                'modified_by'=>$logged_in_users_sk,
                'modified_on'=>date('Y-m-d H:i:s')
            );        
            $query = $this->db->update('tasks', $update_task_record, array('tasks_sk'=>$tasks_sk));
                                    
            return 1;
        } else {
            return 0;
        }*/
        
        $update_task_record = array(
            'task_done'=>'1',
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('tasks', $update_task_record, array('tasks_sk'=>$tasks_sk));
                                
        return 1;
    }        
    
    function fn_task_unpaid($tasks_sk,$payment_amount){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $update_task_record = array(
            'task_done'=>'0',
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('tasks', $update_task_record, array('tasks_sk'=>$tasks_sk));
            
        /*$data = array('tasks_sk'=>$tasks_sk);
		$result = $this->db->delete('transactions', $data);*/
    }
    
    function fn_get_all_incomplete_task_by_project_sk($project_sk){
        $today_date = date('Y-m-d');
        //$this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');

        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        
        $this->db->where('blocks.start_date <= ',$today_date);
        $this->db->where('blocks.end_date < ',$today_date);        
        $this->db->where('tasks.task_done','0');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
                 
        $this->db->order_by('blocks.end_date ASC');
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_completed_task_by_project_sk($project_sk){
        $today_date = date('Y-m-d');
        //$this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');

        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        
        $this->db->where('blocks.start_date <= ',$today_date);
        $this->db->where('blocks.end_date < ',$today_date);        
        $this->db->where('tasks.task_done','1');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
                 
        $this->db->order_by('blocks.end_date ASC');
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_task_by_project_sk($project_sk){
        $today_date = date('Y-m-d');
        //$this->db->select('blocks.block_sk,blocks.block_no,blocks.cluster_sk,blocks.project_sk,blocks.phase_sk,blocks.block_type_sk,blocks.block_title,blocks.block_description,blocks.start_date,blocks.end_date,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,projects.project_name,phases.phase_name,block_type.block_type_name,block_type.block_type_code');
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');

        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        
        $this->db->where('blocks.start_date <= ',$today_date);
        $this->db->where('blocks.end_date < ',$today_date);        
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
                 
        $this->db->order_by('blocks.end_date ASC');
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_task_of_project($project_sk){
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');
        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_incomplete_task_of_project($project_sk){
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');
        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
        $this->db->where('tasks.task_done','0');                 
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_all_complete_task_of_project($project_sk){
        $this->db->select('tasks.*,blocks.end_date');
        $this->db->from('tasks');
        $this->db->join('clusters','clusters.cluster_sk = tasks.cluster_sk','INNER');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','INNER');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','INNER');        
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('tasks.project_sk',$project_sk);
        }
        $this->db->where('tasks.task_done','1');                 
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_agreement_project_detail_by_project_sk($project_sk){
        $this->db->select('projects.project_sk,projects.cluster_sk,projects.project_name,projects.created_by,clusters.client_sk,clusters.agency_sk,clusters.cluster_title,clusters.contact_person_name');
        $this->db->from('projects');
        $this->db->join('clusters','clusters.cluster_sk = projects.cluster_sk','INNER');                
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('projects.project_sk',$project_sk);
        }
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_insert_agreement_sign($insert_arr) {
        $query = $this->db->insert('agreement_sign', $insert_arr);
		return true;
	}
    
    function fn_update_agreement_sign($update_arr,$agreement_sign_sk) {
        $this->db->update('agreement_sign', $update_arr, array('agreement_sign_sk'=>$agreement_sign_sk));
		return true;
	}
    
    function fn_get_agreement_sign_detail($agreement_sign_sk){
        $this->db->select('agreement_sign.*');
        $this->db->from('agreement_sign');
        if(!empty($agreement_sign_sk) && $agreement_sign_sk != ''){
            $this->db->where('agreement_sign.agreement_sign_sk',$agreement_sign_sk);
        }
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_get_latest_agreement_sign_detail_by_project_sk($project_sk){
        $this->db->select('agreement_sign.*');
        $this->db->from('agreement_sign');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('agreement_sign.project_sk',$project_sk);
        }
        $this->db->order_by('agreement_sign.created_on DESC');
        $query = $this->db->get();
        #echo $this->db->last_query();#exit;
        $result['rows'] = $query->num_rows();
        $result['data'] = $query->result();      
        return $result;
    }
    
    function fn_update_phase_side_panel() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $phase_start_date = date('Y-m-d', strtotime($start_date));
        $phase_end_date = date('Y-m-d', strtotime($end_date));
        
        $update_phase_record = array(
            'phase_name'=>$this->input->post('phase_name'),
            'phase_description'=>$this->input->post('phase_description'),
            'start_date'=>$phase_start_date,
            'end_date'=>$phase_end_date,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
        
        //update agreement end date
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            $real_agreement_start_date = $agreement_data[0]->start_date;
            $real_agreement_end_date = $agreement_data[0]->end_date;
            $real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;            
            if(strtotime($real_agreement_start_date) > strtotime($phase_start_date)){
                $update_agreement_record = array(
                    'start_date'=>$phase_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
            if(strtotime($real_agreement_end_date) < strtotime($phase_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$phase_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }
        }            
        
		return $phase_sk;
	}
    
    function fn_get_all_blocks_by_phase_sk_in_side_div_panel($phase_sk) {
        $this->db->select('blocks.*,block_type.block_type_name,block_type.block_type_code,block_type.primary_color,block_type.secondary_color,block_type.border_color');
		$this->db->from('blocks');
        if(!empty($phase_sk) && $phase_sk != ''){
            $this->db->where('phase_sk',$phase_sk);
        }    
        $this->db->join('block_type','block_type.block_type_sk = blocks.block_type_sk','inner');
        //$this->db->order_by('created_on ASC');
        $this->db->order_by('start_date ASC');
        //$this->db->order_by('end_date ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
        $result['data'] = $query->result();        
        return $result;
    }
    
    function fn_get_all_remaining_task_by_phase_sk($phase_sk){
        $this->db->select('tasks.*,task_type.task_type_code,task_type.task_type_title,task_type.description,task_type.action_name,task_type.payment_action');
		$this->db->from('tasks');
        $this->db->join('task_type','task_type.task_type_sk = tasks.task_type_sk','inner');
        $this->db->where('phase_sk',$phase_sk);
        $this->db->where('task_done','0');
        $this->db->order_by('task_type.display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_insert_project_new_popup() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $result = $this->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $default_add_day_in_agreement_start_date_for_end_date = $q_get_all_site_constants[0]->default_add_day_in_agreement_start_date_for_end_date;
        if($default_add_day_in_agreement_start_date_for_end_date != '' && !empty($default_add_day_in_agreement_start_date_for_end_date)){
  			$add_day_in_agreement_start_date_for_end_date = $default_add_day_in_agreement_start_date_for_end_date;
  		}else{
  			$add_day_in_agreement_start_date_for_end_date = '15';
  		}
        $agreement_start_date = date('Y-m-d');
        $agreement_end_date = date('Y-m-d', strtotime($agreement_start_date. ' + '.$add_day_in_agreement_start_date_for_end_date.' days')); 
        
        $project_sk = $this->general->generate_primary_key();
		$record = array(
            'project_sk'=>$project_sk,
            'cluster_sk'=>$this->input->post('cluster_sk'),
            'project_name'=>$this->input->post('project_title'),
            'project_status'=>'not_started',
            'start_date'=>$agreement_start_date,
            'end_date'=>$agreement_end_date,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('projects', $record);
        
        /*$phase_position = '1';
        $phase_sk = $this->general->generate_primary_key();
		$record = array(
            'phase_sk'=>$phase_sk,
            'cluster_sk'=>$this->input->post('cluster_sk'),
            'project_sk'=>$project_sk,
            'phase_name'=>$this->input->post('phase_name'),
            'phase_position'=>$phase_position,
            'phase_status'=>'not_started',
            'start_date'=>$phase_start_date,
            'end_date'=>$phase_end_date,
            'created_by'=>$logged_in_users_sk,
            'created_on'=>date('Y-m-d H:i:s'),
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->insert('phases', $record);*/
        
		return $project_sk;
	}
    
    function fn_insert_phases_from_new_popup($cluster_sk,$project_sk,$phase_arr){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        if(count($phase_arr) > 0){
            $phase_position = '1';
            for($i=0;$i<count($phase_arr);$i++){
                $phase_name = $phase_arr[$i][0];
                $start_date = $phase_arr[$i][1];
                $end_date = $phase_arr[$i][2];
                $phase_start_date = date('Y-m-d', strtotime($start_date));
                $phase_end_date = date('Y-m-d', strtotime($end_date));
        
                $phase_sk = $this->general->generate_primary_key();
        		$record = array(
                    'phase_sk'=>$phase_sk,
                    'cluster_sk'=>$cluster_sk,
                    'project_sk'=>$project_sk,
                    'phase_name'=>$phase_name,
                    'phase_position'=>$phase_position,
                    'phase_status'=>'not_started',
                    'start_date'=>$phase_start_date,
                    'end_date'=>$phase_end_date,
                    'created_by'=>$logged_in_users_sk,
                    'created_on'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->insert('phases', $record);  
                $phase_position = $phase_position + 1;                  
            }    
        }
        
        //update agreement start and end date
        $first_inserted_phase_start_date = '';
        $last_inserted_phase_end_date = '';
        $first_inserted_phase_result = $this->fn_get_first_inserted_phase_by_project($project_sk);
        $first_inserted_phase_rows = $first_inserted_phase_result['rows']; 
        $q_first_inserted_phase_data = $first_inserted_phase_result['data'];                
        if($first_inserted_phase_rows > 0){
        	$first_inserted_phase_start_date = $q_first_inserted_phase_data[0]->start_date; 
        }
        $last_inserted_phase_result = $this->fn_get_last_inserted_phase_by_project($project_sk);
        $last_inserted_phase_rows = $last_inserted_phase_result['rows']; 
        $q_last_inserted_phase_data = $last_inserted_phase_result['data'];                
        if($last_inserted_phase_rows > 0){
        	$last_inserted_phase_end_date = $q_last_inserted_phase_data[0]->end_date;
        }
        
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
        $agreement_rows = $agreement_result['rows'];
        $agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
        	$real_agreement_start_date = $agreement_data[0]->start_date;
        	$real_agreement_end_date = $agreement_data[0]->end_date;
        	$real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;
            if($first_inserted_phase_start_date != ''){
                if(strtotime($real_agreement_start_date) > strtotime($first_inserted_phase_start_date)){
            		$update_agreement_record = array(
            			'start_date'=>$first_inserted_phase_start_date,
            			'modified_by'=>$logged_in_users_sk,
            			'modified_on'=>date('Y-m-d H:i:s')
            		);        
            		$query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            	}
            }                    	  
            if($last_inserted_phase_end_date != ''){
                if(strtotime($real_agreement_end_date) < strtotime($last_inserted_phase_end_date)){
            		$update_agreement_record = array(
            			'end_date'=>$phase_end_date,
            			'modified_by'=>$logged_in_users_sk,
            			'modified_on'=>date('Y-m-d H:i:s')
            		);        
            		$query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            	}
            }                
        }
        
        return true;    
    }
    
    function fn_update_block_with_popup() {
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $block_sk = $this->input->post('block_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $block_title = $this->input->post('block_title');
        $block_description = $this->input->post('block_description');
        $contact_person = $this->input->post('contact_person');
        $payment_amount = $this->input->post('payment_amount');
        $payment_currency = $this->input->post('payment_currency');
        $task_cnt = $this->input->post('task_cnt');
        $first_task = $this->input->post('first_task');
        $second_task = $this->input->post('second_task'); 
        $task_sks_arr = $this->input->post('task_sks');
        $block_start_date = date('Y-m-d', strtotime($start_date));
        $block_end_date = date('Y-m-d', strtotime($end_date));
        $record = array(
            'block_title'=>$block_title,
            'block_description'=>$block_description,
            'contact_person'=>$contact_person,
            'scheduled_start_date'=>$block_start_date,
            'scheduled_end_date'=>$block_end_date,
            'start_date'=>$block_start_date,
            'end_date'=>$block_end_date,
            'payment_amount'=>$payment_amount,
            'payment_currency'=>$payment_currency,
            'modified_by'=>$logged_in_users_sk,
            'modified_on'=>date('Y-m-d H:i:s')
        );        
        $query = $this->db->update('blocks', $record, array('block_sk'=>$block_sk));
        
        //update agreement end date
        $agreement_result = $this->fn_get_project_detail_by_project_sk($project_sk);
		$agreement_rows = $agreement_result['rows'];
		$agreement_data = $agreement_result['data'];
        if($agreement_rows > 0){
            $real_agreement_start_date = $agreement_data[0]->start_date;
            $real_agreement_end_date = $agreement_data[0]->end_date;
            $real_agreement_cluster_sk = $agreement_data[0]->cluster_sk;            
            if(strtotime($real_agreement_start_date) > strtotime($block_start_date)){
                $update_agreement_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }  
            if(strtotime($real_agreement_end_date) < strtotime($block_end_date)){
                $update_agreement_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('projects', $update_agreement_record, array('project_sk'=>$project_sk));        
            }
            //update task ids
            if(!empty($real_agreement_cluster_sk) && $real_agreement_cluster_sk != ''){
                $project_result = $this->fn_get_cluster_detail_by_cluster_sk($real_agreement_cluster_sk);
        		$project_rows = $project_result['rows'];
        		$project_data = $project_result['data'];
                if($project_rows > 0){
                    $client_sk = $project_data[0]->client_sk;
                    $agency_sk = $project_data[0]->agency_sk;
                    if($task_cnt == '2'){
                        $first_task_sk = $task_sks_arr[0];
                        $second_task_sk = $task_sks_arr[1];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));
                            
                            $update_second_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_second_task_record, array('tasks_sk'=>$second_task_sk));
                        }    
                    }
                    if($task_cnt == '1'){
                        $first_task_sk = $task_sks_arr[0];
                        if($first_task == 'agency'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$agency_sk,
                                'assignee_users_type'=>'agency',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }
                        if($first_task == 'client'){
                            $update_first_task_record = array(
                                'assignee_users_sk'=>$client_sk,
                                'assignee_users_type'=>'client',
                                'modified_by'=>$logged_in_users_sk,
                                'modified_on'=>date('Y-m-d H:i:s')
                            );        
                            $query = $this->db->update('tasks', $update_first_task_record, array('tasks_sk'=>$first_task_sk));                            
                        }    
                    }
                }                                
            }            
        }
        
        //update phase end date
        $phase_result = $this->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $real_phase_start_date = $phase_data[0]->start_date;
            $real_phase_end_date = $phase_data[0]->end_date;
            if(strtotime($real_phase_start_date) > strtotime($block_start_date)){
                $update_phase_record = array(
                    'start_date'=>$block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }
            if(strtotime($real_phase_end_date) < strtotime($block_end_date)){
                $update_phase_record = array(
                    'end_date'=>$block_end_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));        
            }
            /*$first_inserted_block_result = $this->fn_get_first_inserted_block_by_phase($phase_sk);
            $first_inserted_block_rows = $first_inserted_block_result['rows']; 
            $q_first_inserted_block_data = $first_inserted_block_result['data'];                
            if($first_inserted_block_rows > 0){
                $first_inserted_block_start_date = $q_first_inserted_block_data[0]->start_date; 
                $update_phase_record = array(
                    'start_date'=>$first_inserted_block_start_date,
                    'modified_by'=>$logged_in_users_sk,
                    'modified_on'=>date('Y-m-d H:i:s')
                );        
                $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
            }
            $last_inserted_block_result = $this->fn_get_last_inserted_block_by_phase($phase_sk);
            $last_inserted_block_rows = $last_inserted_block_result['rows']; 
            $q_last_inserted_block_data = $last_inserted_block_result['data'];                
            if($last_inserted_block_rows > 0){
                $last_inserted_block_end_date = $q_last_inserted_block_data[0]->end_date;
                //if(strtotime($last_inserted_block_end_date) > strtotime($block_end_date)){ 
                    $update_phase_record = array(
                        'end_date'=>$last_inserted_block_end_date,
                        'modified_by'=>$logged_in_users_sk,
                        'modified_on'=>date('Y-m-d H:i:s')
                    );        
                    $query = $this->db->update('phases', $update_phase_record, array('phase_sk'=>$phase_sk));
                //}                    
            }*/               
        }
        #exit;
        return $block_sk;
	}
    
    function fn_get_first_inserted_phase_by_project($project_sk=''){
        $this->db->select('*');
		$this->db->from('phases');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }    
        $this->db->order_by('phases.start_date ASC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_get_last_inserted_phase_by_project($project_sk=''){
        $this->db->select('*');
		$this->db->from('phases');
        if(!empty($project_sk) && $project_sk != ''){
            $this->db->where('project_sk',$project_sk);
        }     
        $this->db->order_by('phases.end_date DESC');
        $this->db->limit(1);        
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
    
    function fn_check_task_done_of_block($block_sk){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        
        $this->db->select('tasks.*,task_type.task_type_code,task_type.task_type_title,task_type.description,task_type.action_name,task_type.payment_action,blocks.start_date as block_start_date,blocks.end_date as block_end_date,blocks.payment_amount as block_payment_amount,phases.phase_name');
		    $this->db->from('tasks');
        $this->db->join('task_type','task_type.task_type_sk = tasks.task_type_sk','inner');
        $this->db->join('projects','projects.project_sk = tasks.project_sk','inner');
        $this->db->join('blocks','blocks.block_sk = tasks.block_sk','inner');
        $this->db->join('phases','phases.phase_sk = tasks.phase_sk','inner');
        $this->db->where('tasks.block_sk',$block_sk);
        $this->db->where('tasks.task_done','0');
        //$this->db->where('tasks.assignee_users_sk',$logged_in_users_sk);
        //$this->db->where('tasks.assignee_users_type',strtolower($user_login_mode));
        $this->db->order_by('task_type.display_order ASC');
        $query = $this->db->get();
		$result['rows'] = $query->num_rows();
		$result['data'] = $query->result();		
		//echo $this->db->last_query(); 
		return $result;    
    }
                                                                                                                            
}

?>