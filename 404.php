<?php 
	get_header(); 
?>

<div class="wrapper section medium-padding">
										
	<div class="section-inner row">
	
		<div class="content column">
		
			<article class="hentry post post-404">
			
				<div class="post-header">
				        
		        	<h2 class="post-title"><?php _e( 'Error 404', 'gordo' ); ?></h2>
		        	
		        </div>
			                                                	            
		        <div class="post-content">
		        	            
		            <p><?php _e( "It seems like you have tried to open a page that doesn't exist. It could have been deleted, moved, or it never existed at all. You are welcome to search for what you are looking for with the form below.", 'gordo' ); ?></p>
		            
		            <?php get_search_form(); ?>
		            
		        </div><!-- .post-content -->
		        
			</article><!-- .post -->
		
		</div><!-- .content -->
		
		<?php gordo_get_sidebar(); ?>

	</div><!-- .section-inner -->

</div><!-- .wrapper -->

<?php get_footer(); ?>
