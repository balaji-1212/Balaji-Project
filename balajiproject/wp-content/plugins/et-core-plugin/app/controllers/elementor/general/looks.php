<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Traits\Elementor;
use ETC\App\Controllers\Shortcodes\Looks as Looks_Shortcode;

/**
 * Looks widget.
 *
 * @since      2.1.3
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Looks extends \Elementor\Widget_Base {

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
		return 'et_looks';
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
		return __( 'Product Looks', 'xstore-core' );
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
		return 'eicon-product-upsell eight_theme-element-icon';
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
		return [ 'looks' ];
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
	 * Register Looks widget controls.
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
			'class',
			[
				'label' => __( 'Extra Class', 'xstore-core' ),
				'type' 	=> \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_settings',
			[
				'label' => __( 'Items', 'xstore-core' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'divider1',
			[
				'label' 		=>	__( 'Query', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'post_type',
			[
				'label' 		=>	__( 'Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'description' 	=>  esc_html__( 'Select content type for your grid.', 'xstore-core' ),
				'options' 		=>	[
					'product' 	=>	esc_html__( 'Product', 'xstore-core' ),
					'ids' 		=>	esc_html__( 'List of IDs', 'xstore-core' ),
				],
				'default'		=> 'product',
			]
		);

		$repeater->add_control(
			'include',
			[
				'label' 		=>	__( 'Include Only', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>  esc_html__( 'Add posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	false,
				'options' 		=>	Elementor::get_post_pages( array( 'post', 'page' ) ),
				'default' 		=>	'',
				'condition'		=>	[ 'post_type' => 'ids' ]
			]
		);

		$repeater->add_control(
			'exclude',
			[
				'label' 		=>	__( 'Exclude', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>  esc_html__( 'Exclude posts, pages, etc. by title.', 'xstore-core' ),
				'multiple' 		=>	false,
				'options' 		=>	Elementor::get_post_pages( array( 'post', 'page' ) ),
				'default' 		=>	'',
			]
		);

		$repeater->add_control(
			'taxonomies',
			[
				'label' 		=>	__( 'Narrow Data Source', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT2,
				'label_block'	=> 'true',
				'description' 	=>  esc_html__( 'Enter categories, tags or custom taxonomies.', 'xstore-core' ),
				'multiple' 		=>	false,
				'options' 		=>	Elementor::get_terms('product_cat'),
				'default' 		=>	'',
				'condition'		=>	[ 'post_type' => 'product']
			]
		);

		$repeater->add_control(
			'items_per_page',
			[
				'label' 		=> __( 'Items Per Page', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'description'  	=> esc_html__( 'Number of items to show per page.', 'xstore-core' ),
				'default'		=> '10',
			]
		);

		$repeater->add_control(
			'columns',
			[
				'label' 		=>	__( 'Columns Number', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					3	=>	esc_html__('3', 'xstore-core'),
					4	=>	esc_html__('4', 'xstore-core'),
				],
				'default'		=> 3
			]
		);

		$repeater->add_control(
			'divider2',
			[
				'label' 		=>	__( 'Layout', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'product_view',
			[
				'label' 		=>	__( 'Product View', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					 'default'	=>	esc_html__( 'Default','xstore-core'),
					 'mask'		=>	esc_html__( 'Buttons on hover','xstore-core'),
					 'info'		=>	esc_html__( 'Information mask','xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'product_view_color',
			[
				'label' 		=>	__( 'Product View Color', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'white'	=> esc_html__('White', 'xstore-core'),
					'dark'	=> esc_html__('Dark', 'xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'divider3',
			[
				'label' 		=>	__( 'Query', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
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

		$repeater->add_control(
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
			]
		);

		$repeater->add_control(
			'meta_key',
			[
				'label' 		=> __( 'Meta Key', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'description'  	=> esc_html__( 'Input meta key for grid ordering.', 'xstore-core' ),
				'condition' 	=> ['orderby' => array( 'meta_value', 'meta_value_num' )],
			]
		);

		$repeater->add_control(
			'divider4',
			[
				'label' 		=>	__( 'Image', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'img',
			[
				'label' => __( 'Banner Image', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'img_size',
			[
				'label' 		=> __( 'Banner Size', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'description'  	=> esc_html__( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'default'		=> '360x790',
			]
		);

		$repeater->add_control(
			'divider5',
			[
				'label' 		=>	__( 'Content', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __( 'Banner Mask Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' 	  => __( 'Link', 'xstore-core' ),
				'type' 		  => \Elementor\Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'divider6',
			[
				'label' 		=>	__( 'Alignment', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'align',
			[
				'label' 		=>	__( 'Align', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SELECT,
				'options' 		=>	[
					'left'		=>	esc_html__('Left', 'xstore-core'), 
					'center'	=>	esc_html__('Center', 'xstore-core'), 
					'right'		=>	esc_html__('Right', 'xstore-core'),
				],
				'default'		=> 'center',
			]
		);

		$repeater->add_control(
			'valign',
			[
				'label' 	=>	__( 'Vertical Align', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'center'  	=>	esc_html__('Center', 'xstore-core'), 
					'top'		=>	esc_html__('Top', 'xstore-core'),
					'bottom' 	=>	esc_html__('Bottom', 'xstore-core'), 
				],
				'default'		=> 'bottom',
			]
		);

		$repeater->add_control(
			'divider7',
			[
				'label' 		=>	__( 'Design', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' 	=>	__( 'Type', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					1 => esc_html__('Design 1', 'xstore-core'),
					2 => esc_html__('Design 2', 'xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'font_style',
			[
				'label' 	=>	__( 'Color Scheme', 'xstore-core' ),
				'type' 		=>	\Elementor\Controls_Manager::SELECT,
				'multiple' 	=>	false,
				'options' 	=>	[
					'', 
					'light' => esc_html__('light', 'xstore-core'), 
					'dark'  => esc_html__('dark', 'xstore-core'),
				],
			]
		);

		$repeater->add_control(
			'banner_pos',
			[
				'label' 		=> __( 'Banner Position', 'xstore-core' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'description'  	=> esc_html__( 'Banner position number. From 1 to number of products.', 'xstore-core' ),
				'default'		=> '1'
			]
		);

		$repeater->add_control(
			'banner_double',
			[
				'label' 		=>	__( 'Banner Double Size', 'xstore-core' ),
				'type' 			=>	\Elementor\Controls_Manager::SWITCHER,
				'return_value'  =>	1,
				'default' 		=>	0,
			]
		);

		$this->add_control(
			'the_look_settings',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'The Look', 'xstore-core' ),
						'list_content' => __( 'Add The Look Item.', 'xstore-core' ),
					],
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Looks widget output on the frontend.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = array(
			'class'	=>	$settings['class'],
		);

		$content = '';
		if ( $settings['the_look_settings'] ) {
			foreach (  $settings['the_look_settings'] as $item ) {
				// Url link
				if ( $item['link']['nofollow'] ) {
					$item['link']['nofollow'] = 'nofollow';
				}		
				if ( $item['link']['is_external'] ) {
					$item['link']['is_external'] = '%20_blank';
				}
				$item['link'] = isset( $item['link']['url'] ) ? 'url:' . $item['link']['url'] . '|target:' . $item['link']['is_external'] . '|rel:' . $item['link']['nofollow'] : '#';

				$content .= '[et_the_look post_type="'. $item['post_type'] .'" include="'. $item['include'] .'" exclude="'. $item['exclude'] .'" taxonomies="'. $item['taxonomies'] .'" items_per_page="'. $item['items_per_page'] .'" columns="'. $item['columns'] .'" product_view="'. $item['product_view'] .'" product_view_color="'. $item['product_view_color'] .'" orderby="'. $item['orderby'] .'" order="'. $item['order'] .'" meta_key="'. $item['meta_key'] .'" img="'. $item['img']['id'] .'" img_size="'. $item['img_size'] .'" link="'. $item['link'] .'" align="'. $item['align'] .'" valign="'. $item['valign'] .'" type="'. $item['type'] .'" font_style="'. $item['font_style'] .'" banner_pos="'. $item['banner_pos'] .'" banner_double="'. $item['banner_double'] .'" css="'. $item['css'] .'"]'. $item['content'] .'[/et_the_look]';
			}
		}

		$Looks_Shortcode =  Looks_Shortcode::get_instance();
		echo $Looks_Shortcode->looks_shortcode( $args , $content);
	}

}
