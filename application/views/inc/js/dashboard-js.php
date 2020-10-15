<script>
jQuery(document).ready(function($){

    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>user/fn_set_dashboard_cookie',
        data: '1=1',
        success: function(data){
             
        }
    });
             
});



function openCreateProjectModal(){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_create_project_popup',
        data: '1=1',
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_project_content').html(arrTotal[0]);
                $('#create_project_popup').modal('show');                
                $('#frm_create_project').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var create_project_obj = document.getElementById("frm_create_project");
                        funAjaxPostCreateProject(create_project_obj);
                        return false;
                    }
                });      
                required_project_event();              
            }                             
        }
    });        
}

function required_project_event(){
    $('#sel_client').change(function(e) {
        var sel_val = $(this).val();
        if(sel_val != ''){            
            $('#new_client_email').prop('required',false);
            $('#new_client_company_name').prop('required',false);
            $('#new_client_email').val('');
            $('#new_client_company_name').val('');
            setTimeout(function(){
                /*var create_project_obj = document.getElementById("frm_create_project");
                funAjaxPostCreateProject(create_project_obj);
                return false;*/
            }, 2000);                    
        }else{
            $('#sel_client').prop('required',true);
        }
    });
    
    $('#new_client_email,#new_client_company_name').keypress(function(e) {
        var new_client_email = $('#new_client_email').val(); 
        var new_client_company_name = $('#new_client_company_name').val();
        if (new_client_email != ''){
            $('#sel_client').val('');
        }
        if (new_client_email != '' && new_client_company_name != '') {
            $('#sel_client').prop('required',false);            
            setTimeout(function(){
                /*var create_project_obj = document.getElementById("frm_create_project");
                funAjaxPostCreateProject(create_project_obj);
                return false;*/
            }, 2000);            
        }else{
            $('#new_client_email').prop('required',true);
            $('#new_client_company_name').prop('required',true);
        }            
    });
    
    $('#new_client_email,#new_client_company_name').on('blur input', function() {
        var new_client_email = $('#new_client_email').val(); 
        var new_client_company_name = $('#new_client_company_name').val();
        if (new_client_email != ''){
            $('#sel_client').val('');
        }
        if (new_client_email != '' && new_client_company_name != '') {
            $('#sel_client').prop('required',false);            
            setTimeout(function(){
                /*var create_project_obj = document.getElementById("frm_create_project");
                funAjaxPostCreateProject(create_project_obj);
                return false;*/
            }, 2000);            
        }else{
            $('#new_client_email').prop('required',true);
            $('#new_client_company_name').prop('required',true);
        }
    });
}

function getCompanyContactPersonName(sel_val){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_company_detail_by_users_sk',
        data: 'sel_val='+sel_val,
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#contact_person_name').val(arrTotal[0]);
            }                             
        }
    });
}

function checkWorkEmailAddress(email){
    return /^([\w-.]+@(?!gmail\.com)(?!googlemail\.com)(?!yahoo\.com)(?!yahoomail\.com)(?!outlook\.com)(?!aol\.com)(?!protonmail\.com)(?!hotmail\.com)(?!mail\.ru)(?!yandex\.ru)(?!mail\.com)([\w-]+.)+[\w-]{2,4})?$/.test(email);
}

function funAjaxPostCreateProject(obj){
	jQuery('#create_project_btn').hide();
	jQuery('#wait_create_project_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#create_project_btn').show();
            jQuery('#wait_create_project_btn').hide();
        }, 4000);		
		return false;
	}  else {
        var sel_client = $('#sel_client').val();
        var new_client_email = $('#new_client_email').val();  
        var new_client_company_name = $('#new_client_company_name').val();
        
        if (sel_client == '' && new_client_email == '' && new_client_company_name == '') {
            $('#sel_client').prop('required',true);
            $('#new_client_email').prop('required',true);
            $('#new_client_company_name').prop('required',true);
            jQuery('#create_project_btn').show();
            jQuery('#wait_create_project_btn').hide();
            var create_project_obj = document.getElementById("frm_create_project");
            funAjaxPostCreateProject(create_project_obj);
            return false;            
        }else{
            if (new_client_email != '' && new_client_company_name != '') {            
                $('#sel_client').prop('required',false);            
            }
            if(sel_client != ''){            
                $('#new_client_email').prop('required',false);
                $('#new_client_company_name').prop('required',false);    
            }            
        }
        
        if (new_client_email != '' && new_client_company_name != '') {
            var ret_email = checkWorkEmailAddress(new_client_email);
            if(ret_email == false){
                displayToastNotificationInAjax('error','Please enter work email address.');
                setTimeout(function(){
                    jQuery('#create_project_btn').show();
                    jQuery('#wait_create_project_btn').hide();
                }, 4000);
                return false;
            }
        }
        
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
                        jQuery('#create_project_btn').show();
                        jQuery('#wait_create_project_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Project has been created successfully.');
        			//window.location.href = arrTotal[0];
                    $('#create_project_popup').modal('hide');
                    openCreateAgreementModal(arrTotal[0],'yes');					
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                        jQuery('#create_project_btn').show();
                        jQuery('#wait_create_project_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','Client email address already exists');
                    return false;        								
				}else{
					setTimeout(function(){
                        jQuery('#create_project_btn').show();
                        jQuery('#wait_create_project_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#create_project_btn').show();
                	jQuery('#wait_create_project_btn').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostCreateProject');        
                }
                return false;
			}
		});
	}
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

