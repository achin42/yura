<?php 
	if( isset($_REQUEST['success']) && strlen($_REQUEST['success'])>0 )
	{
		$title = 'Success';
		$type = 'success';
		$text = $_REQUEST['success'];
	}
	if( isset($_REQUEST['errors']) && strlen($_REQUEST['errors'])>0 )
	{
		$title = 'Error';
		$type = 'error';
		$text = strip_tags(preg_replace('/\s\s+/', ' ',$_REQUEST['errors']));
	}
	if( (isset($_REQUEST['success']) && strlen($_REQUEST['success'])>0) ||
		(isset($_REQUEST['errors']) && strlen($_REQUEST['errors'])>0) ){
?>
<script type="text/javascript">
$(document).ready(function () {       
    var msg_type = "<?php echo $type;?>";//info,warning,error
    if (msg_type == 'success') {
		closehtml = '<span class="alert-icon"><i class="fal fa-check"></i></span>';
		finalhtml = "<span class='alert-right'><a href='javascript:' class='alert-close' ><i class='fal fa-chevron-right'></i></a></span>";
	} else {
		closehtml = '<span class="alert-icon"><i class="fal fa-exclamation-circle"></i></span>';
		finalhtml = '<span class="alert-right"><a href="javascript:"  data-dismiss="alert" class="alert-close" ><i class="fal fa-times"></i></a></span>';
	}
    toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "4000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut",
      "closeHtml": closehtml
	};
    
    var shortCutFunction = "<?php echo $type;?>";//info,warning,error
    var msg = "<?php echo $text;?>";
    var title = "" || '';
    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
    
    $("span.alert-right").remove();
    $("#alert-container").find('.alert-text').after(finalhtml);
}); 
</script>
<?php }?>

<script type="text/javascript">
function displayToastNotificationInAjax(msg_type,msg_title){
	if (msg_type == 'success') {
		closehtml = '<span class="alert-icon"><i class="fal fa-check"></i></span>';
		finalhtml = "<span class='alert-right'><a href='javascript:' class='alert-close' ><i class='fal fa-chevron-right'></i></a></span>";
	} else {
		closehtml = '<span class="alert-icon"><i class="fal fa-exclamation-circle"></i></span>';
		finalhtml = '<span class="alert-right"><a href="javascript:"  data-dismiss="alert" class="alert-close" ><i class="fal fa-times"></i></a></span>';
	}
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "4000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut",
	  "closeHtml": closehtml,
	};

    var shortCutFunction = msg_type;//success,info,warning,error
    var msg = msg_title;
    var title = "" || '';
    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists

    $("span.alert-right").remove();
    $("#alert-container").find('.alert-text').after(finalhtml);

}

$(document).ready(function () {
	$(".alert-close").click(function () {
		$("#alert-container").fadeOut(0);
	});

    $('.login_mode').click(function () {
        var user_login_mode = "<?php echo $this->session->userdata('fe_user_login_mode'); ?>";
        if(user_login_mode == 'AGENCY'){
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->config->item("site_url");?>user/fn_change_user_login_mode',
                data: '1=1',
                success: function(data){
                    window.location.reload();         
                }
            });
        }else{
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->config->item("site_url");?>user/fn_check_agency_verified_or_not',
                data: '1=1',
                success: function(data){
                    if(data == 'pending_verification'){
                        $("#company_verification_popup").removeClass('display-hide');
                    }else{
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->config->item("site_url");?>user/fn_change_user_login_mode',
                            data: '1=1',
                            success: function(data){
                                window.location.reload();         
                            }
                        });
                    }                         
                }
            });
        }            
    });
    
    $("#hide_company_verification_popup").click(function () {
        $("#company_verification_popup").addClass('display-hide');
	});
    
});

function fn_get_ajax_error_handling_messages(jqXHR, exception){
    var err_msg = '';
    if (jqXHR.status === 0) {
        err_msg = 'An unexpected error has occurred. Please verify your network connection.';
    } else if (jqXHR.status == 404) {
        err_msg = 'Requested page not found. Please try again.';
    } else if (jqXHR.status == 500) {
        err_msg = 'Internal server error. Please try later.';
    } else if (exception === 'parsererror') {
        err_msg = 'An unexpected error has occurred. JSON parsing failed.';
    } else if (exception === 'timeout') {
        err_msg = 'An unexpected error has occurred. Request timed out.';
    } else if (exception === 'abort') {
        err_msg = 'An unexpected error has occurred. Ajax request aborted.';
    } else {
        err_msg = 'An unexpected error has occurred. Uncaught Error. ' + jqXHR.responseText;
    }
    return err_msg;
}

