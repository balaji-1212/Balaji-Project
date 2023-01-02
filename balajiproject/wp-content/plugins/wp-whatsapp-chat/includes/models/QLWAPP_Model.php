<?php

require_once QLWAPP_PLUGIN_DIR . 'includes/helpers.php';

class QLWAPP_Model {

	protected $qlwapp = null;

	function options() {
		$options                = array();
		$options['box']         = array();
		$options['button']      = array();
		$options['chat']        = array();
		$options['contacts']    = array();
		$options['display']     = array();
		$options['scheme']      = array();
		$options['woocommerce'] = array();
		$options['license']     = array();
		$options['settings']    = array();
		return $options;
	}

	function get() {

		$result = $this->get_all( $this->table );
		return wp_parse_args( $result, $this->get_args() );
	}

	public function sanitize_value_data( $value_data, $args ) {

		foreach ( $value_data as $key => $value ) {

			if ( array_key_exists( $key, $args ) ) {

				$type = $args[ $key ];

				if ( is_null( $type ) && ! is_numeric( $value ) ) {
					$value_data[ $key ] = intval( $value );
				} elseif ( is_bool( $type ) && ! is_bool( $value ) ) {
					$value_data[ $key ] = ( $value === 'true' || $value === '1' || $value === 1 );
				} elseif ( is_string( $type ) && ! is_string( $value ) ) {
					$value_data[ $key ] = strval( $value );
				} elseif ( is_array( $type ) && ! is_array( $value ) ) {
					$value_data[ $key ] = (array) $type;
				}
			} else {
				unset( $value_data[ $key ] );
			}
		}
		return $value_data;
	}

	function save_all( $qlwapp ) {
		return update_option( 'qlwapp', $qlwapp );
	}

	function save_data( $key = null, $data = null ) {
		$qlwapp         = get_option( 'qlwapp' );
		$qlwapp[ $key ] = $data;
		return $this->save_all( $qlwapp );
	}

	function get_all( $key ) {
		if ( ! $this->qlwapp ) {
			$this->qlwapp = get_option( 'qlwapp', array() );
		}
		$this->qlwapp = wp_parse_args( $this->qlwapp, $this->options() );
		$res          = $this->qlwapp[ $key ];
		return $res;
	}

}
