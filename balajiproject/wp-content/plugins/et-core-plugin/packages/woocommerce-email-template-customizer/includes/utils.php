<?php

namespace VIWEC\INC;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	protected static $instance = null;

	public static $email_ids;

	private function __construct() {
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function enqueue_admin_script_libs( $enqueue_list = [], $depend = [] ) {
		self::enqueue_admin_scripts( $enqueue_list, $depend, true );
	}

	public static function enqueue_admin_scripts( $enqueue_list = [], $depend = [], $libs = false ) {
		if ( is_array( $enqueue_list ) && ! empty( $enqueue_list ) ) {
			$path   = $libs ? VIWEC_JS . 'libs/' : VIWEC_JS;
			$suffix = $libs ? '.min' : '';
			foreach ( $enqueue_list as $script ) {
				wp_enqueue_script( VIWEC_SLUG . '-' . $script, $path . $script . $suffix . '.js', $depend, VIWEC_VER );
			}
		}
	}

	public static function enqueue_admin_styles_libs( $enqueue_list = [] ) {
		self::enqueue_admin_styles( $enqueue_list, true );
	}

	public static function enqueue_admin_styles( $enqueue_list = [], $libs = false ) {
		if ( is_array( $enqueue_list ) && count( $enqueue_list ) ) {
			$path   = $libs ? VIWEC_CSS . 'libs/' : VIWEC_CSS;
			$suffix = $libs ? '.min' : '';
			foreach ( $enqueue_list as $style ) {
				wp_enqueue_style( VIWEC_SLUG . '-' . $style, $path . $style . $suffix . '.css', [], VIWEC_VER );
			}
		}
	}


	public static function build_tree( $categories, $level = 0 ) {
		$cat_list = [];
		foreach ( $categories as $cat ) {
			$prefix         = str_repeat( '- ', $level );
			$cat_list[]     = [ 'id' => $cat->cat_ID, 'name' => $prefix . $cat->cat_name ];
			$sub_categories = get_term_children( $cat->cat_ID, 'product_cat' );

			if ( count( $sub_categories ) ) {
				$args       = array(
					'taxonomy'     => 'product_cat',
					'orderby'      => 'name',
					'hierarchical' => true,
					'hide_empty'   => false,
					'parent'       => $cat->cat_ID
				);
				$categories = get_categories( $args );
				$cat_list   = array_merge( $cat_list, self::build_tree( $categories, $level + 1 ) );
			}
		}

		return $cat_list;
	}

	public static function get_all_categories() {
		$args       = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'hierarchical' => true,
			'hide_empty'   => false,
			'parent'       => 0
		);
		$categories = get_categories( $args );

		return self:: build_tree( $categories );
	}

	public static function get_bought_ids( $line_items ) {
		$bought = [];
		foreach ( $line_items as $item ) {
			$item_data = $item->get_data();
			$p_id      = $item_data['product_id'];
			$bought[]  = $p_id;
		}

		return $bought;
	}

	public static function get_categories_from_bought_id( $bought_ids ) {
		$cat_id_filter = [];
		foreach ( $bought_ids as $id ) {
			$cats          = wc_get_product_cat_ids( $id );
			$cat_id_filter = array_merge( $cat_id_filter, $cats );
		}

		return array_unique( $cat_id_filter );
	}

	public static function get_email_ids() {
		if ( ! self::$email_ids ) {
			$emails    = wc()->mailer()->get_emails();
			$email_ids = [];
			if ( is_array( $emails ) && ! empty( $emails ) ) {
				$accept_emails = [];
				if ( class_exists( 'WC_Correios' ) ) {
					$accept_emails[] = 'correios_tracking';
				}
				
				// compatibility with WooCommerce Germanized Emails plugins
				if ( class_exists('Vendidero\Germanized\Shipments\Emails') ) {
					$accept_emails[] = 'customer_guest_return_shipment_request';
					$accept_emails[] = 'customer_return_shipment_delivered';
					$accept_emails[] = 'customer_return_shipment';
					$accept_emails[] = 'customer_shipment';
					$accept_emails[] = 'new_return_shipment_request';
				}
				
				// compatibility with WooCommerce Subscriptions plugin
				if ( class_exists('WC_Subscriptions_Email')) {
					$accept_emails[] = 'new_renewal_order';
					$accept_emails[] = 'cancelled_subscription';
					$accept_emails[] = 'customer_completed_renewal_order';
					$accept_emails[] = 'customer_completed_switch_order';
					$accept_emails[] = 'customer_on_hold_renewal_order';
					$accept_emails[] = 'customer_payment_retry';
					$accept_emails[] = 'customer_renewal_invoice';
					$accept_emails[] = 'expired_subscription';
					$accept_emails[] = 'new_renewal_order';
					$accept_emails[] = 'new_switch_order';
					$accept_emails[] = 'suspended_subscription';
					$accept_emails[] = 'payment_retry';
					$accept_emails[] = 'customer_processing_renewal_order';
				}

				$accept_emails = apply_filters( 'viwec_accept_email_type', $accept_emails );
				foreach ( $emails as $email ) {
					if ( strpos( $email->template_base, 'woocommerce/templates' ) !== false || in_array( $email->id, $accept_emails ) ) {
						$email_ids[ $email->id ] = $email->title;
					}
				}

				$email_ids['customer_partially_refunded_order'] = $email_ids['customer_refunded_order'] . ' (' . esc_html__( 'partial', 'xstore-core' ) . ')';
				$email_ids['customer_refunded_order']           = $email_ids['customer_refunded_order'] . ' (' . esc_html__( 'full', 'xstore-core' ) . ')';
				$email_ids['customer_invoice_pending']          = $email_ids['customer_invoice'] . ' (' . esc_html__( 'pending', 'xstore-core' ) . ')';
				$email_ids['customer_invoice']                  = $email_ids['customer_invoice'] . ' (' . esc_html__( 'paid', 'xstore-core' ) . ')';
			}

			asort( $email_ids );

			$email_ids            = array_reverse( $email_ids, true );
			$email_ids['default'] = esc_html__( 'Default template', 'xstore-core' );
			$email_ids            = array_reverse( $email_ids, true );

			self::$email_ids = $email_ids;
		}

		return array_merge( self::$email_ids, self::register_email_type() );
	}

	public static function shortcodes() {
		$date_format = wc_date_format();

		return [
			'{admin_email}'          => get_bloginfo( 'admin_email' ),
			'{checkout_url}'         => wc_get_checkout_url(),
			'{customer_name}'        => esc_html__( 'John Doe', 'xstore-core' ),
			'{customer_note}'        => esc_html__( 'Customer note', 'xstore-core' ),
			'{coupon_expire_date}'   => date_i18n( $date_format, current_time( 'U' ) + MONTH_IN_SECONDS ),
			'{first_name}'           => esc_html__( 'John', 'xstore-core' ),
			'{home_url}'             => home_url(),
			'{last_name}'            => esc_html__( 'Doe', 'xstore-core' ),
			'{myaccount_url}'        => wc_get_page_permalink( 'myaccount' ),
			'{order_date}'           => date_i18n( $date_format, current_time( 'U' ) ),
			'{order_discount}'       => wc_price( 5 ),
			'{order_fully_refund}'   => wc_price( 0 ),
			'{order_note}'           => esc_html__( 'Order note', 'xstore-core' ),
			'{order_number}'         => 2158,
			'{order_partial_refund}' => wc_price( 0 ),
			'{order_received_url}'   => wc_get_endpoint_url( 'order-received', 2158, wc_get_checkout_url() ),
			'{order_shipping}'       => wc_price( 10 ),
			'{order_subtotal}'       => wc_price( 50 ),
			'{order_total}'          => wc_price( 55 ),
			'{order_tax}'            => wc_price( 5 ),
			'{payment_method}'       => esc_html__( 'Paypal', 'xstore-core' ),
			'{payment_url}'          => wc_get_endpoint_url( 'order-pay', 2158, wc_get_checkout_url() ) . '?pay_for_order=true&key=wc_order_6D6P8tQ0N',
			'{reset_password_url}'   => wc_get_endpoint_url( 'lost-password/?key=N52psnY51Inm0yE3OdxL', '', wc_get_page_permalink( 'myaccount' ) ),
			'{site_title}'           => get_bloginfo( 'name' ),
			'{shipping_method}'      => esc_html__( 'Flat rate', 'xstore-core' ),
			'{shop_url}'             => wc_get_endpoint_url( 'shop' ),
			'{user_login}'           => 'johndoe',
			'{user_password}'        => 'KG&Q#ToW&kLq0owvLWq4Ck',
			'{user_email}'           => 'johndoe@domain.com',
		];
	}

	public static function register_email_type() {
		$r                   = [];
		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['name'] ) ) {
					continue;
				}
				$r[ $id ] = $data['name'];
			}
		}

		return $r;
	}

	public static function get_hide_elements_data() {
		$pattern = [
			'html/order_detail',
			'html/order_subtotal',
			'html/order_total',
			'html/shipping_method',
			'html/payment_method',
			'html/order_note',
			'html/billing_address',
			'html/shipping_address',
			'html/wc_hook',
		];

		$r = [ 'default' => $pattern, ];

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['hide_elements'] ) ) {
					continue;
				}
				$r[ $id ] = $data['hide_elements'];
			}
		}

		return $r;
	}

	public static function get_hide_rules_data() {
		$r = [
			'default'                 => [ 'min_order', 'max_order', 'category', 'country' ],
			'customer_new_account'    => [ 'min_order', 'max_order', 'category' ],
			'customer_reset_password' => [ 'min_order', 'max_order', 'category' ]
		];

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['hide_rules'] ) ) {
					continue;
				}
				$r[ $id ] = $data['hide_rules'];
			}
		}

		return $r;
	}

	public static function register_3rd_email_type() {
		return apply_filters( 'viwec_register_email_type', [] );
	}

	public static function register_shortcode_for_builder() {
		return apply_filters( 'viwec_live_edit_shortcodes', [] );
	}

	public static function get_register_shortcode_for_builder() {
		$result = [];
		$scs    = self::register_shortcode_for_builder();

		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				if ( ! is_array( $sc ) ) {
					continue;
				}
				$result = array_merge( $result, array_keys( $sc ) );
			}
		}

		return $result;
	}


	public static function get_register_shortcode_for_text_editor() {
		$result = [];

		$email_types = self::register_email_type();
		$scs         = self::register_shortcode_for_builder();

		if ( ! empty( $email_types ) && is_array( $email_types ) ) {
			foreach ( $email_types as $key => $name ) {
				$sc = ! empty( $scs[ $key ] ) ? $scs[ $key ] : '';
				if ( ! $sc || ! is_array( $sc ) ) {
					continue;
				}
				$menu = [];
				foreach ( $sc as $text => $value ) {
					if ( ! $text ) {
						continue;
					}
					$menu[] = [ 'text' => $text, 'value' => $text ];
				}
				$result[ $key ] = [ 'text' => $name, 'menu' => $menu ];
			}
		}

		return $result;
	}

	public static function get_register_shortcode_for_replace() {
		$result = [];

		$scs = self::register_shortcode_for_builder();
		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				$result = array_merge( $result, $sc );
			}
		}

		return $result;
	}

	public static function admin_email_type() {
		$emails = wc()->mailer()->get_emails();
		$r      = [];
		if ( is_array( $emails ) && ! empty( $emails ) ) {
			foreach ( $emails as $email ) {
				if ( ! empty( $email->recipient ) ) {
					$r[] = $email->id;
				}
			}
		}

		return apply_filters( 'viwec_admin_email_types', $r );
	}

	public static function get_email_ids_grouped() {
		$emails = self::get_email_ids();
		$group  = [ 'admin' => [], 'customer' => [] ];
		if ( ! empty( $emails ) ) {
			foreach ( $emails as $id => $name ) {
				if ( in_array( $id, self::admin_email_type() ) ) {
					$group['admin'][ $id ] = $name;
				} else {
					$group['customer'][ $id ] = $name;
				}
			}
		}

		return $group;
	}

	public static function get_email_recipient() {
		$emails    = wc()->mailer()->get_emails();
		$recipient = wp_list_pluck( $emails, 'recipient', 'id' );

		$recipient['customer_invoice_pending']          = '';
		$recipient['customer_partially_refunded_order'] = '';

		return $recipient;
	}

	public static function get_admin_bar_stt() {
		return get_option( 'viwec_admin_bar_stt' );
	}

	public static function default_shortcode_for_replace() {
		$shop_url      = wc_get_page_permalink( 'shop' );
		$myaccount_url = wc_get_page_permalink( 'myaccount' );
		$checkout_url  = wc_get_checkout_url();
		$reset_password_url = wc_get_endpoint_url( 'lost-password/?key=N52psnY51Inm0yE3OdxL', '', wc_get_page_permalink( 'myaccount' ) );

		return [
			'{admin_email}'   => get_option( 'admin_email' ),
			'{site_title}'    => get_bloginfo( 'name' ),
			'{site_url}'      => site_url(),
			'{home_url}'      => home_url(),
			'{shop_url}'      => $shop_url ? $shop_url : home_url(),
			'{myaccount_url}' => $myaccount_url ? $myaccount_url : home_url(),
			'{checkout_url}'  => $checkout_url ? $checkout_url : home_url(),

			'{order_number}'         => '',
			'{customer_name}'        => '',
			'{first_name}'           => '',
			'{last_name}'            => '',
			'{order_date}'           => '',
			'{order_subtotal}'       => '',
			'{order_total}'          => '',
			'{payment_method}'       => '',
			'{shipping_method}'      => '',
			'{order_note}'           => '',
			'{customer_note}'        => '',
			'{payment_url}'          => '',
			'{order_discount}'       => '',
			'{order_shipping}'       => '',
			'{order_received_url}'   => '',
			'{order_fully_refund}'   => '',
			'{order_partial_refund}' => '',
			'{order_tax}'            => '',

			'{user_login}'         => '',
			'{user_password}'      => '',
			'{user_email}'         => '',
			'{reset_password_url}' => $reset_password_url ? $reset_password_url : wp_lostpassword_url(),
			'{coupon_expire_date}' => '',
		];
	}

	public static function replace_shortcode( $html, $args, $object = '', $preview = false ) {

		$shortcodes = self::default_shortcode_for_replace();

		if ( $object ) {
			if ( is_a( $object, 'WC_Order' ) ) {

				$date_fm     = get_option( 'date_format' );
				$refunds     = $object ? $object->get_refunds() : '';
				$refund_html = '';

				if ( $refunds ) {
					foreach ( $refunds as $id => $refund ) {
						$refund_html .= '<div>' . wc_price( '-' . $refund->get_amount(), array( 'currency' => $object->get_currency() ) ) . '</div>';
					}
				}

				$payment_method = $object && $object->get_total() > 0 && $object->get_payment_method_title() && 'other' !== $object->get_payment_method_title() ? $object->get_payment_method_title() : '';

				$shortcodes['{order_number}']         = $object->get_order_number();
				$shortcodes['{customer_name}']        = $object->get_formatted_billing_full_name();
				$shortcodes['{first_name}']           = $object->get_billing_first_name();
				$shortcodes['{last_name}']            = $object->get_billing_last_name();
				$shortcodes['{order_date}']           = date_i18n( $date_fm, strtotime( $object->get_date_created() ) );
				$shortcodes['{order_subtotal}']       = $object->get_subtotal_to_display();
				$shortcodes['{order_total}']          = $object->get_formatted_order_total();
				$shortcodes['{payment_method}']       = $payment_method;
				$shortcodes['{shipping_method}']      = $object->get_shipping_method();
				$shortcodes['{customer_note}']        = $object->get_customer_note();
				$shortcodes['{payment_url}']          = $object->get_checkout_payment_url();
				$shortcodes['{order_discount}']       = $object->get_discount_to_display();
				$shortcodes['{order_shipping}']       = $object->get_shipping_to_display();
				$shortcodes['{order_received_url}']   = $object->get_checkout_order_received_url();
				$shortcodes['{order_fully_refund}']   = $refund_html;
				$shortcodes['{order_partial_refund}'] = $refund_html;

				$tax = '';
				if ( 'excl' === get_option( 'woocommerce_tax_display_cart' ) && wc_tax_enabled() ) {
					if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
						$taxes = [];
						foreach ( $object->get_tax_totals() as $code => $tax ) {
							$taxes[] = $tax->label . ' : ' . $tax->formatted_amount;
						}
						$tax = implode( ',', $taxes );
					} else {
						$tax = wc_price( $object->get_total_tax(), array( 'currency' => $object->get_currency() ) );
					}
				}
				$shortcodes['{order_tax}'] = $tax;
			}

			if ( property_exists( $object, 'object' ) && is_a( $object->object, 'WP_User' ) ) {
				$shortcodes['{user_login}']         = $object->user_login ?? '';
				$shortcodes['{user_password}']      = $object->user_pass ?? '';
				$shortcodes['{user_email}']         = $object->user_email ?? '';
				$shortcodes['{reset_password_url}'] = add_query_arg( [ 'key' => $object->reset_key ?? '', 'id' => $object->user_id ?? '' ],
					wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) );
			}

			if ( is_a( $object, 'WP_User' ) ) {
				$shortcodes['{user_login}']         = $object->user_login ?? '';
				$shortcodes['{customer_name}']      = $object->register_data['user_name'] ?? '';
				$shortcodes['{first_name}']         = $object->register_data['first_name'] ?? '';
				$shortcodes['{last_name}']          = $object->register_data['last_name'] ?? '';
				$shortcodes['{user_password}']      = $object->register_data['password'] ?? '';
				$shortcodes['{user_email}']         = $object->user_email ?? '';
				$shortcodes['{reset_password_url}'] = $object->register_data['password'] ?? '';
			}
		}

		$shortcodes['{order_note}']         = $args['customer_note'] ?? '';
		$shortcodes['{coupon_expire_date}'] = $args['coupon_expire_date'] ?? '';

		if ( $preview ) {
			$shortcodes['{user_login}']    = 'johndoe';
			$shortcodes['{user_password}'] = 'KG&Q#ToW&kLq0owvLWq4Ck';
			$shortcodes['{user_email}']    = 'johndoe@domain.com';
		}

		$custom_shortcode = $preview ? apply_filters( 'viwec_register_preview_shortcode', [], $object ) : apply_filters( 'viwec_register_replace_shortcode', [], $object, $args );

		if ( ! empty( $custom_shortcode ) && is_array( $custom_shortcode ) ) {
			foreach ( $custom_shortcode as $sc ) {
				$shortcodes = array_merge( $sc, $shortcodes );
			}
		}

		return str_replace( array_keys( $shortcodes ), array_values( $shortcodes ), $html );
	}
}

