<script>
jQuery(document).ready(function($){
    
    $('#frm_new_signup_step_1').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var signup_step1_obj = document.getElementById("frm_new_signup_step_1");
            funAjaxPostNewSignUpStep1(signup_step1_obj);
            return false;
        }
    });
    
});

function submitEntrIconClick(){
    var signup_step1_obj = document.getElementById("frm_new_signup_step_1");
    funAjaxPostNewSignUpStep1(signup_step1_obj);
    return false;
}

let resize;
function readURL(input) {
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#crop_image").attr("src", e.target.result);
            if (!resize){
                resize = new Croppie($("#crop_image")[0], {
                    viewport: {
                        width: 300,
                        height: 300,
                        type: 'circle'
                    },
                    boundary: {
                        width: 400,
                        height: 400,
                        type: 'circle'
                    },
                    enableOrientation: true,
                    orientation: 4
                });     
            }else{
        	    resize.destroy();
                resize = null;
                resize = new Croppie($("#crop_image")[0], {
                    viewport: {
                        width: 300,
                        height: 300,
                        type: 'circle'
                    },
                    boundary: {
                        width: 400,
                        height: 400,
                        type: 'circle'
                    },
                    enableOrientation: true,
                    orientation: 4
                });
            }        
            //$("#btn_crop_image").fadeIn();
            $("#crop_image_div").show();
            $("#crop_button_div").show();
            $("#crop_image_popup").show();
            $("#crop_image_overlay").show();
            $("#cropagain_button_div").hide();
            $("#result_div").hide();
            $("#result_img").attr("src", '');
            $("#cropped_img").val('');
            $("#btn_crop_image").on("click", function() {
                resize.result("base64").then(function(dataImg) {
                    var data = [{ image: dataImg }, { name: "myimgage.jpg" }];
                    // use ajax to send data to php
                    $("#crop_image_div").hide();
                    $("#crop_button_div").hide();
                    $("#crop_image_popup").hide();
                    $("#crop_image_overlay").hide();
                    $("#cropagain_button_div").show();
                    $("#result_div").show();
                    $("#result_img").attr("src", dataImg);
                    $("#cropped_img").val(dataImg);                    
                });
            });
            
            $( "#rotate_image_left" ).on('click', function() {
                resize.rotate(parseInt($(this).data('rotate')));
            });
                   
            $( "#rotate_image_right" ).click(function() {
                resize.rotate(parseInt($(this).data('rotate')));
            });
      
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function showverifypopup(){
    $('#varify_signup_popup').modal('show');
}

function showfailpopup(){
    $('#varify_signup_popup').modal('hide');
    $('#failed_signup_popup').modal('show');
}

function toggle_password_event(){
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    
    $('#verification_code_1').keyup(function(e) {     
        if($(this).val().length==$(this).attr('maxlength')){   
            $('#verification_code_2').focus();
        }            
    });
    $('#verification_code_2').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#verification_code_3').focus();
        }            
    });
    $('#verification_code_3').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#verification_code_4').focus();
        }            
    });
    $('#verification_code_4').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#verification_code_5').focus();
        }            
    });
    $('#verification_code_5').keyup(function(e) {
        if($(this).val().length==$(this).attr('maxlength')){        
            $('#verification_code_6').focus();
        }            
    });
}

function change_email_event(){
    $('#change_email_address').click(function () {
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_get_change_email',
            data: '1=1',
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    $('#signup_html').html(arrTotal[0]); 
                    $('#frm_new_signup_step_1').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step1_obj = document.getElementById("frm_new_signup_step_1");
                            funAjaxPostNewSignUpStep1(signup_step1_obj);
                            return false;
                        }
                    });   
                }                             
            }
        });      
    });
}

