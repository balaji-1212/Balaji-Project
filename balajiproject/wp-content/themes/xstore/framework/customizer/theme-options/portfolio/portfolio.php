<?php
/**
 * The template created for displaying blog portfolio options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'portfolio' => array(
			'name'       => 'portfolio',
			'title'      => esc_html__( 'Portfolio', 'xstore' ),
			'icon'       => 'dashicons-images-alt2',
			'priority'   => $priorities['portfolio'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/portfolio' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	
	$select_pages = et_customizer_get_posts(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'page'
		)
	);
	
	$select_pages[0] = esc_html__( 'Select page', 'xstore' );
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		'portfolio_projects' => array(
			'name'        => 'portfolio_projects',
			'type'        => 'toggle',
			'settings'    => 'portfolio_projects',
			'label'       => esc_html__( 'Portfolio projects', 'xstore' ),
			'description' => esc_html__( 'Turn on to enable portfolio projects post type.', 'xstore' ),
			'section'     => 'portfolio',
			'default'     => 1,
		),
		
		'portfolio_page' => array(
			'name'            => 'portfolio_page',
			'type'            => 'select',
			'settings'        => 'portfolio_page',
			'label'           => esc_html__( 'Portfolio page', 'xstore' ),
			'description'     => esc_html__( 'Choose the portfolio page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => '',
			'choices'         => $select_pages,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_filters_type' => array(
			'name'            => 'portfolio_filters_type',
			'type'            => 'select',
			'settings'        => 'portfolio_filters_type',
			'label'           => esc_html__( 'Portfolio filters show', 'xstore' ),
			'description'     => esc_html__( 'Work only at portfolio archive page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'all',
			'choices'         => array(
				'all'    => esc_html__( 'All', 'xstore' ),
				'parent' => esc_html__( 'Only parent', 'xstore' ),
				// 'child'  => esc_html__( 'Only child', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_style' => array(
			'name'            => 'portfolio_style',
			'type'            => 'select',
			'settings'        => 'portfolio_style',
			'label'           => esc_html__( 'Portfolio grid style', 'xstore' ),
			'description'     => esc_html__( 'Control the portfolio projects design on the portfolio page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'default',
			'choices'         => array(
				'default' => esc_html__( 'With title', 'xstore' ),
				'classic' => esc_html__( 'On hover', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_fullwidth' => array(
			'name'            => 'portfolio_fullwidth',
			'type'            => 'toggle',
			'settings'        => 'portfolio_fullwidth',
			'label'           => esc_html__( 'Full width portfolio', 'xstore' ),
			'description'     => esc_html__( 'Turn on to stretch portfolio page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'port_first_wide' => array(
			'name'            => 'port_first_wide',
			'type'            => 'toggle',
			'settings'        => 'port_first_wide',
			'label'           => esc_html__( 'Make first project wide', 'xstore' ),
			'description'     => esc_html__( 'Turn on to make the first portfolio project on the portfolio page in double size.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_masonry' => array(
			'name'            => 'portfolio_masonry',
			'type'            => 'toggle',
			'settings'        => 'portfolio_masonry',
			'label'           => esc_html__( 'Masonry', 'xstore' ),
			'description'     => esc_html__( 'Turn on placing projects in optimal position based on available vertical space.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 1,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_columns' => array(
			'name'            => 'portfolio_columns',
			'type'            => 'select',
			'settings'        => 'portfolio_columns',
			'label'           => esc_html__( 'Columns', 'xstore' ),
			'description'     => esc_html__( 'Choose the number of columns for the portfolio projects.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 3,
			'choices'         => array(
				2 => '2',
				3 => '3',
				4 => '4',
				5 => '5',
				6 => '6',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_margin' => array(
			'name'            => 'portfolio_margin',
			'type'            => 'select',
			'settings'        => 'portfolio_margin',
			'label'           => esc_html__( 'Portfolio item spacing', 'xstore' ),
			'description'     => esc_html__( 'Set the space between portfolio projects on the portfolio page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 15,
			'choices'         => array(
				1  => '0',
				5  => '5',
				10 => '10',
				15 => '15',
				20 => '20',
				30 => '30',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_count' => array(
			'name'            => 'portfolio_count',
			'type'            => 'etheme-text',
			'settings'        => 'portfolio_count',
			'label'           => esc_html__( 'Items per page', 'xstore' ),
			'description'     => esc_html__( 'Set the number of projects to show on the portfolio page before pagination appears. Use -1 to show all items.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'port_single_nav' => array(
			'name'            => 'port_single_nav',
			'type'            => 'toggle',
			'settings'        => 'port_single_nav',
			'label'           => esc_html__( 'Show next/previous projects navigation', 'xstore' ),
			'description'     => esc_html__( 'Turn on to show next/prev project navigation on the single project page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_images_size' => array(
			'name'            => 'portfolio_images_size',
			'type'            => 'etheme-text',
			'settings'        => 'portfolio_images_size',
			'label'           => esc_html__( 'Image sizes for portfolio', 'xstore' ),
			'description'     => esc_html__( 'Choose the most suitable size for the project images on the portfolio page. Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme. Alternatively enter size in pixels. Example: 200x100 (Width x Height).', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'large',
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_order' => array(
			'name'            => 'portfolio_order',
			'type'            => 'select',
			'settings'        => 'portfolio_order',
			'label'           => esc_html__( 'Portfolio order way', 'xstore' ),
			'description'     => esc_html__( 'Choose the method of collation for the portfolio projects on the portfolio page.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'DESC',
			'choices'         => array(
				'DESC' => 'Descending',
				'ASC'  => 'Ascending',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'portfolio_orderby' => array(
			'name'            => 'portfolio_orderby',
			'type'            => 'select',
			'settings'        => 'portfolio_orderby',
			'label'           => esc_html__( 'Portfolio order by', 'xstore' ),
			'description'     => esc_html__( 'Choose the ascending or descending order for the portfolio projects.', 'xstore' ),
			'section'         => 'portfolio',
			'default'         => 'title',
			'choices'         => array(
				'title' => 'Title',
				'date'  => 'Date',
				'ID'    => 'ID',
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_projects',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
	);
	
	return array_merge( $fields, $args );
	
} );