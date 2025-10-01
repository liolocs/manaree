(function($) {
	"use strict";
    var $total_points = box_meat_total; 
    var $added_points = 0;
    var $continue = $total_points;
    var $box_id = box_id; 
    var add_to_cart = false;
    
    function meat_add_pro(input) {
        
        var circle = document.querySelector('.progress-ring__circle');
        var radius = circle.r.baseVal.value;
        var circumference = radius * 2 * Math.PI;
        
        circle.style.strokeDasharray = `${circumference} ${circumference}`;
        circle.style.strokeDashoffset = `${circumference}`;
        
        function setProgress(percent) {
          const offset = circumference - percent / 100 * circumference;
          circle.style.strokeDashoffset = offset;
        }
        
   
        if (input < 101 && input > -1) {
            setProgress(input);
        }  
      
        
    }
    

    $(document).ready(function() {
           function builder_box() { 
           if(!$total_points) {
                return false;
           }
           var $meat_added = [],
           $count_edded = {},
           $varition_edded = {},
           $item_points_added = {},
           $box = $('.choosed_item_list');
           meat_add_pro(0); 
            
            
           
            if(typeof edit_cart_data !== 'undefined'){ 
              add_to_cart = true; 
              $('.add_to_checkout').removeClass('diable'); 
              $varition_edded = edit_cart_data.meat_box_variation;
              $count_edded = edit_cart_data.meat_box;
              $.each( $count_edded, function( key, value ) {
                 $meat_added.push(key);
              });
              $item_points_added  = edit_cart_data.item_points_added;
              $added_points = $total_points;
              meat_add_pro(100); 
              $('.point_number').find('.points_added').html($total_points);
              
              
              $.ajax({
                url: jwsThemeModule.jws_script.ajax_url,
                dataType: 'json',
		        method: 'POST',
                data: {
                    old_cart: 'yes',
					action: 'add_section_save',
                    count:$count_edded,
                    varition_edded:$varition_edded,
                    added_points:$added_points,
                    item_points_added:$item_points_added
				},
                success: function (respons2) {},
                error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                    alert(xhr.responseText); // munculkan alert
                }
            });
              
              
           } 
            
               
           $(document).on('click', '.add_to_box', function(event) {
                 event.preventDefault();   
                 var $btn = $(this);   
                  
                 if($btn.hasClass('block_event')) {
                  $.magnificPopup.open({
                  items: {
                    src: '<div class="jws-alert-popup"><i class="fas fa-exclamation-triangle"></i><h5>'+jws_script_box.notification_select_options_title+'</h5>'+jws_script_box.notification_select_options_text+'</div>', // can be a HTML string, jQuery object, or CSS selector
                    type: 'inline'
                  }
                });
                return false;
                 }
                    
                 if(!$btn.find('.loader').length) {
                      $btn.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');  
                 }                    
                 var index = $btn.parents('.product-item').index(); 
                 var id = $btn.attr('product-id');  
                 var variation_id = $btn.attr('data-variation');    
                 var key = id;  
                
            
                if($total_points <= ($added_points) ) {

                    $.magnificPopup.open({
                      items: {
                        src: '<div class="jws-alert-popup"><i class="fas fa-exclamation-triangle"></i><h5>'+jws_script_box.notification_limit_title+'</h5>'+jws_script_box.notification_limit_text+'</div>', // can be a HTML string, jQuery object, or CSS selector
                        type: 'inline'
                      }
                    });   
                    return false;
                 }
             
                 $btn.addClass('loading');
              
                 if(variation_id != undefined) {
                        key = id+'_'+variation_id;
                        $varition_edded[key] = variation_id;  
                }
            
				$.ajax({
					url: jwsThemeModule.jws_script.ajax_url,
					data: {
						action: 'add_item',
						id: id,
                        variation_id: variation_id,
                        count:$count_edded,
                        
					},
					dataType: 'json',
					method: 'POST',
					success: function(response) {
                        if(response.data.status == 'error') {
                             $.magnificPopup.open({
                              items: {
                                src: '<div class="jws-alert-popup"><i class="fas fa-times"></i><h5>Hi!</h5>Error Data</div>', // can be a HTML string, jQuery object, or CSS selector
                                type: 'inline'
                              }
                             });  
                            return false;
                            
                        }
					    if($total_points < $added_points + parseInt(response.data.points)) {
					       if(!$.magnificPopup.instance.isOpen) {
    					        $.magnificPopup.open({
                                  items: {
                                     src: '<div class="jws-alert-popup"><i class="fas fa-exclamation-triangle"></i><h5>'+jws_script_box.notification_limit_title+'</h5>'+jws_script_box.notification_limit_text+'</div>', // can be a HTML string, jQuery object, or CSS selector
                                     type: 'inline'
                                  },
                                  removalDelay: 360,
                               });  
					       }  
					       return false;   
                        }
                        
                        
                        $added_points = $added_points + parseInt(response.data.points);
                        $item_points_added[key] = parseInt(response.data.points)
   
                        if($.inArray(key, $meat_added) < 0) {
                            
                            $box.append(response.data.content); 
                            $meat_added.push(key);
                            $count_edded[key] = 1;
                          
                        }else {
                            if($count_edded[key]>= 1) {
                              $count_edded[key] = parseInt($count_edded[key])+1;   
                            }
                        }
                        $box.find('.item_box[data-product-id="'+key+'"]').find('.count').html($count_edded[key]);
                        $('.point_number').find('.points_added').html($added_points); 
                        meat_add_pro(($added_points / $total_points) * 100); 
                        
                        if($total_points == $added_points) {
                          add_to_cart = true; 
                            $.magnificPopup.open({
                            items: {
                                src: '<div class="jws-alert-popup"><i class="far fa-check-circle"></i><h5>'+jws_script_box.notification_success_title+'</h5>'+jws_script_box.notification_success_text+'</div>', // can be a HTML string, jQuery object, or CSS selector
                                type: 'inline'
                              }
                            }); 
                           $('.add_to_checkout').removeClass('diable'); 
                        }
               
                        $continue = $total_points - $added_points;
                  
                        if($continue != $total_points ) {
                           $('.box-continue').html(''+jws_script_box.remaining_text_left+' <span class="number">'+$continue+'</span> '+jws_script_box.remaining_text_right+''); 
                        }else {
                           $('.box-continue').empty();
                        }
                  
                         $.ajax({
                            url: jwsThemeModule.jws_script.ajax_url,
                            dataType: 'json',
					        method: 'POST',
                            data: {
                                id: key,
        						action: 'add_section_save',
                                count:$count_edded,
                                varition_edded:$varition_edded,
                                added_points:$added_points,
                                item_points_added:$item_points_added
        					},
                            success: function (respons2) {},
                            error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                                alert(xhr.responseText); // munculkan alert
                            }
                        });
                      
					},
					error: function() {
						console.log('We did not find any data.');
					},
					complete: function() {
					    $('.jws_sticky_move').trigger("sticky_kit:recalc");   
    		            $('.col-bulder-right').addClass('active');  
    					$btn.removeClass('loading'); 
					},
				});
    
           });
           
            $(document).on('click', '.item_box .remove-item', function() { 
                
                var $remove = $(this),
                id = $remove.parents('.item_box').attr('data-product-id'),
                item = $remove.parents('.item_box'),
                index = $remove.parents('.item_box').index();
               
                if($.inArray(id, $meat_added) < 0 || $meat_added[index] != id) { ;
                    $.magnificPopup.open({
                        items: {
                            src: '<div class="jws-alert-popup"><i class="fas fa-exclamation-triangle"></i><h5>Hi!</h5>Product Not found!</div>', // can be a HTML string, jQuery object, or CSS selector
                            type: 'inline'
                        }
                   }); 
                   return false;
                }
    
                  $added_points = $added_points - $item_points_added[id] * $count_edded[id];
                  $('.point_number').find('.points_added').html($added_points);

                  $meat_added = $.grep($meat_added, function(value) {
                     return value != id;
                  });
                  
                  delete $count_edded[id];
                  delete $item_points_added[id];
                  delete $varition_edded[id];   
                 item.remove();
                 $continue = $total_points - $added_points;
                 $('.jws_sticky_move').trigger("sticky_kit:recalc");    
                 if($continue != $total_points ) {
                   $('.box-continue').html('Fill your box to continue <span class="number">'+$continue+'</span> points remaining'); 
                }else {
                   $('.box-continue').empty();
                } 
                meat_add_pro(($added_points / $total_points) * 100); 
                if($total_points > $added_points) {
                   add_to_cart = false; 
                   $('.add_to_checkout').addClass('diable'); 
                }
                
                $.ajax({
                    url: jwsThemeModule.jws_script.ajax_url,
                    dataType: 'json',
			        method: 'POST',
                    data: {
						action: 'add_section_save',
                        count:$count_edded,
                        varition_edded:$varition_edded,
                        id: id,
					},
                    success: function (respons) {},
                    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                        alert(xhr.responseText); // munculkan alert
                    }
                });
                    
            });
            
            
               
                $(document.body).on('click', '.add_to_checkout', function(e) { 
                if(add_to_cart) {
                        
                let $thisbutton = $(this);
                let $cart_key = '';
                if(typeof edit_cart_data !== 'undefined'){  
                   $cart_key = $thisbutton.data('cart_key'); 
                }
            
                var data = '';
             
                data += '&action=jws_ajax_add_to_cart';
               
                data += '&add-to-cart=' + $box_id;
                
           
                $thisbutton.addClass('loading');
                if(!$thisbutton.find('.loader').length) {    
                        $thisbutton.append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');    
                }
  
                $.ajax({
                    url: jwsThemeModule.jws_script.ajax_url,
                    dataType: 'json',
			        method: 'GET',
                    data: {
						action: 'remove_cart_old',
                        cart_key:$cart_key,
					},
                    success: function (respons) {
                              $.ajax({
                                    url: jws_script.ajax_url,
                                    data: data,
                                    method: 'POST',
                                    success: function(response) {
                                        if (!response) {
                                            return;
                                        }
                                        $thisbutton.removeClass('loading');
                                        var this_page = window.location.toString();
                                        this_page = this_page.replace('add-to-cart', 'added-to-cart');
                                        if (response.error && response.product_url) {
                                            window.location = this_page;
                                            return;
                                        }
                                        // Redirect to cart option
                                        $('.jws_box_bulder .nav_step #step_payment').click();
                                        window.location = jws_script.checkout_url;
                                        return;
                                       
                                    },
                                    error: function() {
                                        console.log('ajax adding to cart error');
                                    },
                                    complete: function() {},
                                    });
                    },
                    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                        alert(xhr.responseText); // munculkan alert
                    }
                });
                }
                    
                });
           
            
            }
            
            builder_box();
            
            function nav_step() { 
                

               $(document.body).on('click', '.show_box_mobile', function(e) {
            
                   e.preventDefault();
                   $('.col-bulder-right').toggleClass('active'); 
 
               });
               
               $(document.body).on('click', '.show_box_filter_mobile', function(e) {
            
                   e.preventDefault();
                   $('.box_nav').toggleClass('active'); 
                   
               });
               
               $(document.body).on('click', '.overlay , .remove ', function(e) {
            
                   e.preventDefault();
                   $('.col-bulder-right').removeClass('active'); 
                   $('.box_nav').removeClass('active');

               });
               
                
               $(document.body).on('click', '.jws_box_bulder .nav_step a', function(e) {
            
                   e.preventDefault();
                   
                   if($(this).attr('id') == 'step_payment') {
                        return false;
                   }
                   
                   var current_index = $(this).parent().index();
                   var data_id = $(this).data('id');
    
                   $(".content_section").removeClass('active');
                   $("#"+data_id+"").addClass('active');
                   $(window).trigger('resize');
                   
                   
                   $( ".jws_box_bulder .nav_step li" ).each(function( index ) {
                       var item_index = $(this).index(); 
                       if(item_index < current_index) {
                         $(this).find('a').addClass('edited');
                       }else {
                         $(this).find('a').removeClass('edited');
                       }
                   });
                   

                   $('.jws_box_bulder .nav_step a').removeClass('current');
                   $(this).addClass('current').removeClass('edited');
                   
                   
               });     
               
               $(document.body).on('click', '.next_pricing', function(e) {
                    e.preventDefault();
                    $('#step_pricing').click();
               }); 
               
                
            }
            
            nav_step();

          
            function frequency_checkout() { 
                
               $(document.body).on('change', '.frequency-checkout input[type=radio]', function(e) {
                    add_to_cart = false; 
                    $('.add_to_checkout').addClass('diable loading'); 
                    if(!$('.add_to_checkout').find('.loader').length) {    
                        $('.add_to_checkout').append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>');    
                    }
                    $.ajax({
                        url: jwsThemeModule.jws_script.ajax_url,
                        dataType: 'json',
    			        method: 'POST',
                        data: {
    						action: 'save_frequency',
                            id: $(this).val(),
    					},
                        success: function (respons) {
                           if(respons.date != 'error' && $total_points == $added_points) {
                               setTimeout(function() {
                                add_to_cart = true; 
                                $('.add_to_checkout').removeClass('diable loading'); 
                               }, 500); 
                           };
                           $('.add_to_checkout').removeClass('loading');  
                        },
                        error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                            alert(xhr.responseText); // munculkan alert
                        }
                    });
                   
               });     
                
            }
            
            frequency_checkout();


            function filter_product() {
                
                 $(document.body).on('submit', '.search-meatbox', function(e) {
                    e.preventDefault();
                    if($('.products-tab > .loader').length > 0) {
                        return false;
                    }
                    var url = $(this).attr('action') + '?' + $(this).serialize();
                    $(document.body).trigger('meathouse_catelog_filter_box_ajax', [url, $(this)]);
                });
                
                 $(document.body).on('click', '.category_box_filter a , .points_box_filter a', function(e) {

                   e.preventDefault();
                    if ($(this).hasClass('current')) {
                        return;
                    }
                    $(this).parents('.filter_box_item').find('.current').html($(this).text());
                    $(this).parents('.filter_box_item a').removeClass('current');
                    $(this).addClass('current');
                    var url = $(this).attr('href');
                    $(document.body).trigger('meathouse_catelog_filter_box_ajax', [url, $(this)]);

                });

                
                $(document.body).on('meathouse_catelog_filter_box_ajax', function(e, url, element) {
                        
                    $('.products-tab').append('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>').addClass('loading');    
                    $('#section_builder .products-wrap > .loader').stick_in_parent({offset_top: 200});
                    $('.col-bulder-left').addClass('jws-animated-products');
             
                    var intervalID;

                    if ('?' == url.slice(-1)) {
                        url = url.slice(0, -1);
                    }

                    url = url.replace(/%2C/g, ',');

                    window.history.pushState(null, "", url);
                    $(window).bind("popstate", function() {
                        window.location = location.href
                    });
            
                    clearInterval(intervalID);
                    $('.box_nav').removeClass('active');
                    $('html,body').animate({
                            scrollTop: $(".nav_step").offset().top - 190
                        }, 600);
                    $.get(url, function(res) {
                        
               
                        $('.products-tab').replaceWith($(res).find('.products-tab'));
                        $('.box_filter_right').html($(res).find('.box_filter_right').html());
 
                         var iter = 0;
                        intervalID = setInterval(function() {
                                $('.products-tab').find('.product-item').eq(iter).addClass('jws-animated');
                                iter++;
                         }, 100);
                      
                            if ($('.jws-attr-swatches').length) {
                              
                                $('.jws-attr-swatches').each(function() {
                                    var _this = $(this);
                                     jwsThemeWooModule.jws_load_select_attr(_this);
                                });
                            }
                        
                        $(document.body).trigger('meathouse_ajax_filter_box_request_success', [res, url]);

                    }, 'html');


                });
            }
            
            filter_product();
           
	});
    
    
})(jQuery);    