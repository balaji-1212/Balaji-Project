<?php
/**
 *	Static Blocks Post Type
 */

add_filter( 'etc/add/post/args', 'etc_add_posttype_static_blocks_args' );
function etc_add_posttype_static_blocks_args( $args ) {

	if( ! get_theme_mod( 'static_blocks', true ) ) {
		return $args;
	}

	$args[] = array(
		'register_name'      => 'staticblocks',
		'name'          	 => esc_html_x( 'Static Blocks', 'post type general name', 'xstore-core' ),
		'singular_name' 	 => esc_html_x( 'Block', 'post type singular name', 'xstore-core' ),
		'add_new'       	 => esc_html_x( 'Add New', 'static block', 'xstore-core' ),
		'add_new_item'  	 => sprintf( esc_html__( 'Add New %s', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'edit_item' 		 => sprintf( esc_html__( 'Edit %s', 'xstore-core' ),esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'new_item' 			 => sprintf( esc_html__( 'New %s', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'all_items' 		 => sprintf( esc_html__( 'All %s', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'view_item' 		 => sprintf( esc_html__( 'View %s', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'search_items'  	 => sprintf( esc_html__( 'Search %s', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'not_found' 		 => sprintf( esc_html__( 'No %s Found', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'not_found_in_trash' => sprintf( esc_html__( 'No %s Found In Trash', 'xstore-core' ), esc_html__( 'Static Blocks', 'xstore-core' ) ),
		'parent_item_colon'  => '',
		'menu_name' 		 => esc_html__( 'Static Blocks', 'xstore-core' ),
		'public' 			 => true,
		'publicly_queryable' => true,
		'show_ui' 			 => true,
		'show_in_menu' 		 => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'staticblocks' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'			 => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'publicize', 'wpcom-markdown' ),
		'menu_icon'          => 'dashicons-welcome-widgets-menus',
		'menu_position'      => 8,
		'show_in_rest'       => true,
	);

	return $args;
}

/**
 *	Portfolio Post Type
 */
add_filter( 'etc/add/post/args', 'etc_add_posttype_portfolio_args' );
function etc_add_posttype_portfolio_args( $args ) {

	if( ! get_theme_mod( 'portfolio_projects', true ) ) {
		return $args;
	}
	
	$portfolio_base = get_option( 'portfolio_base' );
	
	$slug  = $portfolio_base ? $portfolio_base : 'project';
	$slug .= get_option( 'portfolio_custom_base' );

	$args[] = array(
		'register_name'      => 'etheme_portfolio',
		'name'               => esc_html_x( 'Projects', 'post type general name', 'xstore-core' ),
		'singular_name'      => esc_html_x( 'Project', 'post type singular name', 'xstore-core' ),
		'add_new'            => esc_html_x( 'Add New', 'project', 'xstore-core' ),
		'add_new_item'       => esc_html__( 'Add New Project', 'xstore-core' ),
		'edit_item'          => esc_html__( 'Edit Project', 'xstore-core' ),
		'new_item'           => esc_html__( 'New Project', 'xstore-core' ),
		'view_item'          => esc_html__( 'View Project', 'xstore-core' ),
		'search_items'       => esc_html__( 'Search Projects', 'xstore-core' ),
		'not_found'          => esc_html__( 'No projects found', 'xstore-core' ),
		'not_found_in_trash' => esc_html__( 'No projects found in Trash', 'xstore-core' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Portfolio',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor','author','thumbnail','excerpt','comments'),
		'menu_icon'          => 'dashicons-images-alt2',
		'rewrite'            => array( 'slug' => $slug ),
		'show_in_rest'       => true,
		'rest_base'             => 'etheme_portfolio',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'slug'       		 => $slug,
		'taxonomies'         => array(
			array(
				'register_name'     => 'portfolio_category',
				'post_types'     	=> 'etheme_portfolio',
				'name'              => esc_html_x( 'Portfolio categories', 'taxonomy general name', 'xstore-core' ),
				'singular_name'     => esc_html_x( 'Portfolio category', 'taxonomy singular name', 'xstore-core' ),
				'search_items'      => esc_html__( 'Search types', 'xstore-core' ),
				'all_items'         => esc_html__( 'All categories', 'xstore-core' ),
				'parent_item'       => esc_html__( 'Parent category', 'xstore-core' ),
				'parent_item_colon' => esc_html__( 'Parent category:', 'xstore-core' ),
				'edit_item'         => esc_html__( 'Edit categories', 'xstore-core' ),
				'update_item'       => esc_html__( 'Update category', 'xstore-core' ),
				'add_new_item'      => esc_html__( 'Add new category', 'xstore-core' ),
				'new_item_name'     => esc_html__( 'New category name', 'xstore-core' ),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'       => true,
				'rest_base'             => 'etheme_portfolio_category',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
				'rewrite'           => array( 'slug' => ( get_option( 'portfolio_cat_base' ) ) ? get_option( 'portfolio_cat_base' ) : 'portfolio-category' ),
			),
		),
	);

	return $args;
}

/**
 *	Portfolio rest api base
 */
add_filter( 'register_post_type_args', 'portfolio_post_type_args', 10, 2 );
function portfolio_post_type_args( $args, $post_type ) {
	if ( 'etheme_portfolio' === $post_type ) {
		$args['show_in_rest'] = true;

		// Optionally customize the rest_base or rest_controller_class
		$args['rest_base']             = 'etheme_portfolio';
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	return $args;
}

/**
 *	portfolio rest callbacks
 */
add_action( 'rest_api_init', 'portfolio_register_rest_meta' );
function portfolio_register_rest_meta() {

	register_rest_field( 'portfolio_category',
		'meta',
		array(
			'get_callback'    => 'portfolio_category_get_meta',
			'update_callback' => 'portfolio_category_update_meta',
			'schema'          => null,
		)
	);
	register_rest_field( 'etheme_portfolio',
		'meta',
		array(
			'get_callback'    => 'portfolio_get_meta',
			'update_callback' => 'portfolio_update_meta',
			'schema'          => null,
		)
	);
	register_rest_field( 'etheme_portfolio',
		'image',
		array(
			'get_callback'    => 'portfolio_get_image',
			'update_callback' => 'portfolio_update_image',
			'schema'          => null,
		)
	);
}

function portfolio_category_get_meta( $object, $field_name, $request ) {
	return get_term_meta( $object[ 'id' ] );
}

function portfolio_category_update_meta($value, $object, $field_name){
	if ( is_array( $value ) || is_object($value) ) {
		foreach ( $value as $key => $v ) {
			update_term_meta( $object->term_id, $key, $v );
		}
		return true;
	}
}

function portfolio_get_meta( $object, $field_name, $request ) {
	return get_post_meta( $object[ 'id' ]);
}

function portfolio_update_meta($value, $object, $field_name){
	if ( is_array( $value ) || is_object($value) ) {
		foreach ( $value as $key => $v ) {
			update_post_meta( $object->ID, $key, $v );
		}
		return true;
	}
}

function portfolio_get_image( $object, $field_name, $request ) {
	$image = null;
	$id =  get_post_thumbnail_id( $object[ 'id' ]);
	if ( $id ) {
		$post = get_post( $id );
		$image = array(
			'id'  => $id,
			'src' => wp_get_attachment_url($id),
			'alt' => get_post_meta($id, '_wp_attachment_image_alt', ''),
			'title' => isset($post->post_title) ? $post->post_title : '',
			'caption' =>  isset($post->post_excerpt) ? $post->post_excerpt : '',
			'description' => isset($post->post_content) ? $post->post_content : ''
		);
	}
	return $image;
}

function portfolio_update_image($value, $object, $field_name){
	if ( $field_name == 'image' && is_array( $value ) && isset($value['src']) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$id = media_sideload_image( $value['src'], 0, null, 'id');

		if (! $id) {
			return false;
		}

		$post_data = array();

		if ( isset($value['alt']) && $value['alt'] ) {
			update_post_meta($id, '_wp_attachment_image_alt', $value['alt']);
		}

		if ( isset($value['title']) && $value['title'] ) {
			$post_data['post_title'] = $value['title'];
		}

		if ( isset($value['caption']) && $value['caption'] ) {
			$post_data['post_excerpt'] = $value['caption'];
		}

		if ( isset($value['description']) && $value['description'] ) {
			$post_data['post_content'] = $value['description'];
		}

		if ( count($post_data) ) {
			$post_data[ 'ID' ] = $id;
			wp_update_post($post_data);
		}

		set_post_thumbnail($object->ID, $id);
		return true;
	}
}


/**
 *	Testimonials Post Type
 */
add_filter( 'etc/add/post/args', 'etc_add_posttype_testimonial_args' );
function etc_add_posttype_testimonial_args( $args ) {

	if( ! get_theme_mod( 'testimonials_type', true ) ) {
		return $args;
	}

	$args[] = array(
		'register_name'      => 'testimonials',
		'name'               => esc_html_x( 'Testimonials', 'post type general name', 'xstore-core' ),
		'singular_name'      => esc_html_x( 'Testimonial', 'post type singular name', 'xstore-core' ),
		'add_new'            => esc_html_x( 'Add New', 'testimonial', 'xstore-core' ),
		'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'xstore-core' ), esc_html__( 'Testimonial', 'xstore-core' ) ),
		'edit_item'          => sprintf( esc_html__( 'Edit %s', 'xstore-core' ), esc_html__( 'Testimonial', 'xstore-core' ) ),
		'new_item'           => sprintf( esc_html__( 'New %s', 'xstore-core' ), esc_html__( 'Testimonial', 'xstore-core' ) ),
		'all_items'          => sprintf( esc_html__( 'All %s', 'xstore-core' ), esc_html__( 'Testimonials', 'xstore-core' ) ),
		'view_item'          => sprintf( esc_html__( 'View %s', 'xstore-core' ), esc_html__( 'Testimonial', 'xstore-core' ) ),
		'search_items'       => sprintf( esc_html__( 'Search %s', 'xstore-core' ), esc_html__( 'Testimonials', 'xstore-core' ) ),
		'not_found'          => sprintf( esc_html__( 'No %s Found', 'xstore-core' ), esc_html__( 'Testimonials', 'xstore-core' ) ),
		'not_found_in_trash' => sprintf( esc_html__( 'No %s Found In Trash', 'xstore-core' ), esc_html__( 'Testimonials', 'xstore-core' ) ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Testimonials', 'xstore-core' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'testimonial' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-format-status',
		'show_in_rest'       => true,
		'rest_base'             => 'etheme_testimonials',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'taxonomies'         => array(
			array(
				'register_name'       => 'testimonial-category',
				'post_types'     	  => 'testimonials',
				'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'xstore-core' ),
				'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'xstore-core' ),
				'search_items'        => sprintf( esc_html__( 'Search %s', 'xstore-core' ), 'Categories' ),
				'all_items'           => sprintf( esc_html__( 'All %s', 'xstore-core' ), 'Categories' ),
				'parent_item'         => sprintf( esc_html__( 'Parent %s', 'xstore-core' ), 'Category' ),
				'parent_item_colon'   => sprintf( esc_html__( 'Parent %s:', 'xstore-core' ), 'Category' ),
				'edit_item'           => sprintf( esc_html__( 'Edit %s', 'xstore-core' ), 'Category' ),
				'update_item'         => sprintf( esc_html__( 'Update %s', 'xstore-core' ), 'Category' ),
				'add_new_item'        => sprintf( esc_html__( 'Add New %s', 'xstore-core' ), 'Category'),
				'new_item_name'       => sprintf( esc_html__( 'New %s Name', 'xstore-core' ), 'Category' ),
				'menu_name'           => sprintf( esc_html__( '%s', 'xstore-core' ), 'Categories' ),
				'public'              => true,
				'hierarchical'        => true,
				'show_ui'             => true,
				'show_admin_column'   => true,
				'query_var'           => true,
				'show_in_nav_menus'   => false,
				'show_tagcloud'       => false,
				'show_in_rest'        => true,
				'rest_base'             => 'etheme_testimonials_category',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
			),
		),
	);

	return $args;
}

/**
 *	Testimonials rest callbacks
 */

add_filter( 'register_post_type_args', 'testimonial_post_type_args', 10, 2 );
function testimonial_post_type_args( $args, $post_type ) {
	if ( 'testimonials' === $post_type ) {
		$args['show_in_rest'] = true;

		// Optionally customize the rest_base or rest_controller_class
		$args['rest_base']             = 'etheme_testimonials';
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	return $args;
}


add_action( 'rest_api_init', 'testimonial_register_rest_meta' );
function testimonial_register_rest_meta() {
	register_rest_field(
		'testimonials',
		'meta',
		array(
			'get_callback'    => 'testimonial_get_meta',
			'update_callback' => 'testimonial_update_meta',
			'schema'          => null,
		)
	);
}



function testimonial_get_meta( $object, $field_name, $request ) {
	return get_post_meta( $object[ 'id' ]);
}

function testimonial_update_meta($value, $object, $field_name){
	if ( is_array( $value ) || is_object($value) ) {
		foreach ( $value as $key => $v ) {
			update_post_meta( $object->ID, $key, $v );
		}
		return true;
	}
}


/**
 *	Brand taxonomy
 */
add_filter( 'etc/add/tax/args', 'etc_add_brand_taxonomies' );
function etc_add_brand_taxonomies( $args ) {

	if( ! get_theme_mod( 'enable_brands', true ) ) {
		return $args;
	}
	
	$custom_base = get_option( 'brand_custom_base' );
	$brand_base = get_option( 'brand_base' );
	
	$brand_slug = $custom_base ? ( $custom_base . '/' ): '';
	
	$brand_slug .= $brand_base ? $brand_base : 'brand';

	$args[] = array(
		'register_name'     => 'brand',
		'post_types'     	=> 'product',
		'name'              => esc_html__( 'Brands', 'xstore-core' ),
		'singular_name'     => esc_html__( 'Brand', 'xstore-core' ),
		'search_items'      => esc_html__( 'Search Brands', 'xstore-core' ),
		'all_items'         => esc_html__( 'All Brands', 'xstore-core' ),
		'parent_item'       => esc_html__( 'Parent Brand', 'xstore-core' ),
		'parent_item_colon' => esc_html__( 'Parent Brand:', 'xstore-core' ),
		'edit_item'         => esc_html__( 'Edit Brand', 'xstore-core' ),
		'update_item'       => esc_html__( 'Update Brand', 'xstore-core' ),
		'add_new_item'      => esc_html__( 'Add New Brand', 'xstore-core' ),
		'new_item_name'     => esc_html__( 'New Brand Name', 'xstore-core' ),
		'menu_name'         => esc_html__( 'Brands', 'xstore-core' ),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'          => true,
		'rest_base'             => 'etheme_brands',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'capabilities'			=> array(
			'manage_terms' 		=> 'manage_product_terms',
			'edit_terms' 		=> 'edit_product_terms',
			'delete_terms' 		=> 'delete_product_terms',
			'assign_terms' 		=> 'assign_product_terms',
		),
		'rewrite'           => array(
			'slug' => $brand_slug,
			'with_front' => false,
		),
		'slug'       		=> $brand_slug
	);

	return $args;
}

/**
 *	Brand rest callbacks
 */
add_action( 'rest_api_init', 'brand_register_rest_meta' );
function brand_register_rest_meta() {
	register_rest_field( 'brand',
		'meta',
		array(
			'get_callback'    => 'brand_get_meta',
			'update_callback' => 'brand_update_meta',
			'schema'          => null,
		)
	);

	register_rest_field( 'brand',
		'image',
		array(
			'get_callback'    => 'brand_get_image',
			'update_callback' => 'brand_update_image',
			'schema'          => null,
		)
	);
}

function brand_get_image( $object, $field_name, $request ) {
	$image = null;
	$id =  get_term_meta( $object[ 'id' ], 'thumbnail_id');
	if ( $id && isset($id[0]) ) {
		$post = get_post( $id[0] );
		$image = array(
			'id'  => $id,
			'src' => wp_get_attachment_url($id[0]),
			'alt' => get_post_meta($id[0], '_wp_attachment_image_alt', ''),
			'title' => isset($post->post_title) ? $post->post_title : '',
			'caption' =>  isset($post->post_excerpt) ? $post->post_excerpt : '',
			'description' => isset($post->post_content) ? $post->post_content : ''
		);
	}
	return $image;
}

function brand_update_image($value, $object, $field_name){
	if ( $field_name == 'image' && is_array( $value ) && isset($value['src']) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$id = media_sideload_image( $value['src'], 0, null, 'id');

		if (! $id) {
			return false;
		}

		$post_data = array();

		if ( isset($value['alt']) && $value['alt'] ) {
			update_post_meta($id, '_wp_attachment_image_alt', $value['alt']);
		}

		if ( isset($value['title']) && $value['title'] ) {
			$post_data['post_title'] = $value['title'];
		}

		if ( isset($value['caption']) && $value['caption'] ) {
			$post_data['post_excerpt'] = $value['caption'];
		}

		if ( isset($value['description']) && $value['description'] ) {
			$post_data['post_content'] = $value['description'];
		}

		if ( count($post_data) ) {
			$post_data[ 'ID' ] = $id;
			wp_update_post($post_data);
		}
		update_term_meta( $object->term_id, 'thumbnail_id', $id );
		return true;
	}
}

function brand_get_meta( $object, $field_name, $request ) {
	return get_term_meta( $object[ 'id' ] );
}

function brand_update_meta($value, $object, $field_name){
	if ( is_array( $value ) || is_object($value) ) {
		foreach ( $value as $key => $v ) {
			update_term_meta( $object->term_id, $key, $v );
		}
		return true;
	}
}

add_action( 'rest_api_init', 'brand_register_rest_add_to_product' );
function brand_register_rest_add_to_product() {
	register_rest_field( 'product',
		'etheme_brands',
		array(
			'get_callback'    => 'brand_get_for_product',
			'update_callback' => 'brand_update_for_product',
			'schema'          => null,
		)
	);

}

function brand_get_for_product( $object, $field_name, $request){
	$terms = wp_get_post_terms( $object['id'], 'brand' );
	return $terms;
}

function brand_update_for_product($value, $object, $field_name){
	return wp_set_post_terms( $object->id, $value, 'brand' );
}

/**
 *	Categories rest callbacks
 */
add_action( 'rest_api_init', 'categories_register_rest_meta' );
function categories_register_rest_meta() {
	register_rest_field( 'category',
		'meta',
		array(
			'get_callback'    => 'categories_get_meta',
			'update_callback' => 'categories_update_meta',
			'schema'          => null,
		)
	);
}

function categories_get_meta( $object, $field_name, $request ) {
	return get_term_meta( $object[ 'id' ] );
}
function categories_update_meta($value, $object, $field_name){
	if ( is_array( $value ) || is_object($value) ) {
		foreach ( $value as $key => $v ) {
			update_term_meta( $object->term_id, $key, $v );
		}
		return true;
	}
}

add_action( 'rest_api_init', 'product_categories_register_rest_meta' );
function product_categories_register_rest_meta() {
	register_rest_field( 'product_cat',
		'meta',
		array(
			'get_callback'    => 'categories_get_meta',
			'update_callback' => 'categories_update_meta',
			'schema'          => null,
		)
	);
}

/**
 *	product swatches rest callbacks
 */
add_action( 'rest_api_init', 'product_swatches_register_rest_options' );
function product_swatches_register_rest_options() {
	register_rest_field( 'product_attribute_term',
		'etheme_options',
		array(
			'get_callback'    => 'product_swatches_get_options',
			'update_callback' => 'product_swatches_set_options',
			'schema'          => null,
		)
	);
}

function product_swatches_get_options( $object, $field_name, $request ) {
	$meta = get_term_meta( $object[ 'id' ] );
	$etheme_options = array(
		'st-color-swatch',
		'st-color-swatch-sq',
		'st-image-swatch',
		'st-image-swatch-sq',
		'st-label-swatch',
		'st-label-swatch-sq'
	);
	$return = array();
	foreach ( $meta as $key => $value) {
		if ( in_array($key, $etheme_options) ) {
			if ( isset($value[0]) ) {
				$return[$key] = $value[0];
			}
		}
	}
	return $return;

}
function product_swatches_set_options($value, $object, $field_name){

	$etheme_options = array(
		'st-color-swatch',
		'st-color-swatch-sq',
		'st-image-swatch',
		'st-image-swatch-sq',
		'st-label-swatch',
		'st-label-swatch-sq'
	);

	foreach ( $value as $key => $v ) {
		if ( in_array($key, $etheme_options) ) {
			if ( ( $key == 'st-image-swatch' || $key == 'st-image-swatch-sq' ) && isset($v['src']) && ! isset($v['id']) ) {
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				$id = media_sideload_image( $v['src'], 0, null, 'id');

				if (! $id) {
					return false;
				}

				$post_data = array();

				if ( isset($v['alt']) && $v['alt'] ) {
					update_post_meta($id, '_wp_attachment_image_alt', $v['alt']);
				}

				if ( isset($v['title']) && $v['title'] ) {
					$post_data['post_title'] = $v['title'];
				}

				if ( isset($v['caption']) && $v['caption'] ) {
					$post_data['post_excerpt'] = $v['caption'];
				}

				if ( isset($v['description']) && $v['description'] ) {
					$post_data['post_content'] = $v['description'];
				}

				if ( count($post_data) ) {
					$post_data[ 'ID' ] = $id;
					wp_update_post($post_data);
				}
				$not_sq = str_replace( '-sq', '', $key);
				update_term_meta( $object->term_id, $not_sq, $id );
				$sq = $not_sq . '-sq';
				update_term_meta( $object->term_id, $sq , $id );
				return true;
			} else {
				$not_sq = str_replace( '-sq', '', $key);
				update_term_meta( $object->term_id, $not_sq, $v );
				$sq = $not_sq . '-sq';
				update_term_meta( $object->term_id, $sq, $v );
			}
		}

	}
	return true;
}