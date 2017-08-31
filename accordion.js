jQuery(document).ready(function(){
	jQuery('.acc_op_content_ul').hide();
	jQuery('.acc_options_ul .acc_op_content_ul:first').slideDown();
	jQuery('.acc_options_title:first').toggleClass("acc_options_title_expanded");
	
	jQuery('h1.acc_options_title').click(function(){
		if(jQuery(this).next().css("display")!="block"){
			jQuery('.acc_op_content_ul').slideUp(200);
			jQuery(this).next().slideDown(200);
		}
		
		jQuery(".acc_options h1").removeClass();
		jQuery(".acc_options h1").addClass("acc_options_title");
		
		jQuery(this).removeClass();
		jQuery(this).addClass("acc_options_title_expanded");
	})
	
})