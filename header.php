<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header id="header" class="bg-graphite section no-padding"<?php echo gordo()->get_header_styles();?>>
    <?php 
    if ( gordo()->get_options('toggle_header_bt') ){
        ?>
        <div id="toggle-header-bt"></div>
        <?php
    }
    ?>
    <div id="site-info" class="section small-padding">
        <div class="header-inner section-inner">
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
    <div id="header-menu">
        <div id="toggle-header-menu-bt">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <ul class="menu section-inner small-padding">
            <!--search -->
            <?php 

            if ( has_nav_menu( 'gordo_primary' ) ) {

                $nav_args = array( 
                    'container' 		=> '', 
                    'items_wrap' 		=> '%3$s',
                    'theme_location' 	=> 'gordo_primary', 
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
         </ul><!-- #header-menu -->
        <?php
        if ( is_active_sidebar( 'header-sidebar' ) ){
            ?>
            <div id="widget-area" class="widget-area section-inner small-padding" role="complementary">
                <?php dynamic_sidebar( 'header-sidebar' ); ?>
            </div><!-- .widget-area -->
            <?php
        }
        ?>
    </div>
</header><!-- .navigation -->
    