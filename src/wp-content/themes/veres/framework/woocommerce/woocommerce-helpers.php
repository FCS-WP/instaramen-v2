<?php
/**
 * WooCommerce helper functions
 * This functions only load if WooCommerce is enabled because
 * they should be used within Woo loops only.
 *
 * @package Veres WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {

    /**
     * Get the product thumbnail for the loop.
     */
    function woocommerce_template_loop_product_thumbnail() {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<figure>';
        echo woocommerce_get_product_thumbnail();
        echo '</figure>';
    }
}

if(!function_exists('veres_modify_sale_flash')){
    function veres_modify_sale_flash( $output ){
        return str_replace('class="onsale"', 'class="la-custom-badge onsale"', $output);
    }
}
add_filter('woocommerce_sale_flash', 'veres_modify_sale_flash');

if(!function_exists('veres_modify_product_list_preset')){
    function veres_modify_product_list_preset( $preset ){
        $preset = array(
            '1' => esc_html__( 'Default', 'veres' )
        );
        return $preset;
    }
}

add_filter('woocommerce_product_description_heading', '__return_empty_string');
add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');

if(!function_exists('veres_woo_get_product_per_page_array')){
    function veres_woo_get_product_per_page_array(){
        $per_page_array = apply_filters('veres/filter/get_product_per_page_array', veres_get_theme_mod('wc_per_page_allow', ''));
        if(!empty($per_page_array)){
            $per_page_array = explode(',', $per_page_array);
            $per_page_array = array_map('trim', $per_page_array);
            $per_page_array = array_map('absint', $per_page_array);
            asort($per_page_array);
            return $per_page_array;
        }
        else{
            return array();
        }
    }
}

if(!function_exists('veres_woo_get_product_per_row_array')){
    function veres_woo_get_product_per_row_array(){
        $per_page_array = apply_filters('veres/filter/get_product_per_row_array', veres_get_theme_mod('wc_per_row_allow', ''));
        if(!empty($per_page_array)){
            $per_page_array = explode(',', $per_page_array);
            $per_page_array = array_map('trim', $per_page_array);
            $per_page_array = array_map('absint', $per_page_array);
            asort($per_page_array);
            return $per_page_array;
        }
        else{
            return array();
        }
    }
}

if(!function_exists('veres_woo_get_product_per_page')){
    function veres_woo_get_product_per_page(){
        return apply_filters('veres/filter/get_product_per_page', veres_get_theme_mod('wc_per_page_default', 12));
    }
}

if(!function_exists('veres_get_base_shop_url')){
    function veres_get_base_shop_url( ){
        if(!function_exists('WC')){
            return home_url('/');
        }

        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        }
        elseif ( is_shop() ) {
            $link = get_permalink( wc_get_page_id( 'shop' ) );
        }
        elseif( is_tax( get_object_taxonomies( 'product' ) ) ) {

            if( is_product_category() ) {
                $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
            }
            elseif ( is_product_tag() ) {
                $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
            }
            else{
                $queried_object = get_queried_object();
                $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
            }
        }
        elseif ( function_exists('dokan_is_store_page') && dokan_is_store_page() ){
            $current_url = add_query_arg(null, null, dokan_get_store_url(get_query_var('author')));
            $current_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $current_url);
            $link = preg_replace('/\/page\/\d+/', '', $current_url);
            $tmp = explode('?', $link);
            if(isset($tmp[0])){
                $link = $tmp[0];
            }
        }
        else{
            $link = get_post_type_archive_link( 'product' );
        }
        return $link;
    }
}

if(!function_exists('veres_wc_allow_translate_text_in_swatches')){

    function veres_wc_allow_translate_text_in_swatches( $text ){
        return esc_html_x( 'Choose an option', 'front-view', 'veres' );
    }

    add_filter('LaStudio/swatches/args/show_option_none', 'veres_wc_allow_translate_text_in_swatches', 10, 1);
}


if(!function_exists('veres_override_woothumbnail_size_name')){
    function veres_override_woothumbnail_size_name( ) {
        return 'woocommerce_gallery_thumbnail';
    }
    add_filter('woocommerce_gallery_thumbnail_size', 'veres_override_woothumbnail_size_name', 0);
}


