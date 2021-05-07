(function($){
	"use strict";
	$(window).on('elementor/frontend/init', function () {
		
		elementorFrontend.hooks.addAction('frontend/element_ready/Kinetic-Global-Widgets-Team-Carousel.default', function(){
			
			var owlWrap = $('.owl-wrapper');

			if (owlWrap.length > 0) {

				if (jQuery().owlCarousel) {
					owlWrap.each(function(){

						var carousel= $(this).find('.owl-carousel'),
							dataNum = $(this).find('.owl-carousel').attr('data-num'),
							dataNum2,
							dataNum3;

						if ( dataNum == 1 ) {
							dataNum2 = 1;
							dataNum3 = 1;
						} else if ( dataNum == 2 ) {
							dataNum2 = 2;
							dataNum3 = dataNum - 1;
						} else {
							dataNum2 = dataNum - 1;
							dataNum3 = dataNum - 2;
						}

						carousel.owlCarousel({
							autoPlay: 10000,
							navigation : true,
							items : dataNum,
							itemsDesktop : [1199,dataNum2],
							itemsDesktopSmall : [979,dataNum3],
							itemsTablet : [768, dataNum3],
						});

					});
				}
			}
			
		});
	
		elementorFrontend.hooks.addAction('frontend/element_ready/Kinetic-Global-Widgets-Gallery-Carousel.default', function(){
			
			var owlWrap = $('.owl-wrapper');

			if (owlWrap.length > 0) {

				if (jQuery().owlCarousel) {
					owlWrap.each(function(){

						var carousel= $(this).find('.owl-carousel'),
							dataNum = $(this).find('.owl-carousel').attr('data-num'),
							dataNum2,
							dataNum3;

						if ( dataNum == 1 ) {
							dataNum2 = 1;
							dataNum3 = 1;
						} else if ( dataNum == 2 ) {
							dataNum2 = 2;
							dataNum3 = dataNum - 1;
						} else {
							dataNum2 = dataNum - 1;
							dataNum3 = dataNum - 2;
						}

						carousel.owlCarousel({
							autoPlay: 10000,
							navigation : true,
							items : dataNum,
							itemsDesktop : [1199,dataNum2],
							itemsDesktopSmall : [979,dataNum3],
							itemsTablet : [768, dataNum3],
						});

					});
				}
			}
			
		});
	
		elementorFrontend.hooks.addAction('frontend/element_ready/Kinetic-Global-Widgets-Testimonials.default', function(){
			
			var owlWrap = $('.owl-wrapper');

			if (owlWrap.length > 0) {

				if (jQuery().owlCarousel) {
					owlWrap.each(function(){

						var carousel= $(this).find('.owl-carousel'),
							dataNum = $(this).find('.owl-carousel').attr('data-num'),
							dataNum2,
							dataNum3;

						if ( dataNum == 1 ) {
							dataNum2 = 1;
							dataNum3 = 1;
						} else if ( dataNum == 2 ) {
							dataNum2 = 2;
							dataNum3 = dataNum - 1;
						} else {
							dataNum2 = dataNum - 1;
							dataNum3 = dataNum - 2;
						}

						carousel.owlCarousel({
							autoPlay: 10000,
							navigation : true,
							items : dataNum,
							itemsDesktop : [1199,dataNum2],
							itemsDesktopSmall : [979,dataNum3],
							itemsTablet : [768, dataNum3],
						});

					});
				}
			}
			
		});

		elementorFrontend.hooks.addAction('frontend/element_ready/Kinetic-Global-Widgets-Gmap.default', function(){
    		if ($(".map").length){
    			
				var map_latitude = Number($('.map').attr('data-latitude'));
				var map_longitude = Number($('.map').attr('data-longitude'));
				var map_zoom = parseInt($('.map').attr('data-zoom'));
				var map_marker = $('.map').attr('data-marker');

				/* ---------------------------------------------------------------------- */
				/*	Contact Map
				/* ---------------------------------------------------------------------- */
				
				var fenway = [map_longitude,map_latitude]; //Change a map coordinate here!
				var markerPosition = [map_longitude,map_latitude]; //Change a map marker here!
				$('.map')
					.gmap3({
						center:fenway,
						zoom: map_zoom,
						mapTypeId: "shadeOfGrey", // to select it directly
						scrollwheel: false,
						mapTypeControlOptions: {
							mapTypeIds: [google.maps.MapTypeId.ROADMAP, "shadeOfGrey"]
						}
					})
					.styledmaptype(
					"shadeOfGrey",
					[
						{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":60}]},
						{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":100}]},
						{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
						{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":90}]},
						{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":87},{"weight":1.2}]},
						{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f8f2e4"},{"lightness":0}]},
						{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#b4e3c7"},{"lightness":0}]},
						{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#d9ceac"},{"lightness":0}]},
						{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#d2c59d"},{"lightness":100},{"weight":0.2}]},
						{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":0}]},
						{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#efebe4"},{"lightness":0}]},
						{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":0}]},
						{"featureType":"water","elementType":"geometry","stylers":[{"color":"#bad8fb"},{"lightness":0}]}
					],
					{name: "Shades of Grey"});
					$('.map')
						.gmap3({
							center: fenway,
							zoom: map_zoom,
							mapTypeId : google.maps.MapTypeId.ROADMAP
						})
						.marker({
							position: markerPosition,
							icon: map_marker
					});

				

				
			}
    	});

		elementorFrontend.hooks.addAction('frontend/element_ready/Kinetic-Global-Widgets-Blog-Masonry.default', function(){
			var winDow = $(window);
			// Needed variables
			var $container=$('.iso-call');
			var $filter=$('.filter');

			try{
				$container.imagesLoaded( function(){
					$container.trigger('resize');
					$container.isotope({
						filter:'*',
						layoutMode:'masonry',
						animationOptions:{
							duration:750,
							easing:'linear'
						}
					});
					setTimeout(Resize, 1500);
				});
			} catch(err) {
			}

			winDow.on('resize', function(){
				var selector = $filter.find('a.active').attr('data-filter');

				try {
					$container.isotope({ 
						filter	: selector,
						animationOptions: {
							duration: 750,
							easing	: 'linear',
							queue	: false,
						}
					});
				} catch(err) {
				}
				return false;
			});
			
			// Isotope Filter 
			$filter.find('a').on('click', function(){
				var selector = $(this).attr('data-filter');

				try {
					$container.isotope({ 
						filter	: selector,
						animationOptions: {
							duration: 750,
							easing	: 'linear',
							queue	: false,
						}
					});
				} catch(err) {

				}
				return false;
			});


		var filterItemA	= $('.filter li a');

			filterItemA.on('click', function(){
				var $this = $(this);
				if ( !$this.hasClass('active')) {
					filterItemA.removeClass('active');
					$this.addClass('active');
				}
			});

	});	

	});
	function Resize() {
		$(window).trigger('resize');
	}

})(jQuery);