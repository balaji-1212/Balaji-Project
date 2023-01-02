<?php 
/**
 * The template for single product share links 
 *
 * @since   1.5.0
 * @version 1.0.1
 * last changes in 1.5.5
 */
	
	// global $et_social_icons;

	$element_options = array();
	// $element_options['product_sharing_type_et-desktop'] = get_theme_mod( 'product_sharing_type_et-desktop' );
	// $element_options['product_socials_label'] = get_theme_mod('product_socials_label_et-desktop');
	// $element_options['product_socials_label_text'] = get_theme_mod('product_socials_label_text_et-desktop');
	
	$element_options['post_id'] = get_the_ID();
    // $element_options['permalink'] = get_permalink($element_options['post_id']);

    // $element_options['image'] =  wp_get_attachment_image_src( get_post_thumbnail_id($element_options['post_id']), 'small' );
    // $element_options['image'] = $element_options['image'][0];
    // $element_options['title'] = rawurlencode(get_the_title($element_options['post_id']));

	// $element_options['product_sharing_links'] = array(
	// 	'facebook' => array(
	// 		'href' => 'https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D='.$element_options['title'].'&amp;p%5Burl%5D='.$element_options['permalink'],
	// 		'title' => esc_attr__('Facebook', 'xstore-core'),
	// 	),
	// 	'twitter' => array(
	// 		'href' => 'https://twitter.com/share?url='.$element_options['permalink'].'&text='.$element_options['title'],
	// 		'title' => esc_attr__('Twitter', 'xstore-core'),
	// 	),
	// 	'linkedin' => array(
	// 		'href' => 'https://www.linkedin.com/shareArticle?mini=true&url='.$element_options['permalink'].'&title='.$element_options['title'],
	// 		'title' => esc_attr__('Linkedin', 'xstore-core'),
	// 	),
	// 	'houzz' => array(
	// 		'href' => 'http://www.houzz.com/imageClipperUpload?imageUrl='.$element_options['image'].'&title='.$element_options['title'].'&link='.$element_options['permalink'],
	// 		'title' => esc_attr__('Houzz', 'xstore-core'),
	// 	),
	// 	'pinterest' => array(
	// 		'href' => 'http://pinterest.com/pin/create/button/?url='.$element_options['permalink'].'&amp;media='.$element_options['image'].'&amp;description='.$element_options['title'],
	// 		'title' => esc_attr__('Pinterest', 'xstore-core'),
	// 	),
	// 	'tumblr' => array(
	// 		'href' => 'https://www.tumblr.com/widgets/share/tool?shareSource=legacy&canonicalUrl=<-urlencode('.$element_options['permalink'].')->&posttype=link',
	// 		'title' => esc_attr__('Tumblr', 'xstore-core'),
	// 	),
	// 	'vk' => array(
	// 		'href' => 'http://vk.com/share.php?url='.$element_options['permalink'].'&image='.$element_options['image'].'?&title='.$element_options['title'],
	// 		'title' => esc_attr__('Vk', 'xstore-core'),
	// 	),
	// 	'whatsapp' => array(
	// 		'href' => 'whatsapp://send?text='.$element_options['title'],
	// 		'title' => esc_attr__('Whatsapp', 'xstore-core'),
	// 	),
	// );

	// $element_options['product_sharing_package_et-desktop'] = get_theme_mod( 'product_sharing_package_et-desktop' );
	// $element_options['product_sharing_target'] = get_theme_mod( 'product_sharing_target_et-desktop' ) ? 'target="_blank"' : '';
	// $element_options['product_sharing_no_follow'] = get_theme_mod( 'product_sharing_no_follow_et-desktop' ) ? 'rel="nofollow"' : '';
	$element_options['product_sharing_content_alignment'] = 'justify-content-' . get_theme_mod( 'product_sharing_content_alignment_et-desktop', 'start' );

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();

	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Socials', 'xstore-core' ) . '"',
			'data-element="product_sharing"'
		); 

?>

<div class="et_element single-product-socials et-socials flex flex-wrap align-items-center <?php echo $element_options['product_sharing_content_alignment']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo product_sharing_callback($element_options['post_id']); ?>
</div>

<?php unset($element_options); ?>