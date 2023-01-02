<?php
namespace ETC\App\Controllers\Elementor\General;

use ETC\App\Controllers\Shortcodes\Banner as Banner_Shortcode;
use ETC\App\Traits\Elementor;

/**
 * Before After Image widget.
 *
 * @since      4.0.12
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Banner_Carousel extends \Elementor\Widget_Base {
 
    use Elementor;
	/**
	 * Get widget name.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_banner_carousel';
	}
	
	/**
	 * Get widget title.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Banner Carousel', 'xstore-core' );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-banner-carousel';
	}
	
	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'mask', 'image', 'effect', 'banner', 'carousel' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return [ 'etheme_elementor_slider' ];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-banner', 'etheme-banners-global' ];
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return etheme_documentation_url('122-elementor-live-copy-option', false);
	}
	
	/**
	 * Register controls.
	 *
	 * @since 4.0.12
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Items', 'xstore-core' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		Elementor::get_banner_with_mask( $repeater, true, true );
		
		$this->add_control(
			'banner_items',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Banner With Mask', 'xstore-core' ),
						'content' => __( 'Banner With Mask Item.', 'xstore-core' ),
					],
					[
						'title' => __( 'Banner With Mask', 'xstore-core' ),
						'content' => __( 'Banner With Mask Item.', 'xstore-core' ),
					],
					[
						'title' => __( 'Banner With Mask', 'xstore-core' ),
						'content' => __( 'Banner With Mask Item.', 'xstore-core' ),
					],
				],
			]
		);
		
		$this->end_controls_section();
		
		// slider settings
		Elementor::get_slider_general_settings($this);
		
		Elementor::get_banner_with_mask_style($this, false, true);
		
		$this->update_control( 'button_color', [
			'selectors' => [
				'{{WRAPPER}} .banner .button-wrap .banner-button' => 'color: {{VALUE}};',
			],
		] );
		
		$this->update_control( 'button_bg', [
			'selectors' => [
				'{{WRAPPER}} .banner .button-wrap .banner-button' => 'background-color: {{VALUE}};',
			],
		] );
		
		$this->update_control( 'button_hover_color', [
			'selectors' => [
				'{{WRAPPER}} .banner .button-wrap .banner-button:hover' => 'color: {{VALUE}};',
			],
		] );
		
		$this->update_control( 'button_hover_bg', [
			'selectors' => [
                '{{WRAPPER}} .banner .button-wrap .banner-button:hover' => 'background-color: {{VALUE}};',
            ],
		] );
		
		// slider style settings
		Elementor::get_slider_style_settings($this);
		
	}
	
	public function render() {
		
		$settings = $this->get_settings_for_display();
		$banner_style_settings = $settings;
		unset($banner_style_settings['banner_items']);
		
		$this->add_render_attribute( 'wrapper', [
            'class' =>
                [
                    'etheme-elementor-swiper-entry',
                    'swiper-entry',
                    $settings['arrows_position'],
                    $settings['arrows_position_style']
                ]
			]
		);
		$this->add_render_attribute( 'wrapper-inner',
			[
            'class' =>
                [
                    'swiper-container',
                    'etheme-elementor-slider',
                    'swiper-container-main'
                ]
			]
		);
		$this->add_render_attribute( 'items-wrapper', 'class', 'swiper-wrapper');
		
		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$Banner_Shortcode = Banner_Shortcode::get_instance();
		$slides_count = count($settings['banner_items']);
		?>
        
            <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
                
                <div <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?>>
                
                    <div <?php $this->print_render_attribute_string( 'items-wrapper' ); ?>>
                    
                    <?php
                    
                        foreach ($settings['banner_items'] as $banner_settings) {
                            
                            $banner_settings['use_custom_fonts_title'] = true;
                            $banner_settings['use_custom_fonts_subtitle'] = true;
                            $banner_settings['responsive_fonts'] = false;
                            $banner_settings['is_preview'] = $edit_mode;
                            $banner_settings['is_elementor'] = true;
                            $banner_settings['prevent_load_inline_style'] = true;
	
                            $banner_settings['type'] = $settings['type'];
	                        $banner_settings['button_link']['title'] = isset( $banner_settings['button_title'] ) ? $banner_settings['button_title'] : '';
                            $banner_settings['align'] = $banner_style_settings['align'];
	                        $banner_settings['valign'] = $banner_style_settings['valign'];
	                        $banner_settings['content_on_hover'] = $banner_style_settings['content_on_hover'] == 'yes';
	                        $banner_settings['is_active'] = $banner_style_settings['is_active'] == 'yes';
	                        $banner_settings['type_with_diagonal'] = $banner_style_settings['type_with_diagonal'] == 'yes';
	                        $banner_settings['type_with_border'] = $banner_style_settings['type_with_border'] == 'yes';
	                        $banner_settings['title_html_tag'] = $banner_style_settings['title_html_tag'];
	                        $banner_settings['hide_title_responsive'] = $banner_style_settings['hide_title_responsive'];
	                        $banner_settings['subtitle_html_tag'] = $banner_style_settings['subtitle_html_tag'];
	                        $banner_settings['hide_subtitle_responsive'] = $banner_style_settings['hide_subtitle_responsive'];
	                        $banner_settings['hide_description_responsive'] = $banner_style_settings['hide_description_responsive'];
	                        if ( $banner_style_settings['img_size'] )
	                            $banner_settings['img_size'] = $banner_style_settings['img_size'];
	                        $banner_settings['image_opacity'] = $banner_style_settings['image_opacity']['size'];
	                        $banner_settings['banner_color_bg'] = $banner_style_settings['banner_color_bg'];
	                        $banner_settings['image_opacity_on_hover'] = $banner_style_settings['image_opacity_on_hover']['size'];
	                        $banner_settings['hide_button_responsive'] = $banner_style_settings['hide_button_responsive'];
	                        $banner_settings['prevent_load_inline_style'] = true;
	
	                        if ( !$banner_settings['img']['id'] )
                                $banner_settings['img_backup'] = '<img src="'.\Elementor\Utils::get_placeholder_image_src().'"/>';
	                        else
		                        $banner_settings['img'] = $banner_settings['img']['id'];
                            
                            ?>
                            
                            <div class="swiper-slide">
                                <?php
                                    echo $Banner_Shortcode->banner_shortcode( $banner_settings, $banner_settings['content'] );
                                ?>
                            </div>
                            
                            <?php
                        }
                    ?>
                    
                    </div>
                    
                    <?php
                        if ( 1 < $slides_count ) {
                            if ( in_array($settings['navigation'], array('both', 'dots')) )
                                Elementor::get_slider_pagination($this, $settings, $edit_mode);
                        }
                    ?>
                    
                </div>
                
                <?php

                    if ( 1 < $slides_count ) {
	                    if ( in_array( $settings['navigation'], array( 'both', 'arrows' ) ) )
		                    Elementor::get_slider_navigation( $settings, $edit_mode );
                    } ?>
                
            </div>
        
        <?php
		
	}
	
}
