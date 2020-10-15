<?php 
	if( isset($_REQUEST['success']) && strlen($_REQUEST['success'])>0 )
	{
		$title = 'Success';
		$type = 'success';
		$text = $_REQUEST['success'];
	}
	if( isset($_REQUEST['errors']) && strlen($_REQUEST['errors'])>0 )
	{
		$title = 'Error';
		$type = 'error';
		$text = strip_tags(preg_replace('/\s\s+/', ' ',$_REQUEST['errors']));
	}
	if( (isset($_REQUEST['success']) && strlen($_REQUEST['success'])>0) ||
		(isset($_REQUEST['errors']) && strlen($_REQUEST['errors'])>0) ){
?>
<script type="text/javascript">
$(document).ready(function () { 
    toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "4000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};
    
    var shortCutFunction = "<?php echo $type;?>";//info,warning,error
    var msg = "<?php echo $text;?>";
    var title = "" || '';
    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
}); 
</script>
<?php }?>
<script type="text/javascript">
//start document ready
$(document).ready(function () {
  
    if($(window).width() > 768){
        $('.expand-nav-icon').click(function(){
            $('body').toggleClass('expand-nav');
        });
    }

    $('[data-toggle="kt-tooltip"]').each(function() {
        initTooltipAjax($(this));
    });
    
    $('.open_side_div_overlay').on('click', function() {
		$('#side_div_panel').css('width','0px');
        $('#side_div_panel').css('left','-40%');
        $('.open_side_div_overlay').hide();           
    });
    
    $('.quick_panel_open_side_div_overlay').on('click', function() {
		$('#quick_panel_side_div_panel').css('width','0px');
        $('#quick_panel_side_div_panel').css('left','-40%');
        $('.quick_panel_open_side_div_overlay').hide();           
    });
    
    var window_height = $( window ).height();
    var scroll_window_height = window_height - 120;
    $('.side_div_panel_body_scroll').css('max-height', scroll_window_height+'px'); //set max height

    $(window).on('resize', function(){
        var window_height = $(this).height();
        var scroll_window_height = window_height - 120;
        $('.side_div_panel_body_scroll').css('max-height', scroll_window_height+'px'); //set max height
    });
                         
    $("select:not('.notselect2')").select2({ allowClear: true});
    $("select:not('.notselect2')").css("width","100%");
    $(".multiselect:not('.notselect2')").select2({ allowClear: true,minimumResultsForSearch: -1});
    $(".multiselect:not('.notselect2')").css("width","100%");
    $(".dateinput").datepicker({
        sideBySide: true,
        format: "mm/dd/yyyy",
        showClose: true
    });
    /*if( jQuery('.daterangeinput').length ){ 
        $(".daterangeinput").datetimepicker({
            sideBySide: true,
            //format: "MM/DD/YYYY HH:mm",
            format: "mm/dd/yyyy hh:ii",
            showClose: true
        });
    }*/
});
//end document ready

function initTooltipAjax(el){
    var skin = el.data('skin') ? 'tooltip-' + el.data('skin') : '';
    var width = el.data('width') == 'auto' ? 'tooltop-auto-width' : '';
    var triggerValue = el.data('trigger') ? el.data('trigger') : 'hover';
    var placement = el.data('placement') ? el.data('placement') : 'left';

    el.tooltip({
        trigger: triggerValue,
        template: '<div class="tooltip ' + skin + ' ' + width + '" role="tooltip">\
            <div class="arrow"></div>\
            <div class="tooltip-inner"></div>\
        </div>'
    });
}

function closeSideDivPanel() {
	$('#side_div_panel').css('width','0px');
	$('#side_div_panel').css('left','-40%');
    $('.open_side_div_overlay').hide();           
}


function closeQuickPanelSideDivPanel() {
	$('#quick_panel_side_div_panel').css('width','0px');
    $('#quick_panel_side_div_panel').css('left','-40%');
    $('.quick_panel_open_side_div_overlay').hide();           
}

function displayToastNotificationInAjax(msg_type,msg_title){
	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "4000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};

    var shortCutFunction = msg_type;//success,info,warning,error
    var msg = msg_title;
    var title = "" || '';
    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
}

equalheight = function (container) {                   
	if (jQuery(window).width() > 700) {

		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = new Array(),
			$el,
			topPosition = 0;
		jQuery(container).each(function () {

			$el = jQuery(this);
			jQuery($el).height('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
					rowDivs[currentDiv].innerHeight(currentTallest);
                }
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.innerHeight();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.innerHeight()) ? ($el.innerHeight()) : (currentTallest);
			}
			for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
				rowDivs[currentDiv].innerHeight(currentTallest);
			}
		});

	} else {
		jQuery(container).height('auto')
	}

}