function resend_email_event(){
    $('#resend_email').click(function () {
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_send_resend_email',
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

function user_type_selection_change_event(){
    $('input[type=radio][name=user_type]').change(function() {
        if (this.value == 'agency') {
            $('#agency_div').show();
            $('#client_div').hide();
            $('#both_div').hide();
        }else if (this.value == 'client') {
            $('#client_div').show();
            $('#agency_div').hide();            
            $('#both_div').hide();
        }else if (this.value == 'both') {
            $('#both_div').show();
            $('#agency_div').hide();
            $('#client_div').hide();            
        }
    });
}

function invited_user_type_selection_change_event(){
    $('input[type=radio][name=user_type]').change(function() {
        if (this.value == 'agency') {
            $('#agency_div').show();
            $('#client_div').hide();
            $('#both_div').hide();
        }else if (this.value == 'client') {
            $('#client_div').show();
            $('#agency_div').hide();            
            $('#both_div').hide();
        }else if (this.value == 'both') {
            $('#both_div').show();
            $('#agency_div').hide();
            $('#client_div').hide();            
        }
    });
}

function back_from_profile_setup_event(){
    $('#back_from_profile_setup').click(function () {
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_get_signup_selection',
            data: '1=1',
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    $('#signup_html').html(arrTotal[0]);
                    var sess_signup_invite_flow = arrTotal[2];  
                    if(sess_signup_invite_flow == 'yes'){
                        $('#frm_invited_signup_step_2').keypress(function(e) {
                            var key = e.which;
                            if (key == 13) {
                                var invited_signup_step_2_obj = document.getElementById("frm_invited_signup_step_2");
                                funAjaxPostInvitedStep2(invited_signup_step_2_obj);
                                return false;
                            }
                        });
                        invited_user_type_selection_change_event();
                    }else{
                        $('#frm_new_signup_step_3').keypress(function(e) {
                            var key = e.which;
                            if (key == 13) {
                                var signup_step3_obj = document.getElementById("frm_new_signup_step_3");
                                funAjaxPostNewSignUpStep3(signup_step3_obj);
                                return false;
                            }
                        });
                        user_type_selection_change_event();
                    }                       
                }                             
            }
        });      
    });
}

function back_from_company_detail_event(){
    $('#back_from_company_detail').click(function () {
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_get_profile_setup_ajax',
            data: '1=1',
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    $('#signup_html').html(arrTotal[0]); 
                    $('#frm_new_signup_step_4').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step4_obj = document.getElementById("frm_new_signup_step_4");
                            funAjaxPostNewSignUpStep4(signup_step4_obj);
                            return false;
                        }
                    });   
                    back_from_profile_setup_event();
                }                             
            }
        });      
    });
}

function get_dial_code_selection(country_code_name){
    var area_code = $('#area_code').val();
    if(area_code == ''){
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_get_dial_code_selection',
            data: 'country_code_name='+country_code_name,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    $('#area_code').html(arrTotal[0]);                    
                }                             
            }
        });
    }            
}

function checkWorkEmailAddress(email){
    return /^([\w-.]+@(?!gmail\.com)(?!googlemail\.com)(?!yahoo\.com)(?!yahoomail\.com)(?!outlook\.com)(?!aol\.com)(?!protonmail\.com)(?!hotmail\.com)(?!mail\.ru)(?!yandex\.ru)(?!mail\.com)([\w-]+.)+[\w-]{2,4})?$/.test(email);
}

