<?php
/**
 * This file includes helper functions used throughout the theme.
 *
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if(!function_exists('veres_add_meta_into_head_tag')){
    function veres_add_meta_into_head_tag(){
        do_action('veres/action/head');
    }
}

/**
 * Add classes to the body tag
 *
 * @since 1.0.0
 */
if (!function_exists('veres_body_classes')) {
    function veres_body_classes($classes) {
        $classes[] = is_rtl() ? 'rtl' : 'ltr';
        $classes[] = 'veres-body';
        $classes[] = 'lastudio-veres';

        $sidebar = apply_filters('veres/filter/sidebar_primary_name', 'sidebar');

        if(!is_active_sidebar($sidebar) || is_page_template(['templates/no-sidebar.php', 'templates/fullwidth.php'])){
            $classes[] = 'site-no-sidebar';
        }
        elseif ( is_active_sidebar($sidebar) ){
	        $classes[] = 'site-has-sidebar';
        }

        if (is_singular('page')) {
            global $post;
            if (strpos($post->post_content, 'la_wishlist') !== false) {
                $classes[] = 'woocommerce-page';
                $classes[] = 'woocommerce-page-wishlist';
            }
            if (strpos($post->post_content, 'la_compare') !== false) {
                $classes[] = 'woocommerce-page';
                $classes[] = 'woocommerce-compare';
            }
        }

        $classes[] = 'body-loading';
	    if( veres_string_to_bool( veres_get_theme_mod('page_preloader') ) ){
            $classes[] = 'site-loading';
            $classes[] = 'active_page_loading';
        }

	    if(!function_exists('lastudio_kit')){
	    	$classes[] = 'wp-default-theme';
	    }

		if(is_404()){
			$classes[] = 'lakitdoc-enable-header-transparency';
		}

        // Return classes
        return $classes;
    }
}

/**
 * Add page loader icon
 *
 * @since 1.0.0
 */
if(!function_exists('veres_add_pageloader_icon')){
    function veres_add_pageloader_icon(){
        if( veres_string_to_bool( veres_get_theme_mod('page_preloader') ) ){
            $loading_style = veres_get_theme_mod('page_preloader_type', 1);
            if($loading_style == 'custom'){
                if(($img = veres_get_theme_mod('page_preloader_custom')) && !empty($img) ){
                    add_filter('veres/filter/enable_image_lazyload', '__return_false', 10000);
                    add_filter('wp_lazy_loading_enabled', '__return_false', 10000);
                    echo '<div class="la-image-loading spinner-custom"><div class="content"><div class="la-loader"><img data-no-lazy="true" src="'.esc_url($img).'" width="50" height="50" alt="'.esc_attr(get_bloginfo('display')).'"/></div><div class="la-loader-ss"></div></div></div>';
                    veres_deactive_filter('veres/filter/enable_image_lazyload', '__return_false', 10000);
                    veres_deactive_filter('wp_lazy_loading_enabled', '__return_false', 10000);
                }
                else{
                    echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner1"></div><div class="la-loader-ss"></div></div></div>';
                }
            }
            else{
                echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner'.esc_attr($loading_style).'"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div><div class="la-loader-ss"></div></div></div>';
            }
        }
    }
}

/**
 * helper to change the excerpt length
 */
if(!function_exists('veres_change_excerpt_length')){
    function veres_change_excerpt_length( $length ){
        $length = 51;
        return $length;
    }
}

/**
 * Helper to render inline svg
 */

if(!function_exists('veres_render_inline_icon_to_footer')){
    function veres_render_inline_icon_to_footer(){
        get_template_part('partials/icons');
    }
}

if(!function_exists('veres_change_excerpt_more')){
	function veres_change_excerpt_more(){
		return '&hellip;';
	}
}