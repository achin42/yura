<script>
jQuery(document).ready(function($){
	
	showpaymentmethods();	             
});

function showpaymentmethods() {
	$.ajax({
		type: 'GET',
		url: site_url+"user/fn_get_transaction_ajax",
		contentType: false,
		cache: false,
		processData:false,
		beforeSend:function(){ },                    
		success:function(data){
			jQuery("#loadingdata").hide();
			jQuery("#paymentmethods").html(data);
			
		}
	});
}

</script>