<script>
jQuery(document).ready(function($){
    
    var uri_segment = '<?php echo $this->uri->segment(2);?>';
    if(uri_segment == 'verify'){
        //$('#verify_company').val('yes');
        $('#btn_verify_agency_company_detail').show();
        $('#btn_agency_company_detail').hide();
    }        
            
    $('#company_name').keyup(function(e) {
        var old_company_name = $('#old_company_name').val();
        var old_country_code = $('#old_country_code').val();
        var old_country_name = $('#old_country_name').val();
        var full_country_name = old_country_code+'###'+old_country_name;
        var company_name = $(this).val();
        var country = $('#country_code_name').val();
        //console.log(old_company_name+'@@'+company_name+'@@'+full_country_name+'@@'+country)
        if(old_company_name != company_name || full_country_name != country){
            $('#verify_company').val('yes');
            $('#btn_verify_agency_company_detail').show();
            $('#btn_agency_company_detail').hide();
        }else{
            $('#verify_company').val('no');
            $('#btn_agency_company_detail').show();
            $('#btn_verify_agency_company_detail').hide();
        }
    });
                
    $('#frm_agency_company_detail').keydown(function(e) {
        var verify_company = $('#verify_company').val();
        if(verify_company == 'yes'){
            $('#btn_verify_agency_company_detail').show();
            $('#btn_agency_company_detail').hide();
        }else{
            $('#btn_agency_company_detail').show();
            $('#btn_verify_agency_company_detail').hide();
        }            
    });
    
    $('#frm_agency_company_detail').keypress(function(e) {
        var key = e.which;
        if (key == 13) {
            var agency_company_detail_obj = document.getElementById("frm_agency_company_detail");
            var verify_company = $('#verify_company').val();
            if(verify_company == 'yes'){
                funAjaxPostVerifyAgencyCompanyDetail(agency_company_detail_obj);
                return false;
            }else{
                funAjaxPostAgencyCompanyDetail(agency_company_detail_obj);
                return false;
            }                
        }
    });
    
    $("#company_domain,#area_code,#company_logo_image").on("change",function() {
        $('#btn_agency_company_detail').show();
        $('#btn_verify_agency_company_detail').hide();
    });
    
    $("#country_code_name").on("change",function() {
        var old_company_name = $('#old_company_name').val();
        var old_country_code = $('#old_country_code').val();
        var old_country_name = $('#old_country_name').val();
        var full_country_name = old_country_code+'###'+old_country_name;
        var company_name = $('#company_name').val();
        var country = $(this).val();
        //console.log(old_company_name+'@@'+company_name+'@@'+full_country_name+'@@'+country)
        if(old_company_name != company_name || full_country_name != country){
            $('#verify_company').val('yes');
            $('#btn_verify_agency_company_detail').show();
            $('#btn_agency_company_detail').hide();
        }else{
            $('#verify_company').val('no');
            $('#btn_agency_company_detail').show();
            $('#btn_verify_agency_company_detail').hide();
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

function funAjaxPostVerifyAgencyCompanyDetail(obj){
	jQuery('#btn_verify_agency_company_detail').hide();
	jQuery('#wait_btn_verify_agency_company_detail').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_verify_agency_company_detail').show();
        jQuery('#wait_btn_verify_agency_company_detail').hide();
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
        					jQuery('#btn_verify_agency_company_detail').show();
        					jQuery('#wait_btn_verify_agency_company_detail').hide();
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
                			url: '<?php echo $this->config->item("site_url");?>user/fn_update_verify_agency_company_detail',
                			data:  new FormData(obj),
                			contentType: false,
                			cache: false,
                			processData:false,
                			beforeSend:function(){ },                    
                			success:function(data){
                				var arrTotal = data.split('^^');
                				if(arrTotal[1] == '1'){
                                    setTimeout(function(){
                                    	jQuery('#btn_verify_agency_company_detail').show();
                                    	jQuery('#wait_btn_verify_agency_company_detail').hide();
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
                                    	jQuery('#btn_verify_agency_company_detail').show();
                                    	jQuery('#wait_btn_verify_agency_company_detail').hide();
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
                                        var apply_for_manual_verification_obj = document.getElementById("frm_agency_company_detail");
                                        funAjaxPostApplyForManualVerificationAgencyCompanyDetail(apply_for_manual_verification_obj);
                                        return false;
                                    });
                                        
                                    return false;
                				}else{
                					setTimeout(function(){
                                    	jQuery('#btn_verify_agency_company_detail').show();
                                    	jQuery('#wait_btn_verify_agency_company_detail').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){                				
                                setTimeout(function(){
                                	jQuery('#btn_verify_agency_company_detail').show();
                                	jQuery('#wait_btn_verify_agency_company_detail').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostVerifyAgencyCompanyDetail');        
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

function funAjaxPostAgencyCompanyDetail(obj){
	jQuery('#btn_agency_company_detail').hide();
	jQuery('#wait_btn_agency_company_detail').show();
	var formid = $(obj).attr('id');
	var formTag = obj;
	var popError = false;
	var data = {};
	
	$('form#'+formid).validator('validate');
	if(jQuery('form#'+formid).find('.has-error').length > 0){popError = true;}
    //popError = false;
	if(popError === true){
		jQuery('#btn_agency_company_detail').show();
        jQuery('#wait_btn_agency_company_detail').hide();
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
        					jQuery('#btn_agency_company_detail').show();
        					jQuery('#wait_btn_agency_company_detail').hide();
        				}, 4000);
        				displayToastNotificationInAjax('error','User with same company name already exists.'); 
        				return false;    
        			}else{
                        /*$('#varify_popup').modal('show');
                        $('#varify_popup').addClass('show');
                        $('#varify_popup').css('display','block');
                        $('.modal-backdrop').css('display','block');*/
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
                                    	jQuery('#btn_agency_company_detail').show();
                                    	jQuery('#wait_btn_agency_company_detail').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('success','Information Saved!');
                                    //$('#varify_popup').modal('hide');
                                    /*$('#varify_popup').removeClass('show');
                                    $('#varify_popup').css('display','none');
                                    $('.modal-backdrop').css('display','none');
                                    $('#succ_popup').modal('show');*/
                                    setTimeout(function(){ 
                                        window.location.href = arrTotal[0]; 
                                    }, 1500);                    
                        			return false;					
                				}else if(arrTotal[1] == '2'){
                                    setTimeout(function(){
                                    	jQuery('#btn_agency_company_detail').show();
                                    	jQuery('#wait_btn_agency_company_detail').hide();
                                    }, 4000);
                                    /*$('#varify_popup').removeClass('show');
                                    $('#varify_popup').css('display','none');
                                    $('.modal-backdrop').css('display','none');
                                    $('#failed_popup').modal('show');*/
                                    return false;
                				}else{
                					setTimeout(function(){
                                    	jQuery('#btn_agency_company_detail').show();
                                    	jQuery('#wait_btn_agency_company_detail').hide();
                                    }, 4000);
                                    displayToastNotificationInAjax('error',data);
                                    return false;
                				}
                			},
                			error:function(jqXHR, exception){
                				setTimeout(function(){
                                	jQuery('#btn_agency_company_detail').show();
                                	jQuery('#wait_btn_agency_company_detail').hide();
                                }, 4000);
                                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                                if(err_msg != ''){
                                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostAgencyCompanyDetail');        
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