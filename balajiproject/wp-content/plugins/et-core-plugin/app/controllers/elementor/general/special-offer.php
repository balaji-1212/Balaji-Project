<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Special Offer widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Special_Offer extends \Elementor\Widget_Base {

	use Elementor;

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'special_offer';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Best Offer', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-best-offer';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'special', 'best', 'offer', 'product', 'woocommerce' ];
	}

    /**
     * Get widget categories.
     *
     * @since 2.0.0
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
	 * Register special offer widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'include',
			[
				'label' 		=> __( 'Select Product', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> 'Search ',
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				]
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' 	=>	__( 'Image Size', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'medium' 	=>	__( 'Medium', 'xstore-core' ),
					'large' 	=>	__( 'Large', 'xstore-core' ),
					'full' 		=>	__( 'Full', 'xstore-core' ),
					'custom' 	=>	__( 'Custom', 'xstore-core' ),
				],
				'default' 	=>	'medium',
			]
		);

		$this->add_control(
			'img_size_dimension',
			[
				'label' => __( 'Image Dimension', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'xstore-core' ),
				'default' => [
					'width' => '200',
					'height' => '100',
				],
				'condition' => ['img_size' => 'custom'],
			]
		);

		$this->add_control(
			'hide_img',
			[
				'label' 		=>	__( 'Hide Image', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'description' 	=>	__( 'Disable product image', 'xstore-core' ),
				'return_value'  =>	'true',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'advanced',
			[
				'label' => __( 'Advanced', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'dis_type',
			[
				'label' 	=> __( 'Show Elements', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::SELECT,
				'default' 	=> 'type1',
				'options' 	=> [
					'type1'  	=>	__( 'Default', 'xstore-core' ),
					'type2'		=>	__( 'Advanced', 'xstore-core' ),
				],
			]
		);

		$this->add_control(
			'product_stock_color_step1',
			[
				'label' 	=> __( 'Full Stock Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => ['dis_type' => 'type2'],
				'default' 	=> '#2e7d32',
			]
		);

		$this->add_control(
			'product_stock_color_step2',
			[
				'label' 	=> __( 'Middle Stock Color', 'xstore-core' ),
				'description' => __('Color of stock line when it is sold more than 50%', 'xstore-core'),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'condition' => ['dis_type' => 'type2'],
				'default'   => '#f57f17',
			]
		);

		$this->add_control(
			'product_stock_color_step3',
			[
				'label'   	=> __( 'Low Stock Color', 'xstore-core' ),
				'type'    	=> \Elementor\Controls_Manager::COLOR,
				'condition' => ['dis_type' => 'type2'],
				'default'   => '#c62828',
			]
		);

		$this->add_control(
			'hover',
			[
				'label' 	=> __( 'Hover Effect', 'xstore-core' ),
				'type'  	=> \Elementor\Controls_Manager::SELECT,
				'default' 	=> 'disable',
				'options' 	=> [
					'disable'   => __( 'Disable', 'xstore-core' ),
					'swap' 	 	=> __( 'Swap', 'xstore-core' ),
					'slider' 	=> __( 'slider', 'xstore-core' ),
                    'carousel'  => __( 'Automatic Carousel', 'xstore-core' ),
				],
				'condition' => ['hide_img' => 'true'],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render special offer widget output on the frontend.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Check if custom is called get custom size
		if ( 'custom' == $settings['img_size'] ) {
			$settings['img_size_dimension']['height'] = isset( $settings['img_size_dimension']['height'] ) ? $settings['img_size_dimension']['height'] : '200';
			$settings['img_size_dimension']['width']  = isset( $settings['img_size_dimension']['width']  ) ? $settings['img_size_dimension']['width']  : '100';
			$img_size = $settings['img_size_dimension']['width'] .'*'. $settings['img_size_dimension']['height'];
		} else {
			$img_size = $settings['img_size'];
		}

		echo do_shortcode( '[et_offer 
			include="'. $settings['include'] .'" 
			img_size="'. $img_size .'"
			hide_img="'. $settings['hide_img'] .'" 
			dis_type="'. $settings['dis_type'] .'"
			product_stock_color_step1="'. $settings['product_stock_color_step1']. '"
			product_stock_color_step2="'. $settings['product_stock_color_step2']. '"
			product_stock_color_step3="'. $settings['product_stock_color_step3']. '"
			hover="'. $settings['hover'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);
	}

}
