<?php 
/**
 * The template for single product size guide
 *
 * @since   1.5.0
 * @version 1.0.2
 * last changes in 1.5.5
 */


$element_options = array();

$element_options['product_id'] = get_the_ID();
$element_options['is_customize_preview'] = get_query_var('et_is_customize_preview', false);
$element_options['icon_type_et-desktop'] = get_theme_mod( 'product_size_guide_icon_et-desktop', 'type1' );
$element_options['product_size_guide_align_et-desktop'] = ' justify-content-'.get_theme_mod( 'product_size_guide_align_et-desktop','inherit' );
$element_options['product_size_guide_icons_et-desktop'] = array (
	'type1' => '<svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100"  xml:space="preserve"><path d="M100,27.7c0-0.3-0.2-0.6-0.4-0.8c-0.2-0.3-0.3-0.3-0.5-0.5c-0.3-0.3-1.2-0.6-1.9-0.6H2.1c-1.4,0-2.1,1.1-2.1,2.1v43.9
	c0,0.5,0.2,0.9,0.4,1.2c0.1,0.1,0.2,0.3,0.2,0.4l0.1,0.1c0.3,0.3,1,0.7,1.7,0.7h95.1c1.4,0,2.4-1.1,2.4-2.4v-44V27.7z M18.4,41.1
	c1.4,0,2.1-1.1,2.1-2.1v-8.3h11.1V46c0,1.5,1.1,2.4,2.1,2.4c1,0,2.1-1,2.1-2.4V30.7h11.1V39c0,1.3,1,2.1,2.4,2.1
	c1.5,0,2.4-1.1,2.4-2.1v-8.3h11.1V46c0,1.4,1.1,2.4,2.4,2.4c1.4,0,2.4-1.1,2.4-2.4V30.7h11.1V39c0,1.3,1,2.1,2.4,2.1
	c1.5,0,2.4-1.1,2.4-2.1v-8.3h11.7V70H4.3V30.7H16V39C16,40,16.9,41.1,18.4,41.1z"/></svg>',
	'type2' => '<svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve"><g><path d="M49.5,37.7c0-6.5-6.7-11.6-15.3-11.6s-15.3,5.1-15.3,11.6s6.7,11.6,15.3,11.6S49.5,44.2,49.5,37.7z M44.9,37.7
		c0,3.8-4.9,7-10.7,7s-10.7-3.2-10.7-7c0-3.8,4.9-7,10.7-7S44.9,33.9,44.9,37.7z"/><path d="M97.7,58.3H68.4V37.7c0-13.9-15.3-25.2-34.2-25.2C15.3,12.5,0,23.8,0,37.7v24.6c0,12.5,12.8,23.2,29.9,24.9
		c0.6,0,1.2,0.1,1.9,0.2c0.8,0.1,1.5,0.2,2.4,0.2h63.5c1.2,0,2.3-1.1,2.3-2.3V60.6C100,59.1,98.8,58.3,97.7,58.3z M4.6,37.7
		c-0.1-4.1,1.6-8.2,4.9-11.6c5.5-5.7,14.8-9.1,24.7-9.1c16.3,0,29.6,9.3,29.6,20.6S50.5,58.3,34.2,58.3S4.6,49.1,4.6,37.7z
		 M63.8,50.2v8.1h-10C57.6,56.5,61.1,53.7,63.8,50.2z M30.2,75.3c-1.2,0-2.3,1.1-2.3,2.3v4.6c-13.6-2.1-23-10.3-23-19.9V50.2
		c6,7.8,17.3,12.7,29.6,12.7h61.2v20H92v-10c0-1.2-1.1-2.3-2.3-2.3c-1.2,0-2.3,1.1-2.3,2.3v10h-7.4v-5.4c0-1.2-1.1-2.3-2.3-2.3
		c-1.2,0-2.3,1.1-2.3,2.3v5.4h-7.4v-5.4c0-1.2-1.1-2.3-2.3-2.3c-1.2,0-2.3,1.1-2.3,2.3v5.4h-7.4v-10c0-1.2-1.1-2.3-2.3-2.3
		s-2.3,1.1-2.3,2.3v10h-7.1v-5.4c0-1.2-1.1-2.3-2.3-2.3s-2.3,1.1-2.3,2.3v5.4h-7.4v-5.4C32.5,76.4,31.4,75.3,30.2,75.3z"/></g></svg>',
	'custom' => get_theme_mod( 'product_size_guide_icon_custom_et-desktop', '' ),
	'none' => ''
);

$element_options['product_size_guide_type'] = get_theme_mod('product_size_guide_type_et-desktop', 'popup');

$element_options['product_size_guide_file'] = get_theme_mod('product_size_guide_file_et-desktop');

