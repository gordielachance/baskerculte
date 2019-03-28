<?php

class Gordo{
    
    /** Version ***************************************************************/
    /**
    * @public string plugin version
    */
    public $version = '1.0.0';
    /**
    * @public string plugin DB version
    */
    public $db_version = '100';
    /*******************************************************************/
    
    public $post_format_excerpts = array();
    
	/**
	 * @var The one true Instance
	 */
	private static $instance;
    
    public $options_default;
    public $options;

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
			self::$instance = new Gordo;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}
    
    function __construct(){ 
    }
    
    function setup_globals(){
        $this->options_default = array(
            'has_archives_menu'     =>  true,
            'has_sidebar_header'    => false,
            'pagination_mode'       => false,
        );
        
        $db_options = array();
        $db_options['has_archives_menu'] = get_theme_mod('gordo_archives_filter', $this->options_default['has_archives_menu']);
        $db_options['has_sidebar_header'] = get_theme_mod('gordo_sidebar_header', $this->options_default['has_sidebar_header']);
        $db_options['pagination_mode'] = get_theme_mod('gordo_pagination_mode', $this->options_default['pagination_mode']);

        $this->options = wp_parse_args($db_options, $this->options_default);
        
    }
    
    function includes(){
    }
    
    function setup_actions(){
        
        //customizer
        new gordo_customizer();
        
        //gordo
        add_action( 'after_setup_theme', array($this,'gordo_setup') );
        add_action( 'wp_head', array($this,'inline_scripts_styles'), 1 );
        add_action( 'wp_enqueue_scripts', array($this,'scripts_styles'), 9 );
        add_action( 'admin_enqueue_scripts', array($this,'admin_scripts_styles'), 9 );
        add_action( 'widgets_init', array($this,'register_sidebars') );
        add_action( 'admin_head', array($this,'gordo_admin_css') );
        add_filter( 'excerpt_more', array($this,'excerpt_more_text') );
        add_action( 'body_class', array($this,'gordo_body_classes') );
        
        //add_filter( 'previous_posts_link_attributes', array($this,'gordo_pagination_classes_prev') );
        //add_filter( 'next_posts_link_attributes', array($this,'gordo_pagination_classes_next') );
        
        //custom
        add_action( 'loop_start', array($this,'remove_blog_share') );
        
        
        add_filter( 'pre_option_link_manager_enabled', '__return_true' ); //enable links manager

        add_filter('the_excerpt', 'do_shortcode'); //enable shortcodes in excerpts
        add_filter('the_excerpt', array($GLOBALS['wp_embed'], 'autoembed')); //enable oEmbed in excerpts
        
        add_action( 'gordo_after_archive_title', 'gordo_post_archive_menu' );

    }
    
    function get_options($keys = null){
        return gordo_get_array_value($keys,$this->options);
    }
    
    public function get_default_option($keys = null){
        return gordo_get_array_value($keys,$this->options_default);
    }

    function inline_scripts_styles(){
        /*remove .no-js class*/
        echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
    }
    
    function gordo_pagination_classes_next() {
        return 'class="post-nav-older fleft"';
    }
    function gordo_pagination_classes_prev() {
        return 'class="post-nav-newer fright"';
    }
    function gordo_body_classes( $classes ) {

        $classes[] = 'bg-white';
        $classes[] = has_header_image() ? 'has-header-image' : null;
        $classes[] = has_post_thumbnail() ? 'has-featured-image' : 'no-featured-image'; // If has post thumbnail
        $classes[] = ( gordo()->get_options('has_sidebar_header') ) ? 'gordo-sidebar-header' : null; //sidebar header ?
        

        // If is mobile //TOFIX TOCHECK USEFUL ?
        if ( wp_is_mobile() ) {
            $classes[] = 'is_mobile';
        }

        // Replicate single classes on other pages
        if ( is_singular() || is_404() ) {
            $classes[] = 'single single-post';
        }
        
        if ( is_page_template('template-bookmarks.php') ){
            if (($key = array_search('single single-post', $classes)) !== false) {
                unset($classes[$key]);
            }
        }

        return $classes;

    }

    function excerpt_more_text( $more ) {
        return '... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . __(' Continue Reading', 'gordo' ) . ' &rarr;</a>';
    }
    function gordo_admin_css() { ?>
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
    
    function get_custom_styles() {
        ?>
        a{
            color:<?php echo get_theme_mod( 'color-links','#13C4A5' ); ?>;
        }
        
        <?php
        return ob_get_clean();
    }
    
    /*
    Frontend Scripts / Styles
    */
    function scripts_styles(){

        //styles
        wp_register_style('fontAwesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_register_style( 'gordoGoogleFonts', '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300|Coming+Soon' );
        wp_register_style( 'gordo', get_template_directory_uri() . '/_inc/css/gordo.css',array('fontAwesome','gordoGoogleFonts'),$this->version );
        wp_enqueue_style( 'gordo' );
        //wp_add_inline_style( 'gordo', $this->get_custom_styles() );
        
        
        //scripts
        wp_register_script( 'imagesloaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js', '4.1.4', true );
        wp_register_script( 'flexslider', '//cdnjs.cloudflare.com/ajax/libs/flexslider/2.6.4/jquery.flexslider.min.js', '2.6.4', true );
        
        wp_register_script( 'gordo.ExpandableMenu',get_template_directory_uri() . '/_inc/js/gordo.ExpandableMenu.js', array('jquery'),'1.0.0' );
        wp_enqueue_script( 'gordo', get_template_directory_uri() . '/_inc/js/gordo.js', array( 'jquery', 'jquery-masonry', 'imagesloaded', 'flexslider',  'gordo.ExpandableMenu' ),$this->version, true );

        if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

    }
    
    /*
    Backend Scripts / Styles
    */
    function admin_scripts_styles(){
        
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
    
	function gordo_setup() {
        
        /*
        custom TinyMCE editor stylesheets
        */
        
		add_editor_style( '_inc/css/editor-style.css' );
		$font_url = '//fonts.googleapis.com/css?family=Roboto+Slab:400,700|Roboto:400,400italic,700,700italic,300';
		add_editor_style( str_replace( ',', '%2C', $font_url ) );
        
        /*HTML5*/
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		
		/*
        Automatic feed
        */
		add_theme_support( 'automatic-feed-links' );
			
		/*
        Post thumbnails
        */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'post-image', 945, 9999 );
		add_image_size( 'post-thumbnail', 600, 9999 );
        add_image_size( 'post-thumbnail-portfolio', 400, 225, true );
		
		/*Post formats
        */
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

		/*
        Add support for title_tag
		add_theme_support( 'title-tag' );
        */

		/*
        Content width
        */
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 676;
			
		/*
        Custom background
        
        It is too complex / too restrictive to handle color schemes using the WP functions.
        We'll use strict CSS instead.
        
        $bg_args = array(
            'default-color' =>      'f3f1e6',
        );
        
        add_theme_support( 'custom-background', $bg_args );
        */
        
		/*
        Custom header image
        */
        
        $header_args = array();
        
        add_theme_support( 'custom-header', $header_args );
        
		/*
        Custom logo
        */
        
        $logo_args = array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
        'flex-width'  => true
	);
        add_theme_support( 'custom-logo', $logo_args );

		/*
        Nav menus
        */
		register_nav_menu( 'gordo_primary', __('Header Menu','gordo') );
        
        if ( gordo()->get_options('has_archives_menu') ){
            register_nav_menu( 'gordo_archives', __('Archives Menu','gordo'),__('Submenu displayed on the archives pages.','gordo') );
        }
		
		/*
        Translation ready
        */
		load_theme_textdomain('gordo', get_template_directory() . '/languages');
		
		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable($locale_file) )
		require_once($locale_file);
		
	}

	function register_sidebars() {
        
        $defaults = array(
            'name' =>       null,
            'id' =>         null,
			'before_title' 	=> '<h3 class="widget-title">',
			'after_title' 	=> '</h3>',
			'before_widget' => '<div id="widget-%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div></div>'
        );
        
        /*footer*/
        
		register_sidebar( wp_parse_args(array(
			'name' 			=> __( 'Footer A', 'gordo' ),
			'id' 			=> 'footer-a',
			'description' 	=> __( 'Widgets in this area will be shown in the left column in the footer.', 'gordo' )
		),$defaults) );

		register_sidebar( wp_parse_args(array(
			'name' 			=> __( 'Footer B', 'gordo' ),
			'id' 			=> 'footer-b',
			'description' 	=> __( 'Widgets in this area will be shown in the middle column in the footer.', 'gordo' )
		),$defaults) );

		register_sidebar( wp_parse_args(array(
			'name' 			=> __( 'Footer C', 'gordo' ),
			'id' 			=> 'footer-c',
			'description' 	=> __( 'Widgets in this area will be shown in the right column in the footer.', 'gordo' )
		),$defaults) );
        
		register_sidebar( wp_parse_args(array(
			'name' 			=> __( 'Credits', 'gordo' ),
			'id'			=> 'credits',
			'description' 	=> __( 'Widgets in this area will be shown at the very bottom, for credits (eg. use an HTML widget)', 'gordo' )
		),$defaults) );
                         
         /*sidebar*/

		register_sidebar( wp_parse_args(array(
			'name' 			=> __( 'Sidebar 1', 'gordo' ),
			'id'			=> 'single-sidebar-a',
			'description' 	=> __( 'Widgets in this area will be shown in the sidebar.', 'gordo' )
		),$defaults) );

	}
    
    function get_header_styles(){
        $styles = null;
        if ( has_header_image() ){
            $styles = sprintf(' style="background-image:url(\'%s\')"',get_header_image());
        }
        return $styles;
    }
    
    function get_page_bookmarks(){
        $args = array(
        );
        $args = apply_filters('gordo_page_bookmarks_args',$args);
        return get_bookmarks( $args );
    }
    
}

