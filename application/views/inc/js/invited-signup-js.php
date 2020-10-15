<script>
jQuery(document).ready(function($){
    
    $('#frm_invited_signup_step_1').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var invited_signup_step_1_obj = document.getElementById("frm_invited_signup_step_1");
            funAjaxPostInvitedStep1(invited_signup_step_1_obj);
            return false;
        }
    });
    
});

function funAjaxPostInvitedStep1(obj){
	jQuery('#btn_invited_signup_step_1').hide();
	jQuery('#wait_btn_invited_signup_step_1').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_invited_signup_step_1').show();
            jQuery('#wait_btn_invited_signup_step_1').hide();
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
                        jQuery('#btn_invited_signup_step_1').show();
                        jQuery('#wait_btn_invited_signup_step_1').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('body').removeClass('member-page');
                    $('body').addClass('pt-0');
                    $('#signup_html').html(arrTotal[0]);
                    $('#frm_invited_signup_step_2').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var invited_signup_step_2_obj = document.getElementById("frm_invited_signup_step_2");
                            funAjaxPostInvitedStep2(invited_signup_step_2_obj);
                            return false;
                        }
                    });
                    invited_user_type_selection_change_event();
        			return false;					
				}else{
					setTimeout(function(){
                        jQuery('#btn_invited_signup_step_1').show();
                        jQuery('#wait_btn_invited_signup_step_1').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){	
                setTimeout(function(){
                	jQuery('#btn_invited_signup_step_1').show();
                	jQuery('#wait_btn_invited_signup_step_1').hide();
                }, 4000);			
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostInvitedStep1');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostInvitedStep2(obj){
	jQuery('#btn_invited_signup_step_2').hide();
	jQuery('#wait_btn_invited_signup_step_2').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
    var user_type_selection = $('input[type=radio][name=user_type]:checked').val();
    if(user_type_selection == 'agency'){
        if($('#agency_terms_condition').prop("checked") == true){
            //alert('checked')
        }else if($('#agency_terms_condition').prop("checked") == false){
            displayToastNotificationInAjax('error','Please accept terms of use, community standards and privacy and cookie policy.');
            setTimeout(function(){ 
                jQuery('#btn_invited_signup_step_2').show();
                jQuery('#wait_btn_invited_signup_step_2').hide(); 
            }, 4000);
            return false;
        }    
    }else if (user_type_selection == 'client') {
        if($('#client_terms_condition').prop("checked") == true){
            //alert('checked')
        }else if($('#client_terms_condition').prop("checked") == false){
            displayToastNotificationInAjax('error','Please accept subscription agreement, community standards and privacy and cookie policy.');
            setTimeout(function(){ 
                jQuery('#btn_invited_signup_step_2').show();
                jQuery('#wait_btn_invited_signup_step_2').hide(); 
            }, 4000);
            return false;
        }
    }else if (user_type_selection == 'both') {
        if($('#both_terms_condition').prop("checked") == true){
            //alert('checked')
        }else if($('#both_terms_condition').prop("checked") == false){
            displayToastNotificationInAjax('error','Please accept terms of use, community standards, subscription agreement and privacy and cookie policy.');
            setTimeout(function(){ 
                jQuery('#btn_invited_signup_step_2').show();
                jQuery('#wait_btn_invited_signup_step_2').hide(); 
            }, 4000);
            return false;
        }            
    }
    
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_invited_signup_step_2').show();
            jQuery('#wait_btn_invited_signup_step_2').hide();
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
                        jQuery('#btn_invited_signup_step_2').show();
                        jQuery('#wait_btn_invited_signup_step_2').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('#signup_html').html(arrTotal[0]);
                    $('#frm_new_signup_step_4').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step4_obj = document.getElementById("frm_new_signup_step_4");
                            funAjaxPostNewSignUpStep4(signup_step4_obj);
                            return false;
                        }
                    });
                    $("#user_profile_image").on("change",function() {
                        readURL(this);
                    });
                    
                    $("#cropped_again").on("click",function(){
                        $("#cropagain_button_div").hide();
                        $("#result_div").hide();
                        $("#crop_image_div").show();
                        $("#crop_button_div").show();
                        $("#crop_image_popup").show();
                        $("#crop_image_overlay").show();
                    });
                    
                    $("#btn_crop_cancel").on("click",function(){
                        $("#cropagain_button_div").hide();
                        $("#result_div").show();
                        $("#crop_image_div").hide();
                        $("#crop_button_div").hide();
                        $("#crop_image_popup").hide();
                        $("#crop_image_overlay").hide();
                    });
                    back_from_profile_setup_event();
        			return false;					
				}else{
					setTimeout(function(){
                        jQuery('#btn_invited_signup_step_2').show();
                        jQuery('#wait_btn_invited_signup_step_2').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){	
                setTimeout(function(){
                	jQuery('#btn_invited_signup_step_2').show();
                	jQuery('#wait_btn_invited_signup_step_2').hide();
                }, 4000);			
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostInvitedStep2');        
                }
                return false;
			}
		});
	}
} 

</script>