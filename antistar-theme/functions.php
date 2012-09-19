<?php

// Default constants
define('HOMEPAGE', esc_url( home_url( '/' ) ));
define('THEME_URL', get_template_directory_uri());
define('THEME_PATH', get_template_directory());

// load the theme's translated strings
load_theme_textdomain('antistar', THEME_PATH.'/languages');

// get default options (FRONT-END)
$opts = get_option('sc_antistar_theme_options');

// Initialization
require THEME_PATH . '/core/config.php'; 
require THEME_PATH . '/core/functions.php';

// include admin functions
if(is_admin()){
	require THEME_PATH . '/core/admin.php';
	
	// Build Menu
	add_action('admin_menu', 'sc_admin_menu');
	add_action( 'after_setup_theme', 'sc_theme_setup' );
	add_action('admin_init', 'sc_antistar_options_init' );
}

// hide admin-bar
add_filter('show_admin_bar', '__return_false');

// Remove gallery style
add_filter('gallery_style', create_function('$a', 'return "
<div class=\'gallery\'>";'));

// Ajax Requests
add_action( 'wp_ajax_nopriv_sc_ajax_callback', 'sc_ajax_callback' );
add_action( 'wp_ajax_sc_ajax_callback', 'sc_ajax_callback' );

// set excerpt length
add_filter( 'excerpt_length', 'sc_set_excerpt_len' );


// Register navigations, sidebar(s) and widgetized areas
add_action( 'init', 'sc_antistar_init' );
add_action( 'widgets_init', 'sc_antistar_widgets_init' );


/**
* Customize theme
*/
// Activate Post formats
add_theme_support('post-formats', array( 'image', 'video' ) );
add_theme_support('post-thumbnails'); // Activate Featured Images


// Thumbnail sizes for theme
add_image_size( 'icon', 120, 120, true );
add_image_size( 'featured-img', 420, 260, true ); // for homepage
add_image_size( 'featured-img-crop', 420, 260, true ); // for details page
add_image_size( 'featured-img-nocrop', 420 );
add_image_size( 'cell', 180 ); // for multi layout lists
add_image_size( 'slider-img', 700, $opts['sc_slider_image_h'], true ); // for slider images


/**
* Options box init
* @since 1.0
*/
// Add options box
add_action( 'add_meta_boxes', 'sc_add_opts_box' );
add_action( 'save_post', 'sc_save_post' );

?>