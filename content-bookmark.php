<?php
global $gordo_bookmark;
?>
    <p class="gordo-hentry-icon"><i class="fa fa-link" aria-hidden="true"></i></p>

<?php
//header
if ( get_the_title() ) { ?>
        <div class="post-header">
		  <h2 class="post-title"><a href="<?php echo $gordo_bookmark->link_url; ?>" rel="bookmark" target="_blank" title="<?php echo $gordo_bookmark->link_name; ?>"><?php echo $gordo_bookmark->link_name; ?></a></h2>
        </div><!-- .post-header -->
<?php 
}
//thumb
if ( $gordo_bookmark->link_image ) { ?>

	<div class="featured-media">
	
		<a href="<?php echo $gordo_bookmark->link_url; ?>" rel="bookmark"  target="_blank" title="<?php echo $gordo_bookmark->link_name; ?>">
			<img src="<?php echo $gordo_bookmark->link_image;?>"/>
		</a>
				
	</div><!-- .featured-media -->
		
<?php 
}

//excerpt

if ( $excerpt = $gordo_bookmark->link_description ){
    ?>
    <div class="post-excerpt">
        <?php echo $excerpt; ?>
    </div><!-- .post-excerpt -->
    <?php
}

//footer

$category = $gordo_bookmark->category_name;
//print_r($gordo_bookmark);
?>
<ul class="post-meta">
    <!--<li class="post-categories"><?php echo $gordo_bookmark->category_name;?></li>-->
    <li class="post-edit"><?php edit_bookmark_link( __( 'Edit This' ), '', '', $gordo_bookmark->link_id );?></li>
<?php
