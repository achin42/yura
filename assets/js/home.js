jQuery(document).ready(function($){

    $('#sign_in_link').click(function () {
        $('#sign_up_div').hide();
        $('#forgot_password_div').hide();
        $('#sign_in_div').removeClass('display-hide');
        $('#sign_in_div').show();
        return false;
	});
    
    $('#sign_up_link').click(function () {
        $('#sign_in_div').hide();
        $('#forgot_password_div').hide();
        $('#sign_up_div').removeClass('display-hide');
        $('#sign_up_div').show();        
        return false;
	});
    
    $('#forgot_password_link').click(function () {
        var sign_in_email = $('#sign_in_email').val();
        $('#sign_in_div').hide();
        $('#sign_up_div').hide();
        $('#forgot_password_div').removeClass('display-hide');
        $('#forgot_password_div').show();             
        $('#forgot_password_email').val(sign_in_email);   
        return false;
	});
    
    $('#back_to_login_link').click(function () {
        $('#sign_up_div').hide();
        $('#forgot_password_div').hide();
        $('#sign_in_div').removeClass('display-hide');
        $('#sign_in_div').show();
        return false;
	});
    
    // $('#myModal').appendTo("body").modal('show');
    $('#frm_login').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var login_obj = document.getElementById("frm_login");
            funAjaxPostSignin(login_obj);
            return false;
        }
    });
    
    $('#frm_signup').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var signup_obj = document.getElementById("frm_signup");
            funAjaxPostSignup(signup_obj);
            return false;
        }
    });      
    
    $('#frm_forgot_password').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var forgot_password_obj = document.getElementById("frm_forgot_password");
            funAjaxPostForgotPassword(forgot_password_obj);
            return false;
        }
    });   
            
});

function funAjaxPostSignin(obj){
	jQuery('#signin_btn').hide();
	jQuery('#wait_signin_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#signin_btn').show();
        jQuery('#wait_signin_btn').hide();
		return false;
	}  else {         
		var subform = jQuery('#'+formid).serialize();
		$.ajax({
			type: 'POST',
			url: obj.action,
            //url: 'err_msg',
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    location.reload();
                    //window.location.replace(arrTotal[0]);
                    //window.location.href = arrTotal[0];
                    //fn_send_ajax_error_handling_mail('aaa');
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#signin_btn').show();
                    	jQuery('#wait_signin_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',arrTotal[0]);
				}else{
					setTimeout(function(){
                    	jQuery('#signin_btn').show();
                    	jQuery('#wait_signin_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
				}
			},
			error:function(jqXHR, exception){
				setTimeout(function(){
                	jQuery('#signin_btn').show();
                	jQuery('#wait_signin_btn').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostSignin');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostSignup(obj){
	jQuery('#signup_btn').hide();
	jQuery('#wait_signup_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#signup_btn').show();
        jQuery('#wait_signup_btn').hide();
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
                    	jQuery('#signup_btn').show();
                    	jQuery('#wait_signup_btn').hide();
                    }, 4000);
        			jQuery('form#'+formid).find('textarea,input').each(function(i, field) {
        				if(field.type != 'hidden' && field.type != 'button' && field.type != 'submit'){ field.value = ''; }
        			});
                    $('#sign_up_div').hide();
                    $('#forgot_password_div').hide();
                    $('#sign_in_div').show();
                    displayToastNotificationInAjax('success','You have been registered successfully! Please verify your email in order to activate your account.');
                    return false;					
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#signup_btn').show();
                    	jQuery('#wait_signup_btn').hide();
                    }, 4000);
        			jQuery('#email').val('');
                    displayToastNotificationInAjax('error','This email address already exists!');
                    return false;
				}else{
					setTimeout(function(){
                    	jQuery('#signup_btn').show();
                    	jQuery('#wait_signup_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#signup_btn').show();
                	jQuery('#wait_signup_btn').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostSignup');        
                }
                return false;
			}
		});
	}
}   
 
