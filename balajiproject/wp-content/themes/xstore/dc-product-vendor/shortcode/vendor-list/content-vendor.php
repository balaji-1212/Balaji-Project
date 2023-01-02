<?php

/**
 * Vendor List Map
 *
 * This template can be overridden by copying it to yourtheme/dc-product-vendor/shortcode/vendor-list/content-vendor.php
 *
 * @package WCMp/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $WCMp, $vendor_list;
$vendor = get_wcmp_vendor($vendor_id);
$image = $vendor->get_image() ? $vendor->get_image('image', array(125, 125)) : $WCMp->plugin_url . 'assets/images/WP-stdavatar.png';
$rating_info = wcmp_get_vendor_review_info($vendor->term_id);
$rating = round($rating_info['avg_rating'], 2);
$review_count = empty(intval($rating_info['total_rating'])) ? '' : intval($rating_info['total_rating']);
$vendor_phone = $vendor->phone ? $vendor->phone : __('No number yet', 'xstore');
$banner = $vendor->get_image('banner') ? $vendor->get_image('banner') : '';
?>
<div class="wcmp-store-list wcmp-store-list-vendor">
	<?php do_action('wcmp_vendor_lists_single_before_image', $vendor->term_id, $vendor->id); ?>
    <div class="wcmp-vendorblocks">
        <div class="wcmp-vendor-details">
            <div class="wcmp-cover-picture"
                 <?php if($banner) echo 'style="background-image: url('.$banner.');"'; ?>
            >
            </div>
            <div class="vendor-heading">
                <div class="wcmp-store-picture">
                    <img class="vendor_img" src="<?php echo esc_url($image); ?>" id="vendor_image_display">
                </div>
                <div class="vendor-header-icon">
                    <div class="dashicons dashicons-phone">
                        <div class="on-hover-cls">
                            <p><?php echo esc_html($vendor_phone); ?></p>
                        </div>
                    </div>
                    <div class="dashicons dashicons-location">
                        <div class="on-hover-cls">
                            <p>
                                <?php if($vendor->get_formatted_address()): ?>
                                    <?php echo substr($vendor->get_formatted_address(), 0, 10); ?>
	                            <?php else: ?>
                                    <?php echo __('No Address found', 'xstore'); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- star rating -->
			<?php
			 $is_enable = wcmp_seller_review_enable(absint($vendor->term_id));
			 if (isset($is_enable) && $is_enable) {
			?>
            <div class="wcmp-rating-block extraCls">
                <div class="wcmp-rating-rate"><?php echo esc_html($rating); ?></div>
				<?php
				$WCMp->template->get_template('review/rating_vendor_lists.php', array('rating_val_array' => $rating_info));
				?>
                <div class="wcmp-rating-review"><?php echo esc_html($review_count); ?></div>
            </div>
			<?php } ?>
            <div class="wcmp-store-detail-wrap">
				<?php do_action('wcmp_vendor_lists_vendor_before_store_details', $vendor); ?>
                <ul class="wcmp-store-detail-list">
                    <li>
                        <i class="wcmp-font ico-store-icon"></i>
						<?php $button_text = apply_filters('wcmp_vendor_lists_single_button_text', $vendor->page_title); ?>
                        <a href="<?php echo esc_url($vendor->get_permalink()); ?>" class="store-name"><?php echo esc_html($button_text); ?></a>
						<?php do_action('wcmp_vendor_lists_single_after_button', $vendor->term_id, $vendor->id); ?>
						<?php do_action('wcmp_vendor_lists_vendor_after_title', $vendor); ?>
                    </li>
					<?php if($vendor->get_formatted_address()) : ?>
                        <li>
                            <i class="wcmp-font ico-location-icon2"></i>
                            <p><?php echo esc_html($vendor->get_formatted_address()); ?></p>
                        </li>
					<?php endif; ?>
                </ul>
				<?php do_action('wcmp_vendor_lists_vendor_after_store_details', $vendor); ?>
            </div>
            <!-- vendor description -->
            <div class="add-call-block">
                <div class="wcmp-detail-block"></div>
                <div class="wcmp-detail-block"></div>
				<?php if ($vendor->address_2) : ?>
                    <div class="wcmp-detail-block">
                        <i class="wcmp-font ico-location-icon2" aria-hidden="true"></i>
                        <span class="descrptn_txt"><?php echo esc_html(substr($vendor->address_2, 0, 10) . '...'); ?></span>
                    </div>
				<?php endif; ?>
            </div>
			<?php do_action('wcmp_vendor_lists_vendor_top_products', $vendor); ?>
        </div>
    </div>
</div>