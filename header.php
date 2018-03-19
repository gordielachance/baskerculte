<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
    <?php wp_head(); ?>
</head>
    
<?php
    
$body_classes = array(
    ( get_theme_mod( 'gordo_sidebar_header' ) ) ? 'row' : null,
);
?>
	
<body <?php body_class($body_classes); ?>>
<header id="header" class="section no-padding"<?php echo gordo()->get_header_styles();?>>
    <div class="section small-padding">
        <div id="site-info" class="header-inner section-inner">
            <?php
            //logo
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
                if ( $site_desc = get_bloginfo( 'description' ) ) {
                    ?>
                    <h2 class="site-description">
                        <?php echo $site_desc;?>
                    </h2>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <div id="mobile-menu-switch" class="nav-toggle">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </div>	
    <div id="main-wide-menu" class="section-inner">
        <ul class="menu">
            <!--search -->
            <li id="menu-item-search" class="menu-item">
                <?php get_template_part( 'searchform' ); ?>
            </li>
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
    </div>
</header><!-- .navigation -->
    <div id="all-but-header">
    