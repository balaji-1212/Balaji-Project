<?php
/**
 * Nav Menu Images Admin Functions
 *
 * @package Nav Menu Images
 * @subpackage Admin Functions
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Nav Menu Images admin functions.
 *
 * @since 1.0
 *
 * @uses Nav_Menu_Images
 */
class Nav_Menu_Images_Admin extends Nav_Menu_Images {
	/**
	 * Sets class properties.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_filter() To hook filters.
	 * @uses add_action() To hook functions.
	 */
	public function __construct() {
		// Register new AJAX thumbnail response
		add_filter( 'admin_post_thumbnail_html', array( $this, '_wp_post_thumbnail_html'   ), 10, 2 );

		// Register walker replacement
		add_action( 'wp_nav_menu_item_et_custom_fields', array( $this, 'filter_walker' ), 1, 4 );

		// Admin Menu Editor
		if ( class_exists('Dynamic_Menus') ) {
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'nav_menu_walker' ), 99 );
		}
		else {
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'nav_menu_walker' ), 999999999 );
		}

		// Register enqueuing of scripts
		add_action( 'admin_menu',                array( $this, 'register_enqueuing'        )        );

		// Register attachment fields display
		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );

		// Register attachment fields handling
		add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_to_save' ), 10, 2 );
		
		add_action( 'wp_update_nav_menu_item', array( $this, 'et_update_item_settings'), 10, 3 );

    	if ( !is_customize_preview() ) { add_filter( 'wp_setup_nav_menu_item', array( $this, 'nav_item_filter' ), 10, 1); }

    	// add_action('wp_setup_nav_menu_item', 'nav_custom_save' );

	}
	
	function nav_item_filter($item) {
	//Alter the title:
	// $original_object = get_post( $item->object_id );
    /** This filter is documented in wp-includes/post-template.php */
    // $original_title = apply_filters( 'the_title', $original_object->post_title, $original_object->ID );
	
    $item->type_label = '8Theme '. ucwords(strtolower( $item->type_label )) .' Options';

    return $item; 

	}

	public static function nav_menu_walker( $walker ) {
		global $wp_version;

		// $this->load_textdomain();

		require_once dirname( __FILE__ ) . '/walker.php';

		return 'NMI_Walker_Nav_Menu_Edit';
	}
	
	public function et_update_item_settings($menu_id, $menu_item_db_id, $args) {

		$fields = et_get_menu_fields();

		foreach ($fields as $field) {
			$id = $field['id'];
			$key = 'menu-item-' . $id;
			if( $field['type'] == 'checkbox') {
				if ( isset( $_REQUEST[$key] ) && is_array( $_REQUEST[$key]) ) {
					// $value = @$_REQUEST[$key][$menu_item_db_id];

					$value = ( isset( $_REQUEST[$key] ) && isset( $_REQUEST[$key][$menu_item_db_id] ) ) ? $_REQUEST[$key][$menu_item_db_id] : 0 ;

					if(!isset($value) && $field['type'] == 'checkbox') $value = 0;
				} else {
					$value = 0;
				}
				update_post_meta( $menu_item_db_id, '_' . $key, $value );
				unset($value);
			} elseif ( isset( $_REQUEST[$key] ) && is_array( $_REQUEST[$key] ) ) {
				// $value = @$_REQUEST[$key][$menu_item_db_id];
//				write_log($_REQUEST[$key]);

				$value = ( isset( $_REQUEST[$key] ) && isset( $_REQUEST[$key][$menu_item_db_id] ) ) ? $_REQUEST[$key][$menu_item_db_id] : 0 ;
				
				if(!isset($value) && $field['type'] == 'checkbox') $value = 0;
				update_post_meta( $menu_item_db_id, '_' . $key, $value );
				unset($value);
			}
		}
	}

	/**
	 * Register script enqueuing on nav menu page.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_action() To hook function.
	 */
	public function register_enqueuing() {
		add_action( 'admin_print_scripts-nav-menus.php', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue necessary scripts.
	 * 
	 * @since 1.0
	 * @access public
	 *
	 * @global $wp_version.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses wp_enqueue_script() To enqueue scripts.
	 * @uses plugins_url() To get URL of the file.
	 * @uses wp_localize_script() To add script's variables.
	 * @uses add_thickbox() To enqueue Thickbox style & script.
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_enqueue_media() To load media view templates, scripts & styles.
	 * @uses do_action() Calls 'nmi_enqueue_scripts'.
	 */
	public function enqueue_scripts() {
		global $wp_version;

		// Load translations
		$this->load_textdomain();

		// Enqueue old script
		wp_enqueue_script( 'nmi-scripts', ETHEME_CODE_3D_URI . 'menu-images/assets/js/nmi.js', array( 'media-upload', 'thickbox' ), '1', true );
		wp_localize_script( 'nmi-scripts', 'nmi_vars', array(
				'alert' => esc_html__( 'You need to set an image as a featured image to be able to use it as an menu item image', 'xstore' )
			)
		);
		add_thickbox();

		// For WP 3.5+, enqueue new script & dependicies
		if ( version_compare( $wp_version, '3.5', '>=' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'nmi-media-view', ETHEME_CODE_3D_URI . 'menu-images/assets/js/media-view.js', array( 'jquery', 'media-editor', 'media-views', 'post' ), '1.1', true );
		}

		do_action( 'nmi_enqueue_scripts' );
	}

	public static function filter_walker( $id, $item, $depth, $args ) { 

		// Form upload link
		$output = '';

		// Form upload link
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $id,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425'
		);
		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );
		

		// Hidden field with item's ID
		$output .= '<input type="hidden" name="nmi_item_id" id="nmi_item_id" value="' . esc_attr( $id ) . '" />';

		// Append menu item upload link
		$output .= '<div class="nmi-other-fields">';

		$output .= '<div class="et_item-popup"><div class="et_modal-header"><span class="title-heading">Menu options</span><i class="et-close-modal et-saveItem"></i></div><div class="et_popup-content">';

		$output .= '<div class="et-loader">
                    <svg viewBox="0 0 187.3 93.7" preserveAspectRatio="xMidYMid meet">
                        <path stroke="#ededed" class="outline" fill="none" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
                        <path class="outline-bg" opacity="0.05" fill="none" stroke="#ededed" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
                    </svg>
                </div>';
        $output .= '<p class="description">Menu id '.$id.'</p>';
		$et_menu_fields = new ET_Menu_Fields();
		$output .= $et_menu_fields->menu_render_fields($id);
		
		// Append menu item upload link
		$loader = '<div class="et-loader"><svg class="loader-circular" viewBox="25 25 50 50"><circle class="loader-path" cx="50" cy="50" r="14" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg></div>';
		$output .= '</div><div class="et_modal-footer"><span class="et_save-changes et-saveItem button-primary">'.$loader.' Save changes</span></div></div></div><div class="nmi-upload-link nmi-div field-level-0 field-level-1 field-level-2" style="display: none;">';
		// Generate menu item image & link's text string
		if ( has_post_thumbnail( $id ) ) {
			$post_thumbnail = get_the_post_thumbnail( $id, 'thumb' );
			$output .= '<div class="nmi-current-image nmi-div"><a href="' . $upload_url . '" data-id="' . $id . '" class="thickbox add_media">' . $post_thumbnail . '</a></div>';
			$link_text = esc_html__( 'Change menu item image', 'xstore' );

			// For WP 3.5+, add 'remove' action link
			$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $id );
			$remove_link = ' | <a href="#" data-id="' . $id . '" class="nmi_remove" onclick="NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $id . ');return false;">' . esc_html__( 'Remove menu item image', 'xstore' ) . '</a>';
		
		} else {
			$output .= '<div class="nmi-current-image nmi-div"></div>';
			$link_text = esc_html__( 'Upload menu item image', 'xstore' );
		}


		// Append menu item upload link
		$output .= '<a href="' . $upload_url . '" data-id="' . $id . '" class="thickbox add_media">' . esc_html( $link_text ) . '</a>';
		// Append menu item 'remove' link
		if ( isset( $remove_link ) )
			$output .= $remove_link;

		// Close menu item
		$output .= '</div>';

		do_action_ref_array( 'nmi_menu_item_walker_output', array( &$output, $item, $depth, $args ) );

		// Prepare general settings
		$settings = array();

		// Prepare post specific settings
		$post = null;
		if ( isset( $id ) ) {
			$post = get_post( $id );
			$settings['post'] = array(
				'id' => $post->ID,
				'nonce' => wp_create_nonce( 'update-post_' . $post->ID ),
			);

			$featured_image_id = get_post_meta( $post->ID, '_thumbnail_id', true );
			$settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
			$settings['post']['featuredExisted'] = $featured_image_id ? 1 : -1;
		}

		// Filter item's settins
		//$settings = apply_filters( 'media_view_settings', $settings, $post );

		// Prepare Javascript varible name
		$object_name = 'nmi_settings[' . $post->ID . ']';

		// Loop through each setting and prepare it for JSON
		foreach ( (array) $settings as $key => $value ) {
			if ( ! is_scalar( $value ) )
				continue;

			$settings[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}

		// Encode settings to JSON
		$script = "$object_name = " . json_encode( $settings ) . ';';

		// If this is first item, register variable
		if ( ! did_action( 'nmi_setup_settings_var' ) ) {
			$script = "var nmi_settings = [];\n" . $script;
			do_action( 'nmi_setup_settings_var', $post->ID );
		}

		// Wrap everythig
		$output .= "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5
		$output .= "/* <![CDATA[ */\n";
		$output .= "$script\n";
		$output .= "/* ]]> */\n";
		$output .= "</script>\n";

		do_action_ref_array( 'nmi_menu_item_walker_end', array( &$output, $item, $depth, $args ) );

		echo $output; // All data escaped 
		
	}

	/**
	 * Output HTML for the post thumbnail meta-box.
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @uses get_post() To get post's object.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses esc_html__() To translate & escape string.
	 * @uses apply_filters() Calls 'nmi_admin_post_thumbnail_html' to
	 *                        overwrite returned output.
	 *
	 * @param string $content Original HTML output of the thubnail.
	 * @param int $post_id The post ID associated with the thumbnail.
	 * @return string New HTML output.
	 */
	public function _wp_post_thumbnail_html( $content, $post_id ) {
		// Check if request from this plugin
		if ( ! isset( $_REQUEST['nmi_request'] ) )
			return $content;

		// Get post object
		$post = get_post( $post_id );

		// Check if post exists and is nav menu item
		if ( ! $post || 'nav_menu_item' != $post->post_type )
			return $content;

		// Load translations
		$this->load_textdomain();

		// Form upload link
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $post->ID,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425'
		);
		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );

		// Item's featured image or plain link
		if ( has_post_thumbnail( $post->ID ) )
			$link = get_the_post_thumbnail( $post->ID, 'thumb' );
		else
			$link = esc_html__( 'Upload menu item image', 'xstore' );

		// Full link
		$content = '<a href="' . $upload_url . '" data-id="' . $post->ID . '" class="thickbox add_media">' . $link . '</a>';

		// If item didn't have image, prepend actions links
		if ( isset( $_REQUEST['thumb_was'] ) && -1 == $_REQUEST['thumb_was'] ) {
			$link_text = esc_html__( 'Change menu item image', 'xstore' );
			$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $post->ID );
			$remove_link = ' | <a href="#" data-id="' . $post->ID . '" class="nmi_remove" onclick="NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $post->ID . ');return false;">' . esc_html__( 'Remove menu item image', 'xstore' ) . '</a>';

			$actions = '<a href="' . $upload_url . '" data-id="' . $post->ID . '" class="thickbox add_media">' . $link_text . '</a>' . $remove_link;

			$content = $actions . $content;
		}

		// Filter returned HTML output
		return apply_filters( 'nmi_admin_post_thumbnail_html', $content, $post->ID );
	}

	/**
	 * Display hover & active image checkboxes.
	 *
	 * @since 3.0
	 * @access public
	 *
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses get_post_meta() To get item's hover & active images IDs.
	 * @uses checked() To set proper checkbox status.
	 * @uses apply_filters() Calls 'nmi_attachment_fields_to_edit' to
	 *                        overwrite returned form fields.
	 *
	 * @param array $form_fields Original attachment form fields.
	 * @param object $post The post object data of attachment.
	 * @return string New attachment form fields.
	 */
	function attachment_fields_to_edit( $form_fields, $post ) {
		// Display only for images
		if ( ! wp_attachment_is_image( $post->ID ) )
			return $form_fields;

		// Display only for nav menu items
		if ( 'nav_menu_item' != get_post_type( $post->post_parent ) )
			return $form_fields;

		// Load translations
		$this->load_textdomain();

		// Add "hover" checkbox
		$parent_hover_id = get_post_meta( $post->post_parent, '_nmi_hover', true );
		$is_hover = ( $parent_hover_id == $post->ID ) ? true : false;
		$is_hover_checked = checked( $is_hover, true, false );

		$form_fields['nmihover'] = array(
			'label'        => esc_html__( 'Used on hover?', 'xstore' ),
			'input'        => 'html',
			'html'         => "<input type='checkbox' class='nmi-hover-checkbox' {$is_hover_checked} name='attachments[{$post->ID}][nmihover]' id='attachments[{$post->ID}][nmihover]' data-parent='{$post->post_parent}' data-checked='{$is_hover}' />",
			'value'        => $is_hover,
			'helps'        => esc_html__( 'Should this image be used on hover', 'xstore' ),
			'show_in_edit' => false
		);

		// Add "active" checkbox
		$parent_active_id = get_post_meta( $post->post_parent, '_nmi_active', true );
		$is_active = ( $parent_active_id == $post->ID ) ? true : false;
		$is_active_checked = checked( $is_active, true, false );

		$form_fields['nmiactive'] = array(
			'label'        => esc_html__( 'Used when active?', 'xstore' ),
			'input'        => 'html',
			'html'         => "<input type='checkbox' class='nmi-active-checkbox' {$is_active_checked} name='attachments[{$post->ID}][nmiactive]' id='attachments[{$post->ID}][nmiactive]' data-parent='{$post->post_parent}' data-checked='{$is_active}' />",
			'value'        => $is_active,
			'helps'        => esc_html__( 'Should this image be used when menu item is active', 'xstore' ),
			'show_in_edit' => false
		);

		// Filter returned HTML output
		return apply_filters( 'nmi_attachment_fields_to_edit', $form_fields, $post );
	}

	/**
	 * Save hover & active image checkboxes submissions.
	 *
	 * @since 3.0
	 * @access public
	 *
	 * @uses update_post_meta() To save new item's hover or active images IDs.
	 * @uses delete_post_meta() To delete old item's hover or active images IDs.
	 *
	 * @param object $post The post object data of attachment.
	 * @param array $attachment Submitted data of attachment.
	 * @return object $post The post object data of attachment.
	 */
	function attachment_fields_to_save( $post, $attachment ) {
		// Save "hover" checkbox
		if ( 'on' == $attachment['nmihover'] )
			update_post_meta( $post['post_parent'], '_nmi_hover', $post['ID'] );
		else
			delete_post_meta( $post['post_parent'], '_nmi_hover', $post['ID'] );

		// Save "active" checkbox
		if ( 'on' == $attachment['nmiactive'] )
			update_post_meta( $post['post_parent'], '_nmi_active', $post['ID'] );
		else
			delete_post_meta( $post['post_parent'], '_nmi_active', $post['ID'] );

		return $post;  
	}
}
