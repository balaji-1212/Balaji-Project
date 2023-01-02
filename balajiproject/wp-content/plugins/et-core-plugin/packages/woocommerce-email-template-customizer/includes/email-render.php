<?php

namespace VIWEC\INC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Render {

	protected static $instance = null;
	public $preview;
	public $demo;
	public $sent_to_admin;
	public $render_data = [];
	public $plain_text;
	public $template_args;
	public $order;
	public $other_message_content;
	public $class_email;
	protected $order_currency;
	protected $user;
	protected $email_id;
	protected $font_family_default = "Helvetica Neue, Helvetica, Roboto, Arial, sans-serif";

	public function __construct() {
		add_action( 'viwec_render_content', [ $this, 'render_content' ], 10, 2 );
		add_filter( 'gettext', [ $this, 'recover_text' ], 10, 3 );
		add_action( 'viwec_order_item_parts', [ $this, 'order_download' ], 10, 3 );
		add_filter( 'woocommerce_order_shipping_to_display_shipped_via', [ $this, 'remove_shipping_method' ] );
		add_filter( 'woocommerce_mail_content', [ $this, 'remove_filter_wc_style' ] );
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function recover_text( $translation, $text, $domain ) {
		if ( $text == 'Order fully refunded.' ) {
			$translation = $text;
		}

		return $translation;
	}

	public function set_object( $email ) {
		$this->email_id = $email->id;
		$object         = $email->object;
		if ( is_a( $object, 'WC_Order' ) ) {
			$this->order          = $object;
			$this->order_currency = $this->order->get_currency();
		} elseif ( is_a( $object, 'WP_User' ) ) {
			$this->user = $email;
		}
	}

	public function set_user( $user ) {
		$this->user = $user;
	}

	public function order( $order_id ) {
		if ( $order_id ) {
			$this->order = wc_get_order( $order_id );
			if ( $this->order ) {
				$this->order_currency = $this->order->get_currency();
			}
		}
	}

	public function demo_order() {
		$this->demo = true;

		$order = new \WC_Order();
		$order->set_id( 123456 );
		$order->set_billing_first_name( 'John' );
		$order->set_billing_last_name( 'Doe' );
		$order->set_billing_email( 'johndoe@domain.com' );
		$order->set_billing_country( 'US' );
		$order->set_billing_city( 'Azusa' );
		$order->set_billing_state( 'NY' );
		$order->set_payment_method( 'paypal' );
		$order->set_payment_method_title( 'Paypal' );
		$order->set_billing_postcode( 10001 );
		$order->set_billing_phone( '0123456789' );
		$order->set_billing_address_1( 'Ap #867-859 Sit Rd.' );
		$order->set_shipping_total( 10 );
		$order->set_total( 60 );
		$this->order = $order;
	}

	public function demo_new_user() {
		$user             = new \WP_User();
		$user->user_login = 'johndoe';
		$user->user_pass  = '$P$BKpFUPNogZw6kAv/dMrk6CjSmlFI8l0';
		$this->user       = $user;
	}

	public function parse_styles( $data ) {
		if ( empty( $data ) ) {
			return '';
		}

		$style = '';
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( $key === 'border-style' && isset( $data['border-width'] ) && $data['border-width'] == '0px' ) {
					continue;
				}
				$style .= "{$key}:{$value};";
			}

			$border_width = isset( $data['border-width'] ) && $data['border-width'] !== '0px' ? true : false;
			$border_style = isset( $data['border-style'] ) ? true : false;

			$style .= $border_width && ! $border_style ? 'border-style:solid;' : '';
		} else {
			$style = $data;
		}

