<?php
/**
 * Conditional display feature for Elementor templates
 *
 * @package    Conditional-display.php
 * @since      4.3.1
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


namespace ETC\App\Controllers\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class Conditional_Display {
	
	public $prefix = 'etheme_conditions';
	
	function __construct() {
		add_action( 'elementor/element/common/_section_style/before_section_start', array( $this, 'register_controls' ) );
//		add_action( 'elementor/element/column/section_advanced/before_section_start', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/section/section_advanced/before_section_start', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/container/section_layout/before_section_start', array( $this, 'register_controls' ) );
		add_filter( 'elementor/frontend/widget/should_render', array( $this, 'content_render' ), 10, 2 );
//		add_filter( 'elementor/frontend/column/should_render', array( $this, 'content_render' ), 10, 2 );
		add_filter( 'elementor/frontend/section/should_render', array( $this, 'content_render' ), 10, 2 );
		add_filter( 'elementor/frontend/container/should_render', array( $this, 'content_render' ), 10, 2 );
	}
	
	public function register_controls( $element ) {
		
		$prefix = $this->prefix;
		
		$element->start_controls_section(
			'_section_'.$prefix,
			[
				'label' => __( 'XSTORE Conditions', 'xstore-core' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);
		
		$element->add_control(
			$prefix,
			[
				'label'     => __( 'Enable Conditions', 'xstore-core' ),
				'type'      => Controls_Manager::SWITCHER,
			]
		);
		
		$element->add_control(
			$prefix . '_show_hide',
			[
				'label'          => __( 'Show/Hide action', 'xstore-core' ),
				'description'    => __( 'Determine if the element should be hidden or shown when the conditions are met.', 'xstore-core' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'show',
				'options'        => [
					'show' => __( 'Show', 'xstore-core' ),
					'hide'    => __( 'Hide', 'xstore-core' ),
				],
				'condition'      => [
					$prefix => 'yes',
				],
				'style_transfer' => false,
			]
		);
		
//		$element->add_control(
//			$prefix.'_time_zone',
//			[
//				'label'       => __( 'Timezone', 'xstore-core' ),
//				'type'        => Controls_Manager::SELECT,
//				'description' => __('It will be used for date/time conditions', 'xstore-core') . '. ' .__( 'You can change', 'xstore-core' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', admin_url('options-general.php'), __( 'Server settings', 'xstore-core' ) ),
//				'default'     => 'server',
//				'options'     => [
//					'server' => __( 'Server Timezone', 'xstore-core' ),
//					'local'  => __( 'Local Timezone', 'xstore-core' ),
//				],
//				'condition'      => [
//					$prefix => 'yes',
//				],
//			]
//		);
		
		$element->add_control(
			$prefix.'_condition_by',
			[
				'label' => __( 'Conditions By', 'xstore-core' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => ['browser-type', 'login-status'],
				'multiple' => true,
				'options' => self::get_conditions_by(),
				'condition'      => [
					$prefix => 'yes',
				],
			]
		);
		
		$element->add_control(
			$prefix . '_condition_type',
			[
				'label'          => __( 'Conditions Type', 'xstore-core' ),
				'description'    => __( 'If ALL conditions need to be met or JUST ONE in order to trigger the hide/show action.', 'xstore-core' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => [
					'all' => __( 'All', 'xstore-core' ),
					'any' => __( 'At least one', 'xstore-core' ),
				],
				'default'        => 'all',
				'condition'      => [
					$prefix => 'yes',
				],
				'style_transfer' => false,
			]
		);
		
		foreach (self::get_conditions_by() as $cond_key => $cond_text) {
			$show_label = true;
			$description = false;
			$show_operators = false;
			$default = '';
			switch ($cond_key) {
				case 'user-role':
					$show_operators = true;
					$description_operators = __('Controls if the customer\'s user-role is in the list set above or is not.', 'xstore-core');
					global $wp_roles;
					$description = sprintf('<strong>%s</strong>%s',__( 'Note: ', 'xstore-core' ),__( 'This condition applies only to logged in users.', 'xstore-core' ));
					$default = ['subscriber'];
					$options = $wp_roles->get_names();
					
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'show_label' => $show_label,
							'description' => $description,
							'type' => Controls_Manager::SELECT2,
							'default' => $default,
							'multiple' => true,
							'options' => $options,
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]
					);
					
					break;
					
				case 'day':
					$default = 'monday';
					$description = esc_html__('if set day is equal or greater then server\'s day then this condition is matched', 'xstore-core');
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
						'label' => $cond_text,
						'type' => Controls_Manager::SELECT,
						'description' => $description,
						'default' => $default,
						'options' => [
							'monday'    => __( 'Monday', 'xstore-core' ),
							'tuesday'   => __( 'Tuesday', 'xstore-core' ),
							'wednesday' => __( 'Wednesday', 'xstore-core' ),
							'thursday'  => __( 'Thursday', 'xstore-core' ),
							'friday'    => __( 'Friday', 'xstore-core' ),
							'saturday'  => __( 'Saturday', 'xstore-core' ),
							'sunday'    => __( 'Sunday', 'xstore-core' ),
						],
						'separator' => 'before',
						'condition'      => [
							$prefix => 'yes',
							$prefix.'_condition_by' => $cond_key,
						],
					]);
				break;
				
				case 'date':
					$default = date('d-m-Y');
					$description = esc_html__('if set date is equal or greater then server\'s date then this condition is matched', 'xstore-core');
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'type' => Controls_Manager::DATE_TIME,
							'description' => $description,
							'default' => $default,
							'picker_options' => [
								'enableTime'	=> false,
								'dateFormat' 	=> 'd-m-Y',
							],
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]);
					break;
					
				case 'time':
					$default = date('H:i');
					$description = esc_html__('if set time is equal or greater then server\'s time then this condition is matched', 'xstore-core');
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => __( 'Time', 'xstore-core' ),
							'type' => Controls_Manager::DATE_TIME,
							'description' => $description,
							'default' => $default,
							'picker_options' => [
								'noCalendar' 	=> true,
								'enableTime'	=> true,
								'dateFormat' 	=> "H:i",
							],
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]);
					break;
				
				case 'browser-type':
					$show_operators = true;
					$description_operators = __('Controls if the customer\'s browser is in the list set above or is not.', 'xstore-core');
					$default = ['chrome', 'safari'];
					$options = [
						'opera'			=> __( 'Opera', 'xstore-core' ),
						'edge'			=> __( 'Edge', 'xstore-core' ),
						'chrome'		=> __( 'Google Chrome', 'xstore-core' ),
						'safari'		=> __( 'Safari', 'xstore-core' ),
						'i_safari'		=> __( 'iPhone Safari', 'xstore-core' ),
						'firefox'		=> __( 'Mozilla Firefox', 'xstore-core' ),
						'ie'			=> __( 'Internet Explorer', 'xstore-core' ),
						'others'		=> __( 'Others', 'xstore-core' ),
					];
					
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'show_label' => $show_label,
							'description' => $description,
							'type' => Controls_Manager::SELECT2,
							'label_block' => true,
							'default' => $default,
							'multiple' => true,
							'options' => $options,
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]
					);
					break;
					
				case 'operating-system':
					$show_operators = true;
					$description_operators = __('Controls if the customer\'s operating system is in the list set above or is not.', 'xstore-core');
					$default = 'mac_os';
					$options = [
						'windows' => __( 'Windows', 'xstore-core' ),
						'mac_os' => __( 'Mac OS', 'xstore-core' ),
						'linux' => __( 'Linux', 'xstore-core' ),
						'ubuntu' => __( 'Ubuntu', 'xstore-core' ),
						'iphone' => __( 'iPhone', 'xstore-core' ),
						'ipod' => __( 'iPod', 'xstore-core' ),
						'ipad' => __( 'Android', 'xstore-core' ),
						'android' => __( 'iPad', 'xstore-core' ),
						'blackberry' => __( 'BlackBerry', 'xstore-core' ),
						'open_bsd' => __( 'OpenBSD', 'xstore-core' ),
						'sun_os' => __( 'SunOS', 'xstore-core' ),
						'safari' => __( 'Safari', 'xstore-core' ),
						'qnx' => __( 'QNX', 'xstore-core' ),
						'beos' => __( 'BeOS', 'xstore-core' ),
						'os2' => __( 'OS/2', 'xstore-core' ),
						'search_bot' => __( 'Search Bot', 'xstore-core' ),
					];
					
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'show_label' => $show_label,
							'description' => $description,
							'type' => Controls_Manager::SELECT2,
							'label_block' => true,
							'default' => $default,
							'multiple' => true,
							'options' => $options,
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]
					);
					break;
					
				case 'login-status':
					$default = 'login';
					$options = [
						'login' 		=> __( 'Logged In', 'xstore-core' ),
						'logout' 		=> __( 'Logged Out', 'xstore-core' ),
					];
					
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'show_label' => $show_label,
							'description' => $description,
							'type' => Controls_Manager::SELECT,
							'default' => $default,
							'options' => $options,
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]
					);
					break;
					
				case 'date-range':
					$default = date('d-m-Y').' to '.date('d-m-Y', strtotime("+ 2 day") );
					$description = esc_html__('if today is between start value and end value it return true otherwise false', 'xstore-core');
					$element->add_control(
						$prefix.'_condition_by'.$cond_key,
						[
							'label' => $cond_text,
							'type' => Controls_Manager::DATE_TIME,
							'description' => $description,
							'default' => $default,
							'picker_options' => [
								'enableTime'	=> false,
								'dateFormat' 	=> 'd-m-Y',
								'mode' 			=> 'range',
							],
							'separator' => 'before',
							'condition'      => [
								$prefix => 'yes',
								$prefix.'_condition_by' => $cond_key,
							],
						]
					);
					break;
					default;
			}
			
			if ( $show_operators ) {
				$element->add_control(
					$prefix . '_condition_by' . $cond_key . '_operator',
					[
						'label'       => __( 'Operator', 'xstore-core' ),
						'show_label'  => $show_label,
						'description' => $description_operators,
						'type'        => Controls_Manager::SELECT,
						'default'     => 'in',
						'options'     => array(
							'in'  => esc_html__( 'In', 'xstore-core' ),
							'not' => esc_html__( 'Not', 'xstore-core' ),
						),
						'condition'   => [
							$prefix                   => 'yes',
							$prefix . '_condition_by' => $cond_key,
						],
					]
				);
			}
			
		}
		
		$element->end_controls_section();
	}
	
	/**
	 * Get all possible condition values.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	public static function get_conditions_by() {
		return [
			'user-role' => __( 'User Role', 'xstore-core' ),
			'login-status' => __( 'Login Status', 'xstore-core' ),
			'browser-type' => __( 'Browser Type', 'xstore-core' ),
			'operating-system' => __( 'Operating System', 'xstore-core' ),
			'day' => __( 'Day', 'xstore-core' ),
			'date' => __( 'Date', 'xstore-core' ),
			'date-range' => __( 'Date Range', 'xstore-core' ),
			'time' => __( 'Time', 'xstore-core' ),
		];
	}
	
	/**
	 * Get current browser.
	 *
	 * @since 4.3.1
	 *
	 * @return string
	 */
	public function get_current_browser() {
		global $is_lynx, $is_gecko, $is_winIE, $is_macIE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone, $is_edge;
		
		$browser = 'others';
		
		switch ( true ) {
			case $is_chrome:
				$browser = 'chrome';
				break;
			case $is_gecko:
				$browser = 'firefox';
				break;
			case $is_safari:
				$browser = 'safari';
				break;
			case $is_iphone:
				$browser = 'i_safari';
				break;
			case $is_opera:
				$browser = 'opera';
				break;
			case $is_edge:
				$browser = 'edge';
				break;
			case $is_winIE:
				$browser = 'ie';
				break;
			case $is_macIE:
				$browser = 'mac_ie';
				break;
			case $is_NS4:
				$browser = 'netscape4';
				break;
			case $is_lynx:
				$browser = 'lynx';
				break;
			
		}
		
		return $browser;
	}
	
	/**
	 * Check all conditions and return result after checking each of them.
	 *
	 * @param $settings
	 * @return bool
	 *
	 * @throws \Exception
	 * @since 4.3.1
	 *
	 */
	public function check_conditions($settings) {
		$condition_type_any = $settings[$this->prefix . '_condition_type'] === 'any';
		$condition_type_all = $settings[$this->prefix . '_condition_type'] === 'all';
		
		$timezone = 'server';
		
		$match = false;
		
		foreach ($settings[$this->prefix.'_condition_by'] as $cond_key) {
			
			if ( $condition_type_any && $match ) {
				break; // stop going further if condition is on any and is matched
			}
			
			switch ($cond_key) {
				case 'user-role':
					if ( count((array)$settings[$this->prefix.'_condition_by'.$cond_key]) < 1) break(2);
					
					if ( is_user_logged_in() ) {
						$user  = wp_get_current_user();
						if ( count(array_intersect((array)$settings[$this->prefix.'_condition_by'.$cond_key], $user->roles)) ) {
							$match = true;
						}
					}
					
					if ( $settings[$this->prefix.'_condition_by'.$cond_key.'_operator'] == 'not' )
						$match = !$match;
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}

					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					break;
				case 'login-status':
					if ( is_user_logged_in() )
						$match = $settings[$this->prefix.'_condition_by'.$cond_key] == 'login';
					else
						$match = $settings[$this->prefix.'_condition_by'.$cond_key] == 'logout';
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}

					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
					
				case 'browser-type':
					if ( count((array)$settings[$this->prefix.'_condition_by'.$cond_key]) < 1) break(2);
					
					$match = in_array($this->get_current_browser(), (array)$settings[$this->prefix.'_condition_by'.$cond_key]);
					
					if ( $settings[$this->prefix.'_condition_by'.$cond_key.'_operator'] == 'not' )
						$match = !$match;
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}

					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
				
				case 'operating-system':
					if ( count((array)$settings[$this->prefix.'_condition_by'.$cond_key]) < 1) break(2);
					
					$os = [
						'windows' => '(Win16)|(Windows 95)|(Win95)|(Windows_95)|(Windows 98)|(Win98)|(Windows NT 5.0)|(Windows 2000)|(Windows NT 5.1)|(Windows XP)|(Windows NT 5.2)|(Windows NT 6.0)|(Windows Vista)|(Windows NT 6.1)|(Windows 7)|(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)|(Windows ME)',
						'mac_os' => '(Mac_PowerPC)|(Macintosh)|(mac os x)',
						'linux' => '(Linux)|(X11)',
						'ubuntu' => 'Ubuntu',
						'iphone' => 'iPhone',
						'ipod' => 'iPod',
						'ipad' => 'Android',
						'android' => 'iPad',
						'blackberry' => 'BlackBerry',
						'open_bsd' => 'OpenBSD',
						'sun_os' => 'SunOS',
						'safari' => '(Safari)',
						'qnx' => 'QNX',
						'beos' => 'BeOS',
						'os2' => 'OS/2',
						'search_bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
					];
					$operating_system_match = false;
					foreach ((array)$settings[$this->prefix.'_condition_by'.$cond_key] as $operating_system) {
						if ( $operating_system_match ) {
							break;
						}
						$pattern = '/' . $os[ $operating_system ] . '/i';
						$operating_system_match   = !!preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
						$match = $operating_system_match;
					}
					
					if ( $settings[$this->prefix.'_condition_by'.$cond_key.'_operator'] == 'not' )
						$match = !$match;
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}
					
					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
					
				case 'day':
					$today = 'local' === $timezone ? $this->get_local_time('l') : $this->get_server_time('l');
					
					$match = strtolower($today) == $settings[$this->prefix.'_condition_by'.$cond_key];
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}
					
					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
					
				case 'date':
					$date = strtotime($settings[$this->prefix.'_condition_by'.$cond_key]);
					
					$today = 'local' === $timezone ? $this->get_local_time('d-m-Y') : $this->get_server_time('d-m-Y');
					$today = strtotime($today);
					
					//if $today is equal to $date or greater then $date it return true otherwise false
					$match = ( ($today >= $date ) );
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}
					
					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
					
				case 'time':
					$time = strtotime($settings[$this->prefix.'_condition_by'.$cond_key]);
					
					$local_time = 'local' === $timezone ? $this->get_local_time('H:i') : $this->get_server_time('H:i');
					$local_time = strtotime($local_time);
					
					//if time is equal or greater then server time it return true
					$match = ( $time <= $local_time );
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}
					
					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
				
				case 'date-range':
					
					$range_date = explode( ' to ', $settings[$this->prefix.'_condition_by'.$cond_key] );
					if ( !is_array( $range_date ) || 2 !== count( $range_date ) ) break(2);
					
					$start = strtotime($range_date[0]);
					$end = strtotime($range_date[1]);
					
					$today = 'local' === $timezone ? $this->get_local_time('d-m-Y') : $this->get_server_time('d-m-Y');
					$today = strtotime($today);
					
					//if $today is between $start and $end it return true otherwise false
					$match = ( ($today >= $start ) && ( $today <= $end ) );
					
					if ( $condition_type_any && $match ) {
						break( 2 );
					}
					
					if ( $condition_type_all && ! $match ) {
						break( 2 );
					}
					
					break;
			}
		}
		
		return $match;
	}
	
	/**
	 * Get Client Site Time.
	 *
	 * @param string $format
	 * @return string
	 *
	 * @throws \Exception
	 * @since 4.3.1
	 *
	 */
	public function get_local_time($format = 'Y-m-d h:i:s A') {
		$local_time_zone = date_default_timezone_get();
		$now_date = new \DateTime('now', new \DateTimeZone($local_time_zone));
		return $now_date->format($format);
	}
	
	/**
	 * Get Server Time.
	 *
	 * @param string $format
	 * @return false|string
	 *
	 * @since 4.3.1
	 *
	 */
	function get_server_time($format = 'Y-m-d h:i:s A') {
		return date($format, strtotime("now") + (get_option('gmt_offset') * HOUR_IN_SECONDS));
	}
	
	/**
	 * Check and return if content should be shown.
	 *
	 * @param $should_render
	 * @param \Elementor\Element_Base $element
	 * @return bool|mixed
	 *
	 * @since 4.3.1
	 *
	 */
	public function content_render( $should_render, \Elementor\Element_Base $element ) {
		
		$settings = $element->get_settings_for_display();
		
		if ( !$settings[$this->prefix]) return $should_render;
		
		if ( count($settings[$this->prefix.'_condition_by']) < 1) return $should_render;
		
		$check_result = $this->check_conditions($settings);
		
		$to_show = $settings[$this->prefix.'_show_hide'] === 'show';
		
		if ( ( $to_show && $check_result ) || ( !$to_show && !$check_result ) ) {
			$should_render = true;
		} elseif ( ( $to_show && !$check_result ) || ( !$to_show && $check_result ) ) {
			$should_render = false;
		}
		
		return $should_render;
	}
}