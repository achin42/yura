<script>
jQuery(document).ready(function($){
    
    var project_sk = "<?php echo $this->uri->segment('2'); ?>";
    var sel_phase_sk = ''; 
    loadProjectPhaseList(project_sk,sel_phase_sk);        
    $('.block-container,.sb-body').scrollbar();
    
    $('.agreement-phase-list-container').on('click',function(){	  	
		cancelUpdatePhase(project_sk);
	});
    
    /*control panel events start*/    
    $('#btn_finish_editing').click(function() {
        $('#btn_finish_editing').addClass('display-hide');
        $('#agency_download_before_sign').removeClass('display-hide');
        $('#review_sign_btn_div').removeClass('display-hide');
        $('#review_sign_btn_div').addClass('d-flex cpanel-btn-parent');
    });
    
    $('.cpanel-head').click(function() {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $(this).closest('.cpanel-left').addClass('open');
            $('.cpanel-right').removeClass('display-hide');
            $('.cpanel-right').addClass('d-flex');
        } else {
            $(this).removeClass('active');
            $(this).closest('.cpanel-left').removeClass('open');
            $('.cpanel-options li').removeClass('active');
            $('.cpanel-right').addClass('display-hide');
            $('.cpanel-right').removeClass('d-flex');
            $('#btn_finish_editing').removeClass('display-hide');
            $('#agency_download_before_sign').addClass('display-hide');
            $('#review_sign_btn_div').addClass('display-hide');
            $('#review_sign_btn_div').removeClass('d-flex cpanel-btn-parent');
        }
    });

    $('.cpanel-options li.no-link a').click(function() {
        $('.cpanel-options li').removeClass('active');
        $('.cpanel-btn-parent').removeClass('active');
        $(this).closest('li').toggleClass('active');
        return false;
    });
    
    $('.cpanel-options li.hover a').hover(function() {
    	$('.cpanel-options li').removeClass('active');
        $('.cpanel-btn-parent').removeClass('active');
        $(this).closest('li').toggleClass('active');
        return false;
    });
    
    $('.cpanel-options li.has-link a').click(function() {
    	$('.cpanel-options li').removeClass('active');
        $('.cpanel-btn-parent').removeClass('active');
    });

    $('body').click(function() {
        $('.cpanel-options li').removeClass('active');
        $('.cpanel-btn-parent').removeClass('active');
    });

    $('.cpanel-popup').click(function(e) {
        e.stopPropagation();
    });

    $('.cpanel-right .show-popup').click(function() {
        $('.cpanel-btn-parent').removeClass('active');
        $('.cpanel-options li').removeClass('active');
        $(this).closest('.cpanel-btn-parent').toggleClass('active');
        return false;
    });
    /*control panel evnets end*/
    
    $('.pending-sign-popover').popover({
        placement : 'top',
        html : true,
        content : '<div class="custom-propover2"><div class="custom-propover-content profile-pending final-state"><div class="cpc-body"><h5>Awaiting company verification</h5><p>You have applied for us manually verify your company details. The process may take up to 24 hours.</p></div><div class="tb-action-box d-flex align-items-center justify-content-end"><a href="#" class="blue-btn okay-btn">Okay</a></div></div></div>'
    });
    
    $('.not-verified-sign-popover').popover({
        placement : 'top',
        html : true,
        content : '<div class="custom-propover2"><div class="custom-propover-content final-state"><div class="cpc-body"><h5>Company details not verified</h5><p>You need to verify your company details to be able to create a Payment block.</p></div><div class="tb-action-box d-flex align-items-center justify-content-end"><a href="#" class="btn-link okay-btn">I\'ll do it later</a><a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a></div></div></div>'
    });
    
    $(document).on("click", ".okay-btn" , function(){
        $(this).parents(".popover").popover('hide');
    });
                                                                  
});

