<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Menu_List as Menu_Shortcode;

/**
 * Menu List widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Menu_List extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'et_menu_list';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Menu List', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-menu-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'menu', 'item', 'link', 'badge', 'label', 'list' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
    	return ['eight_theme_general'];
    }
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register Menu List widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {

		$divider = 0;

		$this->start_controls_section(
			'menu_list_settings',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		Elementor::get_menu_list_item( $repeater );

		$this->add_control(
			'menu_list_item',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Menu List Item 1', 'xstore-core' ),
					],
					[
						'title' => __( 'Menu List Item 2', 'xstore-core' ),
					],
					[
						'title' => __( 'Menu List Item 3', 'xstore-core' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->start_controls_tabs( 'menu_list_tabs' );

		$this->start_controls_tab(
			'menu_list_content',
			[
				'label' => __( 'Content', 'xstore-core' ),
			]
		);

		$this->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Title', 'xstore-core' )
			]
		);

		$this->add_control(
			'title_custom_tag',
			[
				'label' 		=>	__( 'Element Tag', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'h1'			=> esc_html__( 'H1', 'xstore-core' ), 
					'h2'			=> esc_html__( 'H2', 'xstore-core' ), 
					'h3'			=> esc_html__( 'H3', 'xstore-core' ), 
					'h4'			=> esc_html__( 'H4', 'xstore-core' ), 
					'h5'			=> esc_html__( 'H5', 'xstore-core' ), 
					'h6'			=> esc_html__( 'H6', 'xstore-core' ), 
					'p'				=> esc_html__( 'P', 'xstore-core' ), 
					'div'			=> esc_html__( 'DIV', 'xstore-core' ), 
				],
				'default'		=> 'h3',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::URL,
			]
		);

		$this->add_control(
			'label',
			[
				'label' 		=>	__( 'Label', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''		=>	esc_html__( 'Select label', 'xstore-core' ),
					'hot'	=>	esc_html__( 'Hot', 'xstore-core' ),
					'sale'	=>	esc_html__( 'Sale', 'xstore-core' ),
					'new'	=>	esc_html__( 'New', 'xstore-core' ),
				],
			]
		);

		// on update ( bug with fa icons on frontend )
		// $this->add_control(
		// 	'divider'.$divider++,
		// 	[
		// 		'label' => __( 'Icon', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::HEADING,
		// 		'separator' => 'before',
		// 	]
		// );

		// $this->add_control(
		// 	'add_icon',
		// 	[
		// 		'label' 		=>	__( 'Add icon ?', 'xstore-core' ),
		// 		'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
		// 		'return_value'  =>	'true',
		// 		'default' 		=>	'',
		// 	]
		// );

		// $this->add_control(
		// 	'type',
		// 	[
		// 		'label' 		=>	__( 'Icon library', 'xstore-core' ),
		// 		'type' 			=>	\Elementor\Controls_Manager::SELECT,
		// 		'options' 		=>	[
		// 			'svg'			=>	esc_html__( 'Icon', 'xstore-core' ),
		// 			'image'			=>	esc_html__( 'Upload image', 'xstore-core' ),
		// 		],
		// 		'default'		=> 'svg',
		// 		'condition' => [ 'add_icon' => 'true' ],
		// 	]
		// );

		// $this->add_control(
		// 	'icon_library',
		// 	[
		// 		'label' => __( 'Icon', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::ICONS,
		// 		'separator' => 'before',
		// 		'fa4compatibility' => 'icon',
		// 		'default' => [
		// 			'value' => 'et-icon et-gift',
		// 			'library' => 'xstore-icons',
		// 		],
		// 		'label_block' => false,
		// 		'skin' => 'inline',
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'icon_svg_size',
		// 	[
		// 		'label' => __( 'SVG width', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', 'em', 'rem' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 70,
		// 				'step' => 1,
		// 			],
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title img' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'position',
		// 	[
		// 		'label' 		=>	__( 'Position of the icon/image', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::CHOOSE,
		// 		'options' => [
		// 			'left-center' => [
		// 				'title' => __( 'Left', 'xstore-core' ),
		// 				'icon' => 'eicon-h-align-left',
		// 			],
		// 			'center-center' => [
		// 				'title' => __( 'Top', 'xstore-core' ),
		// 				'icon' => 'eicon-v-align-top',
		// 			],
		// 			'right-center' => [
		// 				'title' => __( 'Right', 'xstore-core' ),
		// 				'icon' => 'eicon-h-align-right',
		// 			],
		// 		],
		// 		'render_type' => 'template',
		// 		'default' => 'left-center',
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!=',
		// 					'value' 	=> ''
		// 				],
		// 			]
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'icon_library',
		// 	[
		// 		'label' => __( 'Icon', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::ICONS,
		// 		'fa4compatibility' => 'icon',
		// 		'label_block' => false,
		// 		'default' => [
		// 			'value' => 'et-icon et-gift',
		// 			'library' => 'xstore-icons',
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'icon',
		// 	[
		// 		'label' 	=> __( 'Icon', 'xstore-core' ),
		// 		'type' 		=> 'etheme-icon-control',
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// on update ( bug with fa icons on frontend )
		// $this->add_control(
		// 	'divider'.$divider++,
		// 	[
		// 		'label' => __( 'Image', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::HEADING,
		// 		'separator' => 'before',
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'image'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'img',
		// 	[
		// 		'label' => __( 'Image', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::MEDIA,
		// 		'default' => [
		// 			'url' => \Elementor\Utils::get_placeholder_image_src(),
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'image'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'img_size',
		// 	[
		// 		'label' => __( 'Image Size', 'xstore-core' ),
		// 		'type' 	=> \Elementor\Controls_Manager::TEXT,
		// 		'description' => __( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'image'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		$this->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'class',
			[
				'label' => __( 'CSS Classes', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_list_style',
			[
				'label' => __( 'Style', 'xstore-core' ),
			]
		);

		$this->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'title_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} .item-title-holder  .menu-title',
				'separator'   	=> 'before',
	        ]
	    );

		$this->add_control(
			'color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} .item-title-holder .menu-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' 	=> __( 'Color (hover)', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'align',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default'		=> 'left',
			]
		);

		// on update ( bug with fa icons on frontend )
		// $this->add_control(
		// 	'divider'.$divider++,
		// 	[
		// 		'label' => __( 'Icon', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::HEADING,
		// 		'separator' => 'before',
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!in',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// $this->add_control(
		// 	'icon_size',
		// 	[
		// 		'label' => __( 'Icon Size', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', 'em', 'rem' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 70,
		// 				'step' => 1,
		// 			],
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!=',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title i' => 'font-size: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'icon_spacing',
		// 	[
		// 		'label' => __( 'Spacing', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', 'em', 'rem' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 70,
		// 				'step' => 1,
		// 			],
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[value]',
		// 					'operator'  => '!=',
		// 					'value' 	=> ''
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .position-left-center i, {{WRAPPER}} .et-menu-list .item-title-holder .position-left-center img, {{WRAPPER}} .et-menu-list .item-title-holder .position-left-center svg' => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .position-center-center i, {{WRAPPER}} .et-menu-list .item-title-holder .position-center-center img, {{WRAPPER}} .et-menu-list .item-title-holder .position-center-center svg' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .position-right-center i, {{WRAPPER}} .et-menu-list .item-title-holder .position-right-center img, {{WRAPPER}} .et-menu-list .item-title-holder .position-right-center svg' => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// in next update will be improved not to use at this time
		// $this->add_control(
		// 	'icon_border_radius',
		// 	[
		// 		'label' => __( 'Border Radius', 'xstore-core' ),
		// 		'type' => \Elementor\Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', '%' ],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!in',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		]
		// 	]
		// );

		// on update ( bug with fa icons on frontend )
		// $this->add_control(
		// 	'icon_color',
		// 	[
		// 		'label' 	=> __( 'Icon Color', 'xstore-core' ),
		// 		'type' 		=> \Elementor\Controls_Manager::COLOR,
		// 		'scheme' 	=> [
		// 			'type' 	=> \Elementor\Scheme_Color::get_type(),
		// 			'value' => \Elementor\Scheme_Color::COLOR_1,
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!=',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title i' => 'fill: {{VALUE}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'icon_color_hover',
		// 	[
		// 		'label' 	=> __( 'Icon Color (hover)', 'xstore-core' ),
		// 		'type' 		=> \Elementor\Controls_Manager::COLOR,
		// 		'scheme' 	=> [
		// 			'type' 	=> \Elementor\Scheme_Color::get_type(),
		// 			'value' => \Elementor\Scheme_Color::COLOR_1,
		// 		],
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!=',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title:hover i' => 'fill: {{VALUE}};',
		// 		],
		// 	]
		// );

		// in next update will be improved not to use at this time
		// $this->add_control(
		// 	'icon_bg_color',
		// 	[
		// 		'label' 	=> __( 'Icon Background Color', 'xstore-core' ),
		// 		'type' 		=> \Elementor\Controls_Manager::COLOR,
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!in',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title i' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );

		// $this->add_control(
		// 	'icon_bg_color_hover',
		// 	[
		// 		'label' 	=> __( 'Icon Background Color (hover)', 'xstore-core' ),
		// 		'type' 		=> \Elementor\Controls_Manager::COLOR,
		// 		'conditions' 	=> [
		// 			'terms' 	=> [
		// 				[
		// 					'name' 		=> 'type',
		// 					'operator'  => '=',
		// 					'value' 	=> 'svg'
		// 				],
		// 				[
		// 					'name' 		=> 'add_icon',
		// 					'operator'  => '=',
		// 					'value' 	=> 'true'
		// 				],
		// 				[
		// 					'name' 		=> 'icon_library[library]',
		// 					'operator'  => '!in',
		// 					'value' 	=> ['svg', '']
		// 				],
		// 			]
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .et-menu-list .item-title-holder .menu-title:hover i' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );

		$this->add_control(
			'divider'.$divider++,
			[
				'label' => __( 'Link Paddings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_paddings',
			[
				'label' => __( 'Paddings', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .et-menu-list .item-title-holder  .menu-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Render Menu List widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array();
		foreach ( $settings as $key => $setting ) {
			if ( '_' == substr( $key, 0, 1) ) {
				continue;
			}

			if ( $setting ) {
				switch ($key) {
					// case 'icon':
					// 	// icon svg code
					// 	$icon_type = ET_CORE_DIR . 'app/assets/icon-fonts/svg/' . $setting;
					// 	$icon_type = file_get_contents( $icon_type );
					// 	$icon_type = base64_encode( $icon_type );
					// 	$atts['svg'] = $icon_type;
					// break;
					case 'link':
						$local_settings['build_link'] = array(
							'keys' => array(),
							'values' => array()
						);
						foreach ($setting as $sub_key => $value) {
							$local_settings['build_link']['keys'][] = $sub_key;
							$local_settings['build_link']['values'][] = ( !empty($value) ? $value : '' );
						}

					   $atts[$key] = implode(',', $local_settings['build_link']['keys']) . 
					   '|'
					    . implode(',', $local_settings['build_link']['values']);
						break;
					case 'icon_size':
					case 'icon_border_radius':
					case 'icon_svg_size':
					break;
					case 'img':
						$atts[$key] = isset( $setting['id'] ) ? $setting['id'] : '';

						if ( empty($atts[$key]) ) 
							$atts['img_backup'] = '<img src="'.\Elementor\Utils::get_placeholder_image_src().'" style="max-width: 50px;"/>';
					break;
					default:
						$atts[$key] = $setting;
					break;
				}
			}
		}

		// if ( $settings['add_icon'] ) {
		// 	ob_start();
		// 	\Elementor\Icons_Manager::render_icon( $settings['icon_library'], [ 'aria-hidden' => 'true' ] );
		// 	$atts['icon_rendered'] = ob_get_contents();
  //           ob_end_clean();
		// }

		$atts['use_custom_fonts_title'] = true;
		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
		$atts['is_elementor'] = true;

		$content = '';
		if ( $settings['menu_list_item'] ) {
			foreach (  $settings['menu_list_item'] as $item ) {

				// $item_icon_type = ET_CORE_DIR . 'app/assets/icon-fonts/svg/' . $item['icon'];
				// // icon svg code
				// if ( isset( $item['icon'] ) && strpos( $item['icon'], 'svg' ) ) {
				// 	$item_icon_type = file_get_contents( $item_icon_type );
				// 	$item_icon_type = base64_encode( $item_icon_type );
				// } else {
				// 	$item_icon_type = '';
				// }

				// if ( isset($item['img']) ) 
				// 	$item['img'] = isset( $item['img']['id'] ) ? $item['img']['id'] : '';

				$local_settings['build_link'] = array(
					'keys' => array(),
					'values' => array()
				);
				foreach ($item['link'] as $key => $value) {
					$local_settings['build_link']['keys'][] = $key;
					$local_settings['build_link']['values'][] = ( !empty($value) ? $value : '' );
				}

			   $item['link'] = implode(',', $local_settings['build_link']['keys']) . 
			   '|'
			    . implode(',', $local_settings['build_link']['values']);

				$item['el_class'] = ' elementor-repeater-item-' . $item['_id'];

				// if ( $item['add_icon'] ) {
				// 	ob_start();
				// 	\Elementor\Icons_Manager::render_icon( $item['icon_library'], [ 'aria-hidden' => 'true' ] );
				// 	$item['icon_rendered'] = ob_get_contents();
		  //           ob_end_clean();
				// }

				$pre_content = array(
					'title="'. $item['title'] .'"',
					'use_custom_fonts_title="true"',
					'title_link="'. $item['link'] .'"',
					'label="'. $item['label'] .'"',
					'title_custom_tag="'.$item['title_custom_tag'].'"',
					// 'add_icon="'. $item['add_icon'] .'"',
					// 'type="'. $item['type'] .'"',
					// 'icon_rendered="' . $item['icon_rendered'] . '"',
					// 'img="'. $item['img'] .'"',
					// 'img_backup="'. ( empty($item['img']) ? '<img src="'.\Elementor\Utils::get_placeholder_image_src().'" style="max-width: 50px;"/>' : '' ) . '"',
					// 'img_size="'. $item['img_size'] .'"',
					// 'position="'. $item['position'] .'"',
					'class="'. $item['class'] .'"',
					'el_class="' . $item['el_class'] . '"',
					'is_preview="'. $atts['is_preview'] .'"',
					'is_elementor="'.$atts['is_elementor'].'"'
				);

				$content .= '[et_menu_list_item ' . implode(' ', $pre_content) . ']';
			}
		}

		$Menu_Shortcode = Menu_Shortcode::get_instance();
		echo $Menu_Shortcode->menu_list_shortcode( $atts, $content );

	}

}
