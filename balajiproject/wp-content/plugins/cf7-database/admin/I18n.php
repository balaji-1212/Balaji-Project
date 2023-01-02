<?php

if ( ! defined('ABSPATH') ) {
	exit('Direct\'s not allowed');
}

add_action('plugins_loaded', 'loadPluginTextdomain');
    
function loadPluginTextdomain()
{
    if (function_exists('determine_locale')) {
        $locale = determine_locale();
    } else {
        $locale = is_admin() ? get_user_locale() : get_locale();
    }
    unload_textdomain('cf7-database');
    load_textdomain('cf7-database', CF7D_PLUGIN_PATH . '/i18n/languages/' . $locale . '.mo');
    load_plugin_textdomain('cf7-database', false, CF7D_PLUGIN_PATH . '/i18n/languages/');
}

function getTranslation()
{
    $translation = array(
    'save_change' => __('Save Changes', 'cf7-database'),
    'cf7d' => __('Contact Form 7 Database', 'cf7-database'),
    'go_pro' => __('Go Pro', 'cf7-database'),
    'button_hor' => __('Grid View: This feature is available in Pro version.', 'cf7-database'),
    'filter_notice' => __('Filter: This feature is available in Pro version.', 'cf7-database'),
    'choose_form' => __('Choose Form', 'cf7-database'),
    'loading' => __('Loading...', 'cf7-database'),
    'export_csv' => __('Export to CSV: This feature is available in Pro version.', 'cf7-database'),
    'filter' => __('Filter', 'cf7-database'),
    'type_something' => __('Type something...', 'cf7-database'),
    'show' => __('Show', 'cf7-database'),
    'item' => __("items of", 'cf7-database'),
    'column_setting' => __("Column Settings", 'cf7-database'),
    'table_setting' => __("Table Settings", 'cf7-database'),
    'sort_column' => __("Sort Column", 'cf7-database'),
    'bordered' => __("Bordered", 'cf7-database'),
    'title' => __("Title", 'cf7-database'),
    'column_header' => __("Column Header", 'cf7-database'),
    'expandable' => __("Expandable", 'cf7-database'),
    'fixed_header' => __("Fixed Header", 'cf7-database'),
    'ellipsis' => __("Ellipsis", 'cf7-database'),
    'footer' => __('Footer', 'cf7-database'),
    'checkbox' => __('Checkbox', 'cf7-database'),
    'size' => __('Size', 'cf7-database'),
    'default' => __('Default', 'cf7-database'),
    'middle' => __('Middle', 'cf7-database'),
    'small' => __('Small', 'cf7-database'),
    'table_scroll' => __('Table Scroll', 'cf7-database'),
    'scroll' => __('Scroll', 'cf7-database'),
    'fixed_column' => __('Fixed Columns', 'cf7-database'),
    'pagination_top' => __('Pagination Top', 'cf7-database'),
    'top_left' => __('Top Left', 'cf7-database'),
    'top_center' => __('Top Center', 'cf7-database'),
    'top_right' => __('Top Right', 'cf7-database'),
    'pagination_bottom' => __('Pagination Bottom', 'cf7-database'),
    'bottom_left' => __('Bottom Left', 'cf7-database'),
    'bottom_center' => __('Bottom Center', 'cf7-database'),
    'bottom_right' => __('Bottom Right', 'cf7-database'),
    'none' => __('None', 'cf7-database'),
    'action' => __('ACTION', 'cf7-database'),
    'edit_information' => __('Edit Information', 'cf7-database'),
    'content_information' => __('Content Information', 'cf7-database'),
    'sure_delete' => __('Sure to delete?', 'cf7-database'),
    'cancel' => __('Cancel', 'cf7-database'),
    'ok' => __('OK', 'cf7-database'),
    'delete_selected' => __('Delete Selected', 'cf7-database'),
    'fields'     => __('Fields', 'cf7-database')
    );
    return $translation;
}