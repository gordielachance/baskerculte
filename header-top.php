<header id="header-top" class="bg-graphite section no-padding column"<?php echo gordo()->get_header_styles();?>>
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
    <div id="main-wide-menu" class="bg-dark">
        <div class="bg-dark header-inner section-inner row">
            <div class="nav-toggle fleft hidden">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </div>		
            <ul class="menu row">

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
                <!--search -->
                <li id="menu-item-search">
                    <?php get_template_part( 'searchform' ); ?>
                </li>
             </ul><!-- .main-menu -->
        </div>
    </div>
</header><!-- .navigation -->