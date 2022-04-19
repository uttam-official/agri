
jQuery(document).ready(function(){	
	onloadmethod();	
		
	/*Menu*/	
	jQuery(".gn-icon-menu").click(function(e) {
		e.stopPropagation();
	  jQuery(this).toggleClass("on");  
	});	
	jQuery('#menu').click(function(e) {	
		e.stopPropagation();
		if (jQuery('#slidingMenu').hasClass('open')){		
			jQuery('#slidingMenu').removeClass('open');
			jQuery('#slidingMenu').animate({
				right: '-245px'
			}, 500); 
		} else {
			jQuery('#slidingMenu').addClass('open');
			jQuery('#slidingMenu').animate({
				right: '0px'
			}, 500);
		  }
	});
	

	

});



jQuery(window).resize(function(){	
	onloadmethod();	
});

function onloadmethod(){	


}
