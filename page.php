<?php get_header(); ?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content">
	
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post-header">
												
					    <?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
                        
                        <?php echo gordo_get_hentry_metas();?>
					    				    
				    </div><!-- .post-header -->
				   				        			        		                
					<div class="post-content">                
						<?php the_content(); ?>				            			                        
					</div><!-- .post-content -->
					
					<?php comments_template( '', true ); ?>
									
				</article><!-- .post -->
			
			<?php endwhile; 
		
			else: ?>
			
				<p><?php _e( "We couldn't find any posts that matched your query. Please try again.", "gordo" ); ?></p>
		
			<?php endif; ?>

		</div><!-- .content -->
        
        <?php gordo_get_sidebar(false); ?>

	</div><!-- .section-inner -->

</div><!-- .wrapper -->
								
<?php get_footer(); ?>