/* ---------------------------------------------------------------------------------------------
   CUSTOM NAV WALKER WITH HAS-CHILDREN CLASS
   --------------------------------------------------------------------------------------------- */


class gordo_nav_walker extends Walker_Nav_Menu {
    function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( ! empty( $children_elements[$element->$id_field] ) ) {
            $element->classes[] = 'has-children';
        }
        Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
}


/* ---------------------------------------------------------------------------------------------
   GORDO META FUNCTION
   --------------------------------------------------------------------------------------------- */


function gordo_get_hentry_metas() {
    global $post;

    $list = array();
    
    $list['post-author'] = sprintf('<a href="%s">%s</a>',get_author_posts_url( get_the_author_meta( 'ID' ) ),get_the_author());
    $list['post-date'] = get_the_time('Y/m/d');

    if ( has_category() ) {
        $list['post-categories'] = get_the_category_list( ', ');
    }
    if ( has_tag() ){
        $list['post-tags'] = get_the_tag_list('',', ');
    }
    if ( comments_open() && get_comments_number() ) {
        $list['post-comments'] = sprintf('<a href="%s">%s</a>',get_comments_link(),get_comments_number());
    }

    if ( current_user_can( 'edit_post', $post->ID ) ){
        $list['post-edit'] = sprintf('<a href="%s">%s</a>',get_edit_post_link(),__( 'Edit This' ));
    }

    $list = apply_filters('gordo_get_hentry_metas',$list,$post);

    if ($list){
        
        foreach($list as $key=>$item){
            $list[$key] = sprintf('<li class="%s">%s</li>',$key,$item);
        }

        return sprintf('<ul class="post-meta">%s</ul>',implode("\n",$list));
    }

}



