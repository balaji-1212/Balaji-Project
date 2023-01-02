<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Blog_Carousel as Blog_Carousel_Shortcode;

/**
 * Blog Carousel widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Blog_Carousel extends \Elementor\Widget_Base {

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
		return 'et_blog_carousel';
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
		return __( 'Blog Carousel', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-blog-carousel eight_theme-elementor-deprecated';
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
		return [ 'blog','carousel', 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type'];
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
	 * Register Blog Carousel widget controls.
	 *
	 * @since 2.1.3
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
			'post_type',
			[
				'label' 		=>	__( 'Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	__( 'Select content type for your grid.', 'xstore-core' ),
				'options' 		=>	[
					'post'	=>	esc_html__( 'Post', 'xstore-core' ),
					'ids'	=>	esc_html__( 'List of IDs', 'xstore-core' ),
				],
				'default'	=> 'post'
			]
		);

		$this->add_control(
			'include',
			[
				'label' 		=>	__( 'Include Only', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Add posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_post_pages(),
				'condition' 	=>	['post_type' => 'ids'],
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label' 		=>	__( 'Narrow Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Enter categories or tags', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>  Elementor::get_terms( 'category' ),
				'condition' 	=>	['post_type' => 'post'],
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label' 		=>	__( 'Posts limit', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'description' 	=>	__( 'Number of items to show per page', 'xstore-core' ),
				'label_block'	=> 'true',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -1,
						'max' => 30,
						'step' => 1
					],
				],
			]
		);

		$this->add_control(
			'slide_view',
			[
				'label' 		=>	__( 'Posts Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'vertical'		=>	esc_html__('Simple grid', 'xstore-core'),
					'horizontal'	=>	esc_html__('List', 'xstore-core'),
					'timeline2'		=>	esc_html__('Grid with date label', 'xstore-core'),
				],
				'default'		=> 'vertical'
			]
		);

		$this->add_control(
			'blog_hover',
			[
				'label' 		=>	__( 'Blog Hover Effect', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'zoom'		=>	esc_html__( 'Zoom', 'xstore-core' ),
					'default'	=>	esc_html__( 'Default', 'xstore-core' ),
					'animated'	=>	esc_html__( 'Animated', 'xstore-core' ),
				],
				'default' => 'zoom'
			]
		);

		$this->add_control(
			'blog_align',
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

		$this->add_control(
			'hide_img',
			[
				'label' 		=>	__( 'Hide Image', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	'true',
				'default' 		=>	'',
			]
		);

		$this->add_control(
			'size',
			[
				'label' 		=>	__( 'Images Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'default'		=> 'medium',
				'conditions' 	=> [
					'terms' 	=> [
						[
							'name' 		=> 'hide_img',
							'operator'  => '!=',
							'value' 	=> 'true'
						]
					]
				]
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
			'data_settings',
			[
				'label' => __( 'Query', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
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
					'date'				=>	esc_html__( 'Date', 'xstore-core' ),
					'ID'				=>	esc_html__( 'Order by post ID', 'xstore-core' ),
					'author'			=>	esc_html__( 'Author', 'xstore-core' ),
					'title'				=>	esc_html__( 'Title', 'xstore-core' ),
					'modified'			=>	esc_html__( 'Last modified date', 'xstore-core' ),
					'parent'			=>	esc_html__( 'Post/page parent ID', 'xstore-core' ),
					'comment_count'		=>	esc_html__( 'Number of comments', 'xstore-core' ),
					'menu_order'		=>	esc_html__( 'Menu order/Page Order', 'xstore-core' ),
					'meta_value'		=>	esc_html__( 'Meta value', 'xstore-core' ),
					'meta_value_num'	=>	esc_html__( 'Meta value number', 'xstore-core' ),
					'rand'				=>	esc_html__( 'Random order', 'xstore-core' ),
				),
				'default' 	=>	'date',
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
				'default' 	=>	'DESC',
				'condition' =>	['post_type' => 'post'],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' 		=>	__( 'Meta Key', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>	__( 'Input meta key for grid ordering', 'xstore-core' ),
				'condition' 	=>	['orderby' => array( 'meta_value', 'meta_value_num' )],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' 		=>	__( 'Exclude', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>	__( 'Exclude posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_post_pages(),
				'condition' 	=>	['post_type' => 'post'],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Get slider controls from trait
		Elementor::get_slider_params( $this );

		$this->end_controls_section();

	}

	/**
	 * Render Blog Carousel widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['include'] 	 = !empty( $settings['include'] ) ? implode( ',', $settings['include'] ) : '';

		$settings['exclude'] 	 = !empty( $settings['exclude'] ) ? implode( ',', $settings['exclude'] ) : '';

		$settings['taxonomies'] 	 = !empty( $settings['taxonomies'] ) ? implode( ',', $settings['taxonomies'] ) : '';

		$atts = array(
			'post_type'			=>	$settings['post_type'],
			'include'			=>	$settings['include'],
			'taxonomies'		=>	$settings['taxonomies'],
			'items_per_page'	=>	$settings['items_per_page']['size'],
			'slide_view' 		=>  $settings['slide_view'],
			'blog_hover'		=>	$settings['blog_hover'],
			'blog_align'		=>	$settings['blog_align'],
			'hide_img'			=>	$settings['hide_img'],
			'size'				=>	$settings['size'],
			'orderby'			=>	$settings['orderby'],
			'order'				=>	$settings['order'],
			'meta_key'			=>	$settings['meta_key'],
			'exclude'			=>	$settings['exclude'],

			'ajax'			=>	$settings['ajax'],

			'slider_speed' => $settings['slider_speed'],
			'slider_autoplay' => $settings['slider_autoplay'],
			'slider_stop_on_hover' => $settings['slider_stop_on_hover'],
			'slider_interval' => $settings['slider_interval'],
			'slider_loop' => $settings['slider_loop'],
			'autoheight' => false,
			'hide_buttons' => $settings['hide_buttons'],
			'navigation_type' => $settings['navigation_type'],
			'navigation_position_style' => $settings['navigation_position_style'],
			'navigation_style' => $settings['navigation_style'],
			'navigation_position' => $settings['navigation_position'],
			'pagination_type' => $settings['pagination_type'],
			'hide_fo' => $settings['hide_fo'],
			'nav_color' => $settings['nav_color'],
			'arrows_bg_color' => $settings['arrows_bg_color'],
			'default_color' => $settings['default_color'],
			'active_color' => $settings['active_color'],
			'large' => !empty($settings['slides']) ? $settings['slides'] : 4,
			'notebook' => !empty($settings['slides']) ? $settings['slides'] : 4,
			'tablet_portrait' => !empty($settings['slides_tablet']) ? $settings['slides_tablet'] : 2,
			'tablet_land' => !empty($settings['slides_tablet']) ? $settings['slides_tablet'] : 2,
			'mobile' => !empty($settings['slides_mobile']) ? $settings['slides_mobile'] : 1,

			'elementor' => true,
			'is_preview'		=>	\Elementor\Plugin::$instance->editor->is_edit_mode(),
		);


		$Blog_Carousel = Blog_Carousel_Shortcode::get_instance();
		echo $Blog_Carousel->blog_carousel_shortcode($atts,'');

	}

}
