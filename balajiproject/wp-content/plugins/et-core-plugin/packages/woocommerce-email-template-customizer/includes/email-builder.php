<?php

namespace VIWEC\INC;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Builder {

	protected static $instance = null;

	private function __construct() {
		add_action( 'init', array( $this, 'register_custom_post_type' ), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'get_sample_permalink_html', array( $this, 'delete_permalink' ) );
		add_filter( 'post_row_actions', array( $this, 'delete_view_action' ) );
		add_action( 'save_post_viwec_template', array( $this, 'save_post' ) );

		add_action( 'admin_print_styles', array( $this, 'background_image_style' ) );

		add_filter( 'manage_viwec_template_posts_columns', array( $this, 'add_column_header' ) );
		add_action( 'manage_viwec_template_posts_custom_column', array( $this, 'add_column_content' ), 10, 2 );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
		add_action( 'post_action_viwec_duplicate', array( $this, 'duplicate_template' ) );
		add_action( 'restrict_manage_posts', array( $this, 'add_filter_dropdown' ) );
		add_filter( 'parse_query', array( $this, 'parse_query_filter' ) );
		add_filter( 'enter_title_here', array( $this, 'change_text_add_title' ) );

		add_action( 'admin_head', array( $this, 'remove_action' ), 9999 );

		add_filter( 'woocommerce_email_setting_columns', array( $this, 'add_edit_buttons_columns' ) );
		add_action( 'woocommerce_email_setting_column_viwec-edit', array( $this, 'add_edit_buttons' ) );

		add_filter( 'viwec_register_email_type', [ $this, 'add_other_type' ], 9999 );

		add_action( 'edit_form_after_title', [ $this, 'use_note' ] );

		//Ajax
		add_action( 'wp_ajax_viwec_preview_template', array( $this, 'preview_template' ) );
		add_action( 'wp_ajax_viwec_send_test_email', array( $this, 'send_test_email' ) );
		add_action( 'wp_ajax_viwec_change_admin_bar_stt', array( $this, 'change_admin_bar_stt' ) );
		add_action( 'wp_ajax_viwec_search_coupon', array( $this, 'search_coupon' ) );
		add_action( 'wp_ajax_viwec_search_post', array( $this, 'search_post' ) );
	}

