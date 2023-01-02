<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Create post type controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Post_Types extends Base_Controller{

	public $domain = 'xstore-core';

    /**
     * Registered panels.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $post_args = NULL;

    /**
     * Registered panels.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $tax_args = NULL;

    /**
     * Register post args
     *
     * @return mixed|null|void
     */
    public static function register_post_args() {

        if ( ! is_null( self::$post_args ) ) {
            return self::$post_args;
        }

        return self::$post_args = apply_filters( 'etc/add/post/args', array() );
    }

    /**
     * Register taxonomies args
     *
     * @return mixed|null|void
     */
    public static function register_taxonomies_args() {

        if ( ! is_null( self::$tax_args ) ) {
            return self::$tax_args;
        }

        return self::$tax_args = apply_filters( 'etc/add/tax/args', array() );
    }


	public function hooks() {

		add_action( 'init', array( $this, 'create_custom_post_types' ), 1 );
		add_action( 'init', array( $this, 'create_taxonomies' ), 1 );
		add_filter( 'post_type_link', array( $this, 'portfolio_post_type_link' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'custom_type_settings' ) );
		add_action( 'load-options-permalink.php', array( $this,'seatings_for_permalink') );
		add_filter( 'manage_staticblocks_posts_columns', array( $this, 'et_staticblocks_columns' ) );
		add_action( 'manage_staticblocks_posts_custom_column', array( $this, 'et_staticblocks_columns_val' ), 10, 2 );

		add_action( 'brand_add_form_fields', array( $this, 'add_brand_fileds') );
		add_action( 'brand_edit_form_fields', array( $this, 'edit_brand_fields' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'brand_admin_scripts' ) );
		add_action( 'created_term', array( $this, 'brands_fields_save' ), 10,3 );
		add_action( 'edit_term', array( $this, 'brands_fields_save' ), 10,3 );
	}

    /**
     * Create post types
     * @return null
     */
    public function create_custom_post_types() {
        $args = self::register_post_args();

        foreach ( $args as $fields ) {
            $this->get_model()->register_single_post_type( $fields );

        }

    }

    /**
     * Create post types
     * @return null
     */
    public function create_taxonomies() {
        $args = self::register_taxonomies_args();

        foreach ( $args as $fields ) {

            $this->get_model()->register_single_post_type_taxnonomy( $fields );

        }

    }

	public function portfolio_post_type_link( $permalink, $post ) {
		/**
		 *
		 * Add support for portfolio link custom structure.
		 *
		 */
		if ( $post->post_type != 'etheme_portfolio' ) {
			return $permalink;
		}


		if ( false === strpos( $permalink, '%' ) ) {
			return $permalink;
		}

		// Get the custom taxonomy terms of this post.
		$terms = get_the_terms( $post->ID, 'portfolio_category' );

		if ( ! empty( $terms ) ) {
			$terms = wp_list_sort( $terms, 'ID' );  // order by ID

			$category_object = apply_filters( 'portfolio_post_type_link_portfolio_cat', $terms[0], $terms, $post );
			$category_object = get_term( $category_object, 'portfolio_category' );
			$portfolio_category     = $category_object->slug;

			if ( $category_object->parent ) {
				$ancestors = get_ancestors( $category_object->term_id, 'portfolio_category' );
				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, 'portfolio_category' );
					$portfolio_category     = $ancestor_object->slug . '/' . $portfolio_category;
				}
			}
		} else {
			$portfolio_category = esc_html__( 'uncategorized', 'xstore-core' );
		}

		if ( strpos( $permalink, '%author%' ) != false ) {
			$authordata = get_userdata( $post->post_author );
			$author = $authordata->user_nicename;
		} else {
			$author = '';
		}

		$find = array(
			'%year%',
			'%monthnum%',
			'%day%',
			'%hour%',
			'%minute%',
			'%second%',
			'%post_id%',
			'%author%',
			'%category%',
			'%portfolio_category%'
		);

		$replace = array(
			date_i18n( 'Y', strtotime( $post->post_date ) ),
			date_i18n( 'm', strtotime( $post->post_date ) ),
			date_i18n( 'd', strtotime( $post->post_date ) ),
			date_i18n( 'H', strtotime( $post->post_date ) ),
			date_i18n( 'i', strtotime( $post->post_date ) ),
			date_i18n( 's', strtotime( $post->post_date ) ),
			$post->ID,
			$author,
			$portfolio_category,
			$portfolio_category
		);

		$permalink = str_replace( $find, $replace, $permalink );

		return $permalink;
	}

	public function et_staticblocks_columns($defaults) {
	    return array(
	    	'cb'               => '<input type="checkbox" />',
	        'title'            => esc_html__( 'Title', 'xstore-core' ),
	        'shortcode_column' => esc_html__( 'Shortcode', 'xstore-core' ),
	        'date'             => esc_html__( 'Date', 'xstore-core' ),
	    );
	}
	 
	public function et_staticblocks_columns_val($column_name, $post_ID) {
	   if ($column_name == 'shortcode_column') {
            echo '[block id="'.$post_ID.'"]';
	   }
	}

	public function custom_type_settings() {

		/**
		 *
		 * Add Etheme section block to permalink setting page.
		 *
		 */
		if( get_theme_mod('portfolio_projects', true) || get_theme_mod('enable_brands', true) ){
			add_settings_section(
				'et_section',
				esc_html__( '8theme permalink settings' , 'xstore-core' ),
				array( $this, 'section_callback' ),
				'permalink'
			);
		}

		/**
		 *
		 * Add "Brand base" setting field to Etheme section block.
		 *
		 */
		if ( class_exists('Woocommerce') && get_theme_mod('enable_brands', true) ) {
			add_settings_field(
				'brand_base',
				esc_html__( 'Brand base' , 'xstore-core' ),
				array( $this, 'brand_callback' ),
				'permalink',
				'optional'
			);
		}

		if( get_theme_mod('portfolio_projects', true) ){
			/**
			 *
			 * Add "Portfolio base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_base',
				esc_html__( 'Portfolio base' , 'xstore-core' ),
				array( $this, 'portfolio_callback' ),
				'permalink',
				'optional'
			);

			/**
			 *
			 * Add "Portfolio category base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_cat_base',
				esc_html__( 'Portfolio category base' , 'xstore-core' ),
				array( $this, 'portfolio_cat_callback' ),
				'permalink',
				'optional'
			);
		}
	}


	public function section_callback() {
		/**
		 *
		 * Callback function for Etheme section block.
		 *
		 */

		$checked['portfolio_def'] = ( get_option( 'et_permalink' ) == 'portfolio_def' || ! get_option( 'et_permalink' ) ) ? 'checked' : '';
		$checked['portfolio_cat_base'] = ( get_option( 'et_permalink' ) == 'portfolio_cat_base' ) ? 'checked' : '';
		$checked['portfolio_custom_base'] = ( get_option( 'et_permalink' ) == 'portfolio_custom_base' ) ? 'checked' : '';

		if ( class_exists('Woocommerce') && get_theme_mod('enable_brands', true) ) {
			$shop_url = get_permalink( wc_get_page_id( 'shop' ) ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url() . '/shop/';
			$checked['brand_def'] = ( get_option( 'et_brand_permalink' ) == 'brand_def' || ! get_option( 'et_brand_permalink' ) ) ? 'checked' : '';
			$checked['brand_shop_base'] = ( get_option( 'et_brand_permalink' ) == 'brand_shop_base' || ! get_option( 'et_brand_permalink' ) ) ? 'checked' : '';
			$checked['brand_custom_base'] = ( get_option( 'et_brand_permalink' ) == 'brand_custom_base' ) ? 'checked' : '';

			echo '
				<p>' . esc_html__( '8theme brand permalink settings.' , 'xstore-core' ) . '</p>
				</tbody></tr></th>
				<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><input class="et-inp-brand" type="radio" name="et_brand_permalink" value="brand_def" ' . $checked['brand_def'] . ' >' . esc_html__( 'Default' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/brand-base/brand-archive/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input class="et-inp-brand" type="radio" name="et_brand_permalink" value="brand_shop_base" ' . $checked['brand_shop_base'] . '>' . esc_html__( 'Shop page base' , 'xstore-core' ) . '</label></th>
								<td><code>' . $shop_url . 'brand-base/brand-archive/</code></td>
								<input type="hidden" id="brand-custom-base" name="brand_custom_base" value="' . get_option( 'brand_custom_base' ) . '">
							</tr>
							
						</tbody>
				</table> 
			';
		}

		if( get_theme_mod('portfolio_projects', true) || get_theme_mod('enable_brands', true) ){
			echo '
				<p>' . __( '8theme portfolio permalink settings.' , 'xstore-core' ) . '</p>
				</tbody></tr></th>
				<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_def" ' . $checked['portfolio_def'] . ' >' . esc_html__( 'Default' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_cat_base" ' . $checked['portfolio_cat_base'] . '>' . esc_html__( 'Portfolio category base' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/portfolio-category/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input id="portfolio-custom-base-select" type="radio" name="et_permalink" value="portfolio_custom_base" ' . $checked['portfolio_custom_base'] . '>' . esc_html__( 'Portfolio custom Base' , 'xstore-core' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base</code><input id="portfolio-custom-base" name="portfolio_custom_base" type="text" value="' . get_option( 'portfolio_custom_base' ) . '" class="regular-text code" /></td>
							</tr>
						</tbody>
				</table>

				<script type="text/javascript">
					jQuery( function() {
						jQuery("input.et-inp, input.et-inp-brand").change(function() {
							
							var link = "";

							if ( jQuery( this ).val() == "portfolio_cat_base" ) {
								link = "/%portfolio_category%";
							} else if ( jQuery( this ).val() == "brand_shop_base" ) {
								link = "' . basename( $shop_url ) . '";
							} else {
								link = "";
							}
							
							if ( jQuery( this ).is( ".et-inp-brand" ) ){
								jQuery("#brand-custom-base").val( link );
							} else {
								jQuery("#portfolio-custom-base").val( link );
							}
						});

						jQuery("input:checked").change();
						jQuery("#portfolio-custom-base").focus( function(){
							jQuery("#portfolio-custom-base-select").click();
						} );
					} );
				</script>

				'
			;
		}
	}


	public function portfolio_callback() {
		/**
		 *
		 * Callback function for "portfolio base" setting field.
		 *
		 */

		echo '<input 
			name="portfolio_base"  
			type="text" 
			value="' . get_option( 'portfolio_base' ) . '" 
			class="regular-text code"
			placeholder="project"
		 />';
	}

	public function brand_callback() {
		/**
		 *
		 * Callback function for "brand base" setting field.
		 *
		 */

		echo '<input 
			name="brand_base"  
			type="text" 
			value="' . get_option( 'brand_base' ) . '" 
			class="regular-text code"
			placeholder="brand"
		 />';
	}

	public function portfolio_cat_callback() {
		/**
		 *
		 * Callback function for "portfolio catogory base" setting field.
		 *
		 */

		echo '<input 
			name="portfolio_cat_base"  
			type="text" 
			value="' . get_option( 'portfolio_cat_base' ) . '" 
			class="regular-text code"
			placeholder="portfolio-category"
		 />';
	}


	public function seatings_for_permalink() {
		/**
		 *
		 * Make it work on permalink page.
		 *
		 */
		if ( ! is_admin() ) {
			return;
		}

		if( isset( $_POST['brand_base'] ) ) {
			update_option( 'brand_base', sanitize_title_with_dashes( $_POST['brand_base'] ) );
		}

		if( isset( $_POST['portfolio_base'] ) ) {
			update_option( 'portfolio_base', sanitize_title_with_dashes( $_POST['portfolio_base'] ) );
		}

		if( isset( $_POST['portfolio_cat_base'] ) ) {
			update_option( 'portfolio_cat_base', sanitize_title_with_dashes( $_POST['portfolio_cat_base'] ) );
		}

		if( isset( $_POST['et_permalink'] ) ) {
			update_option( 'et_permalink', sanitize_title_with_dashes( $_POST['et_permalink'] ) );
		}

		if( isset( $_POST['portfolio_custom_base'] ) ) {
			update_option( 'portfolio_custom_base', $_POST['portfolio_custom_base'] );
		}

		if( isset( $_POST['et_brand_permalink'] ) ) {
			update_option( 'et_brand_permalink', sanitize_title_with_dashes( $_POST['et_brand_permalink'] ) );
		}

		if( isset( $_POST['brand_custom_base'] ) ) {
			update_option( 'brand_custom_base', sanitize_title_with_dashes( $_POST['brand_custom_base'] ) );
		}
	}

	/**
	 * Product brands image filed description
	 * @return [type] [description]
	 */
	public function add_brand_fileds() {

		$this->view->add_brand_fileds(
			array(
				'thumbnail'   			  =>	esc_html__( 'Thumbnail', 'xstore-core' ),
				'upload'      			  =>	esc_html__( 'Upload/Add image', 'xstore-core' ),
				'remove'      			  =>	esc_html__( 'Remove image', 'xstore-core' ),
			)
		);

	}

	/**
	 * Product brands edit single tax image filed
	 * @param  [type] $term     [description]
	 * @param  [type] $taxonomy [description]
	 * @return [type]           [description]
	 */
	public function edit_brand_fields($term, $taxonomy ) {
    	$thumbnail_id 	= absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
    	
    	$image = ( $thumbnail_id ) ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();

    	
		$this->view->edit_brand_fields(
			array(
				'thumbnail'   		=>	esc_html__( 'Thumbnail', 'xstore-core' ),
				'upload'      		=>	esc_html__( 'Upload/Add image', 'xstore-core' ),
				'remove'      		=>	esc_html__( 'Remove image', 'xstore-core' ),
				'thumbnail_id'      =>	$thumbnail_id,
				'image'      		=>	$image,
			)
		);
    }

    /**
     * Product brands enqueue media for image selector
     * @return [type] [description]
     */
	public function brand_admin_scripts() {
        $screen = get_current_screen();
        if ( in_array( $screen->id, array('edit-brand') ) ){
			wp_enqueue_media();
        }
    }

    /**
     * Product brands Save image fields
     * @param  [type] $term_id  [description]
     * @param  [type] $tt_id    [description]
     * @param  [type] $taxonomy [description]
     * @return [type]           [description]
     */
    public function brands_fields_save($term_id, $tt_id, $taxonomy ) {
    	if ( isset( $_POST['brand_thumbnail_id'] ) ){
    		if (function_exists( 'update_term_meta' )){
			    update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['brand_thumbnail_id'] ), '' );
		    } else {
			    update_metadata( 'woocommerce_term', $term_id, 'thumbnail_id', absint( $_POST['brand_thumbnail_id'] ), '' );
		    }
    	}
    	delete_transient( 'wc_term_counts' );
    }
}