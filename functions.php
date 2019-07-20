<?php


function load_stylesheets(){

	wp_register_style('stylesheet', get_template_directory_uri() . './css/bootstrap.min.css',
		array(), false, 'all');
	wp_enqueue_style('stylesheet');

	wp_register_style('style', get_template_directory_uri() . './style.css',
		array(), false, 'all');
	wp_enqueue_style('style');
}

function loadjs(){

	wp_register_script('bootstrap', get_template_directory_uri() . './js/bootstrap.bundle.js');
	wp_enqueue_script('bootstrap');

//	wp_register_script('customjs', get_template_directory_uri() . './js/scripts.js', '', 1, true);
	wp_register_script('customjs', get_template_directory_uri() . './js/script.js');
	wp_enqueue_script('customjs');
}

function include_jquery(){

	wp_deregister_script('jquery');

	wp_register_script('jquery', get_template_directory_uri() . './js/jquery-3.4.1.min.js');
	wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'include_jquery');
add_action('wp_enqueue_scripts', 'load_stylesheets');
add_action('wp_enqueue_scripts', 'loadjs');

add_theme_support('menus');

register_nav_menus(array(
	'top-menu'      => __('Top Menu', 'theme'),
	'footer-menu'   => __('Footer Menu', 'theme'),
));