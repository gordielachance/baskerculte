<?php get_header(); ?>

<div class="wrapper section medium-padding">

    <div class="page-title section-inner">
        <!-- archives title -->
        <?php echo gordo_archive_title();?>
        <!-- archives menu -->
        <div id="archives-menu" class="section-inner">
            <i class="fa fa-cog" aria-hidden="true"></i>
            <ul class="menu">
                <li><?php 
                    $link_all = get_permalink( get_option( 'page_for_posts' ) );
                    $text_all = __('All','gordo');
                    printf('<a href="%s" title="%s">%s</a>',$link_all,$text_all,$text_all);
                    ?>
                </li>
                <?php 

                if ( has_nav_menu( 'archives' ) ) {

                    $nav_args = array( 
                        'container' 		=> '', 
                        'items_wrap' 		=> '%3$s',
                        'theme_location' 	=> 'archives', 
                        'walker' 			=> new gordo_nav_walker,
                    );

                    wp_nav_menu( $nav_args ); 

                } else {

                    $archives_cat_args = array(
                        'title_li' 	=> '',
                        'hide_title_if_empty' => true,
                        //'show_option_all' => __('All','gordo'),
                    );

                    wp_list_categories( $archives_cat_args );

                } 

                    do_action('gordo_archives_menu');
                
                ?>
                
             </ul><!-- #archives-menu -->
        </div>
    </div>

	<div class="content section-inner">
																		                    
		<?php if ( have_posts() ) { ?>
		
			<div class="posts infinite-hentries grid-hentries masonry">
					
		    	<?php 
                while ( have_posts() ) { 
                    the_post();
                    global $post;
                    ?>

                    <div class="hentry-container">

                        <article id="post-<?php the_ID(); ?>" <?php post_class('column'); ?>>

                            <?php get_template_part( 'content', get_post_format() ); ?>

                        </article><!-- .post -->

                    </div>
		    			        		            
		        <?php } ?>
                
            </div><!-- .posts -->
	        	                    
        <?php }else{
            echo"No Posts Found";//TO FIX have sidebar etc.
        } ?>
			
	</div><!-- .content -->
	
	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		
		<div class="archive-nav section-inner">
					
			<?php echo get_next_posts_link( '&laquo; ' . __( 'Older posts', 'gordo' ) ); ?>
						
			<?php echo get_previous_posts_link( __( 'Newer posts', 'gordo') . ' &raquo;' ); ?>

		</div><!-- .post-nav archive-nav -->
	
	<?php endif; ?>

</div><!-- .wrapper -->
	              	        
<?php get_footer(); ?>