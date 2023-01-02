<?php

require_once QLWAPP_PLUGIN_DIR . 'includes/helpers.php';
require_once QLWAPP_PLUGIN_DIR . 'includes/models/Button.php';
require_once QLWAPP_PLUGIN_DIR . 'includes/models/Display_Component.php';

class QLWAPP_Contact extends QLWAPP_Model {

	protected $table = 'contacts';

	function get_args() {

		$display_component_model = new Display_Component();
		$args                    = array(
			'id'         => null,
			'order'      => 1,
			'active'     => 1,
			'chat'       => true,
			'auto_open'  => false,
			'avatar'     => 'https://www.gravatar.com/avatar/00000000000000000000000000000000',
			'type'		 => 'phone', 				// here we define the type of button, can be 'phone' or 'group'
			'phone'      => '',
			'group'		 => '',
			'firstname'  => 'John',
			'lastname'   => 'Doe',
			'label'      => esc_html__( 'Support', 'wp-whatsapp-chat' ),
			'message'    => sprintf( esc_html__( 'Hello! I\'m testing the %1$s plugin %2$s', 'wp-whatsapp-chat' ), QLWAPP_PLUGIN_NAME, QLWAPP_LANDING_URL ),
			'timefrom'   => '00:00',
			'timeto'     => '00:00',
			'timezone'   => qlwapp_get_current_timezone(),
			'visibility' => 'readonly',
			'timeout'    => 'readonly', /* TODO: delete */
			'timedays'   => array(),
			'display'    => $display_component_model->get_args(),
		);

		return $args;
	}

	function get_next_id() {
		$contactos = $this->get_contacts();
		if ( count( $contactos ) ) {
			return max( array_keys( $contactos ) ) + 1;
		}
		return 0;
	}

	function add_contact( $contact_data ) {
		$contact_id         = $this->get_next_id();
		$contact_data['id'] = $contact_id;
		return $this->save( $contact_data );
	}

	function update_contact( $contact_data ) {
		return $this->save( $contact_data );
	}

	function update_contacts( $contacts, $order = 0 ) {
		return $this->save_with_reorder( $contacts );
	}

	function save( $contact_data = null ) {
		$contacts                        = $this->get_contacts();
		$contacts[ $contact_data['id'] ] = wp_parse_args( $contact_data, $this->get_args() );
		return $this->save_with_reorder( $contacts, 1 );
	}

	function save_with_reorder( $contacts, $with = 0 ) {
		if ( $with ) {
			$loop = 1;
			foreach ( $contacts as $key => $value ) {
				$contacts[ $key ]['order'] = $loop;
				$loop++;
			}
		}
		return $this->save_data( $this->table, $this->sanitize_value_data( $contacts ) );
	}

	function delete( $id = null ) {
		$contacts = parent::get_all( $this->table );
		if ( $contacts ) {
			if ( count( $contacts ) > 0 ) {
				unset( $contacts[ $id ] );
				return $this->save_with_reorder( $contacts, 1 );
			}
		}
		return false;
	}

	/*
	function settings_sanitize( $settings ) {
		if ( isset( $settings['contacts'] ) ) {
			if ( count( $settings['contacts'] ) ) {
				foreach ( $settings['contacts'] as $id => $c ) {
					$settings['contacts'][ $id ]['id']        = $id;
					$settings['contacts'][ $id ]['auto_open'] = $settings['contacts'][ $id ]['auto_open'];
					$settings['contacts'][ $id ]['chat']      = (bool) $settings['contacts'][ $id ]['chat'];
					$settings['contacts'][ $id ]['avatar']    = sanitize_text_field( $settings['contacts'][ $id ]['avatar'] );
					$settings['contacts'][ $id ]['phone']     = sanitize_text_field( $settings['contacts'][ $id ]['phone'] );
					$settings['contacts'][ $id ]['firstname'] = sanitize_text_field( $settings['contacts'][ $id ]['firstname'] );
					$settings['contacts'][ $id ]['lastname']  = sanitize_text_field( $settings['contacts'][ $id ]['lastname'] );
					$settings['contacts'][ $id ]['label']     = sanitize_text_field( $settings['contacts'][ $id ]['label'] );
					$settings['contacts'][ $id ]['message']   = wp_kses_post( $settings['contacts'][ $id ]['message'] );
					$settings['contacts'][ $id ]['timeto']    = wp_kses_post( $settings['contacts'][ $id ]['timeto'] );
					$settings['contacts'][ $id ]['phone']     = qlwapp_format_phone( $settings['contacts'][ $id ]['phone'] );
					$settings['contacts'][ $id ]['timefrom']  = $settings['contacts'][ $id ]['timefrom'];
					$settings['contacts'][ $id ]['timedays']  = $settings['contacts'][ $id ]['timedays'];
				}
			}
		}
		return $settings;
	} */

	function sanitize_value_data( $contacts, $args = null ) {
		foreach ( $contacts as $key => $contact ) {
			$contacts[ $key ] = parent::sanitize_value_data( $contact, $this->get_args() );
		}
		return $contacts;
	}

	function get_contact( $id ) {
		// $parent_id = @max(array_keys(array_column($contacts, 'id'), $id));
		$contacts = $this->get_contacts();
		return array_replace_recursive( $this->get_args(), $contacts[ $id ] );
	}

	function get_contacts() {

		$button_model = new QLWAPP_Button();
		$button       = $button_model->get();
		$result       = parent::get_all( $this->table );
		if ( $result === null || count( $result ) === 0 ) {
			$default          = array();
			$default[0]       = $this->get_args();
			$default[0]['id'] = 0;
			$result           = $default;
		} else {
			foreach ( $result as $id => $c ) {
				$result[ $id ] = wp_parse_args( $c, $this->get_args() );
			}
		}

		foreach ( $result as $id => $contact ) {
			// Include the button phone number if the contact number is empty
			if ( ! $contact['phone'] ) {
				$result[ $id ]['phone'] = $button['phone'];
			}
			// Sanitize the contact phone number
			$result[ $id ]['phone'] = qlwapp_format_phone( $result[ $id ]['phone'] );
			// Apply vars replacement
			if ( ! is_admin() ) {
				$result[ $id ]['message'] = qlwapp_replacements_vars( $contact['message'] );
			}
		}

		return $result;
	}

	function order_contact( $a, $b ) {

		if ( ! isset( $a['order'] ) || ! isset( $b['order'] ) ) {
			return 0;
		}

		if ( $a['order'] == $b['order'] ) {
			return 0;
		}

		return ( $a['order'] < $b['order'] ) ? -1 : 1;
	}

	function get_contacts_reorder() {
		$contacts = $this->get_contacts();
		uasort( $contacts, array( $this, 'order_contact' ) );
		return $contacts;
	}

}
