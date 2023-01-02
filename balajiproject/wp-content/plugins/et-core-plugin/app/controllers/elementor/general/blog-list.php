<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;

/**
 * Blog List widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Blog_List extends \Elementor\Widget_Base {

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
		return 'et_blog_list';
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
		return __( 'Blog List / Chess', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-blog-list';
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
		return [ 'blog','blog-list', 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
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
	 * Register Blog List widget controls.
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
				'description' 	=>	__( 'Enter categories, tags or custom taxonomies', 'xstore-core' ),
				'multiple' 		=>	true,
				'options' 		=>	Elementor::get_terms( get_object_taxonomies( 'post' ) ),
				'condition' 	=>	['post_type' => 'post'],
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label' 		=>	__( 'Items Per Page', 'xstore-core' ),
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
			'items_limit',
			[
				'label' 		=>	__( 'Limit', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::NUMBER,
				'default'	 	=>	'10',
				'min' 	=> '1',
				'max' 	=> '',
			]
		);

		$this->add_control(
			'blog_layout',
			[
				'label' 		=>	__( 'Posts Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'small' => esc_html__('List', 'xstore-core'),
					'chess' => esc_html__('Chess', 'xstore-core'),
				],
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
				'default'		=> 'zoom'
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
			'size',
			[
				'label' 		=>	__( 'Images Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::TEXT,
				'description' 	=>	__( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'default'		=> 'medium'
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
				'condition' =>	['post_type' => 'post'],
				'default' 	=>	'DESC',
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

	}

	/**
	 * Render Blog List widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['include'] 	 = !empty( $settings['include'] ) ? implode( ',', $settings['include'] ) : '';
		$settings['exclude'] 	 = !empty( $settings['exclude'] ) ? implode( ',', $settings['exclude'] ) : '';

		$settings['taxonomies'] 	 = !empty( $settings['taxonomies'] ) ? implode( ',', $settings['taxonomies'] ) : '';

		echo do_shortcode( '[et_blog_list 
			post_type="'. $settings['post_type'] .'"
			include="'. $settings['include'] .'"
			taxonomies="'. $settings['taxonomies'] .'"
			items_per_page="'. $settings['items_per_page']['size'] .'"
			items_limit="'. $settings['items_limit'] .'"
			blog_layout="'. $settings['blog_layout'] .'"
			blog_hover="'. $settings['blog_hover'] .'"
			blog_align="'. $settings['blog_align'] .'"
			size="'. $settings['size'] .'"
			orderby="'. $settings['orderby'] .'"
			order="'. $settings['order'] .'"
			meta_key="'. $settings['meta_key'] .'"
			exclude="'. $settings['exclude'] .'"
			is_preview="'. ( \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false ) .'"]' 
		);

	}

}
