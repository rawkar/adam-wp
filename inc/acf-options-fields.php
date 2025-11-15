<?php
/**
 * ACF Fields for Options Page
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Fields for Options Page
 */
function adam_klingeteg_register_options_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_options_fields',
        'title' => __('Theme Settings', 'adam-klingeteg'),
        'fields' => array(
            array(
                'key' => 'field_options_navigation',
                'label' => __('Navigation', 'adam-klingeteg'),
                'name' => 'navigation_section',
                'type' => 'group',
                'instructions' => __('Configure navigation menu items', 'adam-klingeteg'),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_navigation_items',
                        'label' => __('Menu Items', 'adam-klingeteg'),
                        'name' => 'navigation_items',
                        'type' => 'repeater',
                        'instructions' => __('Add navigation menu items', 'adam-klingeteg'),
                        'required' => 0,
                        'layout' => 'table',
                        'button_label' => __('Add Menu Item', 'adam-klingeteg'),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_nav_item_label',
                                'label' => __('Label', 'adam-klingeteg'),
                                'name' => 'label',
                                'type' => 'text',
                                'required' => 1,
                            ),
                            array(
                                'key' => 'field_nav_item_url',
                                'label' => __('URL', 'adam-klingeteg'),
                                'name' => 'url',
                                'type' => 'url',
                                'required' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_options_contact',
                'label' => __('Contact Information', 'adam-klingeteg'),
                'name' => 'contact_section',
                'type' => 'group',
                'instructions' => __('Configure contact page information', 'adam-klingeteg'),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_contact_name',
                        'label' => __('Name', 'adam-klingeteg'),
                        'name' => 'contact_name',
                        'type' => 'text',
                        'default_value' => 'Adam Klingeteg',
                    ),
                    array(
                        'key' => 'field_contact_title',
                        'label' => __('Title', 'adam-klingeteg'),
                        'name' => 'contact_title',
                        'type' => 'text',
                        'default_value' => 'Photographer',
                    ),
                    array(
                        'key' => 'field_contact_email',
                        'label' => __('Email', 'adam-klingeteg'),
                        'name' => 'contact_email',
                        'type' => 'email',
                        'default_value' => 'HELLO@ADAMKLINGETEG.COM',
                    ),
                    array(
                        'key' => 'field_contact_phone',
                        'label' => __('Phone', 'adam-klingeteg'),
                        'name' => 'contact_phone',
                        'type' => 'text',
                        'default_value' => '+46 (0) 735 28 74 41',
                    ),
                    array(
                        'key' => 'field_contact_instagram',
                        'label' => __('Instagram URL', 'adam-klingeteg'),
                        'name' => 'contact_instagram',
                        'type' => 'url',
                        'default_value' => 'https://instagram.com/adamklingeteg',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));
}
add_action('acf/init', 'adam_klingeteg_register_options_fields');

