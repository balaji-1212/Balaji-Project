<?php
namespace ETC\App\Controllers\Admin;

use ETC\App\Controllers\Admin\Base_Controller;
use ETC\App\Controllers\Customizer;

/**
 * Import controller.
 *
 * @since      1.4.5
 * @package    ETC
 * @subpackage ETC/Controller
 */
class Import extends Base_Controller {

	// ! Declare default variables
	private $import_url = '';
	private $widgets_counter = 0;
	public  $engine = 'wpb';
	public  $version = '';
	private $active_widgets = '';
	public  $versions = array();

	// ! Main construct/ setup variables
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Add import init actions.
	 *
	 * Require files/add ajax actions callback.
	 *
	 * @since   1.1.0
	 * @version 1.1.2
	 */
	public function init() {
		if( ! defined( 'ETHEME_THEME_SLUG' ) ) return;
		$this->import_url  = apply_filters('etheme_protocol_url', 'https://www.8theme.com/import/xstore-demos/');

		if (defined('ETHEME_BASE_URL')) {
			$this->import_url  = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/');
		}
		$this->versions    = ( function_exists( 'etheme_get_demo_versions' ) ) ? etheme_get_demo_versions() : array();
		add_action('wp_ajax_etheme_import_ajax', array($this, 'import_data'));
	}

	/**
	 * Import data router.
	 *
	 * Manage what data must be imported.
	 *
	 * @since   1.1.0
	 * @version 1.1.4
	 */
	public function import_data(){
		$versions_imported = get_option('versions_imported');
		if( empty( $versions_imported ) ) $versions_imported = array();

		if( !empty($_POST['version']) ) {
			$this->version = $_POST['version'];
		}

		if ( isset($_POST['engine']) && ! empty($_POST['engine']) ) {
			$this->engine = $_POST['engine'];
		}

		if( empty( $this->versions[ $this->version ] ) ){
			echo "wrong version";
			die();
		}

		$to_import = $this->versions[ $this->version ]['to_import'];

		do_action('et_before_data_import');

		if ( isset( $to_import['single_product_builder'] ) ) {
		   update_option( 'etheme_single_product_builder', true );
		}

		switch ($_POST['type']) {
			case 'xml':

			if ( $_POST['install']['value'] != 'et_all' ) {
				$this->import_xml_file($_POST['install']['value']);
			}

			if ( $_POST['install']['value'] == 'products' ) {
				if ( isset($to_import['brands']) && $to_import['brands'] == true ){
					$this->update_terms('brand');
				}
				if ( isset($to_import['product_cats']) && $to_import['product_cats'] == true ){
					$this->update_terms('product_cat');
				}
			}

			if (
				$_POST['install']['value'] == 'pages'
				&& isset($to_import['content-presets'])
				&& $to_import['content-presets']
				&& $this->engine == 'wpb'
			){
				$remote_data = $this->get_remote_data('content-presets');
				if ( $remote_data && is_array($remote_data) ) {
					update_option('mpc_presets_mpc_navigation', json_encode( $remote_data ));
				}
			}

			break;
			case 'options':

			if ( in_array($this->version, array('niche-market', 'eco-scooter') ) ) {
				update_option('etheme_single_product_builder', true);
			}

			if( ! empty( $to_import['options'] ) ) {
				$this->update_options();
			}
			break;

			case 'slider':
			if( ! empty( $to_import['slider'] ) ) {
				for( $i = 0; $i < $to_import['slider']; $i++ ) {
					$this->import_slider( $i );
				}
			}
			break;

			case 'home_page':
			if( ! empty( $to_import['home_page'] ) ) {
				$this->update_home_page();
			}
			break;

			case 'widgets':
			if( ! empty( $to_import['widgets'] ) ) {
				$this->update_widgets();
			}
			break;

			case 'menu':
			$xml_result = $this->import_xml_file($_POST['install']['value']);
			$this->update_menus();
			break;

			case 'fonts':
			$this->import_custom_fonts();
			break;

			case 'variation_taxonomy':
			$this->import_variation_taxonomy('variation_taxonomy');
			break;

			case 'variations_trems':
			$this->import_variations_trems('variations_trems');
			break;

			case 'variation_products':
			$this->import_variation_products(3);
			break;

			case 'et_multiple_headers':
				$this->import_multiple_conditions('et_multiple_headers', 'et_multiple_headers');
				$this->update_menus(true);
			break;

			case 'et_multiple_conditions':
				$this->import_multiple_conditions('et_multiple_conditions', 'et_multiple_conditions');
			break;

			case 'et_multiple_single_product':
				$this->import_multiple_conditions('et_multiple_single_product', 'et_multiple_single_product');
			break;

			case 'et_multiple_single_product_conditions':
				$this->import_multiple_conditions('et_multiple_single_product_conditions', 'et_multiple_single_product_conditions');
			break;

			case 'elementor_globals':
				$this->import_elementor_globals();
			break;
			
			case 'elementor_sections':
				$this->import_elementor_sections();
//				$this->remove_elementor_sections();
//				$this->import_xml_file($_POST['install']['value']);
				break;
				
			case 'imported':
				$versions_imported[] = $this->version;
				update_option('versions_imported', $versions_imported);
				break;
			case 'default_woocommerce_pages':
				$this->default_woocommerce_pages();
				break;
			case 'version_info':
				$this->get_version_info();
				break;
			case 'init_builders':
				$this->init_builders();
				break;
			default:
			break;
		}

		do_action('et_after_data_import');
		die();
	}
	
