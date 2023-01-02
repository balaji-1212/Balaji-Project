<?php
/**
 * Tooltip feature for Elementor templates
 *
 * @package    Tooltip.php
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


class Tooltip {
	
	function __construct() {
		
		add_action('elementor/element/common/_section_style/before_section_start', array($this, 'register_controls'));
		
		// Renders attributes for all Elementor Elements
		// add_action( 'elementor/frontend/widget/before_render', array($this, 'before_render' ), 10 ); // elementor/widget/before_render_content
		add_action( 'elementor/widget/before_render_content', array($this, 'before_render' ), 10 );
	}
	
	public function register_controls($element) {
		
		$element->start_controls_section(
			'_section_etheme_tooltip',
			[
				'label' => __('XSTORE Tooltip', 'xstore-core'),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);
		
		$element->add_control(
			'etheme_tooltip',
			[
				'label'       => __('Enable XStore tooltip', 'xstore-core'),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'template'
			]
		);
		
		$element->start_controls_tabs('etheme_tooltip_tabs');
		
		$element->start_controls_tab('etheme_tooltip_settings', [
			'label' => __('Settings', 'xstore-core'),
			'condition' => [
				'etheme_tooltip!' => '',
			],
		]);
		
		$element->add_control(
			'etheme_tooltip_content',
			[
				'label' => __('Content', 'xstore-core'),
				'type' => Controls_Manager::WYSIWYG,
				'rows' => 5,
				'default' => __('I am a tooltip', 'xstore-core'),
				'dynamic' => ['active' => true],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_position',
			[
				'label' => __('Position', 'xstore-core'),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => __('Top', 'xstore-core'),
					'bottom' => __('Bottom', 'xstore-core'),
					'left' => __('Left', 'xstore-core'),
					'right' => __('Right', 'xstore-core'),
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_animation',
			[
				'label' => __( 'Animation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'auto' => __( 'Auto', 'xstore-core' ),
					'fade' => __( 'Fade in', 'xstore-core' ),
					'bottom' => __( 'Bottom', 'xstore-core' ),
					'none' => __( 'None', 'xstore-core' ),
				],
				'default' => 'auto',
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_animation_duration',
			[
				'label' => __('Animation Duration (ms)', 'xstore-core'),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 5000,
				'default' => 300,
				'step' => 50,
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_animation!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--et-tooltip-duration: {{SIZE}}ms;',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_arrow',
			[
				'label' => __('Arrow', 'xstore-core'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_trigger',
			[
				'label' => __('Trigger', 'xstore-core'),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'click' => __('Click', 'xstore-core'),
					'hover' => __('Hover', 'xstore-core'),
				],
				'prefix_class' => 'etheme-elementor-tooltip-',
				'frontend_available' => true,
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_animation!' => 'none',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_offset',
			[
				'label' => __('Offset (px)', 'xstore-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--et-tooltip-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_offset_2',
			[
				'label' => __('Offset 2 (px)', 'xstore-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--et-tooltip-offset-2: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_align',
			[
				'label' => __('Text Alignment', 'xstore-core'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'xstore-core'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'xstore-core'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'xstore-core'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tooltip' => 'text-align: {{VALUE}};'
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->end_controls_tab();
		
		$element->start_controls_tab('etheme_tooltip_styles', [
			'label' => __('Styles', 'xstore-core'),
			'condition' => [
				'etheme_tooltip!' => '',
			],
		]);
		
		$element->add_responsive_control(
			'etheme_tooltip_width',
			[
				'label' => __('Width', 'xstore-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--et-tooltip-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_arrow_size',
			[
				'label' => __('Tooltip Arrow Size (px)', 'xstore-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--et-tooltip-arrow-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_arrow!' => '',
				],
			]
		);
		
		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'etheme_tooltip_typography',
				'separator' => 'after',
				'selector' => '{{WRAPPER}} .etheme-elementor-tooltip',
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'etheme_tooltip_title_section_bg_color',
				'label'    => __('Background', 'xstore-core'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .etheme-elementor-tooltip',
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_arrow!' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_background_color',
			[
				'label' => __('Background Color', 'xstore-core'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tooltip' => 'background: {{VALUE}};',
					'{{WRAPPER}} .etheme-elementor-tooltip::after' => '--et-tooltip-arrow-color: {{VALUE}}',
				],
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_arrow!' => '',
				],
			]
		);
		
		$element->add_control(
			'etheme_tooltip_color',
			[
				'label' => __('Text Color', 'xstore-core'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tooltip' => 'color: {{VALUE}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'etheme_tooltip_border',
				'label' => __('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-elementor-tooltip',
				'condition' => [
					'etheme_tooltip!' => '',
					'etheme_tooltip_arrow!' => 'yes',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_border_radius',
			[
				'label' => __('Border Radius', 'xstore-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_responsive_control(
			'etheme_tooltip_padding',
			[
				'label' => __('Padding', 'xstore-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'etheme_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-elementor-tooltip',
				'separator' => '',
				'condition' => [
					'etheme_tooltip!' => '',
				],
			]
		);
		
		$element->end_controls_tab();
		
		$element->end_controls_tabs();
		
		$element->end_controls_section();
	}
	
	public function before_render( $widget ) {
		$settings = $widget->get_settings_for_display();
		
		if ( isset( $settings['etheme_tooltip'] ) && $settings['etheme_tooltip'] ) {
			$widget->add_render_attribute( 'etheme-tooltip', 'class', [
				'etheme-elementor-tooltip',
			] );
			$widget->add_render_attribute('etheme-tooltip', 'data-position', $settings['etheme_tooltip_position']);
			$widget->add_render_attribute('etheme-tooltip', 'data-animation', $settings['etheme_tooltip_animation']);
			if ( !!$settings['etheme_tooltip_arrow'] ) {
				$widget->add_render_attribute( 'etheme-tooltip', 'class', [
					'etheme-elementor-tooltip-arrow',
				] );
			}
			?>
			
			<div <?php $widget->print_render_attribute_string( 'etheme-tooltip' ); ?>>
				<?php echo $settings['etheme_tooltip_content']; ?>
			</div>
			
			<?php
			
		}
	}
	
}