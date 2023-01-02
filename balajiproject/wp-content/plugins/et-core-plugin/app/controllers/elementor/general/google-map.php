<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Google Map widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Google_Map extends \Elementor\Widget_Base {

	/**
	 * Retrieve heading widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-google-map';
	}

	/**
	 * Retrieve heading widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Google Map', 'xstore-core' );
	}

	/**
	 * Retrieve heading widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-google-map';
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
        return [ 'google', 'map', 'latitude', 'longitude', 'streetview', 'marker' ];
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
	 * Get widget dependency.
	 *
	 * @since 4.1
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() )
		    return [ 'etheme-elementor-google-map' ];
		return [];
	}
	
    /**
     * Get widget dependency.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget dependency.
     */
	public function get_script_depends() {
		return [ 'etheme-google-map-api', 'etheme-google-map' ];
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
	 * Register google maps widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'General', 'xstore-core' ),
			]
		);

		if ( !get_theme_mod( 'google_map_api', '' ) ) {
			$message = sprintf(
				__( 'Please enter your Google API Key <a href="%1$s">here in google map api section</a>.<br>If dont have api key <a href="%2$s" target="_blank">click here</a> to generate one.', 'xstore-core' ),
				admin_url( 'customize.php' ),
				esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key' )
			);

			$this->add_control(
				'important_note',
				[
					'label' => __( 'Important Note', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => $message,
				// 'content_classes' => 'your-class',
				]
			);

		}         

		$this->add_control(
		    'map_notice',
		    [
			    'label' => __( 'Find Latitude & Longitude', 'xstore-core' ),
			    'type'  => \Elementor\Controls_Manager::RAW_HTML,
			    'raw'   => '<form onsubmit="ethemeMapFindAddress(this);" action="javascript:void(0);"><input type="text" id="etheme-map-find-address" class="etheme-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default"></form><div id="etheme-output-result" class="etheme-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
			    'label_block' => true,
		    ]
	    );

		$this->add_control(
			'map_lat',
			[
				'label' => __( 'Latitude', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '1.2833754',
				'default' => '1.2833754',
				'dynamic' => [ 'active' => true ]
			]
		);

		$this->add_control(
			'map_lng',
			[
				'label' => __( 'Longitude', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '103.86072639999998',
				'default' => '103.86072639999998',
				'separator' => true,
				'dynamic' => [ 'active' => true ]
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom Level', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'default'   => [
					'size' => 300,
				],
				'range' => [
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'map_type',
			[
				'label' => __( 'Map Type', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'roadmap' => __( 'Road Map', 'xstore-core' ),
					'satellite' => __( 'Satellite', 'xstore-core' ),
					'hybrid' => __( 'Hybrid', 'xstore-core' ),
					'terrain' => __( 'Terrain', 'xstore-core' ),
				],
				'default' => 'roadmap',
			]
		);

		$this->add_control(
			'gesture_handling',
			[
				'label' => __( 'Gesture Handling', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'auto' => __( 'Auto (Default)', 'xstore-core' ),
					'cooperative' => __( 'Cooperative', 'xstore-core' ),
					'greedy' => __( 'Greedy', 'xstore-core' ),
					'none' => __( 'None', 'xstore-core' ),
				],
				'default' => 'auto',
				'description' => __('Understand more about Gesture Handling by reading it <a href="https://developers.google.com/maps/documentation/javascript/reference/3/#MapOptions" target="_blank">here.</a> Basically it control how it handles gestures on the map. Used to be draggable and scroll wheel function which is deprecated.'),
			]
		);

		$this->add_control(
			'zoom_control',
			[
				'label' => __( 'Show Zoom Control', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'zoom_control_position',
			[
				'label' => __( 'Control Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'RIGHT_BOTTOM' => __( 'Bottom Right (Default)', 'xstore-core' ),
					'TOP_LEFT' => __( 'Top Left', 'xstore-core' ),
					'TOP_CENTER' => __( 'Top Center', 'xstore-core' ),
					'TOP_RIGHT' => __( 'Top Right', 'xstore-core' ),
					'LEFT_CENTER' => __( 'Left Center', 'xstore-core' ),
					'RIGHT_CENTER' => __( 'Right Center', 'xstore-core' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'xstore-core' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'xstore-core' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'xstore-core' ),
				],
				'default' => 'RIGHT_BOTTOM',
				'condition' => [
					'zoom_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'default_ui',
			[
				'label' => __( 'Show Default UI', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'map_type_control',
			[
				'label' => __( 'Map Type Control', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'map_type_control_style',
			[
				'label' => __( 'Control Styles', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'DEFAULT' => __( 'Default', 'xstore-core' ),
					'HORIZONTAL_BAR' => __( 'Horizontal Bar', 'xstore-core' ),
					'DROPDOWN_MENU' => __( 'Dropdown Menu', 'xstore-core' ),
				],
				'default' => 'DEFAULT',
				'condition' => [
					'map_type_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'map_type_control_position',
			[
				'label' => __( 'Control Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'TOP_LEFT' => __( 'Top Left (Default)', 'xstore-core' ),
					'TOP_CENTER' => __( 'Top Center', 'xstore-core' ),
					'TOP_RIGHT' => __( 'Top Right', 'xstore-core' ),
					'LEFT_CENTER' => __( 'Left Center', 'xstore-core' ),
					'RIGHT_CENTER' => __( 'Right Center', 'xstore-core' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'xstore-core' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'xstore-core' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'xstore-core' ),
				],
				'default' => 'TOP_LEFT',
				'condition' => [
					'map_type_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'streetview_control',
			[
				'label' => __( 'Show Streetview Control', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'streetview_control_position',
			[
				'label' => __( 'Streetview Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'RIGHT_BOTTOM' => __( 'Bottom Right (Default)', 'xstore-core' ),
					'TOP_LEFT' => __( 'Top Left', 'xstore-core' ),
					'TOP_CENTER' => __( 'Top Center', 'xstore-core' ),
					'TOP_RIGHT' => __( 'Top Right', 'xstore-core' ),
					'LEFT_CENTER' => __( 'Left Center', 'xstore-core' ),
					'RIGHT_CENTER' => __( 'Right Center', 'xstore-core' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'xstore-core' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'xstore-core' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'xstore-core' ),
				],
				'default' => 'RIGHT_BOTTOM',
				'condition' => [
					'streetview_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'custom_map_style',
			[
				'label' => __( 'Custom Map Style', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description' => __('Add style from <a href="https://mapstyle.withgoogle.com/" target="_blank">Google Map Styling Wizard</a> or <a href="https://snazzymaps.com/explore" target="_blank">Snazzy Maps</a>. Copy and Paste the style in the textarea.'),
				'condition' => [
					'map_type' => 'roadmap',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		/*Pins Option*/
		$this->start_controls_section(
			'map_marker_pin',
			[
				'label' => __( 'Marker Pins', 'xstore-core' ),
			]
		);

		$this->add_control(
			'infowindow_max_width',
			[
				'label' => __( 'InfoWindow Max Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '250',
				'default' => '250',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'pin_notice', [
				'label' => __( 'Find Latitude & Longitude', 'xstore-core' ),
				'type'  => \Elementor\Controls_Manager::RAW_HTML,
				'raw'   => '<form onsubmit="ethemeMapFindPinAddress(this);" action="javascript:void(0);"><input type="text" id="etheme-map-find-address" class="etheme-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default"></form><div id="etheme-output-result" class="etheme-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'pin_lat', [
				'label' => __( 'Latitude', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ]
			]
		);

		$repeater->add_control(
			'pin_lng', [
				'label' => __( 'Longitude', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ]
			]
		);

		$repeater->add_control(
			'pin_icon', [
				'label' => __( 'Marker Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default (Google)', 'xstore-core' ),
					'red' => __( 'Red', 'xstore-core' ),
					'blue' => __( 'Blue', 'xstore-core' ),
					'yellow' => __( 'Yellow', 'xstore-core' ),
					'purple' => __( 'Purple', 'xstore-core' ),
					'green' => __( 'Green', 'xstore-core' ),
					'orange' => __( 'Orange', 'xstore-core' ),
					'grey' => __( 'Grey', 'xstore-core' ),
					'white' => __( 'White', 'xstore-core' ),
					'black' => __( 'Black', 'xstore-core' ),
				],
				'default' => '',
			]
		);

		$repeater->add_control(
			'pin_title', [
				'label' => __( 'Title', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Pin Title' , 'xstore-core' ),
				'label_block' => true,
				'dynamic' => [ 'active' => true ]
			]
		);		

		$repeater->add_control(
			'pin_content', [
				'label' => __( 'Content', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Pin Content', 'xstore-core' ),
				'dynamic' => [ 'active' => true ]
			]
		);

		$this->add_control(
			'tabs',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'default' => [
					[
						'pin_title' => __( 'Pin #1', 'xstore-core' ),
						'pin_notice' => __( 'Find Latitude & Longitude', 'xstore-core' ),
						'pin_lat' => '1.2833754',
						'pin_lng' => '103.86072639999998',
						'pin_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'xstore-core' ),
					],
				], 
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ pin_title }}}',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_main_style',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pin_header_color',
			[
				'label' => __( 'Title Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-map-container h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => __( 'Title Typography', 'xstore-core' ),
				'name' => 'pin_header_typography',
				'selector' => '{{WRAPPER}} .etheme-map-container h6',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Title Text Shadow', 'xstore-core' ),
				'name' => 'pin_header_text_shadow',
				'selector' => '{{WRAPPER}} .etheme-map-container h6',
				'separator' => true,
			]
		);


		$this->add_control(
			'pin_content_color',
			[
				'label' => __( 'Content Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etheme-map-container .etheme-map-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => __( 'Content Typography', 'xstore-core' ),
				'name' => 'pin_content_typography',
				'selector' => '{{WRAPPER}} .etheme-map-container .etheme-map-content',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Content Text Shadow', 'xstore-core' ),
				'name' => 'pin_content_text_shadow',
				'selector' => '{{WRAPPER}} .etheme-map-container .etheme-map-content',
				'separator' => true,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'xstore-core' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .etheme-map-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render google maps widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$map_styles = $settings['custom_map_style'];
		$replace_code_content = strip_tags($map_styles);
        $new_replace_code_content = preg_replace('/\s/', '', $replace_code_content);

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		$this->add_render_attribute('etheme-google-map', 'data-etheme-map-style', $new_replace_code_content);
		
		$mapmarkers = array();

		foreach ( $settings['tabs'] as $index => $item ) :
			$mapmarkers[] = array(
				'lat' => $item['pin_lat'],
				'lng' => $item['pin_lng'],
				'title' => htmlspecialchars($item['pin_title'], ENT_QUOTES & ~ENT_COMPAT ),
				'content' => htmlspecialchars($item['pin_content'], ENT_QUOTES & ~ENT_COMPAT ),
				'pin_icon' => $item['pin_icon']
			);
		endforeach; 

		?>
		<div id="etheme-map-<?php echo esc_attr($this->get_id()); ?>" class="etheme-map" data-etheme-gesture="<?php echo $settings['gesture_handling'] ?>" <?php if ( 'yes' == $settings['zoom_control'] ) { ?> data-etheme-map-zoom-control="true" data-etheme-map-zoom-control-position="<?php echo $settings['zoom_control_position']; ?>" <?php } else { ?> data-etheme-map-zoom-control="false"<?php } ?> data-etheme-defaultui="<?php if ( 'yes' == $settings['default_ui'] ) { ?>false<?php } else { ?>true<?php } ?>" <?php echo $this->get_render_attribute_string('etheme-google-map'); ?> data-etheme-map-type="<?php echo $settings['map_type']; ?>" <?php if ( 'yes' == $settings['map_type_control'] ) { ?> data-etheme-map-type-control="true" data-etheme-map-type-control-style="<?php echo $settings['map_type_control_style']; ?>" data-etheme-map-type-control-position="<?php echo $settings['map_type_control_position']; ?>"<?php } else { ?> data-etheme-map-type-control="false"<?php } ?> <?php if ( 'yes' == $settings['streetview_control'] ) { ?> data-etheme-map-streetview-control="true" data-etheme-map-streetview-position="<?php echo $settings['streetview_control_position']; ?>"<?php } else {?> data-etheme-map-streetview-control="false"<?php } ?> data-etheme-map-lat="<?php echo $settings['map_lat']; ?>" data-etheme-map-lng="<?php echo $settings['map_lng']; ?>" data-etheme-map-zoom="<?php echo $settings['zoom']['size']; ?>" data-etheme-map-infowindow-width="<?php echo $settings['infowindow_max_width']; ?>" data-etheme-locations='<?php echo json_encode($mapmarkers);?>'></div>

	<?php 
	}
}