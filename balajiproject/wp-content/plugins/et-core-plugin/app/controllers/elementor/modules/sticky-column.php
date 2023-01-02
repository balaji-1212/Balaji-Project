<?php
/**
 * Sticky Column feature for Elementor columns
 *
 * @package    Sticky-column.php
 * @since      4.3.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


class Sticky_Column {
	
	function __construct() {
	 
		// modify column setting to add sticky column option
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'register_controls' ), 10, 2 );
		
		add_action( 'elementor/frontend/column/before_render', array( $this, 'column_before_render' ) );
		
		add_action( 'elementor/frontend/element/before_render', array( $this, 'column_before_render' ) );
		
	}
	
	/**
	 * After column_layout callback
	 *
	 * @param  object $element
	 * @param  array $args
	 * @return void
	 */
	public function register_controls( $element, $args ) {
		
		if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
			$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
			$breakpoints_list   = array();
			
			foreach ($active_breakpoints as $key => $value) {
				$breakpoints_list[$key] = $value->get_label();
			}
			
			$breakpoints_list['desktop'] = 'Desktop';
			$breakpoints_list            = array_reverse($breakpoints_list);
		} else {
			$breakpoints_list = array(
				'desktop' => 'Desktop',
				'tablet'  => 'Tablet',
				'mobile'  => 'Mobile'
			);
		}
		
		$element->start_controls_section(
			'etheme_column_extra_settings',
			array(
				'label' => esc_html__( 'XStore Sticky Content', 'xstore-core' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);
		
		$element->add_control(
			'etheme_column_sticky',
			array(
				'label'        => esc_html__( 'Sticky Column', 'xstore-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'description' 	=>	__( 'Works for live mode only, not for the editor mode', 'xstore-core' ),
				'frontend_available' => true,
			)
		);
		
		$element->add_control(
			'etheme_column_sticky_top_offset',
			array(
				'label'   => esc_html__( 'Top Spacing', 'xstore-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 0,
				'max'     => 500,
				'step'    => 1,
				'frontend_available' => true,
				'condition' => array(
					'etheme_column_sticky!' => '',
				),
			)
		);
		
		$element->add_control(
			'etheme_column_sticky_on',
			array(
				'label'    => __( 'Sticky On', 'xstore-core' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => array(
					'desktop',
					'tablet',
				),
				'frontend_available' => true,
				'options' => $breakpoints_list,
				'condition' => array(
					'etheme_column_sticky!' => '',
				),
//				'render_type'        => 'none',
			)
		);
		
		$element->end_controls_section();
	}
	
	/**
	 * [column_before_render description]
	 * @param  [type] $element [description]
	 * @return [type]          [description]
	 */
	public function column_before_render( $element ) {
		$data     = $element->get_data();
		$type     = isset( $data['elType'] ) ? $data['elType'] : 'column';
		$settings = $data['settings'];
		
		if ( 'column' !== $type ) {
			return false;
		}
		
		if ( isset( $settings['etheme_column_sticky'] ) ) {
			
			if ( filter_var( $settings['etheme_column_sticky'], FILTER_VALIDATE_BOOLEAN ) ) {
				
				$element->add_render_attribute( '_wrapper', array(
					'class'         => 'etheme-elementor-sticky-column',
				) );
				
				$element->add_script_depends( 'sticky-kit' );
				$element->add_script_depends( 'etheme_elementor_sticky_column' );
				wp_enqueue_script('sticky-kit'); // works always
				wp_enqueue_script('etheme_elementor_sticky_column'); // works always
			}
			
		}
	}
	
}