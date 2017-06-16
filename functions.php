<?php
/**
 * Spacious functions related to defining constants, adding files and WordPress core functionality.
 *
 * Defining some constants, loading all the required files and Adding some core functionality.
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menu() To add support for navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750;

/**
 * $content_width global variable adjustment as per layout option.
 */
function spacious_content_width() {
   global $post;
   global $content_width;

   if( $post ) { $layout_meta = get_post_meta( $post->ID, 'spacious_page_layout', true ); }
   if( empty( $layout_meta ) || is_archive() || is_search() ) { $layout_meta = 'default_layout'; }
   $spacious_default_layout = spacious_options( 'default_layout', 'right_sidebar' );

   if ( $layout_meta == 'default_layout' ) {
      if ( ( spacious_options( 'spacious_site_layout', 'box_1218px' ) == 'box_978px' ) || ( spacious_options( 'spacious_site_layout', 'box_1218px' ) == 'wide_978px' ) ) {
         if ( $spacious_default_layout == 'no_sidebar_full_width' ) { $content_width = 978; /* pixels */ }
         else { $content_width = 642; /* pixels */ }
      }
      elseif ( $spacious_default_layout == 'no_sidebar_full_width' ) { $content_width = 1218; /* pixels */ }
      else { $content_width = 750; /* pixels */ }
   }
   elseif ( ( spacious_options( 'spacious_site_layout', 'box_1218px' ) == 'box_978px' ) || ( spacious_options( 'spacious_site_layout', 'box_1218px' ) == 'wide_978px' ) ) {
      if ( $layout_meta == 'no_sidebar_full_width' ) { $content_width = 978; /* pixels */ }
      else { $content_width = 642; /* pixels */ }
   }
   elseif ( $layout_meta == 'no_sidebar_full_width' ) { $content_width = 1218; /* pixels */ }
   else { $content_width = 750; /* pixels */ }
}
add_action( 'template_redirect', 'spacious_content_width' );

add_action( 'after_setup_theme', 'spacious_setup' );
/**
 * All setup functionalities.
 *
 * @since 1.0
 */
if( !function_exists( 'spacious_setup' ) ) :
function spacious_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'spacious', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
	add_theme_support( 'post-thumbnails' );

   // Supporting title tag via add_theme_support (since WordPress 4.1)
   add_theme_support( 'title-tag' );

   // Adds the support for the Custom Logo introduced in WordPress 4.5
   add_theme_support( 'custom-logo',
   		array(
   			'height' => '100',
   			'width' => '100',
   			'flex-width' => true,
   			'flex-height' => true,
   		)
   	);

	// Registering navigation menus.
	register_nav_menus( array(
		'primary' 	=> __( 'Primary Menu','spacious' ),
		'footer' 	=> __( 'Footer Menu','spacious' )
	) );

	// Cropping the images to different sizes to be used in the theme
	add_image_size( 'featured-blog-large', 750, 350, true );
	add_image_size( 'featured-blog-medium', 270, 270, true );
	add_image_size( 'featured', 642, 300, true );
	add_image_size( 'featured-blog-medium-small', 230, 230, true );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'spacious_custom_background_args', array(
		'default-color' => 'eaeaea'
	) ) );

	// Adding excerpt option box for pages as well
	add_post_type_support( 'page', 'excerpt' );

	/**
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	));

	// Support for selective refresh widgets in Customizer
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			// Place three core-defined widgets in the sidebar area.
			'spacious_header_sidebar' => array(
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'spacious_footer_sidebar_one' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'spacious_footer_sidebar_two' => array(
				'search',
			),

			// Put two core-defined widgets in the footer 2 area.
			'spacious_footer_sidebar_three' => array(
				'text_about',
			),

			// Put two core-defined widgets in the footer 2 area.
			'spacious_footer_sidebar_four' => array(
				'search',
				'text_about',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'home',
			'about' => array(
				'thumbnail' => '{{image-spacious1}}',
			),
			'contact' => array(
				'thumbnail' => '{{image-spacious2}}',
			),
			'blog' => array(
				'thumbnail' => '{{image-spacious3}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-spacious1' => array(
				'post_title' => _x( 'about', 'Theme starter content', 'spacious' ),
				'file' => 'images/hero1.jpg', // URL relative to the template directory.
			),
			'image-spacious2' => array(
				'post_title' => _x( 'contact', 'Theme starter content', 'spacious' ),
				'file' => 'images/hero2.jpg',
			),
			'image-spacious3' => array(
				'post_title' => _x( 'blog', 'Theme starter content', 'spacious' ),
				'file' => 'images/hero3.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'primary' => array(
				'name' => __( 'Primary Menu', 'spacious' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'footer' => array(
				'name' => __( 'Footer Menu', 'spacious' ),
				'items' => array(
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'spacious_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );

}
endif;

/**
 * Define Directory Location Constants
 */
