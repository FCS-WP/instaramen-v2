<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_veres_get_demo_array($dir_url, $dir_path){

    $demo_items = array(
        '01-handmade-soap-01' => array(
            'link'          => 'https://veres.la-studioweb.com/01-handmade-soap-01/',
            'title'         => '01 Handmade Soap 01',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-01' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-02' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
        '02-handmade-soap-02' => array(
            'link'          => 'https://veres.la-studioweb.com/02-handmade-soap-02/',
            'title'         => '02 Handmade Soap 02',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-01' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-01' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
        '03-handmade-candles-01' => array(
            'link'          => 'https://veres.la-studioweb.com/03-handmade-candles-01/',
            'title'         => '03 Handmade Candles 01',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-01' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-01' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
        '04-perfume-shop-01' => array(
            'link'          => 'https://veres.la-studioweb.com/04-perfume-shop-01/',
            'title'         => '04 Perfume Shop 01',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-02' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-02' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
        '05-handmade-shop-01' => array(
            'link'          => 'https://veres.la-studioweb.com/05-handmade-shop-01/',
            'title'         => '05 Handmade Shop 01',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-03' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-03' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
        '06-handmade-shop-02' => array(
            'link'          => 'https://veres.la-studioweb.com/06-handmade-shop-02/',
            'title'         => '06 Handmade Shop 02',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-04' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-03' => 'include/general',
                    ],
                ],
            ],
			'data_slider'   => 'Veres-Main-06.zip',
            'category'      => array(
                'Demo',
            )
        ),
        '07-handmade-shop-parallax' => array(
            'link'          => 'https://veres.la-studioweb.com/07-handmade-shop-parallax/',
            'title'         => '07 Handmade Shop Parallax',
            'data_elementor'=> [
                'header'       => [
                    'location' => 'header',
                    'value' => [
                        'veres-header-05' => 'include/general',
                    ],
                ],
                'footer'       => [
                    'location' => 'footer',
                    'value' => [
                        'veres-footer-04' => 'include/general',
                    ],
                ],
            ],
            'category'      => array(
                'Demo',
            )
        ),
    );

    $default_image_setting = array(
        'woocommerce_single_image_width' => 600,
        'woocommerce_thumbnail_image_width' => 300,
        'woocommerce_thumbnail_cropping' => 'custom',
        'woocommerce_thumbnail_cropping_custom_width' => 1,
        'woocommerce_thumbnail_cropping_custom_height' => 1,
        'thumbnail_size_w' => 0,
        'thumbnail_size_h' => 0,
        'medium_size_w' => 0,
        'medium_size_h' => 0,
        'medium_large_size_w' => 0,
        'medium_large_size_h' => 0,
        'large_size_w' => 0,
        'large_size_h' => 0,
    );

    $default_menu = array(
        'main-nav'              => 'Primary Navigation'
    );

    $default_page = array(
        'page_for_posts' 	            => 'Blog',
        'woocommerce_shop_page_id'      => 'Shop',
        'woocommerce_cart_page_id'      => 'Cart',
        'woocommerce_checkout_page_id'  => 'Checkout',
        'woocommerce_myaccount_page_id' => 'My Account'
    );

    $slider = $dir_path . 'Slider/';
    $content = $dir_path . 'Content/';
    $product = $dir_path . 'Product/';
    $widget = $dir_path . 'Widget/';
    $setting = $dir_path . 'Setting/';
    $preview = $dir_url;

    $default_elementor = [
        'single-post'       => [
            'location' => 'single',
            'value' => [
                'veres-single-post-sidebar' => 'include/singular/post',
            ],
        ],
        'single-page'       => [
            'location' => 'single',
            'value' => [
                'veres-woopages' => [
                    'include/singular/page/wishlist',
                    'include/singular/page/compare',
                    'include/singular/page/cart',
                    'include/singular/page/checkout'
                ],
            ]
        ],
        'archive'           => [
            'location' => 'archive',
            'value' => [
                'veres-blog-sidebar' => 'include/archive'
            ]
        ],
        'search-results'    => [
            'location' => 'archive',
            'value'    => '',
            'default' => [
                'name' => 'include/archive/search'
            ],
        ],
        'error-404'         => [
            'location' => 'single',
            'value' => '',
            'default' => [
	            'name' => 'include/archive/search'
            ],
        ],
        'product'           => [
            'location' => 'single',
            'value' => [
                'veres-single-product-01' => 'include/product'
            ]
        ],
        'product-archive'   => [
            'location' => 'archive',
            'value' => [
                'veres-shop-now-sidebar' => 'include/product_archive'
            ]
        ],
    ];

    $elementor_kit_settings = json_decode('{"page_title_selector":"h1.entry-title","active_breakpoints":["viewport_mobile","viewport_mobile_extra","viewport_tablet","viewport_laptop"],"viewport_mobile":767,"viewport_md":768,"viewport_mobile_extra":991,"viewport_tablet":1279,"viewport_lg":1280,"viewport_laptop":1599,"system_colors":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_colors":[{"_id":"c4781b8","title":"VeresPrimary1","color":"#222222"},{"_id":"bc191e9","title":"VeresPrimary2","color":"#DB572E"},{"_id":"93aab24","title":"VeresPrimary3","color":"#F7C495"},{"_id":"0b97d0e","title":"VeresText1","color":"#858585"},{"_id":"7356998","title":"VeresGrey1","color":"#D0D0D0"},{"_id":"c719877","title":"VeresGrey2","color":"#B5B5B5"}],"custom_typography":[{"_id":"2aea63c","title":"Veres01_H2","typography_typography":"custom","typography_font_family":"orpheuspro","typography_font_size":{"unit":"px","size":56,"sizes":[]},"typography_font_weight":"normal","typography_line_height":{"unit":"em","size":0.94,"sizes":[]},"typography_font_size_laptop":{"unit":"px","size":50,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":32,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":26,"sizes":[]}},{"_id":"d72f5af","title":"Veres01_H3","typography_typography":"custom","typography_font_family":"orpheuspro","typography_font_size":{"unit":"px","size":24,"sizes":[]},"typography_font_weight":"500","typography_line_height":{"unit":"em","size":1,"sizes":[]},"typography_font_size_laptop":{"unit":"px","size":22,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":20,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":18,"sizes":[]}},{"_id":"887cdbe","title":"Veres01_Text","typography_typography":"custom","typography_font_family":"Archivo","typography_font_size":{"unit":"px","size":24,"sizes":[]},"typography_line_height":{"unit":"em","size":1.75,"sizes":[]},"typography_font_weight":"300","typography_font_size_laptop":{"unit":"px","size":20,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":16,"sizes":[]}},{"_id":"15151ad","title":"Veres01_Button","typography_typography":"custom","typography_font_size":{"unit":"px","size":16,"sizes":[]},"typography_font_weight":"500","typography_text_transform":"uppercase","typography_line_height":{"unit":"px","size":20,"sizes":[]},"typography_font_size_laptop":{"unit":"px","size":14,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":12,"sizes":[]}},{"_id":"0efdd09","title":"Veres01_SubText","typography_typography":"custom","typography_font_family":"Archivo","typography_font_size":{"unit":"px","size":22,"sizes":[]},"typography_font_weight":"500","typography_text_transform":"uppercase","typography_line_height":{"unit":"em","size":1,"sizes":[]},"typography_font_size_laptop":{"unit":"px","size":18,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":16,"sizes":[]}},{"_id":"2eebc97","title":"Veres01_ProductTitle","typography_typography":"custom","typography_font_family":"Archivo","typography_font_size":{"unit":"px","size":18,"sizes":[]},"typography_font_weight":"500","typography_text_transform":"capitalize","typography_line_height":{"unit":"em","size":1.4,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":16,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":14,"sizes":[]}},{"_id":"677e840","title":"Veres01_ProductPrice","typography_typography":"custom","typography_font_family":"Archivo","typography_font_size":{"unit":"px","size":20,"sizes":[]},"typography_font_weight":"normal","typography_line_height":{"unit":"em","size":1,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":18,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":16,"sizes":[]}},{"_id":"4d035a3","title":"Veres03_H2","typography_typography":"custom","typography_font_family":"orpheuspro","typography_font_size_laptop":{"unit":"px","size":46,"sizes":[]},"typography_font_weight":"normal","typography_line_height_laptop":{"unit":"em","size":"","sizes":[]},"typography_font_size":{"unit":"px","size":52,"sizes":[]},"typography_line_height":{"unit":"em","size":1.1,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":36,"sizes":[]},"typography_font_size_mobile_extra":{"unit":"px","size":30,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":24,"sizes":[]}},{"_id":"3cbbc64","title":"Veres04_H2","typography_typography":"custom","typography_font_size":{"unit":"px","size":52,"sizes":[]},"typography_font_size_laptop":{"unit":"px","size":46,"sizes":[]},"typography_font_size_tablet":{"unit":"px","size":36,"sizes":[]},"typography_font_size_mobile_extra":{"unit":"px","size":30,"sizes":[]},"typography_font_size_mobile":{"unit":"px","size":24,"sizes":[]},"typography_font_weight":"normal","typography_font_style":"italic","typography_line_height":{"unit":"em","size":1.1,"sizes":[]},"typography_font_family":"orpheuspro"}],"default_generic_fonts":"Sans-serif"}', true);

    $data_return = array();

    foreach ($demo_items as $demo_key => $demo_detail){
	    $value = array();

	    $value['title']             = $demo_detail['title'];
	    $value['category']          = !empty($demo_detail['category']) ? $demo_detail['category'] : array('Demo');
	    $value['demo_preset']       = $demo_key;
	    $value['demo_url']          = $demo_detail['link'];
	    $value['preview']           = !empty($demo_detail['preview']) ? $demo_detail['preview'] : ($preview . $demo_key . '.jpg');
	    $value['option']            = $setting . $demo_key . '.json';
	    $value['content']           = !empty($demo_detail['data_sample']) ? $content . $demo_detail['data_sample'] : $content . 'demo-data.json';
	    $value['product']           = !empty($demo_detail['data_product']) ? $product . $demo_detail['data_product'] : $product . 'products.csv';
	    $value['widget']            = !empty($demo_detail['data_widget']) ? $widget . $demo_detail['data_widget'] : $widget . 'widgets.json';
	    $value['pages']             = array_merge( $default_page, array( 'page_on_front' => $demo_detail['title'] ));
	    $value['menu-locations']    = array_merge( $default_menu, isset($demo_detail['menu-locations']) ? $demo_detail['menu-locations'] : array());
	    $value['other_setting']     = array_merge( $default_image_setting, isset($demo_detail['other_setting']) ? $demo_detail['other_setting'] : array());
	    if(!empty($demo_detail['data_slider'])){
		    $value['slider'] = $slider . $demo_detail['data_slider'];
	    }
        $value['elementor']         = array_merge( $default_elementor, isset($demo_detail['data_elementor']) ? $demo_detail['data_elementor'] : array());
        $value['elementor_kit_settings']         = array_merge( $elementor_kit_settings, isset($demo_detail['elementor_kit_settings']) ? $demo_detail['elementor_kit_settings'] : array());
	    $data_return[$demo_key] = $value;
    }

    return $data_return;
}