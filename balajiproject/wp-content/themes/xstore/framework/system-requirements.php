<?php if ( ! defined( 'ETHEME_FW' ) ) {
	exit( 'No direct script access allowed' );
}

// **********************************************************************//
// ! System Requirements
// **********************************************************************//

class Etheme_System_Requirements {
	
	// ! Requirements
	public $requirements = array(         // ! Defaults
		'php'             => '7.4',     // ! 7.0
		'wp'              => '5.8.2',     // ! 3.9
		'ssl_version'     => '1.0',     // ! 1.0
		'wp_uploads'      => true,     // ! true
		'memory_limit'    => '128M',     // ! 128M
		'time_limit'      => array(
			'min' => 120,                 // ! 30
			'req' => 180                 // ! 60
		),
		'max_input_vars'  => array(
			'min' => 1000,                 // ! 1000
			'req' => 2000                 // ! 2000
		),
		'upload_filesize' => '10M',     // ! 10M
		'filesystem'      => 'direct', // ! direct
		'wp_remote_get'   => true,     // ! true
		'f_get_contents'  => true,     // ! true
		'gzip'            => true     // ! true
	);
	
	// ! Let's think that all alright
	public $result = true;
	
	// ! Just leave it here
	function __construct() {
	}
	
	// ! Return requirements
	public function get_requirements() {
		return $this->requirements;
	}
	
	// ! Return icon class, set result
	public function check( $type ) {
		if ( $type ) {
			return 'yes';
		} else {
			$this->result = false;
			
			return 'warning';
		}
		
		return $type;
	}
	
	// ! Return result. Note call it only after "html or system_test" functions!
	public function result() {
		return $this->result;
	}
	
	// ! Return system information
	public function get_system() {
		global $wp_version;
		
		$f_get_contents = str_replace( ' ', '_', 'file get contents' );
		$system         = array(
			'php'             => PHP_VERSION,
			'wp'              => $wp_version,
			'curl_version'    => ( function_exists( 'curl_version' ) ) ? curl_version() : false,
			'ssl_version'     => '',
			'wp_uploads'      => wp_get_upload_dir(),
			'upload_filesize' => ini_get( 'upload_max_filesize' ),
			'memory_limit'    => ini_get( 'memory_limit' ),
			'time_limit'      => ini_get( 'max_execution_time' ),
			'max_input_vars'  => ini_get( 'max_input_vars' ),
			'filesystem'      => get_filesystem_method(),
			'wp_remote_get'   => function_exists( 'wp_remote_get' ),
			'f_get_contents'  => function_exists( 'file_get_contents' ),
			'gzip'            => is_callable( 'gzopen' )
		);

		if ($system['memory_limit'] == -1) {
			$system['memory_limit'] = WP_MEMORY_LIMIT;
		}
		
		if ( isset( $system['curl_version']['ssl_version'] ) ) {
			$system['ssl_version'] = $system['curl_version']['ssl_version'];
			$system['ssl_version'] = preg_replace( '/[^.0-9]/', '', $system['ssl_version'] );
		} else if ( extension_loaded( 'openssl' ) && defined( 'OPENSSL_VERSION_NUMBER' ) ) {
			$system['ssl_version'] = $this->get_openssl_version_number( true );
		} else {
			$system['ssl_version'] = 'undefined';
		}
		
		return $system;
	}
	
