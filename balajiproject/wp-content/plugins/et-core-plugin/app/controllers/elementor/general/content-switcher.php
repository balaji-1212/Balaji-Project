<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Content Switcher widget.
 *
 * @since      4.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Content_Switcher extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 4.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_content_switcher';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Content switcher', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-content-switcher';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.3
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'content', 'text', 'template', 'toggle', 'switch'];
    }
	/**
	 * Get widget categories.
	 *
	 * @since 4.3
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
	 * @since 4.3
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	
	public function get_script_depends() {
		return [ 'etheme_content_switcher' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.3
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-content-switcher' ];
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.3
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register widget controls.
	 *
	 * @since 4.3
	 * @access protected
	 */
	protected function register_controls() {

        $sides = ['a', 'b'];

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'xstore-core'),
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'round' => __('Round', 'xstore-core'),
                    'round-2' => __('Round 2', 'xstore-core'),
                    'round-3' => __('Round 3', 'xstore-core'),
                    'square' => __('Square', 'xstore-core'),
                    'square-2' => __('Square 2', 'xstore-core'),
                    'square-3' => __('Square 3', 'xstore-core')
                ),
                'default' => 'round',
            ]
        );

        $this->add_control(
            'disabled_default',
            [
                'label' => __( 'Disabled state', 'xstore-core' ),
                'description' => __('Switched off state by default', 'xstore-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        foreach ($sides as $side) {

            $this->start_controls_section(
                "section_side_$side",
                [
                    'label' => $side == 'a' ? esc_html__('Switcher 01', 'xstore-core') : esc_html__('Switcher 02', 'xstore-core'),
                ]
            );

            $this->add_control(
                "label_$side",
                [
                    'label' => __( 'Text', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => $side == 'a' ? __( 'On', 'xstore-core' ) : __( 'Off', 'xstore-core' ),
                    'placeholder' => __( 'Switch label', 'xstore-core' ),
                ]
            );

            $this->add_control(
                "graphic_element_$side",
                [
                    'label' => __( 'Graphic Element', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'none' => [
                            'title' => __( 'None', 'xstore-core' ),
                            'icon' => 'eicon-ban',
                        ],
                        'image' => [
                            'title' => __( 'Image', 'xstore-core' ),
                            'icon' => 'eicon-image',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'xstore-core' ),
                            'icon' => 'eicon-star',
                        ],
                    ],
                    'default' => 'none',
                ]
            );

            $this->add_control(
                "image_$side",
                [
                    'label' => __( 'Choose Image', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition' => [
                        "graphic_element_$side" => 'image',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Image_Size::get_type(),
                [
                    'name' => "image_$side", // Actually its `image_size`
                    'default' => 'thumbnail',
                    'condition' => [
                        "graphic_element_$side" => 'image',
                    ],
                ]
            );

            $this->add_control(
                "selected_icon_$side",
                [
                    'label' => __( 'Icon', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'fa4compatibility' => "icon_$side",
                    'default' => [
                        'value' => 'fas fa-star',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        "graphic_element_$side" => 'icon',
                    ],
                ]
            );

            $this->add_control(
                "content_type_$side",
                [
                    'label' => __('Content Type', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->get_saved_content_list(),
                    'default' => 'custom'
                ]
            );

            $this->add_control(
                "save_template_info_$side",
                [
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => sprintf(__('Create template in Templates -> <a href="%s" target="_blank">Saved Templates</a> -> Choose ready to use template or go to Saved Templates and create new one.', 'xstore-core'), admin_url('edit.php?post_type=elementor_library&tabs_group=library')),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'condition' => [
                        "content_type_$side" => 'saved_template'
                    ]
                ]
            );

            $this->add_control(
                "content_$side",
                [
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'label' => __('Content', 'xstore-core'),
                    'description' => __('Content that will be displayed in toggle tab content area.', 'xstore-core'),
                    'condition' => [
                        "content_type_$side" => 'custom',
                    ],
                    'default' => $side == 'a' ? __('Active switcher custom content', 'xstore-core') : __('Inactive switcher custom content', 'xstore-core')
                ]
            );

            $this->add_control(
                "global_widget_$side",
                [
                    'label' => __('Global Widget', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->get_saved_content('widget'),
                    'default' => 'select',
                    'condition' => [
                        "content_type_$side" => 'global_widget'
                    ],
                ]
            );

            $this->add_control(
                "saved_template_$side",
                [
                    'label' => __('Saved Template', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->get_saved_content(),
                    'default' => 'select',
                    'condition' => [
                        "content_type_$side" => 'saved_template'
                    ],
                ]
            );

            $text_columns = range( 1, 10 );
            $text_columns = array_combine( $text_columns, $text_columns );
            $text_columns[''] = esc_html__( 'Default', 'xstore-core' );

            $this->add_responsive_control(
                "text_columns_$side",
                [
                    'label' => esc_html__( 'Columns', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'separator' => 'before',
                    'options' => $text_columns,
                    'condition' => [
                        "content_type_$side" => 'custom',
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .etheme-cs-content[data-side=$side]" => 'columns: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                "column_gap_$side",
                [
                    'label' => esc_html__( 'Columns Gap', 'xstore-core' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'em', 'vw' ],
                    'range' => [
                        'px' => [
                            'max' => 100,
                        ],
                        '%' => [
                            'max' => 10,
                            'step' => 0.1,
                        ],
                        'vw' => [
                            'max' => 10,
                            'step' => 0.1,
                        ],
                        'em' => [
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'condition' => [
                        "content_type_$side" => 'custom',
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .etheme-cs-content[data-side=$side]" => 'column-gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

        }
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Switchers', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_typography',
				'selector' => '{{WRAPPER}} .etheme-cs-toggles',
                'exclude' => ['line_height']
			]
		);
		
		$this->add_responsive_control(
			'switcher_alignment',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'xstore-core'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'xstore-core'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'xstore-core'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-cs-toggles' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_colors' );

        $this->start_controls_tab(
            'tabs_color_normal',
            [
                'label' => __( 'Normal', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-cs-switch' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_01',
            [
                'label' => __( 'Color 01', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--color-01: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_02',
            [
                'label' => __( 'Color 02', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--color-02: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_color_active',
            [
                'label' => __( 'Active', 'xstore-core' ),
            ]
        );

        $this->add_control(
            'label_active_color',
            [
                'label' => __( 'Label Color', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etheme-cs-switch.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_active_01',
            [
                'label' => __( 'Color 01', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--color-active-01: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'color_active_02',
            [
                'label' => __( 'Color 02', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--color-active-02: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
		
		$this->end_controls_section();

        foreach ($sides as $side) {

            $this->start_controls_section(
                "section_style_content_$side",
                [
                    'label' => $side == 'a' ? esc_html__('Content 01', 'xstore-core') : esc_html__('Content 02', 'xstore-core'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'condition' => [
                        "content_type_$side" => 'custom',
                    ],
                ]
            );

            $this->add_responsive_control(
                "align_content_$side",
                [
                    'label' => esc_html__('Alignment', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__('Left', 'xstore-core'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'xstore-core'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'xstore-core'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__('Justified', 'xstore-core'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} .etheme-cs-content[data-side=$side]" => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                "text_color_content_$side",
                [
                    'label' => esc_html__('Text Color', 'xstore-core'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        "{{WRAPPER}} .etheme-cs-content[data-side=$side]" => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => "typography_content_$side",
                    'selector' => "{{WRAPPER}} .etheme-cs-content[data-side=$side]",
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => "text_shadow_content_$side",
                    'selector' => "{{WRAPPER}} .etheme-cs-content[data-side=$side]",
                ]
            );

            $this->end_controls_section();

        }
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.3
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'wrapper', 'class', 'etheme-cs-wrapper' );

        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="etheme-cs-toggles">

                <?php
                if (!in_array($settings['type'], array('square-3', 'round-3'))) { ?>
                    <div class="etheme-cs-switch<?php if (!!!$settings['disabled_default']) echo ' active'; ?>" data-side="a">
                        <?php $this->render_icon($settings); ?>
                        <span><?php echo esc_attr($settings['label_a']); ?></span>
                    </div>
                    <label class="etheme-input-label">
                        <input class="etheme-cs-toggle-switch" type="checkbox" <?php if (!!$settings['disabled_default']) echo ' checked'; ?>>
                        <span class="etheme-cs-slider etheme-cs-<?php echo esc_attr($settings['type']); ?>"></span>
                    </label>
                    <div class="etheme-cs-switch<?php if (!!$settings['disabled_default']) echo ' active'; ?>" data-side="b">
                        <?php $this->render_icon($settings, 'b'); ?>
                        <span><?php echo esc_attr($settings['label_b']); ?></span>
                    </div>
                <?php }
                else { ?>
                    <label class="etheme-input-label type-3">
                        <input class="etheme-cs-toggle-switch" type="checkbox"<?php if (!!$settings['disabled_default']) echo ' checked'; ?>>
                        <span class="etheme-cs-slider etheme-cs-<?php echo esc_attr($settings['type']); ?>">
                            <span class="etheme-cs-toggle-a"><?php echo esc_attr($settings['label_a']); ?></span>
                            <span class="etheme-cs-toggle-b"><?php echo esc_attr($settings['label_b']); ?></span>
                        </span>
                    </label>
                <?php } ?>
            </div>
                <?php
                foreach (['a', 'b'] as $side) { $is_active = $side == 'a';
                    if ( !!$settings['disabled_default'] ) {
                        $is_active = $side == 'b';
                    }?>
                    <div class="etheme-cs-content<?php if ( $is_active ) echo ' active'; ?>" data-side="<?php echo esc_attr($side); ?>"<?php if ( !$is_active) echo ' style="display: none;"'; ?>>
                    <?php
                        switch ($settings["content_type_$side"]) {
                        case 'custom':
                            $this->print_unescaped_setting("content_$side");
                            break;
                        case 'global_widget':
                        case 'saved_template':
                            if (!empty($settings[$settings["content_type_$side"].'_'.$side])):
                                // echo \Elementor\Plugin::$instance->frontend->get_builder_content( $settings[$settings['content_type']], true );
                                $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($settings[$settings["content_type_$side"].'_'.$side]);
                                if (!$content) {
                                    echo esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
                                } else {
                                    echo $content;
                                }
                            endif;
                            break;
                    }
                    ?>
                    </div>
                    <?php
                }
                ?>
        </div>
        
		<?php
    }

    public function render_icon($settings, $side = 'a') {
        $migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();

        $this->add_render_attribute( "icon_wrapper_$side", 'class', ['elementor-icon', 'etheme-cs-icon', "etheme-cs-icon-$side"] );
        $this->add_render_attribute( "image_wrapper_$side", 'class', ['etheme-cs-image', "etheme-cs-image-$side"] );


        switch ($settings["graphic_element_$side"]) {
            case 'icon':
                if ( ! empty( $settings["icon_$side"] ) || ! empty( $settings["selected_icon_$side"] ) ) : ?>
                    <div <?php echo $this->get_render_attribute_string( 'icon_wrapper_a' ); ?>>
                        <?php if ( ( empty( $settings["icon_$side"] ) && $migration_allowed ) || isset( $settings['__fa4_migrated']["selected_icon_$side"] ) ) :
                            \Elementor\Icons_Manager::render_icon( $settings["selected_icon_$side"] );
                        else : ?>
                            <i <?php echo $this->get_render_attribute_string( "icon_$side" ); ?>></i>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                break;
            case 'image': ?>
                <div <?php echo $this->get_render_attribute_string( "image_wrapper_$side" ); ?>>
                    <div class="etheme-cs-image">
                        <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, "image_$side" ); ?>
                    </div>
                </div>
                <?php
                break;
            default;
        }
    }
	/**
	 * Get all elementor page templates
	 *
	 * @return array
	 */
	protected function get_saved_content_list() {
		$content_list = [
			'custom'   => __( 'Custom', 'xstore-core' ),
			'saved_template' => __( 'Saved Template', 'xstore-core' ),
		];
		
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_list['global_widget'] = __( 'Global Widget', 'xstore-core' );
		}
		return $content_list;
	}
	
	protected function get_post_template( $term = 'page' ) {
		$posts = get_posts(
			[
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => [
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $term,
					],
				],
			]
		);
		
		$templates = [];
		foreach ( $posts as $post ) {
			$templates[] = [
				'id'   => $post->ID,
				'name' => $post->post_title,
			];
		}
		return $templates;
	}
	
	protected function get_saved_content( $term = 'section' ) {
		$saved_contents = $this->get_post_template( $term );
		
		if ( count( $saved_contents ) > 0 ) {
			foreach ( $saved_contents as $saved_content ) {
				$content_id             = $saved_content['id'];
				$options[ $content_id ] = $saved_content['name'];
			}
		} else {
			$options['no_template'] = __( 'Nothing Found', 'xstore-core' );
		}
		return $options;
	}
}
