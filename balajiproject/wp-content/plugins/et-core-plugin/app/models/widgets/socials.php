<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;
use ETC\App\Controllers\Shortcodes\Follow;

/**
 * Recent socials Widget.
 * 
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Socials extends Widgets {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_socials', 'description' => esc_html__( "Social links widget", 'xstore-core') );
        parent::__construct('etheme-socials', '8theme - '.esc_html__('Social links', 'xstore-core'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_socials';
    }

    function widget($args, $instance) {
	    if (parent::admin_widget_preview(esc_html__('Social links', 'xstore-core')) !== false) return;
	    if ( xstore_notice() ) return;
	    $ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Socials', $instance, '', '', 'widget', $args );
		    return;
	    }
	    if ( is_null($args)) {
	    	$args = array(
	    		'before_widget' => '',
			    'after_title' => '',
		    );
	    }
	    if (!is_null($args)) {
		    extract($args);
	    }

        if ( empty( $instance['number'] ) || !$number = (int) $instance['number'] ){
            $number = 10;
        } else if ( $number < 1 ){
            $number = 1;
        } else if ( $number > 15 ){
            $number = 15;
        }

	    echo (isset($before_widget)) ? $before_widget : '';
	    echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

        $Follow = Follow::get_instance();

        echo $Follow->follow_shortcode(array(
            'size'        => ( ! empty( $instance['size'] ) ) ? $instance['size'] : '',
            'align'       => ( ! empty( $instance['align'] ) ) ? $instance['align'] : '',
            'target'      => ( ! empty( $instance['target'] ) ) ? $instance['target'] : '',
            'facebook'    => ( ! empty( $instance['facebook'] ) ) ? $instance['facebook'] : '',
            'twitter'     => ( ! empty( $instance['twitter'] ) ) ? $instance['twitter'] : '',
            'instagram'   => ( ! empty( $instance['instagram'] ) ) ? $instance['instagram'] : '',
            'google'      => ( ! empty( $instance['google'] ) ) ? $instance['google'] : '',
            'pinterest'   => ( ! empty( $instance['pinterest'] ) ) ? $instance['pinterest'] : '',
            'linkedin'    => ( ! empty( $instance['linkedin'] ) ) ? $instance['linkedin'] : '',
            'tumblr'      => ( ! empty( $instance['tumblr'] ) ) ? $instance['tumblr'] : '',
            'youtube'     => ( ! empty( $instance['youtube'] ) ) ? $instance['youtube'] : '',
            'whatsapp'       => ( ! empty( $instance['whatsapp'] ) ) ? $instance['whatsapp'] : '',
            'vimeo'       => ( ! empty( $instance['vimeo'] ) ) ? $instance['vimeo'] : '',
            'rss'         => ( ! empty( $instance['rss'] ) ) ? $instance['rss'] : '',
            'vk'          => ( ! empty( $instance['vk'] ) ) ? $instance['vk'] : '',
            'houzz'       => ( ! empty( $instance['houzz'] ) ) ? $instance['houzz'] : '',
            'tripadvisor' => ( ! empty( $instance['tripadvisor'] ) ) ? $instance['tripadvisor'] : '',
            'untapped' => ( ! empty( $instance['untapped'] ) ) ? $instance['untapped'] : '',
            'etsy' => ( ! empty( $instance['etsy'] ) ) ? $instance['etsy'] : '',
            'tik-tok' => ( ! empty( $instance['tik-tok'] ) ) ? $instance['tik-tok'] : '',
            'strava' => ( ! empty( $instance['strava'] ) ) ? $instance['strava'] : '',
            'cafecito' => ( ! empty( $instance['cafecito'] ) ) ? $instance['cafecito'] : '',
            'dribbble' => ( ! empty( $instance['dribbble'] ) ) ? $instance['dribbble'] : '',
            'kofi' => ( ! empty( $instance['kofi'] ) ) ? $instance['kofi'] : '',
            'line' => ( ! empty( $instance['line'] ) ) ? $instance['line'] : '',
            'patreon' => ( ! empty( $instance['patreon'] ) ) ? $instance['patreon'] : '',
            'reddit' => ( ! empty( $instance['reddit'] ) ) ? $instance['reddit'] : '',
            'discord' => ( ! empty( $instance['discord'] ) ) ? $instance['discord'] : '',
            'email' => ( ! empty( $instance['email'] ) ) ? $instance['email'] : '',
            'slider'      => ( ! empty( $instance['slider'] ) ) ? $instance['slider'] : false,
            'image'       => ( ! empty( $instance['image'] ) ) ? $instance['image'] : false,
        ));

	    echo (isset($after_widget)) ? $after_widget : '';

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']       = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['size']        = ( ! empty( $new_instance['size'] ) ) ? strip_tags( $new_instance['size'] ) : '';
        $instance['align']       = ( ! empty( $new_instance['align'] ) ) ? strip_tags( $new_instance['align'] ) : '';
        $instance['target']      = ( ! empty( $new_instance['target'] ) ) ? strip_tags( $new_instance['target'] ) : '';
        $instance['number']      = ( ! empty( $new_instance['number'] ) ) ? (int) $new_instance['number'] : '';
        $instance['slider']      = ( ! empty( $new_instance['slider'] ) ) ? (int) $new_instance['slider'] : '';
        $instance['image']       = ( ! empty( $new_instance['image'] ) ) ? (int) $new_instance['image'] : '';
        $instance['facebook']    = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
        $instance['twitter']     = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : '';
        $instance['instagram']   = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : '';
        $instance['google']      = ( ! empty( $new_instance['google'] ) ) ? strip_tags( $new_instance['google'] ) : '';
        $instance['pinterest']   = ( ! empty( $new_instance['pinterest'] ) ) ? strip_tags( $new_instance['pinterest'] ) : '';
        $instance['linkedin']    = ( ! empty( $new_instance['linkedin'] ) ) ? strip_tags( $new_instance['linkedin'] ) : '';
        $instance['tumblr']      = ( ! empty( $new_instance['tumblr'] ) ) ? strip_tags( $new_instance['tumblr'] ) : '';
        $instance['youtube']     = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';
	    $instance['whatsapp']       = ( ! empty( $new_instance['whatsapp'] ) ) ? strip_tags( $new_instance['whatsapp'] ) : '';
	    $instance['vimeo']       = ( ! empty( $new_instance['vimeo'] ) ) ? strip_tags( $new_instance['vimeo'] ) : '';
        $instance['rss']         = ( ! empty( $new_instance['rss'] ) ) ? strip_tags( $new_instance['rss'] ) : '';
        $instance['vk']          = ( ! empty( $new_instance['vk'] ) ) ? strip_tags( $new_instance['vk'] ) : '';
        $instance['houzz']       = ( ! empty( $new_instance['houzz'] ) ) ? strip_tags( $new_instance['houzz'] ) : '';
	
	    $instance['untapped']       = ( ! empty( $new_instance['untapped'] ) ) ? strip_tags( $new_instance['untapped'] ) : '';
	    $instance['etsy']       = ( ! empty( $new_instance['etsy'] ) ) ? strip_tags( $new_instance['etsy'] ) : '';
	    $instance['tik-tok']       = ( ! empty( $new_instance['tik-tok'] ) ) ? strip_tags( $new_instance['tik-tok'] ) : '';
	    $instance['strava']       = ( ! empty( $new_instance['strava'] ) ) ? strip_tags( $new_instance['strava'] ) : '';
	
	    $instance['cafecito']       = ( ! empty( $new_instance['cafecito'] ) ) ? strip_tags( $new_instance['cafecito'] ) : '';
	    $instance['dribbble']       = ( ! empty( $new_instance['dribbble'] ) ) ? strip_tags( $new_instance['dribbble'] ) : '';
	    $instance['kofi']       = ( ! empty( $new_instance['kofi'] ) ) ? strip_tags( $new_instance['kofi'] ) : '';
	    $instance['line']       = ( ! empty( $new_instance['line'] ) ) ? strip_tags( $new_instance['line'] ) : '';
	    $instance['patreon']       = ( ! empty( $new_instance['patreon'] ) ) ? strip_tags( $new_instance['patreon'] ) : '';
	    $instance['reddit']       = ( ! empty( $new_instance['reddit'] ) ) ? strip_tags( $new_instance['reddit'] ) : '';
	    $instance['discord']       = ( ! empty( $new_instance['discord'] ) ) ? strip_tags( $new_instance['discord'] ) : '';
	    
	    $instance['email']       = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';
	    
        $instance['tripadvisor'] = ( ! empty( $new_instance['tripadvisor'] ) ) ? strip_tags( $new_instance['tripadvisor'] ) : '';
	    $instance['ajax'] = ( isset( $new_instance['ajax'] ) ) ? (bool) $new_instance['ajax'] : false;
        return $instance;
    }

    function form( $instance ) {
        $title       = ( ! isset( $instance['title'] ) ) ? '' : esc_attr( $instance['title'] );
        $size        = ( ! isset( $instance['size'] ) ) ? '' : esc_attr( $instance['size'] );
        $align       = ( ! isset( $instance['align'] ) ) ? '' : esc_attr( $instance['align'] );
        $target      = ( ! isset( $instance['target'] ) ) ? '' : esc_attr( $instance['target'] );
        $facebook    = ( ! isset( $instance['facebook'] ) ) ? '' : esc_attr( $instance['facebook'] );
        $twitter     = ( ! isset( $instance['twitter'] ) ) ? '' : esc_attr( $instance['twitter'] );
        $instagram   = ( ! isset( $instance['instagram'] ) ) ? '' : esc_attr( $instance['instagram'] );
        $google      = ( ! isset( $instance['google'] ) ) ? '' : esc_attr( $instance['google'] );
        $pinterest   = ( ! isset( $instance['pinterest'] ) ) ? '' : esc_attr( $instance['pinterest'] );
        $linkedin    = ( ! isset( $instance['linkedin'] ) ) ? '' : esc_attr( $instance['linkedin'] );
        $tumblr      = ( ! isset( $instance['tumblr'] ) ) ? '' : esc_attr( $instance['tumblr'] );
        $youtube     = ( ! isset( $instance['youtube'] ) ) ? '' : esc_attr( $instance['youtube'] );
	    $whatsapp       = ( ! isset( $instance['whatsapp'] ) ) ? '' : esc_attr( $instance['whatsapp'] );
	    $vimeo       = ( ! isset( $instance['vimeo'] ) ) ? '' : esc_attr( $instance['vimeo'] );
        $rss         = ( ! isset( $instance['rss'] ) ) ? '' : esc_attr( $instance['rss'] );
        $vk          = ( ! isset( $instance['vk'] ) ) ? '' : esc_attr( $instance['vk'] );
        $houzz       = ( ! isset( $instance['houzz'] ) ) ? '' : esc_attr( $instance['houzz'] );
	
	    $untapped       = ( ! isset( $instance['untapped'] ) ) ? '' : esc_attr( $instance['untapped'] );
	    $etsy       = ( ! isset( $instance['etsy'] ) ) ? '' : esc_attr( $instance['etsy'] );
	    $tik_tok       = ( ! isset( $instance['tik-tok'] ) ) ? '' : esc_attr( $instance['tik-tok'] );
	    
	    $strava       = ( ! isset( $instance['strava'] ) ) ? '' : esc_attr( $instance['strava'] );
	    $cafecito       = ( ! isset( $instance['cafecito'] ) ) ? '' : esc_attr( $instance['cafecito'] );
	    $dribbble       = ( ! isset( $instance['dribbble'] ) ) ? '' : esc_attr( $instance['dribbble'] );
	    $kofi       = ( ! isset( $instance['kofi'] ) ) ? '' : esc_attr( $instance['kofi'] );
	    $line       = ( ! isset( $instance['line'] ) ) ? '' : esc_attr( $instance['line'] );
	    $patreon       = ( ! isset( $instance['patreon'] ) ) ? '' : esc_attr( $instance['patreon'] );
	    $reddit       = ( ! isset( $instance['reddit'] ) ) ? '' : esc_attr( $instance['reddit'] );
	    $discord       = ( ! isset( $instance['discord'] ) ) ? '' : esc_attr( $instance['discord'] );
	    
	    $email       = ( ! isset( $instance['email'] ) ) ? '' : esc_attr( $instance['email'] );
	    
        $tripadvisor = ( ! isset( $instance['tripadvisor'] ) ) ? '' : esc_attr( $instance['tripadvisor'] );
	    $ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false;

        parent::widget_input_text( esc_html__( 'Title', 'xstore-core' ), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title );
        parent::widget_input_dropdown( esc_html__( 'Size', 'xstore-core' ), $this->get_field_id( 'size' ), $this->get_field_name( 'size' ), $size, array(
            'small'  => 'Small',
            'normal' => 'Normal',
            'large'  => 'Large',
        ));

        parent::widget_input_dropdown( esc_html__( 'Align', 'xstore-core' ), $this->get_field_id( 'align' ), $this->get_field_name( 'align' ), $align, array(
            'left'   => 'Left',
            'center' => 'Center',
            'right'  => 'Right',
        ));

        parent::widget_input_text( esc_html__( 'Facebook link', 'xstore-core' ), $this->get_field_id( 'facebook' ), $this->get_field_name( 'facebook' ), $facebook );
        parent::widget_input_text( esc_html__( 'Twitter link', 'xstore-core' ), $this->get_field_id( 'twitter' ), $this->get_field_name( 'twitter' ), $twitter );
        parent::widget_input_text( esc_html__( 'Instagram link', 'xstore-core' ), $this->get_field_id( 'instagram' ), $this->get_field_name( 'instagram' ), $instagram );
        parent::widget_input_text( esc_html__( 'Google + link', 'xstore-core' ), $this->get_field_id( 'google' ), $this->get_field_name( 'google' ), $google);
        parent::widget_input_text( esc_html__( 'Pinterest link', 'xstore-core' ), $this->get_field_id( 'pinterest' ), $this->get_field_name( 'pinterest' ), $pinterest );
        parent::widget_input_text( esc_html__( 'LinkedIn link', 'xstore-core' ), $this->get_field_id( 'linkedin' ), $this->get_field_name( 'linkedin' ), $linkedin );
        parent::widget_input_text( esc_html__( 'Tumblr link', 'xstore-core' ), $this->get_field_id( 'tumblr' ), $this->get_field_name( 'tumblr' ), $tumblr );
        parent::widget_input_text( esc_html__( 'YouTube link', 'xstore-core' ), $this->get_field_id( 'youtube' ), $this->get_field_name( 'youtube' ), $youtube );
	    parent::widget_input_text( esc_html__( 'Whatsapp link', 'xstore-core' ), $this->get_field_id( 'whatsapp' ), $this->get_field_name( 'whatsapp' ), $whatsapp );
        parent::widget_input_text( esc_html__( 'Vimeo link', 'xstore-core' ), $this->get_field_id( 'vimeo' ), $this->get_field_name( 'vimeo' ), $vimeo );
        parent::widget_input_text( esc_html__( 'RSS link', 'xstore-core' ), $this->get_field_id( 'rss' ), $this->get_field_name( 'rss' ), $rss );
        parent::widget_input_text( esc_html__( 'VK link', 'xstore-core' ), $this->get_field_id( 'vk' ), $this->get_field_name( 'vk' ), $vk );
        parent::widget_input_text( esc_html__( 'Houzz link', 'xstore-core' ), $this->get_field_id( 'houzz' ), $this->get_field_name( 'houzz' ), $houzz );

        parent::widget_input_text( esc_html__( 'Tripadvisor link', 'xstore-core' ), $this->get_field_id( 'tripadvisor' ), $this->get_field_name( 'tripadvisor' ), $tripadvisor );
	
	    parent::widget_input_text( esc_html__( 'Untapped link', 'xstore-core' ), $this->get_field_id( 'untapped' ), $this->get_field_name( 'untapped' ), $untapped );
	    parent::widget_input_text( esc_html__( 'Etsy link', 'xstore-core' ), $this->get_field_id( 'etsy' ), $this->get_field_name( 'etsy' ), $etsy );
	    parent::widget_input_text( esc_html__( 'Tik-tok link', 'xstore-core' ), $this->get_field_id( 'tik-tok' ), $this->get_field_name( 'tik-tok' ), $tik_tok );
	    
	    parent::widget_input_text( esc_html__( 'Strava link', 'xstore-core' ), $this->get_field_id( 'strava' ), $this->get_field_name( 'strava' ), $strava );
	    parent::widget_input_text( esc_html__( 'Cafecito link', 'xstore-core' ), $this->get_field_id( 'cafecito' ), $this->get_field_name( 'cafecito' ), $cafecito );
	    parent::widget_input_text( esc_html__( 'Dribbble link', 'xstore-core' ), $this->get_field_id( 'dribbble' ), $this->get_field_name( 'dribbble' ), $dribbble );
	    parent::widget_input_text( esc_html__( 'Kofi link', 'xstore-core' ), $this->get_field_id( 'kofi' ), $this->get_field_name( 'kofi' ), $kofi );
	    parent::widget_input_text( esc_html__( 'Line link', 'xstore-core' ), $this->get_field_id( 'line' ), $this->get_field_name( 'line' ), $line );
	    parent::widget_input_text( esc_html__( 'Patreon link', 'xstore-core' ), $this->get_field_id( 'patreon' ), $this->get_field_name( 'patreon' ), $patreon );
	    parent::widget_input_text( esc_html__( 'Reddit link', 'xstore-core' ), $this->get_field_id( 'reddit' ), $this->get_field_name( 'reddit' ), $reddit );
	    parent::widget_input_text( esc_html__( 'Discord link', 'xstore-core' ), $this->get_field_id( 'discord' ), $this->get_field_name( 'discord' ), $discord );
	    
	    parent::widget_input_text( esc_html__( 'Email link', 'xstore-core' ), $this->get_field_id( 'email' ), $this->get_field_name( 'email' ), $email );

        parent::widget_input_dropdown( esc_html__( 'Link Target', 'xstore-core' ), $this->get_field_id('target'),$this->get_field_name('target'), $target, array(
            '_self'  => 'Current window',
            '_blank' => 'Blank',
        ));
	    parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );

    }
}