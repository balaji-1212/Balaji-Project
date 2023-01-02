<?php
/**
 * Description
 *
 * @package    ask-estimate.php
 * @since      4.3.8
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

wp_enqueue_script('call_popup');

$ask_estimate_element_options = array();
$ask_estimate_element_options['popup_content_default'] =
    esc_html__('You may add any content here from Customizer -> WooCommerce -> Built-in Wishlist -> Popup content.', 'xstore-core');
$ask_estimate_element_options['popup_dimensions'] = get_theme_mod('xstore_wishlist_ask_estimate_popup_content_width_height_et-desktop', 'auto');
$ask_estimate_element_options['popup_content'] = get_theme_mod('xstore_wishlist_ask_estimate_popup_content', $ask_estimate_element_options['popup_content_default']);
$ask_estimate_element_options['button_text'] = get_theme_mod('xstore_wishlist_ask_estimate_button_text', esc_html__('Ask for an estimate', 'xstore-core'));
if (empty(trim($ask_estimate_element_options['button_text'])) && !get_query_var('et_is_customize_preview', false)) return;

$ask_estimate_element_options['button_attributes'] = array(
    'class="flex-inline align-items-center pos-relative et-call-popup btn bordered"',
    'data-type="ask-wishlist-estimate"'
);

$ask_estimate_element_options['global_attributes'] = array();

if (get_query_var('et_is_customize_preview', false)) {
    $ask_estimate_element_options['global_attributes'][] = 'data-title="' . esc_html__('Ask estimate popup', 'xstore-core') . '"';
    $ask_estimate_element_options['global_attributes'][] = 'data-element="xstore-wishlist"';
}

$ask_estimate_element_options['button_attributes'] = array_merge($ask_estimate_element_options['button_attributes'], $ask_estimate_element_options['global_attributes']);

add_action('wp_footer', function ($args) use ($ask_estimate_element_options) { ?>
    <div class="ask-wishlist-estimate-popup et-called-popup"
         data-type="ask-wishlist-estimate" <?php echo implode(' ', $ask_estimate_element_options['global_attributes']); ?>>
        <div class="et-popup">
            <div class="et-popup-content<?php echo 'custom' == $ask_estimate_element_options['popup_dimensions'] ? ' et-popup-content-custom-dimenstions' : ''; ?> with-static-block">
                    <span class="et-close-popup et-toggle pos-fixed full-left top"
                          style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;">
                      <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                        <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                      </svg>
                    </span>
                <?php
                echo html_blocks_callback(array(
                    'section' => 'xstore_wishlist_ask_estimate_popup_content_section',
                    'sections' => 'xstore_wishlist_ask_estimate_popup_content_sections',
                    'html_backup' => 'xstore_wishlist_ask_estimate_popup_content',
                    'html_backup_default' => $ask_estimate_element_options['popup_content_default'],
                    'section_content' => true
                ));
                ?>
            </div>
        </div>
    </div>
<?php }); ?>

    <a <?php echo implode(' ', $ask_estimate_element_options['button_attributes']); ?>>
        <span class="et-icon et_b-icon et-message"></span>
        <span><?php echo $ask_estimate_element_options['button_text']; ?></span>
    </a>

<?php unset($ask_estimate_element_options);