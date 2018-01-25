<?php 
	get_header(); 
	$format = get_post_format();
?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner row">
	
		<div class="content column">
												        
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
					
					<?php 

					if ($format == 'video') : ?> 
					
						<?php $video_url = get_post_meta($post->ID, 'video_url', true); ?>

						<div class="featured-media">
						
							<?php if (strpos($video_url,'.mp4') !== false) : ?>
								
								<video controls>
								  <source src="<?php echo $video_url; ?>" type="video/mp4">
								</video>
																						
							<?php else : ?>
								
								<?php 
								
									$embed_code = wp_oembed_get($video_url); 
									
									echo $embed_code;
									
								?>
									
							<?php endif; ?>
							
						</div>
						
					<?php elseif ($format == 'audio') : ?>
					
						<?php $audio_url = get_post_meta($post->ID, 'audio_url', true); ?>
	
						<div class="post-audio">
						
							<audio controls="controls" id="audio-player">
							
								<source src="<?php echo $audio_url; ?>" />
								
							</audio>
						
						</div> <!-- /post-audio -->

					<?php elseif ($format == 'gallery') : ?> 
					
						<div class="featured-media">

							<?php gordo()->flexslider('post-image'); ?>
											
						</div> <!-- /featured-media -->
				
					<?php elseif ( has_post_thumbnail() ) : ?>
					
						<div class="featured-media">
						
							<?php the_post_thumbnail('post-image'); ?>
							
							<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
											
								<div class="media-caption-container">
								
									<p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
									
								</div>
								
							<?php endif; ?>
									
						</div> <!-- /featured-media -->
					
					<?php endif; ?>
														                                    	    
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