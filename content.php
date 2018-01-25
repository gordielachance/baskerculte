<?php

/*
Gordo tile
*/

//post format icon
if ( $icon = gordo_get_hentry_icon() ){
    ?>
    <p class="gordo-hentry-icon"><?php echo $icon;?></p>
    <?php
}

//header
if ( get_the_title() ) { ?>
        <div class="post-header">
		  <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        </div><!-- .post-header -->
<?php 
}
//thumb
if ( has_post_thumbnail() ) { ?>

	<div class="featured-media">
	
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
		
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			
		</a>
				
	</div><!-- .featured-media -->
		
<?php 
}


//excerpt

if ( $excerpt = get_the_excerpt() ){
    ?>
    <div class="post-excerpt">
        <?php the_excerpt(); ?>
    </div><!-- .post-excerpt -->
    <?php
}

//footer
echo gordo_get_hentry_metas(); 
?>
            
