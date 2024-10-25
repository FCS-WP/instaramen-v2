<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter('lastudio-kit/branding/name', 'veres_lakit_branding_name');
if(!function_exists('veres_lakit_branding_name')){
    function veres_lakit_branding_name( $name ){
        $name = esc_html__('Theme Options', 'veres');
        return $name;
    }
}

add_filter('lastudio-kit/branding/logo', 'veres_lakit_branding_logo');
if(!function_exists('veres_lakit_branding_logo')){
    function veres_lakit_branding_logo( $logo ){
        $logo = '';
        return $logo;
    }
}

add_filter('lastudio-kit/logo/attr/src', 'veres_lakit_logo_attr_src');
if(!function_exists('veres_lakit_logo_attr_src')){
    function veres_lakit_logo_attr_src( $src ){
        if(!$src){
	        $src = veres_get_theme_mod('logo_default', get_theme_file_uri('/assets/images/logo.svg'));
        }
        return $src;
    }
}

add_filter('lastudio-kit/logo/attr/src2x', 'veres_lakit_logo_attr_src2x');
if(!function_exists('veres_lakit_logo_attr_src2x')){
    function veres_lakit_logo_attr_src2x( $src ){
        if(!$src){
	        $src = veres_get_theme_mod('logo_transparency', '');
        }
        return $src;
    }
}

add_filter('lastudio-kit/logo/attr/width', 'veres_lakit_logo_attr_width');
if(!function_exists('veres_lakit_logo_attr_width')){
    function veres_lakit_logo_attr_width( $value ){
        if(!$value){
            $value = 174;
        }
        return $value;
    }
}

add_filter('lastudio-kit/logo/attr/height', 'veres_lakit_logo_attr_height');
if(!function_exists('veres_lakit_logo_attr_height')){
    function veres_lakit_logo_attr_height( $value ){
        if(!$value){
            $value = 49;
        }
        return $value;
    }
}

add_action('elementor/frontend/widget/before_render', 'veres_lakit_add_class_into_sidebar_widget');
if(!function_exists('veres_lakit_add_class_into_sidebar_widget')){
    function veres_lakit_add_class_into_sidebar_widget( $widget ){
        if('sidebar' == $widget->get_name()){
            $widget->add_render_attribute('_wrapper', 'class' , 'widget-area');
        }
    }
}

add_filter('lastudio-kit/products/control/grid_style', 'veres_lakit_add_product_grid_style');
if(!function_exists('veres_lakit_add_product_grid_style')){
    function veres_lakit_add_product_grid_style(){
        return [
            '1' => esc_html__('Type 1', 'veres'),
            '2' => esc_html__('Type 2', 'veres'),
            '3' => esc_html__('Type 3', 'veres'),
            '4' => esc_html__('Type 4', 'veres'),
            '5' => esc_html__('Type 5', 'veres'),
            '6' => esc_html__('Type 6', 'veres'),
            '7' => esc_html__('Type 7', 'veres'),
            '8' => esc_html__('Type 8', 'veres'),
            '9' => esc_html__('Type 9', 'veres'),
        ];
    }
}
add_filter('lastudio-kit/products/control/list_style', 'veres_lakit_add_product_list_style');
if(!function_exists('veres_lakit_add_product_list_style')){
    function veres_lakit_add_product_list_style(){
        return [
            '1' => esc_html__('Type 1', 'veres'),
            'mini' => esc_html__('Mini', 'veres'),
        ];
    }
}

add_filter('lastudio-kit/products/box_selector', 'veres_lakit_product_change_box_selector');
if(!function_exists('veres_lakit_product_change_box_selector')){
    function veres_lakit_product_change_box_selector(){
        return '{{WRAPPER}} ul.products .product_item--inner';
    }
}

add_filter('lastudio-kit/posts/format-icon', 'veres_lakit_change_postformat_icon', 10, 2);
if(!function_exists('veres_lakit_change_postformat_icon')){
    function veres_lakit_change_postformat_icon( $icon, $type ){
        return $icon;
    }
}

/**
 * Modify Divider - Weight control
 */
add_action('elementor/element/lakit-portfolio/section_settings/before_section_end', function( $element ){
	$element->add_control(
		'enable_portfolio_lightbox',
		[
			'label'     => esc_html__( 'Enable Lightbox', 'veres' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Yes', 'veres' ),
			'label_off' => esc_html__( 'No', 'veres' ),
			'default'   => '',
			'return_value' => 'enable-pf-lightbox',
			'prefix_class' => '',
		]
	);
}, 10 );

if(!function_exists('veres_elementor_register_custom_widgets')){
	function veres_elementor_register_custom_widgets(){
		require_once get_theme_file_path('/framework/third/elementor/postformat-widget.php');
		\Elementor\Plugin::instance()->widgets_manager->register( new Veres_Elementor_PostFormat_Content_Widget() );
	}
}

add_action( 'elementor/widgets/widgets_registered', 'veres_elementor_register_custom_widgets' );

if(!function_exists('veres_remove_unwanted_metaboxes')){
    function veres_remove_unwanted_metaboxes( $type, $context, $post ){
        remove_meta_box('slider_revolution_metabox', $type, $context);
    }
}

add_action( 'do_meta_boxes', 'veres_remove_unwanted_metaboxes', 10, 3 );

if(!function_exists('veres_elementor_modify_widget_args')){
    function veres_elementor_modify_widget_args( $default_args, $widget ){
        $new_args = [
            'after_widget'	=> '</div>',
            'before_title'	=> '<div class="widget-title"><span>',
            'after_title'	=> '</span></div>',
        ];
        $widget_cssclass = sprintf('widget widget_%1$s %1$s', $widget->get_widget_instance()->id_base);
        if( !empty($widget->get_widget_instance()->widget_cssclass) ){
            $widget_cssclass = 'widget ' . $widget->get_widget_instance()->widget_cssclass;
        }
        $new_args['before_widget'] = sprintf('<div class="%1$s lakit-wp--widget" data-id="%2$s">', $widget_cssclass, $widget->get_id());

        return array_merge($default_args, $new_args);
    }
}
add_filter('elementor/widgets/wordpress/widget_args', 'veres_elementor_modify_widget_args', 20, 2);