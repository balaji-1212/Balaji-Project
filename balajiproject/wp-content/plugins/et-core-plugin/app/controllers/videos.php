<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Import controller.
 *
 * @since      2.3.4
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/Controller
 */
class Videos extends Base_Controller {
	/**
	 * Add metaboxes actions.
	 *
	 * @since   2.3.4
	 * @version 1.0.0
	 *
	 * @var array
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'product_meta_boxes' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'etheme_save_video_meta' ));
	}

	/**
	 * Save metaboxes action.
	 *
	 * @since   2.3.4
	 * @version 1.0.0
	 *
	 */
	public function etheme_save_video_meta($post_id) {
		// Gallery Images
		$video_ids =  explode( ',',  $_POST['product_video_gallery']  ) ;
		update_post_meta( $post_id, '_product_video_gallery', implode( ',', $video_ids ) );
		update_post_meta( $post_id, '_product_video_code',  $_POST['et_video_code']  );
		if ( isset($_POST['et_product_video_autoplay']) && $_POST['et_product_video_autoplay'] != '' ){
			update_post_meta( $post_id, '_product_video_autoplay',  $_POST['et_product_video_autoplay']  );
		} else {
			update_post_meta( $post_id, '_product_video_autoplay',  false  );
		}
	}

	/**
	 * Metaboxes html callback.
	 *
	 * @since   2.3.4
	 * @version 1.0.0
	 *
	 */
	public
	function callback() {
		$this->get_view()->product_video_box();
	}

	/**
	 * Add product metaboxes.
	 *
	 * @since   2.3.4
	 * @version 1.0.0
	 *
	 */
	function product_meta_boxes() {
		add_meta_box( 'woocommerce-product-videos', esc_html__( 'Product Video', 'xstore-core' ), array( $this, 'callback' ), 'product', 'side' );
	}
}