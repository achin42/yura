<script>
jQuery(document).ready(function($){
    
    var filter_via_company = '';    
    var uri_segment = "<?php echo $this->uri->segment('1');?>"; 
    if(uri_segment == 'cluster-list'){
        loadClusterList(filter_via_company);    
    }                     
});

function applyFilter(data_users_sk){
    loadClusterList(data_users_sk)    
    $('.project_filter').closest("li").removeClass('active');
    $('#li_'+data_users_sk).addClass('active');
}

function loadClusterList(filter_via_company){    
    $('#ajax-loader-div').show();
    $('#project_list_container').hide();
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_cluster_list_ajax',
        data: 'filter_via_company='+filter_via_company,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#project_list_container').html(arrTotal[0]);
                if(filter_via_company != ''){
                    $('#li_'+filter_via_company).addClass('active');
                    $('#li_1').removeClass('active');
                }                    
                $('#ajax-loader-div').hide();
                $('#project_list_container').show();  

                /* added by rushabh desai*/
                jQuery('.download-button').click (function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    jQuery('.download-popup').removeClass('open-popup');
                    attr_sk = jQuery(this).attr("data-attr");                    
                    jQuery("#popup_"+attr_sk).addClass("open-popup")
                });             
                jQuery('.download-new-button').click (function(){                    
                    attr_sk = jQuery(this).attr("data-attr");
                    if (jQuery("#popup_"+attr_sk).hasClass("open-popup")) {
                        jQuery("#popup_"+attr_sk).removeClass("open-popup");
                    } else {
                        jQuery("#popup_"+attr_sk).addClass("open-popup")
                    }
                    //jQuery('.fcl-note').find('.download-popup').removeClass('open-popup');
                    //jQuery(this).closest('.fcl-note').find('.download-popup').toggleClass('open-popup');
                });
                $('.download-popup').click( function(e) {                    
                    e.stopPropagation(); // when you click within the content area, it stops the page from seeing it as clicking the body too                    
                });
                $('body').click( function() {
                    jQuery('.download-popup').removeClass('open-popup');
                });                
                /* end code */
                
                var upperHeight = $('.filter-container').outerHeight(); 
                $('.filter-content-box').css({marginTop:upperHeight})
        
            }else if(arrTotal[1] == '2'){
                setTimeout(function(){
                    
                }, 1000);
                window.location.href = arrTotal[0];
            }          
        }
    });        
}

function fun_delete_cluster(cluster_sk){
    swal({
        title: "Confirm Cluster Deletion",
        text: "Are you sure you want to delete this Cluster?",
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
			url: "<?php echo $this->config->item('site_url');?>project/fn_delete_cluster",
			data: "cluster_sk=" + cluster_sk,
			success: function(total){
				displayToastNotificationInAjax('success','Cluster has been deleted successfully.');
                loadClusterList('');
			}
		});
	});
}

function openCreateAgreementModal(cluster_sk,came_from_project){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_create_agreement_popup',
        data: 'cluster_sk='+cluster_sk+'&came_from_project='+came_from_project,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_agreement_content').html(arrTotal[0]);
                $('#create_agreement_popup').modal('show');
                $('#frm_create_agreement').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var create_agreement_obj = document.getElementById("frm_create_agreement");
                        funAjaxPostCreateAgreement(create_agreement_obj);
                        return false;
                    }
                }); 
            }                             
        }
    });        
}

function funAjaxPostCreateAgreement(obj){
	jQuery('#create_agreement_btn').hide();
	jQuery('#wait_create_agreement_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#create_agreement_btn').show();
            jQuery('#wait_create_agreement_btn').hide();
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
                        jQuery('#create_agreement_btn').show();
                        jQuery('#wait_create_agreement_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Agreement has been created successfully.');
        			window.location.href = arrTotal[0];
                    /*$('#create_agreement_popup').modal('hide');
                    $('#create_agreement_content').html('');                    
                    loadClusterList('');*/                    					
				}else{
					setTimeout(function(){
                        jQuery('#create_agreement_btn').show();
                        jQuery('#wait_create_agreement_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){				
                setTimeout(function(){
                	jQuery('#create_agreement_btn').show();
                	jQuery('#wait_create_agreement_btn').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostCreateAgreement');        
                }
                return false;
			}
		});
	}
}

function fn_delete_project(project_sk){
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
				displayToastNotificationInAjax('success','Project has been deleted successfully.');
                loadClusterList('');
			}
		});
	});
}

jQuery(".debitfun").click(function() {
    $.ajax({
        type: 'GET',
        url: site_url + "user/fn_insert_debit_transactions",
        contentType: false,
        cache: false,
        processData:false,
        success:function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){                
                displayToastNotificationInAjax('success','Amount debited successfully.');
                //showpaymentmethods();
                return false;                   
            }else{
                displayToastNotificationInAjax('error','There is no active bank account.');
                return false;
            }
        },
        error:function(jqXHR, exception){            
            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
            if(err_msg != ''){
                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'debitfun');        
            }
            return false;
        }
    });
});

