<?php
namespace ETC\App\Models;

use ETC\App\Models\Base_Model;

/**
 * Create post type class with extendable config.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Post_Types extends Base_Model {

    /**
     * Constructor
     */
    protected function __construct() {}

    /**
     * Register custom post type
     * @param  array $fields
     * @return null      
     */
    public function register_single_post_type( $fields ) {

        $plural     = isset( $fields['name'] )           ? $fields['name']          : '';
        $singular   = isset( $fields['singular_name'] )  ? $fields['singular_name'] : '';
        $menu_name  = isset( $fields['menu_name'] )      ? $fields['menu_name']     : '';

        $labels = array(
            'name'                  => $plural,
            'singular_name'         => $singular,
            'menu_name'             => $menu_name,
            'new_item'              => isset( $fields['new_item'] )                 ? $fields['new_item']               : sprintf( __( 'New %s', 'xstore-core' ), $singular ),
            'add_new_item'          => isset( $fields['add_new_item'] )             ? $fields['add_new_item']           : sprintf( __( 'Add new %s', 'xstore-core' ), $singular ),
            'edit_item'             => isset( $fields['edit_item'] )                ? $fields['edit_item']              : sprintf( __( 'Edit %s', 'xstore-core' ), $singular ),
            'view_item'             => isset( $fields['view_item'] )                ? $fields['view_item']              : sprintf( __( 'View %s', 'xstore-core' ), $singular ),
            'view_items'            => isset( $fields['view_items'] )               ? $fields['view_items']             : sprintf( __( 'View %s', 'xstore-core' ), $plural ),
            'search_items'          => isset( $fields['search_items'] )             ? $fields['search_items']           : sprintf( __( 'Search %s', 'xstore-core' ), $plural ),
            'not_found'             => isset( $fields['not_found'] )                ? $fields['not_found']              : sprintf( __( 'No %s found', 'xstore-core' ), strtolower( $plural ) ),
            'not_found_in_trash'    => isset( $fields['not_found_in_trash'] )       ? $fields['not_found_in_trash']     : sprintf( __( 'No %s found in trash', 'xstore-core' ), strtolower( $plural ) ),
            'all_items'             => isset( $fields['all_items'] )                ? $fields['all_items']              : sprintf( __( 'All %s', 'xstore-core' ), $plural ),
            'archives'              => isset( $fields['archives'] )                 ? $fields['archives']               : sprintf( __( '%s Archives', 'xstore-core' ), $singular ),
            'attributes'            => isset( $fields['attributes'] )               ? $fields['attributes']             : sprintf( __( '%s Attributes', 'xstore-core' ), $singular ),
            'insert_into_item'      => isset( $fields['insert_into_item'] )         ? $fields['insert_into_item']       : sprintf( __( 'Insert into %s', 'xstore-core' ), strtolower( $singular ) ),
            'uploaded_to_this_item' => isset( $fields['uploaded_to_this_item'] )    ? $fields['uploaded_to_this_item']  : sprintf( __( 'Uploaded to this %s', 'xstore-core' ), strtolower( $singular ) ),
            'parent_item'           => isset( $fields['parent_item'] )              ? $fields['parent_item']            : sprintf( __( 'Parent %s', 'xstore-core' ), $singular ),
            'parent_item_colon'     => isset( $fields['parent_item_colon'] )        ? $fields['parent_item_colon']      : sprintf( __( 'Parent %s:', 'xstore-core' ), $singular ),
			'archive_title'         => $plural,
        );

        $args = array(
            'labels'             => $labels,
            'description'        => ( isset( $fields['description'] ) )         ?   $fields['description']          : '',
            'public'             => ( isset( $fields['public'] ) )              ?   $fields['public']               : true,
            'publicly_queryable' => ( isset( $fields['publicly_queryable'] ) )  ?   $fields['publicly_queryable']   : true,
            'exclude_from_search'=> ( isset( $fields['exclude_from_search'] ) ) ?   $fields['exclude_from_search']  : false,
            'show_ui'            => ( isset( $fields['show_ui'] ) )             ?   $fields['show_ui']              : true,
            'show_in_menu'       => ( isset( $fields['show_in_menu'] ) )        ?   $fields['show_in_menu']         : true,
            'query_var'          => ( isset( $fields['query_var'] ) )           ?   $fields['query_var']            : true,
            'show_in_admin_bar'  => ( isset( $fields['show_in_admin_bar'] ) )   ?   $fields['show_in_admin_bar']    : true,
            'capability_type'    => ( isset( $fields['capability_type'] ) )     ?   $fields['capability_type']      : 'post',
            'has_archive'        => ( isset( $fields['has_archive'] ) )         ?   $fields['has_archive']          : true,
            'hierarchical'       => ( isset( $fields['hierarchical'] ) )        ?   $fields['hierarchical']         : true,
            'supports'           => ( isset( $fields['supports'] ) )            ?   $fields['supports']             : array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                    'page-attributes',
                    'post-formats',
            ),
            'menu_position'      => ( isset( $fields['menu_position'] ) )       ? $fields['menu_position']     : 21,
            'menu_icon'          => ( isset( $fields['menu_icon'] ) )           ? $fields['menu_icon']         : 'dashicons-admin-generic',
            'show_in_nav_menus'  => ( isset( $fields['show_in_nav_menus'] ) )   ? $fields['show_in_nav_menus'] : true,
            'rewrite'            => ( isset( $fields['rewrite'] ) )             ? $fields['rewrite']           : '',
        );

        if ( isset( $fields['rewrite'] ) ) {
            $args['rewrite'] = $fields['rewrite'];
        }

        register_post_type( $fields['register_name'], $args );

        if ( isset( $fields['taxonomies'] ) && is_array( $fields['taxonomies'] ) ) {

            foreach ( $fields['taxonomies'] as $taxonomy ) {

                $this->register_single_post_type_taxnonomy( $taxonomy );

            }

        }

    }

    /**
     * Register taxonomies
     * @param  array $tax_fields
     * @return null            
     */
    public function register_single_post_type_taxnonomy( $tax_fields ) {

        $plural     = isset( $tax_fields['name'] )               ?  $tax_fields['name']             : '';
        $singular   = isset( $tax_fields['singular_name'] )      ?  $tax_fields['singular_name']    : '';

        $labels = array(
            'name'                       => $plural,
            'singular_name'              => $singular,
            'menu_name'                  => $plural,
            'all_items'                  => isset( $tax_fields['all_items'] )                     ? $tax_fields['all_items']                    :   sprintf( __( 'All %s' , 'xstore-core' ), $plural ),
            'edit_item'                  => isset( $tax_fields['edit_item'] )                     ? $tax_fields['edit_item']                    :   sprintf( __( 'Edit %s' , 'xstore-core' ), $singular ),
            'view_item'                  => isset( $tax_fields['view_item'] )                     ? $tax_fields['view_item']                    :   sprintf( __( 'View %s' , 'xstore-core' ), $singular ),
            'update_item'                => isset( $tax_fields['update_item'] )                   ? $tax_fields['update_item']                  :   sprintf( __( 'Update %s' , 'xstore-core' ), $singular ),
            'add_new_item'               => isset( $tax_fields['add_new_item'] )                  ? $tax_fields['add_new_item']                 :   sprintf( __( 'Add New %s' , 'xstore-core' ), $singular ),
            'new_item_name'              => isset( $tax_fields['new_item_name'] )                 ? $tax_fields['new_item_name']                :   sprintf( __( 'New %s Name' , 'xstore-core' ), $singular ),
            'parent_item'                => isset( $tax_fields['parent_item'] )                   ? $tax_fields['parent_item']                  :   sprintf( __( 'Parent %s' , 'xstore-core' ), $singular ),
            'parent_item_colon'          => isset( $tax_fields['parent_item_colon'] )             ? $tax_fields['parent_item_colon']            :   sprintf( __( 'Parent %s:' , 'xstore-core' ), $singular ),
            'search_items'               => isset( $tax_fields['search_items'] )                  ? $tax_fields['search_items']                 :   sprintf( __( 'Search %s' , 'xstore-core' ), $plural ),
            'popular_items'              => isset( $tax_fields['popular_items'] )                 ? $tax_fields['popular_items']                :   sprintf( __( 'Popular %s' , 'xstore-core' ), $plural ),
            'separate_items_with_commas' => isset( $tax_fields['separate_items_with_commas'] )    ? $tax_fields['separate_items_with_commas']   :   sprintf( __( 'Separate %s with commas' , 'xstore-core' ), $plural ),
            'add_or_remove_items'        => isset( $tax_fields['add_or_remove_items'] )           ? $tax_fields['add_or_remove_items']          :   sprintf( __( 'Add or remove %s' , 'xstore-core' ), $plural ),
            'choose_from_most_used'      => isset( $tax_fields['choose_from_most_used'] )         ? $tax_fields['choose_from_most_used']        :   sprintf( __( 'Choose from the most used %s' , 'xstore-core' ), $plural ),
            'not_found'                  => isset( $tax_fields['not_found'] )                     ? $tax_fields['not_found']                    :   sprintf( __( 'No %s found' , 'xstore-core' ), $plural ),
        );

        $args = array(
        	'label'                      => $plural,
        	'labels'                     => $labels,
        	'hierarchical'               => isset( $tax_fields['hierarchical'] )           ? $tax_fields['hierarchical']          : true,
        	'public'                     => isset( $tax_fields['public'] )                 ? $tax_fields['public']                : true,
        	'show_ui'                    => isset( $tax_fields['show_ui'] )                ? $tax_fields['show_ui']               : true,
        	'show_in_nav_menus'          => isset( $tax_fields['show_in_nav_menus'] )      ? $tax_fields['show_in_nav_menus']     : true,
        	'show_tagcloud'              => isset( $tax_fields['show_tagcloud'] )          ? $tax_fields['show_tagcloud']         : true,
        	'meta_box_cb'                => isset( $tax_fields['meta_box_cb'] )            ? $tax_fields['meta_box_cb']           : null,
        	'show_admin_column'          => isset( $tax_fields['show_admin_column'] )      ? $tax_fields['show_admin_column']     : true,
        	'show_in_quick_edit'         => isset( $tax_fields['show_in_quick_edit'] )     ? $tax_fields['show_in_quick_edit']    : true,
        	'update_count_callback'      => isset( $tax_fields['update_count_callback'] )  ? $tax_fields['update_count_callback'] : '',
        	'show_in_rest'               => isset( $tax_fields['show_in_rest'] )           ? $tax_fields['show_in_rest']          : true,
        	'rest_base'                  => isset( $tax_fields['rest_base'] )              ? $tax_fields['rest_base']             : true,
        	'rest_controller_class'      => isset( $tax_fields['rest_controller_class'] )  ? $tax_fields['rest_controller_class'] : 'WP_REST_Terms_Controller',
        	'query_var'                  => isset( $tax_fields['query_var'] )              ? $tax_fields['query_var']             : true,
        	'rewrite'                    => isset( $tax_fields['rewrite'] )                ? $tax_fields['rewrite']               : true,
        	'sort'                       => isset( $tax_fields['sort'] )                   ? $tax_fields['sort']                  : '',
        );

        register_taxonomy( $tax_fields['register_name'], $tax_fields['post_types'], $args );

    }

}
