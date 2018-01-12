<?php
/*
 Template Name: CC Blog
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

<div class="wrapper section medium-padding">

	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$total_post_count = wp_count_posts();
	$published_post_count = $total_post_count->publish;
	$total_pages = ceil( $published_post_count / $posts_per_page );
	
	if ( "1" < $paged ) : ?>
	
		<div class="page-title section-inner">
		
			<h5><?php printf( __('Page %s of %s', 'gordo'), $paged, cc_blog_query()->max_num_pages ); ?></h5>
			
		</div>
		
		<div class="clear"></div>
	
	<?php endif; ?>

	<div class="content section-inner">
            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php /*
                <div <?php post_class('single post notice'); ?>>
            
                    <?php if($post->post_content != "") : ?>

                        <div class="post-header">

                            <h2 class="post-title"><?php the_title(); ?></h2>

                        </div> <!-- /post-header -->
                        <div class="post-content">

                                <?php the_content(); ?>
                        </div> <!-- /post-content -->

                    <?php endif; ?>
                
                    <?php get_template_part( 'filter-posts-by-cat'); ?>
                    <hr class="dashed"/>
                 */
                ?>
            <?php if (cc_blog_query()->have_posts()) : ?>
            
                <div class="posts grid-posts polaroid-posts">

                <?php while (cc_blog_query()->have_posts()) : cc_blog_query()->the_post(); ?>

                        <div class="post-container">

                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                <?php get_template_part( 'content-polaroid', get_post_format() ); ?>

                            </article> <!-- /post -->

                        </div>

                <?php endwhile; ?>

                <?php endif; ?>
                

            <?php endwhile; endif; ?>

	</div> <!-- /content -->
	
	<?php if ( cc_blog_query()->max_num_pages > 1 ) : ?>
		
		<div class="archive-nav section-inner">
					
			<?php echo get_next_posts_link( '&laquo; ' . __('Older posts', 'gordo'), cc_blog_query()->max_num_pages); ?>
						
			<?php echo get_previous_posts_link( __('Newer posts', 'gordo') . ' &raquo;', cc_blog_query()->max_num_pages); ?>
			
			<div class="clear"></div>
			
		</div> <!-- /post-nav archive-nav -->
	
	<?php endif; ?>
			
	<div class="clear"></div>

</div> <!-- /wrapper -->
	              	        
<?php get_footer(); ?>