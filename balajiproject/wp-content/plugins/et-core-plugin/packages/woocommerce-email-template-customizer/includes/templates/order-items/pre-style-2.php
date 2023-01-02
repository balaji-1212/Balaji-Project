<?php
defined( 'ABSPATH' ) || exit;

$item_style     = ! empty( $props['childStyle']['.viwec-item-row'] ) ? $render->parse_styles( $props['childStyle']['.viwec-item-row'] ) : '';
$img_size       = ! empty( $props['childStyle']['.viwec-product-img'] ) ? $render->parse_styles( $props['childStyle']['.viwec-product-img'] ) : '';
$img_style = ! empty( $props['childStyle']['.viwec-product-image'] ) ? $render->parse_styles( $props['childStyle']['.viwec-product-image'] ) : '';
$name_size      = ! empty( $props['childStyle']['.viwec-product-name'] ) ? $render->parse_styles( $props['childStyle']['.viwec-product-name'] ) : '';
$quantity_size  = ! empty( $props['childStyle']['.viwec-product-quantity'] ) ? $render->parse_styles( $props['childStyle']['.viwec-product-quantity'] ) : '';
$price_size     = ! empty( $props['childStyle']['.viwec-product-price'] ) ? $render->parse_styles( $props['childStyle']['.viwec-product-price'] ) : '';
$trans_quantity = $props['content']['quantity'] ?? 'x';
$font_size      = '15px';
$image          = VIWEC_IMAGES . 'product.png';
for ( $i = 0; $i < 2; $i ++ ) {
	$margin = $i == 1 ? '' : 'margin-bottom:10px;';

	?>
    <table width='100%' border='0' cellpadding='0' cellspacing='0' align='center' style='<?php echo esc_attr( $item_style . $margin ) ?> border-collapse:collapse;font-size: 0;'>
        <tr>
            <td valign='middle'>
                <!--[if mso | IE]>
                <table width="100%" role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="" style="vertical-align:top;<?php echo esc_attr($img_size); ?>"><![endif]-->
                <div class='viwec-responsive ' style='vertical-align:top;display:inline-block;<?php echo esc_attr( $img_size ); ?>'>
                    <table align="left" width="100%" border='0' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td>
                                <img width='100%' src='<?php echo esc_url( $image ) ?>' style='vertical-align: middle;<?php echo esc_attr($img_style); ?>'>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if mso | IE]></td>
                <td class="" style="vertical-align:top;">
                <![endif]-->
                <div class='viwec-responsive '
                     style='vertical-align:top;display:inline-block;line-height: 150%;<?php echo esc_attr( $name_size ) ?>;font-size: <?php echo esc_attr( $font_size ) ?>'>
                    <table align="left" width="100%" border='0' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td class="viwec-mobile-hidden" style="padding: 0;width: 15px;"></td>
                            <td style="padding-top: 5px;">
                                <p style="<?php echo esc_attr( $name_size ) ?>">Product name</p>
                                <p style="<?php echo esc_attr( $quantity_size ) ?>"><?php echo esc_html( $trans_quantity ) ?> 1</p>
                                <p style="<?php echo esc_attr( $price_size ) ?>"><?php echo wc_price( 25 ) ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
	<?php
}


