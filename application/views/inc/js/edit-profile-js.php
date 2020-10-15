<script>
jQuery(document).ready(function($){
    
    $('#frm_edit_user_profile').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var edit_profile_obj = document.getElementById("frm_edit_user_profile");
            funAjaxPostEditProfile(edit_profile_obj);
            return false;
        }
    });
    
    $('#frm_edit_company_profile').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var edit_company_profile_obj = document.getElementById("frm_edit_company_profile");
            funAjaxPostCompanyProfile(edit_company_profile_obj);
            return false;
        }
    });
                 
});

function funAjaxPostEditProfile(obj){
	jQuery('#edit_user_profile_btn').hide();
	jQuery('#wait_edit_user_profile_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#edit_user_profile_btn').show();
        jQuery('#wait_edit_user_profile_btn').hide();
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
                    	jQuery('#edit_user_profile_btn').show();
                    	jQuery('#wait_edit_user_profile_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
        			return false;					
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#edit_user_profile_btn').show();
                    	jQuery('#wait_edit_user_profile_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','This email address already exists. Please enter any other email address.');
                    return false;
				}else{
					setTimeout(function(){
                    	jQuery('#edit_user_profile_btn').show();
                    	jQuery('#wait_edit_user_profile_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){			
                setTimeout(function(){
                	jQuery('#edit_user_profile_btn').show();
                	jQuery('#wait_edit_user_profile_btn').hide();
                }, 4000);	
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostEditProfile');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostCompanyProfile(obj){
	jQuery('#edit_company_profile_btn').hide();
	jQuery('#wait_edit_company_profile_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#edit_company_profile_btn').show();
        jQuery('#wait_edit_company_profile_btn').hide();
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
                    	jQuery('#edit_company_profile_btn').show();
                    	jQuery('#wait_edit_company_profile_btn').hide();
                    }, 4000);
        			displayToastNotificationInAjax('success','Information Saved!');
                    return false;					
				}else{
					setTimeout(function(){
                    	jQuery('#edit_company_profile_btn').show();
                    	jQuery('#wait_edit_company_profile_btn').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#edit_company_profile_btn').show();
                	jQuery('#wait_edit_company_profile_btn').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostCompanyProfile');        
                }
                return false;
			}
		});
	}
}

</script>