/* ---------------------------------------------------------------------------------------------
   GORDO COMMENT FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'gordo_comment' ) ) {

	function gordo_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		
			<?php __( 'Pingback:', 'gordo' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'gordo' ), '<span class="edit-link">', '</span>' ); ?>
			
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
						
						<p><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(  _x( '%s at %s', '[date] at [time of day]', 'gordo' ), get_comment_date(), get_comment_time() ); ?></a></p>
						
						<div class="comment-actions">
						
							<?php edit_comment_link( __( 'Edit', 'gordo' ), '', '' ); ?>
							
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'gordo' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							
							
						
						</div><!-- .comment-actions -->
						
					</div><!-- .comment-header -->

					<div class="comment-content">
					
						<?php if ( '0' == $comment->comment_approved ) : ?>
						
							<p class="comment-awaiting-moderation"><?php _e( 'Awaiting moderation', 'gordo' ); ?></p>
							
						<?php endif; ?>
					
						<?php comment_text(); ?>
						
					</div><!-- .comment-content -->
					
					<div class="comment-actions-below hidden">
						
						<?php edit_comment_link( __( 'Edit', 'gordo' ), '', '' ); ?>
						
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'gordo' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						
						
					
					</div><!-- .comment-actions -->
					
				</div><!-- .comment-inner -->

			</div><!-- .comment-## -->
		<?php
			break;
		endswitch;
	}
}


/* ---------------------------------------------------------------------------------------------
   GORDO THEME OPTIONS
   --------------------------------------------------------------------------------------------- */

class gordo_customizer {
	public function __construct() {

		add_action( 'customize_register', array( $this, 'register_customize_sections' ) );

	}

