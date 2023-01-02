<?php
/**
 * Sales booster estimated delivery feature
 *
 * @package    sales_booster_estimated_delivery.php
 * @since      8.3.5
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Etheme_Sales_Booster_Estimated_Delivery {
	
	public static $instance = null;
	
	public static $option_name = 'estimated_delivery';
	
	public $args = array(
	        'is_local_product' => false,
	        'product_id' => null,
	        'tag' => 'div',
            'should_render' => true
    );
	
	public $settings = array();
	
	public function __construct() {
	}
	
	public function init($product = null) {
		if ( !class_exists('WooCommerce')) return;
		if ( !get_option('xstore_sales_booster_settings_'.self::$option_name) ) return;
        $this->args['tab_prefix'] = 'et_'.self::$option_name;
		$this->args['product'] = $product;
		if ( $this->args['product'] )
            $this->args['product_id'] = $this->args['product']->get_ID();
		
		$this->args['is_admin'] = is_admin();
		
		$this->args['spb'] = !!get_option( 'etheme_single_product_builder', false );
		add_action('wp', array($this, 'add_actions'));
		
		if ( $this->args['is_admin'] ) {
			add_action( 'woocommerce_product_write_panel_tabs', array($this, 'panel_tab') );
			add_action( 'woocommerce_product_data_panels', array($this, 'panel_data') );
			add_action( 'woocommerce_process_product_meta', array($this, 'save_panel_data') );
        }
        add_action('init', function () {
            add_shortcode('etheme_sales_booster_'.self::$option_name, array($this, 'shortcode_output'));
        });
	}
	
	public function add_actions() {

		if( $this->args['product'] ){
			$this->args['is_local_product'] = true;
		}
		elseif ( is_singular('product') ) {
			$this->args['product'] = wc_get_product();
			$this->args['product_id'] = $this->args['product']->get_ID();
		}
		else {
			return;
		}
		
		$this->set_settings();
		
		if ( !$this->args['is_local_product']) {
			$action       = 'woocommerce_product_meta_end';
			$priority     = 15;
			$apply_action = true;
			if ( $this->args['spb'] ) {
				$action   = 'etheme_woocommerce_template_single_excerpt';
				$priority = 5;
			}
			switch ( $this->settings['position'] ) {
				case 'before_cart_form':
					$action   = 'woocommerce_before_add_to_cart_form';
					$priority = 5;
					break;
				case 'after_cart_form':
					$action   = 'woocommerce_after_add_to_cart_form';
					$priority = 15;
					break;
				case 'before_woocommerce_share':
					$action   = 'woocommerce_share';
					$priority = - 999;
					break;
				case 'after_woocommerce_share':
					$action   = 'woocommerce_share';
					$priority = 999;
					break;
				case 'before_product_meta':
					$this->args['tag'] = 'span';
					$action            = 'woocommerce_product_meta_start';
					$priority          = 5;
					break;
				case 'after_product_meta':
					$this->args['tag'] = 'span';
					$action            = 'woocommerce_product_meta_end';
					$priority          = 15;
					break;
				case 'shortcode':
					$apply_action = false;
					break;
				default:
					if ( $this->args['spb'] ) {
						switch ( $this->settings['position'] ) {
							case 'before_atc':
								$action   = 'etheme_woocommerce_template_single_add_to_cart';
								$priority = 5;
								break;
							case 'after_atc':
								$action   = 'etheme_woocommerce_template_single_add_to_cart';
								$priority = 15;
								break;
							case 'before_excerpt':
								$action   = 'etheme_woocommerce_template_single_excerpt';
								$priority = 5;
								break;
							case 'after_excerpt':
								$action   = 'etheme_woocommerce_template_single_excerpt';
								$priority = 15;
								break;
						}
					}
					break;
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
	}
	
	public function set_settings($custom_settings = array()) {
		$settings = (array)get_option('xstore_sales_booster_settings', array());
		
		$default = array(
			'text_before' => esc_html__('Estimated delivery:', 'xstore'),
			'date_type' => 'days',
			'date_format' => get_option( 'date_format' ),
			'exclude_dates' => array(),
			'min_days' => 3,
			'max_days' => 5,
			'days' => 3,
			'days_type' => 'number',
			'non_working_days' => array(),
			'only_for' => array(),
			'locale' => false,
			'locale_format' => '%A, %b %d',
			'position' => $this->args['spb'] ? 'after_excerpt' : 'after_summary',
		);
		
		$estimated_delivery_only_for = wc_get_product_stock_status_options();
		
		$local_settings = $default;
		
		if (count($settings) && isset($settings[self::$option_name])) {
			$local_settings = wp_parse_args( $settings[ self::$option_name ], $default );
		}
		else {
		    $local_settings[self::$option_name . '_day_off_saturday'] = 'on';
			$local_settings[self::$option_name . '_day_off_sunday'] = 'on';
        }
		
		foreach (array(
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
			'sunday',
		) as $day_off ) {
		    if ( array_key_exists(self::$option_name.'_day_off_'.$day_off, $local_settings))
			    $local_settings['non_working_days'][] = ucfirst($day_off);
        }
		
		foreach (array_keys($estimated_delivery_only_for) as $estimated_delivery_only_for_item ) {
			if ( array_key_exists(self::$option_name.'_only_for_'.$estimated_delivery_only_for_item, $local_settings)) {
				$local_settings['only_for'][] = $estimated_delivery_only_for_item;
				unset($local_settings[self::$option_name.'_only_for_'.$estimated_delivery_only_for_item]);
			}
		}
		
		
		// single product page backend custom options
		$product_custom_options = array();
		foreach ( array('text_before', 'min_days', 'max_days', 'days' ) as $product_custom_option_key ) {
		    $option_value = get_post_meta( $this->args['product_id'], $this->args['tab_prefix'].'_'.$product_custom_option_key, true );
		    if ( $option_value )
		        $product_custom_options[$product_custom_option_key] = $option_value;
		}
		
		$this->settings = wp_parse_args( $product_custom_options, $local_settings );
		$this->settings = wp_parse_args( $custom_settings, $this->settings );
		
        if ( !$this->args['is_admin'] && count( $this->settings['only_for'] ) && isset($this->args['product']) && $this->args['product'] ) {
            $this->args['should_render'] = false;
	        $this->args['product_stock_status'] = $this->args['product']->get_stock_status();
	        foreach ( array_keys($estimated_delivery_only_for) as $estimated_delivery_only_for_item ) {
                if ( ($this->args['product_stock_status'] == $estimated_delivery_only_for_item) && in_array( $estimated_delivery_only_for_item, $this->settings['only_for'] ) ) {
	                $this->args['should_render'] = true;
                }
            }
        }
        
        // all days are non-working hmm good shop
        if ( count($local_settings['non_working_days']) >= 7) {
	        $this->args['should_render'] = false;
        }
	}
	
	public function output() {
	    if ( !$this->args['should_render'] ) return;

        $settings = apply_filters($this->args['tab_prefix'].'_settings', $this->settings, $this->args);

		?>
		<<?php echo esc_attr($this->args['tag']) ?> class="sales-booster-estimated-delivery">
            <?php echo esc_html($settings['text_before']); ?>
            <?php $this->calculated_date($settings); ?>
        </<?php echo esc_attr($this->args['tag']) ?>>
		<?php
	}
	
	public function shortcode_output($atts=array()) {
        $atts = is_array($atts) ? $atts : array();

	    if ( count($this->settings) < 1)
            $this->set_settings();

        if ( isset($atts['exclude_dates']))
            $atts['exclude_dates'] = explode(', ', $atts['exclude_dates']);
	    if ( isset($atts['non_working_days']))
            $atts['non_working_days'] = explode(', ', $atts['non_working_days']);

        $atts['only_for'] = array();

        $this->settings = wp_parse_args($atts, $this->settings);
		if ( !$this->args['should_render'] ) return;
        ob_start();
        $this->output();
        return ob_get_clean();
	}
	
	public function calculated_date($calculate_settings) {
	    $today = strtotime('today');
//		$today = strtotime(get_gmt_from_date( gmdate( 'Y-m-d H:i', strtotime('today') + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ) . ':00'));

	    $html = '';
	    if ( $calculate_settings['date_type'] == 'range') {
	        if ( $calculate_settings['days_type'] == 'number' )
	            $html .= $calculate_settings['min_days'] . ' - ' . $calculate_settings['max_days'] .' ' . esc_html__('days', 'xstore');
	        else
		        $html .= $this->calculate_week_day($today, absint($calculate_settings['min_days']), $calculate_settings['non_working_days'] ) . ' - ' . $this->calculate_week_day($today, absint($calculate_settings['max_days']), $calculate_settings['non_working_days'] );
	    }
	    else {
		    if ( $calculate_settings['days_type'] == 'number' )
			    $html .= $calculate_settings['days'] . ' ' . esc_html__('days', 'xstore');
		    else
			    $html .= $this->calculate_week_day($today, absint($calculate_settings['days']), $calculate_settings['non_working_days'] );
		}
	    
	    echo '<span class="delivery-date">'.$html.'</span>';
	}
	
	function calculate_week_day($timestamp, $days, $skipdays = []) {
		
		// limit to n days
//		if( $days > $this->settings['days'] ){
//			$days = $this->settings['days'];
//		}
		
		$i = 1;
		
		while ($days >= $i) {
			$timestamp = strtotime("+1 day", $timestamp);
			if ( (in_array(date("l", $timestamp), $skipdays)) || (in_array(date("Y-m-d", $timestamp), $this->settings['exclude_dates'])) )
			{
				$days++;
			}
			$i++;
		}
		
		if( $this->settings['locale'] ){

			setlocale(LC_TIME, get_locale());

			if( apply_filters( 'xstore/sales_booster/estimated_delivery/utf8_encode', false) ){
				return utf8_encode( strftime($this->settings['locale_format'], $timestamp) );
			}

			return strftime($this->settings['locale_format'], $timestamp);
		}
		
		return date($this->settings['date_format'], $timestamp);
	}
	
	public function panel_tab() {
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		$label = 'XStore';
		if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
			if ( $xstore_branding_settings['control_panel']['icon'] )
				$icon = $xstore_branding_settings['control_panel']['icon'];
			if ( $xstore_branding_settings['control_panel']['label'] )
				$label = $xstore_branding_settings['control_panel']['label'];
		}
		?>
        <li class="<?php echo esc_attr($this->args['tab_prefix']); ?>_options <?php echo esc_attr($this->args['tab_prefix']); ?>_tab hide_if_virtual hide_if_external">
            <a href="#<?php echo esc_attr($this->args['tab_prefix']); ?>_data"><span>
            <?php echo esc_html__( 'Estimated delivery', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="margin: 0; margin-inline-start: 5px; background: var(--et_admin_main-color, #A4004F); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.$label.'</span>'; ?>
            </span></a>
        </li>
		<?php
	}
	
	public function panel_data() {
		global $post;
		$this->set_settings();
		?>
        <div id="<?php echo esc_attr($this->args['tab_prefix']); ?>_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
					<?php
					woocommerce_wp_text_input(
						array(
							'id'          => $this->args['tab_prefix'].'_text_before',
							// 'value'       => get_post_meta( $product_object->ID, '_et_gtin', true ),
							'placeholder'   => $this->settings['text_before'],
							'label'         => esc_html__( 'Text before', 'xstore' ),
							'description'   => __( 'Write title for estimated delivery output. By default it inherits from main settings in Sales Booster section', 'xstore' ),
							'desc_tip'          => true,
						)
					);
					
					if ( $this->settings['date_type'] == 'range' ) {
						
						woocommerce_wp_text_input(
							array(
								'id'                => $this->args['tab_prefix'].'_min_days',
								'label'             => esc_html__( 'Min days', 'xstore' ),
								'placeholder'       => $this->settings['min_days'],
								'description'       => esc_html__('Set minimum count of days. In other words: From X days to y days. By default it inherits from main settings in Sales Booster section', 'xstore'),
								'desc_tip'          => true,
								'class'             => 'short',
								'type'              => 'number',
								'custom_attributes' => array(
									'step' => 1,
									'min'  => 0,
								),
							)
						);
						woocommerce_wp_text_input(
							array(
								'id'                => $this->args['tab_prefix'].'_max_days',
								'label'             => esc_html__( 'Max days', 'xstore' ),
								'placeholder'       => $this->settings['max_days'],
								'description'       => esc_html__('Set max count of days. In other words: From x days to Y days. By default it inherits from main settings in Sales Booster section', 'xstore'),
								'desc_tip'          => true,
								'class'             => 'short',
								'type'              => 'number',
								'custom_attributes' => array(
									'step' => 1,
									'min'  => 0,
								),
							)
						);
						
					}
					
					else {
						woocommerce_wp_text_input(
							array(
								'id'                => $this->args['tab_prefix'].'_days',
								'label'             => esc_html__( 'Days', 'xstore' ),
								'placeholder'       => $this->settings['days'],
								'description'       => esc_html__('Set count of days. By default it inherits from main settings in Sales Booster section', 'xstore'),
								'desc_tip'          => true,
								'class'             => 'short',
								'type'              => 'number',
								'custom_attributes' => array(
									'step' => 1,
									'min'  => 0,
								),
							)
						);
					}
					
					
					?>
                </p>
            </div>
        </div>
		<?php
	}
	
	public function save_panel_data( $post_id ) {
		$text_before = isset( $_POST[$this->args['tab_prefix'].'_text_before'] ) ? $_POST[$this->args['tab_prefix'].'_text_before'] : '';
		if ( $text_before )
			update_post_meta( $post_id, $this->args['tab_prefix'].'_text_before', $text_before );
		else
			delete_post_meta( $post_id, $this->args['tab_prefix'].'_text_before' );
		$min_days = isset( $_POST[$this->args['tab_prefix'].'_min_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_min_days'] : '';
		if ( $min_days )
			update_post_meta( $post_id, $this->args['tab_prefix'].'_min_days', $min_days );
		else
			delete_post_meta( $post_id, $this->args['tab_prefix'].'_min_days' );
		$max_days = isset( $_POST[$this->args['tab_prefix'].'_max_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_max_days'] : '';
		if ( $max_days )
			update_post_meta( $post_id, $this->args['tab_prefix'].'_max_days', $max_days );
		else
			delete_post_meta( $post_id, $this->args['tab_prefix'].'_max_days' );
		$days = isset( $_POST[$this->args['tab_prefix'].'_days'] ) ? (int)$_POST[$this->args['tab_prefix'].'_days'] : '';
		if ( $days )
			update_post_meta( $post_id, $this->args['tab_prefix'].'_days', $days );
		else
			delete_post_meta( $post_id, $this->args['tab_prefix'].'_days' );
	}
	
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  8.4.5
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
	
}