<?php

/**
 * Child Theme Function
 *
 */

add_action('after_setup_theme', 'veres_child_theme_setup');
add_action('wp_enqueue_scripts', 'veres_child_enqueue_styles', 100);

if (!function_exists('veres_child_enqueue_styles')) {
    function veres_child_enqueue_styles()
    {
        $version = wp_get_theme()->get('Version');
        wp_enqueue_style('veres-child-style', get_stylesheet_directory_uri() . '/style.css', null, $version);
    }
}

if (!function_exists('veres_child_theme_setup')) {
    function veres_child_theme_setup()
    {
        load_child_theme_textdomain('veres-child', get_stylesheet_directory() . '/languages');
    }
}


/*
 * Define Variables
 */
if (!defined('THEME_DIR'))
    define('THEME_DIR', get_template_directory());
if (!defined('THEME_URL'))
    define('THEME_URL', get_template_directory_uri());

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/*
 * Include framework files
 */
foreach (glob(THEME_DIR . '-child' . "/includes/*.php") as $file_name) {
    require_once($file_name);
}
