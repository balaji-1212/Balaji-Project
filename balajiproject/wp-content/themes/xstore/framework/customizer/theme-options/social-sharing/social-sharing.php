<?php
/**
 * The template created for displaying social sharing options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'social-sharing' => array(
			'name'       => 'social-sharing',
			'title'      => esc_html__( 'Social sharing', 'xstore' ),
			'icon'       => 'dashicons-share-alt',
			'priority'   => $priorities['social-sharing'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/social-sharing' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'socials' => array(
			'name'        => 'socials',
			'type'        => 'multicheck',
			'settings'    => 'socials',
			'label'       => esc_html__( 'Select socials you want to show', 'xstore' ),
			'description' => esc_html__( 'Turn on share buttons you need to show on the single product and post pages.', 'xstore' ),
			'section'     => 'social-sharing',
			'default'     => array(
				'share_twitter',
				'share_facebook',
				'share_vk',
				'share_pinterest',
				'share_mail',
				'share_linkedin',
				'share_whatsapp',
				'share_skype'
			),
			'choices'     => array(
				'share_twitter'   => esc_html__( 'Share twitter', 'xstore' ),
				'share_facebook'  => esc_html__( 'Share facebook', 'xstore' ),
				'share_vk'        => esc_html__( 'Share vk', 'xstore' ),
				'share_pinterest' => esc_html__( 'Share pinterest', 'xstore' ),
				'share_mail'      => esc_html__( 'Share mail', 'xstore' ),
				'share_linkedin'  => esc_html__( 'Share linkedin', 'xstore' ),
				'share_whatsapp'  => esc_html__( 'Share whatsapp', 'xstore' ),
				'share_skype'     => esc_html__( 'Share skype', 'xstore' ),
			),
		),
		
		'socials_copy_to_clipboard' => array(
			'name'        => 'socials_copy_to_clipboard',
			'type'        => 'toggle',
			'settings'    => 'socials_copy_to_clipboard',
			'label'       => esc_html__( 'Copy link on click', 'xstore' ),
			'description' => esc_html__( 'Turn on to copy link to clipboard on click.', 'xstore' ),
			'section'     => 'social-sharing',
			'default'     => 0,
		),
		
		'socials_filled' => array(
			'name'        => 'socials_filled',
			'type'        => 'toggle',
			'settings'    => 'socials_filled',
			'label'       => esc_html__( 'Icons filled', 'xstore' ),
			'description' => esc_html__( 'Turn on to show icons with background.', 'xstore' ),
			'section'     => 'social-sharing',
			'default'     => 0,
		),
	);
	
	return array_merge( $fields, $args );
	
} );