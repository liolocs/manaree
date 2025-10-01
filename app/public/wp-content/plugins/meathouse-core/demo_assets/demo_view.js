(function($) {
  "use strict";
  jQuery(document).ready(function($) {
    $('body').on('click', '.jws_show_demo_action', function() {
         $(this).toggleClass('active');
         $('.jws_demo_word').toggleClass('open');
        
         if($('.jws_demo_word').hasClass('open')) {
             var o = window.innerWidth - document.documentElement.clientWidth;
             $('body').css({
                'padding-right': o,
                'overflow': 'hidden',
            });
         } else {
            $('body').css({
                'padding-right': '',
                'overflow': '',
            });
         }  
        
         if($('.jws_demo_items .demo_item').length < 1) {
            $('.jws_demo_items').addClass('loading');
            $.ajax({
    			data: { 
                    action: 'jws_view_demo_content', 
                },
                url: jws_script.ajax_url,
    			type: 'POST',
              
    		}).success(function(response) {
    		$('.jws_demo_items').removeClass('loading');
            $('.jws_demo_items').html(response);
    	     
    		}).error(function(ex) {
    			console.log(ex);
    		}); 
         } 
    });
    $('body').on('click', '.jws_demo_overlay', function() { 
        $('.jws_demo_word').removeClass('open');
        $('.jws_show_demo_action').removeClass('active');
        $('body').css({
            'padding-right': '',
            'overflow': '',
        });
    }); 
  
  });
})(jQuery);