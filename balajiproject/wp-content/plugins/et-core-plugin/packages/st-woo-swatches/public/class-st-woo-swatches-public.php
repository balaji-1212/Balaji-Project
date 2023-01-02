<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/public
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Swatches_Public extends St_Woo_Swatches_Base {

	/**
	 * The public directory path of a plugin.
	 */
	protected $plugin_public_dir_path;

	/**
	 * The public directory path url of a plugin.
	 */
	protected $plugin_public_dir_path_url;

	public function __construct() {

		parent::__construct();		

		$this->plugin_public_dir_path     = plugin_dir_path( __FILE__ );
		$this->plugin_public_dir_path_url = plugin_dir_url( __FILE__ );
		
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

//		add_filter( 'get_the_generator_html', array( &$this, 'generator_tag' ), 15, 2 );
//		add_filter( 'get_the_generator_xhtml', array( &$this, 'generator_tag' ), 15, 2 );

		$this->load_template_hooks();
	}

	/**
	 * Load stylesheets and scripts
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name, $this->plugin_public_dir_path_url . 'js/frontend.min.js', array( 'jquery', 'etheme' ), $this->version, false );

		$params = array(
			"ajax_url"                => esc_url( admin_url( 'admin-ajax.php' ) ),
			"is_customize_preview"    => is_customize_preview(),
			"is_singular_product"     => is_singular( 'product'),
			"show_selected_title" => get_theme_mod('swatch_item_title', 'both'),
			"add_to_cart_btn_text"    => esc_html__( 'Add to cart', 'xstore-core'),
			"read_more_btn_text"      => esc_html__( 'Read More', 'xstore-core'),
			"read_more_about_btn_text" => esc_html__( 'about', 'xstore-core'),
			"read_more_for_btn_text" => esc_html__( 'for', 'xstore-core'),
			"select_options_btn_text" => esc_html__( 'Select options', 'xstore-core'),
			'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'xstore-core' ),
		);

		wp_localize_script( $this->plugin_name, 'sten_wc_params', $params );
	}

	/**
	 * STWooSwatches by SThemes
	 */
	public function generator_tag ( $gen, $type ) {

		switch ( $type ) {

			case 'html':
				$gen .= "\n" . '<meta name="generator" content="STWooSwatches by SThemes">';
			break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="STWooSwatches by SThemes"/>';
			break;
		}

		return $gen;		
	}

	/**
	 * Load the required template hooks for frontend.
	 */
	public function load_template_hooks() {

		/**
		 * The class responsible for defining all actions and hooks that occur in the shop page.
		 */
		require_once $this->plugin_public_dir_path . 'partials/class-st-woo-shop.php';
		new St_Woo_Shop( array(
			'plugin_public_dir_path' => $this->plugin_public_dir_path,			
		) );

		/**
		 * The class responsible for defining all actions and hooks that occur in the single variable product.
		 */
		require_once $this->plugin_public_dir_path . 'partials/class-st-woo-single-variable-product.php';
		new St_Woo_Single_Variable_Product( array(
			'plugin_public_dir_path_url' => $this->plugin_public_dir_path_url,
		) );

		/**
		 * The class responsible for defining all actions and hooks that occur to show order item meta.
		 */
		require_once $this->plugin_public_dir_path . 'partials/class-st-woo-order-item.php';
		new St_Woo_OrderItem( array(
			'plugin_public_dir_path_url' => $this->plugin_public_dir_path_url,
		) );		
	}
}