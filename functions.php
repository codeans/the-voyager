<?php
/**
 * Voyager functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Voyager
 */

if ( ! function_exists( 'thevoyager_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function thevoyager_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Voyager, use a find and replace
	 * to change 'the-voyager' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'the-voyager', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary-left' => esc_html__( 'Primary Left', 'the-voyager' ),
		'primary-right' => esc_html__( 'Primary Right', 'the-voyager' ),
		'mobile' => esc_html__( 'Mobile', 'the-voyager' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'thevoyager_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'thevoyager_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function thevoyager_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'thevoyager_content_width', 640 );
}
add_action( 'after_setup_theme', 'thevoyager_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function thevoyager_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'the-voyager' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'the-voyager' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', 'thevoyager_widgets_init' );



if ( ! function_exists( 'thevoyager_fonts_url' ) ) :
/**
 * Register Google fonts for The Voyager.
 *
 * Create your own thevoyager_fonts_url() function to override in a child theme.
 *
 * @since Voyager 1.0.25
 *
 * @return string Google fonts URL for the theme.
 */
function thevoyager_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'cyrillic';
	
	/* translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'. Do not translate into your own language. */

	$fonts[] = 'Raleway:400,400italic,700,700italic';

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}
	return $fonts_url;
}
endif;


/**
 * Enqueue scripts and styles.
 */
function thevoyager_scripts() {
	wp_enqueue_style( 'thevoyager-style', get_stylesheet_uri() );

	wp_enqueue_style( 'gumby', get_template_directory_uri() . '/css/gumby.css', array(), '20151215', false );
	
	wp_enqueue_style( 'tailwind-styles', get_template_directory_uri() . '/dist/styles.css', array(), '20151215', false );

	wp_enqueue_style( 'thevoyager-fonts', thevoyager_fonts_url(), array(), null);  
	
	wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', array('jquery'), '20151215', true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'thevoyager_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


add_filter( 'wp_nav_menu_objects', function(array $sorted_menu_items, stdClass $args ){
	if($args->theme_location !== 'primary-left'){
		return  $sorted_menu_items;
	}

	$processedMenuItems = array_map(function($menuItem){
		// var_dump($menuItem);
		$menuItem->classes[] = 'block';
		$menuItem->classes[] = 'pb-0';
		$menuItem->classes[] =  'relative';
		$menuItem->classes[] =  'lg:mx-0'; 

		if($menuItem->post_parent === 0){
			$menuItem->classes[] = 'lg:inline-flex';
			$menuItem->classes[] = 'lg:mx-6';
			$menuItem->classes[] =  'lg:ml-0'; 

		}
		return $menuItem;

	}, $sorted_menu_items);

	return $processedMenuItems;
}, 10, 2);


add_filter( 'nav_menu_link_attributes', function($atts, $menu_item, $args, $depth){
	if($args->theme_location !== 'primary-left'){
		return  $atts;
	}
	$menuItemHasChild = null;
	$result = array_search('menu-item-has-children', $menu_item->classes);
	if($result){
		$menuItemHasChild = 'menu-item-has-submenu';
	}
	$atts['class'] =  $menuItemHasChild . ' flex justify-between px-[30px] py-3.5 hover:bg-bg-4 hover:text-color-textDark lg:hover:text-bg-2 lg:hover:bg-transparent lg:border-t-[2px] lg:border-bg-2/0  lg:inline-flex';
	
	if($atts['aria-current'] === 'page'){
		$atts['class'] .= ' text-bg-2 border-bg-2/100';
	}

	if($depth !== 0){
		$atts['class'] .= ' lg:w-full px-[30px] lg:hover:border-bg-2/0';
	}

	if($depth === 0){
		$atts['class'] .= ' lg:hover:border-bg-2/100';
	}
	return $atts;
}, 10, 4);


add_filter( 'nav_menu_submenu_css_class', function($classes, $args, $depth){
	if( $args->theme_location === 'primary-left' ){
		$classes[] = 'hidden lg:absolute lg:top-[54px] flex-col lg:bg-bg-1 lg:w-72';
	}

	if($depth !== 0){
		$classes[] = 'lg:pl-4';
	}
	return $classes;

}, 10, 3 );