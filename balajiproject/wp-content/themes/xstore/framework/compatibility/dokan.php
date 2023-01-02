<?php
/**
 * Description
 *
 * @package    dokan.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

$dokan_compatibles_actions = array(
	'start_wrap' => array(
		'dokan_edit_product_wrap_before',
		'dokan_dashboard_wrap_before',
	),
	'end_wrap' => array(
		'dokan_edit_product_wrap_after',
		'dokan_dashboard_wrap_after'
	),
);

foreach ($dokan_compatibles_actions['start_wrap'] as $key => $value) {
	add_action($value, function(){ ?>
		<div class="container content-page sidebar-mobile-bottom">
			<div class="sidebar-position-without">
				<div class="row">
					<div class="content col-md-12">
	<?php });
}

foreach ($dokan_compatibles_actions['end_wrap'] as $key => $value) {
	add_action($value, function(){ ?>
					</div>
				</div>
			</div>
		</div>
	<?php });
}

add_action( 'wp', function () {
	// Remove it after global lazy finish
	if ( ! is_admin() ) {
		if ( defined( 'ET_CORE_VERSION' ) ) {
			
			// tweak for dokan img attributes with lazy load
			add_filter( 'dokan_product_image_attributes', function ( $attr ) {
				$attr['img'] = array_merge( $attr['img'], array(
					'data-src'    => array(),
					'data-sizes'  => array(),
					'data-srcset' => array(),
					'srcset'      => array(),
				) );
				
				return $attr;
			} );
			
		}
		
		if ( !get_query_var( 'et_is-loggedin', false) ) {
			global $post;
			$dokan_registration_page_shortcode = ( ! empty( $post->post_content ) && strstr( $post->post_content, '[dokan-vendor-registration' ) );
			if ( $dokan_registration_page_shortcode ) {
				add_filter( 'etheme_mini_account_content_type', function () {
					return 'none';
				} );
			}
		}
	}
	
} );

/*
* Adding extra field on New product popup/without popup form
*/

add_action( 'dokan_new_product_after_product_tags', 'etheme_dokan_new_product_field', 10 );

if ( !function_exists('etheme_dokan_new_product_field') ) {
    function etheme_dokan_new_product_field(){ ?>
    
        <?php if ( class_exists('Woocommerce') && get_theme_mod('enable_brands', true) ) : ?>
        
            <div class="dokan-form-group">
                <label for="brand" class="form-label"><?php esc_html_e( 'Brands', 'xstore' ); ?></label>
                <?php
        
                require_once DOKAN_LIB_DIR.'/class.taxonomy-walker.php';
                
                $drop_down_tags = wp_dropdown_categories( array(
                    'show_option_none' => '',
                    'hierarchical'     => 1,
                    'hide_empty'       => 0,
                    'name'             => 'brand[]',
                    'id'               => 'brand',
                    'taxonomy'         => 'brand',
                    'title_li'         => '',
                    'class'            => 'brand dokan-form-control dokan-select2',
                    'exclude'          => '',
                    'selected'         => array(),
                    'echo'             => 0,
                    'walker'           => new WeDevs\Dokan\Walkers\TaxonomyDropdown()
                ) );
        
                echo str_replace( '<select', '<select data-placeholder="' . esc_html__( 'Select product brands', 'xstore' ) . '" multiple="multiple" ', $drop_down_tags ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
                
                ?>
                
            </div>
        
        <?php endif; ?>
        
        <?php // youtube video ?>
        
        <div class="dokan-form-group">
            <label for="et_video_code" class="form-label"><?php esc_html_e('Product video', 'xstore'); ?></label>
            <textarea name="et_video_code" id="et_video_code" rows="7" class="dokan-form-control" placeholder="<?php esc_attr_e('You can use YouTube or Vimeo iframe code', 'xstore'); ?>"></textarea>
        </div>
        
        <?php
    }
}

/*
* Saving product field data for edit and update
*/

add_action( 'dokan_new_product_added', 'etheme_dokan_save_add_product_meta', 10, 2 );
add_action( 'dokan_product_updated', 'etheme_dokan_save_add_product_meta', 10, 2 );

if ( !function_exists('etheme_dokan_save_add_product_meta')) {
	function etheme_dokan_save_add_product_meta( $product_id, $data ) {
		
		if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
			return;
		}
		
		if ( isset( $data['brand'] ) && ! empty( $data['brand'] ) ) {
			$tags_ids = array_map( 'absint', (array) $data['brand'] );
			wp_set_object_terms( $product_id, $tags_ids, 'brand' );
		}
		if ( isset( $data['et_video_code'] ) ) {
			update_post_meta( $product_id, '_product_video_code', $data['et_video_code'] );
		}
	}
}

/*
* Showing field data on product edit page
*/
add_action( 'dokan_product_edit_after_product_tags', 'etheme_dokan_show_on_edit_page', 99, 2 );

if ( !function_exists('etheme_dokan_show_on_edit_page')) {
    function etheme_dokan_show_on_edit_page($post, $post_id){
        
        if ( class_exists('Woocommerce') && get_theme_mod('enable_brands', true) ) : ?>
        
            <div class="dokan-form-group">
                <label for="brand" class="form-label"><?php esc_html_e( 'Brands', 'xstore' ); ?></label>
            <?php
            
            require_once DOKAN_LIB_DIR.'/class.taxonomy-walker.php';
            
            $term = wp_get_post_terms( $post_id, 'brand', array( 'fields' => 'ids') );
            
            $selected = ( $term ) ? $term : array();
            $drop_down_tags = wp_dropdown_categories( array(
                'show_option_none' => '',
                'hierarchical'     => 1,
                'hide_empty'       => 0,
                'name'             => 'brand[]',
                'id'               => 'brand',
                'taxonomy'         => 'brand',
                'title_li'         => '',
                'class'            => 'brand dokan-form-control dokan-select2',
                'exclude'          => '',
                'selected'         => $selected,
                'echo'             => 0,
                'walker'           => new WeDevs\Dokan\Walkers\TaxonomyDropdown( $post_id )
            ) );
            
            echo str_replace( '<select', '<select data-placeholder="' . esc_html__( 'Select product brands', 'xstore' ) . '" multiple="multiple" ', $drop_down_tags ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
            
            ?>
        
            </div>
        
        <?php endif; ?>
        <?php // youtube video ?>
    
        <div class="dokan-form-group">
            <label for="et_video_code" class="form-label"><?php esc_html_e('Product video', 'xstore'); ?></label>
            <textarea name="et_video_code" id="et_video_code" rows="7" class="dokan-form-control" placeholder="<?php esc_attr_e('You can use YouTube or Vimeo iframe code', 'xstore'); ?>"><?php echo get_post_meta( $post_id, '_product_video_code', true ); ?></textarea>
        </div>
        
        <?php
    }
}