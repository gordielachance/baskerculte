<div class="post-header">

	<?php if ( get_the_title() ) : ?>
		
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

	<?php endif; ?>
    
    <?php if ( is_sticky() ) echo '<span class="sticky-post">' . __( 'Sticky post', 'gordo' ) . '</span>'; ?>
    
</div><!-- .post-header -->

<?php if ( has_post_thumbnail() ) : ?>

	<div class="featured-media">
	
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
		
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			
		</a>
				
	</div><!-- .featured-media -->
		
<?php endif; ?>
									                                    	    
<div class="post-excerpt">

	<?php the_excerpt( 100 ); ?>

</div><!-- .post-excerpt -->

<?php gordo_meta(); ?>
            
<div class="clear"></div>