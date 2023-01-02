<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for displaying multiple section of Wordpress customizer
 *
 * @since   1.4.4
 * @version 1.0.2
 * @log
 * remove bug text notice
 * 1.0.2
 * added multiple-'.$builder.'.php'
 */

$builder = isset( $_POST['builder'] ) ? $_POST['builder'] : 'header';
$classes = array();
$texts   = array();

if ( $builder == 'header' ) {
	$classes[] = 'et_headers-multiple';
	$classes[] = 'et_headers-multiple-content';
	$classes[] = 'et_headers-multiple-headers';
	$classes[] = 'et_headers-multiple-conditions';
	$texts['templates-title']  = esc_html__( 'Your headers (Add/Edit/Delete)', 'xstore-core' );
	$texts['conditions-title'] = esc_html__( 'Where do you want to display your header?', 'xstore-core' );
} elseif( $builder == 'single-product' ){
	$classes[] = 'et_product-single-multiple';
	$classes[] = 'et_product-single-multiple-content';
	$classes[] = 'et_product-single-multiple-products';
	$classes[] = 'et_product-single-multiple-conditions';
	$texts['templates-title']  = esc_html__( 'Your templates (Add/Edit/Delete)', 'xstore-core' );
	$texts['conditions-title'] = esc_html__( 'Where do you want to display your template?', 'xstore-core' );
}
?>
<div class="et_builder-multiple <?php echo $classes[0]; ?> hidden">
	<div class="et_builder-multiples <?php echo $classes[2]; ?>">
		<h3>
			<?php echo $texts['templates-title']; ?>
            <span class="dashicons dashicons-format-video" style="font-size: .7em;width: auto;height: auto;vertical-align: -2px;margin: 0 5px 0 0;"></span>
            <a href="https://www.youtube.com/watch?v=BpeXfzNwkOc&amp;feature=youtu.be" target="_blank" style="color: #222;font-size: .7em;">watch the video.</a>
		</h3>
		<div class="et_builder-multiple-content <?php echo $classes[1]; ?>">
			<?php require( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-'.$builder.'.php' ); ?>
		</div>
	</div>
</div>