if(!function_exists('veres_override_woothumbnail_size')){
    function veres_override_woothumbnail_size( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 600 ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_gallery_thumbnail', 'veres_override_woothumbnail_size');
}

if(!function_exists('veres_override_woothumbnail_single')){
    function veres_override_woothumbnail_single( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_single', 'veres_override_woothumbnail_single', 0);
}



if ( !function_exists('veres_modify_text_woocommerce_catalog_orderby') ){
    function veres_modify_text_woocommerce_catalog_orderby( $data ) {
        $data = array(
            'menu_order' => esc_html__( 'Sort by Default', 'veres' ),
            'popularity' => esc_html__( 'Sort by Popularity', 'veres' ),
            'rating'     => esc_html__( 'Sort by Rated', 'veres' ),
            'date'       => esc_html__( 'Sort by Latest', 'veres' ),
            'price'      => sprintf( wp_kses( __( 'Sort by Price: %s', 'veres' ), array( 'i' => array( 'class' => array() ) ) ), '<i class="lastudioicon-arrow-up"></i>' ),
            'price-desc' => sprintf( wp_kses( __( 'Sort by Price: %s', 'veres' ), array( 'i' => array( 'class' => array() ) ) ), '<i class="lastudioicon-arrow-down"></i>' ),
        );
        return $data;
    }

    add_filter('woocommerce_catalog_orderby', 'veres_modify_text_woocommerce_catalog_orderby');
}

if(!function_exists('veres_add_custom_badge_for_product')){
    function veres_add_custom_badge_for_product(){
        global $product;

	    $availability = $product->get_availability();
	    if(!empty($availability['class']) && $availability['class'] == 'out-of-stock' && !empty($availability['availability'])){
		    printf('<span class="la-custom-badge badge-out-of-stock">%s</span>', esc_html($availability['availability']));
	    }

        $product_badges = get_post_meta($product->get_id(), '_la_product_badges', true);
        if(empty($product_badges)){
            return;
        }
        $_tmp_badges = array();
        foreach($product_badges as $badge){
            if(!empty($badge['text'])){
                $_tmp_badges[] = $badge;
            }
        }
        if(empty($_tmp_badges)){
            return;
        }
        foreach($_tmp_badges as $i => $badge){
            $attribute = array();
            if(!empty($badge['bg'])){
                $attribute[] = 'background-color:' . esc_attr($badge['bg']);
            }
            if(!empty($badge['color'])){
                $attribute[] = 'color:' . esc_attr($badge['color']);
            }
            $el_class = ($i%2==0) ? 'odd' : 'even';
            if(!empty($badge['el_class'])){
                $el_class .= ' ';
                $el_class .= $badge['el_class'];
            }

            echo sprintf(
                '<span class="la-custom-badge %1$s" %3$s><span>%2$s</span></span>',
                esc_attr($el_class),
                esc_html($badge['text']),
                (!empty($attribute) ? veres_render_style_attribute(implode(';', $attribute)) : '')
            );
        }
    }
    add_action( 'woocommerce_before_shop_loop_item', 'veres_add_custom_badge_for_product', 11 );
    add_action( 'woocommerce_before_single_product_summary', 'veres_add_custom_badge_for_product', 9 );
    add_action( 'lastudiokit/woocommerce/product-images/render', 'veres_add_custom_badge_for_product', 9 );
}

add_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 11);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');

if(!function_exists('veres_add_custom_block_to_cart_page')){
    function veres_add_custom_block_to_cart_page(){
        ?>
        <div class="lasf-extra-cart lasf-extra-cart--calc">
            <h2><?php esc_html_e('Estimate Shipping', 'veres'); ?></h2>
            <p><?php esc_html_e('Enter your destination to get shipping', 'veres'); ?></p>
            <div class="lasf-extra-cart-box"></div>
        </div>
        <div class="lasf-extra-cart lasf-extra-cart--coupon">
            <h2><?php esc_html_e('Discount code', 'veres'); ?></h2>
            <p><?php esc_html_e('Enter your coupon if you have one', 'veres'); ?></p>
            <div class="lasf-extra-cart-box"></div>
        </div>
        <?php
    }
    add_action('woocommerce_cart_collaterals', 'veres_add_custom_block_to_cart_page', 5);
}


if(!function_exists('veres_add_custom_heading_to_checkout_order_review')){
    function veres_add_custom_heading_to_checkout_order_review(){
        ?><h3 id="order_review_heading_ref"><?php esc_html_e( 'Your order', 'veres' ); ?></h3><?php
    }
}
add_action('woocommerce_checkout_order_review', 'veres_add_custom_heading_to_checkout_order_review', 0);


if( !function_exists('veres_calculator_free_shipping_thresholds')){
    function veres_calculator_free_shipping_thresholds(){
        if( ! veres_string_to_bool( veres_get_theme_mod('freeshipping_thresholds') ) ){
            return;
        }

        if ( WC()->cart->is_empty() ) {
            return;
        }
        // Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts
        $default_zone = new WC_Shipping_Zone( 0 );

        $default_methods = $default_zone->get_shipping_methods();
        foreach ( $default_methods as $key => $value ) {
            if ( $value->id === "free_shipping" ) {
                if ( $value->min_amount > 0 ) {
                    $min_amounts[] = $value->min_amount;
                }
            }
        }
        // Get Free Shipping Methods for all other ZONES & populate array $min_amounts
        $delivery_zones = WC_Shipping_Zones::get_zones();
        foreach ( $delivery_zones as $key => $delivery_zone ) {
            foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
                if ( $value->id === "free_shipping" ) {
                    if ( $value->min_amount > 0 ) {
                        $min_amounts[] = $value->min_amount;
                    }
                }
            }
        }
        // Find lowest min_amount
        if ( isset( $min_amounts ) ) {
            if ( is_array( $min_amounts ) && $min_amounts ) {
                $min_amount = min( $min_amounts );
                // Get Cart Subtotal inc. Tax excl. Shipping
                $current = WC()->cart->subtotal;
                // If Subtotal < Min Amount Echo Notice
                // and add "Continue Shopping" button
                if ( $current > 0 ) {
                    $percent = round( ( $current / $min_amount ) * 100, 2 );
                    $percent >= 100 ? $percent = '100' : '';
                    if ( $percent < 40 ) {
                        $parse_class = 'first-parse';
                    }
                    elseif ( $percent >= 40 && $percent < 80 ) {
                        $parse_class = 'second-parse';
                    }
                    else {
                        $parse_class = 'final-parse';
                    }
                    $parse_class .= ' free-shipping-required-notice';
                    $text1 = veres_get_theme_mod('thresholds_text1', esc_html__('[icon]Spend [amount] to get Free Shipping', 'veres'));
                    $text2 = veres_get_theme_mod('thresholds_text2', esc_html__('[icon]Congratulations! You\'ve got free shipping!', 'veres'));
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="62" height="45" viewBox="0 0 62 45"><g fill="currentColor" fill-rule="evenodd"><path d="M21 38a2 2 0 1 1-4 0 2 2 0 0 1 4 0m29 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0"></path><path d="M19 33.19A4.816 4.816 0 0 0 14.19 38 4.816 4.816 0 0 0 19 42.81 4.816 4.816 0 0 0 23.81 38 4.816 4.816 0 0 0 19 33.19M19 45c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7"></path><path d="M38 37H24.315v-2.145h11.544V2.145H2.14v32.71h11.544V37H0V0h38zm11-3.81A4.816 4.816 0 0 0 44.19 38 4.816 4.816 0 0 0 49 42.81 4.816 4.816 0 0 0 53.81 38 4.816 4.816 0 0 0 49 33.19M49 45c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7"></path><path d="M62 37h-7.607v-2.154h5.47V22.835l-8.578-12.681H38.137v24.692h5.465V37H36V8h16.415L62 22.17z"></path><path d="M42.147 19.932h10.792l-4.15-5.864h-6.642v5.864zM57 22H40V12h9.924L57 22z"></path></g></svg>';
                    $text1 = str_replace('[icon]', $icon, $text1);
                    $text2 = str_replace('[icon]', $icon, $text2);
                    $added_text='';
                    if ( $current < $min_amount ) {
                        $text1 = str_replace('[amount]', wc_price( $min_amount - $current ), $text1);
                        $added_text .= $text1;
                    } else {
                        $added_text = $text2;
                    }
                    $css_inline = 'width: ' . $percent . '%';
                    $html = '<div class="' . esc_attr( $parse_class ) . '">';
                    $html .= '<div class="la-loading-bar"><div class="load-percent"'. veres_render_style_attribute($css_inline) .'>';
                    $html .= '</div><span class="label-free-shipping">'.$added_text.'</span></div>';
                    $html .= '</div>';
                    echo ent2ncr( $html );
                }
            }
        }
    }
    add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'veres_calculator_free_shipping_thresholds', 5 );
    add_action( 'woocommerce_before_cart_table', 'veres_calculator_free_shipping_thresholds', 5 );
}