define( 'SPACIOUS_PARENT_DIR', get_template_directory() );
define( 'SPACIOUS_CHILD_DIR', get_stylesheet_directory() );

define( 'SPACIOUS_INCLUDES_DIR', SPACIOUS_PARENT_DIR. '/inc' );
define( 'SPACIOUS_CSS_DIR', SPACIOUS_PARENT_DIR . '/css' );
define( 'SPACIOUS_JS_DIR', SPACIOUS_PARENT_DIR . '/js' );
define( 'SPACIOUS_LANGUAGES_DIR', SPACIOUS_PARENT_DIR . '/languages' );

define( 'SPACIOUS_ADMIN_DIR', SPACIOUS_INCLUDES_DIR . '/admin' );
define( 'SPACIOUS_WIDGETS_DIR', SPACIOUS_INCLUDES_DIR . '/widgets' );

define( 'SPACIOUS_ADMIN_IMAGES_DIR', SPACIOUS_ADMIN_DIR . '/images' );
define( 'SPACIOUS_ADMIN_CSS_DIR', SPACIOUS_ADMIN_DIR . '/css' );


/**
 * Define URL Location Constants
 */
define( 'SPACIOUS_PARENT_URL', get_template_directory_uri() );
define( 'SPACIOUS_CHILD_URL', get_stylesheet_directory_uri() );

define( 'SPACIOUS_INCLUDES_URL', SPACIOUS_PARENT_URL. '/inc' );
define( 'SPACIOUS_CSS_URL', SPACIOUS_PARENT_URL . '/css' );
define( 'SPACIOUS_JS_URL', SPACIOUS_PARENT_URL . '/js' );
define( 'SPACIOUS_LANGUAGES_URL', SPACIOUS_PARENT_URL . '/languages' );

define( 'SPACIOUS_ADMIN_URL', SPACIOUS_INCLUDES_URL . '/admin' );
define( 'SPACIOUS_WIDGETS_URL', SPACIOUS_INCLUDES_URL . '/widgets' );

define( 'SPACIOUS_ADMIN_IMAGES_URL', SPACIOUS_ADMIN_URL . '/images' );
define( 'SPACIOUS_ADMIN_CSS_URL', SPACIOUS_ADMIN_URL . '/css' );

/** Load functions */
require_once( SPACIOUS_INCLUDES_DIR . '/custom-header.php' );
require_once( SPACIOUS_INCLUDES_DIR . '/functions.php' );
require_once( SPACIOUS_INCLUDES_DIR . '/customizer.php' );
require_once( SPACIOUS_INCLUDES_DIR . '/header-functions.php' );

require_once( SPACIOUS_ADMIN_DIR . '/meta-boxes.php' );

/** Load Widgets and Widgetized Area */
require_once( SPACIOUS_WIDGETS_DIR . '/widgets.php' );

/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Load Demo Importer Configs.
 */
if ( class_exists( 'TG_Demo_Importer' ) ) {
	require get_template_directory() . '/inc/demo-config.php';
}

/**
 * Assign the Spacious version to a variable.
 */
$theme            = wp_get_theme( 'spacious' );
$spacious_version = $theme['Version'];

/* Calling in the admin area for the Welcome Page */
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin/class-spacious-admin.php';
}
/**
* Load TGMPA Configs.
*/
require_once( SPACIOUS_INCLUDES_DIR . '/tgm-plugin-activation/class-tgm-plugin-activation.php' );
require_once( SPACIOUS_INCLUDES_DIR . '/tgm-plugin-activation/tgmpa-spacious.php' );
