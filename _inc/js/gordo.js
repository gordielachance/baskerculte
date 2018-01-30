jQuery(document).ready(function($) {
    
    //External links
    $('a[target="_blank"]').addClass('external-link');
    $('a.external-link').each(function(){
        var domain = gordo_extract_url_domain($(this).attr('href'));
        $(this).attr('data-url-domain',domain);
    });

	//Masonry blocks
	$blocks = $(".posts.masonry");

	$blocks.imagesLoaded(function(){
		$blocks.masonry({
			itemSelector: '.hentry-container'
		});

		// Fade blocks in after images are ready (prevents jumping and re-rendering)
		$(".hentry-container").fadeIn();
	});
	
	$(document).ready( function() { setTimeout( function() { $blocks.masonry(); }, 500); });

	$(window).resize(function () {
		$blocks.masonry();
	});

    /*
    Mobile Menu
    */
    var mobile_menu = $('header #main-wide-menu .menu').html();
    $('#main-mobile-menu').html(mobile_menu);

	// Toggle mobile-menu
	$("header .nav-toggle").on("click", function(){	
		$(this).toggleClass("active");
		$("#main-mobile-menu").slideToggle();
	});

	// Show mobile-menu
	$(window).resize(function() {
		if ($(window).width() > 782) {
			$("header .nav-toggle").removeClass("active");
			$("#main-mobile-menu").hide();
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

//https://stackoverflow.com/a/23945027/782013
function gordo_extract_url_hostname(url) {
    var hostname;
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
        hostname = url.split('/')[2];
    }
    else {
        hostname = url.split('/')[0];
    }

    //find & remove port number
    hostname = hostname.split(':')[0];
    //find & remove "?"
    hostname = hostname.split('?')[0];

    return hostname;
}
function gordo_extract_url_domain(url) {
    var domain = gordo_extract_url_hostname(url),
        splitArr = domain.split('.'),
        arrLen = splitArr.length;

    //extracting the root domain here
    //if there is a subdomain 
    if (arrLen > 2) {
        domain = splitArr[arrLen - 2] + '.' + splitArr[arrLen - 1];
        //check to see if it's using a Country Code Top Level Domain (ccTLD) (i.e. ".me.uk")
        if (splitArr[arrLen - 1].length == 2 && splitArr[arrLen - 1].length == 2) {
            //this is using a ccTLD
            domain = splitArr[arrLen - 3] + '.' + domain;
        }
    }
    return domain;
}