if(!function_exists('veres_wc_add_qty_control_plus')){
    function veres_wc_add_qty_control_plus(){
        echo '<span class="qty-plus"><i class="lastudioicon-i-add-2"></i></span>';
    }
}

if(!function_exists('veres_wc_add_qty_control_minus')){
    function veres_wc_add_qty_control_minus(){
        echo '<span class="qty-minus"><i class="lastudioicon-i-delete-2"></i></span>';
    }
}

add_action('woocommerce_after_quantity_input_field', 'veres_wc_add_qty_control_plus');
add_action('woocommerce_before_quantity_input_field', 'veres_wc_add_qty_control_minus');

if(!function_exists('veres_wc_product_bulk_edit_start')){
    function veres_wc_product_bulk_edit_start(){
        if(!class_exists('LASF', false)){
            return;
        }
        echo '<div class="lasf-bulk-group lasf-show-all"><div class="lasf-section lasf-onload">';
        LASF::field(array(
            'id'        => 'enable_custom_badge',
            'type'      => 'button_set',
            'default'   => 'nochange',
            'title'     => esc_html_x('Enable Custom Badges', 'admin-view', 'veres'),
            'options'   => array(
                'nochange'          => esc_html_x('No Change', 'admin-view', 'veres'),
                'addnew'            => esc_html_x('Override', 'admin-view', 'veres'),
                'removeall'         => esc_html_x('Remove Existing Data', 'admin-view', 'veres')
            )
        ), '', 'la_custom_badge');
        LASF::field(array(
            'id'                => 'product_badges',
            'type'              => 'group',
            'title'             => esc_html_x('Custom Badges', 'admin-view', 'veres'),
            'button_title'      => esc_html_x('Add Badge','admin-view', 'veres'),
            'max'               => 3,
            'dependency'        => array('enable_custom_badge', '==', 'addnew'),
            'fields'            => array(
                array(
                    'id'            => 'text',
                    'type'          => 'text',
                    'default'       => 'New',
                    'title'         => esc_html_x('Badge Text', 'admin-view', 'veres')
                ),
                array(
                    'id'            => 'bg',
                    'type'          => 'color',
                    'default'       => '',
                    'title'         => esc_html_x('Custom Badge Background Color', 'admin-view', 'veres')
                ),
                array(
                    'id'            => 'color',
                    'type'          => 'color',
                    'default'       => '',
                    'title'         => esc_html_x('Custom Badge Text Color', 'admin-view', 'veres')
                ),
                array(
                    'id'            => 'el_class',
                    'type'          => 'text',
                    'default'       => '',
                    'title'         => esc_html_x('Extra CSS class for badge', 'admin-view', 'veres')
                )
            )
        ), '', 'la_custom_badge');
        echo '</div></div>';
?>
        <?php
    }
}
add_action( 'woocommerce_product_bulk_edit_start', 'veres_wc_product_bulk_edit_start' );

