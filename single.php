<?php 
//default file from Baskerville 1.15 since new versions do not show content

	get_header(); 
	
	$format = get_post_format();
	
?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner">
	
		<div class="content fleft">
												        
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php if ($format == 'quote' || $format == 'link' || $format == 'audio' || $format == 'status' || $format == 'chat') : ?>
					
						<?php if ( has_post_thumbnail() ) : ?>
					
							<div class="featured-media">
							
								<?php the_post_thumbnail('post-image'); ?>
								
								<?php if ( !empty(get_post(get_post_thumbnail_id())->post_excerpt) ) : ?>
												
									<div class="media-caption-container">
									
										<p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>
										
									</div>
									
								<?php endif; ?>
										
							</div> <!-- /featured-media -->
						
						<?php endif; ?>
					
					<?php endif; ?>
				
					<div class="post-header">
						
					    <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                        
						<div class="post-metas header-post-metas">
						
							<p class="post-meta post-date"><?php the_time( get_option( 'date_format' ) ); ?></p>
							
							<?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
                            
                            <p class="term-metas">
                                <?php 
                                if ( has_category() ) {
                                    ?>
                                        <span class="post-meta post-categories"><?php the_category(', '); ?></span>
                                    <?php
                                }
                                if ( has_tag() ) {
                                    ?>
                                    <span class="post-meta post-tags"><?php the_tags('', ', '); ?></span>
                                    <?php 
                                } 
                                ?>
                            </p>
							


						</div> <!-- /post-meta -->
					    
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
					
					<?php elseif ($format == 'quote') : ?> 
					
						<?php $quote_content = get_post_meta($post->ID, 'quote_content', true); ?>
						<?php $quote_attribution = get_post_meta($post->ID, 'quote_attribution', true); ?>
					
						<div class="post-quote">

							<blockquote><?php echo $quote_content; ?></blockquote>
							
							<?php if ( $quote_attribution != '' ) : ?>
							
								<cite><?php echo $quote_attribution; ?></cite>
							
							<?php endif; ?>
						
						</div> <!-- /post-quote -->
						
					<?php elseif ($format == 'link') : ?> 
					
						<?php $link_url = get_post_meta($post->ID, 'link_url', true); ?>
						<?php $link_title = get_post_meta($post->ID, 'link_title', true); ?>
					
						<div class="post-link">
						
							<p><?php echo $link_title; ?></p>
							
							<a href="<?php echo $link_url; ?>" title="<?php echo $link_title; ?>"><?php echo url_to_domain( $link_url ); ?></a>
						
						</div> <!-- /post-link -->
						
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
						
						<div class="clear"></div>
									        
					</div> <!-- /post-content -->

					<?php comments_template( '', true ); ?>
												                        
			   	<?php endwhile; else: ?>
			
					<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "gordo"); ?></p>
				
				<?php endif; ?>    
		
			</article> <!-- /post -->
		
		</div> <!-- /content -->
		
		<?php get_sidebar(); ?>
		
		<div class="clear"></div>
		
	</div> <!-- /section-inner -->

</div> <!-- /wrapper -->
		
<?php get_footer(); ?>