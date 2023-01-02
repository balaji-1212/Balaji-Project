<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Products as Products_Shortcode;

/**
 * Products widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Products extends \Elementor\Widget_Base {

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
		return 'etheme_products';
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
		return __( 'Products', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-products eight_theme-elementor-deprecated';
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
		return [ 'products', 'categories', 'woocommerce' ];
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
    	return ['eight_theme_deprecated'];
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
	 * Register Products widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function register_controls() {
		
		$sizes_select2 = array();
		
		if ( function_exists('etheme_get_image_sizes')) {
			$sizes = etheme_get_image_sizes();
			foreach ( $sizes as $size => $value ) {
				$sizes[ $size ] = $sizes[ $size ]['width'] . 'x' . $sizes[ $size ]['height'];
			}
			
			$sizes_select = array(
//				'shop_catalog'                  => 'shop_catalog',
				'woocommerce_thumbnail'         => 'woocommerce_thumbnail',
				'woocommerce_gallery_thumbnail' => 'woocommerce_gallery_thumbnail',
				'woocommerce_single'            => 'woocommerce_single',
//				'shop_thumbnail'                => 'shop_thumbnail',
//				'shop_single'                   => 'shop_single',
				'thumbnail'                     => 'thumbnail',
				'medium'                        => 'medium',
				'large'                         => 'large',
				'full'                          => 'full'
			);
			
			foreach ( $sizes_select as $item => $value ) {
				if ( isset( $sizes[ $item ] ) ) {
					$sizes_select2[ $item ] = $value . ' (' . $sizes[ $item ] . ')';
				} else {
					$sizes_select2[ $item ] = $value;
				}
			}
			
		}
		
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'General', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' 		=>	__( 'Display Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'slider'		=>	esc_html__('Slider', 'xstore-core'),
					'grid'			=>	esc_html__('Grid', 'xstore-core'),
					'list'			=>	esc_html__('List', 'xstore-core'),
					'full-screen'	=>	esc_html__('Full screen', 'xstore-core'),
				],
				'default'		=> 'slider'
			]
		);

		$this->add_control(
			'style',
			[
				'label' 		=>	__( 'Product Layout For Slider', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'default'	=>	esc_html__('Grid', 'xstore-core'), 
					'advanced'	=>	esc_html__('List', 'xstore-core')
				],
				'condition'		=> ['type' => 'slider'],
				'default'		=> 'default'
			]
		);

		$this->add_control(
			'no_spacing',
			[
				'label' 		=>	__( 'Remove Space Between Slides', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
				'condition'		=> ['type' => 'slider']
			]
		);
		
		$this->add_control(
			'no_spacing_grid',
			[
				'label' 		=>	__( 'Remove Space Between Products', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
				'condition'		=> ['type' => 'grid']
			]
		);
		
		$this->add_control(
			'bordered_layout',
			[
				'label' 		=>	__( 'Bordered Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
				'condition'		=> ['type' => array('grid', 'list')]
			]
		);
		
		$this->add_control(
			'hover_shadow',
			[
				'label' 		=>	__( 'Box Shadow On Hover', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);
		
		$this->add_control(
			'columns_grid',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'1'	=>	esc_html__('1', 'xstore-core'),
					'2'	=>	esc_html__('2', 'xstore-core'),
					'3'	=>	esc_html__('3', 'xstore-core'),
					'4'	=>	esc_html__('4', 'xstore-core'),
					'5'	=>	esc_html__('5', 'xstore-core'),
					'6'	=>	esc_html__('6', 'xstore-core'),
				],
				'condition'		=> ['type' => 'grid'],
				'default'		=> '4'
			]
		);

		$this->add_control(
			'columns_list',
			[
				'label' 		=>	__( 'Columns', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'1'	=>	esc_html__('1', 'xstore-core'),
					'2'	=>	esc_html__('2', 'xstore-core'),
					'3'	=>	esc_html__('3', 'xstore-core'),
					'4'	=>	esc_html__('4', 'xstore-core'),
				],
				'condition'		=> ['type' => 'list'],
				'default'		=> '4'
			]
		);	

		$this->add_control(
			'navigation',
			[
				'label' 		=>	__( 'Navigation', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'off'	=>	esc_html__( 'Off', 'xstore-core' ),
					'btn'	=>	esc_html__( 'Load More button', 'xstore-core' ),
					'lazy'	=>	esc_html__( 'Lazy loading', 'xstore-core' ),
				],
				'condition'		=> ['type' => 'grid'],
				'default'		=> 'off'
			]
		);

		$this->add_control(
			'per_iteration',
			[
				'label' 		=>	__( 'Products Per View', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Number of products to show per view and after every loading', 'xstore-core' ),
				'condition'		=>	['navigation' => array( 'btn', 'lazy' )]
			]
		);

		$this->add_control(
			'show_counter',
			[
				'label' 		=>	__( 'Show Sale Countdown', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'show_stock',
			[
				'label' 		=>	__( 'Show Stock Count Bar', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'ajax',
			[
				'label' 		=>	__( 'Lazy Loading', 'xstore-core' ),
				'description' 	=>	__( 'Works for live mode, not for the preview', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'product_view_section',
			[
				'label' => __( 'Product View', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'product_view',
			[
				'label' 		=>	__( 'Product View', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''			=>	esc_html__('Inherit', 'xstore-core'),
					'default'	=>	esc_html__('Default', 'xstore-core'),
					'mask3'		=>	esc_html__('Buttons on hover middle', 'xstore-core'),
					'mask'		=>	esc_html__('Buttons on hover bottom', 'xstore-core'),
					'mask2'		=>	esc_html__('Buttons on hover right', 'xstore-core'),
					'info'		=>	esc_html__('Information mask', 'xstore-core'),
					'booking'	=>	esc_html__('Booking', 'xstore-core'),
					'light'		=>	esc_html__('Light', 'xstore-core'),
					'overlay'   =>  esc_html__('Overlay content on image', 'xstore-core'),
					'Disable'	=>	esc_html__('Disable', 'xstore-core'),
				],
				'condition'		=> ['type' => array('grid', 'list', 'slider')]
			]
		);
		
		$this->add_control(
			'product_view_color',
			[
				'label' 		=>	__( 'Product View Color', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''			=>	esc_html__('Default', 'xstore-core'),
					'white'	=>	esc_html__('White', 'xstore-core'),
					'dark'	=>	esc_html__('Dark', 'xstore-core'),
					'transparent'	=>	esc_html__('Transparent', 'xstore-core'),
				],
				'condition'		=> ['type' => array('grid', 'list', 'slider')]
			]
		);
		
		$this->add_control(
			'product_img_hover',
			[
				'label' 		=>	__( 'Image Hover Effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'' 			=> 	esc_html__( 'Default', 'xstore-core' ),
					'disable'	=>	esc_html__( 'Disable', 'xstore-core' ),
					'swap'		=>	esc_html__( 'Swap', 'xstore-core' ),
					'slider'	=>	esc_html__( 'Images Slider', 'xstore-core' ),
                    'carousel'  =>  esc_html__( 'Automatic Carousel', 'xstore-core' ),
				],
				'condition'		=> ['product_view' => array( '', 'default', 'mask3', 'mask', 'mask2', 'info', 'booking', 'light', 'Disable' ) ]
			]
		);
		
		$this->add_control(
			'product_img_size',
			[
				'label' 		=>	__( 'Image Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	$sizes_select2,
			]
		);
		
		$this->add_control(
			'show_excerpt',
			[
				'label' 		=>	__( 'Show Excerpt', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);
		
		$this->add_control(
			'excerpt_length',
			[
				'label' 		=> __( 'Excerpt Length (symbols)', 'xstore-core' ),
				'type'			=> \Elementor\Controls_Manager::NUMBER,
				'description' 	=> __( 'Controls the number of symbols in the product excerpt.', 'xstore-core' ),
				'default' 		=> '120',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'product_data_section',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'products',
			[
				'label' 		=>	__( 'Products Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					''					=>	esc_html__('All', 'xstore-core'),
					'featured'			=>	esc_html__('Featured', 'xstore-core'),
					'sale'				=>	esc_html__('Sale', 'xstore-core'),
					'recently_viewed'	=>	esc_html__('Recently viewed', 'xstore-core'),
					'bestsellings'		=>	esc_html__('Bestsellings', 'xstore-core'),
				],
			]
		);		

		$this->add_control(
			'orderby',
			[
				'label' 		=>	__( 'Order By', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description'  => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s. Default by Date', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options' 		=>	[
					'date'			=>	esc_html__( 'Date', 'xstore-core' ),
					'ID'			=>	esc_html__( 'ID', 'xstore-core' ),
					'post__in'		=>	esc_html__( 'As IDs provided order', 'xstore-core' ),
					'author'		=>	esc_html__( 'Author', 'xstore-core' ),
					'title'			=>	esc_html__( 'Title', 'xstore-core' ),
					'modified'		=>	esc_html__( 'Modified', 'xstore-core' ),
					'rand'			=>	esc_html__( 'Random', 'xstore-core' ),
					'comment_count'	=>	esc_html__( 'Comment count', 'xstore-core' ),
					'menu_order'	=>	esc_html__( 'Menu order', 'xstore-core' ),
					'price'			=>	esc_html__( 'Price', 'xstore-core' ),
				],
				'default'		=> 'date'
			]
		);

		$this->add_control(
			'order',
			[
				'label' 		=>	__( 'Order Way', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description'   => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s. Default by ASC', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options' 		=>	[
                    'ASC'		=>	esc_html__( 'Ascending', 'xstore-core' ),
                    'DESC'		=>	esc_html__( 'Descending', 'xstore-core' ),
				],
				'default'		=> 'ASC'
			]
		);
		
		$this->add_control(
			'hide_out_stock',
			[
				'label' 		=>	__( 'Hide Out Of Stock', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'yes',
				'default' 		=>	'',
			]
		);
		
		$this->add_control(
			'ids',
			[
				'label' 		=> __( 'Products', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> true,
				'placeholder' 	=> esc_html__('Enter List of Products', 'xstore-core'),
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
			]
		);
		
		$this->add_control(
			'taxonomy_type',
			[
				'label' 		=>	__( 'Taxonomy Type', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'product_cat'		=>	esc_html__( 'Categories', 'xstore-core' ),
					'product_tag'		=>	esc_html__( 'Tags', 'xstore-core' ),
					'brands'		=>	esc_html__( 'Brands', 'xstore-core' ),
				],
				'default'		=> 'product_cat'
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label' 		=>	__( 'Categories', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'description'   =>  esc_html__( 'Enter categories.', 'xstore-core' ),
				'label_block'	=> 'true',
				'multiple' 	=>	true,
				'options' 		=> Elementor::get_terms('product_cat', false, false),
				'condition' => [
					'taxonomy_type' => 'product_cat'
				]
			]
		);
		
		$this->add_control(
			'product_tags',
			[
				'label' 		=>	__( 'Tags', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'description'   =>  esc_html__( 'Enter tags.', 'xstore-core' ),
				'label_block'	=> 'true',
				'multiple' 	=>	true,
				'options' 		=> Elementor::get_terms('product_tag', false, false),
				'condition' => [
					'taxonomy_type' => 'product_tag'
				]
			]
		);
		
		$this->add_control(
			'brands',
			[
				'label' 		=>	__( 'Brands', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'description'   =>  esc_html__( 'Enter brands.', 'xstore-core' ),
				'label_block'	=> 'true',
				'multiple' 	=>	true,
				'options' 		=> Elementor::get_terms('brand', false, false),
				'condition' => [
					'taxonomy_type' => 'brands'
				]
			]
		);

		$this->add_control(
			'limit',
			[
				'label' 		=>	__( 'Limit', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'limit' 		=>	'20',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'		=> ['type' => 'slider'],
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __( 'Title', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
	        \Elementor\Group_Control_Typography::get_type(),
	        [
	            'name'        	=> 'title_typography',
	            'label'       	=> __( 'Typography', 'xstore-core' ),
	            'selector'    	=> '{{WRAPPER}} h3.title, {{WRAPPER}} .products-title',
				'separator'   	=> 'before',
	        ]
	    );

		$this->add_control(
			'title_color',
			[
				'label' 	=> __( 'Color', 'xstore-core' ),
				'type' 		=> \Elementor\Controls_Manager::COLOR,
				'selectors'    => [
					'{{WRAPPER}} h3.title, {{WRAPPER}} .products-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
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
				'default'		=> 'center',
				'selectors'    => [
					'{{WRAPPER}} h3.title, {{WRAPPER}} .products-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Products widget output on the frontend.
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
				if ( in_array($key, array('ids', 'taxonomies', 'brands', 'product_tags') ) ) {
					$atts[$key] = !empty( $setting ) ? implode( ',',$setting ) : array();
				} elseif( 'slides' === $key ) {
					$atts['large'] = $atts['notebook'] = !empty($setting) ? $setting : 4;
				} elseif( 'slides_tablet' === $key ){
					$atts['tablet_land'] = $atts['tablet_portrait'] = !empty($setting) ? $setting : 2;
				} elseif( 'slides_mobile' === $key ){
					$atts['mobile'] = !empty($setting) ? $setting : 1;
				} else {
					$atts[$key] = $setting;
				}
			}

		}

		if ( in_array( $atts['type'], array('grid', 'list') ) )
			$atts['columns'] = $atts['columns_'.$atts['type']];

		$atts['is_preview'] = ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false );
		$atts['elementor'] = true;

		$Products_Shortcode = Products_Shortcode::get_instance();
		echo $Products_Shortcode->products_shortcode($atts, '');

	}

}