	// ! test system
	public function system_test() {
		$system = $this->get_system();
		( $system['filesystem'] === $this->requirements['filesystem'] ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['php'], $this->requirements['php'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['wp'], $this->requirements['wp'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( wp_convert_hr_to_bytes( $system['memory_limit'] ) >= wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? $this->check( true ) : $this->check( false );
		( $system['time_limit'] >= $this->requirements['time_limit']['min'] ) ? $this->check( true ) : $this->check( false );
		( $system['max_input_vars'] >= ( $this->requirements['max_input_vars']['min'] ) ) ? $this->check( true ) : $this->check( false );
		( wp_convert_hr_to_bytes( $system['upload_filesize'] ) >= wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? $this->check( true ) : $this->check( false );
		( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? $this->check( true ) : $this->check( false );
		( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '>=' ) ) ? $this->check( true ) : $this->check( false );
		( $system['gzip'] == $this->requirements['gzip'] ) ? $this->check( true ) : $this->check( false );
		( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? $this->check( true ) : $this->check( false );
		( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? $this->check( true ) : $this->check( false );
	}
	
	// ! Show html result
	public function html() {
		$system = $this->get_system();
		
		echo '<table class="system-requirements">';
		printf(
			'<thead><th class="requirement-headings environment">%1$s</th>
				<th>%2$s</th>
				<th>%3$s</th></thead>',
			esc_html__( 'Environment', 'xstore' ),
			esc_html__( 'Requirement', 'xstore' ),
			esc_html__( 'System', 'xstore' )
		);
		
		printf(
			'<tr class="wp-system %3$s">
					<td>' . esc_html__( 'WP File System:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			$this->requirements['filesystem'],
			$system['filesystem'],
			( $system['filesystem'] === $this->requirements['filesystem'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="php-version %3$s">
					<td>' . esc_html__( 'PHP version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['php'],
			$system['php'],
			( version_compare( $system['php'], $this->requirements['php'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['php'], $this->requirements['php'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="wp-version %3$s">
					<td>' . esc_html__( 'WordPress version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['wp'],
			$system['wp'],
			( version_compare( $system['wp'], $this->requirements['wp'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['wp'], $this->requirements['wp'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="memory-limit %3$s">
					<td>' . esc_html__( 'Memory limit:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['memory_limit'],
			$system['memory_limit'],
			( wp_convert_hr_to_bytes( $system['memory_limit'] ) >= wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? $this->check( true ) : $this->check( false ),
			( wp_convert_hr_to_bytes( $system['memory_limit'] ) === wp_convert_hr_to_bytes( $this->requirements['memory_limit'] ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="execution-time %1$s %2$s">
					<td>' . esc_html__( 'Max execution time:', 'xstore' ) . '</td>
					<td>min (%3$s-%4$s)</td>
					<td>%5$s<span class="dashicons dashicons-%6$s %7$s"></td>
				</tr>',
			( $system['time_limit'] >= $this->requirements['time_limit']['req'] ) ? '' : 'warning',
			( (int) $system['time_limit'] === (int) $this->requirements['time_limit']['min'] ) ? 'min' : '',
			$this->requirements['time_limit']['min'],
			$this->requirements['time_limit']['req'],
			$system['time_limit'],
			( $system['time_limit'] >= $this->requirements['time_limit']['min'] ) ? $this->check( true ) : $this->check( false ),
			( $system['time_limit'] >= $this->requirements['time_limit']['req'] ) ? '' : 'dashicons-warning'
		);
		
		printf(
			'<tr class="input-vars %1$s %2$s">
					<td>' . esc_html__( 'Max input vars:', 'xstore' ) . '</td>
					<td>min (%3$s-%4$s)</td>
					<td>%5$s<span class="dashicons dashicons-%6$s %7$s"></span></td>
				</tr>',
			( $system['max_input_vars'] >= $this->requirements['max_input_vars']['req'] ) ? '' : 'warning',
			( (int) $system['max_input_vars'] === (int) $this->requirements['max_input_vars']['min'] ) ? 'min' : '',
			$this->requirements['max_input_vars']['min'],
			$this->requirements['max_input_vars']['req'],
			$system['max_input_vars'],
			( $system['max_input_vars'] >= ( $this->requirements['max_input_vars']['min'] ) ) ? $this->check( true ) : $this->check( false ),
			( $system['max_input_vars'] >= $this->requirements['max_input_vars']['req'] ) ? '' : 'dashicons-warning'
		);
		
		printf(
			'<tr class="filesize %3$s">
					<td>' . esc_html__( 'Upload max filesize:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['upload_filesize'],
			$system['upload_filesize'],
			( wp_convert_hr_to_bytes( $system['upload_filesize'] ) >= wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? $this->check( true ) : $this->check( false ),
			( (int) wp_convert_hr_to_bytes( $system['upload_filesize'] ) === (int) wp_convert_hr_to_bytes( $this->requirements['upload_filesize'] ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="uploads-folder %3$s">
					<td>' . esc_html__( '../Uploads folder:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			'writable',
			( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? 'writable' : 'unwritable',
			( wp_is_writable( $system['wp_uploads']['basedir'] ) === $this->requirements['wp_uploads'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="ssl-version %3$s">
					<td>' . esc_html__( 'OpenSSL version:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s %4$s"></span></td>
				</tr>',
			$this->requirements['ssl_version'],
			$system['ssl_version'],
			( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '>=' ) ) ? $this->check( true ) : $this->check( false ),
			( version_compare( $system['ssl_version'], $this->requirements['ssl_version'], '==' ) ) ? 'min' : ''
		);
		
		printf(
			'<tr class="gzip-compression %3$s">
					<td>' . esc_html__( 'GZIP compression:', 'xstore' ) . '</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			'enable',
			( $system['gzip'] == $this->requirements['gzip'] ) ? 'enable' : 'disable',
			( $system['gzip'] == $this->requirements['gzip'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="function-f_get_contents %3$s">
					<td>' . str_replace( ' ', '_', 'file get contents' ) . '( ):</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			( $this->requirements['f_get_contents'] ) ? 'enable' : 'disable',
			( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? 'enable' : 'disable',
			( $system['f_get_contents'] == $this->requirements['f_get_contents'] ) ? $this->check( true ) : $this->check( false )
		);
		
		printf(
			'<tr class="function-wp_remote_get %3$s">
					<td>wp_remote_get( ):</td>
					<td>%1$s</td>
					<td>%2$s<span class="dashicons dashicons-%3$s "></span></td>
				</tr>',
			( $this->requirements['wp_remote_get'] ) ? 'enable' : 'disable',
			( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? 'enable' : 'disable',
			( $system['wp_remote_get'] == $this->requirements['wp_remote_get'] ) ? $this->check( true ) : $this->check( false )
		);
		echo '</table>';
	}
	
	public function get_openssl_version_number( $patch_as_number = false, $openssl_version_number = null ) {
		if ( is_null( $openssl_version_number ) ) {
			$openssl_version_number = OPENSSL_VERSION_NUMBER;
		}
		$openssl_numeric_identifier = str_pad( (string) dechex( $openssl_version_number ), 8, '0', STR_PAD_LEFT );
		
		$openssl_version_parsed = array();
		$preg                   = '/(?<major>[[:xdigit:]])(?<minor>[[:xdigit:]][[:xdigit:]])(?<fix>[[:xdigit:]][[:xdigit:]])';
		$preg                   .= '(?<patch>[[:xdigit:]][[:xdigit:]])(?<type>[[:xdigit:]])/';
		preg_match_all( $preg, $openssl_numeric_identifier, $openssl_version_parsed );
		$openssl_version = false;
		if ( ! empty( $openssl_version_parsed ) ) {
			$alphabet        = array(
				1  => 'a',
				2  => 'b',
				3  => 'c',
				4  => 'd',
				5  => 'e',
				6  => 'f',
				7  => 'g',
				8  => 'h',
				9  => 'i',
				10 => 'j',
				11 => 'k',
				12 => 'l',
				13 => 'm',
				14 => 'n',
				15 => 'o',
				16 => 'p',
				17 => 'q',
				18 => 'r',
				19 => 's',
				20 => 't',
				21 => 'u',
				22 => 'v',
				23 => 'w',
				24 => 'x',
				25 => 'y',
				26 => 'z'
			);
			$openssl_version = intval( $openssl_version_parsed['major'][0] ) . '.';
			$openssl_version .= intval( $openssl_version_parsed['minor'][0] ) . '.';
			$openssl_version .= intval( $openssl_version_parsed['fix'][0] );
			$patchlevel_dec  = hexdec( $openssl_version_parsed['patch'][0] );
			if ( ! $patch_as_number && array_key_exists( $patchlevel_dec, $alphabet ) ) {
				$openssl_version .= $alphabet[ $patchlevel_dec ]; // ideal for text comparison
			} else {
				$openssl_version .= '.' . $patchlevel_dec; // ideal for version_compare
			}
		}
		
		return $openssl_version;
	}
}