<?php
/**
 * ACF Fields Setup
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add ACF Options Page
 */
function adam_klingeteg_add_acf_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('Theme Settings', 'adam-klingeteg'),
            'menu_title' => __('Theme Settings', 'adam-klingeteg'),
            'menu_slug'  => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
        ));
    }
}
add_action('acf/init', 'adam_klingeteg_add_acf_options_page');