function fn_send_ajax_error_handling_mail(jqxhr_status, jqxhr_responsetext, exception, err_msg, function_name){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>welcome/fn_send_ajax_error_handling_mail',
        data: 'jqxhr_status='+jqxhr_status+'&jqxhr_responsetext='+jqxhr_responsetext+'&exception='+exception+'&err_msg='+err_msg+'&function_name='+function_name,
        success: function(data){
                                                 
        }
    });    
}

function funAjaxPostApplyForManualVerificationAgencyCompanyDetail(obj){
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
    var subform = jQuery('#'+formid).serialize();
    $.ajax({
    	type: 'POST',
    	//url: obj.action,
        url: '<?php echo $this->config->item("site_url");?>user/fn_apply_for_manual_verification_agency_company_detail',
    	data:  new FormData(obj),
    	contentType: false,
    	cache: false,
    	processData:false,
    	beforeSend:function(){ },                    
    	success:function(data){
    		var arrTotal = data.split('^^');
    		if(arrTotal[1] == '1'){
                setTimeout(function(){
                	jQuery('#apply_for_manual_verification').css('display','block');
                	jQuery('#wait_apply_for_manual_verification').css('display','none');
                }, 4000);
                displayToastNotificationInAjax('success','Information Saved!');
                $('#verification_failed_popup').css('display','none');
                $('#verification_failed_overlay').css('display','none');
                $('#manual_verification_succ_popup').css('display','block');
                $('#manual_verification_succ_overlay').css('display','block');
                /*setTimeout(function(){ 
                    window.location.href = arrTotal[0]; 
                }, 1500);*/                    
    			return false;					
    		}else{
  			   setTimeout(function(){
                  	jQuery('#apply_for_manual_verification').css('display','block');
                	jQuery('#wait_apply_for_manual_verification').css('display','none');
                }, 4000);
                displayToastNotificationInAjax('error',data);
                return false;
    		}
    	},
    	error:function(jqXHR, exception){
    		setTimeout(function(){
                jQuery('#apply_for_manual_verification').css('display','block');
                jQuery('#wait_apply_for_manual_verification').css('display','none');
            }, 4000);
            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
            if(err_msg != ''){
                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostApplyForManualVerificationAgencyCompanyDetail');        
            }
            return false;
    	}
    });
}

function funAjaxPostAgencyCompanySetupVerifyLater(obj){
	jQuery('#btn_agency_company_setup_verify_later').hide();
	jQuery('#wait_btn_agency_company_setup_verify_later').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_agency_company_setup_verify_later').show();
            jQuery('#wait_btn_agency_company_setup_verify_later').hide();
        }, 4000);		
		return false;
	}  else {
        var company_name = $('#company_name').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>user/fn_check_company_name_exists',
            data: 'company_name='+company_name,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    if(arrTotal[0] == 'company_exists'){
                        setTimeout(function(){
                            jQuery('#btn_agency_company_setup_verify_later').show();
                            jQuery('#wait_btn_agency_company_setup_verify_later').hide();
                        }, 4000);
                        displayToastNotificationInAjax('error','User with same company name already exists.'); 
                        return false;    
                    }else{
                        var subform = jQuery('#'+formid).serialize();
                		$.ajax({
                			type: 'POST',
                			url: '<?php echo $this->config->item("site_url");?>user/fn_agency_company_setup_verify_later',
                			data:  new FormData(obj),
                			contentType: false,
                			cache: false,
                			processData:false,
                			beforeSend:function(){ },                    
                			success:function(data){
                				var arrTotal = data.split('^^');
                				if(arrTotal[1] == '1'){
                                    setTimeout(function(){
                                        jQuery('#btn_agency_company_setup_verify_later').show();
                                        jQuery('#wait_btn_agency_company_setup_verify_later').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    setTimeout(function(){ 
                                        window.location.href = arrTotal[0]; 
                                    }, 1000);                    
                        			return false;					
                				}else if(arrTotal[1] == '2'){
                                    jQuery('#btn_agency_company_setup_verify_later').show();
                                    jQuery('#wait_btn_agency_company_setup_verify_later').hide();
                                    displayToastNotificationInAjax('error','Please try again later.');
                                    return false;
                				}else{
                					setTimeout(function(){
                                        jQuery('#btn_agency_company_setup_verify_later').show();
                                        jQuery('#wait_btn_agency_company_setup_verify_later').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                	jQuery('#btn_agency_company_setup_verify_later').show();
                                	jQuery('#wait_btn_agency_company_setup_verify_later').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostAgencyCompanySetupVerifyLater');        
                                }
                                return false;
                			}
                		});    
                    }           
                }                             
            }
        });
	}
}

</script>