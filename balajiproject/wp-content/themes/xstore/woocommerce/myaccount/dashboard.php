<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

$account_page_type = get_option('et_wc_account_page_type', 'new');
if ( $account_page_type == 'new' ) {
$products_type = get_option('et_wc_account_products_type', 'random');

$endpoints = wc_get_account_menu_items();

?>

	<h3 class="title"><span><?php echo esc_html__('Welcome to your account page', 'xstore'); ?></span></h3>

	<p><?php echo sprintf(esc_html__('Hi %1$s, today is a great day to check %2$s page. You can check also:', 'xstore'),
			'<strong>' . esc_html( $current_user->display_name ) . '</strong>', esc_html__('your account', 'xstore') ); ?></p>
	
	<div class="MyAccount-dashboard-buttons"><?php if ( isset($endpoints['orders']) ) : ?>
		    <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="btn black big"><i class="et-icon et_b-icon et-sent"></i><span><?php echo esc_html__('Recent orders', 'xstore'); ?></span></a>
        <?php endif;
	    if ( isset( $endpoints['edit-address'] ) ) : ?>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="btn black big"><i class="et-icon et_b-icon et-internet"></i><span><?php echo esc_html__('Addresses', 'xstore'); ?></span></a>
	    <?php endif;
	    if ( isset( $endpoints['edit-account'] ) ) : ?>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ) ; ?>" class="btn black big"><i class="et-icon et_b-icon et-user"></i><span><?php echo esc_html__('Account details', 'xstore'); ?></span></a>
        <?php endif; ?></div>

	<?php if ( get_option( 'et_wc_account_banner', '' ) != '' ) { ?>
		<div class="MyAccount-banner">
			<?php echo do_shortcode( get_option( 'et_wc_account_banner', '' ) ); ?>
		</div>
	<?php }
	
	if ( $products_type != 'none' ) :

        $args = array(
            'post_type'           => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1,
            'orderby'               => 'rand',
            'posts_per_page'      => 7,
        );
        
        switch ($products_type) {
            case 'featured':
                $featured_product_ids            = wc_get_featured_product_ids();
                $args['post__in'] = array_merge( array( 0 ), $featured_product_ids );
                break;
            case 'sale':
                $product_ids_on_sale             = wc_get_product_ids_on_sale();
                $args['post__in'] = array_merge( array( 0 ), $product_ids_on_sale );
                break;
            case 'bestsellings':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                break;
            default: // random
                break;
        }
        
        $slider_args = array(
            'title' => esc_html__('You may also like...', 'xstore'),
            'large'            => 3,
            'notebook'         => 3,
            'slider_autoplay'  => false,
            'navigation_style' => 'style-4'
        );
        
        echo etheme_slider( $args, 'product', $slider_args );
    
    endif;
    
}
else {
    ?>
    <p>
		<?php
		printf(
		/* translators: 1: user display name 2: logout url */
			wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'xstore' ), $allowed_html ),
			'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
		?>
    </p>

    <p>
		<?php
		/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'xstore' );
		if ( wc_shipping_enabled() ) {
			/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
			$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'xstore' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
		?>
    </p>
    <?php
}

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */