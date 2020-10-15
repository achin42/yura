<script>
jQuery(document).ready(function($){
    
    $('#frm_change_company_name').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var frm_change_company_name_obj = document.getElementById("frm_change_company_name");
            funAjaxPostChangeCompanyName(frm_change_company_name_obj);
            return false;
        }
    });
    
});

function funAjaxPostChangeCompanyName(obj){
    jQuery('#btn_change_company_name').hide();
	jQuery('#wait_btn_change_company_name').show();
    var formid = $(obj).attr('id');
    var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_change_company_name').show();
        jQuery('#wait_btn_change_company_name').hide();
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
                    	jQuery('#btn_change_company_name').show();
                    	jQuery('#wait_btn_change_company_name').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Company name has been changed successfully.');
                    return false;					
				}else if(arrTotal[1] == '2'){
                    setTimeout(function(){
                    	jQuery('#btn_change_company_name').show();
                    	jQuery('#wait_btn_change_company_name').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error','This company name does not exist.');
                    return false;
				}else{
					setTimeout(function(){
                    	jQuery('#btn_change_company_name').show();
                    	jQuery('#wait_btn_change_company_name').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#btn_change_company_name').show();
                	jQuery('#wait_btn_change_company_name').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostChangeCompanyName');        
                }
                return false;
			}
		});
	}
} 

</script>