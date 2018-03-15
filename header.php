<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
    <?php wp_head(); ?>
</head>
	
<body <?php body_class('row'); ?>>
    <?php 
    //use a sidebar header
    if ( get_theme_mod( 'gordo_sidebar_header' ) ) {
        get_template_part( 'header', 'side' );
    }
    ?>
    <div id="all-but-header">
        <?php get_template_part( 'header', 'top' ); ?>
        <header id="header-mobile" class="hidden">
            <ul id="main-mobile-menu" class="menu section bg-dark no-padding">
                <!-- jQuery clone of #main-wide-menu ul -->
            </ul>
        </header>
    