function required_cluster_event(){
    $('input[type=radio][name=client_option]').change(function() {
        if (this.value == 'sel_existing_client') {
            $('#existing_clients').addClass('active');
            $('#existing_clients_disabled').removeClass('disabled');
            $('#new_clients').removeClass('active');
            $('#new_clients_disabled').addClass('disabled');
            
            $('#sel_client').prop('disabled',false);
            $('#new_client_email').prop('disabled',true);
            $('#new_client_company_name').prop('disabled',true);
            
            $('#sel_client').prop('required',true);
            $('#new_client_email').prop('required',false);
            $('#new_client_company_name').prop('required',false);
            $('#new_client_email').val('');
            $('#new_client_company_name').val('');           
        }else if (this.value == 'new_client') {
            $('#new_clients').addClass('active');
            $('#new_clients_disabled').removeClass('disabled');
            $('#existing_clients').removeClass('active');
            $('#existing_clients_disabled').addClass('disabled');
            
            $('#sel_client').prop('disabled',true);
            $('#new_client_email').prop('disabled',false);
            $('#new_client_company_name').prop('disabled',false);
            
            $('#sel_client').prop('required',false);
            $('#sel_client').val('');
            $('#new_client_email').prop('required',true);
            $('#new_client_company_name').prop('required',true);
        } 
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

function openCreateClusterModal(){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>project/fn_get_create_cluster_popup',
        data: '1=1',
        success: function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#create_cluster_content').html(arrTotal[0]);
                $('#create_cluster_popup').modal('show');
                if($('select:not(.ignore)').length > 0){
                    $('select:not(.ignore)').niceSelect(); 
                };
                $('#frm_create_cluster').keypress(function(e) {
                    var key = e.which;
                    if (key == 13) {
                        var create_cluster_obj = document.getElementById("frm_create_cluster");
                        funAjaxPostCreateCluster(create_cluster_obj);
                        return false;
                    }
                });      
                required_cluster_event();
                what_is_cluster_event();                
            }                             
        }
    });        
}

function funAjaxPostCreateCluster(obj){
    jQuery('#create_cluster_btn').hide();
	jQuery('#wait_create_cluster_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#create_cluster_btn').show();
            jQuery('#wait_create_cluster_btn').hide();
        }, 4000);		
		return false;
	}  else {
        var sel_client = $('#sel_client').val();
        var new_client_email = $('#new_client_email').val();  
        var new_client_company_name = $('#new_client_company_name').val();
        
        if (sel_client == '' && new_client_email == '' && new_client_company_name == '') {
            $('#sel_client').prop('required',true);
            $('#new_client_email').prop('required',true);
            $('#new_client_company_name').prop('required',true);
            jQuery('#create_cluster_btn').show();
            jQuery('#wait_create_cluster_btn').hide();
            var create_cluster_obj = document.getElementById("frm_create_cluster");
            funAjaxPostCreateCluster(create_cluster_obj);
            return false;            
        }else{
            if (new_client_email != '' && new_client_company_name != '') {            
                $('#sel_client').prop('required',false);            
            }
            if(sel_client != ''){            
                $('#new_client_email').prop('required',false);
                $('#new_client_company_name').prop('required',false);    
            }            
        }
        
        if (new_client_email != '' && new_client_company_name != '') {
            var ret_email = checkWorkEmailAddress(new_client_email);
            if(ret_email == false){
                displayToastNotificationInAjax('error','Please enter work email address.');
                setTimeout(function(){
                    jQuery('#create_cluster_btn').show();
                    jQuery('#wait_create_cluster_btn').hide();
                }, 4000);
                return false;
            }
        }
        
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
                        jQuery('#create_cluster_btn').show();
                        jQuery('#wait_create_cluster_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Cluster has been created successfully.');
        			//window.location.href = arrTotal[0];
                    /*$('#create_project_popup').modal('hide');
                    openCreateAgreementModal(arrTotal[0],'yes');*/
                    $('#create_cluster_content').html(arrTotal[0]);
                    $('#create_cluster_popup').modal('show');
                    what_is_cluster_event();
                    new_phase_required_event();
                    new_phase_date_picker_event();                                        
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                        jQuery('#create_cluster_btn').show();
                        jQuery('#wait_create_cluster_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','Client email address already exists');
                    return false;        								
				}else{
					setTimeout(function(){
                        jQuery('#create_cluster_btn').show();
                        jQuery('#wait_create_cluster_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){				
                setTimeout(function(){
                	jQuery('#create_cluster_btn').show();
                	jQuery('#wait_create_cluster_btn').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostCreateCluster');        
                }
                return false;
			}
		});
	}	
}

