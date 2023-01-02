<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * PayPal widget.
 *
 * @since      4.0.10
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class PayPal extends \Elementor\Widget_Button {
    
    private $payment_types = [
        'checkout' => 'checkout',
        'subscription' => 'subscription',
        'donation' => 'donation',
    ];
    
    private $billing_cycles = [
        'days' => 'days',
        'weeks' => 'weeks',
        'months' => 'months',
        'years' => 'years',
    ];
	
	private $donation_types = [
        'any' => 'any',
        'fixed' => 'fixed',
    ];
	
    private $errors_types = [
        'global' => 'global',
        'payment_method' => 'payment',
    ];
    
    private $account_types = [
        'simple' => 'simple',
        'advanced' => 'advanced',
    ];
    
    private $paypal_urls = [
	    'prod' => 'https://www.paypal.com/cgi-bin/webscr',
	    'sandbox' => 'https://sandbox.paypal.com/cgi-bin/webscr'
    ];
	
	private $cmd_types = [
		'checkout' => '_xclick',
		'donation' => '_donations',
		'subscription' => '_xclick-subscriptions',
	];
	
	/**
	 * Get widget name.
	 *
	 * @since 4.0.10
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme_paypal';
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
		return __( 'PayPal Button', 'xstore-core' );
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
		return 'eight_theme-elementor-icon et-elementor-paypal-button';
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
		return [ 'paypal', 'payment', 'sell', 'donate' ];
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
	 * Register widget controls.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->register_general_section();
		
		$this->register_account_section();
		
		$this->register_button_section();
		
		$this->register_advanced_section();
		
    }
	
	/**
	 * General settings.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
    protected function register_general_section() {
	    
	    $this->start_controls_section(
		    'section_general',
		    [
			    'label' => esc_html__( 'General', 'xstore-core' ),
		    ]
	    );
	
	    $this->add_control(
		    'type',
		    [
			    'label'   => esc_html__( 'Type', 'xstore-core' ),
			    'type'    => \Elementor\Controls_Manager::SELECT,
			    'options' => array(
				    $this->payment_types['checkout'] => __( 'Checkout', 'xstore-core' ),
				    $this->payment_types['donation'] => __( 'Donation', 'xstore-core' ),
				    $this->payment_types['subscription'] => __( 'Subscription', 'xstore-core' ),
			    ),
			    'default' => $this->payment_types['checkout'],
		    ]
	    );
	
	    $this->add_control(
		    'product_name',
		    [
			    'label' => __( 'Item Name', 'xstore-core' ),
			    'description' => __('If you leave this empty, buyers enter their own name during checkout.', 'xstore-core'),
			    'type' => \Elementor\Controls_Manager::TEXT,
			    'dynamic' => [
				    'active' => true,
			    ],
			    'label_block' => true,
		    ]
	    );
	
	    $this->add_control(
		    'product_sku',
		    [
			    'label' => __( 'SKU', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::TEXT,
			    'dynamic' => [
				    'active' => true,
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'product_price',
		    [
			    'label' => __( 'Price', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::NUMBER,
			    'description' => __('The value for quantity must be a positive integer. Null, zero, or negative numbers are not allowed', 'xstore-core'),
			    'default' => '0.00',
			    'dynamic' => [
				    'active' => true,
			    ],
			    'condition' => [
				    'type!' => $this->payment_types['donation'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'donation_type',
		    [
			    'label' => __( 'Donation Amount', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::SELECT,
			    'default' => $this->donation_types['fixed'],
			    'options' => [
				    $this->donation_types['any'] => __( 'Any Amount', 'xstore-core' ),
				    $this->donation_types['fixed'] => __( 'Fixed', 'xstore-core' ),
			    ],
			    'condition' => [
				    'type' => $this->payment_types['donation'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'donation_amount',
		    [
			    'label' => __( 'Amount', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::NUMBER,
			    'default' => '1',
			    'dynamic' => [
				    'active' => true,
			    ],
			    'condition' => [
				    'type' => $this->payment_types['donation'],
				    'donation_type' => $this->donation_types['fixed'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'currency',
		    [
			    'label' => __( 'Currency', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::SELECT,
			    'default' => 'USD',
			    'options' => $this->get_currencies(),
		    ]
	    );
	
	    $this->add_control(
		    'billing_cycle',
		    [
			    'label' => __( 'Billing Cycle', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::SELECT,
			    'default' => $this->billing_cycles['months'],
			    'options' => [
				    $this->billing_cycles['days'] => __( 'Daily', 'xstore-core' ),
				    $this->billing_cycles['weeks'] => __( 'Weekly', 'xstore-core' ),
				    $this->billing_cycles['months'] => __( 'Monthly', 'xstore-core' ),
				    $this->billing_cycles['years'] => __( 'Yearly', 'xstore-core' ),
			    ],
			    'condition' => [
				    'type' => $this->payment_types['subscription'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'auto_renewal',
		    [
			    'type' => \Elementor\Controls_Manager::SWITCHER,
			    'label' => __( 'Auto Renewal', 'xstore-core' ),
			    'default' => 'yes',
			    'condition' => [
				    'type' => $this->payment_types['subscription'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'quantity',
		    [
			    'label' => __( 'Quantity', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::NUMBER,
			    'default' => 1,
			    'condition' => [
				    'type' => $this->payment_types['checkout'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'shipping_price',
		    [
			    'label' => __( 'Shipping Price', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::NUMBER,
			    'default' => 0,
			    'dynamic' => [
				    'active' => true,
			    ],
			    'condition' => [
				    'type' => $this->payment_types['checkout'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'tax_type',
		    [
			    'label' => __( 'Tax', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::SELECT,
			    'default' => '',
			    'options' => [
				    '' => __( 'None', 'xstore-core' ),
				    'percentage' => __( 'Percentage', 'xstore-core' ),
			    ],
			    'condition' => [
				    'type' => $this->payment_types['checkout'],
			    ],
		    ]
	    );
	
	    $this->add_control(
		    'tax_rate',
		    [
			    'label' => __( 'Tax Percentage', 'xstore-core' ),
			    'type' => \Elementor\Controls_Manager::NUMBER,
			    'default' => '0',
			    'dynamic' => [
				    'active' => true,
			    ],
			    'condition' => [
				    'type' => $this->payment_types['checkout'],
				    'tax_type' => 'percentage',
			    ],
		    ]
	    );
	
	    $this->end_controls_section();
    }
	
	/**
	 * Account settings.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function register_account_section() {
		$this->start_controls_section(
			'section_account',
			[
				'label' => __( 'Pricing & Payments', 'xstore-core' ),
			]
		);
		
		// not used yet but maybe for future
		$this->add_control(
			'merchant_account',
			[
				'label' => __( 'Merchant Account', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => $this->account_types['simple'],
				'options' => [
					$this->account_types['simple'] => __( 'Default (Simple)', 'xstore-core' ),
					$this->account_types['advanced'] => __( 'Custom (Advanced)', 'xstore-core' ),
				],
			]
		);
		
		$this->add_control(
			'email',
			[
				'label' => __( 'PayPal Account', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'description' => __( 'Transactions made through your PayPal button will be registered under this account.', 'xstore-core' ),
				'label_block' => true,
				'condition' => [
					'merchant_account' => $this->account_types['simple'],
				],
				'placeholder' => 'yours@email.com',
			]
		);
		
		$this->add_control(
			'open_in_new_window',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => sprintf( __( 'Open %s In New Tab', 'xstore-core' ), $this->get_merchant_name() ),
				'default' => 'yes',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Button settings.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function register_button_section() {
		
		parent::register_controls();
		
		$this->remove_control( 'button_type' );
		
		$this->update_control( 'link', [
			'type' => \Elementor\Controls_Manager::HIDDEN,
		] );
		
		$this->remove_control( 'size' );
		
		$this->update_control( 'selected_icon', [
			'default' => [
				'value' => 'fab fa-paypal',
				'library' => 'fa-brands',
			],
		] );
		
		$this->update_control( 'text', [
			'default' => 'Buy Now',
		] );
		
		$this->update_control( 'button_text_color', [
			'default' => '#ffffff',
		] );
		
		$this->update_control( 'background_color', [
			'default' => '#00457C',
		] );
		
		$this->update_control( 'border_border', [
			'default' => 'solid',
		] );
		
		$this->update_control( 'border_width', [
			'default' => [
				'top' => 0,
				'left' => 0,
				'right' => 0,
				'bottom' => 0
			]
		]);
		
		$this->update_control( 'border_color', [
			'default' => '#00457C',
		]);
		
		$this->update_control( 'border_radius', [
			'default' => [
				'top' => 0,
				'left' => 0,
				'right' => 0,
				'bottom' => 0
			]
		] );
	}
	
	/**
	 * Advanced settings.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function register_advanced_section() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Additional Options', 'xstore-core' ),
			]
		);
		
		$this->add_control(
			'logo',
			[
				'label' => __( 'Logo Image', 'xstore-core' ),
				'description' => __('The URL of the 150x50-pixel image displayed as your logo in the upper left corner of the PayPal checkout pages. Default is your business name, if you have a PayPal Business account or your email address, if you have PayPal Premier or Personal account.', 'xstore-core'),
				'separator' => 'after',
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => $this->payment_types['checkout'],
				],
			]
		);
		
		$this->add_control(
			'redirect_after_success',
			[
				'label' => __( 'Redirect After Success', 'xstore-core' ),
				'description' => __('By default, PayPal redirects the browser to a PayPal webpage.', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => false,
				'placeholder' => __( 'Paste URL or type', 'xstore-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'redirect_after_cancel',
			[
				'label' => __( 'Redirect After Cancel', 'xstore-core' ),
				'description' => __('By default, PayPal redirects the browser to a PayPal webpage.', 'xstore-core'),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => false,
				'placeholder' => __( 'Paste URL or type', 'xstore-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		
		$this->add_control(
			'notify_url',
			[
				'label' => __( 'Notify Url', 'xstore-core' ),
				'description' => __( 'The URL to which PayPal posts information about the payment, in the form of Instant Payment Notification messages.', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'options' => false,
				'placeholder' => __( 'Paste URL or type', 'xstore-core' ),
				'label_block' => true,
				'render_type' => 'none',
			]
		);
		
		$this->add_control(
			'sandbox_mode',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => __( 'Sandbox', 'xstore-core' )
			]
		);
		
		$this->register_sandbox_controls();
		
		$this->add_control(
			'custom_messages',
			[
				'label' => __( 'Custom Messages', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		
		$error_messages = $this->get_default_error_messages();
		
		$this->add_control(
			'error_message_' . $this->errors_types['global'],
			[
				'label' => __( 'Error Message', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => $error_messages[ $this->errors_types['global'] ],
				'placeholder' => $error_messages[ $this->errors_types['global'] ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
			]
		);
		
		$this->add_control(
			'error_message_' . $this->errors_types['payment_method'],
			[
				'label' => sprintf( __( '%s Not Connected', 'xstore-core' ), $this->get_merchant_name() ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => $error_messages[ $this->errors_types['payment_method'] ],
				'placeholder' => $error_messages[ $this->errors_types['payment_method'] ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
			]
		);
		
		$this->end_controls_section();
	}
	
	/**
	 * Sandbox settings.
     * Uses for testing purposes mostly
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function register_sandbox_controls() {
		$this->add_control(
			'sandbox_email',
			[
				'label' => __( 'Sandbox Email Account', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'description' => __( 'This is the address given to you by PayPal when you set up a sandbox with your developer account. You can use the sandbox to test your purchase flow.', 'xstore-core' ),
				'label_block' => true,
				'condition' => [
					'sandbox_mode' => 'yes',
				],
			]
		);
	}
	
	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 4.0.10
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		if ( ! $this->is_sandbox() ) {
			$form_action = $this->paypal_urls['prod'];
			$email = $settings['email'];
		} else {
			$form_action = $this->paypal_urls['sandbox'];
			$email = $settings['sandbox_email'];
			$this->add_render_attribute( 'button', 'class', 'elementor-payment-sandbox-mode' );
		}
		
		$this->add_render_attribute( 'form', [
			'action' => $form_action,
			'method' => 'post',
			'target' => 'yes' === $settings['open_in_new_window'] ? '_blank' : '_top',
		] );
		
		// PayPal HTML reference:
		// https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/html-reference-landing/
		?>
		
		<form <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			
			<?php
    
				$this->render_input('business', $email);
			
				// maybe not vital
				$this->render_input('lc', 'US');
			
				// item name
				$this->render_input('item_name', $settings['product_name']);
				
                // item number (product sku)
                $this->render_input('item_number', $settings['product_sku']);
			
				// currency code
				$this->render_input('currency_code', $settings['currency']);
			
				$this->render_input('no_note', 1);
			
				// Set PayPal payment settings by payment type.
				switch ($settings['type']) {
					case $this->payment_types['checkout']:
						
						$cmd = $this->cmd_types['checkout'];
						$this->render_input('amount', $settings['product_price']);
						$this->render_input('shipping', $this->get_numeric_setting( 'shipping_price' ));
						$this->render_input('tax_rate', $this->get_numeric_setting( 'tax_rate' ));
						$this->render_input('quantity', $this->get_numeric_setting( 'quantity', 1 ));
						if ( !empty( $settings['logo']['url'] ) )
						    $this->render_input('image_url', $settings['logo']['url']);
						
						break;
					case $this->payment_types['subscription']:
						
						$cmd = $this->cmd_types['subscription'];
						$this->render_input('a3', $settings['product_price']);
						$this->render_input('src', ('yes' === $settings['auto_renewal'] ? 1 : 0));
						$this->render_input('p3', 1);
						$this->render_input('t3', $this->render_billing_cycle_type($settings['billing_cycle']));
						$this->render_input('no_shipping', 1);
						
						break;
					case $this->payment_types['donation']:
					 
						$cmd = $this->cmd_types['donation'];
						$this->render_input('amount',($this->donation_types['fixed'] === $settings['donation_type'] ? $settings['donation_amount'] : ''));
						
						break;
				}
			
			$this->render_input('cmd', $cmd);
			
			if ( ! empty( $settings['notify_url']['url'] ) )
				$this->render_input('notify_url', $settings['notify_url']['url'] );
			
			if ( ! empty( $settings['redirect_after_success']['url'] ) )
				$this->render_input('return', $settings['redirect_after_success']['url']);

			if ( ! empty( $settings['redirect_after_cancel']['url'] ) )
				$this->render_input('cancel_return', $settings['redirect_after_cancel']['url']);
			
            $this->add_render_attribute( 'button', 'type', 'submit' );
            $this->add_render_attribute( 'button', 'name', 'submit' );
            $this->add_render_attribute( 'button', 'class', ['elementor-button', 'etheme-payment-button'] );
            $this->render_button($this);
			
			foreach ( $this->get_errors() as $type => $message ) {
				?>
                <div class="elementor-message elementor-message-danger elementor-error-message-<?php echo $type; ?>">
					<?php echo $message; ?>
                </div>
				<?php
			}
			?>
		
		</form>
		
		<?php
		
    }
	
	/**
	 * Render of input html.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function render_input($name, $value) { ?>
		<input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"/>
		<?php
	}
	
	/**
	 * Render of button html.
	 *
	 * @since 4.0.10
	 *
	 * @return void
	 */
	protected function render_button( \Elementor\Widget_Base $instance = null) {
		echo '<button' . ' ' . $this->get_render_attribute_string( 'button' ) . '>';
		    $this->render_text($instance);
		echo '</button>';
	}
	
	/**
	 * Multi-currency support for PayPal payments
	 *
	 * @since 4.0.10
     * @see https://developer.paypal.com/docs/nvp-soap-api/currency-codes#paypal
	 *
	 * @return array
	 */
	protected function get_currencies() {
		return [
			'AUD' => __( 'AUD', 'xstore-core' ),
			'CAD' => __( 'CAD', 'xstore-core' ),
			'CZK' => __( 'CZK', 'xstore-core' ),
			'DKK' => __( 'DKK', 'xstore-core' ),
			'EUR' => __( 'EUR', 'xstore-core' ),
			'HKD' => __( 'HKD', 'xstore-core' ),
			'HUF' => __( 'HUF', 'xstore-core' ),
			'ILS' => __( 'ILS', 'xstore-core' ),
			'JPY' => __( 'JPY', 'xstore-core' ),
			'MXN' => __( 'MXN', 'xstore-core' ),
			'NOK' => __( 'NOK', 'xstore-core' ),
			'NZD' => __( 'NZD', 'xstore-core' ),
			'PHP' => __( 'PHP', 'xstore-core' ),
			'PLN' => __( 'PLN', 'xstore-core' ),
			'GBP' => __( 'GBP', 'xstore-core' ),
			'RUB' => __( 'RUB', 'xstore-core' ),
			'SGD' => __( 'SGD', 'xstore-core' ),
			'SEK' => __( 'SEK', 'xstore-core' ),
			'CHF' => __( 'CHF', 'xstore-core' ),
			'TWD' => __( 'TWD', 'xstore-core' ),
			'THB' => __( 'THB', 'xstore-core' ),
			'TRY' => __( 'TRY', 'xstore-core' ),
			'USD' => __( 'USD', 'xstore-core' ),
		];
	}
	
    /**
	 * Default error messages.
	 *
	 * @since 4.0.10
	 *
	 * @return string
	 */
	protected function get_default_error_messages() {
		return [
			$this->errors_types['global'] => __( 'An error occurred.', 'xstore-core' ),
			$this->errors_types['payment_method'] => __( 'No payment method connected. Contact seller.', 'xstore-core' ),
		];
	}
	
	/**
	 * Condition for sandbox mode.
	 *
	 * @since 4.0.10
	 *
	 * @return string
	 */
	protected function is_sandbox() {
		return 'yes' === $this->get_settings_for_display( 'sandbox_mode' );
	}
	
	/**
	 * PayPal name.
	 *
	 * @since 4.0.10
	 *
	 * @return string
	 */
	protected function get_merchant_name() {
		return 'PayPal';
	}
	
	/**
	 * Retrieve a numerical field from settings.
	 *
	 * @since 4.0.10
	 *
	 * @return string
	 */
	protected function get_numeric_setting( $key, $min = 0 ) {
		$num = doubleval( $this->get_settings_for_display( $key ) );
		
		return ( $min > $num ) ? $min : $num;
	}
	
	/**
	 * Get current account type.
	 *
	 * @since 4.0.10
	 *
	 * @return string
	 */
	protected function get_api_method() {
		$settings = $this->get_settings_for_display();
		
		return ( $this->account_types['advanced'] === $settings['merchant_account'] ) ? 'sdk' : 'legacy';
	}
	
	/**
	 * Get validation errors.
	 *
	 * @param bool $squash_errors
	 * @return array|string[]
	 *
	 * @since 4.0.10
	 *
	 */
	protected function get_errors( $squash_errors = true ) {
		$settings = $this->get_settings_for_display();
		$errors = [];
		
		// Don't render errors in the editor.
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return $errors;
		}
		
		// No payment method provided.
		if ( 'legacy' === $this->get_api_method() ) {
			$empty_email = empty( $settings['email'] );
			$empty_sandbox_email = $this->is_sandbox() && empty( $settings['sandbox_email'] );
			
			if ( $empty_email || $empty_sandbox_email ) {
				$errors[ $this->errors_types['payment_method'] ] = $this->get_custom_message( $this->errors_types['payment_method'] );
			}
		}
		
		// Other errors.
		$empty_product_price = ( $this->payment_types['donation'] !== $settings['type'] && empty( $settings['product_price'] ) );
		$empty_donation_amount = ( $this->donation_types['fixed'] === $settings['donation_type'] && empty( $settings['donation_amount'] ) );
		$empty_tax = ( ! empty( $settings['tax_type'] ) && empty( $settings['tax_rate'] ) );
		
		if ( $empty_product_price || $empty_donation_amount || $empty_tax ) {
			$errors[ $this->errors_types['global'] ] = $this->get_custom_message( $this->errors_types['global'] );
		}
		
		// Squash errors to show only a global error.
		if ( $squash_errors && 1 < count( $errors ) ) {
			return [
				$this->errors_types['global'] => $this->get_custom_message( $this->errors_types['global'] ),
			];
		}
		
		return $errors;
	}
	
	/**
	 * Get message text by id (`error_message_$id`)
	 *
	 * @param $id
	 * @return mixed|string
	 *
	 * @since 4.0.10
	 *
	 */
	protected function get_custom_message( $id ) {
		$message = $this->get_settings_for_display( 'error_message_' . $id );
		
		// Return the user-defined message.
		if ( ! empty( $message ) ) {
			return $message;
		}
		
		// Return the default message.
		$error_messages = $this->get_default_error_messages();
		
		return ( ! empty( $error_messages[ $id ] ) ) ? $error_messages[ $id ] : __( 'Unknown error.', 'xstore-core' );
	}
	
	/**
	 * Render Billing cycle type (mostly first letter of the cycle).
	 *
	 * @param $billing_cycle
	 * @return string
	 *
	 * @since 4.0.10
	 *
	 */
	protected function render_billing_cycle_type($billing_cycle) {
	    $type = '';
	    switch ($billing_cycle) {
            case $this->billing_cycles['days']:
                $type = 'D';
                break;
		    case $this->billing_cycles['weeks']:
			    $type = 'W';
			    break;
		    case $this->billing_cycles['months']:
			    $type = 'M';
			    break;
		    case $this->billing_cycles['years']:
			    $type = 'Y';
			    break;
            default;
	    }
	    return $type;
	}
	
}
