<?php 

/*
 Template Name: Gordo Bookmarks
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/

get_header(); ?>

<div class="wrapper section medium-padding">

    <div class="page-title section-inner">
        <h3 class="post-title"><?php the_title(); ?></h3>
    </div>

	<div class="content section-inner">

        <?php 
                
        if ( $bookmarks = gordo()->get_page_bookmarks() ){
            ?>
            <div class="posts infinite-hentries grid-hentries masonry">
                <?php
                foreach((array)$bookmarks as $bookmark){
                        global $gordo_bookmark;
                        $gordo_bookmark = $bookmark;
                        ?>
                        <div class="hentry-container">

                            <article id="post-<?php the_ID(); ?>" class="hentry column">

                                <?php get_template_part( 'content', 'bookmark' ); ?>

                            </article><!-- .post -->

                        </div>
                        <?php
                }
                ?>
            </div>
            <?php
        }else{
            //TO FIX
        }

        ?>
			
	</div><!-- .content -->

</div><!-- .wrapper -->
	              	        
<?php get_footer(); ?>