let phase_arr = [];
function funAddPhaseFromClusterProject(){
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
        /*console.log(phase_arr[1][0]+'>>>'+phase_arr[1][1]+'>>>'+phase_arr[1][2])  
        phase_arr = phase_arr.slice(0); // make copy
        phase_arr.splice(0, 1);
        console.log(phase_arr);*/
        phase_arr.push( [phase_title, start_date, end_date] );
        $('#phase_title').val('');
        $('#start_date').val('');
        $('#end_date').val('');                 
        if(phase_arr.length > 0){
            $('#one_phase_required').hide();
            var display_table_html = '<table class="table"><tbody>';
            for (var i = 0; i < phase_arr.length; i++) {
                var display_no = i + 1;
                display_table_html = display_table_html + '<tr><td><b>'+display_no+'</b></td><td>'+phase_arr[i][0]+'</td><td class="text-right">'+phase_arr[i][1]+' - '+phase_arr[i][2]+'</td><td style="width: 50px;"><div class="table-delete-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/delete-outline-icon.svg" onclick="funRemovePhaseFromClusterProject('+i+')"></div></td></tr>';
            }
            display_table_html = display_table_html + '</tbody></table>';
            
            $('#no_phases_block').removeClass('d-flex');
            $('#no_phases_block').hide();
            $('#list_of_phases_block').html(display_table_html);
            $('#list_of_phases_block').show();
        }             
    }
    
}

function funRemovePhaseFromClusterProject(remove_element_key){
    phase_arr = phase_arr.slice(0); // make copy
    phase_arr.splice(remove_element_key, 1);
    if(phase_arr.length > 0){
        $('#one_phase_required').hide();
        var display_table_html = '<table class="table"><tbody>';
        for (var i = 0; i < phase_arr.length; i++) {
            var display_no = i + 1;
            display_table_html = display_table_html + '<tr><td><b>'+display_no+'</b></td><td>'+phase_arr[i][0]+'</td><td class="text-right">'+phase_arr[i][1]+' - '+phase_arr[i][2]+'</td><td style="width: 50px;"><div class="table-delete-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/delete-outline-icon.svg" onclick="funRemovePhaseFromClusterProject('+i+')"></div></td></tr>';
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

function funAjaxPostCreateClusterProject(obj){
    var project_title = $('#project_title').val();
    var cluster_sk = $('#cluster_sk').val();
    if(project_title == ''){
        $('#project_title_required').show();        
    }else{
        $('#project_title_required').hide();
    }
    if(phase_arr.length > 0){
        $('#one_phase_required').hide();    
    }else{
        $('#one_phase_required').show();
    }
    if(phase_arr.length > 0 && project_title != ''){
        jQuery('#create_cluster_project_btn').hide();
        jQuery('#wait_create_cluster_project_btn').show();
        var phase_arr_text = JSON.stringify( phase_arr );
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>project/fn_post_create_cluster_project_popup',
            data: 'cluster_sk='+cluster_sk+'&project_title='+project_title+'&phase_arr='+phase_arr_text,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    displayToastNotificationInAjax('success','Project has been created successfully.');
                    $('#full_progress_bar').addClass('full');
                    setTimeout(function(){
                        jQuery('#create_cluster_project_btn').show();
                        jQuery('#wait_create_cluster_project_btn').hide();
                        window.location.href = arrTotal[0];
                    }, 2000);
                }                             
            }
        });	
    }else{
        return false;
    }
            
}

</script>