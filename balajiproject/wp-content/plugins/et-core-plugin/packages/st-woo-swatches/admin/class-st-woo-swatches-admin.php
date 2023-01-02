<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    St_Woo_Swatches
 * @subpackage St_Woo_Swatches/admin
 * @author     SThemes <s.themes@aol.com>
 */
class St_Woo_Swatches_Admin extends St_Woo_Swatches_Base {

	/**
	 * The admin directory path url of a plugin.
	 */
	protected $plugin_admin_dir_path_url;

	public function __construct() {

		parent::__construct();

		$this->plugin_admin_dir_path = plugin_dir_path( __FILE__ );
		$this->plugin_admin_dir_path_url = plugin_dir_url( __FILE__ );


		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ),100 );		

		add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 5 );
		add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );

		$this->load_includes();
	}

	/**
	 * Load stylesheet and scripts in edit product attribute screen
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();

		if( $screen->id == 'shop_order' ) {

			$css = '.woocommerce_order_items_wrapper .st-swatch-preview { 
				display: inline-block;
				margin: 0 5px;
				border: 1px solid #dddddd;
				padding: 2px;
				cursor: pointer;	
				position: relative;
				border-radius: 26px;
				width: 20px;
				height: 20px;
				text-align: center;
				line-height: 20px;
			}';

			$css .= '.woocommerce_order_items_wrapper .st-swatch-preview span {
				display: inline-block;
				border-radius: 26px;
				width: 20px;
				height: 20px;
				font-size: 14px;
			}';

			$css .= '.woocommerce_order_items_wrapper .st-swatch-preview span img {
				width: 20px;
				height: 20px;
				border-radius: 20px;
			}';

			$css .= '.woocommerce_order_items_wrapper .display_meta th, .woocommerce_order_items_wrapper .display_meta p {
				line-height: 24px !important;
			}';


			wp_add_inline_style( 'woocommerce_admin_styles', $css );
		}

		if ( strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( $this->plugin_name, $this->plugin_admin_dir_path_url . 'css/admin.css', array( 'wp-color-picker' ), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name, $this->plugin_admin_dir_path_url . 'js/admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );
	}

	/**
	 * Add additional links below each plugin on the plugins page.
	 */
	public function plugin_action_links( $actions, $plugin_file ) {

		static $plugin;

		if ( !isset($plugin) ) {

			$plugin = $this->plugin_basename;
		}

		if ( $plugin == $plugin_file ) {

			$links = array(
				'settings'   => sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( get_admin_url( null, add_query_arg( array( 'autofocus' => array( 'panel' => 'woocommerce' ) ), 'customize.php' ) ) ),
					esc_html__( 'Settings', 'xstore-core' )
				),
				'add-swatch' => sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( get_admin_url( null, add_query_arg( array( 'post_type' => 'product', 'page' => 'product_attributes' ), 'edit.php' ) ) ),
					esc_html__( 'Add Swatch', 'xstore-core' )
				),
			);

			$actions = array_merge( $links, $actions );
		}

		return $actions;
	}


	/**
	 * Add additional links below each plugin on the plugins page.
	 */
	public function plugin_row_meta( $actions, $plugin_file ) {

		if( $this->plugin_basename == $plugin_file ) {

			$row_meta = array(
				'support' => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://codecanyon.net/item/stwooswatches-advanced-variable-product-attributes-swatches-for-woocommerce/21905266/comments', esc_html__( 'Premium Support','xstore-core') ),
			);

			$actions = array_merge( $actions, $row_meta );
		}

		return $actions;
	}

	/**
	 * Load the required files for admin functionalities.
	 */
	public function load_includes() {

		/**
		 * The class responsible for defining all actions and hooks that occur in the admin product attributes edit page.
		 * edit.php?post_type=product&page=product_attributes
		 */
		require_once $this->plugin_admin_dir_path . 'partials/class-st-woo-product-attributes.php';
		new St_Woo_Product_Attributes( array(
			'plugin_admin_dir_path' => $this->plugin_admin_dir_path,
			'plugin_admin_dir_path_url' => $this->plugin_admin_dir_path_url,
		) );

		/**
		 * The class responsible for defining all actions and hooks that occur in the admin product add and edit page.
		 * ?post_type=product
		 */
		require_once $this->plugin_admin_dir_path . 'partials/class-st-woo-product.php';
		new St_Woo_Product();
	}
}