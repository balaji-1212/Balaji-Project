<?php  
	/**
	 * The template created for displaying footer panel
	 *
	 * @version 0.0.1
	 * @since 6.0.0
	 */

	add_filter( 'et/customizer/add/panels', function($panels) use($priorities){

		$args = array(
			'footer'	 => array(
				'id'          => 'footer',
				'title'       => esc_html__( 'Footer', 'xstore' ),
				'icon'		  => 'dashicons-arrow-down-alt',
				'priority' => $priorities['footer']
			)
		);

		return array_merge( $panels, $args );
	});