function openQuickPanelSideDivPanel(addwidth) {
    $('#quick_panel_side_div_panel').css('width',addwidth+'%');
    $('#quick_panel_side_div_panel').css('left','0');
    $('.quick_panel_open_side_div_overlay').show();
}

function openAddUpdateUserEditProfileSideDivPanel(addwidth,sess_users_sk) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>users/open_add_update_user_edit_profile_side_div_panel",
        data: "sess_users_sk="+sess_users_sk,
        beforeSend:function(){ },                    
        success:function(data){
            var arrTotal = data.split('^^');
            if(arrTotal[1] == '1'){
                $('#side_div_panel_title').html(arrTotal[0]);
                $('#side_div_panel_wrapper_html').html(arrTotal[2]);
                $('#side_div_panel').css('width',addwidth+'%');
                $('#side_div_panel').css('left','0');
                $('.open_side_div_overlay').show();
                $("select").select2({ allowClear: true});
                $("select").css("width","100%");
                   
                $("#profile_image").on("change",function() {
                    readUserURL(this);
                });
                
            }            
        }   
    });
}

function readUserURL(input) {
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#user_preview_image").attr("src", e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function funAjaxPostAddUpdateUser(obj){        
    var formid = obj.name;
    var formTag = obj;
    var form = $('#'+formid);
    var url = form.attr('action');
    if ($('#'+formid).validator('validate').has('.has-error').length) {
        //$('#apply-validation-color').addClass('apply-validation-color');
        //$('#apply-validation-color-to-business-card').addClass('apply-validation-color');                          
    } else {
        jQuery('#add_update_user').hide();
        jQuery('#wait_add_update_user').show();
        
        $.ajax({
            type: "POST",
            url: url,
            //data: form.serialize(), // serializes the form's elements.
            data:  new FormData(obj),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend:function(){ },
            success:function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    displayToastNotificationInAjax('success','Information Saved!');
                    jQuery('#add_update_user').show();
                    jQuery('#wait_add_update_user').hide();
                    $('#side_div_panel').css('width','0px');
                    $('#side_div_panel').css('left','-40%');
                    $('.open_side_div_overlay').hide();
                    $('#display_user_profile_image').attr('src',arrTotal[0]);
                }else{
                    swal({
                        type: "error",
                        title: "Error",
                        text: data,
                        position: "top-right",
                        showConfirmButton: !1,
                        timer: 2000
                    });
                    jQuery('#add_update_user').show();
                    jQuery('#wait_add_update_user').hide();
                }
            },
			error:function(jqXHR, exception){                				
                setTimeout(function(){
                	jQuery('#add_update_user').show();
                	jQuery('#wait_add_update_user').hide();
                }, 4000);
                displayToastNotificationInAjax('error','An unexpected error has occurred. Please try later.');
                var err_msg = fn_get_ajax_error_handling_messages(jqXHR, exception);
                if(err_msg != ''){
                    fn_send_ajax_error_handling_mail(jqXHR.status, jqXHR.responseText, exception, err_msg, 'funAjaxPostAddUpdateUser');        
                }
                return false;
			}  
        });                          
    }
}

function fn_get_ajax_error_handling_messages(jqXHR, exception){
    var err_msg = '';
    if (jqXHR.status === 0) {
        err_msg = 'An unexpected error has occurred. Please verify your network connection.';
    } else if (jqXHR.status == 404) {
        err_msg = 'Requested page not found. Please try again.';
    } else if (jqXHR.status == 500) {
        err_msg = 'Internal server error. Please try later.';
    } else if (exception === 'parsererror') {
        err_msg = 'An unexpected error has occurred. JSON parsing failed.';
    } else if (exception === 'timeout') {
        err_msg = 'An unexpected error has occurred. Request timed out.';
    } else if (exception === 'abort') {
        err_msg = 'An unexpected error has occurred. Ajax request aborted.';
    } else {
        err_msg = 'An unexpected error has occurred. Uncaught Error. ' + jqXHR.responseText;
    }
    return err_msg;
}

function fn_send_ajax_error_handling_mail(jqxhr_status, jqxhr_responsetext, exception, err_msg, function_name){
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url");?>welcome/fn_send_ajax_error_handling_mail',
        data: 'jqxhr_status='+jqxhr_status+'&jqxhr_responsetext='+jqxhr_responsetext+'&exception='+exception+'&err_msg='+err_msg+'&function_name='+function_name,
        success: function(data){
                                                 
        }
    });    
}

function fn_submit_login(){
    jQuery('#btn_admin_sign_in').hide();
    jQuery('#wait_btn_admin_sign_in').show();
    jQuery('#frm_admin_login').submit();
}

</script>