function funAjaxPostNewSignUpStep1(obj){
    var sign_up_email = $('#sign_up_email').val();
    var ret_email = checkWorkEmailAddress(sign_up_email);
    if(ret_email == false){
        displayToastNotificationInAjax('error','Please enter work email address.');
        return false;
    }
	/*jQuery('#signup_btn').hide();
	jQuery('#wait_signup_btn').show();*/
    var formid = $(obj).attr('id');
    var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		/*jQuery('#signup_btn').show();
        jQuery('#wait_signup_btn').hide();*/
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
                    /*jQuery('#signup_btn').show();
                    jQuery('#wait_signup_btn').hide();*/
                    displayToastNotificationInAjax('success','We have just sent a verification code to your email address.');
                    $('#signup_html').html(arrTotal[0]);                    
                    $('#frm_new_signup_step_2').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step2_obj = document.getElementById("frm_new_signup_step_2");
                            funAjaxPostNewSignUpStep2(signup_step2_obj);
                            return false;
                        }
                    });
                    toggle_password_event();
                    change_email_event();
                    resend_email_event();
        			return false;					
				}else if(arrTotal[1] == '2'){
                    /*jQuery('#signup_btn').show();
                    jQuery('#wait_signup_btn').hide();*/
                    displayToastNotificationInAjax('error','This email address already exists. Please enter any other email address.');
                    return false;
                }else if(arrTotal[1] == '3'){
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('#signup_html').html(arrTotal[0]);                    
                    $('#frm_invited_signup_step_1').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var invited_signup_step_1_obj = document.getElementById("frm_invited_signup_step_1");
                            funAjaxPostInvitedStep1(invited_signup_step_1_obj);
                            return false;
                        }
                    });
                    toggle_password_event();
                    change_email_event();
                    resend_email_event();
        			return false;
				}else{
					/*jQuery('#signup_btn').show();
                    jQuery('#wait_signup_btn').hide();*/
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep1');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostNewSignUpStep2(obj){
	jQuery('#btn_signup_step_2').hide();
	jQuery('#wait_btn_signup_step_2').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_signup_step_2').show();
            jQuery('#wait_btn_signup_step_2').hide();
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
                        jQuery('#btn_signup_step_2').show();
                        jQuery('#wait_btn_signup_step_2').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('body').removeClass('member-page');
                    $('body').addClass('pt-0');
                    $('#signup_html').html(arrTotal[0]);
                    $('#frm_new_signup_step_3').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step3_obj = document.getElementById("frm_new_signup_step_3");
                            funAjaxPostNewSignUpStep3(signup_step3_obj);
                            return false;
                        }
                    });
                    user_type_selection_change_event();
        			return false;					
				}else{
					setTimeout(function(){
                        jQuery('#btn_signup_step_2').show();
                        jQuery('#wait_btn_signup_step_2').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){                
                setTimeout(function(){
                    jQuery('#btn_signup_step_2').show();
                    jQuery('#wait_btn_signup_step_2').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep2');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostNewSignUpStep3(obj){
	jQuery('#btn_signup_step_3').hide();
	jQuery('#wait_btn_signup_step_3').show();
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
                jQuery('#btn_signup_step_3').show();
                jQuery('#wait_btn_signup_step_3').hide(); 
            }, 4000);
            return false;
        }    
    }else if (user_type_selection == 'client') {
        if($('#client_terms_condition').prop("checked") == true){
            //alert('checked')
        }else if($('#client_terms_condition').prop("checked") == false){
            displayToastNotificationInAjax('error','Please accept subscription agreement, community standards and privacy and cookie policy.');
            setTimeout(function(){ 
                jQuery('#btn_signup_step_3').show();
                jQuery('#wait_btn_signup_step_3').hide(); 
            }, 4000);
            return false;
        }
    }else if (user_type_selection == 'both') {
        if($('#both_terms_condition').prop("checked") == true){
            //alert('checked')
        }else if($('#both_terms_condition').prop("checked") == false){
            displayToastNotificationInAjax('error','Please accept terms of use, community standards, subscription agreement and privacy and cookie policy.');
            setTimeout(function(){ 
                jQuery('#btn_signup_step_3').show();
                jQuery('#wait_btn_signup_step_3').hide(); 
            }, 4000);
            return false;
        }            
    }
    
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_signup_step_3').show();
            jQuery('#wait_btn_signup_step_3').hide();
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
                        jQuery('#btn_signup_step_3').show();
                        jQuery('#wait_btn_signup_step_3').hide();
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
                        jQuery('#btn_signup_step_3').show();
                        jQuery('#wait_btn_signup_step_3').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){                
                setTimeout(function(){
                    jQuery('#btn_signup_step_3').show();
                    jQuery('#wait_btn_signup_step_3').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep3');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostNewSignUpStep4(obj){
	jQuery('#btn_signup_step_4').hide();
	jQuery('#wait_btn_signup_step_4').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_signup_step_4').show();
            jQuery('#wait_btn_signup_step_4').hide();
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
                        jQuery('#btn_signup_step_4').show();
                        jQuery('#wait_btn_signup_step_4').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    $('#signup_html').html(arrTotal[0]);
                    $('#frm_new_signup_step_5').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step5_obj = document.getElementById("frm_new_signup_step_5");
                            funAjaxPostNewSignUpStep5(signup_step5_obj);
                            return false;
                        }
                    });
                    $('#frm_new_signup_step_6').keypress(function(e) {
                        var key = e.which;
                        if (key == 13) {
                            var signup_step6_obj = document.getElementById("frm_new_signup_step_6");
                            funAjaxPostNewSignUpStep6(signup_step6_obj);
                            return false;
                        }
                    });
                    $("#company_logo_image").on("change",function() {
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
                    back_from_company_detail_event();
        			return false;					
				}else{
					setTimeout(function(){
                        jQuery('#btn_signup_step_4').show();
                        jQuery('#wait_btn_signup_step_4').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){                
                setTimeout(function(){
                    jQuery('#btn_signup_step_4').show();
                    jQuery('#wait_btn_signup_step_4').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep4');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostNewSignUpStep5(obj){
	jQuery('#btn_signup_step_5').hide();
	jQuery('#wait_btn_signup_step_5').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_signup_step_5').show();
            jQuery('#wait_btn_signup_step_5').hide();
        }, 4000);		
		return false;
	}  else {
        var company_name = $('#company_name').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_check_company_name_exists',
            data: 'company_name='+company_name,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    if(arrTotal[0] == 'company_exists'){
                        setTimeout(function(){
                            jQuery('#btn_signup_step_5').show();
                            jQuery('#wait_btn_signup_step_5').hide();
                        }, 4000);
                        displayToastNotificationInAjax('error','User with same company name already exists.'); 
                        return false;    
                    }else{
                        $('#varify_popup').modal('show');
                        $('#varify_popup').addClass('show');
                        $('#varify_popup').css('display','block');
                        $('.modal-backdrop').css('display','block');
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
                                        jQuery('#btn_signup_step_5').show();
                                        jQuery('#wait_btn_signup_step_5').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    //$('#varify_popup').modal('hide');
                                    $('#varify_popup').removeClass('show');
                                    $('#varify_popup').css('display','none');
                                    $('.modal-backdrop').css('display','none');
                                    $('#succ_popup').modal('show');
                                    setTimeout(function(){ 
                                        //window.location.href = arrTotal[0]; 
                                    }, 1500);                    
                        			return false;					
                				}else if(arrTotal[1] == '2'){
                                    setTimeout(function(){
                                        jQuery('#btn_signup_step_5').show();
                                        jQuery('#wait_btn_signup_step_5').hide();
                                    }, 4000);
                                    $('#varify_popup').removeClass('show');
                                    $('#varify_popup').css('display','none');
                                    $('.modal-backdrop').css('display','none');
                                    //$('#failed_popup').modal('show');
                                    
                                    $('body').removeClass('modal-open');
                                    $('body').css('padding-right','0px');
                                    $('#verification_failed_popup').css('display','block');
                                    $('#verification_failed_overlay').css('display','block');
                                    $("#close_verification_detail").off("click");
                                    $("#close_verification_detail").on("click",function(){
                                        $('#verification_failed_popup').css('display','none');
                                        $('#verification_failed_overlay').css('display','none');
                                    });
                                    $("#apply_for_manual_verification").off("click");
                                    $("#apply_for_manual_verification").on("click",function(){
                                        jQuery('#wait_apply_for_manual_verification').css('display','block');
                                        jQuery('#apply_for_manual_verification').css('display','none');
                                        var apply_for_manual_verification_obj = document.getElementById("frm_new_signup_step_5");
                                        funAjaxPostSignupApplyForManualVerificationAgencyCompanyDetail(apply_for_manual_verification_obj);
                                        return false;
                                    });
                                    
                                    return false;
                				}else{
                					setTimeout(function(){
                                        jQuery('#btn_signup_step_5').show();
                                        jQuery('#wait_btn_signup_step_5').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                    jQuery('#btn_signup_step_5').show();
                                    jQuery('#wait_btn_signup_step_5').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep5');        
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

function funAjaxPostNewSignUpStep6(obj){
	jQuery('#btn_signup_step_6').hide();
	jQuery('#wait_btn_signup_step_6').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_signup_step_6').show();
        jQuery('#wait_btn_signup_step_6').hide();
		return false;
	}  else {
        var company_name = $('#company_name').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_check_company_name_exists',
            data: 'company_name='+company_name,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    if(arrTotal[0] == 'company_exists'){
                        setTimeout(function(){
                            jQuery('#btn_signup_step_6').show();
                            jQuery('#wait_btn_signup_step_6').hide();
                        }, 4000);
                        displayToastNotificationInAjax('error','User with same company name already exists.'); 
                        return false;    
                    }else{
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
                                    	jQuery('#btn_signup_step_6').show();
                                    	jQuery('#wait_btn_signup_step_6').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    window.location.href = arrTotal[0]; 
                                    return false;					
                				}else if(arrTotal[1] == '2'){
                                    setTimeout(function(){
                                    	jQuery('#btn_signup_step_6').show();
                                    	jQuery('#wait_btn_signup_step_6').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error','Please try again later.');
                                    return false;
                				}else if(arrTotal[1] == '3'){
                                    setTimeout(function(){
                                        jQuery('#btn_signup_step_6').show();
                                        jQuery('#wait_btn_signup_step_6').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error','User with same company name already exists.'); 
                                    return false;
                				}else{
                					setTimeout(function(){
                                    	jQuery('#btn_signup_step_6').show();
                                    	jQuery('#wait_btn_signup_step_6').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                	jQuery('#btn_signup_step_6').show();
                                	jQuery('#wait_btn_signup_step_6').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep6');        
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

function funAjaxPostNewSignUpStep5VerifyLater(obj){
	jQuery('#btn_signup_step_5_verify_later').hide();
	jQuery('#wait_btn_signup_step_5_verify_later').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
        setTimeout(function(){
            jQuery('#btn_signup_step_5_verify_later').show();
            jQuery('#wait_btn_signup_step_5_verify_later').hide();
        }, 4000);		
		return false;
	}  else {
        var company_name = $('#company_name').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>login/fn_check_company_name_exists',
            data: 'company_name='+company_name,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    if(arrTotal[0] == 'company_exists'){
                        setTimeout(function(){
                            jQuery('#btn_signup_step_5_verify_later').show();
                            jQuery('#wait_btn_signup_step_5_verify_later').hide();
                        }, 4000);
                        displayToastNotificationInAjax('error','User with same company name already exists.'); 
                        return false;    
                    }else{
                        var subform = jQuery('#'+formid).serialize();
                		$.ajax({
                			type: 'POST',
                			url: '<?php echo $this->config->item("site_url");?>login/fn_insert_new_signup_step_5_verify_later',
                			data:  new FormData(obj),
                			contentType: false,
                			cache: false,
                			processData:false,
                			beforeSend:function(){ },                    
                			success:function(data){
                				var arrTotal = data.split('^^');
                				if(arrTotal[1] == '1'){
                                    setTimeout(function(){
                                        jQuery('#btn_signup_step_5_verify_later').show();
                                        jQuery('#wait_btn_signup_step_5_verify_later').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    setTimeout(function(){ 
                                        window.location.href = arrTotal[0]; 
                                    }, 1000);                    
                        			return false;					
                				}else if(arrTotal[1] == '2'){
                                    jQuery('#btn_signup_step_5_verify_later').show();
                                    jQuery('#wait_btn_signup_step_5_verify_later').hide();
                                    displayToastNotificationInAjax('error','Please try again later.');
                                    return false;
                				}else{
                					setTimeout(function(){
                                        jQuery('#btn_signup_step_5_verify_later').show();
                                        jQuery('#wait_btn_signup_step_5_verify_later').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                	jQuery('#btn_signup_step_5_verify_later').show();
                                	jQuery('#wait_btn_signup_step_5_verify_later').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostNewSignUpStep5VerifyLater');        
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

function funAjaxPostSignupApplyForManualVerificationAgencyCompanyDetail(obj){
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
    var subform = jQuery('#'+formid).serialize();
    $.ajax({
    	type: 'POST',
    	//url: obj.action,
        url: '<?php echo $this->config->item("site_url");?>login/fn_signup_apply_for_manual_verification_agency_company_detail',
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

</script>