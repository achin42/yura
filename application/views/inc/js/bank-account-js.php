<script>
jQuery(document).ready(function($){
	
	showpaymentmethods();	             
});

function showpaymentmethods() {
	$.ajax({
		type: 'GET',
		url: site_url+"user/fn_get_bank_account_ajax",
		contentType: false,
		cache: false,
		processData:false,
		beforeSend:function(){ },                    
		success:function(data){
            var arrTotal = data.split('^^');
			jQuery("#loadingdata").hide();
			jQuery("#paymentmethods").html(arrTotal[0]);
			if(arrTotal[2] > '0'){
                jQuery("#show_add_payment_btn").removeClass('display-hide-imp');
                jQuery("#show_add_payment_btn").show();
                jQuery("#addpaymentblock").hide();
                jQuery(".main-content").removeClass('d-flex');
                jQuery(".main-content").removeClass('align-items-center');
                jQuery("#listing_div").addClass('pl-0');
            }else{
                jQuery("#show_add_payment_btn").addClass('display-hide-imp');
                jQuery("#paymentmethods").addClass('empty-account-box');
                jQuery("#addpaymentblock").css('display','table');
                jQuery(".main-content").addClass('d-flex');
                jQuery(".main-content").addClass('align-items-center');
                jQuery("#listing_div").removeClass('pl-0');
            }
			jQuery(".paymentid").click(function() {
                
		        if($(this).is(":checked")) {
		        	payment_id = $(this).val();
		        	

		            $.ajax({
						type: 'GET',
						url: site_url + "user/fn_set_active_bank_acccount",
						data:  "bank_sk="+payment_id,
						contentType: false,
						cache: false,
						processData:false,
						                
						success:function(data){
							var arrTotal = data.split('^^');
							if(arrTotal[1] == '1'){                
				                displayToastNotificationInAjax('success','Your bank account updated to active successfully.');
				                showpaymentmethods();
				    			return false;					
							}else{
				                displayToastNotificationInAjax('error',data);
				                return false;
							}
						},
						error:function(jqXHR, exception){							
                            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                            if(err_msg != ''){
                                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'showpaymentmethods');        
                            }
                            return false;
						}
					});
		        }
		    });
		}
	});
}

function showaddpaymentblock() {
	jQuery("#addpaymentblock").show();
}

function deletepayment(payment_id) {

	var formid = $("#frmpaymentmethoddel");

	$("#bank_sk").val(payment_id);
	$.ajax({
		type: 'GET',
		url: site_url + "user/fn_delete_bank_account",
		data:  "bank_sk="+payment_id,
		contentType: false,
		cache: false,
		processData:false,
		                
		success:function(data){
			var arrTotal = data.split('^^');
			if(arrTotal[1] == '1'){                
                displayToastNotificationInAjax('success','Your bank account deleted successfully.');
                showpaymentmethods();
    			return false;					
			}else{
                displayToastNotificationInAjax('error',data);
                return false;
			}
		},
		error:function(jqXHR, exception){			
            displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
            var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
            if(err_msg != ''){
                fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'deletepayment');        
            }
            return false;
		}
	});
}
function funAjaxPostSavePayment(obj){
	//jQuery('#insert_payment_btn').hide();
	//jQuery('#wait_insert_payment_btn').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	

	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}


    //popError = false;
	if(popError === true){
		jQuery('#insert_payment_btn').show();
        jQuery('#wait_insert_payment_btn').hide();
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
			                
			success:function(data){
				var arrTotal = data.split('^^');
				if(arrTotal[1] == '1'){
					jQuery("#account_number").val('');
					jQuery("#bank_name").val('');
					jQuery("#account_name").val('');
					
                    jQuery('#insert_payment_btn').show();
                    jQuery('#wait_insert_payment_btn').hide();
                    displayToastNotificationInAjax('success','Your bank account added successfully.');
                    jQuery("#paymentmethods").removeClass("empty-account-box");
                    showpaymentmethods();

        			return false;					
				}else{
					jQuery('#insert_payment_btn').show();
                    jQuery('#wait_insert_payment_btn').hide();
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostSavePayment');        
                }
                return false;
			}
		});
	}
} 



</script>