	public function register_customize_sections( $wp_customize ) {
        /*
		 * Add Panels
		 */

		// New panel for "Layout".
		$wp_customize->add_section( 'gordo_extras', array(
			'title'    => __( 'Gordo Extra Settings', 'gordo' ),
			'priority' => 101
		) );

		/*
		 * Add settings to sections.
		 */
		$this->gordo_extras_section( $wp_customize );

	}

	function gordo_extras_section( $wp_customize ) {
        /*

        $wp_customize->add_setting( 'color-bg-page', array(
          'default'   => 'f3f1e6',
          'transport' => 'refresh',
          'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color-bg-page', array(
          'section' => 'colors',
          'label'   => esc_html__( 'Links', 'gordo' ),
        ) ) );
        

		/* Sidebar header */
		$wp_customize->add_setting( 'gordo_sidebar_header', array(
			'default'           => gordo()->get_default_option('has_sidebar_header'),
			'sanitize_callback' => array( $this, 'sanitize_checkbox' )
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gordo_sidebar_header', array(
			'label'       => esc_html__( 'Sidebar header', 'gordo' ),
			'description' => esc_html__( 'Check this if you want a sidebar header instead of a top header.', 'gordo' ),
			'section'     => 'gordo_extras',
			'settings'    => 'gordo_sidebar_header',
			'type'        => 'checkbox',
			'priority'    => 10
		) ) );
        
        /* Pagination mode */
		$wp_customize->add_setting( 'gordo_pagination_mode', array(
			'default'           => gordo()->get_default_option('gordo_pagination_mode'),
			'sanitize_callback' => array( $this, 'sanitize_checkbox' )
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gordo_pagination_mode', array(
			'label'       => esc_html__( 'Pagination mode', 'gordo' ),
			'description' => esc_html__( 'Previous / Next', 'gordo' ),
			'section'     => 'gordo_extras',
			'settings'    => 'gordo_pagination_mode',
			'type'        => 'checkbox',
			'priority'    => 10
		) ) );
        
		/* Archives menu */
		$wp_customize->add_setting( 'gordo_archives_filter', array(
			'default'           => gordo()->get_default_option('has_archives_menu'),
			'sanitize_callback' => array( $this, 'sanitize_checkbox' )
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gordo_archives_filter', array(
			'label'       => esc_html__( 'Archives menu', 'gordo' ),
			'description' => esc_html__( 'Check this if you want a menu to filter your archives.', 'gordo' ),
			'section'     => 'gordo_extras',
			'settings'    => 'gordo_archives_filter',
			'type'        => 'checkbox',
			'priority'    => 10
		) ) );

	}

	/**
	 * Sanitize Checkbox
	 *
	 * Accepts only "true" or "false" as possible values.
	 *
	 * @param $input
	 *
	 * @access public
	 * @since  1.0
	 * @return bool
	 */
	public function sanitize_checkbox( $input ) {
		return ( $input === true ) ? true : false;
	}

}

/**
 * Get a value in a multidimensional array
 * http://stackoverflow.com/questions/1677099/how-to-use-a-string-as-an-array-index-path-to-retrieve-a-value
 * @param type $keys
 * @param type $array
 * @return type
 */
function gordo_get_array_value($keys = null, $array){
    if (!$keys) return $array;
    
    $keys = (array)$keys;
    $first_key = $keys[0];
    if(count($keys) > 1) {
        if ( isset($array[$keys[0]]) ){
            return gordo_get_array_value(array_slice($keys, 1), $array[$keys[0]]);
        }
    }elseif (isset($array[$first_key])){
        return $array[$first_key];
    }
    
    return false;
}

/*
function to convert a user-supplied URL to just the domain name
*/
function gordo_get_domain($url){
    $host = @parse_url($url, PHP_URL_HOST);
    // If the URL can't be parsed, use the original URL
    // Change to "return false" if you don't want that
    if (!$host)
        $host = $url;
    // The "www." prefix isn't really needed if you're just using
    // this to display the domain to the user
    if (substr($host, 0, 4) == "www.")
        $host = substr($host, 4);
    // You might also want to limit the length if screen space is limited
    if (strlen($host) > 50)
        $host = substr($host, 0, 47) . '...';
    return $host;
}

