<script>
jQuery(document).ready(function($){
    
    $('#frm_profile_detail').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var profile_detail_obj = document.getElementById("frm_profile_detail");
            funAjaxPostProfileDetail(profile_detail_obj);
            return false;
        }
    });
    
    $('#frm_change_password').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var change_password_obj = document.getElementById("frm_change_password");
            funAjaxPostChangePassword(change_password_obj);
            return false;
        }
    });
    
    $(".toggle-password-profile").click(function() {
        $(this).toggleClass("fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
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
                                    
});

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

function funAjaxPostProfileDetail(obj){
	jQuery('#btn_profile_detail').hide();
	jQuery('#wait_btn_profile_detail').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_profile_detail').show();
        jQuery('#wait_btn_profile_detail').hide();
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
                    	jQuery('#btn_profile_detail').show();
                    	jQuery('#wait_btn_profile_detail').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    setTimeout(function(){ 
                        window.location.reload(); 
                    }, 1500);
        			return false;					
				}else{
					setTimeout(function(){
                    	jQuery('#btn_profile_detail').show();
                    	jQuery('#wait_btn_profile_detail').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#btn_profile_detail').show();
                	jQuery('#wait_btn_profile_detail').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostProfileDetail');        
                }
                return false;
			}
		});
	}
} 

function funAjaxPostChangePassword(obj){
	jQuery('#btn_change_password').hide();
	jQuery('#wait_btn_change_password').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_change_password').show();
        jQuery('#wait_btn_change_password').hide();
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
                    	jQuery('#btn_change_password').show();
                    	jQuery('#wait_btn_change_password').hide();
                    }, 4000);
                    displayToastNotificationInAjax('success','Information Saved!');
                    setTimeout(function(){ 
                        window.location.reload(); 
                    }, 1500);
        			return false;					
				}else{
					setTimeout(function(){
                    	jQuery('#btn_change_password').show();
                    	jQuery('#wait_btn_change_password').hide();
                    }, 4000);
                    displayToastNotificationInAjax('error',data);
                    return false;
				}
			},
			error:function(jqXHR, exception){
                setTimeout(function(){
                	jQuery('#btn_change_password').show();
                	jQuery('#wait_btn_change_password').hide();
                }, 4000);				
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostChangePassword');        
                }
                return false;
			}
		});
	}
}
</script>