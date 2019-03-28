<?php get_header(); ?>

<div class="wrapper section medium-padding">

    <div class="page-title section-inner">
        <!-- archives title -->
        <?php echo gordo_get_archive_title();?>
        <?php 
        //archives menu
        do_action('gordo_after_archive_title');
        ?>
    </div>

	<div class="content section-inner">
																		                    
		<?php if ( have_posts() ) { ?>
		
			<div class="posts infinite-hentries grid-hentries masonry">
					
		    	<?php 
                while ( have_posts() ) { 
                    the_post();
                    global $post;
                    ?>

                    <div class="hentry-container">

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <?php get_template_part( 'content', get_post_format() ); ?>

                        </article><!-- .post -->

                    </div>
		    			        		            
		        <?php } ?>
                
            </div><!-- .posts -->
	        	                    
        <?php }else{
            echo"No Posts Found";//TO FIX have sidebar etc.
        } ?>
			
	</div><!-- .content -->
	
	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		
		<div class="archive-nav section-inner">
            <?php

            if ( !gordo()->get_options('pagination_mode') ){

                echo get_next_posts_link( __( 'Previous page', 'gordo' ) );
                echo get_previous_posts_link( __( 'Next page', 'gordo') );
            }else{
                // Previous/next page navigation.

                the_posts_pagination(
                    array(
                        'prev_text'          => __( 'Previous page', 'gordo' ),
                        'next_text'          => __( 'Next page', 'gordo' ),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
                    )
                );
            }
            ?>
		</div><!-- .post-nav archive-nav -->
	
	<?php endif; ?>

</div><!-- .wrapper -->
	              	        
<?php get_footer(); ?>