function loadProjectPhaseList(project_sk,sel_phase_sk){    
    //$('#ajax-loader-div').show();
    //$('#agreement_phase_list_container').hide();
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_project_phase_list_ajax',
        data: 'project_sk='+project_sk+'&sel_phase_sk='+sel_phase_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#agreement_phase_list_container').html(arrTotal[0]);
                
                $('.remove_phase_selection,.remove_block_selection').click(function(event){
                    event.stopPropagation();
            	});
                //$('#ajax-loader-div').hide();
                //$('#agreement_phase_list_container').show();
                setTimeout(function(){
                    //alert(1)
                    //new SimpleBar(document.getElementById('agreement_phase_list_container'));
                    //$('#agreement_phase_list_container').SimpleBar('recalculate');
                    if(sel_phase_sk != ''){
                        //funSelectPhaseAjax(sel_phase_sk);
                        funSelectPhase(sel_phase_sk,project_sk);
                    }
                },100);                     
            }          
        }
    });        
}

function openCreatePhaseModal(cluster_sk,project_sk){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_create_phase_popup',
        data: 'cluster_sk='+cluster_sk+'&project_sk='+project_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_phase_content').html(arrTotal[0]);
                $('#create_phase_popup').modal('show');
                
                $('.input-daterange-1 input').each(function() {
                    $(this).datepicker();
                });    
                
                $("#start_date").change(function () {
                    var startDate = document.getElementById("start_date").value;
                    var endDate = document.getElementById("end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("end_date").value = "";
                    }
                });
                
                $("#end_date").change(function () {
                    var startDate = document.getElementById("start_date").value;
                    var endDate = document.getElementById("end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("end_date").value = "";
                    }
                });
                
                var startDate = document.getElementById("start_date").value;
                var endDate = document.getElementById("end_date").value;
                if ((Date.parse(endDate) <= Date.parse(startDate))) {
                    //alert("End date should be greater than Start date");
                    document.getElementById("end_date").value = "";
                }
                                
                $('#frm_create_phase').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var create_phase_obj = document.getElementById("frm_create_phase");
                        funAjaxPostCreatePhase(create_phase_obj,project_sk);
                        return false;
                    }
                });
            }                             
        }
    });        
}

function funAjaxPostCreatePhase(obj,project_sk){
	jQuery('#create_phase_btn').hide();
	jQuery('#wait_create_phase_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#create_phase_btn').show();
            jQuery('#wait_create_phase_btn').hide();
        }, 4000);		
		return false;
	}  else {
		var subform = jQuery('#'+formid).serialize();
		$.ajax({
			type: 'POST',
			url: obj.action,
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    setTimeout(function(){
                        jQuery('#create_phase_btn').show();
                        jQuery('#wait_create_phase_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Phase has been created successfully.');
        			//window.location.href = arrTotal[0];
                    $('#create_phase_popup').modal('hide');
                    $('#create_phase_content').html('');
                    var sel_phase_sk = '';                    
                    loadProjectPhaseList(project_sk,sel_phase_sk);                    					
				}else{
					setTimeout(function(){
                        jQuery('#create_phase_btn').show();
                        jQuery('#wait_create_phase_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){                
                setTimeout(function(){
                    jQuery('#create_phase_btn').show();
                    jQuery('#wait_create_phase_btn').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostCreatePhase');        
                }
                return false;
			}
		});
	}
}

function createBlock(project_sk,block_type_sk,block_type_code){
    var phase_sk = $('#sel_phase_sk').val();
    $('.icon_'+block_type_code).hide();
    $('.loader_icon_'+block_type_code).show();
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_insert_block',
        data: 'project_sk='+project_sk+'&block_type_sk='+block_type_sk+'&block_type_code='+block_type_code+'&phase_sk='+phase_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){                
                $('#add_html_in_block_container').html(arrTotal[0]);
                $('#create_block_container').hide();
                $('#add_html_in_block_container').show();
                $('.input-daterange-1 input').each(function() {
                    $(this).datepicker();
                });
                $('.icon_'+block_type_code).show();
                $('.loader_icon_'+block_type_code).hide();
                $('#block_cancel_mode').val('add_update_from_right_side');      
                
                $("#block_start_date").change(function () {
                    var startDate = document.getElementById("block_start_date").value;
                    var endDate = document.getElementById("block_end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("block_end_date").value = "";
                    }
                });
                
                $("#block_end_date").change(function () { 
                    var startDate = document.getElementById("block_start_date").value;
                    var endDate = document.getElementById("block_end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("block_end_date").value = "";
                    }
                });
                
                var startDate = document.getElementById("block_start_date").value;
                var endDate = document.getElementById("block_end_date").value;
                if ((Date.parse(endDate) <= Date.parse(startDate))) {
                    //alert("End date should be greater than Start date");
                    document.getElementById("block_end_date").value = "";
                }
                          
                //loadProjectPhaseList(project_sk,phase_sk);
                //displayToastNotificationInAjax('success','Block has been created successfully.');
            }                             
        }
    });        
}