	public function remove_action() {
	    if ( !function_exists('get_current_screen')) return;
		if ( get_current_screen()->id == 'viwec_template' ) {
			remove_all_actions( 'admin_notices' );
			global $wp_meta_boxes;
			unset( $wp_meta_boxes['viwec_template']['side']['core'] );
		}
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function delete_view_action( $actions ) {
		global $post_type;
		if ( 'viwec_template' === $post_type ) {
			unset( $actions['view'] );
		}

		return $actions;
	}

	public function delete_permalink( $link ) {
		global $post_type;
		if ( 'viwec_template' === $post_type ) {
			$link = '';
		}

		return $link;
	}

	public function register_custom_post_type() {
//		if ( ! is_admin() ) { // on test
//			return;
//		}
		$labels = array(
			'name'               => _x( 'Email Builder Templates', 'Post Type General Name', 'xstore-core' ),
			'singular_name'      => _x( 'Email Builder Templates', 'Post Type Singular Name', 'xstore-core' ),
			'menu_name'          => esc_html__( 'Email Builder', 'xstore-core' ),
			'parent_item_colon'  => esc_html__( 'Parent Email', 'xstore-core' ),
			'all_items'          => esc_html__( 'All Emails', 'xstore-core' ),
			'add_new_item'       => esc_html__( 'Add New Template', 'xstore-core' ),
			'add_new'            => esc_html__( 'Add New', 'xstore-core' ),
			'edit_item'          => esc_html__( 'Edit Templates', 'xstore-core' ),
			'update_item'        => esc_html__( 'Update Templates', 'xstore-core' ),
			'search_items'       => esc_html__( 'Search Templates', 'xstore-core' ),
			'not_found'          => esc_html__( 'Not Found', 'xstore-core' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', 'xstore-core' ),
		);

		$args = array(
			'label'               => esc_html__( 'Email Builder Templates', 'xstore-core' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false, // true
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true, // false
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'query_var'           => true,
			'capabilities'        => apply_filters( 'viwec_capabilities_role', array() ),
			'create_posts'        => apply_filters( 'viwec_create_posts_role', '' ),
			'menu_position'       => 2,
			'map_meta_cap'        => true,
			'rewrite'             => array( 'slug' => VIWEC_SLUG ),
			'menu_icon'           => 'dashicons-email'
		);

		// Registering your Custom Post Type
		register_post_type( 'viwec_template', $args );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'viwec-email-builder',
			esc_html__( 'Email', 'xstore-core' ),
			array( $this, 'email_builder_box' ),
			'viwec_template',
			'normal',
			'default'
		);

		add_meta_box(
			'viwec-email-type',
			esc_html__( 'Email type', 'xstore-core' ),
			array( $this, 'email_type_box' ),
			'viwec_template',
			'side',
			'default'
		);

		add_meta_box(
			'viwec-email-rules',
			esc_html__( 'Rules', 'xstore-core' ),
			array( $this, 'email_rules_box' ),
			'viwec_template',
			'side',
			'default'
		);

		add_meta_box(
			'viwec-email-testing',
			esc_html__( 'Testing', 'xstore-core' ),
			array( $this, 'email_testing_box' ),
			'viwec_template',
			'side',
			'default'
		);

		add_meta_box(
			'viwec-email-template-note',
			esc_html__( "Admin's note for this template", 'xstore-core' ),
			array( $this, 'admin_note' ),
			'viwec_template',
			'side',
			'default'
		);

		add_meta_box(
			'viwec-email-data',
			esc_html__( 'Data', 'xstore-core' ),
			array( $this, 'exim_data' ),
			'viwec_template',
			'side',
			'default'
		);
	}

	public function email_builder_box( $post ) {
		$admin_bar_stt = Utils::get_admin_bar_stt();
		wc_get_template( 'email-editor.php', array( 'admin_bar_stt' => $admin_bar_stt ), 'woocommerce-email-template-customizer', VIWEC_TEMPLATES );
	}

	public function email_type_box( $post ) {
		wc_get_template( 'email-type.php', [ 'type_selected' => get_post_meta( $post->ID, 'viwec_settings_type', true ) ],
			'woocommerce-email-template-customizer', VIWEC_TEMPLATES );
	}

	public function email_rules_box( $post ) {
		$settings = get_post_meta( $post->ID, 'viwec_setting_rules', true );
		$params   = [
			'type_selected'       => get_post_meta( $post->ID, 'viwec_settings_type', true ),
			'categories_selected' => $settings['categories'] ?? [],
			'countries_selected'  => $settings['countries'] ?? [],
			'min_subtotal'        => $settings['min_subtotal'] ?? '',
			'max_subtotal'        => $settings['max_subtotal'] ?? ''
		];

		wc_get_template( 'email-rules.php', $params, 'woocommerce-email-template-customizer', VIWEC_TEMPLATES );
	}

	public function email_testing_box( $post ) {
		$_orders  = [];
		$statuses = wc_get_order_statuses();
		if ( ! empty( $statuses ) && is_array( $statuses ) ) {
			foreach ( $statuses as $status => $name ) {
				$arg    = [ 'numberposts' => 1, 'status' => $status ];
				$orders = wc_get_orders( $arg );
				if ( ! empty( $orders ) ) {
					$_orders[] = current( $orders );
				}
			}
		}
		wc_get_template( 'email-testing.php', [ 'orders' => $_orders ], '', VIWEC_TEMPLATES );
	}

	public function exim_data() {
		?>
        <div>
            <textarea id="viwec-exim-data"></textarea>
            <div class="vi-ui buttons">
                <button type="button" class="vi-ui button mini attached viwec-import-data"><?php esc_html_e( 'Import' ); ?></button>
                <button type="button" class="vi-ui button mini attached viwec-export-data"><?php esc_html_e( 'Export' ); ?></button>
                <button type="button" class="vi-ui button mini attached viwec-copy-data"><?php esc_html_e( 'Copy' ); ?></button>
            </div>
        </div>

		<?php
	}

	public function admin_note( $post ) {
		$note = get_post_meta( $post->ID, 'viwec_admin_note', true );
		?>
        <div>
            <textarea id="viwec-admin-note" name="viwec_admin_note"><?php echo wp_kses_post( $note ) ?></textarea>
        </div>

		<?php
	}

	public function save_post( $post_id ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}


		$subject         = isset( $_POST['viwec_settings_subject'] ) ? sanitize_text_field( $_POST['viwec_settings_subject'] ) : '';
		$email_type      = isset( $_POST['viwec_settings_type'] ) ? sanitize_text_field( $_POST['viwec_settings_type'] ) : '';
		$email_structure = isset( $_POST['viwec_email_structure'] ) ? sanitize_text_field( htmlentities( ( $_POST['viwec_email_structure'] ) ) ) : '';
		$email_rules     = isset( $_POST['viwec_setting_rules'] ) ? wc_clean( $_POST['viwec_setting_rules'] ) : '';
		$admin_note      = isset( $_POST['viwec_admin_note'] ) ? sanitize_text_field( ( $_POST['viwec_admin_note'] ) ) : '';

		update_post_meta( $post_id, 'viwec_settings_subject', $subject );
		update_post_meta( $post_id, 'viwec_settings_type', $email_type );
		update_post_meta( $post_id, 'viwec_email_structure', $email_structure );
		update_post_meta( $post_id, 'viwec_setting_rules', $email_rules );
		update_post_meta( $post_id, 'viwec_admin_note', $admin_note );
	}

	public function preview_template() {
		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'viwec_nonce' ) ) ) {
			return;
		}
		$data         = isset( $_POST['data'] ) ? json_decode( stripslashes( html_entity_decode( sanitize_text_field( htmlentities( $_POST['data'] ) ) ) ), true ) : '';
		$order_id     = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		$email_render = Email_Render::init();

		$email_render->preview = true;
		$order_id ? $email_render->order( $order_id ) : $email_render->demo_order();
		$email_render->demo_new_user();
		$email_render->render( $data );
		wp_die();
	}

	public function send_test_email() {
		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'viwec_nonce' ) ) ) {
			return;
		}

		$data     = isset( $_POST['data'] ) ? json_decode( stripslashes( html_entity_decode( sanitize_text_field( htmlentities( $_POST['data'] ) ) ) ), true ) : '';
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';

		$email_render          = Email_Render::init();
		$email_render->preview = true;
		$order_id ? $email_render->order( $order_id ) : $email_render->demo_order();
		$email_render->demo_new_user();
		ob_start();
		$email_render->render( $data );
		$email      = ob_get_clean();
		$headers [] = "Content-Type: text/html";
		$subject    = esc_html__( 'WooCommerce Email Customizer test email template', 'xstore-core' );
		$mail_to    = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$result     = false;
		if ( is_email( $mail_to ) ) {
			$result = wp_mail( $mail_to, $subject, $email, $headers );
		}
		$message = $result ? esc_html__( 'Email was sent successfully', 'xstore-core' ) : esc_html__( 'Your server can\'t send email', 'xstore-core' );
		$result ? wp_send_json_success( $message ) : wp_send_json_error( $message );
	}

	public function background_image_style() {
		if ( !function_exists('get_current_screen')) return;
		if ( is_object(get_current_screen()) && get_current_screen()->id == 'viwec_template' ) {
			$img_map = Init::$img_map;
			$css     = '';

			foreach ( $img_map['social_icons'] as $type => $data ) {
				if ( is_array( $data ) && ! empty( $data ) ) {
					foreach ( $data as $slug => $name ) {
//						$img = VIWEC_IMAGES . $slug . '.png';
						$img = VIWEC_IMAGES . 'new-socials/' . $type . '/' . $slug . '.png';
						$css .= ".mce-i-{$slug}{background: url('{$img}') !important; background-size: cover !important;}";
					}
				}
			}
			foreach ( $img_map['infor_icons'] as $type => $data ) {
				if ( is_array( $data ) && ! empty( $data ) ) {
					foreach ( $data as $slug => $name ) {
						$img = VIWEC_IMAGES . $slug . '.png';
						$css .= ".mce-i-{$slug}{background: url('{$img}') !important; background-size: cover !important;}";
					}
				}
			}

			wp_register_style( 'viwec-inline-style', false );
			wp_enqueue_style( 'viwec-inline-style' );
			wp_add_inline_style( 'viwec-inline-style', $css );
		}
	}

	public function add_column_header( $cols ) {
		$cols = [
			'cb'        => '<input type="checkbox">',
			'title'     => esc_html__( 'Email subject', 'xstore-core' ),
			'type'      => esc_html__( 'Type', 'xstore-core' ),
			'recipient' => esc_html__( 'Recipient', 'xstore-core' ),
			'note'      => esc_html__( 'Note', 'xstore-core' ),
			'date'      => esc_html__( 'Date', 'xstore-core' )
		];

		return $cols;
	}

	public function add_column_content( $col, $post_id ) {
		$wc_mails  = Utils::get_email_ids();
		$recipient = Utils::get_email_recipient();
		$type      = get_post_meta( $post_id, 'viwec_settings_type', true );

		switch ( $col ) {
			case 'type':
				$type = $wc_mails[ $type ] ?? '';
				echo esc_html( $type );
				break;

			case 'recipient':
				echo ! empty( $recipient[ $type ] ) ? esc_html( $recipient[ $type ] ) : esc_html__( 'Customer', 'xstore-core' );
				break;

			case 'note':
				$note = get_post_meta( $post_id, 'viwec_admin_note', true );
				echo esc_html( $note );
				break;
		}

	}

	public function post_row_actions( $action, $post ) {
		if ( $post->post_type === 'viwec_template' ) {
			unset( $action['inline hide-if-no-js'] );
			$href   = admin_url( "post.php?action=viwec_duplicate&id={$post->ID}" );
			$action = [ 'viwec-duplicate' => "<a href='{$href}' onclick='this.style.visibility=\"hidden\";'>" . esc_html__( 'Duplicate', 'xstore-core' ) . "</a>" ] + $action;
		}

		return $action;
	}

	public function duplicate_template() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$dup_id = ! empty( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
		if ( $dup_id ) {
			$current_post = get_post( $dup_id );

			$args   = [
				'post_title' => 'Copy of ' . $current_post->post_title,
				'post_type'  => $current_post->post_type,
			];
			$new_id = wp_insert_post( $args );

			$email_type       = get_post_meta( $dup_id, 'viwec_settings_type', true );
			$email_structure  = get_post_meta( $dup_id, 'viwec_email_structure', true );
			$email_categories = get_post_meta( $dup_id, 'viwec_settings_categories', true );
			$email_countries  = get_post_meta( $dup_id, 'viwec_settings_countries', true );
			update_post_meta( $new_id, 'viwec_settings_type', $email_type );
			update_post_meta( $new_id, 'viwec_email_structure', str_replace( '\\', '\\\\', $email_structure ) );
			update_post_meta( $new_id, 'viwec_settings_categories', $email_categories );
			update_post_meta( $new_id, 'viwec_settings_countries', $email_countries );
			wp_safe_redirect( admin_url( "post.php?post={$new_id}&action=edit" ) );
			exit;
		}
	}

	public function add_filter_dropdown() {
		if ( !function_exists('get_current_screen')) return;
		if ( get_current_screen()->id === 'edit-viwec_template' ) {
			$emails = Utils::get_email_ids();
			echo '<select name="viwec_template_filter">';
			echo "<option value=''>" . esc_html__( 'Filter by type', 'xstore-core' ) . "</option>";
			foreach ( $emails as $key => $name ) {
				echo "<option value='{$key}'>{$name}</option>";
			}
			echo '</select>';
		}
	}

	public function parse_query_filter( $query ) {
		global $pagenow;
		$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
		if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'viwec_template' && ! empty( $_GET['viwec_template_filter'] ) ) {
			$query->query_vars['meta_key']     = 'viwec_settings_type';
			$query->query_vars['meta_value']   = sanitize_text_field( $_GET['viwec_template_filter'] );
			$query->query_vars['meta_compare'] = '=';
		}

		return $query;
	}

	public function change_text_add_title( $title ) {
		if ( !function_exists('get_current_screen')) return;
		if ( get_current_screen()->id == 'viwec_template' ) {
			$title = esc_html__( 'Add Email Subject', 'xstore-core' );
			echo "<div class='viwec-subject-quick-shortcode'><i class='dashicons dashicons-menu'></i></div>";
		}

		return $title;
	}

	public function change_admin_bar_stt() {
		$current_stt = Utils::get_admin_bar_stt();
		$new_stt     = $current_stt ? false : true;
		$result      = update_option( 'viwec_admin_bar_stt', $new_stt );
		if ( $result ) {
			wp_send_json_success( $new_stt );
		} else {
			wp_send_json_error();
		}
		wp_die();
	}

	public function add_edit_buttons( $email ) {
		$href = admin_url( "edit.php?post_type=viwec_template&viwec_template_filter={$email->id}" );
		echo sprintf( "<td><a href='%1s' class='button alignright'>%2s</a></td>", esc_url( $href ), esc_html__( 'Edit with XStore Built-in Email Builder', 'xstore-core' ) );
	}

	public function add_edit_buttons_columns( $columns ) {
		unset( $columns['actions'] );
		$columns['viwec-edit'] = '';
		$columns['actions']    = '';

		return $columns;
	}

	public function add_other_type( $email_ids ) {
		return $email_ids;
	}

	public function search_coupon() {
		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'viwec_nonce' ) ) ) {
			return;
		}
		$q = ! empty( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		if ( $q ) {
			$args    = [
				'numberposts' => - 1,
				'post_type'   => 'shop_coupon',
				's'           => $q
			];
			$coupons = get_posts( $args );
			if ( ! empty( $coupons ) && is_array( $coupons ) ) {
				$result = [];
				foreach ( $coupons as $coupon ) {
					$result[] = [ 'id' => strtoupper( $coupon->post_title ), 'text' => strtoupper( $coupon->post_title ) ];
				}

				wp_send_json( $result );
			}
		}
		wp_die();
	}

	public function search_post() {
		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'viwec_nonce' ) ) ) {
			return;
		}
		$q = ! empty( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';

		if ( $q ) {
			$args  = [
				'numberposts' => - 1,
				'post_type'   => 'post',
				's'           => $q
			];
			$posts = get_posts( $args );
			if ( ! empty( $posts ) && is_array( $posts ) ) {
				$result = [];
				foreach ( $posts as $post ) {
					$result[] = [ 'id' => $post->ID, 'text' => strtoupper( $post->post_title ), 'content' => do_shortcode( $post->post_content ) ];
				}

				wp_send_json( $result );
			}
		}
		wp_die();
	}

	public function use_note( $post ) {
		if ( $post->post_type == 'viwec_template' ) {
			printf( "<p class='et-message et-info' style='margin: 7px 0 10px;'><strong>%s</strong>: %s </p>",
				esc_html__( 'Note', 'xstore-core' ),
				esc_html__( 'To display the content of the 3rd plugins (Checkout Field Editor, Flexible Checkout Fields,...), drag and drop the WC Hook element to the position which you want to display it in email template.', 'xstore-core' )
			);

		}
	}
}