	/**
	 * Import slider.
	 *
	 * Import revolution slider.
	 *
	 * @since   1.1.1
	 * @version 1.1.1
	 *
	 * @param integer $i sliders count
	 * @return bool result of import
	 */
	public function import_slider( $i = 0 ) {

		$zip_file = ( $i > 0 ) ? 'slider' . $i: 'slider' ;

		$activated_data = get_option( 'etheme_activated_data' );
		$key = $activated_data['api_key'];

		if( ! $key || empty( $key ) ) return false;

		$slider_url = $this->generate_remote_url($zip_file);

		try {
			$zip_file = download_url( $slider_url );
		} catch( Exception $e ) {
			return false;
		}

		if(!class_exists('RevSlider')) return false;

		$revapi = new \RevSlider();

		ob_start();

		$slider_result = $revapi->importSliderFromPost(true, true, $zip_file);

		ob_end_clean();

		return $slider_result;
	}

	/**
	 * Import xml files.
	 *
	 * Use WordPress importer to do it.
	 *
	 * @since   1.1.0
	 * @version 1.1.1
	 *
	 * @param string $file file name with extension
	 * @return bool|object true on success|Wp error object
	 */
	public function import_xml_file($file) {

		ini_set( 'max_execution_time', 900 );

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS' , true );
		}

		include ET_CORE_DIR . 'packages/wordpress-importer/wordpress-importer.php';

		$result = false;

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		$importerError = false;

