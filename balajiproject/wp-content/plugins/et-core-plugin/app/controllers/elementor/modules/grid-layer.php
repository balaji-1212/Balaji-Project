<?php
/**
 * Grid Layer feature for Elementor document
 *
 * @package    Grid-layer.php
 * @since      4.3.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Controls_Manager;


class Grid_Layer {
	
	function __construct() {
		// add grid layer for documents
		add_action('elementor/documents/register_controls', array($this, 'register_controls'), 1, 1 );
	}
	
	public static function register_controls( $element ) {
		$element->start_controls_section(
			'_section_etheme_grid_layer',
			[
				'label' => __( 'XSTORE Grid Layer', 'xstore-core' ),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);
		
		$element->add_control(
			'etheme_grid',
			[
				'label'     => __( 'Enable XStore Grid Layer', 'xstore-core' ),
				'type'      => Controls_Manager::SWITCHER,
			]
		);
		
		$element->add_control(
			'etheme_grid_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'This feature is available only in editor and only for design purpose.', 'xstore-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'etheme_grid' => 'yes',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_grid_number',
			[
				'label'     => __( 'Columns', 'xstore-core' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => 12,
				'desktop_default' => 12,
				'tablet_default' => 12,
				'mobile_default' => 12,
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_responsive_control(
			'etheme_grid_max_width',
			[
				'label'      => __( 'Max Width', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 3000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => get_theme_mod('site_width', get_option( 'elementor_container_width', 1140 )), // get_option( 'elementor_container_width', 1140 ),
					'unit' => 'px',
				],
				'desktop_default' => [
					'size' => get_theme_mod('site_width', get_option( 'elementor_container_width', 1140 )),
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => get_option( 'elementor_viewport_lg', 1025 ),
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => get_option( 'elementor_viewport_md', 768 ),
					'unit' => 'px',
				],
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_responsive_control(
			'etheme_grid_offset',
			[
				'label'      => __( 'Offset', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_responsive_control(
			'etheme_grid_gutter',
			[
				'label'      => __( 'Gutter', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 0.1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_control(
			'etheme_grid_zindex',
			[
				'label'      => __( 'Z-Index', 'xstore-core' ),
				'type'       => Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => '1000',
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_control(
			'etheme_grid_color',
			[
				'label'     => __( 'Grid Color', 'xstore-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(250, 0, 0, 0.15)',
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'render_type' => 'none',
			]
		);
		
		$element->add_control(
			'etheme_grid_on',
			[
				'label' => __( 'Grid Layer On', 'xstore-core' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'grid-on',
				'condition' => [
					'etheme_grid' => 'yes',
				],
				'selectors' => [
					'html.elementor-html' => 'position: relative;',
					'html.elementor-html::before' => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; margin-right: auto; margin-left: auto; pointer-events: none; z-index: {{etheme_grid_zindex.VALUE || 1000}}; min-height: 100vh;',
					//Desktop view
					'(desktop) html.elementor-html::before' => '
					width: calc(100% - (2 * {{etheme_grid_offset.SIZE}}{{etheme_grid_offset.UNIT}}));
					max-width: {{etheme_grid_max_width.SIZE}}{{etheme_grid_max_width.UNIT}};
					background-size: calc(100% + {{etheme_grid_gutter.SIZE}}{{etheme_grid_gutter.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number.VALUE}}) - {{etheme_grid_gutter.SIZE}}{{etheme_grid_gutter.UNIT}}), transparent calc((100% / {{etheme_grid_number.VALUE}}) - {{etheme_grid_gutter.SIZE}}{{etheme_grid_gutter.UNIT}}), transparent calc(100% / {{etheme_grid_number.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number.VALUE}}) - {{etheme_grid_gutter.SIZE}}{{etheme_grid_gutter.UNIT}}), transparent calc((100% / {{etheme_grid_number.VALUE}}) - {{etheme_grid_gutter.SIZE}}{{etheme_grid_gutter.UNIT}}), transparent calc(100% / {{etheme_grid_number.VALUE}}) );',
					//Tablet view
					'(tablet) html.elementor-html::before' => '
					width: calc(100% - (2 * {{etheme_grid_offset_tablet.SIZE}}{{etheme_grid_offset_tablet.UNIT}}));
					max-width: {{etheme_grid_max_width_tablet.SIZE}}{{etheme_grid_max_width_tablet.UNIT}};
					background-size: calc(100% + {{etheme_grid_gutter_tablet.SIZE}}{{etheme_grid_gutter_tablet.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number_tablet.VALUE}}) - {{etheme_grid_gutter_tablet.SIZE}}{{etheme_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{etheme_grid_number_tablet.VALUE}}) - {{etheme_grid_gutter_tablet.SIZE}}{{etheme_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{etheme_grid_number_tablet.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number_tablet.VALUE}}) - {{etheme_grid_gutter_tablet.SIZE}}{{etheme_grid_gutter_tablet.UNIT}}), transparent calc((100% / {{etheme_grid_number_tablet.VALUE}}) - {{etheme_grid_gutter_tablet.SIZE}}{{etheme_grid_gutter_tablet.UNIT}}), transparent calc(100% / {{etheme_grid_number_tablet.VALUE}}) );',
					//Mobile view
					'(mobile) html.elementor-html::before' => '
					width: calc(100% - (2 * {{etheme_grid_offset_mobile.SIZE}}{{etheme_grid_offset_mobile.UNIT}}));
					max-width: {{etheme_grid_max_width_mobile.SIZE}}{{etheme_grid_max_width_mobile.UNIT}};
					background-size: calc(100% + {{etheme_grid_gutter_mobile.SIZE}}{{etheme_grid_gutter_mobile.UNIT}}) 100%;
					background-image: -webkit-repeating-linear-gradient( left, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number_mobile.VALUE}}) - {{etheme_grid_gutter_mobile.SIZE}}{{etheme_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{etheme_grid_number_mobile.VALUE}}) - {{etheme_grid_gutter_mobile.SIZE}}{{etheme_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{etheme_grid_number_mobile.VALUE}}) );
					background-image: repeating-linear-gradient( to right, {{etheme_grid_color.VALUE}}, {{etheme_grid_color.VALUE}} calc((100% / {{etheme_grid_number_mobile.VALUE}}) - {{etheme_grid_gutter_mobile.SIZE}}{{etheme_grid_gutter_mobile.UNIT}}), transparent calc((100% / {{etheme_grid_number_mobile.VALUE}}) - {{etheme_grid_gutter_mobile.SIZE}}{{etheme_grid_gutter_mobile.UNIT}}), transparent calc(100% / {{etheme_grid_number_mobile.VALUE}}) );',
				],
			]
		);
		
		$element->end_controls_section();
	}
	
}