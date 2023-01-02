<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function viwec_get_emails_list( $type = '' ) {
	return get_posts( array(
		'numberposts' => - 1,
		'post_type'   => 'viwec_template',
		'meta_key'    => 'viwec_settings_type',
		'meta_value'  => $type,
	) );
}

function viwec_render_email_template( $id ) {
	$email_template = VIWEC\INC\Email_Render::init();
	$data           = get_post_meta( $id, 'viwec_email_structure', true );
	$data           = json_decode( html_entity_decode( $data ), true );
	$email_template->render( $data );
}

function viwec_parse_styles( $data ) {
	if ( empty( $data ) ) {
		return '';
	}

	$style = '';
	if ( is_array( $data ) ) {
		foreach ( $data as $key => $value ) {
			if ( $key === 'border-style' && isset( $data['border-width'] ) && $data['border-width'] == '0px' ) {
				continue;
			}
			$style .= "{$key}:{$value};";
		}

		$border_width = isset( $data['border-width'] ) && $data['border-width'] !== '0px' ? true : false;
		$border_style = isset( $data['border-style'] ) ? true : false;

		$style .= $border_width && ! $border_style ? 'border-style:solid;' : '';
	} else {
		$style = $data;
	}

	return $style;
}

function viwec_allowed_html() {
	$allow_html = wp_kses_allowed_html( 'post' );
	foreach ( $allow_html as $key => $value ) {
		if ( in_array( $key, [ 'div', 'span', 'a', 'input', 'form' ] ) ) {
			$allow_html[ $key ]['data-*'] = 1;
		}
	}
	$allow_html['div']['style'] = [ 'display' => 1 ];

	return array_merge( $allow_html, [
			'input'  => [
				'type'         => 1,
				'id'           => 1,
				'name'         => 1,
				'class'        => 1,
				'placeholder'  => 1,
				'autocomplete' => 1,
				'style'        => 1,
				'value'        => 1,
				'data-*'       => 1,
			],
			'option' => [ 'value' => 1 ],
			'style'  => [
				'type'  => 1,
				'id'    => 1,
				'name'  => 1,
				'class' => 1,
			],
			'meta'   => [ 'http-equiv' => 1, 'content' => 1, 'name' => 1 ]
		]
	);
}

function viwec_safe_kses_styles( $styles ) {
	$styles[] = 'display';

	return $styles;
}

function viwec_get_pro_version() {
	?>
    <a target="_blank" href="https://1.envato.market/BZZv1" class="viwec-get-pro-version">
		<?php esc_html_e( 'Unlock this feature', 'xstore-core' ); ?>
    </a>
	<?php
}