/*
post format icon for posts
*/
function gordo_get_hentry_icon($post_id = null){
    $icon = null;
    $format = get_post_format($post_id);

    switch ($format){
        case 'aside':
            $icon = '<i class="fa fa-sticky-note-o" aria-hidden="true"></i>';
        break;
        case 'chat':
            $icon = '<i class="fa fa-comments-o" aria-hidden="true"></i>';
        break;
        case 'link':
            $icon = '<i class="fa fa-link" aria-hidden="true"></i>';
        break;
        case 'image':
        case 'gallery':
            $icon = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
        break;
        case 'quote':
            $icon = '<i class="fa fa-quote-left" aria-hidden="true"></i>';
        break;
        case 'status':
            $icon = '<i class="fa fa-bolt" aria-hidden="true"></i>';
        break;
        case 'video':
            $icon = '<i class="fa fa-video-camera" aria-hidden="true"></i>';
        break;
        case 'audio':
            $icon = '<i class="fa fa-volume-up" aria-hidden="true"></i>';
        break;
    }

    return apply_filters('gordo_get_hentry_icon',$icon,$post_id);
}

//used for temporary filters
//TO FIX TRY TO AVOID ?
function gordo_set_content_before_more($content){
    global $post;
    // Before the <!--more--> tag
    return $post->gordo_content_before_more;
}

//used for temporary filters
//TO FIX TRY TO AVOID ?
function gordo_set_content_after_more($content){
    global $post;
    // After the <!--more--> tag
    $content = get_post_field( 'post_content', $post->ID ); // Fetch raw post content

    // Get content parts
    $content_parts = get_extended( $content );
    return $content_parts['extended'];
}

/*
Before calling get_sidebar(), allow us to filter the file name.
Eg. 'left' will fetch 'sidebar-left.php'.
*/
function gordo_get_sidebar( $sidebar_name = null ) {
    $sidebar_name = apply_filters('gordo_get_sidebar',$sidebar_name);
    if ( $sidebar_name === false ) return; //We don't want a sidebar
    get_sidebar($sidebar_name);
}
function gordo_get_archive_title(){
    global $wp_query;
    //posts header

    $title = null;
    $subtitle = null;

    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $pagination = ( $paged > 1 ) ? sprintf( __( 'Page %s of %s', 'gordo' ), $paged, $wp_query->max_num_pages ) : null;

    if ( is_search() ){

        $title = __( 'Search results', 'gordo');
        $subtitle = sprintf( '"%s"', get_search_query() );

    }elseif( is_archive() ){
        if ( is_day() ){
            $title = __( 'Date', 'gordo' );
            $subtitle = get_the_date();
        }elseif ( is_month() ){
            $title = __( 'Month', 'gordo' );
            $subtitle = get_the_date('F Y');
        }elseif( is_year() ){
            $title = __( 'Year', 'gordo' );
            $subtitle = get_the_date('Y');
        }elseif( is_category() ){
            $title = __( 'Category', 'gordo' );
            $subtitle = single_cat_title( '', false );
        }elseif( is_tag() ){
            $title = __( 'Tag', 'gordo' );
            $subtitle = single_tag_title( '', false );
        /*
        if ( $tag_description = tag_description() ) {
            echo apply_filters( 'tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>' );
        }
        */

        }elseif( is_author() ){
            $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
            $title = __( 'Author', 'gordo' );
            $subtitle = $curauth->display_name;
        }
    }

    $output = array();

    if ($title){
        $output['title'] = sprintf('<h5>%s</h5>',$title);
    }
    if ($subtitle){
        $output['subtitle'] = sprintf('<h3>%s</h3>',$subtitle);
    }
    if ($pagination){
        $output['pagination'] = sprintf('<h5 class="title-pagination">%s</h5>',$pagination);
    }

    $output_str = implode("\n",$output);
    return sprintf('<p class="gordo-archive-title">%s</p>',$output_str);
}

function gordo_post_archive_menu(){
    global $wp_query;
    
    $show_menu = gordo()->get_options('has_archives_menu');
    
    if ( !$show_menu ) return;
    
    //display based on post types ?
    $allowed_types = array('post');
    $query_post_types = (array)$wp_query->query_vars['post_type'];
    $intersect = array_intersect($allowed_types,$query_post_types);
    if ( empty($intersect) ) return;

    $has_menu = has_nav_menu( 'gordo_archives' );
    
    ?>
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

            if ( $has_menu ) {

                $nav_args = array( 
                    'container' 		=> '', 
                    'items_wrap' 		=> '%3$s',
                    'theme_location' 	=> 'gordo_archives', 
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

            do_action('gordo_post_archive_menu',$has_menu);

            ?>

         </ul><!-- #archives-menu -->
    </div>
    <?php
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

function gordo() {
	return Gordo::instance();
}

gordo();

?>