function openEditBlock(block_sk,project_sk,pass_phase_sk){
    $('.phase-content-box').removeClass('selected');
    $('#sel_phase_'+pass_phase_sk).removeClass('phase-btn-active');
    $('.phase-desc').removeClass('active');
    $('#block_'+block_sk).addClass('active');
    
    var sel_phase_sk = $('#sel_phase_sk').val(); 
    $('#sel_phase_'+sel_phase_sk).removeClass('phase-btn-active');
    if(pass_phase_sk != sel_phase_sk){
        $('#sel_phase_sk').val('');
    }
    
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_open_edit_block',
        data: 'block_sk='+block_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){        
                $('body').addClass('show-notification');        
                $('#add_html_in_block_container').html(arrTotal[0]);
                $('#create_block_container').html('');
                $('#create_block_container').hide();
                $('#add_html_in_block_container').show(); 
                $('.sb-body').scrollbar(); 
                $('.input-daterange-1 input').each(function() {
                    $(this).datepicker();
                });    
                
                $("#block_start_date").change(function () {
                    var startDate = document.getElementById("block_start_date").value;
                    var endDate = document.getElementById("block_end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("block_end_date").value = "";
                    }
                });
                
                $("#block_end_date").change(function () {  
                    var startDate = document.getElementById("block_start_date").value;
                    var endDate = document.getElementById("block_end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("block_end_date").value = "";
                    }
                });
                
                var startDate = document.getElementById("block_start_date").value;
                var endDate = document.getElementById("block_end_date").value;
                if ((Date.parse(endDate) <= Date.parse(startDate))) {
                    //alert("End date should be greater than Start date");
                    document.getElementById("block_end_date").value = "";
                }
                
                /*$('#frm_update_block').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var update_block_obj = document.getElementById("frm_update_block");
                        funAjaxPostUpdateBlock(project_sk);
                        return false;
                    }
                });*/          
            }                             
        }
    });        
}

function cancelUpdateBlock(project_sk,phase_sk,block_cancel_mode,block_sk){
    if(block_cancel_mode == 'add_update_from_right_side'){
        $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('site_url');?>project/fun_delete_block",
			data: "block_sk=" + block_sk,
			success: function(total){
				
			}
		});    
    }
    $('body').removeClass('show-notification');
    $('#add_html_in_block_container').html('');
    $('#create_block_container').show();
    $('#add_html_in_block_container').hide();
    //loadProjectPhaseList(project_sk,phase_sk);
    var sel_phase_sk = '';            
    var sel_phase_sk = $('#sel_phase_sk').val();     
    loadProjectPhaseList(project_sk,sel_phase_sk);
}