jQuery(".creditfun").click(function() {
    $.ajax({
        type: 'GET',
        url: site_url + "user/fn_insert_credit_transactions",
        contentType: false,
        cache: false,
        processData:false,
        success:function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){                
                displayToastNotificationInAjax('success','Amount credited successfully.');
                //showpaymentmethods();
                return false;                   
            }else{
                displayToastNotificationInAjax('error','There is no active payment method.');
                return false;
            }
        },
        error:function(jqXHR, exception){            
            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
            if(err_msg != ''){
                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'creditfun');        
            }
            return false;
        }
    });
});

function linkToProjectExecution(project_sk){
    window.location.href = site_url + 'project-execution/' + project_sk; 
}

function linkToProjectDetail(project_sk){
    window.location.href = site_url + 'project-detail/' + project_sk; 
}
 
function mark_as_completed(tasks_sk){
    $.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('site_url');?>project/fn_task_mark_as_completed",
		data: "tasks_sk="+tasks_sk,
		success: function(total){
            displayToastNotificationInAjax('success','Task mark as completed successfully.');
            $('#mark_as_done_'+tasks_sk).hide();
            $('#mark_as_incomplete_'+tasks_sk).show();
            $('#remove_task_'+tasks_sk).remove();            
            if($('.overdue-task-box > ul > li').length == '0'){
                $('.overdue-task-box').remove();
            }            
		}
	});    
}

function mark_as_incomplete(tasks_sk){
    $.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('site_url');?>project/fn_task_mark_as_incomplete",
		data: "tasks_sk="+tasks_sk,
		success: function(total){
            displayToastNotificationInAjax('success','Task mark as incomplete successfully.');            
            $('#mark_as_incomplete_'+tasks_sk).hide();
            $('#mark_as_done_'+tasks_sk).show();			
		}
	});    
}

function pay_now(tasks_sk,payment_amount){
    $.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('site_url');?>project/fn_task_pay_now",
		data: "tasks_sk="+tasks_sk+"&payment_amount="+payment_amount,
		success: function(total){
            displayToastNotificationInAjax('success','Task mark as completed successfully.');
            $('#pay_now_'+tasks_sk).hide();
            $('#unpaid_'+tasks_sk).show();
		}
	});    
}

function unpaid(tasks_sk,payment_amount){
    $.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('site_url');?>project/fn_task_unpaid",
		data: "tasks_sk="+tasks_sk+"&payment_amount="+payment_amount,
		success: function(total){
            displayToastNotificationInAjax('success','Task mark as incomplete successfully.');            
            $('#unpaid_'+tasks_sk).hide();
            $('#pay_now_'+tasks_sk).show();			
		}
	});    
}

function what_is_cluster_event(){
    $('#gotit,#gotits').on('click',function(){	  	
        $('.cluster-intro-dropdown').removeClass('show');
    });

    $('.cluster-info-box > div > a').on('click',function(){
        if($(this).parent().find('.cluster-intro-dropdown').hasClass('show')){
            $('.cluster-intro-dropdown').removeClass('show');
        }else{
            $('.cluster-intro-dropdown').removeClass('show');
            $(this).next().addClass('show');
        }
        return false;
    });

    $('.cluster-intro-dropdown').click(function(event){
        event.stopPropagation();
    });
}

function new_phase_required_event(){
    $('#project_title').keypress(function(e) {
        var project_title = $('#project_title').val(); 
        if (project_title != ''){
            $('#project_title_required').hide();
        }
    });
    $('#project_title').on('blur input', function() {
        var project_title = $('#project_title').val(); 
        if (project_title != ''){
            $('#project_title_required').hide();
        }
    });
    
    $('#phase_title').keypress(function(e) {
        var phase_title = $('#phase_title').val(); 
        if (phase_title != ''){
            $('#phase_required').hide();
        }
    });    
    $('#phase_title').on('blur input', function() {
        var phase_title = $('#phase_title').val(); 
        if (phase_title != ''){
            $('#phase_required').hide();
        }
    });
    
    $("#start_date,#end_date").change(function () {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        if (start_date != ''){
            $('#start_date_required').hide();
        }
        if (end_date != ''){
            $('#end_date_required').hide();
        }    
    });
}

function new_phase_date_picker_event(){
    $('.ap-date-picker input').each(function() {
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
}

function openCreateAgreementWithPhasesModal(cluster_sk){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_create_agreement_with_phases_popup',
        data: 'cluster_sk='+cluster_sk,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_agreement_with_phases_content').html(arrTotal[0]);
                $('#create_agreement_with_phases_popup').modal('show');
                what_is_cluster_event();
                new_phase_required_event();
                new_phase_date_picker_event();
            }                             
        }
    });        
}

