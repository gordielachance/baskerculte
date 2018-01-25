<div class="footer section medium-padding bg-graphite">
	
	<div class="section-inner row">
	
		<?php if ( is_active_sidebar( 'footer-a' ) ) { ?>
		
			<div class="column one-third">
			
				<div class="widgets">
		
					<?php dynamic_sidebar( 'footer-a' ); ?>
										
				</div>
				
			</div><!-- .column-1 -->
			
		<?php } else { ?>
		
			<div class="column one-third">
			
				<div class="widgets">
			
					<div id="search" class="widget widget_search">
					
						<div class="widget-content">
						
							<h3 class="widget-title"><?php _e( 'Search form', 'gordo' ); ?></h3>
			                <?php get_search_form(); ?>
			                
						</div>
						
	                </div>
									
				</div>
				
			</div><!-- .column-1 -->
			
		<?php } ?>
			
		<?php if ( is_active_sidebar( 'footer-b' ) ) { ?>
		
			<div class="column one-third">
			
				<div class="widgets">
		
					<?php dynamic_sidebar( 'footer-b' ); ?>
										
				</div><!-- .widgets -->
				
			</div><!-- .column-2 -->
			
		<?php } else { ?>
		
			<div class="column one-third">
			
				<div class="widgets">
				
					<div class="widget widget_recent_entries">
					
						<div class="widget-content">
						
							<h3 class="widget-title"><?php _e( 'Latest posts', 'gordo' ); ?></h3>
							
							<ul>
				                <?php
									$args = array( 
										'numberposts' => '5', 
										'post_status' => 'publish' 
									);
									$recent_posts = wp_get_recent_posts( $args );
									foreach( $recent_posts as $recent ){
										echo '<li><a href="' . get_permalink( $recent["ID"] ) . '" title="' . esc_attr( $recent["post_title"] ) . '" >' .  $recent["post_title"] . '</a></li>';
									}
								?>
							</ul>
			                
						</div>
						
	                </div>
									
				</div><!-- .widgets -->
				
			</div><!-- .column-2 -->
			
		<?php } ?>
							
		<?php if ( is_active_sidebar( 'footer-c' ) ) { ?>
		
			<div class="column one-third">
		
				<div class="widgets">
		
					<?php dynamic_sidebar( 'footer-c' ); ?>
										
				</div><!-- .widgets -->
				
			</div>
			
		<?php } else { ?>
		
			<div class="column one-third">
			
				<div id="meta" class="widget widget_text">

					<div class="widget-content">
					
						<h3 class="widget-title"><?php _e( "Text widget", "gordo" ); ?></h3>
						<p><?php _e( "These widgets are displayed because you haven't added any widgets of your own yet. You can do so at Appearance > Widgets in the WordPress settings.", "gordo" ); ?></p>
		                
					</div>

                </div>
								
			</div>
			
		<?php } ?><!-- .footer-c -->
		
		
	
	</div><!-- .footer-inner -->

</div><!-- .footer -->

<div class="credits section bg-dark small-padding">

	<div class="credits-inner section-inner row">
        
		<?php if ( is_active_sidebar( 'credits' ) ) { ?>

				<div class="widgets">
		
					<?php dynamic_sidebar( 'credits' ); ?>
										
				</div>

		<?php } else { ?>
        
            <p class="alignleft">

                &copy; <?php echo date("Y") ?> <a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a><span> &mdash; <?php printf( __( 'Powered by <a href="%s">WordPress</a>', 'gordo'), 'http://www.wordpress.org' ); ?></span>

            </p>

            <p class="alignright">
                <span><?php printf( __( 'Theme by <a href="%s">G.Breant</a>', 'gordo' ), 'https://github.com/gordielachance' ); ?></span>
            </p>
        <?php }?>
        
        <p id="tothetop-footer" class="alignright">
            <a class="tothetop" title="<?php _e( 'To the top', 'gordo' ); ?>" href="#"><?php _e( 'Up', 'gordo' ); ?> &uarr;</a>
        </p>

	</div><!-- .credits-inner -->
	
</div><!-- .credits -->

<?php wp_footer(); ?>

</body>
</html>