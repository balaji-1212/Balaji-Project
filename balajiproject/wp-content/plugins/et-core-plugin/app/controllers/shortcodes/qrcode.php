<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * QRCode shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class QRCode extends Shortcodes {

    function hooks() {}

    function qrcode_shortcode( $atts, $content = null ) {
	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts(array(
            'size' => '128',
            'self_link' => 0,
            'title' => 'QR Code',
            'lightbox' => 0,
            'class' => ''
        ), $atts);

        return $this->etheme_qr_code($content,$atts['title'],$atts['size'],$atts['class'],$atts['self_link'],$atts['lightbox']);
    }

    function etheme_qr_code($text='QR Code', $title = 'QR Code', $size = 128, $class = '', $self_link = false, $lightbox = false ) {
        if( $self_link ) {
            $text = ( is_ssl() || ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) )?"https://":"http://";
            if ( $_SERVER['SERVER_PORT'] != '80' ) {
                $text .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            } else {
                $text .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
        }
        $image = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chld=H|1&chl=' . $text;

        if( $lightbox )
            $output = '<a href="'.$image.'" rel="lightbox" class="qr-lighbox '.$class.'"><img src="'.$image.'" /></a>';
        else
            $output = '<img src="'.$image.'"  class="qr-image '.$class.'" />';

        return $output;
    }

}
