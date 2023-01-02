<?php
/**
 * Description
 *
 * @package    optimization.php
 * @since      1.0.0
 * @author     theme
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */


class XStore_Optimization {
	
	public static $instance = null;
	
	function init(){
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_page_css_files' ), 99999 );
		add_action('wp_head', array($this, 'xstore_font_prefetch'));
		add_action( 'wp_head', array($this, 'seo'), 1);
		add_filter( 'wp_title', array($this,'wp_title'), 10, 2 );
		
		if (get_theme_mod( 'et_force_cache', false )){
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', 0 );
			}
			add_filter( 'rocket_override_donotcachepage', '__return_true', PHP_INT_MAX );
		}
		
		add_filter('language_attributes', array($this, 'add_opengraph_doctype'));
	}
	function enqueue_page_css_files(){

	    // Dequeue theme swiper-slider (enqueued in theme-init.php)
        if (etheme_get_option( 'disable_theme_swiper_js', false )){
            wp_dequeue_script( 'et_swiper-slider' );
        }
		
		// and not preview/edit view
		if ( defined( 'ELEMENTOR_VERSION' ) ) {

            // Enqueue elementor or theme swiper-slider
            if (etheme_get_option( 'disable_theme_swiper_js', false )) {
                require_once(apply_filters('etheme_file_url', ETHEME_CODE . 'features/swiper.php'));
            }
			
			if ( !(
				is_preview() ||
				Elementor\Plugin::$instance->preview->is_preview_mode()
			)
			) {
				
				if ( get_theme_mod( 'disable_elementor_dialog_js', 1 ) ) {
					$scripts = wp_scripts();
					if ( ! ( $scripts instanceof WP_Scripts ) ) {
						return;
					}
					
					$handles_to_remove = [
						'elementor-dialog',
					];
					
					$handles_updated = false;
					
					foreach ( $scripts->registered as $dependency_object_id => $dependency_object ) {
						if ( 'elementor-frontend' === $dependency_object_id ) {
							if ( ! ( $dependency_object instanceof _WP_Dependency ) || empty( $dependency_object->deps ) ) {
								return;
							}
							
							foreach ( $dependency_object->deps as $dep_key => $handle ) {
								if ( in_array( $handle, $handles_to_remove ) ) { // phpcs:ignore
									unset( $dependency_object->deps[ $dep_key ] );
									$dependency_object->deps = array_values( $dependency_object->deps );
									$handles_updated         = true;
								}
							}
						}
					}
					
					if ( $handles_updated ) {
						wp_deregister_script( 'elementor-dialog' );
						wp_dequeue_script( 'elementor-dialog' );
					}
				}
			}
		}
		
		if ( get_theme_mod( 'disable_block_css', 0 ) ){
			wp_deregister_style( 'wp-block-library' );
			wp_deregister_style( 'wp-block-library-theme' );
			wp_deregister_style( 'wc-block-style' );
			wp_deregister_style( 'wc-blocks-vendors-style' );

			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wc-blocks-style' );
			wp_dequeue_style( 'wc-blocks-editor-style' );
		}
	}
	
	public function xstore_font_prefetch() {
		$icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
		
		if ( apply_filters('etheme_preload_woff_icons', true)) : ?>
			<link rel="prefetch" as="font" href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/xstore-icons-<?php echo esc_attr($icons_type); ?>.woff?v=<?php echo esc_attr( ETHEME_THEME_VERSION ); ?>" type="font/woff">
		<?php endif;
		
		if ( apply_filters('etheme_preload_woff2_icons', true)) : ?>
			<link rel="prefetch" as="font" href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/xstore-icons-<?php echo esc_attr($icons_type); ?>.woff2?v=<?php echo esc_attr( ETHEME_THEME_VERSION ); ?>" type="font/woff2">
		<?php
		endif;
	}
	
	public function seo() {
		if (
		    get_query_var('et_is-woocommerce-archive', false) &&
			etheme_get_option( 'et_seo_noindex', 0 )
		) {
			
			$url = parse_url($_SERVER['REQUEST_URI']);
			if (isset($url['query'])){
				echo "\n\t\t<!-- 8theme SEO v1.0.0 -->";
				echo '<meta name="robots" content="noindex, nofollow">';
				echo "\t\t<!-- 8theme SEO -->\n\n";
			}
		}
	}
	
	public function wp_title($title, $sep ) {
		global $paged, $page;
		
		if ( is_feed() ) {
			return $title;
		}
		
		// Add the site name.
		$title .= get_bloginfo( 'name', 'display' );
		
		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}
		
		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'xstore' ), max( $paged, $page ) );
		}
		
		return $title;
	}
	
	public function add_opengraph_doctype($output) {
		$share_facebook = get_theme_mod('socials',array( 'share_twitter', 'share_facebook', 'share_vk', 'share_pinterest', 'share_mail', 'share_linkedin', 'share_whatsapp', 'share_skype'
		));
		if ( is_array($share_facebook) && in_array( 'share_facebook', $share_facebook ) ) {
//			return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
			return $output . ' xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml"';
		} else {
			return $output;
		}
	}
	/**
	 * Returns the instance.
	 *
	 * @return object
	 * @since  8.3.6
	 */
	public static function get_instance( $shortcodes = array() ) {
		
		if ( null == self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		
		return self::$instance;
	}
}
$optimization = new XStore_Optimization;
$optimization->init();