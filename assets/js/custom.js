jQuery(document).ready(function($){
    
    $("html").click(function(){
        $(".custom-propover-content").removeClass('show');
        $('.sb-body').css('overflow-y','auto');
    });

    $('.sign-in-later').click(function(){
		$(".custom-propover-content").removeClass('show');
		$('.sb-body').css('overflow-y','auto');
	});
    
    $(".clk-close-btn").click(function(){
        $(".custom-propover-content").removeClass('show');
        $('.sb-body').css('overflow-y','auto');
    });
    
    $('.new-navbar .member-alert').on('click',function(e){
        $(this).next().addClass('show');
    });
    
    $('.custom-propover-content, .member-alert').click(function(event){
        event.stopPropagation();
    });
    
    /*$('.custom-propover > div > a').on('click',function(){
        if($(this).parent().find('.custom-propover-content').hasClass('show')){
            $('.custom-propover-content').removeClass('show');
            $('.sb-body').css('overflow-y','auto');
	    }else{
            $('.custom-propover-content').removeClass('show');
            $(this).next().addClass('show');
            $('.sb-body').css('overflow-y','visible');
        }
        return false;
    });

    $('.custom-propover-content').click(function(event){
        event.stopPropagation();
    });*/
      
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $('#close').click(function () {
        $('.switch-popup-box').hide();
        return false;
    });

	$('.input-daterange-1 input').each(function() {
        $(this).datepicker();
    });
    // $('#myModal').appendTo("body").modal('show');
});

   
 
