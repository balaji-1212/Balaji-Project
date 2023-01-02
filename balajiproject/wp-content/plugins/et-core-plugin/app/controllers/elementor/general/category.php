<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Category as Category_Shortcode;

/**
 * Category widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Category extends \Elementor\Widget_Base {

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
		return 'et_category';
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
		return __( 'Advanced Block Of Products', 'xstore-core' );
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
		return 'eicon-product-meta eight_theme-element-icon';
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
		return [ 'woocommerce-elements', 'shop', 'store', 'categories', 'query', 'term', 'product' ];
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
	 * Register category widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label' 		=>	__( 'Categories Or Tags', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'List of product categories/tags', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_terms( 'product_cat' ),
			]
		);

		$this->add_control(
			'custom',
			[
				'label' 		=> __( 'Custom Products', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::SWITCHER,
				'description' 	=> __( 'Select custom products, if not then products will be gotten randomly', 'xstore-core' ),
				'return_value' 	=> 'true',
				'default' 		=> '',
			]
		);

		$this->add_control(
			'products_type',
			[
				'label' 		=>	__( 'Products Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	__( 'List of product categories', 'xstore-core' ),
				'options' 		=>	[
					'' 					=> esc_html__('All', 'xstore-core'), 
					'featured' 			=> esc_html__('Featured', 'xstore-core'), 
					'sale' 				=> esc_html__('Sale', 'xstore-core'), 
					'recently_viewed' 	=> esc_html__('Recently viewed', 'xstore-core'), 
					'bestsellings' 		=> esc_html__('Bestsellings', 'xstore-core')
				],
				'condition' => [
					'custom!' => 'true'
				]
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=>	__( 'Order By', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ids_order' => esc_html__( 'As IDs provided order', 'xstore-core' ),
					'ID'  		=> esc_html__( 'ID', 'xstore-core' ),
					'name'  	=> esc_html__( 'Title', 'xstore-core' ),
					'count' 	=> esc_html__( 'Quantity', 'xstore-core' ),
				),
				'default' 	=>	'',
				'condition' => [
					'custom!' => 'true'
				]
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=>	__( 'Order Way', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=> sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'multiple' 	=>	true,
				'options' 	=>	array(
					'ASC' 	=> esc_html__( 'Ascending', 'xstore-core' ),
					'DESC' 	=> esc_html__( 'Descending', 'xstore-core' ),
				),
				'default' 	=>	'ASC',
				'condition' => [
					'custom!' => 'true'
				]
			]
		);

		$this->add_control(
			'hide_out_stock',
			[
				'label' 		=>	__( 'Hide Out Of Stock', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'no'		=> esc_html__( 'No', 'xstore-core' ),
					'Yes' 		=> esc_html__( 'Yes', 'xstore-core' ),
				],
				'default' 		=>	'no',
				'condition' => [
					'custom!' => 'true'
				]
			]
		);

		// $this->add_control(
		// 	'fp_id',
		// 	[
		// 		'label' 	=>	__( 'First Product', 'xstore-core' ),
		// 		'type' 		=>	\Elementor\Controls_Manager::SELECT2,
		// 		'label_block'	=> 'true',
		// 		'multiple' 	=>	true,
		// 		'options' 	=>	Elementor::get_products(),
		// 		'default' 	=>	'',
		// 		'condition' => ['custom' => 'true']
		// 	]
		// );

		$this->add_control(
			'fp_id',
			[
				'label' 		=> __( 'First Product', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> 'Search ',
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
				'condition' => ['custom' => 'true']
			]
		);

		// $this->add_control(
		// 	'sp_id',
		// 	[
		// 		'label' 	=>	__( 'Second Product', 'xstore-core' ),
		// 		'type' 		=>	\Elementor\Controls_Manager::SELECT2,
		// 		'label_block'	=> 'true',
		// 		'multiple' 	=>	true,
		// 		'options' 	=>	Elementor::get_products(),
		// 		'default' 	=>	'',
		// 		'condition' => ['custom' => 'true']
		// 	]
		// );	
		$this->add_control(
			'sp_id',
			[
				'label' 		=> __( 'Second Product', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> 'Search ',
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
				'condition' => ['custom' => 'true']
			]
		);

		// $this->add_control(
		// 	'tp_id',
		// 	[
		// 		'label' 	=>	__( 'Third Product', 'xstore-core' ),
		// 		'type' 		=>	\Elementor\Controls_Manager::SELECT2,
		// 		'label_block'	=> 'true',
		// 		'multiple' 	=>	true,
		// 		'options' 	=>	Elementor::get_products(),
		// 		'default' 	=>	'',
		// 		'condition' => ['custom' => 'true']
		// 	]
		// );

		$this->add_control(
			'tp_id',
			[
				'label' 		=> __( 'Third Product', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> 'Search ',
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
				'condition' => ['custom' => 'true']
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' 	=> __( 'Title Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
			]
		);

		$this->add_control(
			'head_bg',
			[
				'label' 	=> __( 'Title Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
			]
		);

		$this->add_control(
			'content_bg',
			[
				'label' 	=> __( 'Background Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' 	=> __( 'Divider Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
			]
		);

		$this->add_control(
			'radius',
			[
				'label' 	=>	__( 'Border Radius', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'b_width',
			[
				'label' 	=>	__( 'Border Width', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'b_style',
			[
				'label' 		=>	__( 'Border Style', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'' 			=> esc_html__('Unset', 'xstore-core'),
					'solid' 	=> esc_html__('Solid', 'xstore-core'),
					'dashed' 	=> esc_html__('Dashed', 'xstore-core'),
					'dotted' 	=> esc_html__('Dotted', 'xstore-core'),
					'double' 	=> esc_html__('Double', 'xstore-core'),
					'groove' 	=> esc_html__('Groove', 'xstore-core'),
					'ridge' 	=> esc_html__('Ridge', 'xstore-core'),
					'inset' 	=> esc_html__('Inset', 'xstore-core'),
					'outset' 	=> esc_html__('Outset', 'xstore-core'),
				],
			]
		);

		$this->add_control(
			'b_color',
			[
				'label' 	=> __( 'Border Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'default' 	=> '',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render category widget output on the frontend.
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

			if ( in_array($key, array('fp_id', 'sp_id', 'tp_id', 'taxonomies') ) && $setting ) {
				$atts[$key] = !empty( $setting ) ? implode( ',', $setting ) : '';
				continue;
			}			

			if ( $setting ) {
				$atts[$key] = $setting;
			}
		}

		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );

		$Category_Shortcode = Category_Shortcode::get_instance();
		echo $Category_Shortcode->category_shortcode( $atts );

	}

}
