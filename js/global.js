jQuery(document).ready(function($) {

	//Masonry blocks
	$blocks = $(".posts.grid-posts");

	$blocks.imagesLoaded(function(){
		$blocks.masonry({
			itemSelector: '.post-container'
		});

		// Fade blocks in after images are ready (prevents jumping and re-rendering)
		$(".post-container").fadeIn();
	});
	
	$(document).ready( function() { setTimeout( function() { $blocks.masonry(); }, 500); });

	$(window).resize(function () {
		$blocks.masonry();
	});
    
    /*
    Mobile Menu
    */
    var mobile_menu = $('header #main-menu').html();
    $('#mobile-main-menu').html(mobile_menu);

	// Toggle mobile-menu
	$("header .nav-toggle").on("click", function(){	
		$(this).toggleClass("active");
		$("#mobile-main-menu").slideToggle();
	});

	// Show mobile-menu
	$(window).resize(function() {
		if ($(window).width() > 782) {
			$("header .nav-toggle").removeClass("active");
			$("#mobile-main-menu").hide();
		}
	});
	
	
	// Load Flexslider
    $(".flexslider").flexslider({
        animation: "slide",
        controlNav: false,
        prevText: "Previous",
        nextText: "Next",
        smoothHeight: true   
    });

        
	// resize videos after container
	var vidSelector = ".post iframe, .post object, .post video, .widget-content iframe, .widget-content object, .widget-content iframe";	
	var resizeVideo = function(sSel) {
		$( sSel ).each(function() {
			var $video = $(this),
				$container = $video.parent(),
				iTargetWidth = $container.width();

			if ( !$video.attr("data-origwidth") ) {
				$video.attr("data-origwidth", $video.attr("width"));
				$video.attr("data-origheight", $video.attr("height"));
			}

			var ratio = iTargetWidth / $video.attr("data-origwidth");

			$video.css("width", iTargetWidth + "px");
			$video.css("height", ( $video.attr("data-origheight") * ratio ) + "px");
		});
	};

	resizeVideo(vidSelector);

	$(window).resize(function() {
		resizeVideo(vidSelector);
	});
	
	
	// Smooth scroll to header
    $('.tothetop').click(function(){
		$('html,body').animate({scrollTop: 0}, 500);
		$(this).unbind("mouseenter mouseleave");
        return false;
    });
    
    
});