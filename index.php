<?php get_header(); ?>

<div class="wrapper section medium-padding">

    <div class="page-title section-inner">
        <!-- archives title -->
        <?php echo gordo_get_archive_title();?>
        <?php 
        //archives menu
        if ( get_theme_mod('gordo_archives_filter', true) ){
            gordo_archive_menu();
        }
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

                        <article id="post-<?php the_ID(); ?>" <?php post_class('column'); ?>>

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
					
			<?php echo get_next_posts_link( '&laquo; ' . __( 'Older posts', 'gordo' ) ); ?>
						
			<?php echo get_previous_posts_link( __( 'Newer posts', 'gordo') . ' &raquo;' ); ?>

		</div><!-- .post-nav archive-nav -->
	
	<?php endif; ?>

</div><!-- .wrapper -->
	              	        
<?php get_footer(); ?>