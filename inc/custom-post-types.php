<?php
/**
 * Custom Post Types
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Project Custom Post Type
 */
function adam_klingeteg_register_project_post_type() {
    $labels = array(
        'name'                  => _x('Kundcase', 'Post Type General Name', 'adam-klingeteg'),
        'singular_name'         => _x('Kundcase', 'Post Type Singular Name', 'adam-klingeteg'),
        'menu_name'             => __('Kundcase', 'adam-klingeteg'),
        'name_admin_bar'        => __('Kundcase', 'adam-klingeteg'),
        'archives'              => __('Kundcase Arkiv', 'adam-klingeteg'),
        'attributes'            => __('Kundcase Attribut', 'adam-klingeteg'),
        'parent_item_colon'     => __('Föräldra Kundcase:', 'adam-klingeteg'),
        'all_items'             => __('Alla Kundcase', 'adam-klingeteg'),
        'add_new_item'          => __('Lägg till nytt Kundcase', 'adam-klingeteg'),
        'add_new'               => __('Lägg till nytt', 'adam-klingeteg'),
        'new_item'              => __('Nytt Kundcase', 'adam-klingeteg'),
        'edit_item'             => __('Redigera Kundcase', 'adam-klingeteg'),
        'update_item'           => __('Uppdatera Kundcase', 'adam-klingeteg'),
        'view_item'             => __('Visa Kundcase', 'adam-klingeteg'),
        'view_items'            => __('Visa Kundcase', 'adam-klingeteg'),
        'search_items'          => __('Sök Kundcase', 'adam-klingeteg'),
        'not_found'             => __('Inget hittades', 'adam-klingeteg'),
        'not_found_in_trash'    => __('Inget hittades i papperskorgen', 'adam-klingeteg'),
        'featured_image'        => __('Omslagsbild', 'adam-klingeteg'),
        'set_featured_image'    => __('Sätt omslagsbild', 'adam-klingeteg'),
        'remove_featured_image' => __('Ta bort omslagsbild', 'adam-klingeteg'),
        'use_featured_image'    => __('Använd som omslagsbild', 'adam-klingeteg'),
        'insert_into_item'      => __('Infoga i kundcase', 'adam-klingeteg'),
        'uploaded_to_this_item' => __('Uppladdat till detta kundcase', 'adam-klingeteg'),
        'items_list'            => __('Kundcase lista', 'adam-klingeteg'),
        'items_list_navigation' => __('Kundcase lista navigation', 'adam-klingeteg'),
        'filter_items_list'     => __('Filtrera kundcase lista', 'adam-klingeteg'),
    );

    $args = array(
        'label'                 => __('Kundcase', 'adam-klingeteg'),
        'description'           => __('Kundcase och projekt', 'adam-klingeteg'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes'),
        'taxonomies'            => array('project_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'projects'),
    );

    register_post_type('project', $args);
}
add_action('init', 'adam_klingeteg_register_project_post_type', 0);

/**
 * Register Project Tag Taxonomy
 */
function adam_klingeteg_register_project_tag_taxonomy() {
    $labels = array(
        'name'                       => _x('Project Tags', 'Taxonomy General Name', 'adam-klingeteg'),
        'singular_name'              => _x('Project Tag', 'Taxonomy Singular Name', 'adam-klingeteg'),
        'menu_name'                  => __('Tags', 'adam-klingeteg'),
        'all_items'                  => __('All Tags', 'adam-klingeteg'),
        'parent_item'                => __('Parent Tag', 'adam-klingeteg'),
        'parent_item_colon'          => __('Parent Tag:', 'adam-klingeteg'),
        'new_item_name'              => __('New Tag Name', 'adam-klingeteg'),
        'add_new_item'               => __('Add New Tag', 'adam-klingeteg'),
        'edit_item'                  => __('Edit Tag', 'adam-klingeteg'),
        'update_item'                => __('Update Tag', 'adam-klingeteg'),
        'view_item'                  => __('View Tag', 'adam-klingeteg'),
        'separate_items_with_commas' => __('Separate tags with commas', 'adam-klingeteg'),
        'add_or_remove_items'        => __('Add or remove tags', 'adam-klingeteg'),
        'choose_from_most_used'      => __('Choose from the most used', 'adam-klingeteg'),
        'popular_items'              => __('Popular Tags', 'adam-klingeteg'),
        'search_items'               => __('Search Tags', 'adam-klingeteg'),
        'not_found'                  => __('Not Found', 'adam-klingeteg'),
        'no_terms'                   => __('No tags', 'adam-klingeteg'),
        'items_list'                 => __('Tags list', 'adam-klingeteg'),
        'items_list_navigation'      => __('Tags list navigation', 'adam-klingeteg'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'project-tag'),
    );

    register_taxonomy('project_tag', array('project'), $args);
}
add_action('init', 'adam_klingeteg_register_project_tag_taxonomy', 0);

