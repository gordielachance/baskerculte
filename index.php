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

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php get_template_part( 'content', get_gordo_post_format() ); ?>

                    </article><!-- .post -->
		    			        		            
		        <?php } ?>
                
            </div><!-- .posts -->
	        	                    
        <?php }else{
            _e( 'Nothing Found', 'gordo' );//TO FIX have sidebar etc.
        } ?>
			
	</div><!-- .content -->
    
    <?php gordo_pagination();?>

</div><!-- .wrapper -->
	              	        
<?php get_footer(); ?>