function funAjaxPostUpdateBlock(project_sk,phase_sk){
    var obj = document.getElementById("frm_update_block");
	jQuery('#save_changes_btn').hide();
	jQuery('#wait_save_changes_btn').css('display','inline-flex');
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#save_changes_btn').show();
            jQuery('#wait_save_changes_btn').hide();
        }, 4000);		
		return false;
	}  else {
        
         
        $.ajax({
            type: 'POST',
			url: '<?php echo $this->config->item("site_url");?>project/fn_check_block_going_out_of_phase',
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
                var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    swal({
                        title: "Block going out of phase",
                        text: arrTotal[0],
                        type: "warning",
                        html:true,
                        showCancelButton: true,
                        cancelButtonClass: "btn-default btn-sm",
                        confirmButtonClass: "btn-danger btn-sm",
                        confirmButtonText: "Sure, create block",
                        closeOnConfirm: true
                    },
                    function(){
                        var subform = jQuery('#'+formid).serialize();
                		$.ajax({
                			type: 'POST',
                			url: obj.action,
                			data:  new FormData(obj),
                			contentType: false,
                			cache: false,
                			processData:false,
                			beforeSend:function(){ },                    
                			success:function(data){
                				var arrTotal = data.split('^^');
                				if(arrTotal[1] == '1'){
                                    setTimeout(function(){
                                        jQuery('#save_changes_btn').show();
                                        jQuery('#wait_save_changes_btn').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Block has been updated successfully.');
                                    $('#add_html_in_block_container').html('');
                                    $('#add_html_in_block_container').hide();
                                    $('body').removeClass('show-notification');
                                    $('#create_block_container').html('');
                                    $('#create_block_container').hide();                    
                                    $('#create_block_container').show();
                                                        
                                    //loadProjectPhaseList(project_sk,phase_sk);
                                    var sel_phase_sk = '';            
                                    var sel_phase_sk = $('#sel_phase_sk').val(); 
                                    loadProjectPhaseList(project_sk,sel_phase_sk);                    					
                				}else{
                					setTimeout(function(){
                                        jQuery('#save_changes_btn').show();
                                        jQuery('#wait_save_changes_btn').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                                
                                setTimeout(function(){
                                    jQuery('#save_changes_btn').show();
                                    jQuery('#wait_save_changes_btn').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'fn_check_block_going_out_of_phase');        
                                }
                                return false;
                			}
                		});
                	});    			 	
    			}
    			else
    			{
                    var subform = jQuery('#'+formid).serialize();
            		$.ajax({
            			type: 'POST',
            			url: obj.action,
            			data:  new FormData(obj),
            			contentType: false,
            			cache: false,
            			processData:false,
            			beforeSend:function(){ },                    
            			success:function(data){
            				var arrTotal = data.split('^^');
            				if(arrTotal[1] == '1'){
                                setTimeout(function(){
                                    jQuery('#save_changes_btn').show();
                                    jQuery('#wait_save_changes_btn').hide();
                                }, 4000);
                                displayToastNotificationInAjax('success','Block has been updated successfully.');
                                $('#add_html_in_block_container').html('');
                                $('#add_html_in_block_container').hide();
                                $('body').removeClass('show-notification');
                                $('#create_block_container').html('');
                                $('#create_block_container').hide();                    
                                $('#create_block_container').show();
                                                    
                                //loadProjectPhaseList(project_sk,phase_sk);
                                var sel_phase_sk = '';            
                                var sel_phase_sk = $('#sel_phase_sk').val(); 
                                loadProjectPhaseList(project_sk,sel_phase_sk);                    					
            				}else{
            					setTimeout(function(){
                                    jQuery('#save_changes_btn').show();
                                    jQuery('#wait_save_changes_btn').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error',data);
                                return false;
            				}
            			},
            			error:function(jqXHR, exception){            				
                            setTimeout(function(){
                                jQuery('#save_changes_btn').show();
                                jQuery('#wait_save_changes_btn').hide();
                            }, 4000);
                            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                            if(err_msg != ''){
                                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostUpdateBlock');        
                            }
                            return false;
            			}
            		});
    			}
    		}
    	});
        
        setTimeout(function(){
            jQuery('#save_changes_btn').show();
            jQuery('#wait_save_changes_btn').hide();
        }, 4000);
        
	}
}

function funSelectPhase(phase_sk,project_sk){
    /*$('#sel_phase_sk').val(phase_sk);
    $('.remove-phase-active').removeClass('phase-btn-active');
    $('#sel_phase_'+phase_sk).addClass('phase-btn-active');
    $('#add_html_in_block_container').html('');
    $('#create_block_container').show();
    $('#add_html_in_block_container').hide();*/
    
    $('body').addClass('show-notification');
    $('#sel_phase_sk').val(phase_sk);
    $('.phase-content-box').removeClass('selected');
    $('#sel_phase_content_box_'+phase_sk).addClass('selected');
    $('.remove-phase-active').removeClass('phase-btn-active');
    $('#sel_phase_'+phase_sk).addClass('phase-btn-active');    
    $('.phase-desc').removeClass('active');
        
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_select_phase_and_block_detail',
        data: 'phase_sk='+phase_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#add_html_in_block_container').html('');
                $('#add_html_in_block_container').hide();                
                $('#create_block_container').html(arrTotal[0]);
                $('#create_block_container').show();
                $('.sb-body').scrollbar();
                
                $('.input-daterange-1 input').each(function() {
                    $(this).datepicker();
                });    
                
                $("#start_date").change(function () {
                    var startDate = document.getElementById("start_date").value;
                    var endDate = document.getElementById("end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("end_date").value = "";
                    }
                });
                
                $("#end_date").change(function () {
                    var startDate = document.getElementById("start_date").value;
                    var endDate = document.getElementById("end_date").value;
                    if ((Date.parse(endDate) <= Date.parse(startDate))) {
                        //alert("End date should be greater than Start date");
                        document.getElementById("end_date").value = "";
                    }
                });
                
                var startDate = document.getElementById("start_date").value;
                var endDate = document.getElementById("end_date").value;
                if ((Date.parse(endDate) <= Date.parse(startDate))) {
                    //alert("End date should be greater than Start date");
                    document.getElementById("end_date").value = "";
                }
                
                $('#frm_update_phase_side_panel').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var update_phase_obj = document.getElementById("frm_update_phase_side_panel");
                        //funAjaxPostUpdatePhaseSideDiv(project_sk,phase_sk);
                        //return false;
                    }
                });
                
                /*** Popover Start ***/
                $('.pending-popover').popover({
                    placement : 'top',
                    html : true,
                    content : '<div class="custom-propover2"><div class="custom-propover-content profile-pending final-state"><div class="cpc-body"><h5>Awaiting company verification</h5><p>Company verification is required to be able to create a Payment block.</p><p>You have applied for us manually verify your company details. The process may take up to 24 hours.</p></div><div class="tb-action-box d-flex align-items-center justify-content-end"><a href="#" class="blue-btn sign-in-later">Okay</a></div></div></div>'
                });
                
                $('.not-verified-popover').popover({
                    placement : 'top',
                    html : true,
                    content : '<div class="custom-propover2"><div class="custom-propover-content final-state"><div class="cpc-body"><h5>Company details not verified</h5><p>You need to verify your company details to be able to create a Payment block.</p></div><div class="tb-action-box d-flex align-items-center justify-content-end"><a href="javascript:void(0)" class="btn-link sign-in-later">I\'ll do it later</a><a href="<?php echo $this->config->item("site_url");?>agency-company-detail/verify" class="blue-btn">Verify Now</a></div></div></div>'
                });
                
                $(document).on("click", ".sign-in-later" , function(){
                    $(this).parents(".popover").popover('hide');
                });
                /*** Popover End ***/
                
            }                             
        }
    });
                
}