		return $style;
	}

	public function replace_template( $located, $template_name ) {
		if ( $template_name == 'emails/email-styles.php' ) {
			$located = VIWEC_TEMPLATES . 'email-style.php';
		}

		return $located;
	}

	public function remove_filter_wc_style( $arg ) {
		remove_filter( 'woocommerce_email_styles', [ $this, 'remove_wc_style' ], 9999 );

		return $arg;
	}

	public function remove_wc_style() {
		return '';
	}

	public function render( $data ) {
		add_filter( 'woocommerce_email_styles', [ $this, 'remove_wc_style' ], 9999 );

		$bg_style = isset( $data['style_container'] ) ? $this->parse_styles( $data['style_container'] ) : '';

		$this->email_header( $bg_style );
		?>
        <table align='center' width='600' border='0' cellpadding='0' cellspacing='0'>
			<?php
			if ( ! empty( $data['rows'] ) && is_array( $data['rows'] ) ) {
				foreach ( $data['rows'] as $row ) {
					if ( ! empty( $row ) && is_array( $row ) ) {
						$row_outer_style = ! empty( $row['props']['style_outer'] ) ? $this->parse_styles( $row['props']['style_outer'] ) : '';
						?>
                        <tr>
                            <td valign='top' width='100%' style='background-repeat: no-repeat;background-size: cover;background-position: top;
                            <?php echo esc_attr( $row_outer_style ) ?>'>
                                <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;margin: 0; padding:0'>
                                    <tr>
                                        <td valign='top' width='100%' class='viwec-responsive-padding viwec-inline-block' border='0' cellpadding='0' cellspacing='0'
                                            style='width: 100%; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif;font-size: 0 !important;border-collapse: collapse;margin: 0; padding:0; '>

											<?php
											$end_array = array_keys( $row );
											$end_array = end( $end_array );

											if ( ! empty( $row['cols'] && is_array( $row['cols'] ) ) ) {
												$arr_key    = array_keys( $row['cols'] );
												$start      = current( $arr_key );
												$end        = end( $arr_key );
												$col_number = count( $row['cols'] );
//												$width      = round( 100 / $col_number ) - 0.1 . '%';

												$width = ( 100 / $col_number ) . '%';

												foreach ( $row['cols'] as $key => $col ) {
													$col_style = ! empty( $col['props']['style'] ) ? $this->parse_styles( $col['props']['style'] ) : '';

													if ( $start == $key ) { ?>
                                                        <!--[if mso | IE]>
                                                        <table width="100%" role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td valign='top' class="" style="vertical-align:top;width:<?php echo esc_attr( $width ) ?>;"><![endif]-->
													<?php } ?>

                                                    <table align="left" width="<?php echo esc_attr( $width ) ?>" class='viwec-responsive' border="0" cellpadding="0" cellspacing="0"
                                                           style='margin:0; padding:0;border-collapse: collapse;'>
                                                        <tr>
                                                            <td>
                                                                <table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'
                                                                       style='margin:0; padding:0;border-collapse: collapse;width: 100%'>
                                                                    <tr>
                                                                        <td valign='top' width='100%' style='line-height: 1.5;<?php echo esc_attr( $col_style ) ?>'>
																			<?php
																			if ( ! empty( $col['elements'] && is_array( $col['elements'] ) ) ) {
																				foreach ( $col['elements'] as $el ) {
																					$type          = isset( $el['type'] ) ? str_replace( '/', '_', $el['type'] ) : '';
																					$content_style = isset( $el['style'] ) ? $this->parse_styles( str_replace( "'", '', $el['style'] ) ) : '';
																					$el_style      = ! empty( $el['props']['style'] ) ? $this->parse_styles( str_replace( "'", '', $el['props']['style'] ) ) : '';
																					?>
                                                                                    <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'
                                                                                           style='border-collapse: separate;'>
                                                                                        <tr>
                                                                                            <td valign='top' style='<?php echo esc_attr( $el_style ); ?>'>
                                                                                                <table check='' align='center' width='100%' border='0' cellpadding='0'
                                                                                                       cellspacing='0'
                                                                                                       style='border-collapse: separate;'>
                                                                                                    <tr>
                                                                                                        <td valign='top'
                                                                                                            style='font-size: 15px;<?php echo esc_attr( $content_style ) ?>'>
																											<?php
																											do_action( 'viwec_render_content', $type, $el, $this );
																											?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
																					<?php
																				}
																			}
																			?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
													<?php
													if ( $end == $key ) {
														?>
                                                        <!--[if mso | IE]></td></tr></table><![endif]-->
													<?php } else {
														?>
                                                        <!--[if mso | IE]></td>
                                                        <td valign='top' style="vertical-align:top;width:<?php echo esc_attr( $width ) ?>;"><![endif]-->
														<?php
													}
												}
											} ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
					<?php }
				}
			} ?>
        </table>
		<?php
		$this->email_footer();
	}

	public function email_header( $bg_style ) {
		wc_get_template( 'email-header.php', [ 'bg_style' => $bg_style ], '', VIWEC_TEMPLATES );
	}

	public function email_footer() {
		?>
        </td></tr></tbody></table></div></body></html>
		<?php
	}

	public function render_content( $type, $props ) {
		$func = 'render_' . $type;
		if ( method_exists( $this, $func ) ) {
			$this->$func( $props );
		}
	}

	public function replace_shortcode( $text ) {
		$object = '';

		if ( $this->order ) {
			$object = $this->order;
		} elseif ( $this->user ) {
			$object = $this->user;
		}

		$text = Utils::replace_shortcode( $text, $this->template_args, $object, $this->preview );

		return $text;
	}

	public function render_html_image( $props ) {
		$src      = isset( $props['attrs']['src'] ) ? $props['attrs']['src'] : '';
		$width    = isset( $props['childStyle']['img'] ) ? $this->parse_styles( $props['childStyle']['img'] ) : '';
		$ol_width = ! empty( $props['childStyle']['img']['width'] ) ? str_replace( 'px', '', $props['childStyle']['img']['width'] ) : '100%';
		?>
        <img width="<?php echo esc_attr( $ol_width ) ?>" src='<?php echo esc_url( $src ) ?>' max-width='100%'
             style='max-width: 100%;vertical-align: middle;<?php echo esc_attr( $width ) ?>'/>
		<?php
	}

	public function render_html_text( $props ) {
		$content = isset( $props['content']['text'] ) ? $props['content']['text'] : '';
		$content = $this->replace_shortcode( $content );
		echo wp_kses( $content, viwec_allowed_html() );
	}

	public function render_html_order_detail( $props ) {
		if ( $this->order ) {
			$temp    = ! empty( $props['attrs']['data-template'] ) ? $props['attrs']['data-template'] : 1;
			$preview = $this->demo ? 'pre-' : '';

			if ( is_file( VIWEC_TEMPLATES . "order-items/{$preview}style-{$temp}.php" ) ) {
				?>
                <table width='100%' border='0' cellpadding='0' cellspacing='0' align='center'>
                    <tr>
                        <td valign='top'>
							<?php
							$sent_to_admin = $this->template_args['sent_to_admin'] ?? '';
							wc_get_template( "order-items/{$preview}style-{$temp}.php", [
								'order'               => $this->order,
								'items'               => $this->order->get_items(),
								'show_sku'            => $sent_to_admin,
								'show_download_links' => $this->order->is_download_permitted() && ! $sent_to_admin,
								'show_purchase_note'  => $this->order->is_paid() && ! $sent_to_admin,
								'props'               => $props,
								'render'              => $this
							], '', VIWEC_TEMPLATES );
							?>
                        </td>
                    </tr>
                </table>
				<?php
			}
		}
	}

	public function render_html_order_subtotal( $props ) {
		$html = '';
		if ( $this->order ) {

			$left_style  = isset( $props['childStyle']['.viwec-td-left'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-left'] ) : 'text-align:left;';
			$right_style = isset( $props['childStyle']['.viwec-td-right'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-right'] ) : 'text-align:right; width:40%;';
			$el_style    = isset( $props['childStyle']['.viwec-order-subtotal-style'] ) ? $this->parse_styles( $props['childStyle']['.viwec-order-subtotal-style'] ) : '';

			$item_totals = $this->order->get_order_item_totals();
			if ( ! empty( $item_totals ) && is_array( $item_totals ) ) {
				foreach ( $item_totals as $id => $item ) {
					switch ( $id ) {
						case 'cart_subtotal':
							$id = 'subtotal';
							break;
						default:
							break;
					}

					$label = str_replace( ':', '', $item['label'] );

					if ( $label == 'Order fully refunded' && ! empty( $props['content']['refund-full'] ) ) {
						$label = $props['content']['refund-full'];
					}

					if ( $label == 'Refund' && ! empty( $props['content']['refund-part'] ) ) {
						$label = $props['content']['refund-part'];
					}

					if ( in_array( $id, [ 'order_total', 'payment_method' ] ) ) {
						continue;
					}

					$text = $props['content'][ $id ] ?? $label;
					$html .= "<tr>";
					$html .= "<td valign='top'  class='viwec-mobile-50' style='{$el_style}{$left_style}'>{$text}</td>";
					$html .= "<td valign='top'  class='viwec-mobile-50' style='{$el_style}{$right_style}'>{$item['value']}</td>";
					$html .= "</tr>";
				}
			}
		}

		$this->table( $html );
	}

	public function render_html_order_total( $props ) {
		$html = '';
		if ( $this->order ) {
			$left_style  = isset( $props['childStyle']['.viwec-td-left'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-left'] ) : 'text-align:left;';
			$right_style = isset( $props['childStyle']['.viwec-td-right'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-right'] ) : 'text-align:right; width:40%;';
			$el_style    = isset( $props['childStyle']['.viwec-order-total-style'] ) ? $this->parse_styles( $props['childStyle']['.viwec-order-total-style'] ) : '';

			$trans_total = $props['content']['order_total'] ?? esc_html__( 'Total', 'xstore-core' );
			$total_html  = "<tr><td valign='top' class='viwec-mobile-50' style='{$el_style}{$left_style}'>{$trans_total}</td>";
			$total_html  .= "<td valign='top' class='viwec-mobile-50' style='{$el_style}{$right_style}'>{$this->order->get_formatted_order_total()}</td></tr>";

			$html .= $total_html;
		}
		$this->table( $html );
	}

	public function render_html_order_note( $props ) {
		if ( $this->order && $this->order->get_customer_note() ) {
			$html        = '';
			$left_style  = isset( $props['childStyle']['.viwec-td-left'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-left'] ) : 'text-align:left;';
			$right_style = isset( $props['childStyle']['.viwec-td-right'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-right'] ) : 'text-align:right; width:40%;';
			$el_style    = isset( $props['childStyle']['.viwec-order-total-style'] ) ? $this->parse_styles( $props['childStyle']['.viwec-order-total-style'] ) : '';

			$trans_note = $props['content']['order_note'] ?? 'Note';
			$note_html  = "<tr><td valign='top' class='viwec-mobile-50' style='{$el_style}{$left_style}'>{$trans_note}</td>";
			$note_html  .= "<td valign='top' class='viwec-mobile-50' style='{$el_style}{$right_style}'>{$this->order->get_customer_note()}</td></tr>";

			$html .= $note_html;
			$this->table( $html );
		}
	}

	public function render_html_shipping_method( $props ) {
		if ( $this->order ) {
			if ( $shipping_method = $this->order->get_shipping_method() ?? '' ) {
				$shipping_method_html = '';
				$left_style           = isset( $props['childStyle']['.viwec-td-left'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-left'] ) : 'text-align:left;';
				$right_style          = isset( $props['childStyle']['.viwec-td-right'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-right'] ) : 'text-align:right; width:40%;';
				$el_style             = isset( $props['childStyle']['.viwec-shipping-method-style'] ) ? $this->parse_styles( $props['childStyle']['.viwec-shipping-method-style'] ) : '';

				$trans_shipping_method = $props['content']['shipping_method'] ?? esc_html__( 'Shipping method', 'xstore-core' );
				$shipping_method_html  .= "<tr><td  valign='top' class='viwec-mobile-50' style='{$el_style}{$left_style}'>{$trans_shipping_method}</td>";
				$shipping_method_html  .= "<td  valign='top' class='viwec-mobile-50' style='{$el_style}{$right_style}'>{$shipping_method}</td></tr>";
				$this->table( $shipping_method_html );
			}
		}
	}

	public function render_html_payment_method( $props ) {
		$html = '';
		if ( $this->order ) {
			$payment_method_html = '';
			$left_style          = isset( $props['childStyle']['.viwec-td-left'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-left'] ) : 'text-align:left;';
			$right_style         = isset( $props['childStyle']['.viwec-td-right'] ) ? $this->parse_styles( $props['childStyle']['.viwec-td-right'] ) : 'text-align:right; width:40%;';
			$el_style            = isset( $props['childStyle']['.viwec-payment-method-style'] ) ? $this->parse_styles( $props['childStyle']['.viwec-payment-method-style'] ) : '';

			$payment_method = $this->order->get_total() > 0 && $this->order->get_payment_method_title() && 'other' !== $this->order->get_payment_method_title() ? $this->order->get_payment_method_title() : '';

			$trans_payment_method = $props['content']['payment_method'] ?? esc_html__( 'Payment method', 'xstore-core' );
			if ( $payment_method ) {
				$payment_method_html = "<tr><td  valign='top' class='viwec-mobile-50' style='{$el_style}{$left_style}'>{$trans_payment_method}</td>";
				$payment_method_html .= "<td  valign='top' class='viwec-mobile-50' style='{$el_style}{$right_style}'>{$payment_method}</td></tr>";
			}
			$html .= $payment_method_html;
		}
		
		$this->table( $html );
	}

	public function render_html_billing_address( $props ) {
		if ( ! $this->order ) {
			return;
		}

		if ( $this->preview ) {
			$this->render_html_billing_address_via_hook( '', '', '', '', $props );
		} else {
			add_action( 'woocommerce_email_customer_details', [ $this, 'render_html_billing_address_via_hook' ], 20, 5 );
			$args = $this->template_args;
			do_action( 'woocommerce_email_customer_details', $args['order'], $args['sent_to_admin'], $args['plain_text'], $args['email'], $props );
			remove_action( 'woocommerce_email_customer_details', [ $this, 'render_html_billing_address_via_hook' ], 20 );
		}

	}

	public function render_html_billing_address_via_hook( $order, $sent_to_admin, $plain_text, $email, $props ) {
		if ( $props['type'] !== 'html/billing_address' ) {
			return;
		}
		$color       = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';

		$billing_address = $this->order->get_formatted_billing_address();
		$billing_address = str_replace( '<br/>', "</td></tr><tr><td  valign='top' style='color: {$color}; font-weight: {$font_weight};'>", $billing_address );
		$billing_email   = $billing_phone = '';
		if ( $phone = $this->order->get_billing_phone() ) {
			$billing_phone = "<tr><td valign='top' ><a href='tel:$phone' style='color: {$color}; font-weight: {$font_weight};'>$phone</td></tr>";
		}

		if ( $this->order->get_billing_email() ) {
			$billing_email = "<tr><td valign='top' ><a style='color:{$color}; font-weight: {$font_weight};' href='mailto:{$this->order->get_billing_email()}'>{$this->order->get_billing_email()}</a></td></tr>";
		}

		$html = "<tr><td  valign='top'  style='color: {$color}; font-weight: {$font_weight};'>{$billing_address}</td></tr>{$billing_phone}{$billing_email}";
		$this->table( $html );
	}

	public function render_html_shipping_address( $props ) {
		if ( ! $this->order ) {
			return;
		}

		if ( $this->preview ) {
			$this->render_html_shipping_address_via_hook( '', '', '', '', $props );
		} else {
			add_action( 'woocommerce_email_customer_details', [ $this, 'render_html_shipping_address_via_hook' ], 20, 5 );
			$args = $this->template_args;
			do_action( 'woocommerce_email_customer_details', $args['order'], $args['sent_to_admin'], $args['plain_text'], $args['email'], $props );
			remove_action( 'woocommerce_email_customer_details', [ $this, 'render_html_shipping_address_via_hook' ], 20 );
		}
	}

	public function render_html_shipping_address_via_hook( $order, $sent_to_admin, $plain_text, $email, $props ) {
		if ( $props['type'] !== 'html/shipping_address' ) {
			return;
		}
		$color       = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';

		$shipping_address = $this->order->get_formatted_shipping_address();
		$shipping_address = empty( $shipping_address ) ? $this->order->get_formatted_billing_address() : $shipping_address;
		$shipping_address = str_replace( '<br/>', "</td></tr><tr><td valign='top' style='color: {$color}; font-weight: {$font_weight};'>", $shipping_address );

		$html = "<tr><td valign='top' style='color: {$color}; font-weight: {$font_weight};'>{$shipping_address}</td></tr>";
		$this->table( $html );
	}

	public function render_html_suggest_product( $props ) {
		$font_size    = $props['style']['font-size'] ?? 0;
		$font_weight  = $props['style']['font-weight'] ?? 'normal';
		$color        = $props['style']['color'] ?? 'inherit';
		$line_height  = $props['style']['line-height'] ?? 'inherit';
		$suggest_type = $props['attrs']['data-product_type'] ?? 'newest';
		$row          = $props['attrs']['data-max_row'] ?? '';
		$column       = $props['attrs']['data-column'] ?? '';
		$char_limit   = $props['attrs']['character-limit'] ?? '';
		$limit        = $row * $column;
		$style        = "line-height:{$line_height};color: {$color};font-size: {$font_size};font-weight: $font_weight;font-family:{$this->font_family_default}";
		$img_style = isset( $props['childStyle'] ['.viwec-product-image'] ) ? $this->parse_styles( $props['childStyle']['.viwec-product-image'] ) : '';
		$name_style   = isset( $props['childStyle'] ['.viwec-product-name'] ) ? $this->parse_styles( $props['childStyle']['.viwec-product-name'] ) : '';
		$price_style  = isset( $props['childStyle']['.viwec-product-price'] ) ? $this->parse_styles( $props['childStyle']['.viwec-product-price'] ) : '';

		$v_distance = isset( $props['childStyle']['.viwec-product-distance']['padding'] ) ? $props['childStyle']['.viwec-product-distance']['padding'] : '';
		$v_distance = explode( ' ', $v_distance );
		$v_distance = str_replace( 'px', '', end( $v_distance ) );

		$h_distance = isset( $props['childStyle']['.viwec-product-h-distance'] ) ? $this->parse_styles( $props['childStyle']['.viwec-product-h-distance'] ) : '';

		$full_width = isset( $props['style']['width'] ) ? str_replace( 'px', '', $props['style']['width'] ) : 600;

		if ( $suggest_type && $row && $column ) {
			$products       = $this->get_suggest_products( $suggest_type, $limit, $props );
			$products_count = count( $products );
			$row            = ceil( $products_count / $column );

			if ( $row == 1 && $products_count < $column ) {
				$column = $products_count;
			}

			$col_width = ( ( (int) $full_width - ( (int) $v_distance * ( $column - 1 ) ) ) / (int) $column );

			for ( $i = 0; $i < $row; $i ++ ) { ?>
                <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin: 0; border-collapse: collapse">
                    <tr>
                        <td valign='top' style='font-size: 0' border='0' cellpadding='0' cellspacing='0'>
							<?php for ( $j = 0; $j < $column; $j ++ ) {
								$index  = $i * $column + $j;
								$p_name = ! empty( $products[ $index ]['name'] ) ? $products[ $index ]['name'] : '';

								if ( $char_limit ) {
									$product_title = unicode_chars($p_name);
									if ( strlen( $product_title ) > $char_limit ) {
										$split         = preg_split( '/(?<!^)(?!$)/u', $product_title );
										$p_name = ( $char_limit != '' && $char_limit > 0 && ( count( $split ) >= $char_limit ) ) ? '' : $product_title;
										if ( $p_name == '' ) {
											for ( $product_name_i = 0; $product_name_i < $char_limit; $product_name_i ++ ) {
												$p_name .= $split[ $product_name_i ];
											}
											$p_name .= '...';
										}
									}
								}

								$p_price = ! empty( $products[ $index ]['price'] ) ? $products[ $index ]['price'] : '';
								$p_url   = ! empty( $products[ $index ]['url'] ) ? $products[ $index ]['url'] : '';
								$p_image = '';
								if ( isset( $products[ $index ] ) ) {
									$p_image = ! empty( $products[ $index ]['image'] ) ? $products[ $index ]['image'] : wc_placeholder_img_src();
								}

								$hidden = $p_name . $p_price . $p_image ? '' : 'viwec-mobile-hidden';
								$width  = $j != 0 ? $col_width + $v_distance : $col_width;
								$v_gap  = $j != 0 ? $v_distance : 0;
								$h_gap  = $i != $row - 1 ? $h_distance : '';

								if ( $j == 0 ) {
									?>
                                    <!--[if mso | IE]>
                                    <table width='100%' role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td valign='top' style="width: <?php echo esc_attr( $width ); ?>px; vertical-align:top;">
                                    <![endif]-->
									<?php
								}
								?>
                                <table align="left" class="viwec-responsive" width="<?php echo esc_attr( $width ); ?>px" border='0' cellpadding='0' cellspacing='0'
                                       style="border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <table width='100%' valign='top' border='0' cellpadding='0' cellspacing='0'>
                                                <tr>
                                                    <td valign='top' class='viwec-mobile-hidden' style='padding-left: <?php echo esc_attr( $v_gap ) ?>px;'></td>
                                                    <td valign='top'
                                                        style='vertical-align: top; text-align: center;font-size: <?php echo esc_attr( $font_size ) ?>;font-weight: <?php echo esc_attr( $font_weight ) ?>'>
                                                        <a href='<?php echo esc_url( $p_url ) ?>' target="_blank" style='text-decoration: none; <?php echo esc_attr( $style ) ?>'>
                                                            <img alt='<?php echo esc_attr( $p_name ) ?>' style='padding-bottom: 5px;<?php echo esc_attr( $img_style ) ?>' src='<?php echo esc_url( $p_image ) ?>'
                                                                 width='100%' max-width='100%'>
                                                            <div style='overflow: hidden;<?php echo esc_attr( $name_style ) ?>'>
	                                                            <?php echo wp_kses( $p_name, viwec_allowed_html() ) ?>
                                                            </div>
                                                            <div style='<?php echo esc_attr( $price_style ) ?>'>
																<?php echo wp_kses( $p_price, viwec_allowed_html() ) ?>
                                                            </div>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div style='<?php echo esc_attr( $h_gap ) ?>'></div>
                                        </td>
                                    </tr>
                                </table>
								<?php
								if ( $j == $column - 1 ) {
									?>
                                    <!--[if mso | IE]></td></tr></table><![endif]-->
									<?php
								} else {
									?>
                                    <!--[if mso | IE]></td>
                                    <td valign='top' style='vertical-align:top;width:<?php echo esc_attr( $width ) ?>px;'><![endif]-->
									<?php
								}
							}
							?>
                        </td>
                    </tr>
                </table>
			<?php }
		}
	}

	public function render_html_social( $props ) {
		$align   = $props['style']['text-align'] ?? 'left';
		$socials = [
            'facebook',
			'twitter',
			'instagram',
			'youtube',
			'linkedin',
			'whatsapp',
			'pinterest',
			'telegram',
			'tik-tok',
			'untapped',
			'vk' ];
		$html    = '';
		$width   = ! empty( $props['attrs']['data-width'] ) ? $props['attrs']['data-width'] : 32;

		if ( isset( $props['attrs']['direction'] ) && $props['attrs']['direction'] === 'vertical' ) {
			foreach ( $socials as $social ) {
				$link = isset( $props['attrs'][ $social . '_url' ] ) ? esc_url( $props['attrs'][ $social . '_url' ] ) : '';
				$img  = isset( $props['attrs'][ $social ] ) ? esc_url( $props['attrs'][ $social ] ) : '';
				if ( ! empty( $img ) && ! empty( $link ) ) {
					$html .= "<tr><td valign='top' ><a href='{$link}'><img style='vertical-align: middle' src='{$img}' width='{$width}' max-width='100%' style='margin: 3px;'></a></td></tr>";
				}
			}
		} else {
			$html = '<tr>';
			foreach ( $socials as $social ) {
				$link = isset( $props['attrs'][ $social . '_url' ] ) ? esc_url( $props['attrs'][ $social . '_url' ] ) : '';
				$img  = isset( $props['attrs'][ $social ] ) ? esc_url( $props['attrs'][ $social ] ) : '';
				if ( ! empty( $img ) && ! empty( $link ) ) {
					$html .= "<td valign='top' style='padding: 0;'><a href='{$link}'><img src='{$img}' width='{$width}' max-width='100%' style='margin: 3px;'></a></td>";
				}
			}
			$html .= '</tr>';
		}

		$html = "<table align='{$align}' border='0' cellpadding='0' cellspacing='0' >$html</table>";
		echo wp_kses( $html, viwec_allowed_html() );
	}

	public function render_html_button( $props ) {
		$url         = isset( $props['attrs']['href'] ) ? $this->replace_shortcode( $props['attrs']['href'] ) : '';
		$text        = isset( $props['content']['text'] ) ? $this->replace_shortcode( $props['content']['text'] ) : '';
		$text        = str_replace( [ '<p>', '</p>' ], [ '', '' ], $text );
		$align       = $props['style']['text-align'] ?? 'left';
		$style       = isset( $props['childStyle']['a'] ) ? $this->parse_styles( $props['childStyle']['a'] ) : '';
		$text_color  = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'normal';
		$width       = $props['childStyle']['a']['width'] ?? '';
		?>
        <table align='<?php echo esc_attr( $align ) ?>' width='<?php echo esc_attr( $width ) ?>'
               class='viwec-responsive' border='0' cellpadding='0' cellspacing='0'
               role='presentation' style='border-collapse:separate;width: <?php echo esc_attr( $width ) ?>;'>
            <tr>
                <td class='viwec-mobile-button-padding' align='center' valign='middle' role='presentation' style='<?php echo esc_attr( $style ) ?>'>
                    <a href='<?php echo esc_url( $url ) ?>' target='_blank'
                       style='color:<?php echo esc_attr( $text_color ) ?> !important;font-weight: <?php echo esc_attr( $font_weight ) ?>;display:inline-block;
                               text-decoration:none;text-transform:none;margin:0;text-align: center;max-width: 100%;'>
                          <span style='color: <?php echo esc_attr( $text_color ) ?>'>
                              <?php echo wp_kses( $text, viwec_allowed_html() ) ?>
                          </span>
                    </a>
                </td>
            </tr>
        </table>
		<?php
	}

	public function render_html_menu( $props ) {
		$color       = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';
		?>
        <table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate;margin: 0; padding:0'>
			<?php
			if ( isset( $props['content'] ) && is_array( $props['content'] ) ) {
				$count_text = count( array_filter( $props['content'] ) );
				$count_link = count( array_filter( $props['attrs'] ) );
				$col        = min( $count_text, $count_link ) ? 100 / min( $count_text, $count_link ) . '%' : '';

				if ( isset( $props['attrs']['direction'] ) && $props['attrs']['direction'] === 'vertical' ) {
					foreach ( $props['content'] as $key => $value ) {

						$link = isset( $props['attrs'][ $key ] ) ? $this->replace_shortcode( $props['attrs'][ $key ] ) : '';

						if ( empty( $value ) || ! $link ) {
							continue;
						} ?>
                        <tr>
                            <td valign='top'>
                                <a href='<?php echo esc_url( $link ) ?>'
                                   style='color: <?php echo esc_attr( $color ) ?>; font-weight: <?php echo esc_attr( $font_weight ) ?>;font-style:inherit;'>
									<?php echo wp_kses( $value, viwec_allowed_html() ) ?>
                                </a>
                            </td>
                        </tr>
					<?php }
				} else { ?>
                    <tr>
						<?php
						foreach ( $props['content'] as $key => $value ) {

							$link = isset( $props['attrs'][ $key ] ) ? $this->replace_shortcode( $props['attrs'][ $key ] ) : '';

							if ( empty( $value ) || ! $link ) {
								continue;
							}
							?>
                            <td valign='top' width='<?php echo esc_attr( $col ) ?>'>
                                <a href='<?php echo esc_url( $link ) ?>'
                                   style='color: <?php echo esc_attr( $color ) ?>; font-weight: <?php echo esc_attr( $font_weight ) ?>; font-style: inherit'>
									<?php echo wp_kses( $value, viwec_allowed_html() ) ?>
                                </a>
                            </td>
						<?php } ?>
                    </tr>
				<?php }
			} ?>
        </table>
		<?php
	}

	public function render_html_divider( $props ) {
		$style = isset( $props['childStyle']['hr'] ) ? $this->parse_styles( $props['childStyle']['hr'] ) : '';
		?>
        <table width='100%' border='0' cellpadding='0' cellspacing='0'>
            <tr>
                <td valign='top'>
                    <table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin: 10px 0;">
                        <tr>
                            <td valign='top' style="border-width: 0;<?php echo esc_attr( $style ) ?>"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
		<?php
	}

	public function render_html_spacer( $props ) {
		$style         = isset( $props['childStyle']['.viwec-spacer'] ) ? $this->parse_styles( $props['childStyle']['.viwec-spacer'] ) : '';
		$mobile_hidden = ! empty( $props['attrs']['mobile-hidden'] ) && $props['attrs']['mobile-hidden'] == 'true' ? 'viwec-mobile-hidden' : '';
		?>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' style='font-size:0 !important;margin:0;' class='<?php echo esc_attr( $mobile_hidden ) ?>'>
            <tr>
                <td valign='top' style='<?php echo esc_attr( $style ) ?>'></td>
            </tr>
        </table>
		<?php
	}

	public function render_html_contact( $props ) {
		$align       = $props['style']['text-align'] ?? 'left';
		$color       = $props['style']['color'] ?? 'inherit';
		$font_size   = $props['style']['font-size'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';
		$style       = "color: {$color};font-size: {$font_size};font-weight: $font_weight;font-family:{$this->font_family_default};vertical-align:sub;";
		?>
        <table align='<?php echo esc_attr( $align ) ?>'>
			<?php
			if ( ! empty( $props['attrs']['home'] ) && ! empty( $props['content']['home_text'] ) ) {
				$url  = isset( $props['attrs']['home_link'] ) ? $this->replace_shortcode( $props['attrs']['home_link'] ) : '';
				$text = isset( $props['content']['home_text'] ) ? $this->replace_shortcode( $props['content']['home_text'] ) : '';
				?>
                <tr>
                    <td valign='top'><img src='<?php echo esc_url( $props['attrs']['home'] ) ?>' max-width='100%' style='padding-right: 3px;'></td>
                    <td valign='top'><a style='<?php echo esc_attr( $style ) ?>' href='<?php echo esc_url( $url ) ?>'>
							<?php echo wp_kses( $text, viwec_allowed_html() ) ?>
                        </a>
                    </td>
                </tr>
				<?php
			}

			if ( ! empty( $props['attrs']['email'] ) && ! empty( $props['attrs']['email_link'] ) ) {
				$email_url = $this->replace_shortcode( $props['attrs']['email_link'] );
				?>
                <tr>
                    <td valign='top'><img src='<?php echo esc_url( $props['attrs']['email'] ) ?>' max-width='100%' style='padding-right: 3px;'></td>
                    <td valign='top'>
                        <a style='<?php echo esc_attr( $style ) ?>' href='mailto:<?php echo esc_attr( $email_url ) ?>'>
							<?php echo esc_html( $email_url ) ?>
                        </a>
                    </td>
                </tr>
				<?php
			}

			if ( ! empty( $props['attrs']['phone'] ) && ! empty( $props['content']['phone_text'] ) ) {
				?>
                <tr>
                    <td valign='top'><img src='<?php echo esc_url( $props['attrs']['phone'] ) ?>' max-width='100%' style='padding-right: 3px;'></td>
                    <td valign='top'><a style='<?php echo esc_attr( $style ) ?>' href='tel:<?php echo esc_attr( $props['content']['phone_text'] ) ?>'>
							<?php echo wp_kses( $props['content']['phone_text'], viwec_allowed_html() ) ?>
                        </a>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
		<?php
	}

	public function render_html_coupon( $props ) {
		$type        = $props['attrs']['data-coupon-type'] ?? '';
		$coupon_code = $props['content']['data-coupon-code'] ?? '';
		$align       = $props['style']['text-align'] ?? 'left';
		$text_color  = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'normal';
		$width       = $props['childStyle']['.viwec-coupon']['width'] ?? '';
		$style       = isset( $props['childStyle']['.viwec-coupon'] ) ? $this->parse_styles( $props['childStyle']['.viwec-coupon'] ) : '';

		if ( $type == 2 ) {
			$coupon_code = $this->preview ? 's0kvk4kp' : $this->generate_coupon( $props );
		}

		if ( ! $coupon_code || $coupon_code === 'COUPONCODE' ) {
			return;
		}

		$coupon_code = strtoupper( $coupon_code );

		$this->render_data['coupon'] = $coupon_code;

		$coupon_obj     = new \WC_Coupon( $coupon_code );
		
//		$this->template_args['coupon_expire_date'] = $coupon_ex_date; // error when Coupon expiry date is empty
		$coupon_ex_date = $coupon_obj->get_date_expires();
		if ( !empty($coupon_ex_date)) {
		    $coupon_ex_date = $coupon_ex_date->date_i18n( wc_date_format() );
			$this->template_args['coupon_expire_date'] = $coupon_ex_date;
		}

		?>
        <table align='<?php echo esc_attr( $align ) ?>' width='<?php echo esc_attr( $width ) ?>'
               class='viwec-responsive' border='0' cellpadding='0' cellspacing='0' role='presentation'
               style='border-collapse:separate;width:<?php echo esc_attr( $width ) ?>;'>
            <tr>
                <td class='viwec-mobile-button-padding' align='center' valign='middle' role='presentation' style='<?php echo esc_attr( $style ) ?>'>
                    <div style='color:<?php echo esc_attr( $text_color ) ?> !important;font-weight:<?php echo esc_attr( $font_weight ) ?>;
                            display:inline-block;margin:0;text-align: center; max-width:100%;'>
						<?php echo esc_html( $coupon_code ) ?>
                    </div>
                </td>
            </tr>
        </table>
		<?php
	}

	public function render_html_post( $props ) {
		$cat           = ! empty( $props['attrs']['data-post-category'] ) ? $props['attrs']['data-post-category'] : '';
		$title_style   = ! empty( $props['childStyle']['.viwec-post-title'] ) ? $this->parse_styles( $props['childStyle']['.viwec-post-title'] ) : '';
		$content_style = ! empty( $props['childStyle']['.viwec-post-content'] ) ? $this->parse_styles( $props['childStyle']['.viwec-post-content'] ) : '';
		$title_limit   = ! empty( $props['attrs']['data-title-limit'] ) ? $props['attrs']['data-title-limit'] : 0;
		$content_limit = isset( $props['attrs']['data-content-limit'] ) ? $props['attrs']['data-content-limit'] : 80;
		$row           = ! empty( $props['attrs']['data-max_row'] ) ? $props['attrs']['data-max_row'] : 1;
		$col           = ! empty( $props['attrs']['data-column'] ) ? $props['attrs']['data-column'] : 2;
		$include       = ! empty( $props['attrs']['data-include-post-id'] ) ? explode( ',', $props['attrs']['data-include-post-id'] ) : [];
		$exclude       = ! empty( $props['attrs']['data-exclude-post-id'] ) ? explode( ',', $props['attrs']['data-exclude-post-id'] ) : [];

		$distance = ! empty( $props['childStyle']['.viwec-post-distance']['padding'] ) ? $props['childStyle']['.viwec-post-distance']['padding'] : 10;
		$distance = explode( ' ', $distance );
		$distance = (int) str_replace( 'px', '', end( $distance ) );

		$h_distance = ! empty( $props['childStyle']['.viwec-post-h-distance']['padding'] ) ?
			(int) str_replace( 'px', '', current( explode( ' ', $props['childStyle']['.viwec-post-h-distance']['padding'] ) ) ) : 10;

		$full_width = ! empty( $props['childStyle']['.viwec-post']['width'] ) ?
			str_replace( [ 'px' ], [ '' ], $props['childStyle']['.viwec-post']['width'] ) : 600;

		if ( $row && $col ) {
			$posts = get_posts( [
				'numberposts' => $row * $col,
				'category'    => $cat,
				'include'     => $include,
				'exclude'     => $exclude,
			] );

			if ( empty( $posts ) ) {
				return;
			}

			$count_posts = count( $posts );
			$real_row    = ceil( $count_posts / $col );

			if ( ! $real_row ) {
				return;
			}

			$real_col  = ceil( $count_posts / $real_row );
			$col_width = ( (int) $full_width - ( $distance * ( $real_col - 1 ) ) ) / (int) $real_col;

			for ( $i = 0; $i < $real_row; $i ++ ) {
				?>
                <tr>
                    <td valign="top" style="font-size: 0">
                        <!--[if mso | IE]>
                        <table width="100%" role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr><![endif]-->
						<?php
						for ( $j = 0; $j < $real_col; $j ++ ) {
							$k = $j + $i * $real_col;

							if ( ! isset( $posts[ $k ] ) ) {
								continue;
							}

							$post         = $posts[ $k ];
							$post_title   = $post->post_title;
							$post_content = get_the_excerpt( $post );
							$link         = get_permalink( $post );
							$img_src      = get_the_post_thumbnail_url( $post, 'woocommerce_thumbnail' );
							$img          = $img_src ? "<img width='100%' max-width='100%' src='{$img_src}'>" : '';

							if ( $title_limit != 0 ) {
								$post_title = $title_limit < strlen( $post_title ) ? substr( $post_title, 0, $title_limit ) . '...' : $post_title;
							}

							if ( $content_limit ) {
								$post_content = $content_limit < strlen( $post_content ) ? substr( $post_content, 0, $content_limit ) . '...' : $post_content;
							} else {
								$post_content = '';
							}

							$title_content = "<div style='{$title_style}'>{$post_title}</div><div style='{$content_style}'>{$post_content}</div>";
							$gap           = $j != 0 ? "width:{$distance}px;" : '';
							$_col_width    = $j != 0 ? $col_width + $distance : $col_width;
							?>
                            <!--[if mso | IE]>
                            <td valign='top' style='width:<?php echo esc_attr( $_col_width ) ?>px;'><![endif]-->
                            <div class='viwec-responsive '
                                 style='display: inline-block;vertical-align: bottom; width:<?php echo esc_attr( $_col_width ) ?>px;font-size: 15px;'>
                                <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                                    <tr>
                                        <td valign='top' class='viwec-mobile-hidden' style='<?php echo esc_attr( $gap ) ?>'></td>
                                        <td valign='top'>
                                            <a href='<?php echo esc_url( $link ) ?>'>
												<?php
												if ( $real_col == 1 ) {
													$img_width = $img ? '25%' : '0';
													$padding   = $img ? 'padding-left:10px;' : '';
													?>
                                                    <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                                                        <tr>
                                                            <td valign='top' style='width:<?php echo esc_attr( $img_width ) ?>;'>
																<?php echo wp_kses_post( $img ) ?>
                                                            </td>
                                                            <td valign='top' style='<?php echo esc_attr( $padding ) ?>'>
																<?php echo wp_kses_post( $title_content ) ?>
                                                            </td>
                                                        </tr>
                                                    </table>
													<?php
												} else {
													echo wp_kses_post( $img ); ?>
                                                    <div style='padding: 5px;'></div>
													<?php echo wp_kses_post( $title_content );
												} ?>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
								<?php
								if ( $i != $real_row ) { ?>
                                    <div style='padding-top:<?php echo esc_attr( $h_distance ) ?>px;'></div>
								<?php } ?>
                            </div>
                            <!--[if mso | IE]></td><![endif]-->
						<?php } ?>
                        <!--[if mso | IE]></tr></table><![endif]--></td>
                </tr>
			<?php }
		}
	}

	public function render_html_wc_hook( $props ) {
		if ( $this->preview ) {
			esc_html_e( 'WooCommerce hook placeholder', 'xstore-core' );
		} else {
			$hook = ! empty( $props['attrs']['data-wc-hook'] ) ? $props['attrs']['data-wc-hook'] : 'woocommerce_email_before_order_table';
			$args = $this->template_args;
			switch ( $hook ) {
				case '':
				case 'woocommerce_email_before_order_table':
					do_action( 'woocommerce_email_before_order_table', $args['order'], $args['sent_to_admin'], $args['plain_text'], $args['email'] );
					break;
				case 'woocommerce_email_after_order_table':
					do_action( 'woocommerce_email_after_order_table', $args['order'], $args['sent_to_admin'], $args['plain_text'], $args['email'] );
					break;
				case 'woocommerce_email_order_meta':
					do_action( 'woocommerce_email_order_meta', $args['order'], $args['sent_to_admin'], $args['plain_text'], $args['email'] );
					break;
			}
		}
	}

	public function render_html_recover_heading( $props ) {
		if ( $this->preview ) {
			echo esc_html__( 'The heading of original email will be transferred here', 'xstore-core' );
		}
		if ( $this->class_email ) {
			echo wp_kses( $this->class_email->get_heading(), viwec_allowed_html() );
		}
	}

	public function render_html_recover_content() {
		if ( $this->preview ) {
			echo esc_html__( 'The content of original email will be transferred here', 'xstore-core' );
		}
		if ( $this->other_message_content ) {
			echo wp_kses( $this->other_message_content, viwec_allowed_html() );
		}
	}

	public function table( $content, $style = '', $width = '100%', $attr = [] ) {
//		if ( is_array( $attr ) ) {
//			$attr = implode( ' ', $attr );
//		}
//		$attr = wp_parse_args( $attr, [] );
		?>
        <table width='<?php echo esc_attr( $width ) ?>' border='0' cellpadding='0' cellspacing='0' align='center'
               style='border-collapse: collapse;<?php echo esc_attr( $style ) ?>'>
			<?php echo wp_kses( $content, viwec_allowed_html() ) ?>
        </table>
		<?php
	}

	public function generate_coupon( $props ) {
		if ( ! $props['attrs']['data-coupon-amount'] || ( $props['attrs']['data-discount-type'] == 'percent' && $props['attrs']['data-coupon-amount'] > 100 ) ) {
			return '';
		}

		$option = $props['attrs'];
		$code   = $this->generate_coupon_code();
		$coupon = new \WC_Coupon( $code );

		$discount_type = ! empty( $option['data-discount-type'] ) ? $option['data-discount-type'] : 'percent';

		$coupon->set_discount_type( $discount_type );

		$coupon->set_amount( (float) $option['data-coupon-amount'] );

		if ( ! empty( $option['data-coupon-expiry-date'] ) ) {
			$coupon->set_date_expires( current_time( 'U' ) + $option['data-coupon-expiry-date'] * DAY_IN_SECONDS );
		}

		if ( ! empty( $option['data-coupon-min-spend'] ) ) {
			$coupon->set_minimum_amount( $option['data-coupon-min-spend'] );
		}

		if ( ! empty( $option['data-coupon-max-spend'] ) ) {
			$coupon->set_maximum_amount( $option['data-coupon-max-spend'] );
		}

		if ( ! empty( $option['data-coupon-individual'] ) ) {
			$coupon->set_individual_use( $option['data-coupon-individual'] );
		}

		if ( ! empty( $option['data-coupon-exclude-sale'] ) ) {
			$coupon->set_exclude_sale_items( $option['data-coupon-exclude-sale'] );
		}

		if ( ! empty( $option['data-coupon-allow-free-shipping'] ) ) {
			$coupon->set_free_shipping( $option['data-coupon-allow-free-shipping'] );
		}

		if ( ! empty( $option['data-coupon-include-product'] ) ) {
			$coupon->set_product_ids( explode( ',', $option['data-coupon-include-product'] ) );
		}

		if ( ! empty( $option['data-coupon-exclude-product'] ) ) {
			$coupon->set_excluded_product_ids( explode( ',', $option['data-coupon-exclude-product'] ) );
		}

		if ( ! empty( $option['data-coupon-include-categories'] ) ) {
			$coupon->set_product_categories( explode( ',', $option['data-coupon-include-categories'] ) );
		}

		if ( ! empty( $option['data-coupon-exclude-categories'] ) ) {
			$coupon->set_excluded_product_categories( explode( ',', $option['data-coupon-exclude-categories'] ) );
		}

		if ( ! empty( $option['data-coupon-limit-quantity'] ) ) {
			$coupon->set_usage_limit( $option['data-coupon-limit-quantity'] );
		}

		if ( ! empty( $option['data-coupon-limit-items'] ) ) {
			$coupon->set_limit_usage_to_x_items( $option['data-coupon-limit-items'] );
		}

		if ( ! empty( $option['data-coupon-limit-users'] ) ) {
			$coupon->set_usage_limit_per_user( $option['data-coupon-limit-users'] );
		}

		$coupon->save();

		return $code;
	}

	public function generate_coupon_code() {
		$code          = '';
		$character_arr = array_merge( range( 'a', 'z' ), range( 0, 9 ) );

		for ( $i = 0; $i < 8; $i ++ ) {
			$rand = rand( 0, count( $character_arr ) - 1 );
			$code .= $character_arr[ $rand ];
		}

		$args = array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'title'          => $code
		);

		$the_query = new \WP_Query( $args );

		if ( $the_query->have_posts() ) {
			$code = $this->generate_coupon_code();
		}
		wp_reset_postdata();

		return $code;
	}

	public function get_suggest_products( $suggest_type, $limit, $props, $first_variation = false ) {
		$auto_atc         = ! empty( $props['attrs']['data-auto-atc'] ) ? $props['attrs']['data-auto-atc'] : '';
		$suggest_products = $return_products = $categories = $bought_ids = [];
		$orderby          = 'rand';

		if ( $this->order ) {
			$bought_ids = Utils::get_bought_ids( $this->order->get_items( 'line_item' ) );
		}

		$bought_ids = apply_filters( 'viwec_ids_to_suggest_products', $bought_ids );

		switch ( $suggest_type ) {
			case 'related':
				foreach ( $bought_ids as $id ) {
					$suggest_products = array_merge( $suggest_products, wc_get_related_products( $id ) );
				}
				break;

			case 'on_sale':
				$suggest_products = wc_get_product_ids_on_sale();
				break;

			case 'up_sell':
				foreach ( $bought_ids as $id ) {
					$upsel_ids = get_post_meta( $id, '_upsell_ids', true );
					if ( is_array( $upsel_ids ) && count( $upsel_ids ) ) {
						$suggest_products = array_merge( $suggest_products, $upsel_ids );
					}
				}
				break;

			case 'cross_sell':
				foreach ( $bought_ids as $id ) {
					$crosssell_ids = get_post_meta( $id, '_crosssell_ids', true );
					if ( is_array( $crosssell_ids ) && count( $crosssell_ids ) ) {
						$suggest_products = array_merge( $suggest_products, $crosssell_ids );
					}
				}
				break;

			case 'category':
				foreach ( $bought_ids as $id ) {
					$cats = get_the_terms( $id, 'product_cat' );
					if ( is_array( $cats ) && count( $cats ) ) {
						foreach ( $cats as $cat ) {
							$categories[] = $cat->slug;
						}
					}
				}
				break;

			case 'featured':
				$suggest_products = wc_get_featured_product_ids();
				break;

			case 'best_seller':
				$args  = [ 'post_type' => 'product', 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'posts_per_page' => $limit ];
				$query = new \WP_Query( $args );
				if ( is_wp_error( $query ) ) {
					break;
				}
				$products = $query->get_posts();
				if ( is_array( $products ) && count( $products ) ) {
					foreach ( $products as $product ) {
						$suggest_products[] = $product->ID;
					}
				}
				break;

			case 'best_rated':
				$args  = [ 'post_type' => 'product', 'meta_key' => '_wc_average_rating', 'orderby' => 'meta_value_num', 'posts_per_page' => $limit ];
				$query = new \WP_Query( $args );
				if ( is_wp_error( $query ) ) {
					break;
				}
				$products = $query->get_posts();
				if ( is_array( $products ) && count( $products ) ) {
					foreach ( $products as $product ) {
						$suggest_products[] = $product->ID;
					}
				}
				break;

			case 'newest':
				$orderby = 'date';
				break;
		}

		$categories       = array_unique( $categories );
		$suggest_products = array_slice( array_unique( $suggest_products ), 0, $limit );

		$args = [
			'status'       => 'publish',
			'limit'        => $limit,
			'include'      => $suggest_products,
			'category'     => $categories,
			'orderby'      => $orderby,
			'order'        => 'DESC',
			'stock_status' => 'instock',
		];

		$suggest_products = wc_get_products( $args );

		if ( count( $suggest_products ) ) {
			foreach ( $suggest_products as $product ) {
				$image     = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
				$image_url = $image ? $image : wc_placeholder_img_src( 'woocommerce_thumbnail' );
				$url       = $product->get_permalink();
				$pid       = $product->get_id();

				$query_args = [ 'viwec_rt' => substr( NONCE_SALT, 0, 10 ) . $pid ];

				if ( $product->is_type( 'simple' ) && $auto_atc == 'true' ) {
					$query_args['add-to-cart'] = $pid;
				}

				$url = add_query_arg( $query_args, $url );

				$return_products[] = [
					'name'  => $product->get_name(),
					'price' => $product->get_price_html(),
					'image' => $image_url,
					'url'   => $url
				];
			}
		}

		return $return_products;
	}

	public function order_download( $item_id, $item, $order ) {
		$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

		if ( ! $show_downloads ) {
			return;
		}

		$pid       = $item->get_data()['product_id'];
		$downloads = $order->get_downloadable_items();

		foreach ( $downloads as $download ) {
			if ( $pid == $download['product_id'] ) {
				$href    = esc_url( $download['download_url'] );
				$display = esc_html( $download['download_name'] );
				$expires = '';
				if ( ! empty( $download['access_expires'] ) ) {
					$datetime     = esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) );
					$title        = esc_attr( strtotime( $download['access_expires'] ) );
					$display_time = esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) );
					$expires      = "- <time datetime='$datetime' title='$title'>$display_time</time>";
				}
				echo "<p><a href='$href'>$display</a> $expires</p>";
			}
		}
	}

	public function remove_shipping_method( $shipping_display ) {
		if ( $this->order ) {
			return '';
		}

		return $shipping_display;
	}
}

