<script>
jQuery(document).ready(function($){
    
    $('#frm_delete_user').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var frm_delete_user_obj = document.getElementById("frm_delete_user");
            funAjaxPostDeleteUser(frm_delete_user_obj);
            return false;
        }
    });
    
});

function submitEntrIconClick(){
    var frm_delete_user_obj = document.getElementById("frm_delete_user");
    funAjaxPostDeleteUser(frm_delete_user_obj);
    return false;
}

function funAjaxPostDeleteUser(obj){
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
                    displayToastNotificationInAjax('success','User has been deleted successfully.');
                    return false;					
				}else if(arrTotal[1] == '2'){
                    /*jQuery('#signup_btn').show();
                    jQuery('#wait_signup_btn').hide();*/
                    displayToastNotificationInAjax('error','This email address does not exist.');
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
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostDeleteUser');        
                }
                return false;
			}
		});
	}
} 

</script>