let agreement_phase_arr = [];
function funAddPhaseFromAgreementPopup(){
    var phase_title = $('#phase_title').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(phase_title == ''){
        $('#phase_required').show();
    }else{
        $('#phase_required').hide();
    }
    if(start_date == ''){
        $('#start_date_required').show();
    }else{
        $('#start_date_required').hide();
    }
    if(end_date == ''){
        $('#end_date_required').show();
    }else{
        $('#end_date_required').hide();
    }
    
    if(phase_title != '' && start_date != '' && end_date != ''){
        /*console.log(agreement_phase_arr[1][0]+'>>>'+agreement_phase_arr[1][1]+'>>>'+agreement_phase_arr[1][2])  
        agreement_phase_arr = agreement_phase_arr.slice(0); // make copy
        agreement_phase_arr.splice(0, 1);
        console.log(agreement_phase_arr);*/
        agreement_phase_arr.push( [phase_title, start_date, end_date] );
        $('#phase_title').val('');
        $('#start_date').val('');
        $('#end_date').val('');                 
        if(agreement_phase_arr.length > 0){
            $('#one_phase_required').hide();
            var display_table_html = '<table class="table"><tbody>';
            for (var i = 0; i < agreement_phase_arr.length; i++) {
                var display_no = i + 1;
                display_table_html = display_table_html + '<tr><td><b>'+display_no+'</b></td><td>'+agreement_phase_arr[i][0]+'</td><td class="text-right">'+agreement_phase_arr[i][1]+' - '+agreement_phase_arr[i][2]+'</td><td style="width: 50px;"><div class="table-delete-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/delete-outline-icon.svg" onclick="funRemovePhaseFromAgreementPopup('+i+')"></div></td></tr>';
            }
            display_table_html = display_table_html + '</tbody></table>';
            
            $('#no_phases_block').removeClass('d-flex');
            $('#no_phases_block').hide();
            $('#list_of_phases_block').html(display_table_html);
            $('#list_of_phases_block').show();
        }             
    }
    
}

function funRemovePhaseFromAgreementPopup(remove_element_key){
    agreement_phase_arr = agreement_phase_arr.slice(0); // make copy
    agreement_phase_arr.splice(remove_element_key, 1);
    if(agreement_phase_arr.length > 0){
        $('#one_phase_required').hide();
        var display_table_html = '<table class="table"><tbody>';
        for (var i = 0; i < agreement_phase_arr.length; i++) {
            var display_no = i + 1;
            display_table_html = display_table_html + '<tr><td><b>'+display_no+'</b></td><td>'+agreement_phase_arr[i][0]+'</td><td class="text-right">'+agreement_phase_arr[i][1]+' - '+agreement_phase_arr[i][2]+'</td><td style="width: 50px;"><div class="table-delete-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/delete-outline-icon.svg" onclick="funRemovePhaseFromAgreementPopup('+i+')"></div></td></tr>';
        }
        display_table_html = display_table_html + '</tbody></table>';
        
        $('#no_phases_block').removeClass('d-flex');
        $('#no_phases_block').hide();
        $('#list_of_phases_block').html(display_table_html);
        $('#list_of_phases_block').show();
    }else{
        $('#no_phases_block').addClass('d-flex');
        $('#no_phases_block').show();
        $('#list_of_phases_block').html('');
        $('#list_of_phases_block').hide();
    }    
}

function funAjaxPostCreateAgreementPhases(obj){
    var project_title = $('#project_title').val();
    var cluster_sk = $('#cluster_sk').val();
    if(project_title == ''){
        $('#project_title_required').show();        
    }else{
        $('#project_title_required').hide();
    }
    if(agreement_phase_arr.length > 0){
        $('#one_phase_required').hide();    
    }else{
        $('#one_phase_required').show();
    }
    if(agreement_phase_arr.length > 0 && project_title != ''){
        jQuery('#create_agreement_phases_btn').hide();
        jQuery('#wait_create_agreement_phases_btn').show();
        var agreement_phase_arr_text = JSON.stringify( agreement_phase_arr );
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>project/fn_post_create_agreement_with_phases_popup',
            data: 'cluster_sk='+cluster_sk+'&project_title='+project_title+'&phase_arr='+agreement_phase_arr_text,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    setTimeout(function(){
                        jQuery('#create_agreement_phases_btn').show();
                        jQuery('#wait_create_agreement_phases_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Project has been created successfully.');
        			window.location.href = arrTotal[0];                                    
                }                             
            }
        });	
    }else{
        return false;
    }
            
}

</script>