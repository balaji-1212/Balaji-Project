<?php
/**
 * Reviews images functionality for products
 *
 * @package    reviews-images.php
 * @since      8.3.5
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_WooCommerce_Product_Reviews_Images {
 
    public static $option_name = 'customer_reviews';
    
    public $local_vars = [
        'comment_img_key' => ETHEME_PREFIX.'comment_image_id',
        'comment_field_name' => ETHEME_PREFIX.'comment_image'
    ];
    
    public $settings = array();
	
	/**
	 * constructor method.
	 * @since 8.3.5
	 */
	public function __construct() {
		if ( !class_exists('WooCommerce')) return;
		if ( !get_option('xstore_sales_booster_settings_'.self::$option_name.'_images') ) return;
		
	    $this->init_vars();
        $this->hooks();
	}
	
	/**
	 * Initialize main settings values based on Theme Settings.
	 *
	 * @since 8.3.5
	 *
	 * @return void
	 */
	public function init_vars() {

		$settings = (array)get_option('xstore_sales_booster_settings', array());
		
		$default = array(
			'image_size' => 1,
			'images_count' => 3,
			'images_required' => '',
            'images_lightbox' => 'on',
            'images_preview' => 'on'
		);
		
		$local_settings = $default;
		
		if (count($settings) && isset($settings[self::$option_name])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
		}

		$force_check_switchers = array(
			'images_required',
            'images_lightbox',
            'images_preview'
        );
		foreach ($force_check_switchers as $switcher) {
		    $local_settings[$switcher] = $local_settings[$switcher] == 'on';
        }
		
		$this->settings = $local_settings;
    }
    
	/**
	 * Hooks for creating/saving/removing images in comment form.
	 *
	 * @since 8.3.5
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'comment_form_submit_field', array( $this, 'upload_image_form' ), 10 );
		add_filter( 'preprocess_comment', array( $this, 'validation_images' ), 10 );
		add_action( 'comment_post', array( $this, 'save_media' ), 10, 3 );
		add_filter( 'comment_text', array( $this, 'images_output' ), 40 );
		add_action( 'delete_comment', array( $this, 'delete_media' ), 10 );
	}
	
	
	/**
	 * Gets the meta key of the attachment ID for comment meta.
	 *
	 * @param $submit_field
	 * @return string
	 *
	 * @since 8.3.5
	 *
	 */
	public function upload_image_form( $submit_field ) {
		if ( ! is_singular( 'product' ) ) {
			return $submit_field;
		}
		
		if ( ! get_query_var( 'et_is-loggedin', false) ) {
			return '<p class="et-reviews-images-message">' . esc_html__( 'You have to be logged in to be able to add photos to your review.', 'xstore' ) . '</p>' . $submit_field;
		}
		
		$name            = $this->local_vars['comment_field_name'];
		$max_upload_size = $this->get_max_upload_size( true );
		$required        = $this->settings['images_required'];
		
		wp_enqueue_script( 'et_reviews_images' );
		wp_localize_script( 'et_reviews_images', 'et_reviews_images_config', array(
			'comment_images_upload_size_text'        => sprintf( esc_html__( 'Some files are too large. Allowed file size is %s.', 'xstore' ), $max_upload_size ), // phpcs:ignore
			'comment_images_count_text'              => sprintf( esc_html__( 'You can upload up to %s images to your review.', 'xstore' ), $this->settings['images_count'] ), // phpcs:ignore
			'single_product_comment_images_required' => $required ? 'yes' : 'no', // phpcs:ignore
			'comment_required_images_error_text'     => esc_html__( 'Image is required.', 'xstore' ), // phpcs:ignore
			'comment_images_upload_mimes_text'       => sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'xstore' ), apply_filters( 'xts_comment_images_upload_mimes', 'png, jpeg' ) ), // phpcs:ignore
			'comment_images_added_count_text'        => esc_html__( 'Added %s image(s)', 'xstore' ), // phpcs:ignore
			'comment_images_upload_size'             => $this->get_max_upload_size(),
			'comment_images_count'                   => $this->settings['images_count'],
			'comment_images_preview'                 => $this->settings['images_preview'] ? 'yes' : 'no',
			'comment_images_upload_mimes'            => apply_filters(
				'etheme_comment_images_upload_mimes',
				array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'png'          => 'image/png',
				)
			),
        ) );
		
		ob_start();
		
		?>
        <div class="et-reviews-images">
            <label for="et-reviews-images-uploader">
                <span class="btn medium pointer">
                    <i class="et-icon et-plus"></i>
                    <?php echo esc_html__( 'Add images', 'xstore' ); ?>
                    <?php if ( $required ) : ?>
                        <span class="required">*</span>
                    <?php endif; ?>
                </span>
            </label>

            <span class="btn black medium pointer hidden" id="et-reviews-images-reset"><?php echo esc_html__('Reset Images', 'xstore'); ?></span>
            
            <input id="et-reviews-images-uploader" name="<?php echo esc_attr( $name ); ?>[]" type="file" multiple accept="<?php echo implode(',', array_values(apply_filters(
                'etheme_comment_images_upload_mimes',
                array(
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'png'          => 'image/png',
                )
            ))); ?>" <?php if($required) echo 'required'; ?> />
            
            <small class="et-reviews-images-info block">
                <?php printf( esc_html__( 'You may upload up to %s images. Maximum image size is %s.', 'xstore' ), $this->settings['images_count'], $max_upload_size ); // phpcs:ignore ?>
            </small>

            <div class="et-reviews-images-message et-reviews-images-count"></div>

            <?php if ( $this->settings['images_preview'] ) : ?>
                <div class="et-reviews-images-previewer"></div>
            <?php endif; ?>
        </div>
		<?php
		
		$file_field = ob_get_clean();
		
		return $file_field . $submit_field;
	}
	
	
	/**
	 * Checks the attachment before posting a comment.
	 *
	 * @param $comment_data
	 * @return mixed
	 *
	 * @since 8.3.5
	 *
	 */
	public function validation_images( $comment_data ) {
        if ( !is_user_logged_in() ) {
			return $comment_data;
		}
		
		$field_name   = $this->local_vars['comment_field_name'];
		$images_count = $this->settings['images_count'];
		
		if ( ! isset( $_FILES[ $field_name ] ) ) {
			return $comment_data;
		}
		
		$attachments = $_FILES[ $field_name ]; // phpcs:ignore
		$names       = $attachments['name'];
		
		if ( is_array( $names ) && count( $names ) > $images_count ) {
			wp_die( sprintf( esc_html__( 'You can upload up to %s images to your review.', 'xstore' ), $images_count ) ); // phpcs:ignore
		}
		
		foreach ( $attachments['size'] as $size ) {
			if ( $size > $this->get_max_upload_size() ) {
				wp_die( sprintf( esc_html__( 'The maximum upload file size: %s.', 'xstore' ), $this->get_max_upload_size( true ) ) ); // phpcs:ignore
			}
		}
		
		if ( $this->settings['images_required'] ) {
			$this->allow_media_types();
			foreach ( $names as $name ) {
				$filetype = wp_check_filetype( $name );
				
				if ( ! $filetype['ext'] ) {
					wp_die( sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'xstore' ), apply_filters( 'etheme_comment_images_upload_mimes', implode(',', array_keys(array(
						'jpg|jpeg|jpe' => 'image/jpeg',
						'png'          => 'image/png',
					)) ) ) ) ); // phpcs:ignore
				}
			}
			$this->disallow_media_types();
		}
		
		return $comment_data;
	}
	
	
	/**
	 * Deletes an assigned attachment.
	 *
	 * @param $comment_id
	 * @return void
	 *
	 * @since 8.3.5
	 *
	 */
	public function delete_media( $comment_id ) {
		if ( ! $this->check_existed_images( $comment_id ) ) {
			return;
		}
		
		$image_ids = $this->get_image_ids_array( $comment_id );
		$meta_key  = $this->local_vars['comment_img_key'];
		
		foreach ( $image_ids as $id ) {
			wp_delete_attachment( $id, true );
		}
		
		delete_comment_meta( $comment_id, $meta_key );
	}
	
	/**
	 * Saves attachment after comment is posted.
	 *
	 * @param $comment_id
	 * @param $comment_approved
	 * @param $comment
	 * @return void
	 *
	 * @since 8.3.5
	 *
	 */
	public function save_media( $comment_id, $comment_approved, $comment ) {
        if ( !is_user_logged_in() ) {
			return;
		}
		
		$field_name = $this->local_vars['comment_field_name'];
		
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}
		
		$image_ids = '';
		
		if ( $_FILES ) {
			$files = $_FILES[ $field_name ]; // phpcs:ignore
			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ],
					);
					
					$_FILES = array( $field_name => $file );
					
					add_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );
					$attachment_id = media_handle_upload( $field_name, $comment['comment_post_ID'] );
					remove_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );
					
					if ( ! is_wp_error( $attachment_id ) ) {
						$image_ids .= $attachment_id . ',';
					}
				}
			}
			
			$this->assign_images( $comment_id, $image_ids );
		}
	}
	
	/**
	 * Filter images which are allowed to upload.
	 *
	 * @since 8.3.5
	 *
	 * @return void
	 */
	public function allow_media_types() {
		add_filter( 'upload_mimes', array( $this, 'filter_upload_mimes' ), 200 );
	}
	
	/**
	 * Disable filter images which are allowed to upload.
	 *
	 * @since 8.3.5
	 *
	 * @return void
	 */
	public function disallow_media_types() {
		remove_filter( 'upload_mimes', array( $this, 'filter_upload_mimes' ), 200 );
	}
	
	/**
	 * Set up array of allowed image types to upload.
	 *
	 * @since 8.3.5
	 *
	 * @return mixed
	 */
	public function filter_upload_mimes() {
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
		);
		
		return apply_filters( 'etheme_comment_images_upload_mimes', $mimes );
	}
	
	/**
	 * Gets max upload file size.
	 *
	 * @since 8.3.5
	 *
	 * @param bool $with_format Optional.
	 *
	 * @return integer|string
	 */
	/**
	 * Set max upload image size (calculated).
	 *
	 * @param false $formatted
	 * @return float|int
	 *
	 * @since 8.3.5
	 *
	 */
	public function get_max_upload_size( $formatted = false ) {
		$max_upload_size = (int) $this->settings['image_size'] * MB_IN_BYTES;
		
		if ( $formatted )
			return size_format( $max_upload_size );
		
		return $max_upload_size;
	}
	
	/**
	 * Check if images are set for such comment id (taken from param).
	 *
	 * @param int $comment_id
	 * @return bool
	 *
	 * @since 8.3.5
	 *
	 */
	public function check_existed_images( $comment_id = 0 ) {
		if ( ! $comment_id ) {
			$comment_id = $this->get_comment_ID();
		}
		
		$attachment_ids = $this->get_image_ids_array( $comment_id );
		
		if ( ! $attachment_ids ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get comment images if those exist.
	 *
	 * @param int $comment_id
	 * @return mixed
	 *
	 * @since 8.3.5
	 *
	 */
	public function get_image_ids_meta( $comment_id = 0 ) {
		$meta_key = $this->local_vars['comment_img_key'];
		
		if ( ! $comment_id ) {
			$comment_id = $this->get_comment_ID();
		}
		
		return get_comment_meta( $comment_id, $meta_key, true );
	}
	
	/**
	 * Gets all ids of images uploaded for comment (comment id as param).
	 *
	 * @param int $comment_id
	 * @return array|false|string[]
	 *
	 * @since 8.3.5
	 *
	 */
	public function get_image_ids_array( $comment_id = 0 ) {
	    $ids = $this->get_image_ids_meta( $comment_id );
		$ids = $ids != '' ? explode( ',', $this->get_image_ids_meta( $comment_id ) ) : array();
		
		return $ids;
	}
	
	/**
	 * Return comment id.
	 *
	 * @since 8.3.5
	 *
	 * @return string
	 */
	public function get_comment_ID() {
		$comment = get_comment();
		
		if ( ! $comment ) {
			return '';
		}
		
		return $comment->comment_ID;
	}
	
	/**
	 * Display images under comment.
	 *
	 * @param $comment_content
	 * @return string
	 *
	 * @since 8.3.5
	 *
	 */
	public function images_output( $comment_content ) {
		if ( ! $this->check_existed_images() || is_admin() || ! is_singular( 'product' ) ) {
			return $comment_content;
		}
		
		$image = '';
		$ids = $this->get_image_ids_array();
		
		if ( count( $ids ) ) {
			
			$image = '<div class="review-images">';
			foreach ($ids as $id) {
			    if ( $id ) {
			        if ( $this->settings['images_lightbox']) {
				        $src = wp_get_attachment_image_src($id, 'full');
				        $image .= '<a href="'.$src[0].'" rel="lightbox">';
				            $image .= wp_get_attachment_image($id, 'thumbnail');
				        $image .= '</a>';
			        }
			        else
				        $image .= wp_get_attachment_image($id, 'thumbnail');
                }
			}
			
			$image .= '</div>';
			
        }
		
		return $comment_content . $image;
	}
	
	/**
	 * Makes comment image force thumbnail size.
	 *
	 * @since 8.3.5
	 *
	 * @return string[]
	 */
	public function get_image_sizes() {
		$sizes = array( 'thumbnail' );
		return $sizes;
	}
	
	/**
	 * Assigns an attachment for the comment.
	 *
	 * @param $comment_id
	 * @param $image_ids
	 * @return mixed
	 *
	 * @since 8.3.5
	 *
	 */
	public function assign_images( $comment_id, $image_ids ) {
		$meta_key = $this->local_vars['comment_img_key'];
		
		return update_comment_meta( $comment_id, $meta_key, $image_ids );
	}
}

new Etheme_WooCommerce_Product_Reviews_Images();