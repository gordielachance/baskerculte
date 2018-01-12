<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >

    <?php wp_head(); ?>

</head>
	
<body <?php body_class(); ?>>

    <header class="section no-padding bg-dark">

        <div class="header-inner section-inner">

            <?php
            if ( get_bloginfo( 'description' ) || get_bloginfo( 'title' ) ) {
                ?>
                <h1 class="blog-title">
                    <a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'title' ) ) . ' &mdash; ' . esc_attr( get_bloginfo( 'description' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'title' ) ); ?></a>
                </h1>
                <?php
            }
            ?>

            <div class="nav-toggle fleft hidden">

                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>

                <div class="clear"></div>

            </div>		
            <ul id="main-menu">

                <?php 

                if ( has_nav_menu( 'primary' ) ) {

                    $nav_args = array( 
                        'container' 		=> '', 
                        'items_wrap' 		=> '%3$s',
                        'theme_location' 	=> 'primary', 
                        'walker' 			=> new baskerculte_nav_walker,
                    );

                    wp_nav_menu( $nav_args ); 

                } else {

                    $list_pages_args = array(
                        'container' => '',
                        'title_li' 	=> '',
                    );

                    wp_list_pages( $list_pages_args );

                } 

                ?>

                <!--search -->
                <?php get_template_part( 'searchform' ); ?>

             </ul><!-- .main-menu -->

        </div><!-- .navigation-inner -->

        <ul id="mobile-main-menu" class="section bg-graphite no-padding hidden">
            <!-- jQuery clone of #main-menu -->
        </ul>

    </header><!-- .navigation -->