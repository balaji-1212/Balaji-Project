<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages the way Google Fonts are enqueued.
 */
final class Etheme_Modules_Webfonts_Embed {

	/**
	 * The config ID.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $config_id;

	/**
	 * The Kirki_Fonts_Google object.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var object
	 */
	protected $googlefonts;

	/**
	 * Fonts to load.
	 *
	 * @access protected
	 * @since 3.0.26
	 * @var array
	 */
	protected $fonts_to_load = array();

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0
	 * @param string $config_id   The config-ID.
	 * @param object $webfonts    The Kirki_Modules_Webfonts object.
	 * @param object $googlefonts The Kirki_Fonts_Google object.
	 * @param array  $args        Extra args we want to pass.
	 */
	public function __construct( $config_id, $googlefonts, $args = array() ) {
		$this->config_id   = $config_id;
		$this->googlefonts = $googlefonts;
		$this->populate_fonts();

	}

	/**
	 * Webfont Loader for Google Fonts.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function populate_fonts() {

		// Go through our fields and populate $this->fonts.
		$this->loop_fields( $this->config_id );

		// Goes through $this->fonts and adds or removes things as needed.
		$this->googlefonts->process_fonts();

		foreach ( $this->googlefonts->fonts as $font => $weights ) {
			foreach ( $weights as $key => $value ) {
				if ( 'italic' === $value ) {
					$weights[ $key ] = '400i';
				} else {
					$weights[ $key ] = str_replace( array( 'regular', 'bold', 'italic' ), array( '400', '', 'i' ), $value );
				}
			}
			$this->fonts_to_load[] = array(
				'family'  => $font,
				'weights' => $weights,
			);
		}
	}

	/**
	 * Webfont Loader script for Google Fonts.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function the_css() {
		$webfonts = '';
		foreach ( $this->fonts_to_load as $font ) {
			$family  = str_replace( ' ', '+', trim( $font['family'] ) );
			$weights = join( ',', $font['weights'] );
			$url     = "https://fonts.googleapis.com/css?family={$family}:{$weights}&subset=cyrillic,cyrillic-ext,devanagari,greek,greek-ext,khmer,latin,latin-ext,vietnamese,hebrew,arabic,bengali,gujarati,tamil,telugu,thai&display=swap";

			$transient_id = 'kirki_gfonts_' . md5( $url );
			$contents     = get_transient( $transient_id );

			if ( ! class_exists( 'Kirki_Fonts_Downloader' ) ) {
				include_once wp_normalize_path( Kirki::$path . '/modules/webfonts/class-kirki-fonts-downloader.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
			}
			$downloader = new Kirki_Fonts_Downloader();
			$contents   = $downloader->get_styles( $url );

			if ( $contents ) {
				$webfonts .= wp_strip_all_tags( $contents );
			}
		}

		return $webfonts;
	}


	/**
	 * Goes through all our fields and then populates the $this->fonts property.
	 *
	 * @access public
	 * @param string $config_id The config-ID.
	 */
	public function loop_fields( $config_id ) {

		foreach ( Kirki::$fields as $field ) {
			if ( isset( $field['kirki_config'] ) && $config_id !== $field['kirki_config'] ) {
				continue;
			}
			if ( true === apply_filters( "kirki_{$config_id}_webfonts_skip_hidden", true ) ) {
				// Only continue if field dependencies are met.
				if ( ! empty( $field['required'] ) ) {
					$valid = true;

					foreach ( $field['required'] as $requirement ) {
						if ( isset( $requirement['setting'] ) && isset( $requirement['value'] ) && isset( $requirement['operator'] ) ) {
							$controller_value = Kirki_Values::get_value( $config_id, $requirement['setting'] );
							if ( ! Kirki_Helper::compare_values( $controller_value, $requirement['value'], $requirement['operator'] ) ) {
								$valid = false;
							}
						}
					}

					if ( ! $valid ) {
						continue;
					}
				}
			}
			$this->googlefonts->generate_google_font( $field );
		}
	}
}
