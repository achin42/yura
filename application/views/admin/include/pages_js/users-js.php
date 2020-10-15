<script type="text/javascript">
$( document ).ready(function(){
	fun_search_users(0);
    
    $('.multiple_open_side_div_overlay').on('click', function() {
        $('#multiple_side_div_panel').css('width','0px');
        $('#multiple_side_div_panel').css('left','-40%');
        $('.multiple_open_side_div_overlay').hide();           
    });
    
    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            fun_search_users();
            return false;
        }
    });
        
});

function fun_search_users(page_num) {
    page_num = page_num?page_num:0;
    var search_users_keyword = $('#search_users_keyword').val();
    var search_users_sk = $('#search_users_sk').val();
	var sort_by = $('#sort_by').val();
	var sort_order = $('#sort_order').val();
	var page_limit = $('#page_limit').val();
    var filter_agency = $('#filter_agency').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->config->item("site_url"); ?>admin/fn_get_all_users_ajax_pagination_data/'+page_num,
        data:'filter_agency='+filter_agency+'&page='+page_num+'&search_users_keyword='+search_users_keyword+'&search_users_sk='+search_users_sk+'&sort_by='+sort_by+'&sort_order='+sort_order+'&page_limit='+page_limit,
        beforeSend: function () {
            $('.loading').show();
			$('#users_wrapper').hide();
			$('#users_wrapper').html('');
        },
        success: function (html) {
            $('.loading').hide();
            $('#users_wrapper').html(html);
			$('#users_wrapper').show();
            $('[data-toggle="kt-tooltip"]').each(function() {
                initTooltipAjax($(this));
            });
        }
    });
}

function fun_sort_by(page_num,fieldname,order) {
	page_num = page_num?page_num:0;
	frmobj=window.document.frm_users_list;
	frmobj.sort_by.value=fieldname;
	frmobj.sort_order.value=order;
    fun_search_users(page_num);
}

function fn_approve_agency_company(company_sk){
    swal({
        title: "Confirmation Required",
        text: "Are you sure you want to approve?",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-default btn-sm",
        confirmButtonClass: "btn-danger btn-sm",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
    function(){
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('site_url'); ?>admin/fn_approve_agency_company",
            data: "company_sk=" + company_sk,
            success: function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    displayToastNotificationInAjax('success','Agency has been approved successfully!');
                    fun_search_users(0);
                }else{
                    swal({
                        type: "error",
                        title: "Error",
                        text: data,
                        position: "top-right",
                        showConfirmButton: !1,
                        timer: 2000
                    });
                }
            }
        });
    });
}

function fn_reject_agency_company(company_sk){
    swal({
        title: "Confirmation Required",
        text: "Are you sure you want to reject?",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: "btn-default btn-sm",
        confirmButtonClass: "btn-danger btn-sm",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
    function(){
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('site_url'); ?>admin/fn_open_reject_agency_company_side_div_panel",
            data: "company_sk="+company_sk,
            beforeSend:function(){ },                    
            success:function(data){
                var arrTotal = data.split('^^');
                if(arrTotal[1] == '1'){
                    $('#side_div_panel_title').html(arrTotal[0]);
                    $('#side_div_panel_wrapper_html').html(arrTotal[2]);
                    $('#side_div_panel').css('width','40%');
                    $('#side_div_panel').css('left','0');
                    $('.open_side_div_overlay').show();
                    $("select").select2({ allowClear: true});
                    $("select").css("width","100%");
                }            
            }   
        });    
    });
}

function funAjaxPostUpdateAgency(obj){        
    var formid = obj.name;
    var formTag = obj;
    var form = $('#'+formid);
    var url = form.attr('action');
    if ($('#'+formid).validator('validate').has('.has-error').length) {
        //$('#apply-validation-color').addClass('apply-validation-color');
        //$('#apply-validation-color-to-business-card').addClass('apply-validation-color');                          
    } else {
        jQuery('#update_agency').hide();
        jQuery('#wait_update_agency').show();
        
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
                    jQuery('#update_agency').show();
                    jQuery('#wait_update_agency').hide();
                    $('#side_div_panel').css('width','0px');
                    $('#side_div_panel').css('left','-40%');
                    $('.open_side_div_overlay').hide();
                    fun_search_users(0);                    
                }else{
                    swal({
                        type: "error",
                        title: "Error",
                        text: data,
                        position: "top-right",
                        showConfirmButton: !1,
                        timer: 2000
                    });
                    jQuery('#update_agency').show();
                    jQuery('#wait_update_agency').hide();
                }
            },
			error:function(jqXHR, exception){                				
                setTimeout(function(){
                	jQuery('#update_agency').show();
                	jQuery('#wait_update_agency').hide();
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

function fn_filter_agency(filter_agency){
	$('#filter_agency').val(filter_agency);
	fun_search_users(0);
}

</script>