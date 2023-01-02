<?php

require_once QLWAPP_PLUGIN_DIR . 'includes/models/QLWAPP_Model.php';

class QLWAPP_Button extends QLWAPP_Model {


	protected $table = 'button';

	function get_args() {
		$args = array(
			'layout'          => 'button',
			'box'             => 'no',
			'position'        => 'bottom-right',
			'text'            => esc_html__( 'How can I help you?', 'wp-whatsapp-chat' ),
			'message'         => sprintf( esc_html__( 'Hello! I\'m testing the %1$s plugin %2$s', 'wp-whatsapp-chat' ), QLWAPP_PLUGIN_NAME, QLWAPP_LANDING_URL ),
			'icon'            => 'qlwapp-whatsapp-icon',
			'type'            => 'phone',                 // here we define the type of button, can be 'phone' or 'group'
			'phone'           => QLWAPP_PHONE_NUMBER,
			'group'           => '',
			'developer'       => 'no',
			'rounded'         => 'yes',
			'timefrom'        => '00:00',
			'timeto'          => '00:00',
			'timedays'        => array(),
			'timezone'        => qlwapp_get_current_timezone(),
			'visibility'      => 'readonly',
			'timeout'         => 'readonly', /* TODO: delete */
			'animation-name'  => '',
			'animation-delay' => '',
		);
		return $args;
	}

	function sanitize( $settings ) {
		if ( isset( $settings['layout'] ) ) {
			$settings['layout'] = sanitize_html_class( $settings['layout'] );
		}
		if ( isset( $settings['position'] ) ) {
			$settings['position'] = sanitize_html_class( $settings['position'] );
		}
		if ( isset( $settings['text'] ) ) {
			$settings['text'] = sanitize_text_field( $settings['text'] );
		}
		if ( isset( $settings['message'] ) ) {
			$settings['message'] = sanitize_text_field( $settings['message'] );
		}
		if ( isset( $settings['icon'] ) ) {
			$settings['icon'] = sanitize_html_class( $settings['icon'] );
		}
		if ( isset( $settings['phone'] ) ) {
			$settings['phone'] = qlwapp_format_phone( $settings['phone'] );
		}
		if ( isset( $settings['group'] ) ) {
			$settings['group'] = sanitize_url( $settings['group'] );
		}

		return $settings;
	}

	function save( $button_data = null ) {
		return parent::save_data( $this->table, $this->sanitize( $button_data ) );
	}

	function get() {

		$result = $this->get_all( $this->table );

		$result = wp_parse_args( $result, $this->get_args() );

		if ( ! is_admin() ) {
			$result['text']    = qlwapp_replacements_vars( $result['text'] );
			$result['message'] = qlwapp_replacements_vars( $result['message'] );
		}

		return $result;
	}
}
