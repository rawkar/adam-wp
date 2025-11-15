<?php
/**
 * ACF Fields for Projects
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Fields for Projects
 */
function adam_klingeteg_register_project_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_project_fields',
        'title' => __('Project Details', 'adam-klingeteg'),
        'fields' => array(
            array(
                'key' => 'field_project_year',
                'label' => __('Year', 'adam-klingeteg'),
                'name' => 'year',
                'type' => 'text',
                'instructions' => __('Enter the year this project was completed', 'adam-klingeteg'),
                'required' => 0,
                'default_value' => date('Y'),
                'placeholder' => '2024',
            ),
            array(
                'key' => 'field_project_description',
                'label' => __('Brödtext', 'adam-klingeteg'),
                'name' => 'description',
                'type' => 'wysiwyg',
                'instructions' => __('Ange brödtext för detta kundcase', 'adam-klingeteg'),
                'required' => 0,
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_project_cover_image',
                'label' => __('Omslagsbild', 'adam-klingeteg'),
                'name' => 'cover_image',
                'type' => 'image',
                'instructions' => __('Välj omslagsbild för detta projekt (används i projekt-grid)', 'adam-klingeteg'),
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_project_gallery',
                'label' => __('Bildgalleri', 'adam-klingeteg'),
                'name' => 'gallery',
                'type' => 'text',
                'instructions' => __('Använd knappen nedan för att ladda upp bilder. Detta fält fylls i automatiskt.', 'adam-klingeteg'),
                'required' => 0,
                'default_value' => '',
                'placeholder' => '',
                'readonly' => 1,
            ),
            array(
                'key' => 'field_project_featured',
                'label' => __('Featured Project', 'adam-klingeteg'),
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => __('Check to feature this project on the homepage', 'adam-klingeteg'),
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
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
add_action('acf/init', 'adam_klingeteg_register_project_fields');

