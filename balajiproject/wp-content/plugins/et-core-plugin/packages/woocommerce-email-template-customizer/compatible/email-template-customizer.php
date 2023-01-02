<?php

use VIWEC\INC\Email_Render;
use VIWEC\INC\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'VIWEC_Render_Email_Template' ) ) {

	class VIWEC_Render_Email_Template {
		protected $temp_id;
		protected $order;

		public function __construct( $args ) {
			if ( ! empty( $args['template_id'] ) ) {
				$this->temp_id = $args['template_id'];
			}

			if ( ! empty( $args['order'] ) ) {
				$this->set_order( $args['order'] );
			}
		}

		public function set_order( $order ) {
			if ( is_a( $order, 'WC_Order' ) ) {
				$this->order = $order;
			} else {
				$order_id = (int) $order;
				if ( $order_id ) {
					$this->order = wc_get_order( $order_id );
				}
			}
		}

		public function get_content() {
			if ( ! $this->temp_id ) {
				return;
			}
			$email_template        = Email_Render::init();
			$email_template->order = $this->order;
			$data                  = get_post_meta( $this->temp_id, 'viwec_email_structure', true );
			$data                  = json_decode( html_entity_decode( $data ), true );
			$email_template->render( $data );
		}

		public function get_subject() {
			$subject = '';
			$post    = get_post( $this->temp_id );
			if ( $post ) {
				$subject = $post->post_title;
				$subject = Utils::replace_shortcode( $subject, '', $this->order );
			}

			return $subject;
		}
	}
}

