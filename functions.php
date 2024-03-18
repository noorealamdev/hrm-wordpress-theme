<?php
/**
 * Hrm functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hrm
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function hrm_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Hrm, use a find and replace
		* to change 'hrm' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'cm-hrm', get_template_directory() . '/languages' );

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

	// This theme uses wp_nav_menu().
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'cm-hrm' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'hrm_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'hrm_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function hrm_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hrm_content_width', 640 );
}
add_action( 'after_setup_theme', 'hrm_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hrm_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'hrm' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'hrm' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hrm_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cm_hrm_scripts() {
	wp_enqueue_style( 'cm-hrm-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style('cm-hrm-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), _S_VERSION);
    wp_enqueue_style( 'cm-hrm-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-dataTables-bs5', get_template_directory_uri() . '/assets/css/dataTables-bs5.min.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-remixicon', get_template_directory_uri() . '/assets/css/remixicon.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-meanmenu', get_template_directory_uri() . '/assets/css/meanmenu.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-animate', get_template_directory_uri() . '/assets/css/animate.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-select2', get_template_directory_uri() . '/assets/css/select2.min.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-sweetalert', get_template_directory_uri() . '/assets/css/sweetalert.min.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-toastr', get_template_directory_uri() . '/assets/css/toastr.min.css', array(), _S_VERSION );
    wp_enqueue_style( 'cm-hrm-main', get_template_directory_uri() . '/assets/css/main.css', array(), _S_VERSION );


    wp_enqueue_script('jquery');
	wp_enqueue_script( 'cm-hrm-bootstrap-bundle', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-meanmenu', get_template_directory_uri() . '/assets/js/jquery.meanmenu.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-nice-select', get_template_directory_uri() . '/assets/js/jquery.nice-select.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-sweetalert', get_template_directory_uri() . '/assets/js/sweetalert.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-select2', get_template_directory_uri() . '/assets/js/select2.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-toastr', get_template_directory_uri() . '/assets/js/toastr.min.js', array(), _S_VERSION, false );
	wp_enqueue_script( 'cm-hrm-feather', get_template_directory_uri() . '/assets/js/feather.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-dataTables', get_template_directory_uri() . '/assets/js/dataTables.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'cm-hrm-dataTables-bs5', get_template_directory_uri() . '/assets/js/dataTables-bs5.js', array(), _S_VERSION, true );
	//wp_enqueue_script( 'cm-hrm-apexcharts', get_template_directory_uri() . '/assets/js/apexcharts.js', array(), _S_VERSION, false );

	wp_enqueue_script( 'cm-hrm-main', get_template_directory_uri() . '/assets/js/main.js', array(), _S_VERSION, true );

	wp_localize_script( 'cm-hrm-main', 'localize',
		array(
			'_ajax_url' => admin_url( 'admin-ajax.php' ),
			'_ajax_nonce' => wp_create_nonce( '_ajax_nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cm_hrm_scripts' );

/**
 * Fixing shortcode.
 */
add_action('template_redirect', function () {
	ob_start();
});

/**
 * Load Composer’s autoloader.
 */
require_once ( get_template_directory() .'/vendor/autoload.php');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Kirki Customizer Framework
 */
require get_template_directory() . '/vendor/kirki/kirki.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Kirki customizer options.
 */
require get_template_directory() . '/inc/kirki-customizer.php';

/**
 * Loading all custom classes.
 */
require get_template_directory() . '/inc/classes/Utils.php';
require get_template_directory() . '/inc/classes/DatabaseQuery.php';
require get_template_directory() . '/inc/classes/Templates.php';
require get_template_directory() . '/inc/classes/Dashboard.php';
require get_template_directory() . '/inc/classes/Company.php';
require get_template_directory() . '/inc/classes/Department.php';
require get_template_directory() . '/inc/classes/Designation.php';
require get_template_directory() . '/inc/classes/Employee.php';
require get_template_directory() . '/inc/classes/Client.php';
require get_template_directory() . '/inc/classes/Project.php';
require get_template_directory() . '/inc/classes/Invoice.php';