		//check if wp_importer, the base importer class is available, otherwise include it
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once($class_wp_importer);
			else
				$importerError = true;
		}

		if($importerError !== false) {
			echo ("The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually.");
			return false;
		}

		if(class_exists('WP_Importer')) {
			
			// Enable svg support
			add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );

			$uploads = wp_get_upload_dir();

			$version_data = $this->get_remote_data($file, false);

			$tmpxml = '';

			if( $version_data ) {
				$tmpxml = $uploads['basedir']. '/xstore-tmp-data.xml';
				file_put_contents($tmpxml, $version_data);
			}

			try {

				ob_start();

				add_filter( 'intermediate_image_sizes', function($sizes){return array();} );

				$file_url = $tmpxml;

				$importer = new \WP_Import();

				$importer->fetch_attachments = true;

				$importer->import($file_url);

				$result = ob_get_clean();

			} catch (Exception $e) {
				$result = false;
				echo ("Error while importing");
			}
			
			// Enable svg support
			remove_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
		}
		return $result;
	}

	/**
	 * Update menus.
	 *
	 * Force update menus in theme options.
	 *
	 * @since   1.1.0
	 * @version 1.1.1
	 * @param $multiple boolean : is multiple menus or not
	 */
	public function update_menus($multiple = false){
		$menus = array();
		$menus['main_menu_term'] = wp_get_nav_menu_object( 'Main menu' );
		$menus['main_menu_2_term'] = wp_get_nav_menu_object( 'Secondary menu' );
		$menus['secondary_menu_term'] = wp_get_nav_menu_object( 'All departments' );

		if ( wp_get_nav_menu_object( 'Mobile menu' ) ) {
			$menus['mobile_menu_term'] = wp_get_nav_menu_object( 'Mobile menu' );
		} else {
			$menus['mobile_menu_term'] = $menus['main_menu_term'];
		}

		if ( wp_get_nav_menu_object( 'Vertical menu' ) ){
			$menus['header_vertical_menu_term'] = wp_get_nav_menu_object( 'Vertical menu' );
		} else {
			$menus['header_vertical_menu_term'] = $menus['main_menu_term'];
		}

		if ($multiple){
			$multiple_headers = get_option('et_multiple_headers');
			if ($multiple_headers){
				$multiple_headers = json_decode($multiple_headers, true);
				if (is_array($multiple_headers) && count($multiple_headers)){
					foreach ( $multiple_headers as $key => $value ) {
						foreach ($menus as $k => $v) {
							if ( isset($multiple_headers[$key]['options'][$k]) && isset( $v->term_id ) ) {
								$multiple_headers[$key]['options'][$k] = strval($v->term_id);
							}
						}
					}
					update_option( 'et_multiple_headers', json_encode($multiple_headers) );
				}
			}
		} else {
			foreach ($menus as $key => $value) {
				if ( isset( $value->term_id ) ) {
					set_theme_mod( $key, strval($value->term_id) );
				}
			}
		}
	}

	/**
	 * Update widgets.
	 *
	 * Create custom widget areas/ Create widgets.
	 *
	 * @since   1.1.0
	 * @version 1.1.1
	 */
	private function update_widgets() {
		$widgets = $this->get_remote_data('widgets');

		// We don't want to undo user changes, so we look for changes first.
		$this->active_widgets = get_option( 'sidebars_widgets' );

		$this->widgets_counter = 1;

		if( ! empty( $widgets['custom-sidebars'] ) ) {
			foreach ($widgets['custom-sidebars'] as $customsidebar) {
				etheme_add_sidebar( $customsidebar );
			}
		}

		foreach ($widgets['sidebar-widgets'] as $area => $params) {
			if ( ! empty ( $this->active_widgets[$area] ) && $params['flush'] ) {
				$this->flush_widget_area($area);
			} else if(! empty ( $this->active_widgets[$area] ) && ! $params['flush'] ) {
				continue;
			}
			foreach ($params['widgets'] as $widget => $args) {
				$this->add_widget($area, $args['widget'], $args['args']);
			}
		}
		// Now save the $active_widgets array.
		update_option( 'sidebars_widgets', $this->active_widgets );
	}

	/**
	 * Add widget.
	 *
	 * Create widgets.
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 *
	 * @param integer $sidebar widget area id
	 * @param integer $widget  widget id
	 * @param array   $options widget options
	 */
	private function add_widget( $sidebar, $widget, $options = array() ) {
		$this->active_widgets[ $sidebar ][] = $widget . '-' . $this->widgets_counter;
		$widget_content = get_option( 'widget_' . $widget );
		$widget_content[ $this->widgets_counter ] = $options;
		update_option(  'widget_' . $widget, $widget_content );
		$this->widgets_counter++;
	}

	/**
	 * Flush widget area.
	 *
	 * Flush widget area in area.
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 *
	 * @param integer $area widget area id
	 */
	private function flush_widget_area( $area ) {
		unset($this->active_widgets[ $area ]);
	}

	/**
	 * Update home page.
	 *
	 * Update show_on_front/page_on_front/page_for_posts.
	 *
	 * @since   1.1.0
	 * @version 1.1.0
	 */
	public function update_home_page() {
		$blog_id = get_page_by_title('Blog');
		$home_page = get_page_by_title('Home ' . str_replace('home-', '', $this->version));
		$pageid = $home_page->ID;
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pageid );
		update_option( 'page_for_posts', $blog_id->ID );
	}

	/**
	 * Update options.
	 *
	 * Update theme options by using set_theme_mod.
	 *
	 * @since   1.1.0
	 * @version 1.1.1
	 */
	public function update_options() {
		$new_options = $this->get_remote_data('options', false);

		if( $new_options ) {
			$new_options = @unserialize( $new_options );

			if ( isset($new_options['mods']) && is_array($new_options['mods'])) {

				unset($new_options['mods']['0']);
				unset($new_options['mods']['nav_menu_locations']);
				unset($new_options['mods']['custom_css_post_id']);

				foreach ( $new_options['mods'] as $key => $val ) {

					if ( $key == 'footer_border_width' ) {
						$val = intval($val);
					}

					// Save the mod.
					set_theme_mod( $key, $val );
				}

				set_theme_mod( 'load_wc_cart_fragments', true );
				set_theme_mod( 'images_loading_type_et-desktop', 'default' );
			}
			update_option( 'elementor_global_image_lightbox', '' );
		}
	}

	/**
	 * Import custom fonts.
	 *
	 * Load font file and update it in wp_options.
	 *
	 * @since   1.5.1
	 * @version 1.0.1
	 */
	public function import_custom_fonts(){
		$fonts = get_option( 'etheme-fonts', false );

		if ( ! is_array( $fonts ) ) {
			$fonts = array();
		}

		$new_fonts = $this->get_remote_data('fonts');

		foreach ($new_fonts as $key => $value) {
			$id  = array_search($value['id'], array_column($fonts, 'id'));

			if ( $id !== false ) {
				continue;
			}

			// Get remote font
			$file         = $value['file'];
			$file['time'] = current_time( 'mysql' );
			$remote_file  = $file['url'];
			$content      = file_get_contents( $remote_file );
			$uploads      = wp_get_upload_dir();

			// Setup right font folder
			$time         = current_time( 'mysql' );
			$y            = substr( $time, 0, 4 );
			$m            = substr( $time, 5, 2 );
			$subdir       = "/$y/$m";

			$fonts_uploads = array(
				'path'   => $uploads['basedir'] . '/custom-fonts' . $subdir,
				'url'    => $uploads['baseurl'] . '/custom-fonts' . $subdir ,
				'subdir' => '/custom-fonts' . $subdir,
			);

			// Create custom fonts folder
			$is_dir = is_dir( $fonts_uploads['path'] );
			if ( ! $is_dir ) {
				$resoult = wp_mkdir_p( $fonts_uploads['path'] );
				if ( ! $resoult ){
					return esc_html__( 'Can not create custom fonts folder', 'xstore-core' );
				}
			}

			// Put remote file content into the folder/ reset file url
			if ( ! file_exists( $fonts_uploads['path'] . '/' . $file['name'] ) ) {
				$file['name'] = str_replace( ' ', '-', $file['name'] );
				file_put_contents( $fonts_uploads['path'] . '/' . $file['name'], $content );
			}
			$file['url'] = $fonts_uploads['url'] . '/' . $file['name'];
			
			$value['file'] = $file;
			$fonts[] = $value;
		}
		update_option( 'etheme-fonts', $fonts );
		return false;
	}

	/**
	 * Update brands.
	 *
	 * Update theme brands.
	 *
	 * @since   1.5.2
	 * @version 1.0.1
	 *
	 * @param  string $terms terms to import
	 */
	public function update_terms($terms) {
		$remote_data = $this->get_remote_data($terms);
		if (! $remote_data ) {
			return;
		}

		foreach ($remote_data as $key => $value) {
			$term = get_term_by('slug', $value['term']['slug'], $terms);
			if ( $term ) {
				$id = $term->term_id;

				// Update brand parent
				if ($value['term']['parent']) {

					$parent = array_filter($remote_data,function($var) use ($value){
						return( $var['term']['term_id'] == $value['term']['parent'] );
					});

					if ( $parent ) {
						$parent = end($parent);
						if ( $parent['term'] && $parent['term']['term_id'] ) {
							$parent = get_term_by('slug', $parent['term']['slug'], $terms);
							wp_update_term( $id, $terms, array(
								'parent' => $parent->term_id,
							));
						}
					}
				}

				// Update brand thumbnail_id

				if ($value['meta'] && $value['meta']['thumbnail_id'] && $value['meta']['thumbnail_id'][0] ) {
					update_term_meta( $id, 'thumbnail_id', $value['meta']['thumbnail_id'][0] );
				}

				// Update brand description
				if ($value['term']['description']) {
					wp_update_term( $id, $terms, array(
						'description' => $value['term']['description'],
					));
				}

				if ( in_array($terms, array('category', 'product_cat', 'product_tag') ) ) {
					if (isset($value['meta']['_et_second_description']) && isset($value['meta']['_et_second_description'][0])) {
						update_term_meta( $id, '_et_second_description', $value['meta']['_et_second_description'][0] );
					}

					if ($value['meta'] && isset($value['meta']['_et_page_heading_id']) && isset($value['meta']['_et_page_heading_id'][0] ) ) {
						update_term_meta( $id, '_et_page_heading_id', $value['meta']['_et_page_heading_id'][0] );
					}

					if (
						$value['meta'] && 
						isset($value['meta']['_et_page_heading']) && 
						isset($value['meta']['_et_page_heading'][0]) && 
						isset($value['meta']['_et_page_heading_id']) && 
						isset($value['meta']['_et_page_heading_id'][0] ) ) {
						update_term_meta( $id, '_et_page_heading', wp_get_attachment_url($value['meta']['_et_page_heading_id'][0]) ) ;
					}
				}
			}
		}
	}

	/**
	 * import variation taxonomy.
	 *
	 * create taxonomy for product attributes.
	 *
	 * @since   1.5.3
	 * @version 1.0.1
	 *
	 * @param  string $terms terms to import
	 */
	public function import_variation_taxonomy($terms){
		$remote_data = $this->get_remote_data($terms);

		foreach ($remote_data as $taxonomie) {
			$args = array(
				'id'      => '',
				'name'    => $taxonomie['taxonomie']['attribute_name'],
				'label'   => $taxonomie['taxonomie']['attribute_label'],
				'type'    => $taxonomie['taxonomie']['attribute_type'],
				'orderby' => $taxonomie['taxonomie']['attribute_orderby'],
				'public'  => false
			);
			wc_create_attribute( $args );
		}
	}

	/**
	 * import variation trems.
	 *
	 * create trems for product attributes.
	 *
	 * @since   1.5.3
	 * @version 1.0.2
	 *
	 * @param  string $terms terms to import
	 * @log
	 * Fixed wp_error
	 */
	public function import_variations_trems($terms){
		$remote_data = $this->get_remote_data($terms);

		foreach ($remote_data as $taxonomie) {
			if ( isset( $taxonomie['taxonomie'] ) && isset( $taxonomie['taxonomie']['terms'] ) ) {
				foreach ( $taxonomie['taxonomie']['terms'] as $term ) {
					$args = array(
						'description' => $term['term']['description'],
						'parent'      => 0,
						'slug'        => $term['term']['slug'],
					);

					$insert_data = wp_insert_term( $term['term']['name'], 'pa_' . $taxonomie['taxonomie']['attribute_name'], $args );

					foreach ( $term['meta'] as $key => $value ) {
						$swatches = array(
							'st-color-swatch-sq',
							'st-color-swatch',
							'st-label-swatch-sq',
							'st-label-swatch',
							'st-image-swatch-sq',
							'st-image-swatch'
						);
						if (
							in_array( $key, $swatches )
							&& ! is_wp_error( $value[0] )
							&& ! is_wp_error( $insert_data )
						) {
							update_term_meta( $insert_data['term_id'], $key, $value[0] );
						}
					}
				}
			}
		}
	}

	/**
	 * import products variations.
	 *
	 * create variations for products.
	 *
	 * @since   1.5.3
	 * @version 1.0.1
	 *
	 * @param  integer $count number of products to import
	 */
	public function import_variation_products($count){
		$remote_data = $this->get_remote_data('variation_products');
		$_i = 0;
		foreach ($remote_data as $key => $value) {
			if ( $_i == $count ) {
				return;
			}
			$_i++;

			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
				'post_name__in'  => array($key),
				'fields'         => 'ids' 
			);
			$q  = get_posts( $args );
			$id = $q[0];

			foreach ($value as $variation) {
				$attributes = array();

				foreach ($variation['attributes'] as $attribute_key => $attribute) {
					$attributes[str_replace( 'attribute_pa_', '', $attribute_key )] = $attribute;
				}

				$variation_data =  array(
					'attributes' => $attributes,
					// 'sku'           => $variation['sku'],
					'regular_price' => $variation['display_regular_price'],
					'sale_price'    => $variation['display_price'],
					'stock_qty'     => $variation['max_qty'],
					// 'stock' => $variation['is_in_stock'],
					'image_id' => $variation['image_id'],

				);
				$this->create_product_variation( $id, $variation_data );
			}
		}
	}

	/**
	 * import products variations.
	 *
	 * create variations for products.
	 *
	 * @since   1.5.3
	 * @version 1.0.2
	 * @log 1.0.1
	 * Fix non object slug
	 * 1.0.2
	 * Fix $product not a product
	 */
	public function create_product_variation( $product_id, $variation_data ){
		// Get the Variable product object (parent)
		$product = wc_get_product($product_id);

		if (!$product) return;

		$variation_post = array(
			'post_title'  => $product->get_title(),
			'post_name'   => 'product-'.$product_id.'-variation',
			'post_status' => 'publish',
			'post_parent' => $product_id,
			'post_type'   => 'product_variation',
			'guid'        => $product->get_permalink()
		);

		$variation_id = wp_insert_post( $variation_post );
		$variation    = new \WC_Product_Variation( $variation_id );

		foreach ($variation_data['attributes'] as $attribute => $term_name ){
			$taxonomy = 'pa_'.$attribute; 

			if( ! taxonomy_exists( $taxonomy ) ) {
				register_taxonomy(
					$taxonomy,
					'product_variation',
					array(
						'hierarchical' => false,
						'label'        => ucfirst( $attribute ),
						'query_var'    => true,
						'rewrite'      => array( 'slug' => sanitize_title($attribute) ), 
					)
				);
			}

			if( ! term_exists( $term_name, $taxonomy ) ) {
				wp_insert_term( $term_name, $taxonomy ); 
			}

			$term_slug       = get_term_by('name', $term_name, $taxonomy );

			if (is_object($term_slug) && $term_slug->slug){
				$term_slug = $term_slug->slug;
				$post_term_names = wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );
				if( ! in_array( $term_name, $post_term_names ) ){
					wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
				}
				update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
			}
		}

		if( ! empty( $variation_data['sku'] ) ){
			$variation->set_sku( $variation_data['sku'] );
		}

		if( empty( $variation_data['sale_price'] ) ){
			$variation->set_price( $variation_data['regular_price'] );
		} else {
			$variation->set_price( $variation_data['sale_price'] );
			$variation->set_sale_price( $variation_data['sale_price'] );
		}
		$variation->set_regular_price( $variation_data['regular_price'] );

		if( ! empty($variation_data['stock_qty']) ){
			$variation->set_stock_quantity( $variation_data['stock_qty'] );
			$variation->set_manage_stock(true);
			$variation->set_stock_status('');
		} else {
			$variation->set_manage_stock(false);
		}

		if ( ! empty( $variation_data['image_id'] ) ) {
			$variation->set_image_id($variation_data['image_id']);
		}

		$variation->set_weight(''); 
		$variation->save(); 
	}

	/**
	 * import products variations.
	 *
	 * create variations for products.
	 *
	 * @since   2.2.1
	 * @version 1.0.1
	 *
	 * @param  string $file   name of file with extension
	 * @param  string $option option name
	 */
	public function import_multiple_conditions($option, $file){
		$local = json_decode( get_option( $option ), true );
		if ( ! is_array($local) ) {
			$local = array();
		}

		$remote_data = $this->get_remote_data($file);

		foreach ($remote_data as $key => $value) {
			if ( ! isset( $local[$key] ) ) {
				$local[$key] = $value;
			} 
		}
		update_option( $option, json_encode($local) );
	}

	/**
	 * import elementor globals.
	 *
	 * @since   2.3.0
	 * @version 1.0.1
	 *
	 */
	public function import_elementor_globals(){
		$remote_data = $this->get_remote_data('elementor_globals');

		if ($remote_data) {
		   foreach ($remote_data as $key => $value) {
			   update_option( $key, $value );
		   }
		}

		if (class_exists('CSS_Manager')) {
			$CSS_Manager = new CSS_Manager();
			$CSS_Manager->save_settings();
		}
	}
	
	public function import_elementor_sections() {
		
		ini_set( 'max_execution_time', 900 );
		
		$data = $this->get_remote_data('elementor_sections');
		
		// Enable svg support
		add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
		
		foreach ($data as $saved_template) {
				$document = \Elementor\Plugin::$instance->documents->create(
					'section',
					[
						'post_title'  => $saved_template['title'],
						'post_status' => 'publish',
						'post_type'   => 'elementor_library',
					]
				);
				
				if ( is_wp_error( $document ) ) {
					continue;
				}
			
				$template_data = [
					'elements' => $this->make_content_unique( $saved_template['content'] ),
					'settings' => $saved_template['settings'],
				];
				
				$document->save( $template_data );
				
				$template_id = $document->get_main_id();
				
				/**
				 * After template library save.
				 *
				 * Fires after Elementor template library was saved.
				 *
				 * @param int   $template_id   The ID of the template.
				 * @param array $template_data The template data.
				 *
				 * @since 1.0.1
				 *
				 */
				do_action( 'elementor/template-library/after_save_template', $template_id, $template_data );
				
				/**
				 * After template library update.
				 *
				 * Fires after Elementor template library was updated.
				 *
				 * @param int   $template_id   The ID of the template.
				 * @param array $template_data The template data.
				 *
				 * @since 1.0.1
				 *
				 */
				do_action( 'elementor/template-library/after_update_template', $template_id, $template_data );

		}
		
		
		remove_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
	}
	
	public static function add_svg_support( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	
	/**
	 * Generates random id before inserting content.
	 *
	 * @param $content
	 * @return mixed
	 *
	 * @since 4.1
	 *
	 */
	protected function make_content_unique( $content ) {
		return \Elementor\Plugin::instance()->db->iterate_data( $content, function ( $element ) {
			$element['id'] = \Elementor\Utils::generate_random_string();
			
			return $element;
		} );
	}
	
	/**
	 * Install default WooCommerce pages.
	 *
	 * @since   7.1.1
	 * @version 1.0.0
	 *
	 */
	public function default_woocommerce_pages(){
		$shop_page = get_page_by_title('shop');
		$cart_page = get_page_by_title('cart');
		$checkout_page = get_page_by_title('checkout');
		$my_account = get_page_by_title('my account');

		if ($shop_page){
			update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		}
		if ($cart_page){
			update_option( 'woocommerce_cart_page_id', $cart_page->ID );
		}
		if ($checkout_page){
			update_option( 'woocommerce_checkout_page_id', $checkout_page->ID);
		}
		if ($my_account){
			update_option( 'woocommerce_myaccount_page_id',$my_account->ID );
		}
	}

	/**
	 * Get version info.
	 *
	 * @since   7.1.0
	 * @version 1.0.3
	 *
	 */
	public function get_version_info(){

		$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
		$Customizer->customizer_style('kirki-styles');

		$Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
		$Etheme_Customize_header_Builder->generate_header_builder_style('all');

		$Etheme_Customize_header_Builder = new \Etheme_Customize_header_Builder();
		$Etheme_Customize_header_Builder->generate_single_product_style('all');

		$data = wp_remote_get($this->generate_url());
		$code = wp_remote_retrieve_response_code($data);

		if( ! is_wp_error( $data ) && $code == '200' ) {
			$data = wp_remote_retrieve_body($data);
			$verified = json_decode($data,true);
			$verified['domain'] = $this->get_domain();
			update_option('etheme_current_version',$data, 'no');
			wp_send_json($verified);
		} else {
			$data = false;
		}

		return true;
	}

	/**
	 * Generate url
	 *
	 * @since   3.2.4
	 * @version 1.0.0
	 *
	 * @return string url
	 */
	public function generate_url(){
		$errors = isset($_POST['errors']) ? $_POST['errors'] : null;
		$base = $this->import_url . '1/versions/';
		return add_query_arg(
			array(
				'etheme_version_info' => $this->version,
				'etheme_engine' => $this->engine,
				'errors' => json_encode($errors),
				'system' => json_encode($this->get_system()),
				'pkg' => json_encode(
					array(
						'theme'=> ETHEME_THEME_VERSION,
						'plugin' => ET_CORE_VERSION,
						'code' => $this->get_code(),
						'domain' => $this->get_domain()
					)
				),
			),
		$base );
	}

	/**
	 * Get domain
	 *
	 * @since   3.2.5
	 * @version 1.0.0
	 *
	 * @return string domain
	 */
	public function get_domain() {
		$domain = get_option('siteurl'); //or home
		$domain = str_replace('http://', '', $domain);
		$domain = str_replace('https://', '', $domain);
		$domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
		return urlencode($domain);
	}

	/**
	 * Get system status
	 *
	 * @since   3.2.4
	 * @version 1.0.0
	 *
	 * @return array system status
	 */
	public function get_system(){
		$system = '';
		if ( class_exists('Etheme_System_Requirements') ) {
			$system = new \Etheme_System_Requirements();
			$system = $system->get_system();
		} elseif( defined('ETHEME_CODE') && is_user_logged_in() && current_user_can('administrator') ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'system-requirements.php') );

			$system = new \Etheme_System_Requirements();
			$system = $system->get_system();
		}
		$system['wp_uploads'] = wp_is_writable( $system['wp_uploads']['basedir'] );
		unset($system['curl_version']);
		return $system;
	}

	/**
	 * Get code
	 *
	 * @since   3.2.4
	 * @version 1.0.0
	 *
	 * @return string code
	 */
	public function get_code(){
		$activated_data = get_option( 'etheme_activated_data' );
		$activated_data = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
		return $activated_data;
	}

	/**
	 * Generate remote url.
	 *
	 * @since   2.3.2
	 * @version 1.0.1
	 *
	 * @param  string $file name of file with extension
	 * @return string       path to file
	 */
	public function generate_remote_url($file){
		return $this->import_url . $this->version . '/' . $this->engine . '/' . $this->get_file_name($file) . '?code=' . $this->get_code();
	}

	/**
	 * Get remote data.
	 *
	 * @since   2.3.2
	 * @version 1.0.1
	 *
	 * @param  string     $file name of file with extension
	 * @param  bool       $json use or not json_decode
	 * @return array|bool $data array with data|false if fail
	 */
	public function get_remote_data($file, $json=true){
		$data = wp_remote_get($this->generate_remote_url($file));
		$code = wp_remote_retrieve_response_code($data);

		if( ! is_wp_error( $data ) && $code == '200' ) {
			$data = wp_remote_retrieve_body($data);
			if ($json){
				$data = json_decode($data, true);
			}
		} else {
			$data = false;
		}
		return $data;
	}

	/**
	 * Get file name.
	 *
	 * @since   2.3.2
	 * @version 1.0.0
	 *
	 * @param  string      $file name of import type
	 * @return string|bool file name with extension|false if no file
	 */
	public function get_file_name($file){
		$files = array(
			'fonts'=> 'fonts.json',
			'brand'=> 'brands.json',
			'product_cat'=> 'product_cats.json',
			'variation_taxonomy'=> 'product_variation_terms.json',
			'variations_trems'=> 'product_variation_terms.json',
			'variation_products'=> 'data_product_variations.json',
			'et_multiple_conditions'=> 'multiple_header_conditions.json',
			'et_multiple_headers'=> 'multiple_header_templates.json',
			'et_multiple_single_product_conditions'=> 'multiple_single_product_conditions.json',
			'et_multiple_single_product'=> 'multiple_single_product_templates.json',
			'elementor_globals'=> 'elementor_defaults.json',
			'elementor_sections'=> 'elementor_sections.json',
			'contact-forms'=> 'contact-forms.xml',
			'coupons'=> 'coupons.xml',
			'grid-builder'=> 'grid-builder.xml',
			'mailchimp'=> 'mailchimp.xml',
			'media'=> 'media.xml',
			'menu'=> 'menu.xml',
			'orders'=> 'orders.xml',
			'pages'=> 'pages.xml',
			'posts'=> 'posts.xml',
			'products'=> 'products.xml',
			'projects'=> 'projects.xml',
			'refunds'=> 'refunds.xml',
			'smart-product'=> 'smart-product.xml',
			'static-blocks'=> 'static-blocks.xml',
			'testimonials'=> 'testimonials.xml',
			'variations'=> 'variations.xml',
			'vc-templates'=> 'vc-templates.xml',
			'widgets'=> 'widgets.json',
			'options'=> 'options.dat',
			'slider'=> 'slider.zip',
			'slider1'=> 'slider1.zip',
			'slider2'=> 'slider2.zip',
			'slider3'=> 'slider3.zip',
			'slider4'=> 'slider4.zip',
			'slider5'=> 'slider5.zip',
			'slider6'=> 'slider6.zip',
			'content-presets'=> 'content-presets.json'
		);
		return ( isset($files[$file]) ) ? $files[$file] : false;
	}

	/**
	 * Init builders for custom post types.
	 *
	 * @since   4.3.5
	 * @version 1.0.0
	 *
	 *
	 * @return string|bool file name with extension|false if no file
	 */
	public function init_builders(){
		if (defined( 'ELEMENTOR_VERSION' )){
			$elementor_cpt_support = get_option( 'elementor_cpt_support' );
			if (!is_array($elementor_cpt_support)){
				$elementor_cpt_support = array('post', 'page', 'staticblocks', 'testimonials', 'etheme_portfolio', 'product');
			} else {
				$elementor_cpt_support[] = 'staticblocks';
				$elementor_cpt_support[] = 'testimonials';
				$elementor_cpt_support[] = 'etheme_portfolio';
				$elementor_cpt_support[] = 'product';
			}
			update_option('elementor_cpt_support', $elementor_cpt_support);
		}

		if (defined( 'WPB_VC_VERSION' )){
			if (!function_exists('get_editable_roles')) {
				require_once(ABSPATH . '/wp-admin/includes/user.php');
			}

			if (!class_exists('Vc_Roles')){
				require_once vc_path_dir( 'SETTINGS_DIR', 'class-vc-roles.php' );
			}
			$roles = new \Vc_Roles();
			$roles->save(
				array(
					'administrator' => json_encode(array(
						"post_types"      => [
							"_state"            => "custom",
							"post"              => "1",
							"page"              => "1",
							"e-landing-page"    => "0",
							"elementor_library" => "0",
							"staticblocks"      => "1",
							"testimonials"      => "1",
							"etheme_portfolio"  => "1",
							"product"           => "1"
						],
						"backend_editor"  => [
							"_state"             => "1",
							"disabled_ce_editor" => "0"
						],
						"frontend_editor" => [
							"_state" => "1"
						],
						"unfiltered_html" => [
							"_state" => "1"
						],
						"post_settings"   => [
							"_state" => "1"
						],
						"templates"       => [
							"_state" => "1"
						],
						"shortcodes"      => [
							"_state" => "1"
						],
						"grid_builder"    => [
							"_state" => "1"
						],
						"presets"         => [
							"_state" => "1"
						],
						"dragndrop"       => [
							"_state" => "1"
						]
					)),
					'editor' => json_encode(array(
						"post_types"      => [
							"_state"            => "custom",
							"post"              => "1",
							"page"              => "1",
							"e-landing-page"    => "0",
							"elementor_library" => "0",
							"staticblocks"      => "1",
							"testimonials"      => "1",
							"etheme_portfolio"  => "1",
							"product"           => "1"
						],
						"backend_editor"  => [
							"_state"             => "1",
							"disabled_ce_editor" => "0"
						],
						"frontend_editor" => [
							"_state" => "1"
						],
						"unfiltered_html" => [
							"_state" => "1"
						],
						"post_settings"   => [
							"_state" => "1"
						],
						"templates"       => [
							"_state" => "1"
						],
						"shortcodes"      => [
							"_state" => "1"
						],
						"grid_builder"    => [
							"_state" => "1"
						],
						"presets"         => [
							"_state" => "1"
						],
						"dragndrop"       => [
							"_state" => "1"
						]
					))
				)
			);
		}
		return true;
	}
}