if(!function_exists('veres_wc_product_bulk_edit_save')){
    function veres_wc_product_bulk_edit_save( $product ){
        $product_id = $product->get_id();
        if ( isset( $_REQUEST['la_custom_badge'], $_REQUEST['la_custom_badge']['enable_custom_badge'] ) ) {
	        $old_data = get_post_meta($product->get_id(), '_la_product_badges', true);
            $enable = $_REQUEST['la_custom_badge']['enable_custom_badge'];
            if( 'removeall' == $enable ) {
	            $old_data = [];
                update_post_meta( $product_id, '_la_product_badges', $old_data );
            }
            elseif( 'addnew' == $enable && !empty($_REQUEST['la_custom_badge']['product_badges'])) {
                $product_badges = $_REQUEST['la_custom_badge']['product_badges'];
                update_post_meta( $product_id, '_la_product_badges', $old_data );
            }
        }
    }
}
add_action( 'woocommerce_product_bulk_edit_save', 'veres_wc_product_bulk_edit_save' );

if(!function_exists('veres_wc_swatches_attribute_change_type_default')){
    function veres_wc_swatches_attribute_change_type_default( $value, $product_id, $is_tax, $product ){
        if($is_tax){
            $value = 'term_options';
        }
        return $value;
    }
}
add_filter( 'LaStudio/swatches/attribute/default_type', 'veres_wc_swatches_attribute_change_type_default', 10, 4);

if(!function_exists('veres_wc_disable_redirect_single_search_result')){
    function veres_wc_disable_redirect_single_search_result( $value ){
        if(isset($_GET['la_doing_ajax'])){
            $value = false;
        }
        return $value;
    }
}
add_filter('woocommerce_redirect_single_search_result', 'veres_wc_disable_redirect_single_search_result');

if(!function_exists('veres_wc_render_variation_templates')){
    function veres_wc_render_variation_templates(){
        wc_get_template('single-product/add-to-cart/variation.php');
    }
}
add_action('wp_footer', 'veres_wc_render_variation_templates');

if(!function_exists('veres_wc_add_register_link_to_login_frm')){
    function veres_wc_add_register_link_to_login_frm(){
        if( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ){
            echo sprintf('<p class="wcr_resigter_link"><span>%1$s</span><a href="%3$s">%2$s</a></p>', esc_html__('Don’t have account ?', 'veres'), esc_html__('Sign up now', 'veres'), wc_get_page_permalink('myaccount'));
        }
    }
}
add_action('woocommerce_login_form_end', 'veres_wc_add_register_link_to_login_frm');