function funAjaxPostForgotPassword(obj){
	jQuery('#forgot_password_btn').hide();
	jQuery('#wait_forgot_password_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#forgot_password_btn').show();
        jQuery('#wait_forgot_password_btn').hide();
		return false;
	}  else {         
		var subform = jQuery('#'+formid).serialize();
		$.ajax({
			type: 'POST',
			url: obj.action,
            //url: 'err_msg',
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    setTimeout(function(){
                    	jQuery('#forgot_password_btn').show();
                    	jQuery('#wait_forgot_password_btn').hide();
                    }, 4000);
                    $('#recover_password_div').html(arrTotal[0]);
                    $('#sign_up_div').hide();
                    $('#forgot_password_div').hide();
                    $('#sign_in_div').hide();
                    $('#recover_password_div').show();   
                    change_password_event();     
                    resend_change_password_email_event();    
                    $('#frm_change_password').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var change_password_obj = document.getElementById("frm_change_password");
                            funAjaxPostChangePassword(change_password_obj);
                            return false;
                        }
                    });                
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#forgot_password_btn').show();
                    	jQuery('#wait_forgot_password_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','This email address does not exists!');
				}else{
					setTimeout(function(){
                    	jQuery('#forgot_password_btn').show();
                    	jQuery('#wait_forgot_password_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
				}
			},
			error:function(jqXHR, exception){
				setTimeout(function(){
                	jQuery('#forgot_password_btn').show();
                	jQuery('#wait_forgot_password_btn').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostSignin');        
                }
                return false;
			}
		});
	}
} 

function change_password_event(){
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    
    $('#forgot_password_verification_code_1').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#forgot_password_verification_code_2').focus();
        }            
    });
    $('#forgot_password_verification_code_2').keyup(function(e) {  
        if($(this).val().length==$(this).attr('maxlength')){      
            $('#forgot_password_verification_code_3').focus();
        }            
    });
    $('#forgot_password_verification_code_3').keyup(function(e) {   
        if($(this).val().length==$(this).attr('maxlength')){     
            $('#forgot_password_verification_code_4').focus();
        }            
    });
    $('#forgot_password_verification_code_4').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#forgot_password_verification_code_5').focus();
        }            
    });
    $('#forgot_password_verification_code_5').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#forgot_password_verification_code_6').focus();
        }            
    });
    
}

function funAjaxPostChangePassword(obj){
	jQuery('#change_password_btn').hide();
	jQuery('#wait_change_password_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#change_password_btn').show();
        jQuery('#wait_change_password_btn').hide();
		return false;
	}  else {         
		var subform = jQuery('#'+formid).serialize();
		$.ajax({
			type: 'POST',
			url: obj.action,
            //url: 'err_msg',
			data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },                    
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
                    setTimeout(function(){
                    	jQuery('#change_password_btn').show();
                    	jQuery('#wait_change_password_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('#recover_password_div').hide();
                    $('#sign_up_div').hide();
                    $('#forgot_password_div').hide();
                    $('#sign_in_div').show();
                    $('#recover_password_div').html('');   
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#change_password_btn').show();
                    	jQuery('#wait_change_password_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','This email address does not exists!');
				}else{
					setTimeout(function(){
                    	jQuery('#change_password_btn').show();
                    	jQuery('#wait_change_password_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
				}
			},
			error:function(jqXHR, exception){
				setTimeout(function(){
                	jQuery('#change_password_btn').show();
                	jQuery('#wait_change_password_btn').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostSignin');        
                }
                return false;
			}
		});
	}
}

function resend_change_password_email_event(){
    $('#resend_change_password_email').click(function () {
        $.ajax({
            type: 'POST',
            url: site_url+'login/fn_send_resend_change_password_email',
            data: '1=1',
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    displayToastNotificationInAjax('success','We have just sent a verification code to your email address.');
                    return false;       
                }else{
                    displayToastNotificationInAjax('error','Something wrong. Please try again later.');
                    return false;
                }                             
            }
        });      
    });
}
