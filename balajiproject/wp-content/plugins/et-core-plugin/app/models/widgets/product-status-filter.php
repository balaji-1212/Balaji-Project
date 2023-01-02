<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\WC_Widget;
use function DI\get;

/**
 * Stock Status filter Widget.
 *
 * @since      3.0.3
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Product_Status_Filter extends WC_Widget {
	function __construct() {
		$this->widget_cssclass    = 'sidebar-widget etheme-product-status-filter etheme_product_status_filter';
		$this->widget_description = esc_html__( 'Widget to filtering products by stock attribute', 'xstore-core' );
		$this->widget_id          = 'etheme_product_status_filter';
		$this->widget_name        = '8theme - ' . esc_html__( 'Product Status Filters', 'xstore-core' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Product Status', 'xstore-core' ),
				'label' => esc_html__( 'Title', 'xstore-core' ),
			),
			'query_type' => array(
                'type'    => 'select',
                'std'     => 'and',
                'label'   => esc_html__( 'Query type', 'xstore-core' ),
                'options' => array(
                    'and' => esc_html__( 'And', 'xstore-core' ),
                    'or' => esc_html__( 'Or', 'xstore-core' ),
                ),
            ),
			'request_type' => array(
				'type'    => 'select',
				'std'     => 'new',
				'label'   => esc_html__( 'Request type', 'xstore-core' ),
				'options' => array(
					'old' => esc_html__( 'Old', 'xstore-core' ),
					'new' => esc_html__( 'New', 'xstore-core' ),
				),
			),
			'count' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Show numbers of products', 'xstore-core' )
			),
			'ajax' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Use ajax preload for this widget', 'xstore-core' )
			)
		);
		$this->query_type = 'and';
		$this->request_type = 'new';
		parent::__construct();
		add_action( 'woocommerce_product_query', array( $this, 'show_in_stock_products' ) );
		add_filter( 'loop_shop_post_in', array( $this, 'show_on_sale_products' ) );
	}

	function widget($args, $instance) {
        if (parent::admin_widget_preview(esc_html__('Product Status Filters', 'xstore-core')) !== false) return;
		if ( xstore_notice() ) return;
		if (is_admin() || ! is_shop() && ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() && ! is_tax('brand') ){
			return;
		}

		$ajax  = isset( $instance['ajax'] ) ? $instance['ajax'] : $this->settings['ajax']['std'];

		if (apply_filters('et_ajax_widgets', $ajax)){
			$instance['selector'] = '.etheme-product-status-filter';
			echo et_ajax_element_holder( 'Product_Status_Filter', $instance, '', '', 'widget_filter', $args );
			return;
		}
		$link  = $this->get_current_page_url();
		$class = $in_stock_class = $out_of_stock_class = '';
		$sale_link = $in_stock = $out_of_stock = $link;
		$count = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];

		$this->query_type = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];

		$this->request_type = isset( $instance['request_type'] ) ? $instance['request_type'] : $this->settings['request_type']['std'];

		if (! isset($_GET['stock_status'])){
			$out_of_stock = add_query_arg( 'stock_status', 'out_of_stock', $link );
		} else {
			if ($_GET['stock_status']=='in_stock'){
				$out_of_stock = add_query_arg( 'stock_status', 'out_of_stock,in_stock', $link );
			} elseif($_GET['stock_status']=='out_of_stock,in_stock'){
				$out_of_stock = add_query_arg( 'stock_status', 'in_stock', $link );
				$out_of_stock_class = 'chosen';
			} else {
				$out_of_stock = remove_query_arg( 'stock_status', $link );
				$out_of_stock_class = 'chosen';
			}
		}

		if (! isset($_GET['stock_status'])){
			$in_stock = add_query_arg( 'stock_status', 'in_stock', $link );
		} else {
			if ($_GET['stock_status']=='out_of_stock'){
				$in_stock = add_query_arg( 'stock_status', 'out_of_stock,in_stock', $link );
			}elseif($_GET['stock_status']=='out_of_stock,in_stock'){
				$in_stock = add_query_arg( 'stock_status', 'out_of_stock', $link );
				$in_stock_class = 'chosen';
			}else {
				$in_stock = remove_query_arg( 'stock_status', $link );
				$in_stock_class = 'chosen';
			}
		}

		if (! isset($_GET['sale_status'])){
			$sale_link = add_query_arg( 'sale_status', true, $link );
		} else {
			$sale_link = remove_query_arg( 'sale_status', $link );
			$class = 'chosen';
		}
		$this->widget_start( $args, $instance );

		?>
        <ul>
            <li class="<?php echo $in_stock_class; ?>">
                <a href="<?php echo $in_stock; ?>" class="etheme-ajax-filter etheme-stock-filter">
					<?php echo esc_html__( 'In stock', 'xstore-core' ); ?>
				    <?php echo ($count) ? '(' . $this->get_status_count('instock') . ')' : ''?>
                </a>
            </li>
            <li class="<?php echo $out_of_stock_class; ?>">
                <a href="<?php echo $out_of_stock; ?>" class="etheme-ajax-filter etheme-stock-filter">
					<?php echo esc_html__( 'Out of stock', 'xstore-core' ); ?>
					<?php echo ($count) ? '(' .$this->get_status_count(). ')' : ''?>
                </a>
            </li>
            <li class="<?php echo $class; ?>">
                <a href="<?php echo $sale_link; ?>" class="etheme-ajax-filter etheme-sale-filter">
					<?php echo esc_html__( 'On sale', 'xstore-core' ) ?>
					<?php //echo ($count) ? '(' .count(wc_get_product_ids_on_sale()). ')' : ''?>
					<?php echo ($count) ? '(' .$this->get_status_count('', '_sale_price', '!='). ')' : ''?>
                </a>
            </li>
        </ul>
		<?php
		$this->widget_end( $args, $instance );
	}

	public function get_status_count($type = 'outofstock', $k = '_stock_status', $q = '='){
		$filter_brand = isset( $_GET['filter_brand'] ) ? $_GET['filter_brand'] : '' ;
		$_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
		$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
		$stock_status       = isset( $_GET['stock_status'] ) ? wc_clean( wp_unslash( $_GET['stock_status'] ) ) : 0;
		$sale_status       = isset( $_GET['sale_status'] ) ? wc_clean( wp_unslash( $_GET['sale_status'] ) ) : 0;

		global $wpdb;

		// new
        if ($this->request_type == 'new'){
	        $isd = array();

	        if ($k == '_sale_price'){
		        $sales_ids = wc_get_product_ids_on_sale();
		        if ( count($sales_ids) ) {
//		        AND p.post_type IN ('product', 'product_variation')
			        $outofstock = "SELECT ID FROM $wpdb->posts as p
                WHERE p.post_status = 'publish'
                AND p.post_type IN ('product')
                AND p.ID IN (" . implode( ',', $sales_ids ) . ")
            ";
		        }
		        else {
			        return count($sales_ids);
		        }
	        } else {
		        $outofstock = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='$k'
                AND pm.meta_value $q '$type'
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";
	        }

	        // merge with filters
	        $isd = array_merge(
		        $isd,
		        $this->get_price_ids($wpdb, $min_price, $max_price), // price filter
		        $this->get_rating_ids($wpdb, $rating_filter), // rating_filter
		        $this->get_brand_ids($wpdb, $filter_brand), // filter_brand
		        $this->get_attributes_ids($wpdb, $_chosen_attributes), // chosen_attributes
		        $this->get_taxonomy_ids($wpdb) // $taxonomy
	        );

	        if ($this->query_type == 'and'){
		        $isd = array_merge(
			        $isd,
			        $this->get_sale_ids($k,$sale_status), // self sale_status
			        $this->get_stock_ids($wpdb, $stock_status) // self stock status
		        );
	        }

	        // do not count hidden products
	        $outofstock .= " AND p.ID NOT IN ( SELECT tr.object_id
                FROM $wpdb->terms as t
                JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                JOIN $wpdb->term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id WHERE t.name = 'exclude-from-catalog')
            ";

	        // get translated products
	        if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && defined( 'ICL_LANGUAGE_CODE' ) ){
		        $outofstock .= " AND p.ID IN ( SELECT pl.element_id FROM {$wpdb->prefix}icl_translations
                WHERE pl.language_code = '".ICL_LANGUAGE_CODE."'
            ";
	        }

	        if (count($isd)){
		        $outofstock .= "AND p.ID IN (".implode(',', $isd).")";
	        }

	        return count($wpdb->get_results( $outofstock, OBJECT_K ));
        }
        else {
        // OLD
	        //$outofstock = "SELECT COUNT(*) FROM $wpdb->posts as p
	        // product status(outofstock, instock, on_sale)
	        $outofstock = $outofstock_start = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='$k'
                AND pm.meta_value $q '$type'
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";

	        // self out_of_stock
	        if ($stock_status === 'out_of_stock'){
		        $outofstock .= "
                INNER JOIN $wpdb->postmeta pm5
                on p.ID = pm5.post_id
                AND pm5.meta_key='_stock_status'
                AND pm5.meta_value = 'outofstock'
            ";
	        }

	        // self in_stock
	        if ($stock_status === 'in_stock'){
		        $outofstock .= "
            INNER JOIN $wpdb->postmeta pm6
            on p.ID = pm6.post_id
            AND pm6.meta_key='_stock_status'
            AND pm6.meta_value = 'instock'
        ";
	        }

	        // self sale_status
	        if ($sale_status){
		        $outofstock .= "
                INNER JOIN $wpdb->postmeta pm7
                on p.ID = pm7.post_id
                AND pm7.meta_key='_sale_price'
                AND pm7.meta_value != ''
            ";
	        }

	        // $min_price
	        if ($min_price){
		        $outofstock .= "
                INNER JOIN $wpdb->postmeta pm3
                on p.ID = pm3.post_id
                AND pm3.meta_key='_price'
                AND pm3.meta_value >= $min_price
            ";
	        }

	        // $max_price
	        if ($max_price){
		        $outofstock .= "
                INNER JOIN $wpdb->postmeta pm4
                on p.ID = pm4.post_id
                AND pm4.meta_key='_price'
                AND pm4.meta_value <= $max_price
            ";
	        }

	        // $rating_filter
	        if (count($rating_filter)){
		        if ( count($rating_filter) > 1 ) {
			        $outofstock .= "
	                INNER JOIN $wpdb->postmeta pm2
	                on p.ID = pm2.post_id
	                AND pm2.meta_key='_wc_average_rating'
	            ";

			        $outofstock .= "AND (";
			        $i = 0;
			        foreach ($rating_filter as $filter) {
				        $end = ($filter+0.9);
				        $i++;

				        $outofstock .= "
		                pm2.meta_value BETWEEN $filter AND $end
		            ";

				        if ($i < count($rating_filter)) {
					        $outofstock .= " OR ";
				        }
			        }
			        $outofstock .= ")";
		        } else {
			        $end = ($rating_filter[0]+0.9);
			        $outofstock .= "
	                INNER JOIN $wpdb->postmeta pm2
	                on p.ID = pm2.post_id
	                AND pm2.meta_key='_wc_average_rating'
	                AND pm2.meta_value BETWEEN $rating_filter[0] AND $end
	            ";
		        }
	        }

	        // $filter_brand
	        if ($filter_brand) {
		        $brands = explode( ',', $_GET['filter_brand'] );
		        $ids    = array();

		        foreach ( $brands as $key => $value ) {
			        $term = get_term_by( 'slug', $value, 'brand' );
			        if ( ! isset( $term->term_taxonomy_id ) || empty( $term->term_taxonomy_id ) ) // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
			        {
			        } else {
				        $ids[] = $term->term_taxonomy_id;
			        }
		        }
		        if ( ! implode( ',', $ids ) ) {
			        $ids = 0;
		        } else {
			        $ids = implode( ',', $ids );
		        }
		        $outofstock .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $ids . ") )";
	        }

	        // $_chosen_attributes
	        if ( ! empty( $_chosen_attributes ) ) {
		        foreach ( $_chosen_attributes as $taxonomy => $data ) {
			        $t_ids = array();

			        foreach ( $data['terms'] as $term_slug ) {
				        $term = get_term_by( 'slug', $term_slug, $taxonomy );
				        if ( ! $term ) {
					        continue;
				        }

				        if ($data['query_type'] == 'and') {
					        $outofstock .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $term->term_id . ") )";
				        } else {
					        $t_ids[] = $term->term_id;
				        }
			        }

			        if ($data['query_type'] == 'or'){
				        if ( ! implode( ',', $t_ids ) ) {
					        $t_ids = 0;
				        } else {
					        $t_ids = implode( ',', $t_ids );
				        }
				        $outofstock .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $t_ids . ") )";
			        }
		        }
	        }

	        // $taxonomy
	        if (is_product_category() || is_product_tag() || is_product_taxonomy() || is_tax('brand')){
		        $outofstock .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . get_queried_object()->term_id . ") )";
	        }

	        // do not count hidden products
	        $outofstock .= " AND p.ID NOT IN ( SELECT tr.object_id
            FROM $wpdb->terms as t
            JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
            JOIN $wpdb->term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id WHERE t.name = 'exclude-from-catalog')
        ";

	        // count product variations not variation products
//        $outofstock .= "AND p.ID NOT IN (SELECT posts.ID  FROM $wpdb->posts AS posts
//            INNER JOIN $wpdb->term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//            INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//            INNER JOIN $wpdb->terms AS terms ON term_taxonomy.term_id = terms.term_id
//            WHERE
//                term_taxonomy.taxonomy = 'product_type'
//            AND terms.slug = 'variable')
//        ";

	        // polylang
	        if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && defined( 'ICL_LANGUAGE_CODE' ) ){
		        $outofstock .= "
					INNER JOIN {$wpdb->prefix}icl_translations pl
               		on p.ID = pl.element_id
            		and pl.language_code = '".ICL_LANGUAGE_CODE."'
				";
	        }

	        if ($k == '_sale_price') {
		        $sale_vsriation = str_replace($outofstock_start, "", $outofstock);
		        $sale_vsriation = str_replace("p.ID", "p.post_parent", $sale_vsriation);

		        $outofstock .= " OR p.ID IN (
			SELECT p.post_parent as parent_id FROM $wpdb->posts AS p 
				INNER JOIN $wpdb->postmeta AS meta ON p.ID = meta.post_id 
					AND p.post_type =  'product_variation'  
					AND p.post_status = 'publish' 
					AND meta.meta_key = '_sale_price' 
					AND meta.meta_value != ''
					$sale_vsriation
			)";
	        }

	        return count($wpdb->get_results( $outofstock, OBJECT_K ));
        }
	}

	private function get_stock_ids($wpdb, $stock_status){
		$ids = array();
		// self out_of_stock
		if ($stock_status === 'out_of_stock') {
			$ids = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='_stock_status'
                AND pm.meta_value  = 'outofstock'
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";
			$ids = array_keys($wpdb->get_results( $ids, OBJECT_K  ));
		} elseif ($stock_status === 'in_stock') {
			$ids = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='_stock_status'
                AND pm.meta_value  = 'instock'
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";
			$ids = array_keys($wpdb->get_results( $ids, OBJECT_K  ));
		}

		return $ids;
    }

    private function get_price_ids($wpdb, $min_price, $max_price){
	    $min_price_ids = array();
	    $max_price_ids = array();
	    // $min_price
	    if ($min_price){
		    $min_price_ids = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='_price'
                AND pm.meta_value  >= $min_price
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";
		    $min_price_ids = array_keys($wpdb->get_results( $min_price_ids, OBJECT_K  ));
	    }

	    // $max_price
	    if ($max_price){
		    $max_price_ids = "SELECT ID FROM $wpdb->posts as p 
                INNER JOIN $wpdb->postmeta pm
                on p.ID = pm.post_id
                and  pm.meta_key='_price'
                AND pm.meta_value  <= $max_price
                AND p.post_status = 'publish'
                AND p.post_type = 'product'
            ";
		    $max_price_ids = array_keys($wpdb->get_results( $max_price_ids, OBJECT_K  ));
	    }

	    return array_merge($min_price_ids, $max_price_ids);
    }

    private function get_rating_ids($wpdb, $rating_filter){
	    if (count($rating_filter)){

		    $ids = "SELECT ID FROM $wpdb->posts as p ";

		    if ( count($rating_filter) > 1 ) {
			    $ids .= "
	                INNER JOIN $wpdb->postmeta pm2
	                on p.ID = pm2.post_id
	                AND pm2.meta_key='_wc_average_rating'
	            ";

			    $ids .= "AND (";
			    $i = 0;
			    foreach ($rating_filter as $filter) {
				    $end = ($filter+0.9);
				    $i++;

				    $ids .= "
		                pm2.meta_value BETWEEN $filter AND $end
		            ";

				    if ($i < count($rating_filter)) {
					    $ids .= " OR ";
				    }
			    }
			    $ids .= ")";
		    } else {
			    $end = ($rating_filter[0]+0.9);
			    $ids .= "
	                INNER JOIN $wpdb->postmeta pm2
	                on p.ID = pm2.post_id
	                AND pm2.meta_key='_wc_average_rating'
	                AND pm2.meta_value BETWEEN $rating_filter[0] AND $end
	            ";
		    }
		    $ids .= "AND p.post_status = 'publish'
			    AND p.post_type = 'product'";

		    return array_keys($wpdb->get_results( $ids, OBJECT_K  ));
	    } else {
		    return array();
	    }
    }

    private function get_brand_ids($wpdb, $filter_brand){
	    if ($filter_brand) {
		    $brands = explode( ',', $_GET['filter_brand'] );
		    $ids    = array();

		    foreach ( $brands as $key => $value ) {
			    $term = get_term_by( 'slug', $value, 'brand' );
			    if ( ! isset( $term->term_taxonomy_id ) || empty( $term->term_taxonomy_id ) ) // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
			    {
			    } else {
				    $ids[] = $term->term_taxonomy_id;
			    }
		    }
		    if ( ! implode( ',', $ids ) ) {
			    $ids = 0;
		    } else {
			    $ids = implode( ',', $ids );
		    }
		    return array_keys($wpdb->get_results( "SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $ids . ")", OBJECT_K  ));
	    } else {
	        return array();
        }
    }

    private function get_attributes_ids($wpdb, $_chosen_attributes){
	    if ( ! empty( $_chosen_attributes ) ) {
		    $ids = "SELECT ID FROM $wpdb->posts as p WHERE p.post_type = 'product'";
		    foreach ( $_chosen_attributes as $taxonomy => $data ) {
			    $t_ids = array();

			    foreach ( $data['terms'] as $term_slug ) {
				    $term = get_term_by( 'slug', $term_slug, $taxonomy );
				    if ( ! $term ) {
					    continue;
				    }

				    if ($data['query_type'] == 'and') {
					    $ids .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $term->term_id . ") )";
				    } else {
					    $t_ids[] = $term->term_id;
				    }
			    }

			    if ($data['query_type'] == 'or'){
				    if ( ! implode( ',', $t_ids ) ) {
					    $t_ids = 0;
				    } else {
					    $t_ids = implode( ',', $t_ids );
				    }
				    $ids .= " AND p.ID IN ( SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . $t_ids . ") )";
			    }
		    }

		    return array_keys($wpdb->get_results( $ids, OBJECT_K  ));
	    } else {
	        return array();
        }
    }

    private function get_taxonomy_ids($wpdb){
	    if (is_product_category() || is_product_tag() || is_product_taxonomy() || is_tax('brand')){
		    $ids = " SELECT $wpdb->term_relationships.object_id  FROM $wpdb->term_relationships WHERE term_taxonomy_id  IN (" . get_queried_object()->term_id . ")";

		    return array_keys($wpdb->get_results( $ids, OBJECT_K  ));
	    } else {
	        return array();
	    }
    }

    private function get_sale_ids($k, $sale_status){
	    $ids = array();
	    if ($k != '_sale_price' && $sale_status){
		    $ids = wc_get_product_ids_on_sale();
		    // hide parent variable product and show only variations
		    $variable_products_detach = get_theme_mod( 'variable_products_detach', false );
		    $variable_products_no_parent = get_theme_mod( 'variation_product_parent_hidden', true );
		    if ( $variable_products_detach ) {
			    if ( $variable_products_no_parent && function_exists('etheme_product_variable_with_children_excluded')) {
				    $ids = array_diff($ids, etheme_product_variable_with_children_excluded());
			    }
		    }
	    }
	    return $ids;
    }

	public function show_on_sale_products( $ids ) {
		if ( isset( $_GET['sale_status'] ) && $_GET['sale_status'] ) {
			if (count(wc_get_product_ids_on_sale())){
				$ids = array_merge( $ids, wc_get_product_ids_on_sale() );
				
				// hide parent variable product and show only variations
				$variable_products_detach = get_theme_mod( 'variable_products_detach', false );
				$variable_products_no_parent = get_theme_mod( 'variation_product_parent_hidden', true );
				if ( $variable_products_detach ) {
				    if ( $variable_products_no_parent && function_exists('etheme_product_variable_with_children_excluded')) {
				        $ids = array_diff($ids, etheme_product_variable_with_children_excluded());
				    }
				}
			} else {
				$ids = array(99999);
			}
		}
		return $ids;
	}

	public function show_in_stock_products( $query ) {
		if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] ) {
			$meta_query = array();
			if ($_GET['stock_status'] == 'in_stock'){
				$meta_query = array(
					'relation' => 'AND',
					array(
						'key'     => '_stock_status',
						'value'   => 'instock',
						'compare' => '=',
					),
				);
			} elseif ($_GET['stock_status'] == 'out_of_stock'){
				$meta_query = array(
					'relation' => 'AND',
					array(
						'key'     => '_stock_status',
						'value'   => 'outofstock',
						'compare' => '=',
					),
				);
			}
			$query->set( 'meta_query', array_merge( WC()->query->get_meta_query(), $meta_query ) );
		}
	}
}
