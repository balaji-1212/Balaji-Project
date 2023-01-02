<?php

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';
$item_style  = $props['childStyle']['.viwec-item-row'] ? $render->parse_styles( $props['childStyle']['.viwec-item-row'] ) : '';

$trans_product  = $props['content']['product'] ?? esc_html__( 'Product', 'xstore-core' );
$trans_quantity = $props['content']['quantity'] ?? esc_html__( 'Quantity', 'xstore-core' );
$trans_price    = $props['content']['price'] ?? esc_html__( 'Price', 'xstore-core' );

$th_style_left   = 'border:1px solid #dddddd; text-align:'.(is_rtl()?'right':'left').'; padding: 10px;';
$th_style_center = 'border:1px solid #dddddd; text-align:center; padding: 10px;';
$th_style_right  = 'border:1px solid #dddddd; text-align:'.(is_rtl()?'left':'right').'; padding: 10px;';

$html = "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='center' style='{$item_style} border-collapse:collapse;line-height: 1'>";
$html .= "<tr><th style='{$th_style_left}'>{$trans_product}</th><th style='{$th_style_center}'>{$trans_quantity}</th><th style='{$th_style_right} width:40%'>{$trans_price}</th></tr>";

ob_start();
for ( $i = 0; $i < 2; $i ++ ) {
	?>
    <tr>
        <td style='<?php echo esc_attr( $th_style_left ) ?>'>
            Product name
        </td>

        <td style='<?php echo esc_attr( $th_style_center ) ?>'>
            1
        </td>

        <td style='<?php echo esc_attr( $th_style_right ) ?>'>
			<?php echo wc_price( 25 ); ?>
        </td>
    </tr>
	<?php
}

echo wp_kses( $html . ob_get_clean() . '</table>', viwec_allowed_html() );

