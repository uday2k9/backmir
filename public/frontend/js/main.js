// JavaScript Document

//for input group focus
jQuery(document).ready(function() {
	new WOW().init();
    jQuery(".input-group > input").focus(function(e){
        $(this).parent().addClass("input-group-focus");
    }).blur(function(e){
        jQuery(this).parent().removeClass("input-group-focus");
    });
    
    
     setInterval(function(){ 
		 
		 if (jQuery("#member_form, #member_login").length>0) {
			window.location.reload(true);
		 }
		 
		 }, 600000);
     
     
       function refreshToken() {
            jQuery.get('refresh-token').done(function(data){
                jQuery('input._token').val(data);
            });
        };

        setInterval(refreshToken, 60000); // Each minute, edit milliseconds as you prefer.
});