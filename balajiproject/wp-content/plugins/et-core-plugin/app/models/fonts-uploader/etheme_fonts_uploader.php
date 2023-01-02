<?php

/**
 *
 * @package     XStore theme
 * @author      8theme
 * @version     1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Etheme_Custom_Fonts' ) ) {
	
	
	/**
	 * Main Etheme_Custom_Fonts class
	 *
	 * @since       3.1.6
	 */
	class Etheme_Custom_Fonts {
		
		// Protected vars
		public $extension_url;
		public $extension_dir;
		public static $theInstance;
		
		/**
		 * Class Constructor. Defines the args for the extions class
		 *
		 * @param array $sections   Panel sections.
		 * @param array $args       Class constructor arguments.
		 * @param array $extra_tabs Extra panel tabs.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function __construct() {
			
			$this->post_data = $_POST;
			$this->file_data = $_FILES;
			$this->errors    = $this->successes = array();
			
			// ! Call upload file function
			if ( isset( $this->post_data['et-upload'] ) ) {
				$this->upload_action();
			}
			
			$this->enqueue();
		}
		
		// ! Add ajax actions
		public function actions() {
			add_action( 'wp_ajax_et_ajax_fonts_remove', array( $this, 'et_ajax_fonts_remove' ) );
			add_action( 'wp_ajax_et_ajax_fonts_export', array( $this, 'et_ajax_fonts_export' ) );
		}
		
		// ! Remove font by ajax
		public function et_ajax_fonts_remove() {
			
			$post_data = $_POST;
			$fonts     = get_option( 'etheme-fonts', false );
			
			$out = array(
				'messages' => array(),
				'status'   => 'error'
			);
			
			if ( ! isset( $post_data['id'] ) || empty( $post_data['id'] ) ) {
				$out['messages'][] = esc_html__( 'File ID does not exist', 'xstore-core' );
				echo json_encode( $out );
				die();
			}
			
			if ( ! function_exists( 'wp_delete_file' ) ) {
				require_once ABSPATH . WPINC . '/functions.php';
			}
			
			foreach ( $fonts as $key => $value ) {
				
				if ( $value['id'] == $post_data['id'] ) {
					
					$file = $value['file'];
					
					$upload_dir = wp_upload_dir();
					$upload_dir = $upload_dir['basedir'] . '/custom-fonts';
					$url        = explode( '/custom-fonts', $file['url'] );
					
					wp_delete_file( $upload_dir . $url[1] );
					
					if ( $this->custom_font_exists( $file['url'] ) ) {
						$out['messages'][] = esc_html__( 'File was\'t deleted', 'xstore-core' );
						die();
					} else {
						unset( $fonts[ $key ] );
					}
				}
			}
			
			update_option( 'etheme-fonts', $fonts );
			
			if ( count( $out['messages'] ) < 1 ) {
				$out['status']     = 'success';
				$out['messages'][] = esc_html__( 'File was deleted', 'xstore-core' );
			}
			echo json_encode( $out );
			die();
		}
		
		// ! Field Render Function
		public function render() {
			
			$out = $style = '';
			
			$out .= '<h2 class="etheme-page-title etheme-page-title-type-2">' . esc_html__( 'Upload Your Custom Font', 'xstore-core' ) . '</h2>';
			
			$out .= '<div class="et-col-7 etheme-fonts-section">';
			
			$out .= '<p>' . esc_html__( 'Upload the custom font to use throughout the site. For full browser support it\'s recommended to upload all formats. You can upload as many custom fonts as you need. The font you upload here will be available in the font-family drop-downs at the Typography options.', 'xstore-core' ) . '</p>';
			
			$out .= '<p class="et-message et-info">' . sprintf( esc_html__( 'Uploaded fonts you may find in your %s -> %s section', 'xstore-core' ), '<a href="' . esc_url( wp_customize_url() ) . '">' . esc_html__( 'Customizer', 'xstore-core' ) . '</a>', '<a href="' . add_query_arg( 'autofocus[section]', 'typography-content', wp_customize_url() ) . '">' . esc_html__( 'Typography', 'xstore-core' ) . '</a>' ) . '</p>';
			
			$out .= '<a class="add-form et-button et-button-green no-loader last-button">' . esc_html__( 'Upload font', 'xstore-core' ) . '</a>';
			
			ob_start();
			do_action( 'etheme_custom_font_export' );
			
			$out .= ob_get_clean();
			
			$out .= '<a id="et_download-export-file" class="hidden" href=""></a>';
			
			if ( count( $this->errors ) > 0 ) {
				foreach ( $this->errors as $value ) {
					$out .= '<p class="et-message et-error">' . $value . '</p>';
				}
			}
			
			if ( count( $this->successes ) > 0 ) {
				foreach ( $this->successes as $value ) {
					$out .= '<p class="et-message">' . $value . '</p>';
				}
			}
			
			$fonts = get_option( 'etheme-fonts', false );
			
			// ! Out font information
			if ( $fonts ) {
				$out .= '<div class="et_fonts-info">';
				$out .= '<h2>' . esc_html__( 'Uploaded fonts', 'xstore-core' ) . '</h2>';
				$out .= '<ul>';
				
				$style .= '<style>';
				
				foreach ( $fonts as $value ) {
					
					// ! Set HTML
					$out .= '<li>';
					$out .= '<p>';
					$out .= '<span class="et_font-name">' . $value['name'] . '</span>';
					$out .= '<i class="et_font-remover dashicons dashicons-no-alt" aria-hidden="true" data-id="' . $value['id'] . '"></i>';
					$out .= '</p>';
					
					if ( ! $this->custom_font_exists( $value['file']['url'] ) ) {
						$out .= '<p class="et_font-error et-message et-info">';
						$out .= esc_html__( 'It looks like font file was removed from the folder directly', 'xstore-core' );
						$out .= '</p>';
						continue;
					}
					
					$out .= '<p class="et_font-preview" style="font-family: &quot;' . $value['name'] . '&quot;;"> 1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z </p>';
					$out .= '<details>';
					$out .= '<summary>' . esc_html__( 'Font details', 'xstore-core' ) . '</summary>';
					$out .= '<ul>';
					$out .= '<li>' . esc_html__( 'Uploaded at', 'xstore-core' ) . ' : ' . $value['file']['time'] . '</li>';
					$out .= '<li>';
					$out .= '</li>';
					$out .= '<li>' . esc_html__( 'File name', 'xstore-core' ) . ' : ' . $value['file']['name'] . '</li>';
					$out .= '<li>' . esc_html__( 'File size', 'xstore-core' ) . ' : ' . $this->file_size( $value['file']['size'] ) . '</li>';
					$out .= '</ul>';
					$out .= '</details>';
					$out .= '</li>';
					
					// ! Validate format
					switch ( $value['file']['extension'] ) {
						case 'ttf':
							$format = 'truetype';
							break;
						case 'otf':
							$format = 'opentype';
							break;
						case 'eot':
							$format = false;
							break;
						case 'eot?#iefix':
							$format = 'embedded-opentype';
							break;
						case 'woff2':
							$format = 'woff2';
							break;
						case 'woff':
							$format = 'woff';
							break;
						default:
							$format = false;
							break;
					}
					
					$format = ( $format ) ? 'format("' . $format . '")' : '';
					
					$font_url = ( is_ssl() && ( strpos( $value['file']['url'], 'https' ) === false ) ) ? str_replace( 'http', 'https', $value['file']['url'] ) : $value['file']['url'];
					
					// ! Set fonts
					$style .= '
                                    @font-face {
                                        font-family: "' . $value['name'] . '";
                                        src: url(' . $font_url . ') ' . $format . ';
                                    }
                                ';
				}
				
				$style .= '</style>';
				
				$out .= '</ul>';
				$out .= '</div>';
			}
			
			$out .= '</div>';
			
			$out .= '
                <div class="et-col-5 et_fonts-notifications etheme-options-info">
                    <h2 clas="et_fonts-table-title" style="margin-top: 40px;">' . esc_html__( 'Browser Support for Font Formats', 'xstore-core' ) . '</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th>' . esc_html__( 'Font format', 'xstore-core' ) . '</th>
                                <th class="et_fonts-br-name et_ie"><i class="fa fa-internet-explorer" aria-hidden="true"></i></th>
                                <th class="et_fonts-br-name et_chrome"><i class="fa fa-chrome" aria-hidden="true"></i></th>
                                <th class="et_fonts-br-name et_firefox"><i class="fa fa-firefox" aria-hidden="true"></i></th>
                                <th class="et_fonts-br-name et_safari"><i class="fa fa-safari" aria-hidden="true"></i></th>
                                <th class="et_fonts-br-name et_opera"><i class="fa fa-opera" aria-hidden="true"></i></th>                
                            </tr>
                            <tr>
                                <td>TTF/OTF</td>
                                <td>9.0*</td>
                                <td>4.0</td>
                                <td>3.5</td>
                                <td>3.1</td>
                                <td>10.0</td>
                            </tr>
                            <tr>
                                <td>WOFF</td>
                                <td>9.0</td>
                                <td>5.0</td>
                                <td>3.6</td>
                                <td>5.1</td>
                                <td>11.1</td>
                            </tr>
                            <tr>
                                <td>WOFF2</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>36.0</td>
                                <td>35.0*</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>26.0</td>
                            </tr>
                            <!-- <tr>
                                <td>SVG</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>4.0</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td>3.2</td>
                                <td>9.0</td>
                            </tr> -->
                            <tr>
                                <td>EOT</td>
                                <td>6.0</td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                                <td><i class="et_deprecated dashicons dashicons-no-alt" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="et-message et-info">' . esc_html__( 'Please, make sure that you upload font formats that are supported by all the browsers.', 'xstore-core' ) . '</p>
                </div>
            ';
			
			echo $style . $out;
		}
		
		// ! Upload file
		private function upload_action() {
			
			// ! Return if name file
			if ( ! isset( $this->file_data['et-fonts'] ) || empty( $this->file_data['et-fonts'] ) ) {
				$this->errors[] = esc_html__( 'Empty Font file field', 'xstore-core' );
				
				return;
			}
			
			// ! Require file
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			
			// ! Set Valid file formats
			$valid_formats = array( 'eot', 'woff2', 'woff', 'ttf', 'otf' );
			
			$file = $this->file_data['et-fonts'];
			
			// ! Get file extension
			$extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
			
			// ! Check file extension
			if ( ! in_array( strtolower( $extension ), $valid_formats ) ) {
				$this->errors[] = esc_html__( 'Wrong file extension "use only: eot, woff2, woff, ttf, otf"', 'xstore-core' );
				
				return;
			}
			
			// ! Check size 5mb limit
			if ( $file['size'] > ( 1048576 * 7 ) ) {
				$this->errors[] = esc_html__( 'File size more then 7MB', 'xstore-core' );
				
				return;
			}
			
			if ( $file['name'] ) {
				
				// ! Set overrides
				$overrides = array(
					'test_form' => false,
					'test_type' => false,
				);
				
				// ! Set font user data
				$user             = wp_get_current_user();
				$by               = array();
				$by['user_email'] = $user->user_email;
				$by['user_login'] = $user->user_login;
				$by['roles']      = array();
				foreach ( $user->roles as $value ) {
					$by['roles'][] = $value;
				}
				
				$font_file = array(
					'name'      => $file['name'],
					'type'      => $file['type'],
					'size'      => $file['size'],
					'extension' => $extension,
					'time'      => current_time( 'mysql' ),
				);
				
				// ! Change upload dir
				add_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );
				
				$status = wp_handle_upload( $file, $overrides );
				
				// ! Set upload dir to default
				remove_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );
				
				if ( $status && ! isset( $status['error'] ) ) {
					$font_file['url']   = $status['url'];
					$this->gafq_files[] = $font_file;
					$this->successes[]  = esc_html__( 'File was successfully uploaded.', 'xstore-core' );
					
					// ! Update fonts
					$fonts = get_option( 'etheme-fonts', false );
					$font  = array();
					
					$font['id']   = mt_rand( 1000000, 9999999 );
					$font['name'] = str_replace( '.' . $extension, '', $file['name'] );
					$font['file'] = $font_file;
					$font['user'] = $by;
					$fonts[]      = $font;
					update_option( 'etheme-fonts', $fonts );
					
				} else {
					//$this->errors[] = $status['error'];
				}
			}
			
			return;
		}
		
		// ! Upload dir filter function
		public function etheme_upload_dir( $dir ) {
			$time   = current_time( 'mysql' );
			$y      = substr( $time, 0, 4 );
			$m      = substr( $time, 5, 2 );
			$subdir = "/$y/$m";
			
			return array(
				       'path'   => $dir['basedir'] . '/custom-fonts' . $subdir,
				       'url'    => $dir['baseurl'] . '/custom-fonts' . $subdir,
				       'subdir' => '/custom-fonts' . $subdir,
			       ) + $dir;
		}
		
		// **********************************************************************//
		// ! Check file exists by url
		// **********************************************************************//
		public function custom_font_exists( $url ) {
			$upload_dir = wp_upload_dir();
			$upload_dir = $upload_dir['basedir'] . '/custom-fonts';
			$url = explode( '/custom-fonts', $url );
			
			return file_exists( $upload_dir . $url[1] );
		}
		
		// Get formated file size
		public function file_size( $bytes ) {
			if ( $bytes >= 1073741824 ) {
				$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
			} elseif ( $bytes >= 1048576 ) {
				$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
			} elseif ( $bytes >= 1024 ) {
				$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
			} elseif ( $bytes > 1 ) {
				$bytes = $bytes . ' bytes';
			} elseif ( $bytes == 1 ) {
				$bytes = $bytes . ' byte';
			} else {
				$bytes = '0 bytes';
			}
			
			return $bytes;
		}
		
		// ! Enqueue Function
		
		public function enqueue() {
			wp_enqueue_style( 'etheme-custom-fonts-css', ET_CORE_URL . 'app/models/fonts-uploader/style.css' );
			wp_enqueue_script( 'etheme-custom-fonts-js', ET_CORE_URL . 'app/models/fonts-uploader/script.js', array(
				'jquery'
			) );
		}
		
		public function et_ajax_fonts_export() {
			$fonts = get_option( 'etheme-fonts', false );
			foreach ( $fonts as $key => $value ) {
				$fonts[ $key ]['user']['user_login'] = 'imported';
				$fonts[ $key ]['user']['user_email'] = 'imported';
				$fonts[ $key ]['user']['roles']      = 'imported';
			}
			wp_send_json( $fonts );
		}
		
	} // class
	
	$custom_fonts = new Etheme_Custom_Fonts();
	$custom_fonts->actions();
} // if
