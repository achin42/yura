<script>
jQuery(document).ready(function($){
    
    $('#frm_client_company_setup').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var client_company_setup_obj = document.getElementById("frm_client_company_setup");
            funAjaxPostClientCompanySetup(client_company_setup_obj);
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

function get_dial_code_selection(country_code_name){
    var area_code = $('#area_code').val();
    if(area_code == ''){
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->config->item("site_url");?>user/fn_get_dial_code_selection',
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

function funAjaxPostClientCompanySetup(obj){
	jQuery('#btn_client_company_setup').hide();
	jQuery('#wait_btn_client_company_setup').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_client_company_setup').show();
        jQuery('#wait_btn_client_company_setup').hide();
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
        					jQuery('#btn_client_company_setup').show();
        					jQuery('#wait_btn_client_company_setup').hide();
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
                                    	jQuery('#btn_client_company_setup').show();
                                    	jQuery('#wait_btn_client_company_setup').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    window.location.href = arrTotal[0]; 
                                    return false;					
                				}else if(arrTotal[1] == '2'){
                                    setTimeout(function(){
                                    	jQuery('#btn_client_company_setup').show();
                                    	jQuery('#wait_btn_client_company_setup').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error','Please try again later.');
                                    return false;
                				}else{
                					setTimeout(function(){
                                    	jQuery('#btn_client_company_setup').show();
                                    	jQuery('#wait_btn_client_company_setup').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                	jQuery('#btn_client_company_setup').show();
                                	jQuery('#wait_btn_client_company_setup').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostClientCompanySetup');        
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