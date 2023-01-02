<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Team Member shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Team_Member extends Shortcodes {
	
	function hooks() {
	}
	
	function team_member_shortcode( $atts, $content = null ) {

		if ( xstore_notice() )
			return;
		
		$atts = shortcode_atts( array(
			'class'       => '',
			'type'        => 1,
			'img_effect'  => 2,
			'name'        => '',
			'email'       => '',
			
			// socials
			'size'        => 'normal',
			'align'       => 'center',
			'target'      => '_blank',
			'facebook'    => '',
			'twitter'     => '',
			'instagram'   => '',
			'skype'       => '',
			'pinterest'   => '',
			'linkedin'    => '',
			'tumblr'      => '',
			'youtube'     => '',
			'whatsapp'    => '',
			'vimeo'       => '',
			'rss'         => '',
			'vk'          => '',
			'tripadvisor' => '',
			'houzz'       => '',
			
			'content_bg' => '#fff',
			'overlay_bg' => '',
			
			'name_color'       => '',
			'position_color'   => '',
			'email_color'      => '',
			'email_link_color' => '',
			'content_color'    => '',
			
			'icons_position'    => 'img',
			'icons_bg'          => '',
			'icons_color'       => '',
			'icons_bg_hover'    => '',
			'icons_color_hover' => '',
			'filled'            => '',
			'tooltip'           => '',
			
			'text_align'       => 'center',
			'position'         => '',
			'content_position' => 'top',
			'img_position'     => 'left',
			'content'          => '',
			'img'              => '',
			'img_backup'       => '',
			'img_size'         => '270x170',
			
			// extra settings
			'is_preview'       => isset( $_GET['vc_editable'] ),
            'prevent_load_inline_style' => false
		), $atts );
		
		$atts['is_preview'] = apply_filters( 'etheme_output_shortcodes_inline_css', $atts['is_preview'] );
		
		$options = array(
			'box_id' => rand( 100, 999 ),
			'image'  => function_exists('etheme_get_image') ? etheme_get_image( intval( $atts['img'] ), $atts['img_size'] ) : ''
		);
		
		if ( empty( $options['image'] ) && ! empty( $atts['img_backup'] ) ) {
			$options['image'] = $atts['img_backup'];
		}
		
		if ( ! empty( $atts['content'] ) ) {
			$content = $atts['content'];
		}
		
		$atts['content_position'] = str_replace( array( 'top', 'middle', 'bottom' ), array(
			'start',
			'center',
			'end'
		), $atts['content_position'] );
		
		// selectors 
		$options['selectors']['item']    = '.team-member-' . $options['box_id'];
		$options['selectors']['overlay']  = '.team-member' . $options['selectors']['item'] . ' .member-image:before';
		$options['selectors']['details'] = $options['selectors']['details_2'] = '.team-member' . $options['selectors']['item'] . ' .member-details';
		
		if ( $atts['type'] == '2' ) {
			$options['selectors']['details']   = '.team-member' . $options['selectors']['item'] . ' .content-section';
			$options['selectors']['details_2'] = '.team-member' . $options['selectors']['item'] . ':not(:hover) .content-section';
		}
		
		$options['selectors']['name']       = $options['selectors']['details_2'] . ' .member-name';
		$options['selectors']['position']   = $options['selectors']['details_2'] . ' .member-position';
		$options['selectors']['email']      = $options['selectors']['details_2'] . ' .member-email';
		$options['selectors']['email_link'] = $options['selectors']['details_2'] . ' .member-email a';
		$options['selectors']['content']    = $options['selectors']['details_2'] . ' .member-content';
		
		// create css data for selectors
		$options['css'] = array(
			$options['selectors']['item']       => array(),
			$options['selectors']['overlay']     => array(),
			$options['selectors']['details']    => array(),
			$options['selectors']['name']       => array(),
			$options['selectors']['position']   => array(),
			$options['selectors']['email']      => array(),
			$options['selectors']['email_link'] => array(),
			$options['selectors']['content']    => array(),
		);
		
		if ( $atts['overlay_bg'] )
		    $options['css'][ $options['selectors']['overlay'] ][] = 'background-color: ' . $atts['overlay_bg'];
		
		if ( in_array( $atts['type'], array( '2', '3' ) ) ) {
			$options['css'][ $options['selectors']['details'] ][] = 'background-color: ' . $atts['content_bg'];
		}
		
		if ( $atts['name_color'] != '' ) {
			$options['css'][ $options['selectors']['name'] ][] = 'color: ' . $atts['name_color'];
		}
		
		if ( $atts['position_color'] != '' ) {
			$options['css'][ $options['selectors']['position'] ][] = 'color: ' . $atts['position_color'];
		}
		
		if ( $atts['email_color'] != '' ) {
			$options['css'][ $options['selectors']['email'] ][] = 'color: ' . $atts['email_color'];
		}
		
		if ( $atts['email_link_color'] != '' ) {
			$options['css'][ $options['selectors']['email_link'] ][] = 'color: ' . $atts['email_link_color'];
		}
		
		if ( $atts['content_color'] != '' ) {
			$options['css'][ $options['selectors']['content'] ][] = 'color: ' . $atts['content_color'];
		}
		
		// create output css 
		$options['output_css'] = array();
		
		if ( count( $options['css'][ $options['selectors']['overlay'] ] ) ) {
			$options['output_css'][] = $options['selectors']['overlay'] . '{' . implode( ';', $options['css'][ $options['selectors']['overlay'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['details'] ] ) ) {
			$options['output_css'][] = $options['selectors']['details'] . '{' . implode( ';', $options['css'][ $options['selectors']['details'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['name'] ] ) ) {
			$options['output_css'][] = $options['selectors']['name'] . '{' . implode( ';', $options['css'][ $options['selectors']['name'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['position'] ] ) ) {
			$options['output_css'][] = $options['selectors']['position'] . '{' . implode( ';', $options['css'][ $options['selectors']['position'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['email'] ] ) ) {
			$options['output_css'][] = $options['selectors']['email'] . '{' . implode( ';', $options['css'][ $options['selectors']['email'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['email_link'] ] ) ) {
			$options['output_css'][] = $options['selectors']['email_link'] . '{' . implode( ';', $options['css'][ $options['selectors']['email_link'] ] ) . '}';
		}
		
		if ( count( $options['css'][ $options['selectors']['content'] ] ) ) {
			$options['output_css'][] = $options['selectors']['content'] . '{' . implode( ';', $options['css'][ $options['selectors']['content'] ] ) . '}';
		}
		
		
		$atts['class'] .= ' team-member-' . $options['box_id'];
		$atts['class'] .= ' member-type-' . $atts['type'];
		$atts['class'] .= ' image-position-' . $atts['img_position'];
		
		$atts['class'] .= ' et_image-with-hover et_image-hover-' . $atts['img_effect'];
		
		$options['type2']         = $atts['type'] == 2;
		$options['content_class'] = $options['type2'] && ! empty( $options['image'] ) ? '6' : '12';
		
		$options['content_class'] .= ( $options['type2'] ) ? ' flex align-items-' . $atts['content_position'] : '';
		
		if ( $atts['icons_bg'] != '' ) {
			$atts['filled'] = 'true';
		}
		
		$options['follow_attr'] = array(
			'size="' . $atts['size'] . '"',
			'align="' . $atts['align'] . '"',
			'target="' . $atts['target'] . '"',
			'facebook="' . $atts['facebook'] . '"',
			'twitter="' . $atts['twitter'] . '"',
			'instagram="' . $atts['instagram'] . '"',
			'skype="' . $atts['skype'] . '"',
			'pinterest="' . $atts['pinterest'] . '"',
			'linkedin="' . $atts['linkedin'] . '"',
			'tumblr="' . $atts['tumblr'] . '"',
			'youtube="' . $atts['youtube'] . '"',
			'whatsapp="' . $atts['whatsapp'] . '"',
			'vimeo="' . $atts['vimeo'] . '"',
			'rss="' . $atts['rss'] . '"',
			'vk="' . $atts['vk'] . '"',
			'tripadvisor="' . $atts['tripadvisor'] . '"',
			'houzz="' . $atts['houzz'] . '"',
			'icons_bg="' . $atts['icons_bg'] . '"',
			'icons_color="' . $atts['icons_color'] . '"',
			'icons_bg_hover="' . $atts['icons_bg_hover'] . '"',
			'icons_color_hover="' . $atts['icons_color_hover'] . '"',
			'filled="' . $atts['filled'] . '"',
			'tooltip="' . $atts['tooltip'] . '"',
			'is_preview="' . $atts['is_preview'] . '"'
		);
		
		ob_start();
		
		if ( !$atts['prevent_load_inline_style'] && function_exists( 'etheme_enqueue_style' ) ) {
            etheme_enqueue_style( 'banners-global', true );
            etheme_enqueue_style( 'team-member', true );
        }

		?>

		<div class="team-member <?php echo esc_attr($atts['class']); ?>">

	        <?php if ( $options['type2'] ) 
        	echo '<div class="row">';

		    if ( !empty( $options['image'] ) ) :

	            if ( $options['type2'] ) 
            	echo '<div class="image-section col-md-6">';
	            ?>

		            <div class="member-image">
		                <?php echo $options['image']; ?>
		                <div class="member-content">
		                    <?php 
		                    if ( $atts['icons_position'] == 'img' ) 
		                    	echo do_shortcode('[follow ' . implode(' ', $options['follow_attr']) . ' ]');
		                   	?>
		                </div>
		            </div>

	            	<div class="clear"></div>

	            <?php if ( $options['type2'] ) 
            	echo '</div>';

		    endif;

	    
		        if ( $options['type2'] ) { ?>

	            <div class="content-section col-md-<?php echo esc_attr($options['content_class']); ?>">

		        <?php } ?>

			        <div class="member-details text-<?php echo esc_attr($atts['text_align']); ?>">

			        	<?php 
			            if ( $atts['name'] != '' )
			                echo '<h4 class="member-name">'.$atts['name'].'</h4>';

					    if ( $atts['position'] != '' )
						    echo '<h5 class="member-position">'.$atts['position'].'</h5>';

			            if ( $atts['email'] != '' )
			                echo '<p class="member-email"><span>'.__('Email:', 'xstore-core').'</span> <a href="mailto:'.$atts['email'].'">'.$atts['email'].'</a></p>';

		                    if ( $atts['icons_position'] == 'content' ) 
		                    	echo do_shortcode('[follow ' . implode(' ', $options['follow_attr']) . ' ]');

					    echo '<div class="member-content">' . do_shortcode($content) . '</div>';

					    ?>

			    	</div>

		        <?php 

		        if ( $options['type2'] ) { ?>

	        	</div><?php // .content-section ?>

	        </div>

	        <?php } ?>

	   	</div>

	    <?php

        if ( $atts['is_preview'] ) {
            echo parent::initPreviewCss($options['output_css']);
            echo parent::initPreviewJs();
        }
        else 
            parent::initCss($options['output_css']);

	    unset($atts);
	    unset($options);
	    
	    return ob_get_clean();
	}
}
