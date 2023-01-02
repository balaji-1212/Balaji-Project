<?php
/**
 * Sales booster safe & secure checkout feature
 *
 * @package    sales_booster_safe_checkout.php
 * @since      8.3.9
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_Sales_Booster_Safe_Checkout {
	
	public static $instance = null;
	
	public static $option_name = 'safe_checkout';

	public $args = array(
        'is_local_product' => false,
        'product_id' => null,
    );
	
	public $settings = array();
    public $local_styles = array();
	
	public function __construct() {
	}

    /**
     * @param null $product
     */
	public function init($product = null) {
		if ( !class_exists('WooCommerce')) return;

		if ( !get_option('xstore_sales_booster_settings_'.self::$option_name) ) return;

        $this->args['product'] = $product;
        if ( $this->args['product'] )
            $this->args['product_id'] = $this->args['product']->get_ID();

		$this->args['spb'] = !!get_option( 'etheme_single_product_builder', false );

		add_action('wp', array($this, 'add_actions'));

        add_action('init', function () {
            add_shortcode('etheme_sales_booster_'.self::$option_name, array($this, 'shortcode_output'));
        });
	}
	
	public function add_actions() {

        if( $this->args['product'] ){
            $this->args['is_local_product'] = true;
        }
		$this->set_settings();

		if ( !$this->args['is_local_product'] && $this->settings['shown_on_single_product'] == 'on') {
			$action       = 'woocommerce_product_meta_start';
			$priority     = 15;
			$apply_action = true;
			if ( $this->args['spb'] ) {
				$action   = 'etheme_woocommerce_template_single_meta';
				$priority = 5;
			}
            if ( $apply_action ) {
                add_action( $action, array( $this, 'output' ), $priority );

                if ( has_action('after_page_wrapper', 'etheme_sticky_add_to_cart') ) {
                    add_action( 'after_page_wrapper', function () use ($action, $priority) {
                        remove_action( $action, array( $this, 'output' ), $priority );
                    }, -1 );
                    add_action( 'after_page_wrapper', function () use ($action, $priority) {
                        add_action( $action, array( $this, 'output' ), $priority );
                    }, 2 );
                }
            }
		}

		if ( $this->settings['shown_on_cart'] == 'on' ) {
            add_action('etheme_woocommerce_cart_after_collaterals', array( $this, 'output' ), 10);
        }
        if ( $this->settings['shown_on_checkout'] == 'on' ) {
            add_action('woocommerce_checkout_after_order_review', array( $this, 'output' ), 10);
        }

	}

    /**
     * @param array $custom_settings
     * @since 8.3.9
     */
	public function set_settings($custom_settings = array()) {
		$settings = (array)get_option('xstore_sales_booster_settings', array());

        $safe_payments_methods = array(
            'visa'  => esc_html__( 'Visa', 'xstore' ),
            'master-card'  => esc_html__( 'Master Card', 'xstore' ),
            'paypal' => esc_html__( 'PayPal', 'xstore' ),
            'american-express' => esc_html__( 'American Express', 'xstore' ),
            'maestro' => esc_html__( 'Maestro', 'xstore' ),
            'bitcoin' => esc_html__( 'Bitcoin', 'xstore' ),
        );

        $default = array(
            'text_before' => esc_html__( 'Guaranteed {{safe}} checkout', 'xstore' ),
            'text_after' => esc_html__( 'Your Payment is {{100% Secure}}', 'xstore' ),
            'items' => array(),
            'text_before_highlight_color' => '#2e7d32',
            'text_after_highlight_color' => (get_theme_mod( 'dark_styles', false ) ? '#fff' : '#222'),
            'tooltips' => false,
            'shown_on_quick_view' => '',
            'shown_on_single_product' => 'on',
            'shown_on_cart' => 'on',
            'shown_on_checkout' => 'on',
		);

        foreach ($safe_payments_methods as $safe_payments_method_key => $safe_payments_method_name) {
            $item_index = array_search($safe_payments_method_key,array_keys($safe_payments_methods));
            $default['items'][] = 'items_'.$item_index;
            $default['items_'.$item_index.'_payment_method'] = $safe_payments_method_key;
            $default['items_'.$item_index.'_custom_image'] = '';
            $default['items_'.$item_index.'_tooltip'] = sprintf(esc_html__('Pay safely with %s', 'xstore'), $safe_payments_method_name);
        }

        $default['items'] = implode(',', $default['items']);
		
		$local_settings = $default;
		
		if (count($settings) && isset($settings[self::$option_name])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
		}

        if ( !isset($local_settings['items']) ) return;
        $items = explode(',', $local_settings['items']);
        if ( count($items) < 1) return;

        foreach ($items as $item) {
            $payment_method = isset($local_settings[$item.'_payment_method']) ? $local_settings[$item.'_payment_method'] : array_key_first($safe_payments_methods);
            $payment_method_image = isset($local_settings[$item.'_custom_image']) && !empty($local_settings[$item.'_custom_image']) ? $local_settings[$item.'_custom_image'] : '';
            if ( '' == $payment_method_image ) {
                $payment_method = $payment_method == 'custom' ? array_key_first($safe_payments_methods) : $payment_method;
                $payment_method_image = ETHEME_BASE_URI.'images/woocommerce/payment-icons/'.$payment_method.'.jpeg';
            }
            $tooltip = isset($local_settings[$item.'_tooltip']) ? $local_settings[$item.'_tooltip'] : false;
            $local_settings['payments'][] = array(
                'name' => $payment_method,
                'image' => $payment_method_image,
                'tooltip' => $tooltip
            );
        }

        $this->settings = wp_parse_args( $custom_settings, $local_settings );
        $this->settings = wp_parse_args( $custom_settings, $this->settings );

        if ( $this->settings['text_before_highlight_color'] && $this->settings['text_before_highlight_color'] != $default['text_before_highlight_color'] )
            $this->local_styles[] = '.sales-booster-safe-checkout legend .highlight {color:' . $this->settings['text_before_highlight_color'].'}';
        if ( $this->settings['text_after_highlight_color'] && $this->settings['text_after_highlight_color'] != $default['text_after_highlight_color'] )
            $this->local_styles[] = '.sales-booster-safe-checkout > .highlight {color:' . $this->settings['text_after_highlight_color'].'}';
	}

    /**
     * Output the content of payments.
     * @since 8.3.9
     */
	public function output() {

        $settings = apply_filters('et_'.self::$option_name.'_settings', $this->settings, $this->args);
        if ( count($settings['payments']) < 1 ) return;
        if ( count($this->local_styles) )
            wp_add_inline_style( 'xstore-inline-css', implode('', $this->local_styles) );
        ?>
		<div class="sales-booster-safe-checkout">
            <fieldset>
                <?php
                if ( $settings['text_before'] )
                    echo '<legend>'.str_replace(array('{{', '}}'), array('<span class="highlight">','</span>'), esc_html($settings['text_before'])).'</legend>';

                foreach ($settings['payments'] as $payment) {
                    if ( !isset($payment['image']) || !$payment['image'] ) continue;
                    $has_tooltip = $settings['tooltips'] == 'on' && !empty($payment['tooltip']); ?>
                    <span<?php if ( $has_tooltip ) : ?> class="mtips mtips-top"<?php endif; ?>>
                        <img src="<?php echo esc_url($payment['image']); ?>" alt="<?php echo !empty($payment['tooltip']) ? esc_attr($payment['tooltip']) : esc_attr($payment['name']); ?>">
                        <?php if ( $has_tooltip ) : ?>
                            <span class="mt-mes"><?php echo esc_html($payment['tooltip']); ?></span>
                        <?php endif; ?>
                    </span>
                    <?php }
            ?>
            </fieldset>
            <?php
            if ( $settings['text_after'] )
                echo str_replace(array('{{', '}}'), array('<span class="highlight">','</span>'), esc_html($settings['text_after']));
            ?>
        </div>
		<?php
	}

    /**
     * Outputs the content of payments with parsing for custom params from shortcode attributes.
     * @param array $atts
     * @return false|string
     * @since 8.3.9
     */
	public function shortcode_output($atts=array()) {
        $atts = is_array($atts) ? $atts : array();

	    if ( count($this->settings) < 1)
            $this->set_settings();

	    if ( isset($atts['payments']) ) {
            $atts['payments_ready'] = array();
	        $atts['payments'] = explode(';', $atts['payments']);
	        foreach ($atts['payments'] as $payment) {
	            if ( !$payment ) continue;
	            $payment = explode(',', $payment);
	            $payment_ready = array();
	            foreach ($payment as $payment_args) {
                    list($k, $v) = explode(': ', $payment_args);
                    $payment_ready[trim($k)] = trim($v);
                }
                $atts['payments_ready'][] = $payment_ready;
            }
	        $atts['payments'] = $atts['payments_ready'];
	        unset($atts['payments_ready']);
        }

        $this->settings = wp_parse_args($atts, $this->settings);
//        var_dump($this->settings);
        ob_start();
        $this->output();
        return ob_get_clean();
	}
	
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  8.3.9
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
	
}