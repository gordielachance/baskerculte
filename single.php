<?php get_header(); ?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content">
												        
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <?php
                    //post format icon
                    if ( $icon = gordo_get_hentry_icon() ){
                        ?>
                        <p class="gordo-hentry-icon"><?php echo $icon;?></p>
                        <?php
                    }
                    ?>
				
					<div class="post-header">
                        
                        <?php the_title( '<h2 class="post-title">', '</h2>' ); ?>
						<?php echo gordo_get_hentry_metas();?>
					    
					</div> <!-- /post-header -->
																			                                    	    
					<div class="post-content">
						                                                                                       
						<?php the_content(); ?>
								
						<?php wp_link_pages(); ?>
		        
					</div> <!-- /post-content -->

					<?php comments_template( '', true ); ?>
												                        
			   	<?php endwhile; else: ?>
			
					<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "gordo"); ?></p>
				
				<?php endif; ?>    
		
			</article> <!-- /post -->
		
		</div> <!-- /content -->
		
		<?php gordo_get_sidebar(); ?>

	</div> <!-- /section-inner -->

</div> <!-- /wrapper -->
		
<?php get_footer(); ?>