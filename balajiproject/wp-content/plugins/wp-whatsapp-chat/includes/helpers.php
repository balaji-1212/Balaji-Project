<?php

function qlwapp_format_phone( $phone ) {

	$phone = preg_replace( '/[^0-9]/', '', $phone );

	$phone = ltrim( $phone, '0' );

	return $phone;
}

function qlwapp_get_replacements() {

	global $wp;

	$remove = function () {
		return '/';
	};

	add_filter( 'document_title_separator', $remove );
	$title = wp_get_document_title();
	remove_filter( 'document_title_separator', $remove );

	return array(
		'{SITE_TITLE}'    => get_bloginfo( 'name' ),
		'{SITE_URL}'      => home_url( '/' ),
		'{SITE_EMAIL}'    => get_bloginfo( 'admin_email' ),
		'{CURRENT_URL}'   => home_url( $wp->request ),
		'{CURRENT_TITLE}' => $title,
	);
}

function qlwapp_get_replacements_text() {

	$replacements = qlwapp_get_replacements();

	return implode( ' ', array_keys( $replacements ) );
}

function qlwapp_replacements_vars( $text ) {

	$replacements = qlwapp_get_replacements();

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $text );
}

function qlwapp_get_timezone_offset( $timezone ) {

	if ( ! $timezone ) {
		return;
	}

	if ( strpos( $timezone, 'UTC' ) !== false ) {
		$offset = preg_replace( '/UTC\+?/', '', $timezone ) * 60;
	} else {;
		$current = timezone_open( $timezone );
		$utc    = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );
		$offset = $current->getOffset( $utc ) / 3600 * 60;
	}
	return $offset;
}

function qlwapp_get_current_timezone() {
	// Get user settings
	$current_offset = get_option( 'gmt_offset' );
	$tzstring       = get_option( 'timezone_string' );

	$check_zone_info = true;

	// Remove old Etc mappings. Fallback to gmt_offset.
	if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
		$tzstring = '';
	}

	if ( empty( $tzstring ) ) {
		// Create a UTC+- zone if no timezone string exists
		$check_zone_info = false;
		if ( 0 == $current_offset ) {
			$tzstring = 'UTC+0';
		} elseif ( $current_offset < 0 ) {
			$tzstring = 'UTC' . $current_offset;
		} else {
			$tzstring = 'UTC+' . $current_offset;
		}
	}
	return $tzstring;
}
