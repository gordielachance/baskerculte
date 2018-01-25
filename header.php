<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
    <?php wp_head(); ?>
</head>
	
<body <?php body_class('column'); ?>>
    
    <?php
    $bgimgstyle = null;
    if ( has_header_image() ){
        $bgimgstyle = sprintf(' style="background-image:url(\'%s\')"',get_header_image());
    }
    ?>

    <header class="section no-padding bg-dark column"<?php echo $bgimgstyle;?>>
        <div class="bg-graphite section small-padding">
            <div class="header-inner section-inner">
                <?php
                //logo
                //TO FIX NOT WORKING
                if ( has_custom_logo() ) {
                    the_custom_logo();
                }else{
                    //title & desc
                    $title_attr = ( get_bloginfo( 'description' ) ) ? sprintf('%s - %s',get_bloginfo( 'title' ),get_bloginfo( 'description' )) : get_bloginfo( 'title' );
                    if ( get_bloginfo( 'title' ) ) {
                        ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr($title_attr); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'title' ) ); ?></a>
                        </h1>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div>
            <div class="header-inner section-inner row">
                <div class="nav-toggle fleft hidden">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>		
                <ul id="main-wide-menu" class="row">

                    <?php 

                    if ( has_nav_menu( 'primary' ) ) {

                        $nav_args = array( 
                            'container' 		=> '', 
                            'items_wrap' 		=> '%3$s',
                            'theme_location' 	=> 'primary', 
                            'walker' 			=> new gordo_nav_walker,
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
                 </ul><!-- .main-menu -->
                <!--search -->
                <span id="menu-item-search">
                    <?php get_template_part( 'searchform' ); ?>
                </span>
            </div>
        </div>
        <ul id="main-mobile-menu" class="section bg-dark no-padding hidden">
            <!-- jQuery clone of #main-wide-menu -->
        </ul>
    </header><!-- .navigation -->