<?php
/**
 * Parallax feature for Elementor widgets
 *
 * @package    Parallax.php
 * @since      4.0.9
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class Parallax {
	
	function __construct() {
		
		// Add new controls to advanced tab globally
		add_action( "elementor/element/common/_section_style/before_section_start", array( $this, 'register_controls') );
		
		// Renders attributes for all Elementor Elements
		add_action( 'elementor/frontend/widget/before_render', array($this, 'before_render' ), 10 );
		
	}
	
	/**
	 * Add parallax controls.
	 *
	 * @param $element
	 * @return void
	 *
	 * @since 4.0.9
	 *
	 */
	public function register_controls( $element ){
		
		$element->start_controls_section(
			'etheme_section_extra',
			array(
				'label'     => __( 'XSTORE Effects', 'xstore-core' ),
				'tab'       => Controls_Manager::TAB_ADVANCED
			)
		);
		
		$element->add_control(
			'etheme_parallax',
			array(
				'label'        => __( 'Enable XStore Effects', 'xstore-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_mobile',
			array(
				'label'        => __( 'Enable parallax on mobile', 'xstore-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => array(
					'etheme_parallax!' => ''
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_type',
			array(
				'label'   => __( 'Type', 'xstore-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'scroll_effects'  => __( 'Scroll Effects', 'xstore-core' ),
					'3d_hover_effects'  => __( '3d Hover Effect', 'xstore-core' ),
					'hover_effects'  => __( 'Hover Effect', 'xstore-core' ),
					'floating_effects'  => __( 'Floating Effect', 'xstore-core' ),
				),
				'default'   => 'scroll_effects',
				'prefix_class' => 'etheme-parallax-',
				'condition' => array(
					'etheme_parallax!' => ''
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_scroll_scale',
			array(
				'label'        => esc_html__( 'Scale', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default' => [
					'size' => 1,
				],
				'range' => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3,
						'step' => .1
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_scroll_heading',
			[
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'label' => __( 'Moving by axis', 'xstore-core' ),
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
			]
		);
		
		$element->start_controls_tabs( 'etheme_parallax_scroll_tabs', [
			'condition' => array(
				'etheme_parallax!' => '',
				'etheme_parallax_type' => 'scroll_effects'
			)
		] );
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_tab_x',
			[
				'label' => __( 'X axis', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_x',
			[
				'label'        => esc_html__( 'X axis offset', 'xstore-core' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_tab_y',
			[
				'label' => __( 'Y axis', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_y',
			[
				'label'        => esc_html__( 'Y axis offset', 'xstore-core' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => -80,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_tab_z',
			[
				'label' => __( 'Z axis', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_z',
			[
				'label'        => esc_html__( 'Z axis offset', 'xstore-core' ),
				'description'  => esc_html__( 'Recommended -200 to 200', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		$element->end_controls_tabs();
		
		$element->add_control(
			'etheme_parallax_scroll_rotate_heading',
			[
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'label' => __( 'Rotate', 'xstore-core' ),
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_rotate_type',
			[
				'label'        => esc_html__( 'Type', 'xstore-core' ),
				'type'         => Controls_Manager::SELECT,
				'default' => 'simple',
				'options'      => [
					'3d'  => esc_html__('3d rotate', 'xstore-core'),
					'simple'  => esc_html__('Simple', 'xstore-core'),
				],
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_rotate',
			[
				'label'        => esc_html__( 'Angle', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default' => [
					'size' => 0,
				],
				'range' => array(
					'px' => array(
						'min'  => 0,
						'max'  => 360,
						'step' => 1
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
					'etheme_parallax_scroll_rotate_type' => 'simple'
				),
				'frontend_available' => true
			]
		);
		
		$element->start_controls_tabs( 'etheme_parallax_scroll_rotate_3d_tabs', [
			'condition' => array(
				'etheme_parallax!' => '',
				'etheme_parallax_type' => 'scroll_effects',
				'etheme_parallax_scroll_rotate_type' => '3d'
			)
		] );
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_rotate_3d_tab_x',
			[
				'label' => __( 'X rotate', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_rotateX',
			[
				'label'        => esc_html__( 'Angle', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
					'etheme_parallax_scroll_rotate_type' => '3d'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_rotate_3d_tab_y',
			[
				'label' => __( 'Y rotate', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_rotateY',
			[
				'label'        => esc_html__( 'Angle', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
					'etheme_parallax_scroll_rotate_type' => '3d'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		
		$element->start_controls_tab(
			'etheme_parallax_scroll_rotate_3d_tab_z',
			[
				'label' => __( 'Z rotate', 'xstore-core' ),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_rotateZ',
			[
				'label'        => esc_html__( 'Angle', 'xstore-core' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => 0,
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
					'etheme_parallax_scroll_rotate_type' => '3d'
				),
				'frontend_available' => true
			]
		);
		
		$element->end_controls_tab();
		$element->end_controls_tabs();
		
		$element->add_control(
			'etheme_parallax_scroll_advanced_heading',
			[
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'label' => __( 'Advanced', 'xstore-core' ),
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
			]
		);
		
		$element->add_control(
			'etheme_parallax_scroll_perspective',
			array(
				'label'        => esc_html__( 'Perspective', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 800
				],
				'range' => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 100
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects',
					'etheme_parallax_scroll_z!' => ''
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_scroll_smoothness',
			array(
				'label'        => esc_html__( 'Smoothness', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default' => [
					'size'=> 30
				],
				'range' => array(
					'px' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 5
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'scroll_effects'
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_maxTilt',
			array(
				'label'        => esc_html__( 'Smoothness', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 20
				],
				'range' => array(
					'px' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 5
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_scale',
			array(
				'label'        => esc_html__( 'Scale', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 1.1
				],
				'range' => array(
					'px' => array(
						'min'  => 1,
						'max'  => 3,
						'step' => .1
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			)
		);

//	    $element->add_control(
//		    'etheme_parallax_3d_hover_speed',
//		    [
//			    'label'        => esc_html__( 'Speed', 'xstore-core' ),
//			    'type'         => Controls_Manager::TEXT,
//			    'default'      => 300,
//			    'render_type'  => 'template',
//			    'condition' => array(
//				    'etheme_parallax!' => '',
//				    'etheme_parallax_type' => '3d_hover_effects'
//			    )
//		    ]
//	    );
		
		$element->add_control(
			'etheme_parallax_3d_hover_speed',
			array(
				'label'        => esc_html__( 'Speed', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 400
				],
				'range' => array(
					'px' => array(
						'min'  => 100,
						'max'  => 2000,
						'step' => 100
					)
				),
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_perspective',
			array(
				'label'        => esc_html__( 'Perspective', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 700
				],
				'range' => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 100
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_disableAxis',
			[
				'label'        => esc_html__( 'Disable axis', 'xstore-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					''  => __('None', 'xstore-core'),
					'x'  => 'X',
					'y'  => 'y',
				],
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			]
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_glare',
			array(
				'label'        => esc_html__( 'Glare effect', 'xstore-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects'
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_3d_hover_glare_max',
			array(
				'label'        => esc_html__( 'Max Glare', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 1,
						'step' => .1
					)
				),
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => '3d_hover_effects',
					'etheme_parallax_3d_hover_glare!' => ''
				),
				'frontend_available' => true
			)
		);
		
		$element->add_control(
			'etheme_parallax_hover_smoothness',
			array(
				'label'        => esc_html__( 'Smoothness', 'xstore-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'      => [
					'size' => 50
				],
				'range' => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1
					)
				),
				'render_type'  => 'template',
				'condition' => array(
					'etheme_parallax!' => '',
					'etheme_parallax_type' => 'hover_effects'
				),
				'frontend_available' => true
			)
		);
		
		//
		
		$element->add_control(
			'etheme_parallax_floating_translate_toggle',
			[
				'label' => __( 'Translate', 'xstore-core' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
//			    'return_value' => 'yes',
				'default' => 'yes',
				'starter_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'etheme_parallax_type' => 'floating_effects'
				]
			]
		);
		
		$element->start_popover();
		
		$element->add_control(
			'etheme_parallax_floating_translate_x',
			[
				'label' => __( 'Translate X', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 15,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_translate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_translate_y',
			[
				'label' => __( 'Translate Y', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 15,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_translate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_translate_duration',
			[
				'label' => __( 'Duration', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 100
					]
				],
				'default' => [
					'size' => 1000,
				],
				'condition' => [
					'etheme_parallax_floating_translate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_translate_delay',
			[
				'label' => __( 'Delay', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 100
					]
				],
				'condition' => [
					'etheme_parallax_floating_translate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->end_popover();
		
		$element->add_control(
			'etheme_parallax_floating_rotate_toggle',
			[
				'label' => __( 'Rotate', 'xstore-core' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'etheme_parallax_type' => 'floating_effects'
				]
			]
		);
		
		$element->start_popover();
		
		$element->add_control(
			'etheme_parallax_floating_rotate_x',
			[
				'label' => __( 'Rotate X', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 45,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_rotate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_rotate_y',
			[
				'label' => __( 'Rotate Y', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 45,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_rotate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_rotate_z',
			[
				'label' => __( 'Rotate Z', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 45,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_rotate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_rotate_duration',
			[
				'label' => __( 'Duration', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 100
					]
				],
				'default' => [
					'size' => 1000,
				],
				'condition' => [
					'etheme_parallax_floating_rotate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_rotate_delay',
			[
				'label' => __( 'Delay', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 100
					]
				],
				'condition' => [
					'etheme_parallax_floating_rotate_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->end_popover();
		
		$element->add_control(
			'etheme_parallax_floating_scale_toggle',
			[
				'label' => __( 'Scale', 'xstore-core' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'frontend_available' => true,
				'condition' => [
					'etheme_parallax_type' => 'floating_effects'
				]
			]
		);
		
		$element->start_popover();
		
		$element->add_control(
			'etheme_parallax_floating_scale_x',
			[
				'label' => __( 'Scale X', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 1,
						'to' => 1.2,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_scale_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_scale_y',
			[
				'label' => __( 'Scale Y', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 1,
						'to' => 1.2,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'labels' => [
					__( 'From', 'xstore-core' ),
					__( 'To', 'xstore-core' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'condition' => [
					'etheme_parallax_floating_scale_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_scale_duration',
			[
				'label' => __( 'Duration', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 100
					]
				],
				'default' => [
					'size' => 1000,
				],
				'condition' => [
					'etheme_parallax_floating_scale_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->add_control(
			'etheme_parallax_floating_scale_delay',
			[
				'label' => __( 'Delay', 'xstore-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 100
					]
				],
				'condition' => [
					'etheme_parallax_floating_scale_toggle' => 'yes',
					'etheme_parallax_type' => 'floating_effects'
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		
		$element->end_popover();
		
		$element->end_controls_section();
	}
	
	/**
	 * Description of the function.
	 *
	 * @param $widget
	 * @return void
	 *
	 * @since 4.0.9
	 *
	 */
	public function before_render( $widget ) {
		$settings = $widget->get_settings_for_display();
		
		if ( isset( $settings['etheme_parallax'] ) && $settings['etheme_parallax'] ) {
			
			switch ($settings['etheme_parallax_type']) {
				
				case 'scroll_effects':
					$widget->add_script_depends( 'etheme_parallax_scroll_effect' );
					wp_enqueue_script('etheme_parallax_scroll_effect'); // works always
					break;
				
				case '3d_hover_effects':
					$widget->add_script_depends( 'etheme_parallax_3d_hover_effect' ); // not always
					wp_enqueue_script('etheme_parallax_3d_hover_effect'); // works always
					break;
				
				case 'hover_effects':
					$widget->add_script_depends( 'etheme_parallax_hover_effect' ); // not always
					wp_enqueue_script('etheme_parallax_hover_effect'); // works always
					break;
				
				case 'floating_effects':
					$widget->add_script_depends( 'etheme_parallax_floating_effect' ); // not always
					wp_enqueue_script('etheme_parallax_floating_effect'); // works always
					break;
				
				default;
			}
			
		}
	}
	
}