function funSelectPhaseAjax(phase_sk){
    $('#sel_phase_sk').val(phase_sk);
    $('.remove-phase-active').removeClass('phase-btn-active');
    $('#sel_phase_'+phase_sk).addClass('phase-btn-active');    
}

function fun_delete_block(project_sk,block_sk,phase_sk){
    swal({
        title: "Confirm Block Deletion",
        text: "Are you sure you want to delete this Block?",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-default btn-sm",
        confirmButtonClass: "btn-danger btn-sm",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    },
    function(){
        $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('site_url');?>project/fun_delete_block",
			data: "block_sk=" + block_sk,
			success: function(total){
				displayToastNotificationInAjax('success','Block has been deleted successfully.');
                $('#add_html_in_block_container').html('');
                $('#create_block_container').show();
                $('#add_html_in_block_container').hide();
                loadProjectPhaseList(project_sk,phase_sk);
			}
		});
	});
}

function openUpdatePhaseModal(cluster_sk,project_sk,phase_sk){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_update_phase_popup',
        data: 'cluster_sk='+cluster_sk+'&project_sk='+project_sk+'&phase_sk='+phase_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_phase_content').html(arrTotal[0]);
                $('#create_phase_popup').modal('show');
                $('#frm_update_phase').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var update_phase_obj = document.getElementById("frm_update_phase");
                        funAjaxPostUpdatePhase(update_phase_obj,project_sk,phase_sk);
                        return false;
                    }
                });
            }                             
        }
    });        
}

