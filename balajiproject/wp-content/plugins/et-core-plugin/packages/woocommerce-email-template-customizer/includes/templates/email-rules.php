<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$currency       = get_woocommerce_currency_symbol( get_woocommerce_currency() );
$currency_label = esc_html__( 'Subtotal', 'xstore-core' ) . " ({$currency})";

?>
<div>
    <div class="viwec-setting-row" data-attr="country">
        <label><?php esc_html_e( 'Apply to billing countries', 'xstore-core' ) ?></label>
        <select name="viwec_setting_rules[countries][]" class="viwec-select2 viwec-input" multiple data-placeholder="All countries">
			<?php
			$wc_countries       = WC()->countries->get_countries();
			$countries_selected = is_array( $countries_selected ) ? $countries_selected : [];
			foreach ( $wc_countries as $code => $country ) {
				$selected = in_array( $code, $countries_selected ) ? 'selected' : '';
				echo "<option value='{$code}' {$selected}>{$country}</option>";
			}
			?>
        </select>
    </div>

    <div class="viwec-setting-row" data-attr="category">
        <label><?php esc_html_e( 'Apply to categories', 'xstore-core' ) ?></label>
        <select name="viwec_setting_rules[categories][]" class="viwec-select2 viwec-input" multiple data-placeholder="All categories">
			<?php
			$categories_selected = is_array( $categories_selected ) ? $categories_selected : [];
			$categories          = \VIWEC\INC\Utils::get_all_categories();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) {
					$selected = in_array( $cat['id'], $categories_selected ) ? 'selected' : '';
					echo "<option value='{$cat['id']}' {$selected}>{$cat['name']}</option>";
				}
			}
			?>
        </select>
    </div>

    <div class="viwec-setting-row" data-attr="min_order">
        <label><?php esc_html_e( 'Apply to min order', 'xstore-core' ) ?></label>
        <div class="viwec-flex viwec-group-input">
            <span class="viwec-subtotal-symbol"><?php echo esc_html( $currency_label ); ?></span>
            <input type="text" name="viwec_setting_rules[min_subtotal]" value="<?php echo esc_attr( $min_subtotal ) ?>">
        </div>
    </div>

    <div class="viwec-setting-row" data-attr="max_order">
        <label><?php esc_html_e( 'Apply to max order', 'xstore-core' ) ?></label>
        <div class="viwec-flex viwec-group-input">
            <span class="viwec-subtotal-symbol"><?php echo esc_html( $currency_label ); ?></span>
            <input type="text" name="viwec_setting_rules[max_subtotal]" value="<?php echo esc_attr( $max_subtotal ) ?>">
        </div>
    </div>
</div>
