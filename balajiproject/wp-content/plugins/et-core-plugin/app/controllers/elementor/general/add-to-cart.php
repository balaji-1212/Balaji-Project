<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * Add to cart widget.
 *
 * @since      4.0.10
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor
 */
class Add_To_Cart extends \Elementor\Widget_Base {
 
    private $xstore_theme;
    
	/**
	 * Get widget name.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_wc_add_to_cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Add To Cart', 'xstore-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eight_theme-elementor-icon et-elementor-add-to-cart-button';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
	}

    /**
     * Get widget categories.
     *
     * @since 4.0.10
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
	 * @since 4.0.10
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return [ 'etheme-elementor-wc-add-to-cart' ];
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
	 * Register countdown widget controls.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			[
				'label' => __( 'General', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'product_id',
			[
				'label' 		=> __( 'Product', 'xstore-core' ),
				'label_block' 	=> true,
				'type' 			=> 'etheme-ajax-product',
				'multiple' 		=> false,
				'data_options' 	=> [
					'post_type' => array( 'product_variation', 'product' ),
				],
			]
		);
		
		$this->add_control(
			'show_quantity',
			[
				'label' => __( 'Show Quantity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Please note that switching on this option will disable some of the design controls.', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'quantity',
			[
				'label' => __( 'Quantity', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'show_quantity!' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Add to cart', 'xstore-core' ),
				'placeholder' => __( 'Add to cart', 'xstore-core' ),
			]
		);
		
		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'xstore-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'xstore-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'xstore-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'xstore-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
                'condition' => [
					'show_quantity!' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_min_width',
			[
				'label' => __( 'Min Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'fa4compatibility' => 'icon',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'xstore-core' ),
					'right' => __( 'After', 'xstore-core' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);
		
		$this->add_control(
			'icon_indent',
			[
				'label' => __( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
                    'size' => 7
                ],
				'selectors' => [
					'{{WRAPPER}} .button-text:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-text:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'button_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );
		
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}}; --loader-side-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}}; --loader-side-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button.button',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Quantity Form', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'quantity_spacing',
			[
				'label' => __( 'Quantity Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .quantity' => 'margin-right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .quantity' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
	}

	/**
	 * Render countdown widget output on the frontend.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function render() {

		if ( !class_exists('WooCommerce') ) {
			echo esc_html__('Install WooCommerce Plugin to use this widget', 'xstore-core');
			return;
		}
		
		$this->xstore_theme = defined('ETHEME_THEME_VERSION');
		
		$settings = $this->get_settings_for_display();
		
		if ( ! empty( $settings['product_id'] ) ) {
			$product_id = $settings['product_id'];
		} elseif ( wp_doing_ajax() && isset($_POST['post_id']) ) {
			$product_id = $_POST['post_id'];
		} else {
			$product_id = get_queried_object_id();
		}
		
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
		
		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );
		
		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}
		
		global $product;
		$product = wc_get_product( (int)$product_id );
		
		?>
		<div class="etheme-wc-add-to-cart-wrapper">
			<?php
			
			if ( 'yes' === $settings['show_quantity'] ) {
				$this->render_form_button( $product );
			} else {
				$this->render_ajax_button( $product );
			}
			
			?>
		</div>
		<?php
	}
	
	
	/**
	 * @param \WC_Product $product
	 */
	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();
		
		if ( $product ) {
			
			$class = implode( ' ', array_filter( [
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			] ) );
			
			if ( $this->xstore_theme ) {
				$class .= ' btn black';
			}
			
			$this->add_render_attribute( 'button',
				[
					'rel' => 'nofollow',
					'href' => $product->add_to_cart_url(),
					'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class' => $class,
				]
			);
			
		}
		elseif ( current_user_can( 'manage_options' ) ) {
			$settings['button_text'] = __( 'Please set a valid product', 'xstore-core' );
			$this->add_render_attribute( 'button', ['class' => [
			    'product_type_simple', 'add_to_cart_button', 'ajax_add_to_cart', 'btn', 'black'
            ] ] );
			$this->set_settings( $settings );
		}
		
		?>
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
	            <?php
                if ( $product && !$product->is_in_stock() ) :
		            echo wc_get_stock_html( $product ); // WPCS: XSS ok.
	            endif; ?>
                <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                    <?php $this->render_text(); ?>
                </a>
            </div>
        <?php
	}
	
	private function render_form_button( $product ) {
	 
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo __( 'Please set a valid product', 'xstore-core' );
			return;
		}
		
		$with_buy_now = has_action('woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn');
		
		$text_callback = function() {
			ob_start();
			$this->render_text();
			return ob_get_clean();
		};
		
		if ( $this->xstore_theme ) {
			remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
			add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
		}
		
		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', [ $this, 'unescape_html' ], 10, 2 );
		
		if ( $with_buy_now ) {
			remove_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
		}
		
		ob_start();
		woocommerce_template_single_add_to_cart();
		$form = ob_get_clean();
		$form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );
		echo $form;
		
		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', [ $this, 'unescape_html' ] );
		if ( $with_buy_now ) {
			add_action( 'woocommerce_after_add_to_cart_button', 'etheme_buy_now_btn', 10 );
		}
		
	}
	
	/**
	 * Render button text.
	 *
	 * Render widget text.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'button_text',
			[
				'class' => 'button-text',
			]
		);
		
		if ( $settings['icon_align'] == 'left')
			$this->render_icon( $settings );
		
        ?>
        <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>><?php echo $settings['button_text']; ?></span>
        
        <?php
        if ( $settings['icon_align'] == 'right')
            $this->render_icon( $settings );
	}
	
	public function unescape_html( $safe_text, $text ) {
		return $text;
	}
	
	protected function render_icon($settings) {
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<?php if ( $is_new || $migrated ) :
				\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
                <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
			<?php endif;
		endif;
	}

}