function funAjaxPostUpdatePhase(obj,project_sk,phase_sk){
	jQuery('#update_phase_btn').hide();
	jQuery('#wait_update_phase_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#update_phase_btn').show();
            jQuery('#wait_update_phase_btn').hide();
        }, 4000);		
		return false;
	}  else {
		var subform = jQuery('#'+formid).serialize();
		$.ajax({
			type: 'POST',
			url: obj.action,
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    setTimeout(function(){
                        jQuery('#update_phase_btn').show();
                        jQuery('#wait_update_phase_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Phase has been updated successfully.');
        			//window.location.href = arrTotal[0];
                    $('#create_phase_popup').modal('hide');
                    $('#create_phase_content').html('');            
                    //loadProjectPhaseList(project_sk,phase_sk);
                    var sel_phase_sk = ''; 
                    loadProjectPhaseList(project_sk,sel_phase_sk);                    					
				}else{
					setTimeout(function(){
                        jQuery('#update_phase_btn').show();
                        jQuery('#wait_update_phase_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){                
                setTimeout(function(){
                    jQuery('#update_phase_btn').show();
                    jQuery('#wait_update_phase_btn').hide();    
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostUpdatePhase');        
                }
                return false;
			}
		});
	}
}

function fun_delete_phase(cluster_sk,project_sk,phase_sk){
    swal({
        title: "Confirm Phase Deletion",
        text: "Deleting a phase will delete all the blocks in it. Are you sure you want to delete this phase?",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-default btn-sm",
        confirmButtonClass: "btn-danger btn-sm",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    },
    function(){
        $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('site_url');?>project/fun_delete_phase",
			data: "cluster_sk="+cluster_sk+"&project_sk="+project_sk+"&phase_sk="+phase_sk,
			success: function(total){
				displayToastNotificationInAjax('success','Phase has been deleted successfully.');
                $('#add_html_in_block_container').html('');
                $('#add_html_in_block_container').hide();
                $('#create_block_container').html('');
                $('#create_block_container').hide();
                $('body').removeClass('show-notification');                
                var sel_phase_sk = '';
                loadProjectPhaseList(project_sk,sel_phase_sk);
			}
		});
	});
}

function funSelectTask(sel_task,tasks_sk){
    var task_cnt = $('#task_cnt').val();
    if(task_cnt == '2'){
        if(sel_task == 'agency'){
            var other_sel_task = 'client';
        }else{
            var other_sel_task = 'agency';
        }         
        $('#first_task').val(sel_task);
        $('#second_task').val(other_sel_task);        
    }else{
        $('#first_task').val(sel_task);
    }
    $('.remove-task-active').removeClass('active');
    $('#sel_task_'+sel_task+'_'+tasks_sk).addClass('active');
    if(task_cnt == '2'){
        if(sel_task == 'agency'){
            $('.other_client').addClass('active');
        }else{
            $('.other_agency').addClass('active');
        }         
    }    
}

function funSelectSecondTask(sel_task,tasks_sk){
    var task_cnt = $('#task_cnt').val();
    if(task_cnt == '2'){
        if(sel_task == 'agency'){
            var other_sel_task = 'client';
        }else{
            var other_sel_task = 'agency';
        }         
        $('#first_task').val(other_sel_task);
        $('#second_task').val(sel_task);        
    }else{
        $('#first_task').val(sel_task);
    }
    $('.remove-task-active').removeClass('active');
    $('#sel_task_'+sel_task+'_'+tasks_sk).addClass('active');    
}

function funSignAgreementDocument(project_sk){
    $('.clk-sign-now').hide();
    $('.clk-please-wait').show();
    $.ajax({
    	type: 'POST',
    	url: "<?php echo $this->config->item('site_url');?>project/fn_sign_agreement_document",
    	data: "project_sk="+project_sk,    	                    
    	success:function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                window.location.href = arrTotal[0];                                      					
			}else{
                $('.clk-sign-now').show();
                $('.clk-please-wait').hide();
                displayToastNotificationInAjax('error',arrTotal[0]);
                return false;
			}            
        }
    });                      
}

function funSecondSignerSignAgreementDocument(agreement_sign_sk){
    if ($('#chk_second_sign').is(":checked")) {
        $('.clk-sign-now').hide();
        $('.clk-please-wait').show();
        $.ajax({
        	type: 'POST',
        	url: "<?php echo $this->config->item('site_url');?>project/fn_second_signer_sign_agreement_document",
        	data: "agreement_sign_sk="+agreement_sign_sk,    	                    
        	success:function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){                    
                    window.location.href = arrTotal[0];                                      					
    			}else{
                    $('.clk-sign-now').show();
                    $('.clk-please-wait').hide();
                    displayToastNotificationInAjax('error',arrTotal[0]);
                    return false;
    			}            
            }
        });
    }else{
        displayToastNotificationInAjax('error','Please agree terms and conditions');
        return false;
    }                                      
}

