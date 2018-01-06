jQuery(document).ready(function($) {

    //blog
    var $blog = $('.page-template-template-blog-php');
    if ($blog.length){
        
        //var $blog_sidebar = $blog.find('.section-inner .sidebar');
        //$blog_sidebar.addClass('sticky-item');
    }
    
    //portfolio
    $('.posts.grid-posts article').click(function(){
		var link = $(this).find('a.post-title');
        if (link.length){
            window.location.href = link.attr('href');
        }
    });
    
    //dropits
    var $dropits = $('.dropit');
    if ($dropits.length){
        $dropits.dropit();
        $dropits.show();
    }
    
    
});






