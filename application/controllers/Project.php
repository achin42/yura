<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

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
    
    function fn_get_create_project_popup(){
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $user_result = $this->users_model->fn_get_all_users_for_create_project($logged_in_users_sk);
        $data['user_rows'] = $user_rows = $user_result['rows']; 
        $data['user_data'] = $user_data = $user_result['data'];
    
        $data['company_image'] = ''; 
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/create_project',$data,true);
        echo '^^1';exit;
    }    
    
    function fn_get_company_detail_by_users_sk(){
        $users_sk = $this->input->post('sel_val');
        
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $company_rows = $company_result['rows']; 
        $company_data = $company_result['data'];
        if($company_rows > 0){
            echo $company_data[0]->contact_person_name.'^^1';exit; 
        }else{
            echo "2^^2";exit;
        }    
    }
    
    function fn_insert_project(){
        #echo "aa";exit;
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }          
        $sel_client = $this->input->post('sel_client');
        $new_client_email = $this->input->post('new_client_email');
        $new_client_company_name = $this->input->post('new_client_company_name');
        if($sel_client == '' && $new_client_email == '' && $new_client_company_name == ''){
            $this->form_validation->set_rules('sel_client', $user_login_mode_off, 'required');
        }
                                                                     
        $this->form_validation->set_rules('cluster_title', 'Cluster Title', 'required');
        $this->form_validation->set_rules('contact_person_name', 'Contact Person', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){     
            $insert_project = 'yes';
            $user_login_mode = $this->session->userdata('fe_user_login_mode');
            if($user_login_mode == 'AGENCY'){
                if($sel_client == '' && $new_client_email != '' && $new_client_company_name != ''){
                    $user_email_result = $this->users_model->fn_get_user_detail_by_email($new_client_email);
                    $user_rows = $user_email_result['rows']; 
                    $user_data = $user_email_result['data'];
                    if($user_rows > 0){
                        $insert_project = 'no';
                        echo '2^^2';exit;                            
                    }                            
                }
            }                                            
            if($insert_project == 'yes'){
                $cluster_sk = $this->project_model->fn_insert_cluster();
                /* image upload start */
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/cluster_image';
                if(!is_dir($create_dir_path)){
                    mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_FILES['cluster_image']['name'])){
                    $config['upload_path'] = $this->config->item('UPLOAD_DIR').'/cluster_image/';
        			$tmp = explode(".", $_FILES['cluster_image']['name']);
                    $ext = end($tmp);
        			$config['file_name'] = 'cluster_image_'.$this->general->generate_random_letters(4).'.'.$ext;
        			
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->set_allowed_types('*');
                    $data['upload_data'] = '';
                }
                //if not successful, set the error message
        		if ($this->upload->do_upload('cluster_image')) {
                    $upload_data = $this->upload->data();
                    $uploaded_file_name = $upload_data['file_name'];
                    $result = $this->project_model->fn_update_cluster_image($uploaded_file_name,$cluster_sk);
                }            
                /* image upload end */                
                echo $cluster_sk.'^^1';exit;
                #echo $this->config->item('site_url').'cluster-list/^^1';exit;
            }else{
                echo '2^^2';exit;    
            }                                        
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_cluster_list(){
        $data['page_title'] = 'Cluster List';
        $data['page_name'] = 'cluster_list';
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $filter_company_result = $this->users_model->fn_get_all_users_for_project_filter($logged_in_users_sk,$user_login_mode);
        $data['filter_company_rows'] = $filter_company_rows = $filter_company_result['rows']; 
        $data['q_filter_company_data'] = $q_filter_company_data = $filter_company_result['data'];
        //echo $filter_company_rows;exit;
        $project_result = $this->project_model->fn_get_all_clusters_list($user_login_mode);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['q_project_data'] = $q_project_data = $project_result['data'];
        
        $blocks_for_overdue_task_list_result = $this->project_model->fn_get_all_blocks_for_overdue_task_list();
        $data['blocks_for_overdue_task_list_rows'] = $blocks_for_overdue_task_list_rows = $blocks_for_overdue_task_list_result['rows']; 
        $data['q_blocks_for_overdue_task_list_data'] = $q_blocks_for_overdue_task_list_data = $blocks_for_overdue_task_list_result['data'];
        
        $blocks_for_active_task_list_result = $this->project_model->fn_get_all_blocks_for_active_task_list();
        $data['blocks_for_active_task_list_rows'] = $blocks_for_active_task_list_rows = $blocks_for_active_task_list_result['rows']; 
        $data['q_blocks_for_active_task_list_data'] = $q_blocks_for_active_task_list_data = $blocks_for_active_task_list_result['data'];
        
        $blocks_for_upcoming_task_list_result = $this->project_model->fn_get_all_blocks_for_upcoming_task_list();
        $data['blocks_for_upcoming_task_list_rows'] = $blocks_for_upcoming_task_list_rows = $blocks_for_upcoming_task_list_result['rows']; 
        $data['q_blocks_for_upcoming_task_list_data'] = $q_blocks_for_upcoming_task_list_data = $blocks_for_upcoming_task_list_result['data'];
        
        $overdue_task_list_arr = array();
        $overdue_not_done_task_cnt = 0; 
        $task_cnt = 0;
        foreach($q_blocks_for_overdue_task_list_data as $blocks_for_overdue_task_list_data){
            $block_sk = $blocks_for_overdue_task_list_data->block_sk;
            $block_start_date = $blocks_for_overdue_task_list_data->start_date;
            $block_end_date = $blocks_for_overdue_task_list_data->end_date;
            
            $task_result = $this->project_model->fn_get_all_assigned_task_by_block_sk($block_sk);
            $task_rows = $task_result['rows']; 
            $q_task_list_data = $task_result['data'];                                
            if($task_rows > 0){
                foreach($q_task_list_data as $task_list_data){
                    $overdue_task_list_arr[$task_cnt]['tasks_sk'] = $task_list_data->tasks_sk;
                    $overdue_task_list_arr[$task_cnt]['task_type_sk'] = $task_list_data->task_type_sk;
                    $overdue_task_list_arr[$task_cnt]['assignee_users_sk'] = $task_list_data->assignee_users_sk;
                    $overdue_task_list_arr[$task_cnt]['assignee_users_type'] = $task_list_data->assignee_users_type;
                    $overdue_task_list_arr[$task_cnt]['task_type_code'] = $task_list_data->task_type_code;
                    $overdue_task_list_arr[$task_cnt]['task_type_title'] = $task_list_data->task_type_title;
                    $overdue_task_list_arr[$task_cnt]['description'] = $task_list_data->description;
                    $overdue_task_list_arr[$task_cnt]['action_name'] = $task_list_data->action_name;
                    $overdue_task_list_arr[$task_cnt]['payment_action'] = $task_list_data->payment_action;
                    $overdue_task_list_arr[$task_cnt]['block_start_date'] = $block_start_date;
                    $overdue_task_list_arr[$task_cnt]['block_end_date'] = $block_end_date;                
                    $overdue_task_list_arr[$task_cnt]['color_code'] = $task_list_data->color_code;
                    $overdue_task_list_arr[$task_cnt]['block_start_date'] = $task_list_data->block_start_date;
                    $overdue_task_list_arr[$task_cnt]['block_end_date'] = $task_list_data->block_end_date;
                    $overdue_task_list_arr[$task_cnt]['block_payment_amount'] = $task_list_data->block_payment_amount;
                    $overdue_task_list_arr[$task_cnt]['phase_name'] = $task_list_data->phase_name;  
                    $overdue_task_list_arr[$task_cnt]['task_done'] = $task_list_data->task_done;
                    if($task_list_data->task_done == '0'){
                        $overdue_not_done_task_cnt++;    
                    }                       
                    $task_cnt++;
                }                                                                            
            } 
        }
        
        $active_task_list_arr = array(); 
        $task_cnt = 0;
        foreach($q_blocks_for_active_task_list_data as $blocks_for_active_task_list_data){
            $block_sk = $blocks_for_active_task_list_data->block_sk;
            $block_start_date = $blocks_for_active_task_list_data->start_date;
            $block_end_date = $blocks_for_active_task_list_data->end_date;
            
            $task_result = $this->project_model->fn_get_all_assigned_task_by_block_sk($block_sk);
            $task_rows = $task_result['rows']; 
            $q_task_list_data = $task_result['data'];                                
            if($task_rows > 0){
                foreach($q_task_list_data as $task_list_data){
                    $active_task_list_arr[$task_cnt]['tasks_sk'] = $task_list_data->tasks_sk;
                    $active_task_list_arr[$task_cnt]['task_type_sk'] = $task_list_data->task_type_sk;
                    $active_task_list_arr[$task_cnt]['assignee_users_sk'] = $task_list_data->assignee_users_sk;
                    $active_task_list_arr[$task_cnt]['assignee_users_type'] = $task_list_data->assignee_users_type;
                    $active_task_list_arr[$task_cnt]['task_type_code'] = $task_list_data->task_type_code;
                    $active_task_list_arr[$task_cnt]['task_type_title'] = $task_list_data->task_type_title;
                    $active_task_list_arr[$task_cnt]['description'] = $task_list_data->description;
                    $active_task_list_arr[$task_cnt]['action_name'] = $task_list_data->action_name;
                    $active_task_list_arr[$task_cnt]['payment_action'] = $task_list_data->payment_action;
                    $active_task_list_arr[$task_cnt]['block_start_date'] = $block_start_date;
                    $active_task_list_arr[$task_cnt]['block_end_date'] = $block_end_date;                
                    $active_task_list_arr[$task_cnt]['color_code'] = $task_list_data->color_code;
                    $active_task_list_arr[$task_cnt]['block_start_date'] = $task_list_data->block_start_date;
                    $active_task_list_arr[$task_cnt]['block_end_date'] = $task_list_data->block_end_date;
                    $active_task_list_arr[$task_cnt]['block_payment_amount'] = $task_list_data->block_payment_amount;
                    $active_task_list_arr[$task_cnt]['phase_name'] = $task_list_data->phase_name;  
                    $active_task_list_arr[$task_cnt]['task_done'] = $task_list_data->task_done;                       
                    $task_cnt++;
                }                                                                            
            } 
        }
        
        $upcoming_task_list_arr = array(); 
        $task_cnt = 0;
        foreach($q_blocks_for_upcoming_task_list_data as $q_blocks_for_upcoming_task_list_data){
            $block_sk = $q_blocks_for_upcoming_task_list_data->block_sk;
            $block_start_date = $q_blocks_for_upcoming_task_list_data->start_date;
            $block_end_date = $q_blocks_for_upcoming_task_list_data->end_date;
            
            $task_result = $this->project_model->fn_get_all_assigned_task_by_block_sk($block_sk);
            $task_rows = $task_result['rows']; 
            $q_task_list_data = $task_result['data'];                                
            if($task_rows > 0){
                foreach($q_task_list_data as $task_list_data){
                    $upcoming_task_list_arr[$task_cnt]['tasks_sk'] = $task_list_data->tasks_sk;
                    $upcoming_task_list_arr[$task_cnt]['task_type_sk'] = $task_list_data->task_type_sk;
                    $upcoming_task_list_arr[$task_cnt]['assignee_users_sk'] = $task_list_data->assignee_users_sk;
                    $upcoming_task_list_arr[$task_cnt]['assignee_users_type'] = $task_list_data->assignee_users_type;
                    $upcoming_task_list_arr[$task_cnt]['task_type_code'] = $task_list_data->task_type_code;
                    $upcoming_task_list_arr[$task_cnt]['task_type_title'] = $task_list_data->task_type_title;
                    $upcoming_task_list_arr[$task_cnt]['description'] = $task_list_data->description;
                    $upcoming_task_list_arr[$task_cnt]['action_name'] = $task_list_data->action_name;
                    $upcoming_task_list_arr[$task_cnt]['payment_action'] = $task_list_data->payment_action;
                    $upcoming_task_list_arr[$task_cnt]['block_start_date'] = $block_start_date;
                    $upcoming_task_list_arr[$task_cnt]['block_end_date'] = $block_end_date;    
                    $upcoming_task_list_arr[$task_cnt]['color_code'] = $task_list_data->color_code;
                    $upcoming_task_list_arr[$task_cnt]['block_start_date'] = $task_list_data->block_start_date;
                    $upcoming_task_list_arr[$task_cnt]['block_end_date'] = $task_list_data->block_end_date;
                    $upcoming_task_list_arr[$task_cnt]['block_payment_amount'] = $task_list_data->block_payment_amount;
                    $upcoming_task_list_arr[$task_cnt]['phase_name'] = $task_list_data->phase_name;
                    $upcoming_task_list_arr[$task_cnt]['task_done'] = $task_list_data->task_done;                                     
                    $task_cnt++;
                }                                                                            
            } 
        }
        
        $data['overdue_task_list_arr'] = $overdue_task_list_arr;
        $data['overdue_not_done_task_cnt'] = $overdue_not_done_task_cnt;
        $data['active_task_list_arr'] = $active_task_list_arr;
        $data['upcoming_task_list_arr'] = $upcoming_task_list_arr;                                                             
        $display_dashboard_instruction = get_cookie('dashboard_instruction');        
        $data['display_dashboard_instruction'] = $display_dashboard_instruction;
        $data['user_login_mode'] = $user_login_mode;
        $data['user_login_mode_off'] = $user_login_mode_off;
        $this->load->view('project/cluster_list',$data);
    }
    
    function fn_get_cluster_list_ajax(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        } 
        $filter_via_company = $this->input->post('filter_via_company');
        
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $filter_company_result = $this->users_model->fn_get_all_users_for_project_filter($logged_in_users_sk,$user_login_mode);
        $data['filter_company_rows'] = $filter_company_rows = $filter_company_result['rows']; 
        $data['q_filter_company_data'] = $q_filter_company_data = $filter_company_result['data'];
                
        $project_result = $this->project_model->fn_get_all_clusters_list($user_login_mode,$filter_via_company);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['q_project_data'] = $q_project_data = $project_result['data'];
        
        $data['filter_via_company'] = $filter_via_company;
        $data['user_login_mode'] = $user_login_mode;
        $data['user_login_mode_off'] = $user_login_mode_off;
        
        if($project_rows > 0){            
            echo $this->load->view('project/cluster_list_ajax',$data,true);
            echo '^^1^^';exit;
        }else{
            echo $this->config->item('site_url').'dashboard/^^2';exit;
        }            
    }
    
    function fn_delete_cluster(){
        $cluster_sk = $this->input->post('cluster_sk');
		$result = $this->project_model->fn_delete_cluster($cluster_sk);
    }
    
    function fn_get_project_detail(){
        #echo $block_sk = $this->general->generate_primary_key();exit;
        $data['page_title'] = 'Project Detail';
        $data['page_name'] = 'project_detail';
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        $project_sk = $this->uri->segment('2');
        if(empty($project_sk) || $project_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }        
        $data['project_sk'] = $project_sk;
        $agreement_result = $this->project_model->fn_get_project_detail_by_project_sk($project_sk);
        $data['agreement_rows'] = $agreement_rows = $agreement_result['rows']; 
        $data['agreement_data'] = $agreement_data = $agreement_result['data'];
        $data['cluster_sk'] = $cluster_sk = $agreement_data[0]->cluster_sk;        
                    
        if($agreement_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }                
        if(empty($cluster_sk) || $cluster_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $pre_defined_block_type_result = $this->project_model->fn_get_all_pre_defined_block_type();
        $data['pre_defined_block_type_rows'] = $pre_defined_block_type_rows = $pre_defined_block_type_result['rows']; 
        $data['q_pre_defined_block_type_data'] = $q_pre_defined_block_type_data = $pre_defined_block_type_result['data'];
        
        $agreement_sign_result = $this->project_model->fn_get_latest_agreement_sign_detail_by_project_sk($project_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['q_agreement_sign_data'] = $q_agreement_sign_data = $agreement_sign_result['data'];
        
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $logged_in_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_user_rows'] = $logged_in_user_rows = $logged_in_user_result['rows']; 
        $data['logged_in_user_data'] = $logged_in_user_data = $logged_in_user_result['data'];
        
        $logged_in_company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_company_rows'] = $logged_in_company_rows = $logged_in_company_result['rows']; 
        $data['logged_in_company_data'] = $logged_in_company_data = $logged_in_company_result['data'];
        
        $data['logged_in_user_type'] = $logged_in_user_type = $logged_in_user_data[0]->user_type;
        $data['logged_in_is_agency_verified'] = $logged_in_is_agency_verified = $logged_in_company_data[0]->is_agency_verified;
        $data['logged_in_verify_later'] = $logged_in_verify_later = $logged_in_company_data[0]->verify_later;
        $data['logged_in_applied_for_manual_verification'] = $logged_in_applied_for_manual_verification = $logged_in_company_data[0]->applied_for_manual_verification;
                        
        $this->load->view('project/project_detail',$data);
    }
    
    function fn_get_project_phase_list_ajax(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        } 
        $project_sk = $this->input->post('project_sk');
        $sel_phase_sk = $this->input->post('sel_phase_sk');
        $calendar_block_arr = array();
                
        $agreement_result = $this->project_model->fn_get_project_detail_by_project_sk($project_sk);
        $data['agreement_rows'] = $agreement_rows = $agreement_result['rows']; 
        $data['agreement_data'] = $agreement_data = $agreement_result['data'];
        $data['cluster_sk'] = $cluster_sk = $agreement_data[0]->cluster_sk;        
        $data['agreement_start_date'] = $agreement_start_date = $agreement_data[0]->start_date;
        $data['agreement_end_date'] = $agreement_end_date = $agreement_data[0]->end_date;
        $dates_array = array();
        $calendar_block_arr = array();
        $new_calendar_block_arr = array(); 
        $month_wise_days_count_array = array();
        $month_name_array = array();
        $day_count = 0;
                    
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $phases_result = $this->project_model->fn_get_all_phases_by_project($project_sk);
        $data['phase_rows'] = $phase_rows = $phases_result['rows']; 
        $data['q_phase_data'] = $q_phase_data = $phases_result['data'];
        
        $block_result = $this->project_model->fn_get_all_blocks_by_project($project_sk);
        $block_rows = $block_result['rows']; 
        $q_block_data = $block_result['data'];
        
        $dates_array = $this->general->get_dates_between_two_dates($agreement_start_date,$agreement_end_date);
        $current_month_name = date('F',strtotime($dates_array[0]));
        $month_name_array[] = $current_month_name;        
        for($i=0;$i<count($dates_array);$i++){
            $loop_month_name = date('F',strtotime($dates_array[$i]));
            $day_name = date('j',strtotime($dates_array[$i]));
            if($current_month_name != $loop_month_name){
                $current_month_name = $loop_month_name;
                $month_name_array[] = $current_month_name;
                $month_wise_days_count_array[] = $day_count;
                $day_count = 0;                 
            }
            $day_count++;
        }
        $first_date_of_last_month = date('Y-m-01', strtotime($agreement_end_date));
        $last_month_days = $this->general->date_diff_in_days($agreement_end_date, $first_date_of_last_month);
        $month_wise_days_count_array[] = $last_month_days+1;
        /*echo "<pre>";
        print_r($q_block_data);
        print_r($calendar_block_arr);      
        print_r($month_name_array);  
        print_r($month_wise_days_count_array);         
        exit;*/        
        $data['month_name_array'] = $month_name_array;
        $data['month_wise_days_count_array'] = $month_wise_days_count_array;        
        $data['dates_array'] = $dates_array;
        $data['calendar_block_arr'] = $calendar_block_arr;
        $data['new_calendar_block_arr'] = $new_calendar_block_arr;
        
        $data['block_cancel_mode'] = 'update_from_calendar_left_side';        
        $data['project_sk'] = $project_sk;
        $data['sel_phase_sk'] = $sel_phase_sk;
        $data['user_login_mode_off'] = $user_login_mode_off;            
        echo $this->load->view('project/project_detail_ajax',$data,true);
        echo '^^1^^';
        exit;
    }
    
    function fn_get_create_agreement_popup(){
        $data['cluster_sk'] = $cluster_sk = $this->input->post('cluster_sk');
        $data['came_from_project'] = $came_from_project = $this->input->post('came_from_project');     
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];
        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $data['user_login_mode_off'] = $user_login_mode_off; 
        if($came_from_project == 'yes'){
            echo $this->load->view('project/create_agreement_came_from_project',$data,true);
        }else{
            echo $this->load->view('project/create_agreement',$data,true);
        }        
        echo '^^1';exit;
    } 
    
    function fn_insert_agreement(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }        
        $this->form_validation->set_rules('project_name', 'Project Title', 'required');
        $this->form_validation->set_rules('phase_name', 'Name of first Phase', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $project_sk = $this->project_model->fn_insert_project();
            echo $this->config->item('site_url').'project-detail/'.$project_sk.'^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_delete_project(){
        $project_sk = $this->input->post('project_sk');
		$result = $this->project_model->fn_delete_project($project_sk);
    }
    
    function fn_get_create_phase_popup(){
        $data['cluster_sk'] = $cluster_sk = $this->input->post('cluster_sk');
        $data['project_sk'] = $project_sk = $this->input->post('project_sk');     
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];
        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/create_phase_with_date',$data,true);
        echo '^^1';exit;
    }
    
    function fn_insert_phase(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }        
        $this->form_validation->set_rules('phase_name', 'Phase Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $phase_sk = $this->project_model->fn_insert_phase();
            echo '1^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_insert_block(){
        $data['project_sk'] = $project_sk = $this->input->post('project_sk');
        $data['block_type_sk'] = $block_type_sk = $this->input->post('block_type_sk');
        $data['block_type_code'] = $block_type_code = $this->input->post('block_type_code');
        $data['phase_sk'] = $phase_sk = $this->input->post('phase_sk');
        
        $block_sk = $this->project_model->fn_insert_block();
        
        $block_result = $this->project_model->fn_get_block_detail_by_block_sk($block_sk);
        $data['block_rows'] = $block_rows = $block_result['rows']; 
        $data['block_data'] = $block_data = $block_result['data'];
        $data['block_sk'] = $block_sk; 
        
        $task_result = $this->project_model->fn_get_all_task_by_block_sk($block_sk);
        $data['task_rows'] = $task_rows = $task_result['rows']; 
        $data['q_task_data'] = $q_task_data = $task_result['data'];
        
        $data['block_cancel_mode'] = 'add_update_from_right_side';
        if($block_type_code == 'input'){
            echo $this->load->view('project/input_block_html',$data,true);                    
        }elseif($block_type_code == 'meeting'){
            echo $this->load->view('project/meeting_block_html',$data,true);                    
        }elseif($block_type_code == 'proposal'){
            echo $this->load->view('project/proposal_block_html',$data,true);                    
        }elseif($block_type_code == 'payment'){
            echo $this->load->view('project/payment_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback'){
            echo $this->load->view('project/feedback_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback-cycle'){
            echo $this->load->view('project/feedback_cycle_block_html',$data,true);                    
        }elseif($block_type_code == 'approval'){
            echo $this->load->view('project/approval_block_html',$data,true);                    
        }elseif($block_type_code == 'milestone'){
            echo $this->load->view('project/milestone_block_html',$data,true);                    
        }
        echo '^^1';exit;
    }
    
    function fn_get_open_edit_block(){
        $data['block_sk'] = $block_sk = $this->input->post('block_sk');
        
        $block_result = $this->project_model->fn_get_block_detail_by_block_sk($block_sk);
        $data['block_rows'] = $block_rows = $block_result['rows']; 
        $data['block_data'] = $block_data = $block_result['data'];
        $data['block_type_code'] = $block_type_code = $block_data[0]->block_type_code; 
        $data['project_sk'] = $project_sk = $block_data[0]->project_sk;
        $data['phase_sk'] = $phase_sk = $block_data[0]->phase_sk;
        
        $task_result = $this->project_model->fn_get_all_task_by_block_sk($block_sk);
        $data['task_rows'] = $task_rows = $task_result['rows']; 
        $data['q_task_data'] = $q_task_data = $task_result['data'];
        
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
        $data['phase_rows'] = $phase_rows = $phase_result['rows']; 
        $data['q_phase_data'] = $q_phase_data = $phase_result['data'];
        
        $data['block_cancel_mode'] = 'update_from_calendar_left_side';
        if($block_type_code == 'input'){
            echo $this->load->view('project/input_block_html',$data,true);                    
        }elseif($block_type_code == 'meeting'){
            echo $this->load->view('project/meeting_block_html',$data,true);                    
        }elseif($block_type_code == 'proposal'){
            echo $this->load->view('project/proposal_block_html',$data,true);                    
        }elseif($block_type_code == 'payment'){
            echo $this->load->view('project/payment_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback'){
            echo $this->load->view('project/feedback_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback-cycle'){
            echo $this->load->view('project/feedback_cycle_block_html',$data,true);                    
        }elseif($block_type_code == 'approval'){
            echo $this->load->view('project/approval_block_html',$data,true);                    
        }elseif($block_type_code == 'milestone'){
            echo $this->load->view('project/milestone_block_html',$data,true);                    
        }
        echo '^^1';exit;
    }
    
    function fn_update_block(){
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        if($start_date != '' && $end_date != ''){
            $data['project_sk'] = $project_sk = $this->input->post('project_sk');
            $data['block_sk'] = $block_sk = $this->input->post('block_sk');
            $block_sk = $this->project_model->fn_update_block_with_popup();
            echo '1^^1';exit;
        }else{
            if($start_date == ''){
                echo 'Please select start date';exit;
            }    
            if($end_date == ''){
                echo 'Please select end date';exit;                    
            }
        }            
    }
    

    function fn_get_download_agreement($pass_project_sk='',$pass_download_on_server='no') {
        error_reporting(0);
        $this->load->library("pdf");

        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        $set_from_url_project_sk = $this->uri->segment('2');
        $set_from_url_download = $this->uri->segment('3');
        if(isset($pass_project_sk)){
            if(!empty($pass_project_sk)){
                $project_sk = $pass_project_sk;
            }                        
        }else{
            $project_sk = $set_from_url_project_sk;            
        }
        if(isset($pass_download_on_server)){
            if(!empty($pass_project_sk)){
                $download_on_server = $pass_download_on_server;
            }            
        }else{
            $download_on_server = 'no';
        }
        
        if(empty($project_sk) || $project_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
            exit;    
        }        
        $agreement_result = $this->project_model->fn_get_project_detail_by_project_sk($project_sk);
        $data['agreement_rows'] = $agreement_rows = $agreement_result['rows']; 
        $data['agreement_data'] = $agreement_data = $agreement_result['data'];
        $cluster_sk = $agreement_data[0]->cluster_sk;        
                    
        if($agreement_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
            exit;    
        }                
        if(empty($cluster_sk) || $cluster_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
            exit;    
        }

        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];        
       
        $users_sk = $project_data[0]->client_sk;
        
        $client_responsibilities = $this->project_model->fn_get_responsibilities_list_by_project_sk($project_sk,$users_sk,'client');
        $data['client_responsibilities_rows'] = $client_responsibilities_rows = $client_responsibilities['rows']; 
        $data['client_responsibilities_data'] = $client_responsibilities_data = $client_responsibilities['data'];


        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $agency_sk = $project_data[0]->agency_sk;

        $agent_responsibilities = $this->project_model->fn_get_responsibilities_list_by_project_sk($project_sk,$agency_sk,'agency');
        $data['agent_responsibilities_rows'] = $agent_responsibilities_rows = $agent_responsibilities['rows']; 
        $data['agent_responsibilities_data'] = $agent_responsibilities_data = $agent_responsibilities['data'];

        $agency_result = $this->users_model->fn_get_user_company_detail_by_users_sk($agency_sk);
        $data['agency_rows'] = $agency_rows = $agency_result['rows']; 
        $data['agency_data'] = $agency_data = $agency_result['data'];
        
        $agency_user_result = $this->users_model->fn_get_user_detail_by_users_sk($agency_sk);
        $data['agency_user_rows'] = $agency_user_rows = $agency_user_result['rows']; 
        $data['agency_user_data'] = $agency_user_data = $agency_user_result['data'];
        
        $phases_result = $this->project_model->fn_get_all_phases_by_project($project_sk);
        $data['phases_rows'] =  $phases_result['rows']; 
        $data['phases_data'] =  $phases_result['data'];

        $block_result = $this->project_model->fn_get_all_blocks_by_project_for_download($project_sk);
        $data['block_rows'] =  $block_result['rows']; 
        $data['block_data'] =  $block_result['data'];        
        
        $total_payment_result = $this->project_model->fn_get_total_payment_of_cluster($cluster_sk);
        $total_payment_rows = $total_payment_result['rows']; 
        $q_total_payment_data = $total_payment_result['data'];                
        if($total_payment_rows > 0 && !empty($q_total_payment_data[0]->total_payment) && $q_total_payment_data[0]->total_payment != ''){
            $total_project_payment = $q_total_payment_data[0]->total_payment; 
        }    
        $data['total_project_payment'] = $total_project_payment;
        
        $result = $this->users_model->fn_get_all_site_constants();
		$data['get_all_site_constants_record_count'] = $get_all_site_constants_record_count = $result['rows'];
		$data['get_all_site_constants'] = $q_get_all_site_constants = $result['data'];
                             
        //echo "<pre>";
        //print_r($data);exit;
        $pdf_name = $agreement_data[0]->project_name."-".date('d-m-Y').".pdf";
        $download_on_server_pdf_name = $agreement_data[0]->project_name."-".$project_sk.".pdf";
        $download_on_server_pdf_name = $this->general->clean_string($download_on_server_pdf_name);
        $this->pdf->load_view('project/agreement_download',$data,TRUE);
        $this->pdf->render();
        if($download_on_server == 'no'){
            $agreement_sign_result = $this->project_model->fn_get_latest_agreement_sign_detail_by_project_sk($project_sk);
            $agreement_sign_rows = $agreement_sign_rows = $agreement_sign_result['rows']; 
            $q_agreement_sign_data = $q_agreement_sign_data = $agreement_sign_result['data'];
            $first_signer_signed = '0';
            $second_signer_signed = '0';
            $document_uuid = '';
            if($agreement_sign_rows > 0){
                $first_signer_signed = $q_agreement_sign_data[0]->first_signer_signed;  
                $second_signer_signed = $q_agreement_sign_data[0]->second_signer_signed;
                $document_uuid = $q_agreement_sign_data[0]->sign_request_document_uuid;                
            }
            if(($first_signer_signed == '1' || $second_signer_signed == '1') && $document_uuid != ''){
                $ret_arr = $this->signrequest->fn_download_document_from_sign_request($document_uuid);
                if($ret_arr['status'] == 'succ'){
                    $document_signed_file_url = $ret_arr['document_signed_file_url'];                    
                    $signed_file_name = 'signed-'.$agreement_data[0]->project_name."-".date('d-m-Y').".pdf";
                    $signed_file_name = $this->general->clean_string($signed_file_name); 
                    $save_file_path = $this->config->item("UPLOAD_DIR").'agreement_pdf/'.$signed_file_name;
                    file_put_contents($save_file_path,file_get_contents($document_signed_file_url));
                    if(file_exists($save_file_path)){
                        header("Expires: 0");
                        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
                        header("Cache-Control: no-store, no-cache, must-revalidate");
                        header("Cache-Control: post-check=0, pre-check=0", false);
                        header("Pragma: no-cache");
                        header("Content-type: application/file");
                        header('Content-length: '.filesize($save_file_path));
                        header('Content-disposition: attachment; filename="'.basename($signed_file_name).'"');
                        readfile($save_file_path);
                        exit;                    
                    }else{
                        
                    }    
                }else{
                    $this->pdf->stream($pdf_name);
                }                
            }else{
                $this->pdf->stream($pdf_name);
            }                    
        }else{
            $create_dir_path = $this->config->item('UPLOAD_DIR').'/agreement_pdf';
            if(!is_dir($create_dir_path)){
                mkdir($create_dir_path, 0777, true);
            }
            $output = $this->pdf->output();
            file_put_contents($this->config->item("UPLOAD_DIR").'/agreement_pdf/'.$download_on_server_pdf_name, $output);
            return 'succ###'.$download_on_server_pdf_name;
        }                    
    }
    
    function fun_delete_block(){
        $block_sk = $this->input->post('block_sk');
		$result = $this->project_model->fun_delete_block($block_sk);
    }
    
    function fn_get_update_phase_popup(){
        $data['cluster_sk'] = $cluster_sk = $this->input->post('cluster_sk');
        $data['project_sk'] = $project_sk = $this->input->post('project_sk');
        $data['phase_sk'] = $phase_sk = $this->input->post('phase_sk');     
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];
        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
		$data['phase_rows'] = $phase_rows = $phase_result['rows'];
		$data['phase_data'] = $phase_data = $phase_result['data'];
        
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/update_phase',$data,true);
        echo '^^1';exit;
    }
    
    function fn_update_phase(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }        
        $this->form_validation->set_rules('phase_name', 'Phase Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $phase_sk = $this->project_model->fn_update_phase();
            echo '1^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fun_delete_phase(){
        $result = $this->project_model->fun_delete_phase();
    }
    
    function fn_get_project_detail_for_execution(){
        #echo $block_sk = $this->general->generate_primary_key();exit;
        $data['page_title'] = 'Project Detail';
        $data['page_name'] = 'project_execution';
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        $project_sk = $this->uri->segment('2');
        if(empty($project_sk) || $project_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }        
        $data['project_sk'] = $project_sk;
        $agreement_result = $this->project_model->fn_get_project_detail_by_project_sk($project_sk);
        $data['agreement_rows'] = $agreement_rows = $agreement_result['rows']; 
        $data['agreement_data'] = $agreement_data = $agreement_result['data'];
        $data['cluster_sk'] = $cluster_sk = $agreement_data[0]->cluster_sk;        
                    
        if($agreement_rows == 0){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }                
        if(empty($cluster_sk) || $cluster_sk == ''){
            redirect($this->config->item('site_url').'cluster-list/');
			exit;    
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $pre_defined_block_type_result = $this->project_model->fn_get_all_pre_defined_block_type();
        $data['pre_defined_block_type_rows'] = $pre_defined_block_type_rows = $pre_defined_block_type_result['rows']; 
        $data['q_pre_defined_block_type_data'] = $q_pre_defined_block_type_data = $pre_defined_block_type_result['data'];
        
        $agreement_sign_result = $this->project_model->fn_get_latest_agreement_sign_detail_by_project_sk($project_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['q_agreement_sign_data'] = $q_agreement_sign_data = $agreement_sign_result['data'];
        
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $logged_in_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_user_rows'] = $logged_in_user_rows = $logged_in_user_result['rows']; 
        $data['logged_in_user_data'] = $logged_in_user_data = $logged_in_user_result['data'];
        
        $logged_in_company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_company_rows'] = $logged_in_company_rows = $logged_in_company_result['rows']; 
        $data['logged_in_company_data'] = $logged_in_company_data = $logged_in_company_result['data'];
        
        $data['logged_in_user_type'] = $logged_in_user_type = $logged_in_user_data[0]->user_type;
        $data['logged_in_is_agency_verified'] = $logged_in_is_agency_verified = $logged_in_company_data[0]->is_agency_verified;
        $data['logged_in_verify_later'] = $logged_in_verify_later = $logged_in_company_data[0]->verify_later;
        $data['logged_in_applied_for_manual_verification'] = $logged_in_applied_for_manual_verification = $logged_in_company_data[0]->applied_for_manual_verification;
        
        $this->load->view('project/project_detail_for_execution',$data);
    }
    
    function fn_get_project_execution_phase_list_ajax(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        } 
        $project_sk = $this->input->post('project_sk');
        $sel_phase_sk = $this->input->post('sel_phase_sk');        
        $calendar_block_arr = array();
                
        $agreement_result = $this->project_model->fn_get_project_detail_by_project_sk($project_sk);
        $data['agreement_rows'] = $agreement_rows = $agreement_result['rows']; 
        $data['agreement_data'] = $agreement_data = $agreement_result['data'];
        $data['cluster_sk'] = $cluster_sk = $agreement_data[0]->cluster_sk;        
        $data['agreement_start_date'] = $agreement_start_date = $agreement_data[0]->start_date;
        $data['agreement_end_date'] = $agreement_end_date = $agreement_data[0]->end_date;
        $dates_array = array();
        $calendar_block_arr = array();
        $new_calendar_block_arr = array(); 
        $month_wise_days_count_array = array();
        $month_name_array = array();
        $day_count = 0;
                    
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $phases_result = $this->project_model->fn_get_all_phases_by_project($project_sk);
        $data['phase_rows'] = $phase_rows = $phases_result['rows']; 
        $data['q_phase_data'] = $q_phase_data = $phases_result['data'];
        
        $block_result = $this->project_model->fn_get_all_blocks_by_project($project_sk);
        $block_rows = $block_result['rows']; 
        $q_block_data = $block_result['data'];
        
        $dates_array = $this->general->get_dates_between_two_dates($agreement_start_date,$agreement_end_date);
        $current_month_name = date('F',strtotime($dates_array[0]));
        $month_name_array[] = $current_month_name;        
        for($i=0;$i<count($dates_array);$i++){
            $loop_month_name = date('F',strtotime($dates_array[$i]));
            $day_name = date('j',strtotime($dates_array[$i]));
            if($current_month_name != $loop_month_name){
                $current_month_name = $loop_month_name;
                $month_name_array[] = $current_month_name;
                $month_wise_days_count_array[] = $day_count;
                $day_count = 0;                 
            }
            $day_count++;
        }
        $first_date_of_last_month = date('Y-m-01', strtotime($agreement_end_date));
        $last_month_days = $this->general->date_diff_in_days($agreement_end_date, $first_date_of_last_month);
        $month_wise_days_count_array[] = $last_month_days+1;
        /*echo "<pre>";
        print_r($q_block_data);
        print_r($calendar_block_arr);      
        print_r($month_name_array);  
        print_r($month_wise_days_count_array);         
        exit;*/
        $data['month_name_array'] = $month_name_array;
        $data['month_wise_days_count_array'] = $month_wise_days_count_array;        
        $data['dates_array'] = $dates_array;
        $data['calendar_block_arr'] = $calendar_block_arr;
        $data['new_calendar_block_arr'] = $new_calendar_block_arr;
        
        $data['project_sk'] = $project_sk;
        $data['sel_phase_sk'] = $sel_phase_sk;
        $data['user_login_mode_off'] = $user_login_mode_off;            
        echo $this->load->view('project/project_detail_for_execution_ajax',$data,true);
        echo '^^1^^';
        exit;
    }
    
    function fn_get_open_execution__block(){
        $data['block_sk'] = $block_sk = $this->input->post('block_sk');
        
        $block_result = $this->project_model->fn_get_block_detail_by_block_sk($block_sk);
        $data['block_rows'] = $block_rows = $block_result['rows']; 
        $data['block_data'] = $block_data = $block_result['data'];
        $data['block_type_code'] = $block_type_code = $block_data[0]->block_type_code; 
        $data['project_sk'] = $project_sk = $block_data[0]->project_sk;
        $data['phase_sk'] = $phase_sk = $block_data[0]->phase_sk;
        
        $task_result = $this->project_model->fn_get_all_task_by_block_sk($block_sk);
        $data['task_rows'] = $task_rows = $task_result['rows']; 
        $data['q_task_data'] = $q_task_data = $task_result['data'];
        
        $active_task_list_arr = array(); 
        $task_cnt = 0;
        $task_result = $this->project_model->fn_get_all_assigned_task_by_block_sk($block_sk);
        $task_rows = $task_result['rows']; 
        $q_task_list_data = $task_result['data'];                                
        if($task_rows > 0){
        	foreach($q_task_list_data as $task_list_data){
        		$active_task_list_arr[$task_cnt]['tasks_sk'] = $task_list_data->tasks_sk;
        		$active_task_list_arr[$task_cnt]['task_type_sk'] = $task_list_data->task_type_sk;
        		$active_task_list_arr[$task_cnt]['assignee_users_sk'] = $task_list_data->assignee_users_sk;
        		$active_task_list_arr[$task_cnt]['assignee_users_type'] = $task_list_data->assignee_users_type;
        		$active_task_list_arr[$task_cnt]['task_type_code'] = $task_list_data->task_type_code;
        		$active_task_list_arr[$task_cnt]['task_type_title'] = $task_list_data->task_type_title;
        		$active_task_list_arr[$task_cnt]['description'] = $task_list_data->description;
        		$active_task_list_arr[$task_cnt]['action_name'] = $task_list_data->action_name;
        		$active_task_list_arr[$task_cnt]['payment_action'] = $task_list_data->payment_action;
        		//$active_task_list_arr[$task_cnt]['block_start_date'] = $block_start_date;
        		//$active_task_list_arr[$task_cnt]['block_end_date'] = $block_end_date;                
        		$active_task_list_arr[$task_cnt]['color_code'] = $task_list_data->color_code;
                $active_task_list_arr[$task_cnt]['block_start_date'] = $task_list_data->block_start_date;
                $active_task_list_arr[$task_cnt]['block_end_date'] = $task_list_data->block_end_date;
                $active_task_list_arr[$task_cnt]['block_payment_amount'] = $task_list_data->block_payment_amount;
                $active_task_list_arr[$task_cnt]['phase_name'] = $task_list_data->phase_name;
                $active_task_list_arr[$task_cnt]['task_done'] = $task_list_data->task_done;                         
        		$task_cnt++;
        	}                                                                            
        }
        
        $data['active_task_list_arr'] = $active_task_list_arr;
        /*echo "<pre>";
        print_r($task_result);exit;*/
        if($block_type_code == 'input'){
            echo $this->load->view('project/execution_input_block_html',$data,true);
        }elseif($block_type_code == 'meeting'){
            echo $this->load->view('project/execution_meeting_block_html',$data,true);                    
        }elseif($block_type_code == 'proposal'){
            echo $this->load->view('project/execution_proposal_block_html',$data,true);                    
        }elseif($block_type_code == 'payment'){
            echo $this->load->view('project/execution_payment_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback'){
            echo $this->load->view('project/execution_feedback_block_html',$data,true);                    
        }elseif($block_type_code == 'feedback-cycle'){
            echo $this->load->view('project/execution_feedback_cycle_block_html',$data,true);                    
        }elseif($block_type_code == 'approval'){
            echo $this->load->view('project/execution_approval_block_html',$data,true);                    
        }elseif($block_type_code == 'milestone'){
            echo $this->load->view('project/execution_milestone_block_html',$data,true);                    
        }
        echo '^^1';exit;
    }
    
    function fn_update_execution_block(){
        $data['project_sk'] = $project_sk = $this->input->post('project_sk');
        $data['block_sk'] = $block_sk = $this->input->post('block_sk');
        $block_sk = $this->project_model->fn_update_execution_block();
        echo '1^^1';exit;
    }
    
    function fn_task_mark_as_completed(){
        $data['tasks_sk'] = $tasks_sk = $this->input->post('tasks_sk');
        $tasks_sk = $this->project_model->fn_task_mark_as_completed($tasks_sk);
        echo '1^^1';exit;
    }
    
    function fn_task_mark_as_incomplete(){
        $data['tasks_sk'] = $tasks_sk = $this->input->post('tasks_sk');
        $tasks_sk = $this->project_model->fn_task_mark_as_incomplete($tasks_sk);
        echo '1^^1';exit;
    }
    
    function fn_task_pay_now(){
        $data['tasks_sk'] = $tasks_sk = $this->input->post('tasks_sk');
        $data['payment_amount'] = $payment_amount = $this->input->post('payment_amount');
        $tasks_sk = $this->project_model->fn_task_pay_now($tasks_sk,$payment_amount);
        echo '1^^1';exit;
    }
    
    function fn_task_unpaid(){
        $data['tasks_sk'] = $tasks_sk = $this->input->post('tasks_sk');
        $data['payment_amount'] = $payment_amount = $this->input->post('payment_amount');
        $tasks_sk = $this->project_model->fn_task_unpaid($tasks_sk,$payment_amount);
        echo '1^^1';exit;
    }
    
    function fn_sign_agreement_document(){
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['project_sk'] = $project_sk = $this->input->post('project_sk');
        
        $ret_str = $this->fn_get_download_agreement($project_sk,'yes');
        $ret_str_arr = explode('###',$ret_str);
        $succ_ret_str = $ret_str_arr[0];
        $download_on_server_pdf_name = $ret_str_arr[1];     
        if($succ_ret_str == 'succ'){
            $result = $this->users_model->fn_get_all_site_constants();
			$get_all_site_constants_record_count = $result['rows'];
			$q_get_all_site_constants = $result['data'];
            
            $agreement_result = $this->project_model->fn_get_agreement_project_detail_by_project_sk($project_sk);
            $agreement_record_count = $agreement_result['rows'];
			$q_get_agreement_project_data = $agreement_result['data'];            
            if($agreement_record_count > 0){
                $sign_document_url = $this->config->item("UPLOAD_URL").'agreement_pdf/'.$download_on_server_pdf_name;
                $from_email = $q_get_all_site_constants[0]->from_email;
                $from_email_name = $q_get_all_site_constants[0]->from_name;
                $agreement_created_by = $q_get_agreement_project_data[0]->created_by;
                $project_client_sk = $q_get_agreement_project_data[0]->client_sk;
                $project_agency_sk = $q_get_agreement_project_data[0]->agency_sk;                
                $agreement_contract_name = $q_get_agreement_project_data[0]->project_name;                 
                if($agreement_created_by == $project_client_sk){
                    $first_signer_users_sk = $project_client_sk;
                    $second_signer_users_sk = $project_agency_sk; 
                }else{
                    $first_signer_users_sk = $project_agency_sk;
                    $second_signer_users_sk = $project_client_sk;
                }               
                /*start first signer data*/ 
                $first_signer_result = $this->users_model->fn_get_user_detail_by_users_sk($first_signer_users_sk);
                $first_signer_record_count = $first_signer_result['rows'];
                $get_first_signer_data = $first_signer_result['data'];                
                $first_signer_email = $get_first_signer_data[0]->email;
                $first_signer_first_name = $get_first_signer_data[0]->first_name;
                $first_signer_last_name = $get_first_signer_data[0]->last_name;
                $first_signer_display_name = $get_first_signer_data[0]->first_name.''.$get_first_signer_data[0]->last_name;
                $first_signer_sk = $first_signer_users_sk;
                /*end first signer data*/
                /*start second signer data*/
                $second_signer_result = $this->users_model->fn_get_user_detail_by_users_sk($second_signer_users_sk);
                $second_signer_record_count = $second_signer_result['rows'];
                $get_second_signer_data = $second_signer_result['data'];                
                $second_signer_email = $get_second_signer_data[0]->email;
                $second_signer_first_name = $get_second_signer_data[0]->first_name;
                $second_signer_last_name = $get_second_signer_data[0]->last_name;
                $second_signer_display_name = $get_second_signer_data[0]->first_name.''.$get_second_signer_data[0]->last_name;
                $second_signer_sk = $second_signer_users_sk;
                /*end second signer data*/    
                
                if($first_signer_email != '' && $first_signer_first_name != '' && $first_signer_last_name != '' && $first_signer_display_name != '' && $second_signer_email != '' && $second_signer_first_name != '' && $second_signer_last_name != '' && $second_signer_display_name != ''){
                    $agreement_sign_sk = $this->general->generate_primary_key();
                    $insert_agreement_sign['agreement_sign_sk'] = $agreement_sign_sk;
                    $insert_agreement_sign['project_sk'] = $project_sk;
                    $insert_agreement_sign['from_email'] = $from_email;
                    $insert_agreement_sign['from_email_name'] = $from_email_name;
                    $insert_agreement_sign['agreement_contract_name'] = $agreement_contract_name;
                    $insert_agreement_sign['first_signer_sk'] = $first_signer_users_sk;
                    $insert_agreement_sign['first_signer_email'] = $first_signer_email;
                    $insert_agreement_sign['first_signer_first_name'] = $first_signer_first_name;
                    $insert_agreement_sign['first_signer_last_name'] = $first_signer_last_name;
                    $insert_agreement_sign['first_signer_display_name'] = $first_signer_display_name;
                    $insert_agreement_sign['second_signer_sk'] = $second_signer_users_sk;
                    $insert_agreement_sign['second_signer_email'] = $second_signer_email;
                    $insert_agreement_sign['second_signer_first_name'] = $second_signer_first_name;
                    $insert_agreement_sign['second_signer_last_name'] = $second_signer_last_name;
                    $insert_agreement_sign['second_signer_display_name'] = $second_signer_display_name;                    
                    $insert_agreement_sign['created_by'] = $logged_in_users_sk;
                    $insert_agreement_sign['created_on'] = date('Y-m-d H:i:s');                    
                    $this->project_model->fn_insert_agreement_sign($insert_agreement_sign);
                                                                                                             
                    $ret_arr = $this->signrequest->send_sign_request_to_users($from_email,$from_email_name,$agreement_sign_sk,$project_sk,$agreement_contract_name,$sign_document_url,$first_signer_sk,$first_signer_email,$first_signer_first_name,$first_signer_last_name,$first_signer_display_name,$second_signer_sk,$second_signer_email,$second_signer_first_name,$second_signer_last_name,$second_signer_display_name);
                    
                    if($ret_arr['status'] == 'succ'){
                        $update_agreement_sign['sign_request_uuid'] = $ret_arr['uuid'];
                        $update_agreement_sign['sign_request_document_uuid'] = $ret_arr['document_uuid'];                                                        
                        $update_agreement_sign['first_signer_email_viewed'] = $ret_arr['first_signer_email_viewed'];
                        $update_agreement_sign['first_signer_viewed'] = $ret_arr['first_signer_viewed'];
                        $update_agreement_sign['first_signer_signed'] = $ret_arr['first_signer_signed'];
                        $update_agreement_sign['first_signer_downloaded'] = $ret_arr['first_signer_downloaded'];
                        $update_agreement_sign['first_signer_embed_url'] = $ret_arr['first_signer_embed_url'];                             
                        $update_agreement_sign['second_signer_email_viewed'] = $ret_arr['second_signer_email_viewed'];
                        $update_agreement_sign['second_signer_viewed'] = $ret_arr['second_signer_viewed'];
                        $update_agreement_sign['second_signer_signed'] = $ret_arr['second_signer_signed'];
                        $update_agreement_sign['second_signer_downloaded'] = $ret_arr['second_signer_downloaded'];
                        $update_agreement_sign['second_signer_embed_url'] = $ret_arr['second_signer_embed_url'];
                        $update_agreement_sign['modified_by'] = $logged_in_users_sk;
                        $update_agreement_sign['modified_on'] = date('Y-m-d H:i:s');
                        $this->project_model->fn_update_agreement_sign($update_agreement_sign,$agreement_sign_sk);
                        
                        echo $ret_arr['first_signer_embed_url'];
                        echo '^^1';exit;
                    }else{
                        echo 'Error when calling sign request api. Plase contact administrator.';
                        //echo $ret_arr['msg'];
                        echo '^^2';exit;
                    }
                }else{
                    echo 'Cannot proceed with signing the agreement as client has not yet signed up';
                    echo '^^2';exit;                            
                }
            }                            
        }    
    }
    
    function fn_second_signer_sign_agreement_document(){
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['agreement_sign_sk'] = $agreement_sign_sk = $this->input->post('agreement_sign_sk');
        
        $agreement_sign_result = $this->project_model->fn_get_agreement_sign_detail($agreement_sign_sk);
        $data['agreement_sign_rows'] = $agreement_sign_rows = $agreement_sign_result['rows']; 
        $data['agreement_sign_data'] = $agreement_sign_data = $agreement_sign_result['data'];
        
        echo $agreement_sign_data[0]->second_signer_embed_url;
        echo '^^1';exit;
    }
    
    function fn_check_sign_request_of_users(){
        $uuid = '2652449c-ce02-4fa9-875d-a81191cc1c4e';
        $ret_arr = $this->signrequest->check_sign_request_of_users($uuid);
        echo "<pre>";print_r($ret_arr);exit;
    } 
    
    function fn_check_download_document_from_sign_request(){
        $uuid = '1570d4ca-23fb-4fd0-bc46-dc58e0db615f';
        $ret_arr = $this->signrequest->fn_check_download_document_from_sign_request($uuid);
        echo "<pre>";print_r($ret_arr);exit;
    }
    
    function fn_get_select_phase_and_block_detail(){
        $data['phase_sk'] = $phase_sk = $this->input->post('phase_sk');
        $cluster_sk = '';
        $project_sk = '';
        
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
        $data['phase_rows'] = $phase_rows = $phase_result['rows']; 
        $data['q_phase_data'] = $q_phase_data = $phase_result['data'];
        
        $pre_defined_block_type_result = $this->project_model->fn_get_all_pre_defined_block_type();
        $data['pre_defined_block_type_rows'] = $pre_defined_block_type_rows = $pre_defined_block_type_result['rows']; 
        $data['q_pre_defined_block_type_data'] = $q_pre_defined_block_type_data = $pre_defined_block_type_result['data'];
        
        $data['cluster_sk'] = $cluster_sk = $q_phase_data[0]->cluster_sk;
        $data['project_sk'] = $project_sk = $q_phase_data[0]->project_sk;
        
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $logged_in_user_result = $this->users_model->fn_get_user_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_user_rows'] = $logged_in_user_rows = $logged_in_user_result['rows']; 
        $data['logged_in_user_data'] = $logged_in_user_data = $logged_in_user_result['data'];
        
        $logged_in_company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($logged_in_users_sk);
        $data['logged_in_company_rows'] = $logged_in_company_rows = $logged_in_company_result['rows']; 
        $data['logged_in_company_data'] = $logged_in_company_data = $logged_in_company_result['data'];
        
        $data['logged_in_user_type'] = $logged_in_user_type = $logged_in_user_data[0]->user_type;
        $data['logged_in_is_agency_verified'] = $logged_in_is_agency_verified = $logged_in_company_data[0]->is_agency_verified;
        $data['logged_in_verify_later'] = $logged_in_verify_later = $logged_in_company_data[0]->verify_later;
        $data['logged_in_applied_for_manual_verification'] = $logged_in_applied_for_manual_verification = $logged_in_company_data[0]->applied_for_manual_verification;
                 
        echo $this->load->view('project/select_phase_and_block_detail_html',$data,true);                    
        echo '^^1';exit;
    }
    
    function fn_update_phase_side_panel(){
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }        
        $this->form_validation->set_rules('phase_name', 'Phase Name', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){
            $phase_sk = $this->project_model->fn_update_phase_side_panel();
            echo '1^^1';exit;                
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_select_phase_and_block_detail_for_execution(){
        $data['phase_sk'] = $phase_sk = $this->input->post('phase_sk');
        $cluster_sk = '';
        $project_sk = '';
        
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
        $data['phase_rows'] = $phase_rows = $phase_result['rows']; 
        $data['q_phase_data'] = $q_phase_data = $phase_result['data'];
        
        $pre_defined_block_type_result = $this->project_model->fn_get_all_pre_defined_block_type();
        $data['pre_defined_block_type_rows'] = $pre_defined_block_type_rows = $pre_defined_block_type_result['rows']; 
        $data['q_pre_defined_block_type_data'] = $q_pre_defined_block_type_data = $pre_defined_block_type_result['data'];
        
        $data['cluster_sk'] = $cluster_sk = $q_phase_data[0]->cluster_sk;
        $data['project_sk'] = $project_sk = $q_phase_data[0]->project_sk;
         
        echo $this->load->view('project/select_phase_and_block_detail_for_execution_html',$data,true);                    
        echo '^^1';exit;
    }
    
    function fn_get_create_cluster_popup(){
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $user_result = $this->users_model->fn_get_all_users_for_create_project($logged_in_users_sk);
        $data['user_rows'] = $user_rows = $user_result['rows']; 
        $data['user_data'] = $user_data = $user_result['data'];
    
        $data['company_image'] = ''; 
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/create_cluster',$data,true);
        echo '^^1';exit;
    }
    
    function fn_insert_cluster(){
        #echo "aa";exit;
        $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }          
        $sel_client = $this->input->post('sel_client');
        $new_client_email = $this->input->post('new_client_email');
        $new_client_company_name = $this->input->post('new_client_company_name');
        if($sel_client == '' && $new_client_email == '' && $new_client_company_name == ''){
            $this->form_validation->set_rules('sel_client', $user_login_mode_off, 'required');
        }
                                                                     
        $this->form_validation->set_rules('cluster_title', 'Cluster Title', 'required');
        $this->form_validation->set_rules('contact_person_name', 'Contact Person', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run()){     
            $insert_project = 'yes';
            $user_login_mode = $this->session->userdata('fe_user_login_mode');
            if($user_login_mode == 'AGENCY'){
                if($sel_client == '' && $new_client_email != '' && $new_client_company_name != ''){
                    $user_email_result = $this->users_model->fn_get_user_detail_by_email($new_client_email);
                    $user_rows = $user_email_result['rows']; 
                    $user_data = $user_email_result['data'];
                    if($user_rows > 0){
                        $insert_project = 'no';
                        echo '2^^2';exit;                            
                    }                            
                }
            }                                            
            if($insert_project == 'yes'){
                $cluster_sk = $this->project_model->fn_insert_cluster();
                /* image upload start */
                $create_dir_path = $this->config->item('UPLOAD_DIR').'/cluster_image';
                if(!is_dir($create_dir_path)){
                    mkdir($create_dir_path, 0777, true);
                }
                if(!empty($_FILES['cluster_image']['name'])){
                    $config['upload_path'] = $this->config->item('UPLOAD_DIR').'/cluster_image/';
        			$tmp = explode(".", $_FILES['cluster_image']['name']);
                    $ext = end($tmp);
        			$config['file_name'] = 'cluster_image_'.$this->general->generate_random_letters(4).'.'.$ext;
        			
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->set_allowed_types('*');
                    $data['upload_data'] = '';
                }
                //if not successful, set the error message
        		if ($this->upload->do_upload('cluster_image')) {
                    $upload_data = $this->upload->data();
                    $uploaded_file_name = $upload_data['file_name'];
                    $result = $this->project_model->fn_update_cluster_image($uploaded_file_name,$cluster_sk);
                }            
                /* image upload end */                
                $ret_data['cluster_sk'] = $cluster_sk;
                echo $this->load->view('project/create_cluster_project',$ret_data,true);
                echo '^^1';exit;
            }else{
                echo '2^^2';exit;    
            }                                        
        }else{
			$data['errors'] = validation_errors();
            echo strip_tags($data['errors']);exit;
		}
    }
    
    function fn_get_create_cluster_project_popup(){
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $user_result = $this->users_model->fn_get_all_users_for_create_project($logged_in_users_sk);
        $data['user_rows'] = $user_rows = $user_result['rows']; 
        $data['user_data'] = $user_data = $user_result['data'];
    
        $data['company_image'] = ''; 
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/create_cluster_project',$data,true);
        echo '^^1';exit;
    }
    
    function fn_post_create_cluster_project_popup(){
        $cluster_sk = $this->input->post('cluster_sk');
        $project_title = $this->input->post('project_title');
        $post_phase_arr = $this->input->post('phase_arr');
        $phase_arr = json_decode($post_phase_arr,true);
        /*echo "<pre>";
        print_r($phase_arr);exit;*/
        if($project_title != ''){
            if(count($phase_arr) > 0){
                $project_sk = $this->project_model->fn_insert_project_new_popup();
                if($project_sk != ''){
                    $ret_result = $this->project_model->fn_insert_phases_from_new_popup($cluster_sk,$project_sk,$phase_arr);    
                }
                echo $this->config->item('site_url').'project-detail/'.$project_sk.'^^1';exit;
            }else{
                $data['errors'] = 'Phase cannot be blank.';
                echo strip_tags($data['errors']);exit;
            }    
        }else{
            $data['errors'] = 'Project Title cannot be blank.';
            echo strip_tags($data['errors']);exit;            
        }        
    }
    
    function fn_get_create_agreement_with_phases_popup(){
        $data['cluster_sk'] = $cluster_sk = $this->input->post('cluster_sk');
        $data['logged_in_users_sk'] = $logged_in_users_sk = $this->session->userdata('fe_user');
        $data['user_login_mode'] = $user_login_mode = $this->session->userdata('fe_user_login_mode');
        if($user_login_mode == 'AGENCY'){
            $user_login_mode_off = 'Client';
        }else{
            $user_login_mode_off = 'Agency';
        }
        
        $project_result = $this->project_model->fn_get_cluster_detail_by_cluster_sk($cluster_sk);
        $data['project_rows'] = $project_rows = $project_result['rows']; 
        $data['project_data'] = $project_data = $project_result['data'];
        
        if($user_login_mode == 'AGENCY'){
            $users_sk = $project_data[0]->client_sk;
        }else{
            $users_sk = $project_data[0]->agency_sk;
        }
        $company_result = $this->users_model->fn_get_user_company_detail_by_users_sk($users_sk);
        $data['company_rows'] = $company_rows = $company_result['rows']; 
        $data['company_data'] = $company_data = $company_result['data'];
        
        $data['user_login_mode_off'] = $user_login_mode_off; 
        echo $this->load->view('project/create_agreement_with_phases_popup',$data,true);
        echo '^^1';exit;
    } 
    
    function fn_post_create_agreement_with_phases_popup(){
        $cluster_sk = $this->input->post('cluster_sk');
        $project_title = $this->input->post('project_title');
        $post_phase_arr = $this->input->post('phase_arr');
        $phase_arr = json_decode($post_phase_arr,true);
        /*echo "<pre>";
        print_r($phase_arr);exit;*/
        if($project_title != ''){
            if(count($phase_arr) > 0){
                $project_sk = $this->project_model->fn_insert_project_new_popup();
                if($project_sk != ''){
                    $ret_result = $this->project_model->fn_insert_phases_from_new_popup($cluster_sk,$project_sk,$phase_arr);    
                }
                echo $this->config->item('site_url').'project-detail/'.$project_sk.'^^1';exit;
            }else{
                $data['errors'] = 'Phase cannot be blank.';
                echo strip_tags($data['errors']);exit;
            }    
        }else{
            $data['errors'] = 'Project Title cannot be blank.';
            echo strip_tags($data['errors']);exit;            
        }        
    }
    
    function fn_check_block_going_out_of_phase(){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $block_sk = $this->input->post('block_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $block_start_date = date('Y-m-d', strtotime($start_date));
        $block_end_date = date('Y-m-d', strtotime($end_date));
        $display_block_start_date = date('m/d/Y', strtotime($start_date));
        $display_block_end_date = date('m/d/Y', strtotime($end_date));
        
        $out_of_phase = 'no';
        $start_date_out_of_phase = 'no';
        $end_date_out_of_phase = 'no';
        $real_phase_name = '';
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $real_phase_start_date = $phase_data[0]->start_date;
            $real_phase_end_date = $phase_data[0]->end_date;
            $real_phase_name = $phase_data[0]->phase_name;
            if(strtotime($real_phase_start_date) > strtotime($block_start_date)){
                $start_date_out_of_phase = 'yes';
                $out_of_phase = 'yes';
            }
            if(strtotime($real_phase_end_date) < strtotime($block_end_date)){
                $end_date_out_of_phase = 'yes';
                $out_of_phase = 'yes';
            }
        }
        
        if($out_of_phase == 'yes'){
            if($start_date_out_of_phase == 'yes' && $end_date_out_of_phase == 'yes'){
                echo 'Selected timeline of the block will need to change the start date and end date of <b>'.$real_phase_name.'</b> phase to <b>'.$display_block_start_date.'</b> and <b>'.$display_block_end_date.'</b>.<br /><br />Please confirm that you want to proceed.';    
            }elseif($start_date_out_of_phase == 'yes' && $end_date_out_of_phase == 'no'){
                echo 'Selected timeline of the block will need to change the start date of <b>'.$real_phase_name.'</b> phase to <b>'.$display_block_start_date.'</b>.<br /><br />Please confirm that you want to proceed.';
            }elseif($start_date_out_of_phase == 'no' && $end_date_out_of_phase == 'yes'){
                echo 'Selected timeline of the block will need to change the end date of <b>'.$real_phase_name.'</b> phase to <b>'.$display_block_end_date.'</b>.<br /><br />Please confirm that you want to proceed.';
            }
            echo '^^1';exit;
        }else{
            echo 'no^^2';exit;    
        }
    }
    
    function fn_check_phase_not_covering_all_blocks(){
        $logged_in_users_sk = $this->session->userdata('fe_user');
        $project_sk = $this->input->post('project_sk');
        $phase_sk = $this->input->post('phase_sk');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $phase_start_date = date('Y-m-d', strtotime($start_date));
        $phase_end_date = date('Y-m-d', strtotime($end_date));
        
        $phase_not_covering_all_blocks = 'no';
        $phase_result = $this->project_model->fn_get_phase_detail_by_phase_sk($phase_sk);
		$phase_rows = $phase_result['rows'];
		$phase_data = $phase_result['data'];
        if($phase_rows > 0){
            $first_inserted_block_result = $this->project_model->fn_get_first_inserted_block_by_phase($phase_sk);
            $first_inserted_block_rows = $first_inserted_block_result['rows']; 
            $q_first_inserted_block_data = $first_inserted_block_result['data'];                
            if($first_inserted_block_rows > 0){ 
                $first_inserted_block_start_date = $q_first_inserted_block_data[0]->start_date;
                if(strtotime($phase_start_date) > strtotime($first_inserted_block_start_date)){
                    $phase_not_covering_all_blocks = 'yes';
                }
            }
            $last_inserted_block_result = $this->project_model->fn_get_last_inserted_block_by_phase($phase_sk);
            $last_inserted_block_rows = $last_inserted_block_result['rows']; 
            $q_last_inserted_block_data = $last_inserted_block_result['data'];                
            if($last_inserted_block_rows > 0){
                $last_inserted_block_end_date = $q_last_inserted_block_data[0]->end_date;
                if(strtotime($last_inserted_block_end_date) > strtotime($phase_end_date)){ 
                    $phase_not_covering_all_blocks = 'yes';
                }                    
            }
            
        }
        
        if($phase_not_covering_all_blocks == 'yes'){
            echo "The new timeline of the phase doesn't contain all the existing blocks. Please adjust the blocks first as per your plans and then try to change the timeline of the phase.";
            echo '^^1';exit;
        }else{
            echo 'no^^2';exit;    
        }
    }
                                                                                                                                                
}
