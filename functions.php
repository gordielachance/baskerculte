<?php

function cc_portfolio_query(){
    global $cc_portfolio_query;
    global $wp_the_query;

    if (!$cc_portfolio_query){
        $args = array(
                'post_type'		=>	'post',
                'showposts'		=>	12,
                'tag'			=> 'portfolio',
		'category__not_in'	=> -9, //exclude web
                'has_password'		=> false,
                'paged'			=> get_query_var( 'paged' ), //if homepage, 'page' instead of 'paged'
        );
        
        if ( isset($_GET['cat']) ) {
		$args['cat'] = $_GET['cat'];
		if ($args['cat'] == 9){
			unset( $args['category__not_in'] );
		}
	}


        $cc_portfolio_query = new WP_Query( $args );
    }
    
    $wp_the_query = $cc_portfolio_query;

    return $cc_portfolio_query;
}

function cc_blog_query(){
    global $cc_blog_query;
    global $wp_the_query;
    
    if (!$cc_blog_query){
        $args = array(
                'post_type'		=>	'post',
                'showposts'		=>	20,
                'has_password'		=> false,
                'paged'			=> get_query_var( 'paged' ),
                //'tag__not_in' => array('53'),//exclude portfolio
        );
        
        if (isset($_GET['cat']))$args['cat'] = $_GET['cat'];

        $cc_blog_query = new WP_Query( $args );
    }
    
    $wp_the_query = $cc_blog_query;
    
    return $cc_blog_query;
}

class Baskerculte{
    
	/**
	 * @var The one true Instance
	 */
	private static $instance;

