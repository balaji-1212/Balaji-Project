<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
$show_icons = get_option('et_wc_account_nav_icons', 'yes');
$account_page_type = get_option('et_wc_account_page_type', 'new');

$current_user = wp_get_current_user();

?>

	<div class="woocommerce-MyAccount-navigation-wrapper type-<?php echo esc_attr($account_page_type); ?> <?php if ( $show_icons == 'no' ) : ?>without-icons<?php endif; ?>">
		<?php if ( $account_page_type == 'new' ) : ?>
            <div class="MyAccount-user-info">
                <?php echo get_avatar($current_user->ID); ?>
                <div>
                    <div class="MyAccount-user-name"><?php echo $current_user->display_name; ?></div>
                    <div><?php echo $current_user->user_email; ?></div>
                </div>
            </div>
        <?php endif; ?>
		
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<?php
                foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
	
	</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>