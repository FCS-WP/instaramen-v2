<?php
// add_action('wp_enqueue_scripts', 'shin_scripts');
// function shin_scripts()
// {
//     $version = '2.2.0';

//     // Load CSS
//     wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/main/main.css', array(), $version, 'all');
//     // Load JS
//     wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/main/main.js', array('jquery'), $version, true);
// }


add_action('wp_enqueue_scripts', 'shin_scripts');
function shin_scripts()
{
  $version = time();

  wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/dist/css/main.min.css', array(), $version, 'all');

  wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/dist/js/main.min.js', array('jquery'), $version, true);
}

