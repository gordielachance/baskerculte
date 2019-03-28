jQuery(document).ready(function($) {
    
    //Expandable lists
    $('#header ul.menu li').gordoExpandableMenu({button_glyph_open:'<i class="fa fa-angle-down"></i>',button_glyph_closed:'<i class="fa fa-angle-up"></i>'});
    
    //External links
    $('a[target="_blank"]').addClass('external-link');
    $('a.external-link').each(function(){
        var domain = gordo_extract_url_domain($(this).attr('href'));
        $(this).attr('data-url-domain',domain);
        if ($(this).find('img').length) {
            $(this).addClass('no-link-icon');
        }
    });

	//Masonry blocks
	$blocks = $(".posts.masonry");

	$blocks.imagesLoaded(function(){
		$blocks.masonry({
			itemSelector: 'article'
		});

		// Fade blocks in after images are ready (prevents jumping and re-rendering)
		$("article").fadeIn();
	});
	
	$(document).ready( function() { setTimeout( function() { $blocks.masonry(); }, 500); });

	$(window).resize(function () {
		$blocks.masonry();
	});

    /*
    Mobile Menu
    */

	$(window).resize(function() {
        var is_mobile = ($(window).width() <= 782);
        $('body').toggleClass('gordo-is-mobile',is_mobile);
	});
    
	// Toggle mobile-menu
	$("#toggle-menu-bt").on("click", function(){	
		$('#header-menu').toggleClass("gordo-expanded");
	})
	
	
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
    
    $(window).trigger('resize');

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