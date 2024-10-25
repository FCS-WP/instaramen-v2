<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('veres_setup_metaboxes')){
	function veres_setup_metaboxes(){
		if(!function_exists('lastudio_kit_post_meta')){
			return;
		}
		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'veres-portfolio-settings',
			'title'         => esc_html__( 'Portfolio Settings', 'veres' ),
			'page'          => array( 'la_portfolio' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
				'_pf_gallery' => array(
					'type'               => 'media',
					'title'              => esc_html__( 'Image Gallery', 'veres' ),
					'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery.', 'veres' ),
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Set Gallery Images', 'veres' ),
				),
				'_pf_description' => array(
					'type'        => 'wysiwyg',
					'title'       => esc_html__( 'Short Description', 'veres' ),
					'rows'        => 5
				),
				'_pf_client' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Client', 'veres' ),
				),
				'_pf_date' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Date', 'veres' ),
				),
				'_pf_awards' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Awards', 'veres' ),
				),
				'_pf_link1' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Demo Link 1', 'veres' ),
				),
				'_pf_link2' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Demo Link 2', 'veres' ),
				),
			),
		) );

		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'veres-post-settings',
			'title'         => esc_html__( 'Post Settings', 'veres' ),
			'page'          => array( 'post' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
				'_la_bg' => array(
					'type'               => 'media',
					'title'              => esc_html__( 'Background Image', 'veres' ),
					'description'        => esc_html__( 'This option will work on some cases.', 'veres' ),
					'library_type'       => 'image',
					'multi_upload'       => false,
					'value_format'       => 'id',
					'upload_button_text' => esc_html__( 'Set Image', 'veres' ),
				),
				'post_formats' => array(
					'type' => 'component-tab-horizontal',
					'ignore_save'   => true,
				),
				'gallery_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Gallery', 'veres' ),
				),
				'_la_gallery_images' => array(
					'type'               => 'media',
					'parent'             => 'gallery_tab',
					'title'              => esc_html__( 'Image Gallery', 'veres' ),
					'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery post formats.', 'veres' ),
					'library_type'       => 'image',
					'multi_upload'       => true,
					'value_format'       => 'id',
					'upload_button_text' => esc_html__( 'Set Gallery Images', 'veres' ),
				),
				'link_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Link', 'veres' ),
				),
				'_la_link_text' => array(
					'type'        => 'text',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link Text', 'veres' ),
					'description' => esc_html__( 'Enter your text. This setting is used for your link post formats.', 'veres' ),
				),
				'_la_link' => array(
					'type'        => 'text',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link URL', 'veres' ),
					'description' => esc_html__( 'Enter your external url. This setting is used for your link post formats.', 'veres' ),
				),
				'_la_link_target' => array(
					'type'        => 'select',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link Target', 'veres' ),
					'description' => esc_html__( 'Choose your target for the url. This setting is used for your link post formats.', 'veres' ),
					'value'       => '_blank',
					'options'     => array(
						'_blank' => esc_html__('Blank', 'veres'),
						'_self'  => esc_html__('Self', 'veres'),
					),
				),
				'quote_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Quote', 'veres' ),
				),
				'_la_quote_text' => array(
					'type'        => 'textarea',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Quote', 'veres' ),
					'description' => esc_html__( 'Enter your quote. This setting is used for your quote post formats.', 'veres' ),
				),
				'_la_quote_cite' => array(
					'type'        => 'text',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Cite', 'veres' ),
					'description' => esc_html__( 'Enter the quote source. This setting is used for your quote post formats.', 'veres' ),
				),
				'_la_quote_color' => array(
					'type'        => 'colorpicker',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Text Color', 'veres' ),
				),
				'_la_quote_bg' => array(
					'type'        => 'colorpicker',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Background Color', 'veres' ),
				),
				'video_audio_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Video & Audio', 'veres' ),
				),
				'_la_video_link' => array(
					'type'        => 'text',
					'parent'      => 'video_audio_tab',
					'title'       => esc_html__( 'Link Video URL', 'veres' ),
					'description' => esc_html__( 'Insert Youtube or Vimeo video link', 'veres' ),
				),
			),
		) );
	}
}

add_action( 'after_setup_theme', 'veres_setup_metaboxes', 6 );