$element_options['product_size_guide_local_img'] = etheme_get_custom_field( 'size_guide_img', $element_options['product_id']);
$element_options['product_size_guide_local_type'] = etheme_get_custom_field( 'size_guide_type', $element_options['product_id']);

if ( $element_options['product_size_guide_local_img'] != '' )
	$element_options['product_size_guide_file'] = $element_options['product_size_guide_local_img'];
else {
	if ( function_exists('etheme_category_size_guide') ) {
		$element_options['category_size_guide'] = etheme_category_size_guide($element_options['product_id']);
		if ( $element_options['category_size_guide'] )
			$element_options['product_size_guide_local_img'] = $element_options['product_size_guide_file'] = $element_options['category_size_guide'];
    }
}


$element_options['product_size_guide_type'] = $element_options['product_size_guide_local_type'] != '' ? $element_options['product_size_guide_local_type'] : $element_options['product_size_guide_type'];

$element_options['product_size_guide_icon_et-desktop'] = $element_options['product_size_guide_icons_et-desktop'][$element_options['icon_type_et-desktop']];

$element_options['product_size_guide_label_et-desktop'] = get_theme_mod( 'product_size_guide_label_et-desktop', 'Sizing guide' );
$element_options['product_size_guide_label_show_et-desktop'] = get_theme_mod( 'product_size_guide_label_show_et-desktop', 1 );

$element_options['product_size_guide_img_et-desktop'] = get_theme_mod( 'product_size_guide_img_et-desktop', 'https://xstore.8theme.com/wp-content/uploads/2018/08/Size-guide.jpg' );
$element_options['product_size_guide_section_et-desktop'] = ( get_theme_mod( 'product_size_guide_sections_et-desktop', 0 ) ) ? get_theme_mod( 'product_size_guide_section_et-desktop', '' ) : '';

// Add this because in some case it's empty array
if (is_array($element_options['product_size_guide_img_et-desktop'])){
	$element_options['product_size_guide_img_et-desktop'] = 'https://xstore.8theme.com/wp-content/uploads/2018/08/Size-guide.jpg';
}

$element_options['product_size_guide_content_et-desktop'] = ( $element_options['product_size_guide_section_et-desktop'] != '' && $element_options['product_size_guide_section_et-desktop'] > 0 ) ? $element_options['product_size_guide_section_et-desktop'] : '<img src="'.$element_options['product_size_guide_img_et-desktop'].'" alt="' . esc_html__('sizing guide', 'xstore-core') . '">';

if ( ( $element_options['product_size_guide_section_et-desktop'] != '' && $element_options['product_size_guide_section_et-desktop'] > 0 ) ) {
}
else {
	if ( $element_options['product_size_guide_img_et-desktop'] == '' && $element_options['product_size_guide_local_img'] == '' ) {
		return;
	}
}

$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
$element_options['attributes'] = $element_options['toggle_attributes'] = array();

if ( $element_options['is_customize_preview'] ) 
	$element_options['attributes'] = array(
		'data-title="' . esc_html__( 'Sizing guide', 'xstore-core' ) . '"',
		'data-element="product_size_guide"'
	);

if ( $element_options['product_size_guide_type'] == 'popup' || $element_options['product_size_guide_file'] == '' ) {
	$element_options['toggle_attributes'] = array(
		'class="pointer et-popup_toggle"',
		'data-type="size_guide"',
		'data-popup-on="click"',
		'data-id="' . $element_options['product_id'] . '"'
	);
}
else
	$element_options['toggle_attributes'] = array(
		'href="' . $element_options['product_size_guide_file'] . '"',
		'download="' . wp_basename($element_options['product_size_guide_file']) . '"'
	);

?>

<div class="et_element single-product-size-guide align-items-center flex <?php echo $element_options['product_size_guide_align_et-desktop']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>	
	<a <?php echo implode( ' ', $element_options['toggle_attributes'] ); ?> style="all: inherit; cursor: pointer;">
		<?php if ( $element_options['product_size_guide_icon_et-desktop'] != '' || $element_options['is_customize_preview'] ) { ?>
			<span class="et-icon <?php echo ( $element_options['is_customize_preview'] && $element_options['product_size_guide_icon_et-desktop'] == '') ? 'dt-hide mob-hide' : ''; ?>">
				<?php echo $element_options['product_size_guide_icon_et-desktop']; ?>
			</span>
		<?php } ?>
		
		<?php if ( $element_options['product_size_guide_label_show_et-desktop'] || $element_options['is_customize_preview'] ) { ?>
			<span class="et-element-label <?php echo ( $element_options['is_customize_preview'] && !$element_options['product_size_guide_label_show_et-desktop'] ) ? 'dt-hide mob-hide' : ''; ?>">
				<?php echo $element_options['product_size_guide_label_et-desktop']; ?>
			</span>
		<?php } ?>
	</a>
</div>

<?php unset($element_options); ?>