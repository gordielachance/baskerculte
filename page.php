<?php get_header(); ?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner row">
	
		<div class="content column">
	
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post-header">
												
					    <?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
                        
                        <?php echo gordo_get_hentry_metas();?>
					    				    
				    </div><!-- .post-header -->
				
					<?php if ( has_post_thumbnail() ) : ?>
						
						<div class="featured-media">
						
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
							
								<?php the_post_thumbnail( 'post-image' ); ?>
								
								<?php if ( ! empty( get_post( get_post_thumbnail_id() )->post_excerpt ) ) : ?>
												
									<div class="media-caption-container">
									
										<p class="media-caption"><?php echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></p>
										
									</div>
									
								<?php endif; ?>
								
							</a>
									
						</div><!-- .featured-media -->
							
					<?php endif; ?>
				   				        			        		                
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