function funAjaxPostUpdatePhaseSideDiv(project_sk,phase_sk){
    var obj = document.getElementById("frm_update_phase_side_panel");
	jQuery('#phase_save_changes_btn').hide();
	jQuery('#wait_phase_save_changes_btn').css('display','inline-flex');
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#phase_save_changes_btn').show();
            jQuery('#wait_phase_save_changes_btn').hide();
        }, 4000);		
		return false;
	}  else {
        $.ajax({
            type: 'POST',
			url: '<?php echo $this->config->item("site_url");?>project/fn_check_phase_not_covering_all_blocks',
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
                var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    swal({
                        title: "Phase not covering all the blocks",
                        text: arrTotal[0],
                        type: "warning",
                        html:true,
                        showCancelButton: false,
                        cancelButtonClass: "btn-default btn-sm",
                        confirmButtonClass: "btn-danger btn-sm",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    },
                    function(){
                           
                	});    			 	
    			}
    			else
    			{
                    var subform = jQuery('#'+formid).serialize();
            		$.ajax({
            			type: 'POST',
            			url: obj.action,
            			data:  new FormData(obj),
            			contentType: false,
            			cache: false,
            			processData:false,
            			beforeSend:function(){ },                    
            			success:function(data){
            				var arrTotal = data.split('^^');
            				if(arrTotal[1] == '1'){
                                setTimeout(function(){
                                    jQuery('#phase_save_changes_btn').show();
                                    jQuery('#wait_phase_save_changes_btn').hide();
                                }, 4000);
                                displayToastNotificationInAjax('success','Phase has been updated successfully.');
                    			loadProjectPhaseList(project_sk,phase_sk);
                                /*$('body').removeClass('show-notification');
                                $('#create_block_container').html('');
                                $('#create_block_container').hide();                    
                                var sel_phase_sk = ''; 
                                loadProjectPhaseList(project_sk,sel_phase_sk);*/                    
            				}else{
            					setTimeout(function(){
                                    jQuery('#phase_save_changes_btn').show();
                                    jQuery('#wait_phase_save_changes_btn').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error',data);
                                return false;
            				}
            			},
            			error:function(jqXHR, exception){            				
                            setTimeout(function(){
                                jQuery('#phase_save_changes_btn').show();
                                jQuery('#wait_phase_save_changes_btn').hide();
                            }, 4000);
                            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                            if(err_msg != ''){
                                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostUpdatePhaseSideDiv');        
                            }
                            return false;
            			}
            		});    
    			}
    		}
    	});
        
        setTimeout(function(){
            jQuery('#phase_save_changes_btn').show();
            jQuery('#wait_phase_save_changes_btn').hide();
        }, 4000);
    
	}
}

function cancelUpdatePhase(project_sk){
    var block_cancel_mode = $('#block_cancel_mode').val();
    if(block_cancel_mode == 'add_update_from_right_side'){
        var block_sk = $('#block_sk').val();
        $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('site_url');?>project/fun_delete_block",
			data: "block_sk=" + block_sk,
			success: function(total){
                $('[data-toggle="popover"]').each(function () {
                    $(this).popover('hide');
                });
            
				$('body').removeClass('show-notification');
                $('#create_block_container').html('');
                $('#create_block_container').hide();
                $('#add_html_in_block_container').hide();
                var sel_phase_sk = ''; 
                loadProjectPhaseList(project_sk,sel_phase_sk);
			}
		});    
    }else{    
        $('[data-toggle="popover"]').each(function () {
            $(this).popover('hide');
        });
    
        $('body').removeClass('show-notification');
        $('#create_block_container').html('');
        $('#create_block_container').hide();
        $('#add_html_in_block_container').hide();
        var sel_phase_sk = ''; 
        loadProjectPhaseList(project_sk,sel_phase_sk);                
    }        
}

function fn_delete_project_from_detail(project_sk){
    swal({
        title: "Confirm Project Deletion",
        text: "Are you sure you want to delete this Project?",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-default btn-sm",
        confirmButtonClass: "btn-danger btn-sm",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    },
    function(){
        $.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('site_url');?>project/fn_delete_project",
			data: "project_sk=" + project_sk,
			success: function(total){
                window.location.href = "<?php echo $this->config->item('site_url');?>cluster-list/";			 	
			}
		});
    });	
}

</script>