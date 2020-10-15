<div class="row">
  <div class="col-sm-12">
	 <div class="table-responsive">
		 <table class="table table-striped-- table-bordered-- table-hover-- table-checkable-- dataTable no-footer dtr-inline collapsed" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info">
			<col width="10%"/>
			<col width="9%"/>
            <col width="9%"/>
            <col width="9%"/>
            <col width="9%"/>
            <col width="9%"/>
            <col width="10%"/>
            <col width="10%"/>
            <col width="10%"/>
            <col width="10%"/>
			<col width="5%"/>
			<thead>
			   <tr>
                    <th>Actions</th>
					<th <?php if ($this->input->post('sort_by') == 'first_name') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'first_name','desc');">First Name <?php if ($this->input->post('sort_by') == 'first_name') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'first_name','asc');">First Name <?php if ($this->input->post('sort_by') == 'first_name') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'last_name') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'last_name','desc');">Last Name <?php if ($this->input->post('sort_by') == 'last_name') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'last_name','asc');">Last Name <?php if ($this->input->post('sort_by') == 'last_name') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'email') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'email','desc');">Email <?php if ($this->input->post('sort_by') == 'email') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'email','asc');">Email <?php if ($this->input->post('sort_by') == 'email') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'company_name') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'company_name','desc');">Company Name <?php if ($this->input->post('sort_by') == 'company_name') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'company_name','asc');">Company Name <?php if ($this->input->post('sort_by') == 'company_name') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'company_domain') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'company_domain','desc');">Company Domain <?php if ($this->input->post('sort_by') == 'company_domain') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'company_domain','asc');">Company Domain <?php if ($this->input->post('sort_by') == 'company_domain') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'chamber_of_commerce_number') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'chamber_of_commerce_number','desc');">Chamber of commerce number <?php if ($this->input->post('sort_by') == 'chamber_of_commerce_number') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'chamber_of_commerce_number','asc');">Chamber of commerce number <?php if ($this->input->post('sort_by') == 'chamber_of_commerce_number') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'vat_number') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'vat_number','desc');">VAT number <?php if ($this->input->post('sort_by') == 'vat_number') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'vat_number','asc');">VAT number <?php if ($this->input->post('sort_by') == 'vat_number') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th <?php if ($this->input->post('sort_by') == 'country_name') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'country_name','desc');">Country <?php if ($this->input->post('sort_by') == 'country_name') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'country_name','asc');">Country <?php if ($this->input->post('sort_by') == 'country_name') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
                    <th>Status</th>
                    <th <?php if ($this->input->post('sort_by') == 'U.created_on') { ?> class="<?php echo "sorting_".$this->input->post('sort_order'); ?>" <?php } else {?>class="sorting"<?php } ?>>
					<?php if ($this->input->post('sort_order') == 'asc') { ?><a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'U.created_on','desc');">Created On <?php if ($this->input->post('sort_by') == 'U.created_on') { ?><i class="flaticon2-arrow-up"></i><?php } ?></a><?php } else {?>
					<a href="javascript:fun_sort_by(<?php echo $curr_page; ?>,'U.created_on','asc');">Created On <?php if ($this->input->post('sort_by') == 'U.created_on') { ?><i class="flaticon2-arrow-down"></i><?php } ?></a>
					<?php } ?>
					</th>
				</tr>
			</thead>
			<tbody>
        		<?php if($recordcount_users > 0) { foreach($get_all_users as $get_all_users_val){ ?>
                        <?php
                            $status = '';
                            $status_condition = '';
                            if($get_all_users_val->applied_for_manual_verification == '0' && $get_all_users_val->verify_later == '0'){
                                if($get_all_users_val->is_agency_verified == '0'){
                                    $status = 'Pending';
                                    $status_condition = 'pending';
                                }elseif($get_all_users_val->is_agency_verified == '1'){
                                    $status = 'Auto Approved';
                                    $status_condition = 'auto_approved';
                                }elseif($get_all_users_val->is_agency_verified == '2'){
                                    $status = 'Rejected';
                                    $status_condition = 'rejected';
                                }                                
                            }else{
                                if($get_all_users_val->is_agency_verified == '0'){
                                    $status = 'Pending';
                                    $status_condition = 'pending';
                                }elseif($get_all_users_val->is_agency_verified == '1'){
                                    $status = 'Manually Approved';
                                    $status_condition = 'manually_approved';
                                }elseif($get_all_users_val->is_agency_verified == '2'){
                                    $status = 'Rejected';
                                    $status_condition = 'rejected';
                                } 
                            }
                            
                            $status_cls = "btn-label-success";
							if($status_condition == 'auto_approved'){
								$status_cls = "btn-label-success";
							} elseif ($status_condition == 'manually_approved') {
								$status_cls = "btn-label-focus";
							}elseif ($status_condition == 'rejected') {
								$status_cls = "btn-label-danger";
							}elseif ($status_condition == 'pending') {
								$status_cls = "btn-label-warning";
							}
                                            
                        ?>
        			  <tr class="odd gradeX" id="ID_<?php echo $get_all_users_val->users_sk; ?>">
                            <td data-field="Actions" data-autohide-disabled="false" class="kt-datatable__cell">
                                <?php if($status_condition == 'auto_approved'){ ?>
                                    <span class="dropdown">
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Approve" class="btn btn-sm btn-clean btn-icon btn-icon-md action_disabled"><i class="la la-check"></i></a>
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Reject" class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="fn_reject_agency_company('<?php echo $get_all_users_val->company_sk;?>');" ><i class="la la-times"></i></a>
                                    </span>
                                <?php }elseif($status_condition == 'manually_approved'){ ?>
                                    <span class="dropdown">
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Approve" class="btn btn-sm btn-clean btn-icon btn-icon-md action_disabled"><i class="la la-check"></i></a>
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Reject" class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="fn_reject_agency_company('<?php echo $get_all_users_val->company_sk;?>');" ><i class="la la-times"></i></a>
                                    </span>    
                                <?php }elseif($status_condition == 'rejected'){ ?>
                                    <span class="dropdown">
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Approve" class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="fn_approve_agency_company('<?php echo $get_all_users_val->company_sk;?>');" ><i class="la la-check"></i></a>
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Reject" class="btn btn-sm btn-clean btn-icon btn-icon-md action_disabled"><i class="la la-times"></i></a>
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="<?php echo $get_all_users_val->reason_for_rejection; ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md action_disabled"><i class="la la-envelope"></i></a>                                                                                
                                    </span>
                                <?php }else{ ?>
                                    <span class="dropdown">
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Approve" class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="fn_approve_agency_company('<?php echo $get_all_users_val->company_sk;?>');"  ><i class="la la-check"></i></a>
                                        <a href="javascript:void(0)" data-html="true" data-toggle="kt-tooltip" data-placement="right" data-skin="brand" data-original-title="Reject" class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="fn_reject_agency_company('<?php echo $get_all_users_val->company_sk;?>');" ><i class="la la-times"></i></a>                                    
                                    </span>
                                <?php } ?>                                    
        					</td>
        					<td>
                                <div class="kt-user-card-v2">
            						<div class="kt-user-card-v2__pic"> 
            							<?php
                                            $full_user_image_path = $this->config->item("UPLOAD_DIR").'user_image/'.$get_all_users_val->user_image;
                                            if($get_all_users_val->user_image != '' && file_exists($full_user_image_path) ) {
                                                $full_user_image_url = $this->config->item("UPLOAD_URL").'user_image/'.$get_all_users_val->user_image;
            							?>
            								<div class="kt-badge kt-badge--xl kt-badge--brand"><span><img src="<?php echo $full_user_image_url; ?>" class="kt-img-rounded kt-marginless" alt="photo"></span></div>
            							<?php } else { ?>
            								<div class="kt-badge kt-badge--xl kt-badge--brand"><span><?php echo substr($get_all_users_val->first_name, 0, 1);?></span></div>                                                                    
            							<?php } ?>                                                            
            						</div>
            						<div class="kt-user-card-v2__details">
            							<span class="kt-user-card-v2__name">
                                            <?php echo $get_all_users_val->first_name; ?>
                                        </span>
            						</div>
            					</div>
                            </td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->last_name; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->email; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->company_name; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->company_domain; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->chamber_of_commerce_number; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->vat_number; ?></td>
                            <td class="hide-tablet-and-mobile"><?php echo $get_all_users_val->country_name; ?></td>
                            <td class="hide-tablet-and-mobile"><span class="btn btn-bold btn-sm btn-font-sm <?php echo $status_cls;?>"><?php echo $status; ?></span></td>
        					<td class="hide-tablet-and-mobile"><?php echo date('d/m/Y',strtotime($get_all_users_val->created_on)); ?></td>
        				</tr>
        			<?php } ?>
    			<?php } else {?>
					<tr><td colspan="10" align="center">No records found!</td></tr>
    			<?php } ?>
			</tbody>
		 </table>
	 </div>
  </div>
</div>
<div class="row">
	<div class="col-md-1 col-sm-12">
		<div class="dataTables_length" id="kt_table_1_length">
			<label>
			   <select name="page_limit" id="page_limit" aria-controls="kt_table_1" class="custom-select custom-select-sm form-control form-control-sm" onchange="fun_search_users()">
					<option value="10" <?php if ($page_limit == 10){ echo "selected='selected'";} ?>>10</option>
					<option value="20" <?php if ($page_limit == 20){ echo "selected='selected'";} ?>>20</option>
					<option value="50" <?php if ($page_limit == 50){ echo "selected='selected'";} ?>>50</option>
					<option value="100" <?php if ($page_limit == 100){ echo "selected='selected'";} ?>>100</option>
			   </select>
			</label>
		 </div>
	</div>
	<?php echo $this->ajax_pagination->create_links(); ?>
</div>