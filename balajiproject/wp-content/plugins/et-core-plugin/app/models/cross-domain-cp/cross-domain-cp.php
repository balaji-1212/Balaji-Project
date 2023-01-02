<?php

namespace ETC\App\Models;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local as ELEMENTOR_IMPORT;

if ( !class_exists('Etheme_Cross_Domain_CP') ) {
	class Etheme_Cross_Domain_CP {
		
		/**
		 * A reference to an instance of this class.
		 *
		 * @since 4.1
		 */
		private static $instance = null;
		
		const PREFIX = 'etheme-cross-domain-cp';
		const PREFIX_UNDERSCORE = 'etheme_cross_domain_cp';
		const PATH = ET_CORE_URL . 'app/models/cross-domain-cp';
		
		/**
		 * Init actions.
		 *
		 * @since 4.1
		 *
		 * @return void
		 */
		function init() {
			
			add_action( 'wp_ajax_' . self::PREFIX_UNDERSCORE . '_import', [ $this, 'import_data' ] );
			add_action( 'wp_ajax_nopriv_' . self::PREFIX_UNDERSCORE . '_import', [ $this, 'import_data' ] );
			
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );
			
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		}
		
		public function frontend_scripts() {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				wp_enqueue_style( self::PREFIX . '-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', [], '1.0' );
			}
		}
		/**
		 * Enqueue editor scripts.
		 *
		 * @since 4.1
		 *
		 * @return void
		 */
		public function editor_scripts() {
			wp_enqueue_script( self::PREFIX . '-scripts', self::PATH . '/assets/js/script.min.js', [
				'jquery',
				'elementor-editor'
			], '1.0', true );
			
			wp_localize_script(
				self::PREFIX . '-scripts',
				self::PREFIX_UNDERSCORE . '_config',
				[
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'nonce'      => wp_create_nonce( self::PREFIX ),
					'texts'      => [
						'live_paste_info' => __( 'Cross Domain Copy Paste was developed by XStore Theme developers and could be achieved by right click on any widget/section/column', 'xstore-core' ),
						'live_paste' => __( 'Live Paste', 'xstore-core' ),
						'find_templates' => __( 'Find Widgets', 'xstore-core' ),
						'processing' => __( 'Processing...', 'xstore-core' ),
						'not_allowed' => __('Live Paste feature is not allowed right now, because you didn\'t copy any section before. You can find nice looking sections here: https://xstore.8theme.com/widgets/', 'xstore-core')
					]
				]
			);
		}
		
		/**
		 * Import function.
		 *
		 * @since 4.1
		 *
		 * @return void
		 */
		public function import_data() {
			$nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
			$data  = isset( $_REQUEST['data'] ) ? wp_unslash( $_REQUEST['data'] ) : '';
			
			if ( ! wp_verify_nonce( $nonce, self::PREFIX ) ) {
				wp_send_json_error(
					__( 'You are not allowed to complete this task, thank you.', 'xstore-core' ),
					403
				);
			}
			
			if ( empty( $data ) ) {
				wp_send_json_error( __( 'Sorry, cannot process empty content!', 'xstore-core' ) );
			}
			
			// Enable svg support
			add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
			
			$data = [ json_decode( $data, true ) ];
			
			if ( array_key_exists('saved_templates', $data[0]) ) {
//				$data[0]['saved_templates'] = $this->make_content_unique( $data[0]['saved_templates'] );
//				write_log($data[0]['saved_templates']);
//				write_log($data[0]['saved_templates']);
				// import_single_template
//				\Elementor\TemplateLibrary\Source_Local->process_export_import_content( $data[0]['saved_templates'], 'on_import' );
				// Plugin::$instance->source_local->create_temp_file
//				$template_id = \ELEMENTOR_IMPORT->save_item( [
//					'content' => $data[0]['saved_templates'],
//					'title' => $data['title'],
//					'type' => $data['type'],
//					'page_settings' => [],
//				] );
//				write_log(class_exists(\Elementor\Plugin::$instance->source_local));
//				$test = \Elementor\Plugin::$instance->uploads_manager->create_temp_file( $data[0]['saved_templates'], 'saved-templates.json' );
//				write_log($test);
				foreach ($data[0]['saved_templates'] as $saved_template) {
//					write_log($saved_template);
//					continue;
					
					$document = \Elementor\Plugin::$instance->documents->create(
						'section',
						[
							'post_title' => $saved_template['title'],
							'post_status' => 'publish',
							'post_type' => 'elementor_library',
						]
					);
					
					if ( is_wp_error( $document ) ) continue;
					
//					if ( ! empty( $template_data['elements'] ) ) {
//						$this->make_content_unique( $data[0]['saved_templates'] )
//						$content = $saved_template[0]['elements'];
//					}
					
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
					 * @since 1.0.1
					 *
					 * @param int   $template_id   The ID of the template.
					 * @param array $template_data The template data.
					 */
					do_action( 'elementor/template-library/after_save_template', $template_id, $template_data );
					
					/**
					 * After template library update.
					 *
					 * Fires after Elementor template library was updated.
					 *
					 * @since 1.0.1
					 *
					 * @param int   $template_id   The ID of the template.
					 * @param array $template_data The template data.
					 */
					do_action( 'elementor/template-library/after_update_template', $template_id, $template_data );
					
//					return $template_id;
				}
			}
			$data = $this->make_content_unique( $data );
			$data = $this->import_content( $data );
			
			remove_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
			
			wp_send_json_success( $data );
		}
		
		/**
		 * Loop through the element and set settings for it.
		 *
		 * @param \Elementor\Controls_Stack $element
		 * @return mixed
		 *
		 * @since 4.1
		 *
		 */
		protected function set_content_settings( \Elementor\Controls_Stack $element ) {
			
			$element_data = $element->get_data();
			$method       = 'on_import';
			
			if ( method_exists( $element, $method ) ) {
				$element_data = $element->{$method}( $element_data );
			}
			
			foreach ( $element->get_controls() as $control ) {
				
				$control_class = \Elementor\Plugin::instance()->controls_manager->get_control( $control['type'] );
				
				if ( ! $control_class ) {
					return $element_data;
				}
				
				if ( method_exists( $control_class, $method ) ) {
					$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
				}
			}
			
			return $element_data;
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
		 * Import function.
		 *
		 * @param $content
		 * @return mixed
		 *
		 * @since 4.1
		 *
		 */
		protected function import_content( $content ) {
			
			return \Elementor\Plugin::instance()->db->iterate_data(
				$content,
				function ( $data ) {
					
					if ( isset($data['widgetType']) ) {
						$saved_template_info = esc_html__('We have imported popup template successfully. To setup it in the correct way please, save this page, refresh and select it in dropdown.', 'xstore-core');
						switch ($data['widgetType']) {
							case 'etheme_modal_popup':
								// switch 'saved_template' type to 'content' and insert text info in popup
								if ( $data['settings']['popup_content_type'] == 'saved_template' ) {
									$data['settings']['popup_content_type'] = 'custom';
									$data['settings']['popup_content']      = $saved_template_info;
								}
								break;
							case 'et-general-tabs':
								foreach ( $data['settings']['et_tabs_tab'] as $tab_key => $tab_content ) {
									// switch 'template' type to 'content' and insert text info in popup
									if ( $data['settings']['et_tabs_tab'][$tab_key]['et_tabs_text_type'] == 'template') {
										$data['settings']['et_tabs_tab'][$tab_key]['et_tabs_text_type'] = 'content';
										$data['settings']['et_tabs_tab'][$tab_key]['et_tabs_tab_content'] = $saved_template_info;
									}
								}
								break;
								
						}
					}
					
					$widget = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $data );
					
					if ( ! $widget ) {
						// in case widget is not yet implemented in current version of plugin user is using now
						$data['settings'] = [
							'alert_type' => 'danger',
							'alert_title' => esc_html__('You are using older version of XStore Core plugin. Please, update it to latest version and try to copy/paste again.', 'xstore-core'),
							'alert_description' => ''
						];
						$data['widgetType'] = 'alert';
						$widget = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $data );
					}
					
					if ( ! $widget ) {
						return null;
					}
					
					return $this->set_content_settings( $widget );
				}
			);
		}
		
		/**
		 * Allows to import/export svg.
		 *
		 * @param $mimes
		 * @return mixed
		 *
		 * @since 4.1
		 *
		 */
		public static function add_svg_support( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}
		
		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @since  4.1
		 */
		public static function get_instance( $shortcodes = array() ) {
			
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			
			return self::$instance;
		}
		
	}
}

/**
 * Returns instance of Etheme_Cross_Domain_CP
 *
 * @return object
 */
function etheme_cross_domain_copy_paste() {
	return Etheme_Cross_Domain_CP::get_instance();
}

etheme_cross_domain_copy_paste()->init();