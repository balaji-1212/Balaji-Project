<?php
/**
 * Description
 *
 * @package    posts-tabs.php
 * @since      4.1.3
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Posts Tabs widget.
 *
 * @since      4.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Posts_Tabs extends \Elementor\Widget_Base {
	
	use Elementor;
	
	protected static $query_args;
	protected static $id;
	protected static $page_link;
	protected static $widget_type;
	
	public static $instance = null;
	
	/**
	 * Get widget name.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_posts_tabs';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts Tabs', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-posts-tab';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'masonry', 'isotope', 'post', 'query', 'post type', 'grid', 'blog', 'layout', 'tab' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
	    $styles = ['etheme-elementor-posts'];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$styles += array(
				'elementor-icons-fa-solid',
				'elementor-icons-fa-regular',
				'elementor-icons-fa-brands',
			);
		}
		return $styles;
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.1.3
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		$scripts = ['etheme_elementor_tabs'];
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
			$scripts[] = 'etheme_post_product';
		return $scripts;
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
	 * Register widget controls.
	 *
	 * @since 4.1.3
	 * @access protected
	 */
	protected function register_controls() {
        		
        $this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_responsive_control(
			'cols',
			[
				'label' => __( 'Columns', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '3',
				'selectors' => [
					'{{WRAPPER}}' => '--cols: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tabs_position',
			[
				'label' => __( 'Tabs Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'horizontal' => esc_html__('Horizontal', 'xstore-core'),
					'vertical' => esc_html__('Vertical', 'xstore-core'),
				],
				'default' => 'horizontal',
				'prefix_class'          => 'etheme-elementor-tabs-',
			]
		);
		
		
		$this->add_responsive_control(
			'tabs_column_width',
			[
				'label' => __( 'Columns Proportion', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'separator' => 'after',
				'default' => [
					'unit' => '%'
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 70,
						'step' => 1,
					],
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'tabs_position' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-width-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'tabs_type',
			[
				'label' => __( 'Tabs Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    'simple' => esc_html__('Simple', 'xstore-core'),
                    'folders' => esc_html__('Folders', 'xstore-core'),
                    'overline' => esc_html__('Overline', 'xstore-core'),
                    'underline' => esc_html__('Underline', 'xstore-core'),
				],
				'default' => 'simple',
//				'selectors_dictionary'  => [
//					'folders'          => '--etheme-tabs-space-bottom: 0;',
//					'simple' => '',
//                    'overline' => '',
//                    'underline'          => '',
//				],
//				'selectors' => [
//					'{{WRAPPER}}' => '{{VALUE}};',
//				],
				'prefix_class'          => 'etheme-elementor-tabs-',
			]
		);
		
		$this->add_control(
			'tab_icon_align',
			[
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'tab_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
                    'size' => 7
                ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme-elementor-tab-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'color_schema',
			[
				'label' => __( 'Color Schema', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'dark' => esc_html__('Dark', 'xstore-core'),
					'white' => esc_html__('White', 'xstore-core'),
				],
				'default' => 'white',
				'selectors_dictionary'  => [
					'dark'          => '--post-color-white: #222; --post-color-dark: #fff; --post-color-dark-15: rgba(255,255,255,.15); --post-color-dark-07: rgba(255,255,255,.7); --post-color-grey: #fff; --content-padding: 15px;',
					'white'          => '',
				],
//				'condition' => [
//                    'post_image!' => ''
//                ],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'content_position',
			[
				'label' => __( 'Content Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'inside' => esc_html__('Inside', 'xstore-core'),
					'outside' => esc_html__('Outside', 'xstore-core'),
				],
				'default' => 'inside',
				'render_type' => 'template',
				'selectors_dictionary'  => [
					'inside'          => '--image-space: 0px; --image-width-proportion: 50%; --content-padding: 25px; --terms-label-offset-y: 30px; --post-meta-padding: 10px 0 0;',
					'outside'          => '',
				],
				'condition' => [
					'post_image!' => ''
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_column_width',
			[
				'label' => __( 'Columns Proportion', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'separator' => 'after',
				'default' => [
					'unit' => '%'
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 70,
						'step' => 1,
					],
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'post_image!' => '',
					'image_position!' => 'top',
					'content_position!' => 'inside'
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-width-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'button'		=>	esc_html__('Load More', 'xstore-core'),
					'scroll'	=>	esc_html__('Infinite Scroll', 'xstore-core'),
					'pagination'		=>	esc_html__('Pagination', 'xstore-core'),
					'none'		=>	esc_html__('None', 'xstore-core'),
				],
				'frontend_available' => true,
				'default' => 'none',
			]
		);
		
		$this->add_control(
			'navigation_pagination_ajax',
			[
				'label'        => esc_html__( 'Ajax Pagination', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'navigation' => 'pagination',
				],
				'frontend_available' => true,
			]
		);
		
		$this->add_control(
			'navigation_button_text',
			[
				'label' 		=>	__( 'Button Text', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __('Load More', 'xstore-core'),
				'condition' => [
					'navigation' => 'button',
				],
			]
		);
		
		$this->add_control(
			'posts_per_page',
			[
				'label'      => esc_html__( 'Load Items Per Iteration', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 4,
				'frontend_available' => true,
				'condition'  => [
					'navigation!' => 'none',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_post_settings',
			[
				'label' => esc_html__( 'Post', 'xstore-core' ),
			]
		);
		
		
		$post_elements = self::get_post_elements();
		
		foreach ($post_elements as $key => $value) {
			$this->add_control(
				'post_'.$key,
				[
					'label'        => $value,
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default' => in_array($key, array('image', 'title', 'meta', 'excerpt', 'button', 'categories')) ? 'yes' : ''
				]
			);
			
			// injection of some options for specific keys
			switch ($key) {
				case 'image':
					// make as filter for image
					$this->add_group_control(
						\Elementor\Group_Control_Image_Size::get_type(),
						[
							'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
							'default' => 'medium',
							'separator' => 'none',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						$key.'_hover_effect',
						[
							'label'       => esc_html__( 'Hover Effect', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'scaleIn' => esc_html__('ScaleIn', 'xstore-core'),
								'scaleOut' => esc_html__('Scale Out', 'xstore-core'),
								'slideRtl' => esc_html__('Slide Rtl', 'xstore-core'),
								'slideScaleOut' => esc_html__('Slide + Scale Out', 'xstore-core'),
								'none' => esc_html__('None', 'xstore-core'),
							],
							'default' => 'scaleIn',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						$key.'_position',
						[
							'label' => __( 'Image Position', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::CHOOSE,
							'options' => [
								'left'    => [
									'title' => __( 'Left', 'xstore-core' ),
									'icon' => 'eicon-h-align-left',
								],
								'top' => [
									'title' => __( 'Top', 'xstore-core' ),
									'icon' => 'eicon-v-align-top',
								],
								'right' => [
									'title' => __( 'Right', 'xstore-core' ),
									'icon' => 'eicon-h-align-right',
								],
							],
							'default' => 'top',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						$key.'_link',
						[
							'label'        => __('Image link', 'xstore-core'),
							'type'         => \Elementor\Controls_Manager::SWITCHER,
							'default' => 'yes',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						'post_date_label',
						[
							'label'       => esc_html__( 'Date Label Position', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::CHOOSE,
							'options' => [
								'inside'    => [
									'title' => __( 'On Image', 'xstore-core' ),
									'icon' => 'eicon-image-hotspot',
								],
								'top' => [
									'title' => __( 'On Image Top', 'xstore-core' ),
									'icon' => 'eicon-v-align-top',
								],
								'none' => [
									'title' => __( 'None', 'xstore-core' ),
									'icon' => 'eicon-ban',
								],
							],
							'default' => 'inside',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						'img_size_divider',
						[
							'type' => \Elementor\Controls_Manager::DIVIDER,
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					break;
				case 'title':
				case 'excerpt':
				    if ( $key == 'title' ) {
					    $this->add_control(
						    'post_'.$key.'_html_tag',
						    [
							    'label' => esc_html__( 'HTML Tag', 'xstore-core' ),
							    'type' => \Elementor\Controls_Manager::SELECT,
							    'options' => [
								    'h1' => 'H1',
								    'h2' => 'H2',
								    'h3' => 'H3',
								    'h4' => 'H4',
								    'h5' => 'H5',
								    'h6' => 'H6',
								    'div' => 'div',
								    'span' => 'span',
								    'p' => 'p',
							    ],
							    'default' => 'h3',
						    ]
					    );
                    }
					$this->add_control(
						'post_'.$key.'_limit_type',
						[
							'label'       => esc_html__( 'Limit By', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'chars' => esc_html__('Chars', 'xstore-core'),
								'words' => esc_html__('Words', 'xstore-core'),
								'lines' => esc_html__('Lines', 'xstore-core'),
								'none' => esc_html__('None', 'xstore-core'),
							],
							'default' => 'none',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						'post_'.$key.'_limit',
						[
							'label'      => esc_html__( 'Limit', 'xstore-core' ),
							'type'       => \Elementor\Controls_Manager::NUMBER,
							'min' => 0,
							'max' => 200,
							'step' => 1,
							'condition' => [
								'post_'.$key.'!' => '',
								'post_'.$key.'_limit_type' => ['chars', 'words']
							]
						]
					);
					
					$selector = '{{WRAPPER}} .etheme-post-content .etheme-post-title a';
					if ( $key == 'excerpt' )
						$selector = '{{WRAPPER}} .etheme-post-content .etheme-post-excerpt';
					
					$this->add_control(
						'post_'.$key.'_lines_limit',
						[
							'label'      => esc_html__( 'Lines Limit', 'xstore-core' ),
							'description' => esc_html__( 'Line-height will not work with this option. Don\'t set it up in typography settings.', 'xstore-core' ),
							'type'       => \Elementor\Controls_Manager::NUMBER,
							'min' => 1,
							'max' => 20,
							'step' => 1,
							'default' => 2,
							'condition' => [
								'post_'.$key.'!' => '',
								'post_'.$key.'_limit_type' => 'lines'
							],
							'selectors' => [
								'{{WRAPPER}}' => '--post-'.$key.'-lines: {{VALUE}};',
								$selector => 'height: calc(var(--post-'.$key.'-lines) * 3ex); line-height: 3ex; overflow: hidden;',
							],
						]
					);
					
					$this->add_control(
						'post_'.$key.'_divider',
						[
							'type' => \Elementor\Controls_Manager::DIVIDER,
							'condition' => [
								'post_'.$key.'!' => '',
							]
						]
					);
					break;
				case 'button':
					
					$this->add_control(
						'post_'.$key.'_type',
						[
							'label'       => esc_html__( 'Button Type', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'button' => esc_html__('Button', 'xstore-core'),
								'text' => esc_html__('Text', 'xstore-core'),
							],
							'default' => 'text',
							'condition' => [
								'post_'.$key.'!' => ''
							]
						]
					);
					
					$this->add_control(
						'post_'.$key.'_text',
						[
							'label' => __( 'Button Text', 'xstore-core' ),
							'type' => \Elementor\Controls_Manager::TEXT,
							'default' => esc_html__('Read More', 'xstore-core'),
							'condition' => [
								'post_'.$key.'!' => '',
							],
						]
					);
					
//					$this->add_control(
//						'post_'.$key.'_custom_selected_icon',
//						[
//							'label' => __( 'Button Icon', 'xstore-core' ),
//							'type' => \Elementor\Controls_Manager::ICONS,
//							'fa4compatibility' => 'post_'.$key.'_custom_icon',
//							'skin' => 'inline',
//							'label_block' => false,
//							'condition' => [
//								'post_'.$key.'!' => '',
//							],
//						]
//					);
//
//					$this->add_control(
//						'post_'.$key.'_icon_align',
//						[
//							'label' => __( 'Icon Position', 'xstore-core' ),
//							'type' => \Elementor\Controls_Manager::SELECT,
//							'default' => 'left',
//							'options' => [
//								'left' => __( 'Before', 'xstore-core' ),
//								'right' => __( 'After', 'xstore-core' ),
//							],
//							'condition' => [
//								'post_'.$key.'!' => '',
//								'post_'.$key.'_custom_selected_icon[value]!' => ''
//							],
//						]
//					);
//
//					$this->add_control(
//						'post_'.$key.'_custom_icon_indent',
//						[
//							'label' => __( 'Icon Spacing', 'xstore-core' ),
//							'type' => \Elementor\Controls_Manager::SLIDER,
//							'range' => [
//								'px' => [
//									'max' => 50,
//								],
//							],
//							'default' => [
//								'size' => 7
//							],
//							'selectors' => [
//								'{{WRAPPER}} .etheme-post-button .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
//								'{{WRAPPER}} .etheme-post-button .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
//							],
//							'condition' => [
//								'post_'.$key.'!' => '',
//								'post_'.$key.'_custom_selected_icon[value]!' => '',
//							],
//						]
//					);
					break;
				case 'meta':
					$this->add_control(
						'post_'.$key.'_data',
						[
							'label' => __( 'Meta Data', 'xstore-core' ),
							'label_block' => true,
							'type' => \Elementor\Controls_Manager::SELECT2,
							'default' => [ 'author', 'date', 'comments', 'share' ],
							'multiple' => true,
							'options' => apply_filters('etheme_posts_post_meta_data', [
								'author' => __( 'Author', 'xstore-core' ),
								'date' => __( 'Date', 'xstore-core' ),
								'comments' => __( 'Comments', 'xstore-core' ),
								'modified' => __( 'Date Modified', 'xstore-core' ),
								'share' => __('Share', 'xstore-core')
							]),
							'separator' => 'before',
							'condition' => [
								'post_'.$key.'!' => '',
							],
						]
					);
					
					$this->add_control(
						'post_'.$key.'_share_data',
						[
							'label' => __( 'Share Data', 'xstore-core' ),
							'label_block' => true,
							'type' => \Elementor\Controls_Manager::SELECT2,
							'multiple' => true,
							'options' 		=> array(
								'facebook' => __('Facebook', 'xstore-core'),
								'twitter' => __('Twitter', 'xstore-core'),
								'linkedin' => __('Linkedin', 'xstore-core'),
								'vk' => __('Vk', 'xstore-core'),
								'pinterest' => __('Pinterest', 'xstore-core'),
								'whatsapp' => __('Whatsapp', 'xstore-core'),
							),
							'default' => array(
								'facebook',
								'twitter',
								'linkedin'
							),
							'separator' => 'before',
							'condition' => [
								'post_'.$key.'!' => '',
								'post_'.$key.'_data' => 'share',
							]
						]
					);
					
					$this->add_control(
						'post_'.$key.'_divider',
						[
							'type' => \Elementor\Controls_Manager::DIVIDER,
							'condition' => [
								'post_'.$key.'!' => '',
							]
						]
					);
					break;
                case 'categories':
				case 'tags':
                    $this->add_control(
                        'post_'.$key.'_position',
                        [
                            'label'       => esc_html__( 'Position', 'xstore-core' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'image' => esc_html__('On Image', 'xstore-core'),
                                'content' => esc_html__('In Content', 'xstore-core'),
                            ],
                            'default' => $key == 'categories' ? 'image' : 'content',
                            'condition' => [
                                'post_image!' => '',
                                'post_'.$key.'!' => ''
                            ]
                        ]
                    );
                    $this->add_control(
                        'post_'.$key.'_limit',
                        [
                            'label'      => esc_html__( 'Limit', 'xstore-core' ),
                            'type'       => \Elementor\Controls_Manager::NUMBER,
                            'separator' => 'after',
                            'min' => 1,
                            'max' => 10,
                            'step' => 1,
                            'condition' => [
                                'post_'.$key.'!' => ''
                            ]
                        ]
                    );
                    break;
			}
		}
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tabs', 'xstore-core' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'tab_title',
			[
				'label' 		=>	__( 'Title', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'default' => __( 'Tab 01', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'tab_title_selected_icon', [
				'label' => esc_html__('Icon', 'xstore-core'),
				'label_block' => false,
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'fa4compatibility' => 'tab_title_icon',
                'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
			]
		);
		
		$repeater->add_control(
			'query_heading',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'query_type',
			[
				'label' 		=>	__( 'Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'all' => esc_html__( 'All Posts', 'xstore-core' ),
					'posts_ids' => esc_html__( 'List of IDs', 'xstore-core' ),
					'categories' => esc_html__('By Categories', 'xstore-core'),
					'tags' => esc_html__('By Tags', 'xstore-core')
				],
				'default'	=> 'all'
			]
		);
		
		$repeater->add_control(
			'limit',
			[
				'label'      => esc_html__( 'Posts Limit', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'default' => 3,
				'condition'  => [
					'query_type!' => [ 'post_ids' ],
				],
			]
		);
		
		$repeater->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'description' => __('Number of post to displace or pass over.', 'xstore-core') .
				                 ' <a href="https://developer.wordpress.org/reference/classes/wp_query/#pagination-parameters#:~:text=offset%20(int)%20%E2%80%93%20number%20of%20post%20to%20displace%20or%20pass%20over" rel="nofollow" target="_blank">' .
				                 __('More info', 'xstore-core') .
				                 '</a>',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'condition'   => [
					'query_type!' => 'posts_ids',
					'orderby!' => 'rand',
				],
			]
		);
		
		$repeater->add_control(
			'posts_ids',
			[
				'label'       => esc_html__( 'Include Only', 'xstore-core' ),
				'description' => esc_html__( 'Add posts by title.', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> esc_html__('Enter List of Posts', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'post' ),
				],
				'condition'   => [
					'query_type' => 'posts_ids',
				],
			]
		);
		
		$repeater->add_control(
			'exclude_ids',
			[
				'label'       => esc_html__( 'Exclude By', 'xstore-core' ),
				'description' => esc_html__( 'Add posts by title.', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> esc_html__('Enter List of Posts', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'post' ),
				],
				'condition'   => [
					'query_type!' => 'posts_ids',
				],
			]
		);
		
		$repeater->add_control(
			'categories',
			[
				'label' 		=>	__( 'Categories', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Enter categories', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>  Elementor::get_terms( 'category', false, false ),
				'condition'  => [
					'query_type' => [ 'categories' ],
				],
			]
		);
		
		$repeater->add_control(
			'tags',
			[
				'label' 		=>	__( 'Tags', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Enter tags', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>  Elementor::get_terms( 'post_tag', false, false ),
				'condition'  => [
					'query_type' => [ 'tags' ],
				],
			]
		);
		
		$repeater->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'post_date',
				'options'   => array(
					'post_date' => __( 'Date', 'xstore-core' ),
					'post_title' => __( 'Title', 'xstore-core' ),
					'menu_order' => __( 'Menu Order', 'xstore-core' ),
					'rand' => __( 'Random', 'xstore-core' ),
				),
				'condition' => [
					'query_type!' => 'posts_ids',
				],
			]
		);
		
		$repeater->add_control(
			'order',
			[
				'label'     => esc_html__( 'Sort Order', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'ASC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'xstore-core' ),
					'ASC'  => esc_html__( 'Ascending', 'xstore-core' ),
				),
//				'condition' => [
//					'query_type!' => 'posts_ids',
//				],
			]
		);
		
		$repeater->add_control(
			'ignore_sticky_posts',
			[
				'label'        => esc_html__( 'Ignore Sticky Posts', 'xstore-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'query_type' => 'all',
				],
				'description' => __( 'Sticky-posts ordering is visible on frontend only', 'xstore-core' ),
			]
		);
		
		$repeater->add_control(
			'select_date',
			[
				'label' => __( 'Date', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'anytime' => __( 'All', 'xstore-core' ),
					'today' => __( 'Past Day', 'xstore-core' ),
					'week' => __( 'Past Week', 'xstore-core' ),
					'month'  => __( 'Past Month', 'xstore-core' ),
					'quarter' => __( 'Past Quarter', 'xstore-core' ),
					'year' => __( 'Past Year', 'xstore-core' ),
					'exact' => __( 'Custom', 'xstore-core' ),
				],
				'default' => 'anytime',
				'condition' => [
					'query_type!' => [ 'posts_ids' ],
				],
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'date_before',
			[
				'label' => __( 'Before', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'label_block' => false,
				'multiple' => false,
				'placeholder' => __( 'Choose', 'xstore-core' ),
				'condition' => [
					'select_date' => 'exact',
					'query_type!' => [ 'posts_ids' ],
				],
				'description' => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'xstore-core' ),
			]);
		
		$repeater->add_control(
			'date_after',
			[
				'label' => __( 'After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'label_block' => false,
				'placeholder' => __( 'Choose', 'xstore-core' ),
				'condition' => [
					'select_date' => 'exact',
					'query_type!' => [ 'posts_ids' ],
				],
				'description' => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'xstore-core' ),
			]);
		
		$this->add_control(
			'items',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ tab_title }}}',
				'default' => [
					[
                        'tab_title' => __('Tab 01', 'xstore-core'),
                        'tab_title_selected_icon' => [
	                        'value' => 'far fa-star',
	                        'library' => 'fa-regular',
                        ]
					],
					[
						'tab_title' => __('Tab 02', 'xstore-core'),
                        'tab_title_selected_icon' => [
							'value' => 'far fa-gem',
							'library' => 'fa-regular',
						],
                        'order' => 'DESC'
					],
                    [
						'tab_title' => __('Tab 03', 'xstore-core'),
						'tab_title_selected_icon' => [
							'value' => 'far fa-lightbulb',
							'library' => 'fa-regular',
						]
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'cols_gap',
			[
				'label' => __( 'Columns Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cols-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'rows_gap',
			[
				'label' => __( 'Rows Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--rows-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// tabs
		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'tabs_stretch_items',
			[
				'label' 		=> esc_html__( 'Stretch items', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab' => 'flex: 1;',
				],
				'condition' => [
					'tabs_position' => 'horizontal',
				],
			]
		);
		
		$this->add_control(
			'tabs_horizontal_alignment',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'' => esc_html__( 'Default', 'xstore-core' ),
					'flex-start' => esc_html__( 'Start', 'xstore-core' ),
					'center' => esc_html__( 'Center', 'xstore-core' ),
					'flex-end' => esc_html__( 'End', 'xstore-core' ),
//					'space-between' => esc_html__( 'Space Between', 'xstore-core' ),
//					'space-around' => esc_html__( 'Space Around', 'xstore-core' ),
//					'space-evenly' => esc_html__( 'Space Evenly', 'xstore-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tabs-wrapper' => 'justify-content: {{VALUE}}',
				],
				'condition' => [
					'tabs_position' => 'horizontal',
                    'tabs_stretch_items' => ''
				],
			]
		);
		
		$this->add_control(
			'tabs_vertical_alignment',
			[
				'label' => __( 'Vertical Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'xstore-core' ),
					'top' => esc_html__( 'Top', 'xstore-core' ),
					'middle' => esc_html__( 'Middle', 'xstore-core' ),
					'bottom' => esc_html__( 'Bottom', 'xstore-core' ),
//					'space-between' => esc_html__( 'Space Between', 'xstore-core' ),
//					'space-around' => esc_html__( 'Space Around', 'xstore-core' ),
//					'space-evenly' => esc_html__( 'Space Evenly', 'xstore-core' ),
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
                    'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tabs-wrapper' => 'align-content: {{VALUE}}; align-items: {{VALUE}};',
				],
				'condition' => [
					'tabs_position' => 'vertical',
				],
			]
		);
		
		$this->add_control(
			'tabs_space',
			[
				'label' => __( 'Space Between', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-space: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'tabs_type!' => 'folders'
                ]
			]
		);
		
		$this->add_control(
			'tabs_line_weight',
			[
				'label' => __( 'Line Weight', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-line-weight: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'simple'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_typography',
				'selector' => '{{WRAPPER}} .etheme-elementor-tab',
			]
		);
		
		$this->start_controls_tabs('tabs_tab_colors' );
		
		$this->start_controls_tab( 'tabs_tab_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tab_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tab_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tab_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-border-color: {{VALUE}};',
				],
                'condition' => [
                    'tabs_type' => 'folders'
                ]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_tab_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tab_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab:hover, {{WRAPPER}} .etheme-elementor-tab-active' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tab_background_color_hover',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab:hover, {{WRAPPER}} .etheme-elementor-tab-active' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tab_line_color_hover',
			[
				'label' => __( 'Line Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-line-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'tab_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type' => 'simple'
				]
			]
		);
		
		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'tabs_space_below',
			[
				'label' => __( 'Space Below', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--etheme-tabs-space-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tabs_type!' => 'folders'
				]
			]
		);
		
		$this->end_controls_section();
		
		// post
		$this->start_controls_section(
			'section_tab_content_style',
			[
				'label' => esc_html__( 'Tab Content', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//                'condition' => [
//					'tabs_type' => 'folders'
//				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'tab_content_border',
				'label'     => esc_html__( 'Border', 'xstore-core' ),
				'selector'  => '{{WRAPPER}} .etheme-elementor-tab-content',
//                'fields_options' => [
//					'border' => [
//						'default' => 'solid',
//					],
//					'width' => [
//						'default' => [
//							'top' => 1,
//							'left' => 1,
//							'right' => 1,
//							'bottom' => 1
//						],
//					],
//					'color' => [
//						'default' => '#e1e1e1',
//					]
//				],
			]
		);
		
		$this->add_responsive_control(
			'tab_content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
//				'default' => [
//					'top' => 20,
//					'left' => 20,
//					'right' => 20,
//					'bottom' => 20
//				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// post
		$this->start_controls_section(
			'section_post_style',
			[
				'label' => esc_html__( 'Post', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-post'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-post',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .etheme-post',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// Content
		$this->start_controls_section(
			'section_post_content',
			[
				'label' => esc_html__( 'Content', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'content_alignment',
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
				'selectors' => [
					'{{WRAPPER}} .etheme-post' => 'text-align: {{VALUE}};',
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme-post-content'
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-post-content',
				'condition' => [
					'post_image!' => '',
					'content_position' => 'inside',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .etheme-post-content',
				'separator' => 'before',
                'condition' => [
					'post_image!' => '',
					'content_position' => 'inside',
				],
			]
		);
		
		$this->add_control(
			'content_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'post_image!' => '',
					'content_position' => 'inside',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'xstore-core'),
				'type' =>  \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_image!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .etheme-post-image-wrapper img',
			]
		);
		
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_space',
			[
				'label' => __( 'Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-space: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs('image_overlay_tabs');
		
		$this->start_controls_tab( 'image_overlay_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'image_overlay_color',
			[
				'label' 	=> esc_html__( 'Overlay Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .etheme-post .etheme-post-image-inner:before' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'image_overlay_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core'),
			]
		);
		
		$this->add_control(
			'image_overlay_color_hover',
			[
				'label' 	=> esc_html__( 'Overlay Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
				'selectors' => [
					'{{WRAPPER}} .etheme-post:hover .etheme-post-image-inner:before' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		// date label
		$this->start_controls_section(
			'section_post_date_label_style',
			[
				'label' => esc_html__( 'Date Label', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_date_label!' => 'none',
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => __('Day Typography', 'xstore-core'),
				'name' => 'post_date_label_date_typography',
				'selector' => '{{WRAPPER}} .etheme-post-date-label .etheme-post-day',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => __('Month Typography', 'xstore-core'),
				'name' => 'post_date_label_month_typography',
				'selector' => '{{WRAPPER}} .etheme-post-date-label .etheme-post-month',
			]
		);
		
		$this->start_controls_tabs('tabs_post_date_label_colors' );
		
		$this->start_controls_tab( 'tabs_post_date_label_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'post_date_label_day_color',
			[
				'label' => __( 'Day Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-date-label .etheme-post-day' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_date_label_month_color',
			[
				'label' => __( 'Month Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-date-label .etheme-post-month' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_date_label_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-date-label' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_post_date_label_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'post_date_label_day_color_hover',
			[
				'label' => __( 'Day Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post:hover .etheme-post-date-label .etheme-post-day' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_date_label_month_color_hover',
			[
				'label' => __( 'Month Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post:hover .etheme-post-date-label .etheme-post-month' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_date_label_background_color_hover',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post:hover .etheme-post-date-label' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'post_date_label_proportion',
			[
				'label' => __( 'Width/Height Proportion', 'xstore-core' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--date-label-proportion: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'post_date_label_offset',
			[
				'label' => __( 'Offset', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--date-label-offset: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'post_date_label' => 'inside',
				]
			]
		);
		
		$this->add_control(
			'post_date_label_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-date-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_categories_label_style',
			[
				'label'     => __( 'Categories Label', 'xstore-core' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_image!'                    => '',
					'post_categories!'               => '',
					'post_categories_position' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'categories_label_typography',
				'selector' => '{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories',
			]
		);
		
		$this->start_controls_tabs( 'tabs_categories_label_colors' );
		
		$this->start_controls_tab( 'tabs_categories_label_color_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'categories_label_color',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_label_background_color',
			[
				'label'     => __( 'Background Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_label_border_radius',
			[
				'label'      => __( 'Border Radius', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_categories_label_color_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'categories_label_color_hover',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_label_background_color_hover',
			[
				'label'     => __( 'Background Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
//		$this->add_control(
//			'categories_label_space',
//			[
//				'label'      => __( 'Bottom Space', 'xstore-core' ),
//				'type'       => \Elementor\Controls_Manager::SLIDER,
//				'size_units' => [ 'px' ],
//				'range'      => [
//					'px' => [
//						'min'  => 0,
//						'max'  => 50,
//						'step' => 1,
//					],
//				],
//				'selectors'  => [
//					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
//				],
//			]
//		);
		
		$this->end_controls_section();
		
		// tags
		$this->start_controls_section(
			'section_tags_label_style',
			[
				'label'     => __( 'Tags Label', 'xstore-core' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_image!'              => '',
					'post_tags!'               => '',
					'post_tags_position' => 'image',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'tags_label_typography',
				'selector' => '{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags',
			]
		);
		
		$this->start_controls_tabs( 'tabs_tags_label_colors' );
		
		$this->start_controls_tab( 'tabs_tags_label_color_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'tags_label_color',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_label_background_color',
			[
				'label'     => __( 'Background Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_label_border_radius',
			[
				'label'      => __( 'Border Radius', 'xstore-core' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_tags_label_color_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' )
			]
		);
		
		$this->add_control(
			'tags_label_color_hover',
			[
				'label'     => __( 'Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_label_background_color_hover',
			[
				'label'     => __( 'Background Color', 'xstore-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
//		$this->add_control(
//			'tags_label_space',
//			[
//				'label'      => __( 'Bottom Space', 'xstore-core' ),
//				'type'       => \Elementor\Controls_Manager::SLIDER,
//				'size_units' => [ 'px' ],
//				'range'      => [
//					'px' => [
//						'min'  => 0,
//						'max'  => 50,
//						'step' => 1,
//					],
//				],
//				'selectors'  => [
//					'{{WRAPPER}} .etheme-post-image-wrapper .etheme-post-tags' => 'margin-bottom: {{SIZE}}{{UNIT}};',
//				],
//			]
//		);
		
		$this->end_controls_section();
		
		// Section Shape Divider
		$this->start_controls_section(
			'section_image_shape_divider',
			[
				'label' => esc_html__( 'Image Shape Divider', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_image_shape_dividers' );
		
		$shapes_options = [
			'' => esc_html__( 'None', 'xstore-core' ),
		];
		
		foreach ( \Elementor\Shapes::get_shapes() as $shape_name => $shape_props ) {
			$shapes_options[ $shape_name ] = $shape_props['title'];
		}
		
		foreach ( [
			'top' => esc_html__( 'Top', 'xstore-core' ),
			'bottom' => esc_html__( 'Bottom', 'xstore-core' ),
		] as $side => $side_label ) {
			$base_control_key = "image_shape_divider_$side";
			
			$this->start_controls_tab(
				"tab_$base_control_key",
				[
					'label' => $side_label,
				]
			);
			
			$this->add_control(
				$base_control_key,
				[
					'label' => esc_html__( 'Type', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $shapes_options,
//					'render_type' => 'template',
//					'frontend_available' => true,
				]
			);
			
			$this->add_control(
				$base_control_key . '_color',
				[
					'label' => esc_html__( 'Color', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'condition' => [
						"image_shape_divider_$side!" => '',
					],
					'selectors' => [
						"{{WRAPPER}} .etheme-post-image-wrapper .elementor-shape-$side .elementor-shape-fill" => 'fill: {{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				$base_control_key . '_width',
				[
					'label' => esc_html__( 'Width', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'range' => [
						'%' => [
							'min' => 100,
							'max' => 300,
						],
					],
					'condition' => [
						"image_shape_divider_$side" => array_keys( \Elementor\Shapes::filter_shapes( 'height_only', \Elementor\Shapes::FILTER_EXCLUDE ) ),
					],
					'selectors' => [
						"{{WRAPPER}} .etheme-post-image-wrapper .elementor-shape-$side svg" => 'width: calc({{SIZE}}{{UNIT}} + 1.3px)',
					],
				]
			);
			
			$this->add_responsive_control(
				$base_control_key . '_height',
				[
					'label' => esc_html__( 'Height', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 500,
						],
					],
					'condition' => [
						"image_shape_divider_$side!" => '',
					],
					'selectors' => [
						"{{WRAPPER}} .etheme-post-image-wrapper .elementor-shape-$side svg" => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_control(
				$base_control_key . '_flip',
				[
					'label' => esc_html__( 'Flip', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'condition' => [
						"image_shape_divider_$side" => array_keys( \Elementor\Shapes::filter_shapes( 'has_flip' ) ),
					],
					'selectors' => [
						"{{WRAPPER}} .etheme-post-image-wrapper .elementor-shape-$side svg" => 'transform: translateX(-50%) rotateY(180deg)',
					],
				]
			);
			
			$this->add_control(
				$base_control_key . '_negative',
				[
					'label' => esc_html__( 'Invert', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
//					'frontend_available' => true,
					'condition' => [
						"image_shape_divider_$side" => array_keys( \Elementor\Shapes::filter_shapes( 'has_negative' ) ),
					],
				]
			);
			
			$this->add_control(
				$base_control_key . '_above_content',
				[
					'label' => esc_html__( 'Bring to Front', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'selectors' => [
						"{{WRAPPER}} .etheme-post-image-wrapper .elementor-shape-$side" => 'z-index: 2; pointer-events: none',
					],
					'condition' => [
						"image_shape_divider_$side!" => '',
					],
				]
			);
			
			$this->end_controls_tab();
		}
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_categories_style',
			[
				'label' => __( 'Categories', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'conditions' 	=> [
					'relation' => 'and',
					'terms' 	=> [
						[
							'name' 		=> 'post_categories',
							'operator'  => '!=',
							'value' 	=> ''
						],
						[
							'relation' => 'or',
							'terms' 	=> [
								[
									'name' 		=> 'post_categories_position',
									'operator'  => '=',
									'value' 	=> 'content'
								],
								[
									'name' 		=> 'post_image',
									'operator'  => '=',
									'value' 	=> ''
								],
							]
						]
					]
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'categories_typography',
				'selector' => '{{WRAPPER}} .etheme-post-content .etheme-post-categories',
			]
		);
		
		$this->start_controls_tabs('tabs_categories_colors');
		
		$this->start_controls_tab( 'tabs_categories_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'categories_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_categories_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'categories_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'categories_background_color_hover',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'categories_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// tags
		$this->start_controls_section(
			'section_tags_style',
			[
				'label' => __( 'Tags', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'conditions' 	=> [
					'relation' => 'and',
					'terms' 	=> [
						[
							'name' 		=> 'post_tags',
							'operator'  => '!=',
							'value' 	=> ''
						],
						[
							'relation' => 'or',
							'terms' 	=> [
								[
									'name' 		=> 'post_tags_position',
									'operator'  => '=',
									'value' 	=> 'content'
								],
								[
									'name' 		=> 'post_image',
									'operator'  => '=',
									'value' 	=> ''
								],
							]
						]
					]
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tags_typography',
				'selector' => '{{WRAPPER}} .etheme-post-content .etheme-post-tags',
			]
		);
		
		$this->start_controls_tabs('tabs_tags_colors');
		
		$this->start_controls_tab( 'tabs_tags_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tags_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_tags_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'tags_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tags_background_color_hover',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'tags_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-content .etheme-post-tags' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_title!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .etheme-post-title',
			]
		);
		
		$this->start_controls_tabs('tabs_title_colors');
		
		$this->start_controls_tab( 'tabs_title_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-title a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_title_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'title_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// excerpt
		$this->start_controls_section(
			'section_excerpt_style',
			[
				'label' => __( 'Excerpt', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_excerpt!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .etheme-post-excerpt',
			]
		);
		
		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'excerpt_space',
			[
				'label' => __( 'Bottom Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// button
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_button!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .etheme-post-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'condition' => [
					'post_button_type' => 'button'
				],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#000000'
//					],
//				],
				'selector' => '{{WRAPPER}} .etheme-post-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-button:hover, {{WRAPPER}} .etheme-post-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-post-button:hover svg, {{WRAPPER}} .etheme-post-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'condition' => [
                    'post_button_type' => 'button'
                ],
//				'fields_options' => [
//					'background' => [
//						'default' => 'classic'
//					],
//					'color' => [
//						'default' => '#444444'
//					],
//				],
				'selector' => '{{WRAPPER}} .etheme-post-button:hover, {{WRAPPER}} .etheme-post-button:focus',
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
                    'post_button_type' => 'button'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-button:hover, {{WRAPPER}} .etheme-post-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .etheme-post-button',
				'separator' => 'before',
                'condition' => [
					'post_button_type' => 'button'
				],
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'condition' => [
					'post_button_type' => 'button'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'condition' => [
					'post_button_type' => 'button'
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
		// meta
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => __( 'Meta Data', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_meta!' => ''
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'selector' => '{{WRAPPER}} .etheme-post-meta-data',
			]
		);
		
		$this->start_controls_tabs('tabs_meta_colors');
		
		$this->start_controls_tab( 'tabs_meta_color_normal',
			[
				'label' => esc_html__('Normal', 'xstore-core')
			]
		);
		
		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-meta-data' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'meta_link_color',
			[
				'label' => __( 'Link Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-meta-data a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'tabs_meta_color_hover',
			[
				'label' => esc_html__('Hover', 'xstore-core')
			]
		);
		
		$this->add_control(
			'meta_color_hover',
			[
				'label' => __( 'Link Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-post-meta-data a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'meta_border',
				'label' => esc_html__('Border', 'xstore-core'),
				'selector' => '{{WRAPPER}} .etheme-post-meta-data',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => 1,
							'left' => 0,
							'right' => 0,
							'bottom' => 0,
						],
					],
					'color' => [
						'default' => '#e1e1e1',
					]
				],
			]
		);
		
		$this->add_responsive_control(
			'meta_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-meta-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'meta_space',
			[
				'label' => __( 'Top Space', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-post-meta-data' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// navigation button
		$this->start_controls_section(
			'section_navigation_button_style',
			[
				'label' => esc_html__( 'Navigation Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'button'
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_button_typography',
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_navigation_button_style' );
		
		$this->start_controls_tab(
			'tab_navigation_button_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_navigation_button_hover',
			[
				'label' => esc_html__( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover svg, {{WRAPPER}} .etheme-elementor-lazy-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus',
			]
		);
		
		$this->add_control(
			'navigation_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'navigation_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button:hover, {{WRAPPER}} .etheme-elementor-lazy-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navigation_button_border',
				'selector' => '{{WRAPPER}} .etheme-elementor-lazy-button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_button_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_button_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// navigation scroll
		$this->start_controls_section(
			'section_navigation_scroll_style',
			[
				'label' => esc_html__( 'Scroll Loader', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'scroll'
				],
			]
		);
		
		$this->add_control(
			'navigation_scroll_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => '--etheme-elementor-loader-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_scroll_size',
			[
				'label' => __( 'Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => '--etheme-elementor-loader-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'navigation_scroll_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-lazy-button-wrapper' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				],
			]
		);
		
		$this->end_controls_section();
		
		// navigation pagination
		$this->start_controls_section(
			'section_navigation_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'pagination'
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_pagination_items_gap',
			[
				'label' => __( 'Items Gap', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => '--etheme-elementor-pagination-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'navigation_pagination_size',
			[
				'label' => __( 'Items Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => '--etheme-elementor-pagination-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_pagination_typography',
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
			]
		);
		
		$this->start_controls_tabs( 'tabs_navigation_pagination_style' );
		
		$this->start_controls_tab(
			'tab_navigation_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_pagination_text_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_pagination_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_navigation_pagination_hover',
			[
				'label' => esc_html__( 'Active/Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'navigation_pagination_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover svg, {{WRAPPER}} .etheme-elementor-pagination ul .current svg, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'navigation_pagination_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus',
			]
		);
		
		$this->add_control(
			'navigation_pagination_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'navigation_pagination_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:hover, {{WRAPPER}} .etheme-elementor-pagination ul .current, {{WRAPPER}} .etheme-elementor-pagination ul .page-numbers:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'navigation_pagination_border',
				'selector' => '{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'navigation_pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination ul .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'navigation_pagination_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-elementor-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Register widget controls.
	 *
	 * @since 4.1.3
	 * @access protected
	 */
	protected function render() {
	    
		$settings = $this->get_settings_for_display();
		
		if ( !count($settings['items']) ) return;
		
		self::$widget_type = 'posts-tabs';
		
		$this->add_render_attribute( 'tabs-wrapper', [
			'class' => [
				'etheme-elementor-tabs-wrapper',
                // 'elementor-hidden-tablet',
                //'elementor-hidden-mobile'
			]
		]);
		
//		$this->add_render_attribute( 'tabs-wrapper-mobile', [
//			'class' => [
//				'etheme-elementor-tabs-wrapper',
//				 'elementor-hidden-desktop',
//				'elementor-hidden-tablet',
//			]
//		]);
		
		// tweak to hide it via our classes for preview mode
        // in real frontend use default Elementor classes to make it style compatible of responsive params 
//		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
//			$this->add_render_attribute( 'tabs-wrapper', 'class', 'etheme-elementor-hidden-mobile' );
//			$this->add_render_attribute( 'tabs-wrapper-mobile', 'class', [
//				'etheme-elementor-hidden-desktop',
//				'etheme-elementor-hidden-tablet',
//            ] );
//        }
		
		$this->add_render_attribute( 'tab', [
			'class' => [
				'etheme-elementor-tab',
			]
		]);
		
//		$this->add_render_attribute( 'tab-mobile', [
//			'class' => [
//				'etheme-elementor-tab',
//			]
//		]);
		
		$this->add_render_attribute( 'tab-text', [
			'class' => [
				'etheme-elementor-tab-text',
			]
		]);
		
		$this->add_render_attribute( 'tab-content-wrapper', [
			'class' => [
				'etheme-elementor-tab-content'
			]
		]);
		
		$this->add_render_attribute( 'wrapper', [
			'class' => [
				'etheme-posts-wrapper',
				'etheme-grid',
			]
		]);
		
		if ( $settings['image_position'] != 'top' ) {
			$this->add_render_attribute( 'wrapper', [
				'class' => [
					'etheme-posts-wrapper-list',
				]
			]);
		}
		
		$pagination_ajax = $settings['navigation'] == 'pagination' && !!$settings['navigation_pagination_ajax'];
		
		$active_tab = 1;
		
		if ( !$pagination_ajax ) {
			for ($i=0; $i<count($settings['items']); $i++) {
				$id = $this->get_id() . '-'. $settings['items'][$i]['_id'];
				$find_active_tab = absint( empty( $_GET['etheme-'.self::$widget_type.'-'.$id.'-tab'] ) ? 0 : $_GET['etheme-'.self::$widget_type.'-'.$id.'-tab'] );
				if ( $find_active_tab > 0)
				    $active_tab = $find_active_tab;
            }
		}
		
		?>
        <div <?php $this->print_render_attribute_string( 'tabs-wrapper' ); ?>>
            <?php
                for ($i=0; $i<count($settings['items']); $i++) {
	                
	                $this->remove_render_attribute( 'tab', 'class', 'etheme-elementor-tab-active' );
	                $this->remove_render_attribute( 'tab', 'data-tab-id' );
	                
	                $this->add_render_attribute( 'tab', 'data-tab-id', $settings['items'][$i]['_id'] );
                    if ( ($i+1) == $active_tab ) {
                        // echo $i . ' ' . $page;
	                    $this->add_render_attribute( 'tab', 'class', 'etheme-elementor-tab-active' );
                    }
                    ?>
                    <span <?php $this->print_render_attribute_string( 'tab' ); ?>>
                        <?php
                        if ( $settings['tab_icon_align'] == 'right')
                            echo '<span '.$this->get_render_attribute_string( 'tab-text' ).'>'.esc_html($settings['items'][$i]['tab_title']).'</span>';
                        $migrated = isset( $settings['items'][$i]['__fa4_migrated']['tab_title_selected_icon'] );
                        $is_new = empty( $settings['items'][$i]['tab_title_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
                        if ( ! empty( $settings['items'][$i]['tab_title_icon'] ) || ! empty( $settings['items'][$i]['tab_title_selected_icon']['value'] ) ) : ?>
	                        <?php if ( $is_new || $migrated ) :
		                        \Elementor\Icons_Manager::render_icon( $settings['items'][$i]['tab_title_selected_icon'], [ 'aria-hidden' => 'true' ] );
	                        else : ?>
                                <i class="<?php echo esc_attr( $settings['items'][$i]['tab_title_icon'] ); ?>" aria-hidden="true"></i>
	                        <?php endif;
                        endif;
                        if ( $settings['tab_icon_align'] == 'left')
	                        echo '<span '.$this->get_render_attribute_string( 'tab-text' ).'>'.esc_html($settings['items'][$i]['tab_title']).'</span>';
                        ?>
                    </span>
                    <?php
                }
            ?>
        </div>
        <?php
		
		
		foreach ( $settings['items'] as $item_key => $item_settings ) {
			
			self::$id = $this->get_id() . '-'.$item_settings['_id'];
			self::$page_link = get_permalink();
			
			$item_settings = wp_parse_args($item_settings, $settings);
			$offset = $item_settings['offset'] && $item_settings['offset'] > 0 ? $item_settings['offset'] : 0;
		
            $posts = self::get_query( $item_settings );
			
			$this->remove_render_attribute( 'tab-content-wrapper', 'class', 'etheme-elementor-tab-content-active' );
			$this->remove_render_attribute( 'tab-content-wrapper', 'data-tab-content-id' );
			
			$this->add_render_attribute( 'tab-content-wrapper', 'data-tab-content-id', $item_settings['_id'] );
			
//			$this->remove_render_attribute( 'tab-mobile', 'class', 'etheme-elementor-tab-active' );
//			$this->remove_render_attribute( 'tab-mobile', 'data-tab-id' );
//
//			$this->add_render_attribute( 'tab-mobile', 'data-tab-id', $item_settings['_id'] );
			
			if ( ($item_key+1) == $active_tab) {
				$this->add_render_attribute( 'tab-content-wrapper', 'class', 'etheme-elementor-tab-content-active' );
//				$this->add_render_attribute( 'tab-mobile', 'class', 'etheme-elementor-tab-active' );
			}
			
			
            
            global $local_settings;
            $local_settings = $item_settings;
            if ( $posts && $posts->have_posts() ) {
                
                $_i=0;
                
                if ( in_array($settings['navigation'], array('button', 'scroll')) || ($settings['navigation'] == 'pagination' && !!$settings['navigation_pagination_ajax']))
                    wp_enqueue_script( 'etheme_post_product' );
                
                ?>
                
                <div <?php $this->print_render_attribute_string( 'tab-content-wrapper' ); ?>>
                
                <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                    <?php
                    if ( $settings['navigation'] == 'pagination' && !!!$settings['navigation_pagination_ajax'] ) {
                        $page = absint( empty( $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ? 1 : $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] );
                        $new_limit = $item_settings['limit'] != -1 ? $item_settings['limit'] : ($posts->found_posts - $offset);
                        if ( $page > 1 ) {
                            $loaded_posts = ($page - 1) * $item_settings['posts_per_page'];
                            if ( $item_settings['limit'] > $loaded_posts ) {
                                $new_limit = $item_settings['limit'] - $loaded_posts;
                            }
                        }
                        while ( $posts->have_posts() ) {
                            $posts->the_post();
                            if ( $_i >= $new_limit ) {
                                break;
                            }
                            $_i++;
                            $this->get_content_post( $local_settings );
                        }
                    }
                    else {
                        while ( $posts->have_posts() ) {
                            $posts->the_post();
                            $this->get_content_post( $local_settings );
                        }
                    }
                    ?>
                </div>

                <?php
                    if ( $settings['navigation'] != 'none' ) {

                        if ( $posts->max_num_pages > 1 && ($item_settings['limit'] == -1 || $item_settings['limit'] > $item_settings['posts_per_page']) ) {

                            $button_attributes = $settings['navigation'] == 'pagination' && !!$settings['navigation_pagination_ajax'] ? 'pagination-wrapper'.$item_settings['_id'] : 'load-more-button'.$item_settings['_id'];
		
		                    $nonce = wp_create_nonce( 'etheme_'.self::$widget_type.'_nonce' );
		
		                    $post_content = array();
		                    foreach (self::get_post_elements() as $key => $string) {
			                    if ( !$settings['post_'.$key]) continue;
			                    $post_content['post_'.$key] = true;
			                    switch ($key) {
				                    case 'image':
					                    $post_content['post_date_label'] = $settings['post_date_label'];
					                    $post_content[$key.'_size'] = $settings[$key.'_size'];
					                    $post_content[$key.'_custom_dimension'] = $settings[$key.'_custom_dimension'];
					                    $post_content[$key.'_position'] = $settings[$key.'_position'];
					                    $post_content[$key.'_link'] = $settings[$key.'_link'];
					                    $post_content[$key.'_hover_effect'] = $settings[$key.'_hover_effect'];
					                    $post_content['content_position'] = $settings['content_position'];
					                    // not css values should be send to new loaded posts
					                    $post_content[$key.'_shape_divider_top'] = $settings[$key.'_shape_divider_top'];
					                    $post_content[$key.'_shape_divider_bottom'] = $settings[$key.'_shape_divider_bottom'];
					                    $post_content[$key.'_shape_divider_top_negative'] = $settings[$key.'_shape_divider_top_negative'];
					                    $post_content[$key.'_shape_divider_bottom_negative'] = $settings[$key.'_shape_divider_bottom_negative'];
					                    break;
				                    case 'button':
					                    $post_content['post_'.$key.'_type'] = $settings['post_'.$key.'_type'];
					                    $post_content['post_'.$key.'_text'] = $settings['post_'.$key.'_text'];
					                    break;
				                    case 'meta':
					                    $post_content['post_'.$key.'_data'] = $settings['post_'.$key.'_data'];
					                    $post_content['post_'.$key.'_share_data'] = $settings['post_'.$key.'_share_data'];
					                    break;
				                    case 'categories':
				                    case 'tags':
					                    $post_content['post_'.$key.'_position'] = $settings['post_'.$key.'_position'];
				                        $post_content['post_'.$key.'_limit'] = $settings['post_'.$key.'_limit'];
					                    break;
				                    case 'title':
				                    case 'excerpt':
					                    if ( $key == 'title' ) {
						                    $post_content['post_'.$key.'_html_tag'] = $settings['post_'.$key.'_html_tag'];
					                    }
					                    $post_content['post_'.$key.'_limit_type'] = $settings['post_'.$key.'_limit_type'];
					                    $post_content['post_'.$key.'_limit'] = $settings['post_'.$key.'_limit'];
					                    break;
			                    }
		                    }
		                    
		                    $this->add_render_attribute( 'load-more-button-wrapper'.$item_settings['_id'], [
			                    'class' => [
				                    'etheme-elementor-lazy-button-wrapper',
				                    'elementor-align-center',
			                    ]
		                    ]);
		
		                    $query_settings = array(
			                    'select_date' => $item_settings['select_date'],
		                    );
		                    if ( $item_settings['select_date'] != 'anytime') {
			                    $query_settings['date_before'] = $item_settings['date_before'];
			                    $query_settings['date_after'] = $item_settings['date_after'];
		                    }
	
	                        if ( $item_settings['limit'] != '-1' && ($posts->found_posts - $offset) > $item_settings['limit'])
		                        $found_posts = $item_settings['limit'];
	                        else
		                        $found_posts = max(0, $posts->found_posts - $offset);
		
		                    $this->add_render_attribute( $button_attributes, [
				                    'data-widget-type' => self::$widget_type,
				                    'data-paged' => '1',
				                    'data-max-paged' => $posts->max_num_pages,
				                    'data-found-posts' => $found_posts,
				                    'data-offset' => $offset,
				                    'data-nonce' => $nonce,
				                    'data-query-settings' => esc_attr(wp_json_encode(array_merge(
					                    $query_settings,
					                    array(
						                    'posts_per_page' => $item_settings['posts_per_page'],
						                    'offset' => $offset,
						                    'limit' => $item_settings['limit'],
						                    'navigation' => $settings['navigation'],
						                    'query_type' => $item_settings['query_type'],
						                    'posts_ids' => $item_settings['posts_ids'],
						                    'exclude_ids' => $item_settings['exclude_ids'],
						                    'categories' => $item_settings['categories'],
						                    'tags' => $item_settings['tags'],
						                    'orderby' => $item_settings['orderby'],
						                    'order' => $item_settings['order'],
						                    'ignore_sticky_posts' => $item_settings['ignore_sticky_posts'],
					                    )
				                    ))),
				                    'data-post-settings' => esc_attr(wp_json_encode(
					                    $post_content
				                    ))
			                    ]
		                    );
		
		                    if ( isset( $item_settings['limit'] ) && $item_settings['limit'] != -1 ) {
			                    $this->add_render_attribute( 'load-more-button'.$item_settings['_id'], [
				                    'data-limit' => $item_settings['limit']
			                    ]);
		                    }
		
		                    switch ($settings['navigation']) {
			                    case 'button':
			                    case 'scroll':
				                    $this->add_render_attribute( 'load-more-button'.$item_settings['_id'], [
					                    'class' => [
						                    'etheme-elementor-lazy-button',
						                    'navigation-type-'.$settings['navigation']
					                    ] ]);
                                    if ( $settings['navigation'] == 'button' ) {
                                        $this->add_render_attribute( 'load-more-button'.$item_settings['_id'], [
                                            'class' => [
                                                'elementor-button',
                                            ]
                                        ]);
                                    }
				                    ?>
                                    <div <?php $this->print_render_attribute_string( 'load-more-button-wrapper'.$item_settings['_id'] ); ?>>
                                        <a <?php $this->print_render_attribute_string( 'load-more-button'.$item_settings['_id'] ); ?>>
						                    <?php if ( $settings['navigation'] == 'button' ) {
							                    echo '<span>' . $settings['navigation_button_text'] . '</span>';
						                    } ?>
                                        </a>
                                    </div>
				                    <?php
				                    break;
			                    case 'pagination':
				                    $is_rtl = is_rtl();
				                    $left_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
				                                  '<path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>' .
				                                  '</svg>';
				                    $right_arrow = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24">' .
				                                   '<path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>' .
				                                   '</svg>';
				                    $total = $found_posts >= $item_settings['limit'] ? ceil($item_settings['limit']/$item_settings['posts_per_page']) : $posts->max_num_pages;
				                    if ( $found_posts >= $item_settings['limit'] && $item_settings['limit'] == -1 || ($offset && (($found_posts + $offset) >= $item_settings['limit'])) )
					                    $total = ceil( $found_posts / $item_settings['posts_per_page'] );
				                    
                                    $this->add_render_attribute( 'pagination-wrapper'.$item_settings['_id'], [
					                    'class' => 'etheme-elementor-pagination',
				                    ]);
				
				                    if ( !!$settings['navigation_pagination_ajax'] ) {
					                    $this->add_render_attribute( 'pagination-wrapper'.$item_settings['_id'], [
						                    'class' => 'etheme-elementor-pagination-ajax',
						                    'data-widget-id' => self::$id,
						                    'data-total-pages' => $total,
						                    'data-permalink' => self::$page_link,
						                    'data-limit' => $item_settings['limit']
					                    ]);
				                    }
				                    ?>
                                    <div <?php $this->print_render_attribute_string( 'pagination-wrapper'.$item_settings['_id'] ); ?>>
					                    <?php
                                        $base = add_query_arg( 'etheme-'.self::$widget_type.'-'.self::$id.'-page', '%#%', self::$page_link );
                                        $format = '?etheme-'.self::$widget_type.'-'.self::$id.'-page=%#%';
                                        if ( !$settings['navigation_pagination_ajax'] ) {
	                                        $base = add_query_arg( 'etheme-'.self::$widget_type.'-'.self::$id.'-tab', $item_key+1, self::$page_link );
	                                        $base = add_query_arg( 'etheme-'.self::$widget_type.'-'.self::$id.'-page', '%#%', $base );
	                                        $format = '?etheme-'.self::$widget_type.'-'.self::$id.'-page=%#%';
	                                        $format .= '&etheme-'.self::$widget_type.'-'.self::$id.'-tab=%#%';
                                        }
					                    echo paginate_links( array(
						                    'base'      => esc_url_raw( $base ),
						                    'format'    => $format,
						                    'add_args'  => false,
						                    'current'   => max( 1, absint( empty( $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ? 1 : $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ),
						                    'total'     => $total,
						                    'prev_text' => $is_rtl ? $right_arrow : $left_arrow,
						                    'next_text' => $is_rtl ? $left_arrow : $right_arrow,
						                    'type'      => 'list',
						                    'end_size'  => 2,
						                    'mid_size'  => 2
					                    ) );
					                    ?>
                                    </div>
				                    <?php
				                    break;
		                    }
		
	                    }
	                    //
                    }
                    ?>
                    
                </div>
                <?php
            }

            else {
	            echo '<div class="elementor-panel-alert elementor-panel-alert-warning">'.
	                 esc_html__('No posts were found matching your selection.', 'xstore-core') .
	                 '</div>';
            }
            
            wp_reset_postdata();
            // reset post data
            self::$query_args = null;
            self::$id = null;
            self::$page_link = null;
        }
		
		// reset post data
		self::$widget_type = null;
		
	}
	
	public function get_content_post($settings) {
		global $local_settings;
		$local_settings = $settings;
		
		foreach ( array(
            'image-wrapper',
            'image',
            'post-content-wrapper',
            'date-label',
            'terms-label-wrapper'
        ) as $element_2_remove_class ) {
			$this->remove_render_attribute( $element_2_remove_class, 'class' );
		}
		$this->add_render_attribute( 'image-wrapper', [
			'class' => [
				'etheme-post-image-wrapper',
			]
		]);
		
		if ( $local_settings['image_position'] == 'right' ) {
			$this->add_render_attribute( 'image-wrapper', [
				'class' => [
					'etheme-post-image-right',
				]
			]);
		}
		
		if ( $local_settings['image_hover_effect'] != 'none' ) {
			$this->add_render_attribute( 'image-wrapper', [
				'class' => [
					'etheme-image-hover',
                    'etheme-image-hover-'.$local_settings['image_hover_effect']
				]
			]);
		}
		
		$this->add_render_attribute( 'image', [
			'class' => [
				'etheme-post-image-inner',
			]
		]);
		
		$this->add_render_attribute( 'post-content-wrapper', [
			'class' => [
				'etheme-post-content',
				$local_settings['content_position'] == 'inside' ? $local_settings['content_position'] : ''
			]
		]);
		
		$this->add_render_attribute( 'date-label', [
			'class' => [
				'etheme-post-date-label',
				$local_settings['post_date_label'] != 'none' ? $local_settings['post_date_label'] : ''
			]
		]);
		
		$this->add_render_attribute( 'terms-label-wrapper', [
			'class' => [
				'etheme-post-terms-label',
			]
		]);
		
		$this->render_post_header();
		
		$post_content = [];
		foreach (self::get_post_elements() as $key => $string_text) {
			if ( !isset($local_settings['post_'.$key]) || !$local_settings['post_'.$key]) continue;
			switch ($key) {
				case 'image':
					$local_settings[ 'image' ] = [
						'id' => get_post_thumbnail_id(),
					];
					$thumbnail_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $local_settings, 'image' );
					
					if ( empty( $thumbnail_html ) ) {
						$this->remove_render_attribute( 'post-content-wrapper', 'class', 'inside');
						break;
					}
					
					ob_start();
					echo $thumbnail_html;
					$post_content[$key] = ob_get_clean();
					
				break;
				case 'title':
					ob_start();
					$this->render_post_title();
					$post_content[$key] = ob_get_clean();
					break;
				case 'excerpt':
				    ob_start();
					$this->render_post_excerpt();
					$post_content[$key] = ob_get_clean();
					break;
				case 'meta':
				    ob_start();
					$this->render_meta_data();
					$post_content[$key] = ob_get_clean();
					break;
				case 'categories':
					ob_start();
					$this->render_post_categories_tags($local_settings['post_'.$key.'_limit']);
					$post_content[$key] = ob_get_clean();
					break;
				case 'tags':
					ob_start();
					$this->render_post_categories_tags($local_settings['post_'.$key.'_limit'], 'post_tag');
					$post_content[$key] = ob_get_clean();
					break;
				case 'button':
				    ob_start();
					$this->render_post_read_more();
					$post_content[$key] = ob_get_clean();
					break;
				case 'share':
				    ob_start();
					$this->render_post_share();
					$post_content[$key] = ob_get_clean();
					break;
			}
		}
		
		if ( array_key_exists('image', $post_content) ) {
			$image_tag = 'div';
			if ( $local_settings['image_link']) {
				$image_tag = 'a';
				$this->remove_render_attribute( 'image', 'href');
				$this->add_render_attribute( 'image', [
					'href' => get_the_permalink()
				]);
			}
		    ?>
            <div <?php $this->print_render_attribute_string( 'image-wrapper' ); ?>>
                <<?php \Elementor\Utils::print_validated_html_tag( $image_tag ); ?> <?php $this->print_render_attribute_string( 'image' ); ?>>
                    <?php
                        echo $post_content['image'];
                        if ( $local_settings['image_shape_divider_top'] ) {
                            $this->print_image_shape_divider( 'top' );
                        }
    
                        if ( $local_settings['image_shape_divider_bottom'] ) {
                            $this->print_image_shape_divider( 'bottom' );
                        }
                    ?>
                </<?php \Elementor\Utils::print_validated_html_tag( $image_tag ); ?>>
                <?php
                    if ( $local_settings['post_date_label'] != 'none' ) { ?>
                        <span <?php $this->print_render_attribute_string( 'date-label' ); ?>>
                            <?php $this->render_date_by_type('formatted', false); ?>
                        </span>
                    <?php }
                    unset($post_content['image']);
                    $terms = [];
                    if ( array_key_exists('categories', $post_content) && $local_settings['post_categories_position'] == 'image' ) {
	                    $terms[] = $post_content['categories'];
	                    unset($post_content['categories']);
                    }
                    if ( array_key_exists('tags', $post_content) && $local_settings['post_tags_position'] == 'image' ) {
                        $terms[] = $post_content['tags'];
	                    unset($post_content['tags']);
                    }
                    if ( count($terms) ) {
                        ?>
                        <span <?php $this->print_render_attribute_string( 'terms-label-wrapper' ); ?>>
                            <?php echo implode('', $terms); ?>
                        </span>
                        <?php
                    }
                ?>
            </div>
            <?php
        }
		if ( count($post_content) ) : ?>
            <div <?php $this->print_render_attribute_string( 'post-content-wrapper' ); ?>>
				<?php echo implode('', $post_content); ?>
            </div>
		<?php
		endif;
		
		$this->render_post_footer();
	}
	
	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'etheme-post' ] ); ?>>
		<?php
	}
	
	protected function render_post_footer() {
		?>
		</article>
		<?php
	}
	
	protected function render_post_title() {
		global $local_settings;
		$tag = \Elementor\Utils::validate_html_tag( $local_settings['post_title_html_tag'] );
		?>
		<<?php echo $tag; ?> class="etheme-post-title">
		<a href="<?php echo get_the_permalink(); ?>">
			<?php echo $this->limit_string(get_the_title()); ?>
		</a>
		</<?php echo $tag; ?>>
		<?php
	}
	
	protected function render_post_excerpt() {
		?>
		<div class="etheme-post-excerpt">
			<?php echo $this->limit_string(get_the_excerpt(), 'excerpt'); ?>
		</div>
		<?php
	}
	
	protected function render_post_categories_tags($number = 0, $type = 'category') {
		$id = get_the_ID();
		switch ($type) {
			case 'post_tag':
				$terms = wp_get_post_tags($id, array('number' => $number));
				$class = 'tags';
				break;
			default:
				$terms = wp_get_post_categories($id, array('number' => $number));
				$class = 'categories';
				break;
		}
		
		if ( count($terms) < 1) return;
		?>
        <span class="etheme-post-terms etheme-post-<?php echo esc_attr($class); ?>">
			<?php
			foreach ($terms as $term) {
				$term = is_object($term) ? $term : get_term_by( 'id', $term, $type );
				echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a>';
			}
			?>
		</span>
		<?php
	}
	
	protected function render_meta_data() {
		global $local_settings;
		if ( count($local_settings['post_meta_data']) < 1 ) return; ?>
		<div class="etheme-post-meta-data">
			<?php
			foreach ($local_settings['post_meta_data'] as $key) {
				switch ($key) {
					case 'author':
						$this->render_author();
						break;
					case 'date':
						$this->render_date_by_type();
						break;
					case 'comments':
						$this->render_comments();
						break;
					case 'modified':
						$this->render_date_by_type('modified');
						break;
					case 'share':
						$this->render_post_share();
						break;
					default:
						do_action('etheme_posts_post_meta_data_render', $key, $local_settings['post_meta_data'], get_the_ID());
						break;
				}
			}
			?>
		</div>
		<?php
	}
	
	protected function render_author() {
		?>
            <span class="etheme-post-author">
                <?php echo get_avatar( get_the_author_meta('email') , 40 ); ?>
                <?php echo sprintf(esc_html__('by %1s', 'xstore-core'), '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author().'</a>'); ?>
            </span>
		<?php
	}
	
	protected function render_date_by_type( $type = 'publish', $wrapper = true ) {
		$date = false;
		if ( $wrapper ) : ?>
			<span class="etheme-post-date">
		<?php endif;
			switch ( $type ) :
				case 'modified':
					$date = get_the_modified_date();
					$link = get_month_link(get_the_modified_date('Y'), get_the_modified_date('n'));
					break;
				case 'formatted':
					echo '<span class="etheme-post-day">'.get_the_date('d').'</span>';
					echo '<span class="etheme-post-month">'.get_the_date('M').'</span>';
					$link = get_month_link(get_the_date('Y'), get_the_date('n'));
					break;
				default:
					$date = get_the_date();
					$link = get_month_link(get_the_date('Y'), get_the_date('n'));
			endswitch;
			if ( $date ) {
				/** This filter is documented in wp-includes/general-template.php */
				echo sprintf('<a href="%1s">%2s %3s</a>', $link, '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
<path d="M22.56 1.68h-2.568v-1.032c0-0.264-0.24-0.576-0.576-0.576h-2.76c-0.264 0-0.576 0.24-0.576 0.576v1.032h-8.28v-1.032c0-0.264-0.24-0.576-0.576-0.576h-2.712c-0.264 0-0.576 0.24-0.576 0.576v1.032h-2.496c-0.264 0-0.552 0.24-0.552 0.576v21.096c0 0.264 0.24 0.576 0.576 0.576h21.096c0.264 0 0.576-0.24 0.576-0.576v-21.12c-0.024-0.312-0.264-0.552-0.576-0.552zM22.032 7.080v15.72h-20.016v-15.72h20.016zM5.136 3.24v-2.040h1.632v2.064h-1.632zM17.232 3.24v-2.040h1.632v2.064h-1.632zM4.608 4.392h2.76c0.264 0 0.576-0.24 0.576-0.576v-1.032h8.256v1.032c0 0.264 0.24 0.576 0.576 0.576h2.736c0.264 0 0.576-0.24 0.576-0.576v-1.032h1.992v3.216h-20.064v-3.216h2.040v1.032c-0.024 0.264 0.216 0.576 0.552 0.576zM19.584 9.096h-15.168v11.664h15.192v-11.664zM18.48 17.232v2.424h-2.424v-2.424h2.424zM18.48 13.704v2.448h-2.424v-2.448h2.424zM18.48 10.176v2.448h-2.424v-2.448h2.424zM14.976 17.232v2.424h-2.4v-2.424h2.4zM14.976 13.704v2.448h-2.4v-2.448h2.4zM14.976 10.176v2.448h-2.4v-2.448h2.4zM9.024 12.624v-2.448h2.424v2.448h-2.424zM9.024 16.152v-2.448h2.424v2.448h-2.424zM11.448 17.232v2.424h-2.424v-2.424h2.424zM7.92 17.232v2.424h-2.424v-2.424h2.424zM7.944 13.704v2.448h-2.424v-2.448h2.424zM7.944 10.176v2.448h-2.424v-2.448h2.424z"></path>
</svg>', apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ));
			}
		if ( $wrapper ) : ?>
			</span>
		<?php
		endif;
	}
	
	protected function render_comments() {
		?>
		<span class="etheme-post-comments">
            <?php
            $comment_link_template = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
				<path d="M21.288 0.528h-18.6c-1.44 0-2.64 1.176-2.64 2.64v12.744c0 1.44 1.176 2.64 2.64 2.64h2.52l2.256 4.56c0.096 0.216 0.336 0.384 0.6 0.384 0.24 0 0.456-0.12 0.6-0.36l2.256-4.536h10.368c1.44 0 2.64-1.176 2.64-2.64v-12.792c0-1.44-1.176-2.64-2.64-2.64zM22.632 3.168v12.744c0 0.72-0.576 1.296-1.296 1.296h-10.824c-0.264 0-0.504 0.144-0.6 0.36l-1.848 3.696-1.848-3.696c-0.096-0.216-0.336-0.384-0.6-0.384h-2.928c-0.696 0-1.272-0.576-1.272-1.272v-12.744c0-0.72 0.576-1.296 1.296-1.296h18.624c0.72 0 1.296 0.576 1.296 1.296z"></path>
			</svg>%s';
            comments_popup_link(
	            sprintf( $comment_link_template, '0'),
	            sprintf( $comment_link_template, '1'),
	            sprintf( $comment_link_template, '%')
            );
            ?>
		</span>
		<?php
	}
	
	protected function render_post_read_more() {
		global $local_settings;
		?>
		<a class="<?php echo ('button' == $local_settings['post_button_type'] ? 'elementor-button ' : ''); ?>etheme-post-button" href="<?php echo get_permalink(get_the_ID()); ?>">
			<?php
//				if ( empty($local_settings['post_button_custom_selected_icon']['value']) || $local_settings['post_button_icon_align'] == 'right') {
					echo '<span class="button-text">' . $local_settings['post_button_text'] . '</span>';
//				}
                if ( $local_settings['post_button_type'] == 'text' ) {
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">'.
                         (is_rtl() ?
                            '<path d="M23.184 11.28h-20.808l6.624-6.96c0.12-0.12 0.192-0.312 0.192-0.504s-0.096-0.36-0.216-0.48c-0.12-0.12-0.312-0.192-0.48-0.192-0.192 0-0.36 0.072-0.504 0.216l-7.752 8.184c-0.024 0.024-0.048 0.072-0.072 0.096l-0.024 0.048c-0.072 0.168-0.096 0.36-0.024 0.528 0.024 0.024 0.024 0.072 0.048 0.096 0.024 0.048 0.048 0.072 0.072 0.096l0.048 0.048c0 0 0 0 0 0l7.728 8.112c0.144 0.144 0.312 0.216 0.504 0.216 0.264 0 0.408-0.12 0.48-0.216 0.24-0.264 0.264-0.672 0.024-0.96l-6.648-6.936h20.808c0.384 0 0.696-0.312 0.696-0.696s-0.312-0.696-0.696-0.696z"></path>' :
                            '<path d="M23.928 11.832c-0.048-0.12-0.12-0.216-0.168-0.264l-7.776-8.16c-0.12-0.144-0.288-0.216-0.48-0.24-0.168 0-0.36 0.048-0.48 0.168-0.288 0.24-0.312 0.672-0.048 0.984l6.648 6.984h-20.904c-0.384 0-0.696 0.312-0.696 0.696s0.312 0.672 0.696 0.672h20.904l-6.624 7.008c-0.12 0.144-0.192 0.312-0.168 0.48 0 0.192 0.096 0.36 0.216 0.48 0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.48-0.192l7.776-8.16c0 0 0.168-0.264 0.168-0.336 0.024-0.12 0.024-0.216-0.024-0.336z"></path>') .
                    '</svg>';
                }
//				echo '<span class="elementor-button-icon">';
//					$this->render_icon($local_settings);
//				echo '</span>';
//				if ( $local_settings['post_button_icon_align'] == 'left') {
//					echo '<span class="button-text">' . $local_settings['post_button_text'] . '</span>';
//				}
			?>
		</a>
		<?php
	}
	
	protected function render_post_share() {
		global $local_settings;
		?>
		<span class="etheme-post-share">
            <a>
                <svg width="1em" height="1em" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.11773 7.80952C8.5453 7.80952 8.03418 8.03417 7.62558 8.42267C7.54366 8.50459 7.46224 8.60661 7.40093 8.68853L4.10956 6.68526C4.19148 6.46061 4.23219 6.23546 4.23219 5.9902C4.23219 5.74494 4.19148 5.51979 4.10956 5.29514L7.40093 3.31197C7.78942 3.8638 8.42317 4.21158 9.11823 4.21158C10.2837 4.21158 11.224 3.27127 11.224 2.10579C11.224 0.940319 10.2837 0 9.11823 0C7.95276 0 7.01244 0.940319 7.01244 2.10579C7.01244 2.33044 7.05315 2.5556 7.11446 2.78025L3.80249 4.76341C3.414 4.21158 2.78025 3.88441 2.10579 3.88441C0.940319 3.88441 0 4.82473 0 5.9902C0 7.15567 0.940319 8.09599 2.10579 8.09599C2.78025 8.09599 3.414 7.76881 3.80249 7.21699L7.11446 9.22025C7.03254 9.4449 7.01244 9.67006 7.01244 9.89471C7.01244 10.4671 7.23709 10.9783 7.62558 11.3869C8.01407 11.7753 8.5458 12 9.11773 12C9.68966 12 10.2013 11.7753 10.6099 11.3869C10.9984 10.9984 11.223 10.4666 11.223 9.89471C11.223 9.32278 10.9984 8.81116 10.6099 8.40256C10.2214 8.03468 9.68966 7.80952 9.11773 7.80952V7.80952ZM10.6305 9.91481C10.6305 10.3239 10.4671 10.6918 10.2013 10.9778C9.91532 11.2637 9.54693 11.407 9.13834 11.407C8.72974 11.407 8.36135 11.2436 8.07539 10.9778C7.78942 10.6918 7.64619 10.3234 7.64619 9.91481C7.64619 9.50622 7.80953 9.13783 8.07539 8.85186C8.36135 8.5659 8.72974 8.42267 9.13834 8.42267C9.54693 8.42267 9.91532 8.586 10.2013 8.85186C10.4671 9.13783 10.6305 9.50622 10.6305 9.91481ZM7.62508 2.10579C7.62508 1.2881 8.29954 0.613645 9.11723 0.613645C9.93492 0.613645 10.63 1.2881 10.63 2.10579C10.63 2.92348 9.95552 3.59794 9.13783 3.59794C8.32014 3.59794 7.62508 2.92348 7.62508 2.10579V2.10579ZM2.1259 7.50245C1.3082 7.50245 0.633748 6.82799 0.633748 6.0103C0.633748 5.19261 1.3082 4.51816 2.1259 4.51816C2.94359 4.51816 3.61804 5.19261 3.61804 6.0103C3.61804 6.82799 2.94359 7.50245 2.1259 7.50245V7.50245Z"/>
                </svg>
            </a>
			<?php if ( count($local_settings['post_meta_share_data'])) :
				$permalink = get_the_permalink();
			    ?>
                <span class="etheme-post-share-popup">
                    <?php
                        foreach ($local_settings['post_meta_share_data'] as $social) {
                        switch ($social) {
                            case 'twitter':
                                ?>
                                <a href="https://twitter.com/share?url=<?php echo esc_url( $permalink ); ?>&text=<?php echo esc_attr__('Share on Twitter', 'xstore-core'); ?>" title="<?php echo esc_html__('Share on Twitter', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M24 4.56c-0.888 0.384-1.848 0.648-2.832 0.768 1.032-0.6 1.8-1.56 2.16-2.712-0.96 0.576-1.992 0.96-3.12 1.2-0.912-0.96-2.184-1.56-3.6-1.56-2.712 0-4.92 2.208-4.92 4.92 0 0.384 0.024 0.768 0.12 1.128-4.080-0.192-7.704-2.16-10.152-5.136-0.432 0.744-0.672 1.584-0.672 2.496 0 1.704 0.888 3.216 2.184 4.080-0.768-0.024-1.56-0.264-2.208-0.624 0 0.024 0 0.024 0 0.048 0 2.4 1.704 4.368 3.936 4.824-0.384 0.12-0.84 0.168-1.296 0.168-0.312 0-0.624-0.024-0.936-0.072 0.648 1.944 2.448 3.384 4.608 3.432-1.68 1.32-3.792 2.088-6.096 2.088-0.408 0-0.792-0.024-1.176-0.072 2.184 1.416 4.752 2.208 7.56 2.208 9.048 0 14.016-7.512 14.016-13.992 0-0.216 0-0.432-0.024-0.624 0.96-0.72 1.776-1.584 2.448-2.568z"></path>
                                    </svg>
                                  </a>
                                <?php
                            break;
                            case 'facebook':
                                ?>
                                <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr__('Share on Facebook', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M13.488 8.256v-3c0-0.84 0.672-1.488 1.488-1.488h1.488v-3.768h-2.976c-2.472 0-4.488 2.016-4.488 4.512v3.744h-3v3.744h3v12h4.512v-12h3l1.488-3.744h-4.512z"></path>
                                    </svg>
                                </a>
                                <?php
                                break;
                            case 'linkedin':
                                ?>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( $permalink ); ?>&title=<?php echo esc_attr__('Share on Linkedin', 'xstore-core'); ?>" title="<?php echo esc_attr__('Share on Linkedin', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M0 7.488h5.376v16.512h-5.376v-16.512zM19.992 7.704c-0.048-0.024-0.12-0.048-0.168-0.048-0.072-0.024-0.144-0.024-0.216-0.048-0.288-0.048-0.6-0.096-0.96-0.096-3.12 0-5.112 2.28-5.76 3.144v-3.168h-5.4v16.512h5.376v-9c0 0 4.056-5.64 5.76-1.488 0 3.696 0 10.512 0 10.512h5.376v-11.16c0-2.496-1.704-4.56-4.008-5.16zM5.232 2.616c0 1.445-1.171 2.616-2.616 2.616s-2.616-1.171-2.616-2.616c0-1.445 1.171-2.616 2.616-2.616s2.616 1.171 2.616 2.616z"></path>
                                    </svg>
                                  </a>
                                <?php
                                break;
                            case 'vk':
                                ?>
                                <a href="https://vk.com/share.php?url=<?php echo esc_url( $permalink ); ?>?&title=<?php echo esc_attr__('Share on Vk', 'xstore-core'); ?>" title="<?php echo esc_attr__('Share on Vk', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M23.784 17.376c-0.072-0.12-0.456-0.984-2.376-2.76-2.016-1.872-1.752-1.56 0.672-4.8 1.464-1.968 2.064-3.168 1.872-3.672-0.168-0.48-1.272-0.36-1.272-0.36l-3.6 0.024c0 0-0.264-0.048-0.456 0.072s-0.312 0.384-0.312 0.384-0.576 1.512-1.344 2.808c-1.608 2.736-2.256 2.88-2.52 2.712-0.6-0.384-0.456-1.584-0.456-2.424 0-2.64 0.408-3.744-0.792-4.032-0.384-0.096-0.672-0.168-1.68-0.168-1.296-0.024-2.376 0-3 0.312-0.408 0.192-0.72 0.648-0.528 0.672 0.24 0.024 0.768 0.144 1.056 0.528 0.36 0.504 0.36 1.632 0.36 1.632s0.216 3.12-0.504 3.504c-0.48 0.264-1.152-0.288-2.592-2.76-0.744-1.272-1.296-2.664-1.296-2.664s-0.096-0.264-0.288-0.408c-0.24-0.168-0.552-0.216-0.552-0.216l-3.384 0.024c0 0-0.504 0.024-0.696 0.24-0.168 0.192-0.024 0.6-0.024 0.6s2.688 6.288 5.712 9.456c2.784 2.904 5.952 2.712 5.952 2.712h1.44c0 0 0.432-0.048 0.648-0.288 0.216-0.216 0.192-0.624 0.192-0.624s-0.024-1.92 0.864-2.208c0.888-0.288 2.016 1.872 3.216 2.688 0.912 0.624 1.584 0.48 1.584 0.48l3.216-0.048c0 0 1.68-0.096 0.888-1.416z"></path>
                                    </svg>
                                </a>
                                <?php
                                break;
                            case 'pinterest':
                                ?>
                                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr__('Share on Pinterest', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M12.336 0c-6.576 0-10.080 4.224-10.080 8.808 0 2.136 1.2 4.8 3.096 5.64 0.288 0.12 0.456 0.072 0.504-0.192 0.048-0.216 0.312-1.176 0.432-1.656 0.048-0.144 0.024-0.288-0.096-0.408-0.624-0.744-1.128-2.064-1.128-3.312 0-3.216 2.544-6.312 6.888-6.312 3.744 0 6.384 2.448 6.384 5.928 0 3.936-2.088 6.672-4.8 6.672-1.488 0-2.616-1.176-2.256-2.64 0.432-1.728 1.272-3.6 1.272-4.848 0-1.128-0.624-2.040-1.92-2.040-1.536 0-2.76 1.512-2.76 3.528 0 1.296 0.456 2.16 0.456 2.16s-1.512 6.096-1.8 7.224c-0.48 1.92 0.072 5.040 0.12 5.328 0.024 0.144 0.192 0.192 0.288 0.072 0.144-0.192 1.968-2.808 2.496-4.68 0.192-0.696 0.96-3.456 0.96-3.456 0.504 0.912 1.944 1.68 3.504 1.68 4.608 0 7.92-4.032 7.92-9.048-0.072-4.848-4.2-8.448-9.48-8.448z"></path>
                                    </svg>
                                </a>
                                <?php
                                break;
                            case 'whatsapp':
                                ?>
                                <a href="https://api.whatsapp.com/send?text=<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr__('Share on Whatsapp', 'xstore-core'); ?>" target="_blank" rel="noopener">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path d="M23.952 11.688c0 6.432-5.256 11.64-11.712 11.64-2.064 0-3.984-0.528-5.664-1.44l-6.48 2.064 2.112-6.24c-1.056-1.752-1.68-3.816-1.68-6 0-6.432 5.256-11.64 11.712-11.64 6.456-0.024 11.712 5.184 11.712 11.616zM12.216 1.92c-5.424 0-9.864 4.368-9.864 9.768 0 2.136 0.696 4.128 1.872 5.736l-1.224 3.624 3.792-1.2c1.56 1.032 3.432 1.608 5.424 1.608 5.424 0.024 9.864-4.368 9.864-9.768s-4.44-9.768-9.864-9.768zM18.144 14.376c-0.072-0.12-0.264-0.192-0.552-0.336s-1.704-0.84-1.968-0.936c-0.264-0.096-0.456-0.144-0.648 0.144s-0.744 0.936-0.912 1.128c-0.168 0.192-0.336 0.216-0.624 0.072s-1.224-0.432-2.304-1.416c-0.864-0.744-1.44-1.68-1.608-1.968s-0.024-0.432 0.12-0.576c0.12-0.12 0.288-0.336 0.432-0.504s0.192-0.288 0.288-0.48c0.096-0.192 0.048-0.36-0.024-0.504s-0.648-1.536-0.888-2.112c-0.24-0.576-0.48-0.48-0.648-0.48s-0.36-0.024-0.552-0.024c-0.192 0-0.504 0.072-0.768 0.36s-1.008 0.984-1.008 2.376c0 1.392 1.032 2.76 1.176 2.952s1.992 3.168 4.92 4.296c2.928 1.152 2.928 0.768 3.456 0.72s1.704-0.696 1.944-1.344c0.24-0.672 0.24-1.248 0.168-1.368z"></path>
                                    </svg>
                                </a>
                                <?php
                                break;
                        }
                    }
                    ?>
                </span>
                <?php
			endif; ?>
		</span>
		<?php
	}
	
	protected function render_icon($settings) {
		$migrated = isset( $settings['__fa4_migrated']['post_button_custom_selected_icon'] );
		$is_new = empty( $settings['post_button_custom_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		if ( ! empty( $settings['post_button_custom_icon'] ) || ! empty( $settings['post_button_custom_selected_icon']['value'] ) ) : ?>
			<?php if ( $is_new || $migrated ) :
				\Elementor\Icons_Manager::render_icon( $settings['post_button_custom_selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings['post_button_custom_icon'] ); ?>" aria-hidden="true"></i>
			<?php endif;
		endif;
	}
	
	/**
	 * Function that returns rendered title by chars/words limit.
	 *
	 * @param $string
	 * @return mixed|string
	 *
	 * @since 4.1.3
	 *
	 */
	public function limit_string($string, $type = 'title') {
		global $local_settings;
		if ( $local_settings['post_'.$type.'_limit'] > 0) {
			if ( $local_settings['post_'.$type.'_limit_type'] == 'chars' ) {
				return Elementor::limit_string_by_chars($string, $local_settings['post_'.$type.'_limit']);
			}
			elseif ( $local_settings['post_'.$type.'_limit_type'] == 'words' ) {
				return Elementor::limit_string_by_words($string, $local_settings['post_'.$type.'_limit']);
			}
		}
		return $string;
	}
	
	/**
	 * Get query for render products.
	 *
	 * @param $settings
	 * @return \WP_Query
	 *
	 * @since 4.1.3
	 *
	 */
	public static function get_query($settings, $extra_params = array()) {
		
		self::get_query_vars($settings);
		$query_args = wp_parse_args( $extra_params, self::$query_args );
		
		return new \WP_Query( $query_args );
	}
	
	public static function get_query_vars($settings) {
		
		self::set_common_args($settings);
		self::set_order_args($settings);
		self::set_pagination_args($settings);
		self::set_post_include_args($settings);

		if ( 'posts_ids' !== $settings['query_type'] ) {

			self::set_post_exclude_args($settings);
			if ( $settings['query_type'] == 'categories' )
				self::set_post_categories_args($settings);
            elseif ( $settings['query_type'] == 'tags' )
				self::set_post_tags_args($settings);
			self::set_date_args($settings);
			
			self::set_offset($settings);
		}
	}
	
	protected static function set_offset($settings) {
		if ( $settings['offset'] && $settings['offset'] > 0 ) {
			// it is for non-ajax pagination cases
			if ( isset(self::$query_args['paged']) ) {
				self::set_query_arg( 'offset',
					$settings['offset'] + ( ( self::$query_args['paged'] - 1 ) * self::$query_args['posts_per_page'] )
				);
			}
			else {
				self::set_query_arg( 'offset', $settings['offset'] );
			}
		}
	}
	
	protected static function set_pagination_args($settings) {
		$posts_per_page = $settings['limit'];
		if ( $settings['navigation'] != 'none' ) {
            if ( $settings['limit'] > $settings['posts_per_page'] || $settings['limit'] == -1 ) {
                $posts_per_page = $settings['posts_per_page'];
            }
		}
		self::set_query_arg( 'posts_per_page', $posts_per_page );
		self::set_query_arg( 'ignore_sticky_posts', !!$settings['ignore_sticky_posts'] );
	}
	
	protected static function set_common_args($settings) {
		self::set_query_arg('post_status', 'publish'); // Hide drafts/private posts for admins
		self::set_query_arg('post_type', 'post' );
		self::set_query_arg('tax_query', array(
			'relation' => 'AND',
		) );
		$page = absint( empty( $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] ) ? 1 : $_GET['etheme-'.self::$widget_type.'-'.self::$id.'-page'] );
		self::set_query_arg('page', $page);
		if ( 1 < $page ) {
			self::set_query_arg('paged', $page);
		}
		self::set_query_arg('no_found_rows', $settings['navigation'] != 'none' ? false : 1 );
	}
	
	protected static function set_post_include_args($settings) {
		
		if ( $settings['query_type'] == 'posts_ids' ) {
			
			self::set_query_arg( 'post__in', $settings['posts_ids'] );
			
			if ( empty( self::$query_args['post__in'] ) ) {
				// If no selection - return an empty query
				self::set_query_arg('post__in', [ 0 ] );
			}
		}
	}
	
	protected static function set_post_exclude_args($settings) {
		
		if ( empty( $settings['exclude_ids'] ) ) {
			return;
		}
		
		self::set_query_arg( 'post__not_in', $settings['exclude_ids'] );
	}
	
	protected static function set_post_categories_args($settings) {
		if ( empty( $settings['categories'] ) ) {
			return;
		}
		
		$old_tax_query = self::$query_args['tax_query'];
		unset(self::$query_args['tax_query']);
		self::set_query_arg( 'tax_query',
			array_merge($old_tax_query,
				array(
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $settings['categories']
					)
				)
			)
		);
	}
	
	protected static function set_post_tags_args($settings) {
		if ( empty( $settings['tags'] ) ) {
			return;
		}
		
		$old_tax_query = self::$query_args['tax_query'];
		unset(self::$query_args['tax_query']);
		self::set_query_arg( 'tax_query',
			array_merge($old_tax_query,
				array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $settings['tags']
					)
				)
			)
		);
	}
	
	protected static function set_order_args($settings) {
		self::set_query_arg( 'orderby', $settings['orderby'] );
		self::set_query_arg( 'order', $settings['order'] );
	}
	
	protected static function set_date_args($settings) {
		
		$select_date = $settings['select_date'];
		if ( ! empty( $select_date ) ) {
			$date_query = [];
			switch ( $select_date ) {
				case 'today':
					$date_query['after'] = '-1 day';
					break;
				case 'week':
					$date_query['after'] = '-1 week';
					break;
				case 'month':
					$date_query['after'] = '-1 month';
					break;
				case 'quarter':
					$date_query['after'] = '-3 month';
					break;
				case 'year':
					$date_query['after'] = '-1 year';
					break;
				case 'exact':
					$after_date = $settings['date_after'];
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}
					$before_date = $settings['date_before'];
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}
					$date_query['inclusive'] = true;
					break;
			}
			
			self::set_query_arg( 'date_query', $date_query );
		}
	}
	
	/**
	 * @param string    $key
	 * @param mixed     $value
	 */
	protected static function set_query_arg( $key, $value ) {
		if ( ! isset( self::$query_args[ $key ] ) ) {
			self::$query_args[ $key ] = $value;
		}
	}
	
	/**
	 * Print section shape divider.
	 *
	 * Used to generate the shape dividers HTML.
	 *
	 * @since 4.1.3
	 * @access private
	 *
	 * @param string $side Shape divider side, used to set the shape key.
	 */
	protected function print_image_shape_divider( $side ) {
//		$settings = $this->get_active_settings();
        global $local_settings;
		$base_setting_key = "image_shape_divider_$side";
		$negative = ! empty( $local_settings[ $base_setting_key . '_negative' ] );
		$shape_path = \Elementor\Shapes::get_shape_path( $local_settings[ $base_setting_key ], $negative );
		if ( ! is_file( $shape_path ) || ! is_readable( $shape_path ) ) {
			return;
		}
		?>
        <div class="elementor-shape elementor-shape-<?php echo esc_attr( $side ); ?>" data-negative="<?php
		// PHPCS - the variable $negative is getting a setting value with a strict structure.
		echo var_export( $negative ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>">
			<?php
			// PHPCS - The file content is being read from a strict file path structure.
			echo file_get_contents( $shape_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
        </div>
		<?php
	}
	
	public static function get_post_elements() {
		$elements = array(
			'image' => esc_html__('Show Image', 'xstore-core'),
			'categories' => esc_html__('Show Categories', 'xstore-core'),
			'tags' => esc_html__('Show Tags', 'xstore-core'),
			'title' => esc_html__('Show Title', 'xstore-core'),
			'excerpt' => esc_html__('Show Excerpt', 'xstore-core'),
			'button' => esc_html__('Show Read More Button', 'xstore-core'),
			'meta' => esc_html__('Show Post Meta', 'xstore-core'),
		);
		return apply_filters('etheme_posts_post_elements', $elements);
	}
	
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  4.1.3
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
}