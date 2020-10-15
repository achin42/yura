<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General {
    
    function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->set_site_constant_data();
    }
    
    function set_site_constant_data(){
        $site_constants_id = '1';
        $this->ci->db->where('site_constants_id',$site_constants_id);
		$query = $this->ci->db->get('site_constants');
		$rows = $query->num_rows();
		$data = $query->result();
		
        $result_arr = array();
        if($rows > 0){
            $result_arr = $data[0];        
            foreach ($result_arr as $key => $val){
                if($key == 'upload_url'){
                    $key = 'UPLOAD_URL';
                }
                $this->ci->config->set_item($key,$val);
			}
        }             
	}
    
    function generate_primary_key() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
    
    function generate_random_letters($length) {
		$random = '';
		for ($i = 0; $i < $length; $i++) {
			$random .= chr(rand(ord('a'), ord('z')));
		}
		return $random;
	}
    
    function display_date_format_in_input($date='') {
        $ret_date = '';
		if($date != ''){
            $ret_date = date('m/d/Y',strtotime($date));
        }
		return $ret_date;
	}
    
    function display_date_format_front($date='') {
        $ret_date = '';
		if($date != ''){
            $ret_date = date('dS M Y', strtotime($date));
        }
		return $ret_date;
	}
    
    function date_diff_in_days($date1, $date2){ 
        // Calulating the difference in timestamps 
        $diff = strtotime($date2) - strtotime($date1); 
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        return abs(round($diff / 86400)); 
    }
    
    function get_dates_between_two_dates($start, $end, $format = 'Y-m-d') { 
          
        // Declare an empty array 
        $array = array(); 
          
        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        // Use loop to store date into array 
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        // Return the array elements 
        return $array; 
    } 
    
    function get_month_wise_days_count_between_two_dates($start, $end) {
        $output = [];
        $time   = strtotime($start);
        $last   = date('m-Y', strtotime($end));
        
        do {
            $month = date('m-Y', $time);
            $total = date('t', $time);
        
            $output[] = [
                //'month' => $month,
                'total' => $total,
            ];
        
            $time = strtotime('+1 month', $time);
        } while ($month != $last);
        
        return $output; 
    } 
    
    function display_overdue_date_in_task_list($date='') {
        $ret_date_str = '';
        $today_date = date('Y-m-d');
        $tomorrow_date = date("Y-m-d", strtotime("+1 day"));        
		if($date != ''){
            if($date == $today_date){
                $ret_date_str = 'due today';
            }else{
                $day_diff = $this->date_diff_in_days($date, $today_date);
                $ret_date_str = 'due by '.$day_diff.' days';
                //$ret_date_str = 'due on '.$this->display_date_format_front($date);
            }
            $ret_date = date('dS M Y', strtotime($date));
        }
		return $ret_date_str;
	}
    
    function display_due_date_in_task_list($date='') {
        $ret_date_str = '';
        $today_date = date('Y-m-d');
        $tomorrow_date = date("Y-m-d", strtotime("+1 day"));        
		if($date != ''){
            if($date == $today_date){
                $ret_date_str = 'due today';
            }elseif($date == $tomorrow_date){
                $ret_date_str = 'due tomorrow';
            }else{
                $ret_date_str = 'due on '.$this->display_date_format_front($date);
            }
            $ret_date = date('dS M Y', strtotime($date));
        }
		return $ret_date_str;
	}
    
    function fn_email_user_signup_mail_html($email=''){
        $base64_encode_email = base64_encode($email);
        $link = $this->ci->config->item('site_url').'activate.html?link='.$base64_encode_email;
        $activation_link = '<a href="'.$link.'" target="_blank">Click Here</a>';
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					Please click below link to activate your account.<br/><br/>
                                                                                    '.$activation_link.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function fn_email_admin_signup_mail_html($user_name='',$user_email='',$company_name=''){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
    							<tr>
    								<td class="editable" align="center">
    									<table align="center" cellspacing="0" cellpadding="0" border="0">
    										<tr>
    											<td align="center" width="600">
    												<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    													<tr>
    														<td height="25">
    														</td>
    													</tr>
    													<tr>
    														<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
    														</td>
    													</tr>
    													<tr>
    														<td height="10">
    														</td>
    													</tr>
    												</table>
    												<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    													<tr>
    														<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
    															<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td height="20">
    																	</td>
    																</tr>
    																<tr>
    																	<td align="center">
        																	<!--logo-->
        				
        																	<table align="center" cellspacing="0" cellpadding="0" border="0">
        																		<tr>
        																			<td style="line-height: 0px;" align="center">
        																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
        																			</td>
        																		</tr>
        																	</table>
        																	<!--end logo-->
        				
        																</td>
    																</tr>
    																<tr>
    																	<td height="20">
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    													<tr>
    														<td align="center" style="background-color:#FFFFFF;">
    															<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    																<!--content-->
    																<tr>
    																	<td align="center">
    																		<table class="textbutton" style="border:0px solid #000;width:100%;" align="center" cellspacing="0" cellpadding="0" border="0" width="100%">
    																			<tr align="left">
    																				<td data-link-style="text-decoration:none; color:#000;width:100%;" width="100%" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
    																						Good news! A User has just signed up.<br/><br/>
    																						<table style="border:1px solid #ebebeb; width:80%;font-family: \'Century Gothic\', Arial, sans-serif;" cellpadding="5" cellspacing="0" width="80%" >
    																							<tbody>
    																							<tr>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;">Full name</td>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;">'.$user_name.'</td>
    																							</tr>
    																							<tr>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;">Email address</td>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;"><a href="mailto:'.$user_email.'" target="_blank">'.$user_email.'</a></td>
    																							</tr>
                                                                                                <tr>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;">Company Name</td>
    																								<td style="border-bottom:1px solid #dddddd;background-color:#fff;font-family: \'Century Gothic\', Arial, sans-serif;">'.$company_name.'</td>
    																							</tr>
    																							</tbody>
    																							</table>
    																				</td>
    																			</tr>
    																		</table>
    																	</td>
    																</tr>
    																<!--end content-->
    																
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    																<!--button-->
    					
    																
    																<!--end button-->
    					
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    													
    												</table>
    												<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
    													<tr>
    														<td align="center">
    															<table align="center" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td align="center" width="600">
    																		<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    																			<tr>
    																				<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
    																				
    																				</td>
    																			</tr>
    																			<tr>
    																				<td height="25">
    																				</td>
    																			</tr>
    																			<tr>
    																				<td align="center">
    																					<table align="center" cellspacing="0" cellpadding="0" border="0">
    																						<tr>
    																							<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
    																								Copyright &copy; '.date('Y').' All rights reserved.<br />
    																								<small>This is a system-generated mail. Please do not reply to this email address.</small>
    																							</td>
    																						</tr>
    																					</table>
    																				</td>
    																			</tr>
    																			<tr>
    																				<td height="35">
    																				</td>
    																			</tr>
    																		</table>
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    												</table>
    											</td>
    										</tr>
    									</table>
    								</td>
    							</tr>
    						</table>';
                    
        return $message;                            
    }
    
    function display_date_time_format_front($date='') {
        $ret_date = '';
        if($date != ''){
            $ret_date = date('dS M Y H:i:s', strtotime($date));
        }
        return $ret_date;
    }
    
    function display_agreement_status_old($project_sk='') {
        $ret_date_str = '';
        $completed_block_record_count = 0;
        
        $block_result = $this->ci->project_model->fn_get_all_blocks_by_project($project_sk);
        $total_block_record_count = $block_result['rows'];
        $q_get_all_blocks = $block_result['data'];                                    
        if($total_block_record_count > 0){
                $incomplete_block_result = $this->ci->project_model->fn_check_all_blocks_completed_by_today($project_sk);
                $incomplete_block_record_count = $incomplete_block_result['rows'];
                $q_get_all_incomplete_blocks = $incomplete_block_result['data'];                                    
                if($incomplete_block_record_count > 0){
                    $first_block_result = $this->ci->project_model->fn_get_first_block_of_project($project_sk);
                    $first_block_record_count = $first_block_result['rows'];
                    $q_get_first_block = $first_block_result['data'];                                    
                    if($first_block_record_count > 0){
                        $today_date = date('Y-m-d');
                        //$today_date = '2020-06-01';
                        $first_block_start_date = $q_get_first_block[0]->start_date;
                        if( strtotime($first_block_start_date) > strtotime($today_date) ){
                            $day_diff = $this->date_diff_in_days($first_block_start_date, $today_date);
                            $ret_date_str = '<em>scheduled to start in '.$day_diff.' days</em><span class="gradient-border-gray"></span>';
                        }else{      
                            $incomplete_task_result = $this->ci->project_model->fn_get_all_incomplete_task_by_project_sk($project_sk);
                            $incomplete_task_record_count = $incomplete_task_result['rows'];
                            $q_get_all_incomplete_task = $incomplete_task_result['data'];                                    
                            if($incomplete_task_record_count > 0){
                                $task_end_date = $q_get_all_incomplete_task[0]->end_date;
                                $today_date = date('Y-m-d'); 
                                $day_diff = $this->date_diff_in_days($task_end_date, $today_date);
                                $total_task_record_count = 0;
                                
                                $all_task_result = $this->ci->project_model->fn_get_all_task_by_project_sk($project_sk);
                                $all_task_record_count = $all_task_result['rows'];
                                $q_get_all_task = $all_task_result['data'];                                    
                                if($all_task_record_count > 0){
                                    $total_task_record_count = $all_task_record_count;
                                }                                
                                $completed_task_percentage = ceil(($incomplete_task_record_count * 100) / $total_task_record_count);
                                if($completed_task_percentage > 0){
                                    $style_tag_width = 'style="width:'.$completed_task_percentage.'%;" ';
                                }else{
                                    $style_tag_width = 'style="width:2%;" ';
                                }
                                    
                                if($day_diff == '1'){
                                    $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' day late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                                }else{
                                    $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' days late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                                }    
                            }else{
                                $completed_block_record_count = $total_block_record_count - $incomplete_block_record_count;
                                $completed_percentage = ceil(($completed_block_record_count * 100) / $total_block_record_count);
                                //$ret_date_str = $total_block_record_count.'com'.$completed_block_record_count.'inco'.$incomplete_block_record_count.'per'.$completed_percentage;
                                if($completed_percentage > 0){
                                    $style_tag_width = 'style="width:'.$completed_percentage.'%;" ';
                                    $ret_date_str = '<em>running on time</em><span class="gradient-border-green" '.$style_tag_width.'></span>';
                                }else{
                                    $ret_date_str = '<em>running on time</em><span class="gradient-border-green" style="width:2%" ></span>';
                                }
                            }                                                                                      
                        }                                                         
                    }                        
                }else{
                    $incomplete_task_result = $this->ci->project_model->fn_get_all_incomplete_task_by_project_sk($project_sk);
                    $incomplete_task_record_count = $incomplete_task_result['rows'];
                    $q_get_all_incomplete_task = $incomplete_task_result['data'];                                    
                    if($incomplete_task_record_count > 0){
                        $task_end_date = $q_get_all_incomplete_task[0]->end_date;
                        $today_date = date('Y-m-d'); 
                        $day_diff = $this->date_diff_in_days($task_end_date, $today_date);
                        $total_task_record_count = 0;
                                
                        $all_task_result = $this->ci->project_model->fn_get_all_task_by_project_sk($project_sk);
                        $all_task_record_count = $all_task_result['rows'];
                        $q_get_all_task = $all_task_result['data'];                                    
                        if($all_task_record_count > 0){
                            $total_task_record_count = $all_task_record_count;
                        }                                
                        $completed_task_percentage = ceil(($incomplete_task_record_count * 100) / $total_task_record_count);
                        if($completed_task_percentage > 0){
                            $style_tag_width = 'style="width:'.$completed_task_percentage.'%;" ';
                        }else{
                            $style_tag_width = 'style="width:2%;" ';
                        }
                            
                        if($day_diff == '1'){
                            $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' day late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                        }else{
                            $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' days late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                        }                                                            
                    }else{
                        $ret_date_str = '<em style="color: #ED7463;">completed</em><div class="add-line"><i class="fas fa-check"></i></div>';
                    }                    
                }                                    
        }else{
            $ret_date_str = '<em>No blocks added yet</em><span class="gradient-border-gray"></span>';
        }                                
        //$ret_date_str = '<em style="color: #ED7463;">running 2 days late</em><span class="gradient-border-orange"></span>';    
		return $ret_date_str;
	}
    
    function display_agreement_status($project_sk='') {
        $ret_date_str = '';
        
        $block_result = $this->ci->project_model->fn_get_all_blocks_by_project($project_sk);
        $total_block_record_count = $block_result['rows'];
        $q_get_all_blocks = $block_result['data'];                                    
        if($total_block_record_count > 0){
            $first_block_result = $this->ci->project_model->fn_get_first_block_of_project($project_sk);
            $first_block_record_count = $first_block_result['rows'];
            $q_get_first_block = $first_block_result['data'];                                    
            if($first_block_record_count > 0){
                $today_date = date('Y-m-d');
                //$today_date = '2020-06-01';
                $first_block_start_date = $q_get_first_block[0]->start_date;
                if( strtotime($first_block_start_date) > strtotime($today_date) ){
                    $day_diff = $this->date_diff_in_days($first_block_start_date, $today_date);
                    $ret_date_str = '<em>scheduled to start in '.$day_diff.' days</em><span class="gradient-border-gray"></span>';
                }else{
                    $all_task_result = $this->ci->project_model->fn_get_all_task_of_project($project_sk);
                    $all_task_record_count = $all_task_result['rows'];
                    $q_get_all_task = $all_task_result['data'];                                    
                    if($all_task_record_count > 0){
                        $incomplete_task_record_count = 0;
                        $complete_task_record_count = 0;
                        
                        $all_incomplete_task_result = $this->ci->project_model->fn_get_all_incomplete_task_of_project($project_sk);
                        $all_incomplete_task_record_count = $all_incomplete_task_result['rows'];
                        $q_get_all_incomplete_task = $all_incomplete_task_result['data'];                                    
                        if($all_incomplete_task_record_count > 0){
                            $incomplete_task_record_count = $all_incomplete_task_record_count;
                        }
                        
                        $all_complete_task_result = $this->ci->project_model->fn_get_all_complete_task_of_project($project_sk);
                        $all_complete_task_record_count = $all_complete_task_result['rows'];
                        $q_get_all_complete_task = $all_complete_task_result['data'];                                    
                        if($all_complete_task_record_count > 0){
                            $complete_task_record_count = $all_complete_task_record_count;
                        }        
                        
                        $completed_percentage = ceil(($complete_task_record_count * 100) / $all_task_record_count);
                        if($completed_percentage > 0){
                            $style_tag_width = 'style="width:'.$completed_percentage.'%;" ';
                        }else{
                            $style_tag_width = 'style="width:2%;" ';
                        }
                        
                        if($complete_task_record_count == $all_task_record_count){
                            $ret_date_str = '<em style="color: #ED7463;">completed</em><div class="add-line"><i class="fas fa-check"></i></div>';
                        }else{
                            $all_incomplete_task_by_agreement_result = $this->ci->project_model->fn_get_all_incomplete_task_by_project_sk($project_sk);
                            $all_incomplete_task_by_agreement_record_count = $all_incomplete_task_by_agreement_result['rows'];
                            $q_get_all_incomplete_task_by_agreement = $all_incomplete_task_by_agreement_result['data'];                                    
                            if($all_incomplete_task_by_agreement_record_count > 0){
                                $task_end_date = $q_get_all_incomplete_task_by_agreement[0]->end_date;
                                $today_date = date('Y-m-d'); 
                                $day_diff = $this->date_diff_in_days($task_end_date, $today_date);
                                if($day_diff == '1'){
                                    $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' day late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                                }else{
                                    $ret_date_str = '<em style="color: #ED7463;">running '.$day_diff.' days late</em><span class="gradient-border-orange" '.$style_tag_width.' ></span>';
                                } 
                            }else{
                                $ret_date_str = '<em>running on time</em><span class="gradient-border-green" '.$style_tag_width.'></span>';
                            }
                        }
                    }else{
                        $ret_date_str = '<em>No task added yet</em><span class="gradient-border-gray"></span>';
                    }    
                }                             
            }                                   
        }else{
            $ret_date_str = '<em>No blocks added yet</em><span class="gradient-border-gray"></span>';
        }                                            
		return $ret_date_str;
	}
    
    function get_random_color_code() {
		$arr = array("#4285F4","#000000","#55F49A","#ED7463","#E42890","#912AD1");
        $random_keys = array_rand($arr,1);
        $random = $arr[$random_keys];
		return $random;
	}
    
    function fn_email_second_agreement_signer_mail_html($agreement_contract_name,$first_signer_fullname,$second_signer_embed_url){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        $sign_request_link = '<a href="'.$second_signer_embed_url.'" target="_blank" style="min-width: 250px;background: #0034da;border: 1px solid #0034da;color: #fff;border-radius: 3px;font-weight: 700;padding: 8px 25px;font-size: 15px;line-height: 18px;box-shadow: none;text-decoration: none;">Click Here</a>';
        $sign_request_link = 'Please <a href="'.$second_signer_embed_url.'" target="_blank">click here</a> to sign.';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
    							<tr>
    								<td class="editable" align="center">
    									<table align="center" cellspacing="0" cellpadding="0" border="0">
    										<tr>
    											<td align="center" width="600">
    												<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    													<tr>
    														<td height="25">
    														</td>
    													</tr>
    													<tr>
    														<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
    														</td>
    													</tr>
    													<tr>
    														<td height="10">
    														</td>
    													</tr>
    												</table>
    												<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    													<tr>
    														<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
    															<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td height="20">
    																	</td>
    																</tr>
    																<tr>
    																	<td align="center">
        																	<!--logo-->
        				
        																	<table align="center" cellspacing="0" cellpadding="0" border="0">
        																		<tr>
        																			<td style="line-height: 0px;" align="center">
        																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
        																			</td>
        																		</tr>
        																	</table>
        																	<!--end logo-->
        				
        																</td>
    																</tr>
    																<tr>
    																	<td height="20">
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    													<tr>
    														<td align="center" style="background-color:#FFFFFF;">
    															<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    																<!--content-->
    																<tr>
    																	<td align="center">
    																		<table class="textbutton" style="border:0px solid #000;width:100%;" align="center" cellspacing="0" cellpadding="0" border="0" width="100%">
    																			<tr align="left">
    																				<td data-link-style="text-decoration:none; color:#000;width:100%;" width="100%" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
  																						'.$first_signer_fullname.' has sent you a signature request for '.$agreement_contract_name.' '.$sign_request_link.'<br/><br/>  																						
    																				</td>
    																			</tr>
    																		</table>
    																	</td>
    																</tr>
    																<!--end content-->
    																
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    																<!--button-->
    					
    																
    																<!--end button-->
    					
    																<tr>
    																	<td height="50">
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    													
    												</table>
    												<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
    													<tr>
    														<td align="center">
    															<table align="center" cellspacing="0" cellpadding="0" border="0">
    																<tr>
    																	<td align="center" width="600">
    																		<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
    																			<tr>
    																				<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
    																				
    																				</td>
    																			</tr>
    																			<tr>
    																				<td height="25">
    																				</td>
    																			</tr>
    																			<tr>
    																				<td align="center">
    																					<table align="center" cellspacing="0" cellpadding="0" border="0">
    																						<tr>
    																							<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
    																								Copyright &copy; '.date('Y').' All rights reserved.<br />
    																								<small>This is a system-generated mail. Please do not reply to this email address.</small>
    																							</td>
    																						</tr>
    																					</table>
    																				</td>
    																			</tr>
    																			<tr>
    																				<td height="35">
    																				</td>
    																			</tr>
    																		</table>
    																	</td>
    																</tr>
    															</table>
    														</td>
    													</tr>
    												</table>
    											</td>
    										</tr>
    									</table>
    								</td>
    							</tr>
    						</table>';
                    
        return $message;                            
    }
    
    function clean_string($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9.\-]/', '', $string); // Removes special chars.
    }
    
    function generate_random_code($length) {
		$random = '';
		for ($i = 0; $i < $length; $i++) {
			$random .= chr(rand(ord('0'), ord('9')));
		}
		return $random;
	}
    
    function fn_email_user_verify_account_mail_html($verification_code=''){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					Below is your 6 digit verification code.<br/><br/>
                                                                                    '.$verification_code.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function brex_country_filter($brex_country_list_arr){
        $country_list_arr = array();
        if(!empty($brex_country_list_arr)){
            $j = 0;
            for($i=0;$i<count($brex_country_list_arr);$i++){
                $country_code = $brex_country_list_arr[$i]['country_code'];
                $country_name = $brex_country_list_arr[$i]['country_name'];
                $find_word = 'US-';
                if(strpos($country_code, $find_word) !== false){
                    //echo "Word Found!";
                } else{
                    $country_list_arr[$j]['country_code'] = $country_code;
                    $country_list_arr[$j]['country_name'] = $country_name;
                    $j++;    
                } 
            }            
            usort($country_list_arr, array($this, "sortByOrder"));
        }    
        return $country_list_arr; 
    }
    
    function sortByOrder($a, $b) {
        //return $a['country_name'] - $b['country_name'];
        return strcasecmp($a["country_name"], $b["country_name"]);
    }
    
    function fn_email_invited_user_mail_html($email='',$invite_by_user_name='',$cluster_title=''){
        $base64_encode_email = base64_encode($email);
        $link = $this->ci->config->item('site_url').'invited-activation.html?link='.$base64_encode_email;
        $invite_activation_link = '<a href="'.$link.'" target="_blank" style="min-width: 250px;background: #0034da;border: 1px solid #0034da;color: #fff;border-radius: 3px;font-weight: 700;padding: 8px 25px;font-size: 15px;line-height: 18px;box-shadow: none;text-decoration: none;">Accept Invitation</a>';
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					You have been invited to Yura app by '.$invite_by_user_name.' to <br/>collaborate on '.$cluster_title.'<br/><br/>
                                                                                    '.$invite_activation_link.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function fn_email_agency_approved_mail_html(){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        $link = $this->ci->config->item('site_url').'dashboard/';
        $goto_dashboard_link = '<a href="'.$link.'" target="_blank" style="min-width: 250px;background: #0034da;border: 1px solid #0034da;color: #fff;border-radius: 3px;font-weight: 700;padding: 8px 25px;font-size: 15px;line-height: 18px;box-shadow: none;text-decoration: none;">Go to Dashboard</a>';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					We have verified the authenticity of company details you have provided. Now you can add payment blocks to C-Flows, as well as sign and execute the agreements.<br/><br/>
                                                                                    '.$goto_dashboard_link.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function fn_email_agency_rejected_mail_html($reason_for_rejection=''){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        $link = $this->ci->config->item('site_url').'agency-company-detail/';
        $check_company_details_link = '<a href="'.$link.'" target="_blank" style="min-width: 250px;background: #0034da;border: 1px solid #0034da;color: #fff;border-radius: 3px;font-weight: 700;padding: 8px 25px;font-size: 15px;line-height: 18px;box-shadow: none;text-decoration: none;">Check the Company details</a>';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					Your details couldn\'t be verified because of following reason<br/><br/>
                                                                                    <span style="color: #E6B546;font-weight: bold;background-color: #FEFAF1;padding: 15px;display: block;">'.$reason_for_rejection.'</span><br/>
                                                                                    You can recheck the details you have submitted and submit for verification again.<br/><br/>
                                                                                    '.$check_company_details_link.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function fn_email_error_handling_mail_html($jqxhr_status,$jqxhr_responsetext,$exception,$err_msg,$function_name){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        $site_url = $this->ci->config->item('site_url');
         
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					<b>jqXHR status:</b> '.$jqxhr_status.'<br/><br/>
                                                                                    <b>jqXHR response text:</b> '.$jqxhr_responsetext.'<br/><br/>
                                                                                    <b>Exception:</b> '.$exception.'<br/><br/>
                                                                                    <b>Error message:</b> '.$err_msg.'<br/><br/>
                                                                                    <b>Function name:</b> '.$function_name.'<br/><br/>
                                                                                    <b>Site URL:</b> '.$site_url.'<br/><br/>																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
    function fn_forgot_password_mail_html($forgot_password_user_verification_code=''){
        $logo_url = $this->ci->config->item('site_url').'assets/images/yuraio-mail-logo.png';
        
        $message = '<table data-module="header" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
						<tr>
							<td class="editable" align="center">
								<table align="center" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td align="center" width="600">
											<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td height="25">
													</td>
												</tr>
												<tr>
													<td data-link-style="text-decoration:none; color:#6ec8c7;" data-link-color="Preference Link" data-color="Preference" data-size="Preference" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#7f8c8d; font-size:13px; line-height: 28px;font-style: italic;" class="editable" align="center">
													</td>
												</tr>
												<tr>
													<td height="10">
													</td>
												</tr>
											</table>
											<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td data-bgcolor="Header BG" data-bg="Header BG" style="background-color: #FFFFFF !important;" class="editable" align="center">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="20">
																</td>
															</tr>
															<tr>
																<td align="center">
																	<!--logo-->
				
																	<table align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="line-height: 0px;" align="center">
																				 <a href="#"><img data-crop="false" src="'.$logo_url.'" alt="Yura" style="display:block; line-height:0px; font-size:0px; border:0px; width:60%;"/></a>
																			</td>
																		</tr>
																	</table>
																	<!--end logo-->
				
																</td>
															</tr>
															<tr>
																<td height="20">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td align="center" style="background-color:#FFFFFF;">
														<table align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--content-->
															<tr>
																<td align="center">
																	<table class="textbutton" style="border:0px solid #000;" align="center" cellspacing="0" cellpadding="0" border="0">
																		<tr align="left">
																			<td data-link-style="text-decoration:none; color:#000;" data-link-color="Content Link" data-color="Content" data-size="Content" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000000; font-size:14px; line-height: 28px;" align="center">
																				<singleline>
																					Below is your OTP for password reset.<br/><br/>
                                                                                    '.$forgot_password_user_verification_code.'																																											
																				</singleline>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<!--end content-->
															
															<tr>
																<td height="50">
																</td>
															</tr>
															<!--button-->
				
															
															<!--end button-->
				
															<tr>
																<td height="50">
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table data-module="footer" data-bgcolor="Main BG" class="currentTable" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f3f6fa">
												<tr>
													<td align="center">
														<table align="center" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td align="center" width="600">
																	<table class="table-inner" align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td data-bgcolor="Container" style="border-bottom-left-radius:4px;border-bottom-right-radius:4px;border-bottom:3px solid #ecf0f1;" height="10" bgcolor="#ffffff">
																			
																			</td>
																		</tr>
																		<tr>
																			<td height="25">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table align="center" cellspacing="0" cellpadding="0" border="0">
																					<tr>
																						<td style="font-family: \'Century Gothic\', Arial, sans-serif; color:#000; font-size:14px; line-height: 28px;" align="center">
																							Copyright &copy; - '.date('Y').' All rights reserved.<br />
																							<small>This is a system-generated mail. Please do not reply to this email address.</small>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="35">
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
                    
        return $message;                            
    }
    
}