	/**
	 * Main Instance
	 *
	 * Insures that only one instance of the plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @staticvar array $instance
	 * @return The instance
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Baskerculte;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}
    
    function __construct(){ 
    }
    
    function setup_globals(){
    }
    
    function includes(){
    }
    
    function setup_actions(){
        //baskerville
        add_action( 'after_setup_theme', array($this,'baskerculte_setup') );
        add_action( 'wp_enqueue_scripts', array($this,'baskerculte_load_javascript_files') );
        add_action( 'wp_print_styles', array($this,'baskerculte_load_style') );
        add_action( 'init', array($this,'baskerculte_add_editor_styles') );
        add_action( 'widgets_init', array($this,'baskerculte_sidebar_registration') );
        add_action( 'customize_register' , array( 'baskerculte_Customize' , 'register' ) ); // Setup the Theme Customizer settings and controls...
        add_action( 'admin_head', array($this,'baskerculte_admin_css') );
        add_filter( 'excerpt_more', array($this,'baskerculte_new_excerpt_more') );
        add_action( 'body_class', array($this,'baskerculte_body_classes') );
        add_filter( 'previous_posts_link_attributes', array($this,'baskerculte_pagination_classes_prev') );
        add_filter( 'next_posts_link_attributes', array($this,'baskerculte_pagination_classes_next') );
        add_filter( 'excerpt_length', array($this,'baskerculte_custom_excerpt_length'), 999 );
        
        //custom
        add_filter( 'body_class', array($this,'body_class'),999 );
        add_action( 'wp_enqueue_scripts', array($this,'scripts_styles') );
        add_action( 'loop_start', array($this,'remove_blog_share') );
        add_shortcode( 'gallery', array($this,'gallery') );
        
        add_action( 'wp', array($this,'cv_no_autopop') );

    }
    
    function baskerculte_pagination_classes_next() {
        return 'class="post-nav-older fleft"';
    }
    function baskerculte_pagination_classes_prev() {
        return 'class="post-nav-newer fright"';
    }
    function baskerculte_body_classes( $classes ) {

        // If has post thumbnail
        $classes[] = has_post_thumbnail() ? 'has-featured-image' : 'no-featured-image';

        // If is mobile
        if ( wp_is_mobile() ) {
            $classes[] = 'is_mobile';
        }

        // Replicate single classes on other pages
        if ( is_singular() || is_404() ) {
            $classes[] = 'single single-post';
        }

        return $classes;

    }
    function baskerculte_custom_excerpt_length( $length ) {
        return 40;
    }
    function baskerculte_new_excerpt_more( $more ) {
        return '... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . __(' Continue Reading', 'baskerculte' ) . ' &rarr;</a>';
    }
    function baskerculte_admin_css() { ?>
        <style type="text/css">   
            #postimagediv #set-post-thumbnail img {
                max-width: 100%;
                height: auto;
            }
        </style>
        <?php 
    }
    function flexslider( $size = 'thumbnail' ) {

        $attachment_parent = is_page() ? $post->ID : get_the_ID();

        $images = get_posts( array(
            'orderby'        	=> 'menu_order',
            'order'          	=> 'ASC',
            'post_mime_type' 	=> 'image',
            'post_parent'   	=> $attachment_parent,
            'post_status'    	=> null,
            'post_type'     	=> 'attachment',
            'posts_per_page'    => -1,
        ) );

        if ( $images ) { ?>

            <div class="flexslider">

                <ul class="slides">

                    <?php 
                    foreach( $images as $image ) :
                        $attimg = wp_get_attachment_image( $image->ID, $size ); ?>

                        <li>
                            <?php echo $attimg; ?>
                            <?php if ( ! empty( $image->post_excerpt ) && is_single() ) : ?>
                                <div class="media-caption-container">
                                    <p class="media-caption"><?php echo $image->post_excerpt; ?></p>
                                </div>
                            <?php endif; ?>
                        </li>

                        <?php 
                    endforeach; 
                    ?>

                </ul>

            </div><!-- .flexslider -->

            <?php

        }
    }

    function body_class($classes){
        if (is_page_template('template-blog.php')){
            if (($key = array_search('single single-post', $classes)) !== false) {
                unset($classes[$key]);
            }

        }elseif (is_page_template('template-portfolio.php')){
           if (($key = array_search('single single-post', $classes)) !== false) {
                unset($classes[$key]);
            }
        }

        return $classes;
    }
    function scripts_styles(){

        wp_register_style('fontAwesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        wp_enqueue_style( 'fontAwesome');

        wp_enqueue_script( 'cc-global', get_stylesheet_directory_uri().'/_inc/js/cc-global.js', array('jquery'));

        //if (is_page('portfolio')){
            wp_enqueue_style('jquery.dropit', get_stylesheet_directory_uri().'/_inc/css/dropit.css');
            wp_enqueue_script( 'jquery.dropit', get_stylesheet_directory_uri().'/_inc/js/dropit.js', array('jquery'),'1.1.0');
        //}
    }
	function baskerculte_load_style() {
		if ( ! is_admin() ) {
			wp_register_style( 'baskerville_googleFonts', '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300' );
			wp_register_style( 'baskerville_style', get_template_directory_uri() . '/_inc/css/baskerville.css' );
            wp_register_style( 'cargoculte_style', get_template_directory_uri() . '/_inc/css/cargoculte.css' );
			
			wp_enqueue_style( 'baskerville_googleFonts' );
			wp_enqueue_style( 'baskerville_style' );
            wp_enqueue_style( 'cargoculte_style' );
		}
	}
    function remove_blog_share(){
        if (is_page('blog')){ //blog
            remove_filter( 'the_content', 'sharing_display',19 );
            remove_filter( 'the_excerpt', 'sharing_display',19 );
            if ( class_exists( 'Jetpack_Likes' ) ) {
                remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
            }

        }
    }
    function gallery($atts){ //hijack the gallery shortcode to add default attributes
        if (!isset($atts['type'])){
            $atts['type'] = 'slideshow';
        }
        if (!isset($atts['link'])){
            $atts['link'] = 'file';
        }
        return gallery_shortcode( $atts );
    }
    function cv_no_autopop(){
        if (!is_page('2967')) return;
        remove_filter('the_content', 'wpautop');
    }
    
	function baskerculte_setup() {
		
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
			
		// Post thumbnails
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'post-image', 945, 9999 );
		add_image_size( 'post-thumbnail', 600, 9999 );
        add_image_size( 'post-thumbnail-portfolio', 400, 225, true );
		
		// Post formats
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

		// Add support for title_tag
		add_theme_support( 'title-tag' );

		// Set content width
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 676;
			
		// Add support for custom background
		$args = array(
			'default-color'	=> '#f1f1f1'
		);
		add_theme_support( "custom-background", $args );

		// Add nav menu
		register_nav_menu( 'primary', 'Primary Menu' );
		
		// Make the theme translation ready
		load_theme_textdomain('baskerculte', get_template_directory() . '/languages');
		
		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable($locale_file) )
		require_once($locale_file);
		
	}
	function baskerculte_load_javascript_files() {

		if ( ! is_admin() ) {
			wp_register_script( 'baskerculte_imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.js', '', true );
			wp_register_script( 'baskerculte_flexslider', get_template_directory_uri() . '/js/flexslider.min.js', '', true );

			wp_enqueue_script( 'baskerculte_global', get_template_directory_uri() . '/js/global.js', array( 'jquery', 'masonry', 'baskerculte_imagesloaded', 'baskerculte_flexslider' ), '', true );

			if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
		}
	}

	function baskerculte_add_editor_styles() {
		add_editor_style( 'baskerculte-editor-style.css' );
		$font_url = '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300';
		add_editor_style( str_replace( ',', '%2C', $font_url ) );
	}
	function baskerculte_sidebar_registration() {

		register_sidebar( array(
			'name' 			=> __( 'Footer A', 'baskerculte' ),
			'id' 			=> 'footer-a',
			'description' 	=> __( 'Widgets in this area will be shown in the left column in the footer.', 'baskerculte' ),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div><div class="clear"></div></div>'
		) );

		register_sidebar( array(
			'name' 			=> __( 'Footer B', 'baskerculte' ),
			'id' 			=> 'footer-b',
			'description' 	=> __( 'Widgets in this area will be shown in the middle column in the footer.', 'baskerculte' ),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div><div class="clear"></div></div>'
		) );

		register_sidebar( array(
			'name' 			=> __( 'Footer C', 'baskerculte' ),
			'id' 			=> 'footer-c',
			'description' 	=> __( 'Widgets in this area will be shown in the right column in the footer.', 'baskerculte' ),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div><div class="clear"></div></div>'
		) );

		register_sidebar( array(
			'name' 			=> __( 'Sidebar 1', 'baskerculte' ),
			'id'			=> 'sidebar-1',
			'description' 	=> __( 'Widgets in this area will be shown in the sidebar.', 'baskerculte' ),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div><div class="clear"></div></div>'
		) );
        
		register_sidebar( array(
			'name' 			=> __( 'Sidebar 2', 'baskerculte' ),
			'id'			=> 'sidebar-2',
			'description' 	=> __( 'Widgets in this area will be shown in the sidebar.', 'baskerculte' ),
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div><div class="clear"></div></div>'
		) );

	}
}

/* ---------------------------------------------------------------------------------------------
   CUSTOM NAV WALKER WITH HAS-CHILDREN CLASS
   --------------------------------------------------------------------------------------------- */


class baskerculte_nav_walker extends Walker_Nav_Menu {
    function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( ! empty( $children_elements[$element->$id_field] ) ) {
            $element->classes[] = 'has-children';
        }
        Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
}


/* ---------------------------------------------------------------------------------------------
   BASKERVILLE META FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'baskerculte_meta' ) ) {

	function baskerculte_meta() { ?>

		<div class="post-meta">
		
			<a class="post-date" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( 'Y/m/d' ); ?></a>
			
			<?php
			
			if ( function_exists( 'zilla_likes' ) ) zilla_likes(); 
		
			if ( comments_open() ) {
				comments_popup_link( '0', '1', '%', 'post-comments' );
			}
			
			edit_post_link(); 
			
			?>
			
			<div class="clear"></div>
		
		</div><!-- .post-meta -->
		
	<?php
	}

}


/* ---------------------------------------------------------------------------------------------
   BASKERVILLE COMMENT FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'baskerculte_comment' ) ) {

	function baskerculte_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		
			<?php __( 'Pingback:', 'baskerculte' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'baskerculte' ), '<span class="edit-link">', '</span>' ); ?>
			
		</li>
		<?php
				break;
			default :
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		
			<div id="comment-<?php comment_ID(); ?>" class="comment">
			
				<?php echo get_avatar( $comment, 80 ); ?>
			
				<div class="comment-inner">

					<div class="comment-header">
												
						<?php printf( '<cite class="fn">%1$s</cite>',
							get_comment_author_link()
						); ?>
						
						<p><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(  _x( '%s at %s', '[date] at [time of day]', 'baskerculte' ), get_comment_date(), get_comment_time() ); ?></a></p>
						
						<div class="comment-actions">
						
							<?php edit_comment_link( __( 'Edit', 'baskerculte' ), '', '' ); ?>
							
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'baskerculte' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							
							<div class="clear"></div>
						
						</div><!-- .comment-actions -->
						
					</div><!-- .comment-header -->

					<div class="comment-content">
					
						<?php if ( '0' == $comment->comment_approved ) : ?>
						
							<p class="comment-awaiting-moderation"><?php _e( 'Awaiting moderation', 'baskerculte' ); ?></p>
							
						<?php endif; ?>
					
						<?php comment_text(); ?>
						
					</div><!-- .comment-content -->
					
					<div class="comment-actions-below hidden">
						
						<?php edit_comment_link( __( 'Edit', 'baskerculte' ), '', '' ); ?>
						
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'baskerculte' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						
						<div class="clear"></div>
					
					</div><!-- .comment-actions -->
					
				</div><!-- .comment-inner -->

			</div><!-- .comment-## -->
		<?php
			break;
		endswitch;
	}
}


/* ---------------------------------------------------------------------------------------------
   BASKERVILLE THEME OPTIONS
   --------------------------------------------------------------------------------------------- */


class baskerculte_Customize {

   public static function register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'baskerculte_options', 
         array(
            'title' => __( 'Baskerculte Options', 'baskerculte' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize some settings for Baskerculte.', 'baskerculte'), //Descriptive tooltip
         ) 
      );
      
      $wp_customize->add_section( 'baskerculte_logo_section' , array(
		    'title'       => __( 'Logo', 'baskerculte' ),
		    'priority'    => 40,
		    'description' => 'Upload a logo to replace the default site name and description in the header',
		) );
      
      //2. Register new settings to the WP database...      
      $wp_customize->add_setting( 'baskerculte_logo', 
      	array( 
      		'sanitize_callback' => 'esc_url_raw'
      	) 
      );
                  
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'baskerculte_logo', array(
		    'label'    => __( 'Logo', 'baskerculte' ),
		    'section'  => 'baskerculte_logo_section',
		    'settings' => 'baskerculte_logo',
		) ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }
   
   public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) echo $return;
      }
      return $return;
    }
}

/**
 * The main function responsible for returning the one true Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return The one true Instance
 */

function baskerculte() {
	return Baskerculte::instance();
}

baskerculte();


?>