<?php
/**
 * Helper Functions
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get project cover image
 *
 * @param int $post_id Post ID
 * @return string Image URL
 */
function adam_klingeteg_get_project_cover_image($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Try ACF field first
    $cover_image = get_field('cover_image', $post_id);
    
    if ($cover_image) {
        if (is_array($cover_image)) {
            return esc_url($cover_image['url']);
        } elseif (is_numeric($cover_image)) {
            return esc_url(wp_get_attachment_image_url($cover_image, 'full'));
        }
    }

    // Fallback to featured image
    $featured_image = get_the_post_thumbnail_url($post_id, 'full');
    if ($featured_image) {
        return esc_url($featured_image);
    }

    // Try to get image from assets/projects folder based on project title
    $project_title = get_the_title($post_id);
    if (!empty($project_title)) {
        $project_image = adam_klingeteg_get_project_image_from_assets($project_title, 'cover');
        if ($project_image) {
            return esc_url($project_image);
        }
    }

    // Default fallback
    return esc_url(get_template_directory_uri() . '/assets/images/default-project.jpg');
}

/**
 * Get project image from assets/projects folder
 *
 * @param string $project_name Project name
 * @param string $type 'cover' for first image, 'gallery' for all images
 * @return string|array Image URL(s)
 */
function adam_klingeteg_get_project_image_from_assets($project_name, $type = 'cover') {
    $template_dir = get_template_directory();
    $template_uri = get_template_directory_uri();
    $projects_dir = $template_dir . '/assets/projects';
    
    // Clean project name to match folder name
    $folder_name = $project_name;
    
    // Check if folder exists
    $project_folder = $projects_dir . '/' . $folder_name;
    if (!is_dir($project_folder)) {
        return false;
    }
    
    // Get all images from folder
    $images = glob($project_folder . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
    
    if (empty($images)) {
        return false;
    }
    
    // Sort images by filename
    sort($images);
    
    if ($type === 'cover') {
        // Return first image URL
        $image_path = $images[0];
        $image_url = str_replace($template_dir, $template_uri, $image_path);
        return $image_url;
    } else {
        // Return all image URLs
        $image_urls = array();
        foreach ($images as $image_path) {
            $image_url = str_replace($template_dir, $template_uri, $image_path);
            $image_urls[] = $image_url;
        }
        return $image_urls;
    }
}

/**
 * Get project gallery images
 *
 * @param int $post_id Post ID
 * @return array Array of image URLs
 */
function adam_klingeteg_get_project_gallery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Try to get from custom meta first (works without ACF PRO)
    $gallery_meta = get_post_meta($post_id, '_project_gallery', true);
    $images = array();
    
    if (!empty($gallery_meta)) {
        $gallery_ids = explode(',', $gallery_meta);
        $gallery_ids = array_filter(array_map('intval', $gallery_ids));
        
        foreach ($gallery_ids as $img_id) {
            $img_url = wp_get_attachment_image_url($img_id, 'full');
            if ($img_url) {
                $images[] = esc_url($img_url);
            }
        }
    }
    
    // Fallback to ACF field if custom meta is empty
    if (empty($images) && function_exists('get_field')) {
        $gallery = get_field('gallery', $post_id);
        
        if ($gallery && is_array($gallery)) {
            foreach ($gallery as $image) {
                if (is_array($image)) {
                    // ACF returns array format
                    if (isset($image['url'])) {
                        $images[] = esc_url($image['url']);
                    } elseif (isset($image['ID'])) {
                        $img_url = wp_get_attachment_image_url($image['ID'], 'full');
                        if ($img_url) {
                            $images[] = esc_url($img_url);
                        }
                    } elseif (isset($image['image']) && is_numeric($image['image'])) {
                        // Repeater format
                        $img_url = wp_get_attachment_image_url($image['image'], 'full');
                        if ($img_url) {
                            $images[] = esc_url($img_url);
                        }
                    }
                } elseif (is_numeric($image)) {
                    // ACF returns ID format
                    $img_url = wp_get_attachment_image_url($image, 'full');
                    if ($img_url) {
                        $images[] = esc_url($img_url);
                    }
                }
            }
        }
    }

    // If no gallery images from ACF, try to get from assets/projects folder
    if (empty($images)) {
        $project_title = get_the_title($post_id);
        if (!empty($project_title)) {
            $assets_images = adam_klingeteg_get_project_image_from_assets($project_title, 'gallery');
            if ($assets_images && is_array($assets_images)) {
                foreach ($assets_images as $img_url) {
                    $images[] = esc_url($img_url);
                }
            }
        }
    }

    return $images;
}

/**
 * Get project year
 *
 * @param int $post_id Post ID
 * @return string Year
 */
function adam_klingeteg_get_project_year($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $year = get_field('year', $post_id);
    
    if (empty($year)) {
        // Fallback to post date year
        $year = get_the_date('Y', $post_id);
    }

    return esc_html($year);
}

/**
 * Get project tags
 *
 * @param int $post_id Post ID
 * @return array Array of tag names
 */
function adam_klingeteg_get_project_tags($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms($post_id, 'project_tag');
    
    if (!$terms || is_wp_error($terms)) {
        return array();
    }

    $tags = array();
    foreach ($terms as $term) {
        $tags[] = esc_html($term->name);
    }

    return $tags;
}

/**
 * Get all projects
 *
 * @param array $args Query arguments
 * @return WP_Query
 */
function adam_klingeteg_get_projects($args = array()) {
    $defaults = array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'       => 'menu_order',
        'order'          => 'ASC',
    );

    $args = wp_parse_args($args, $defaults);

    return new WP_Query($args);
}

/**
 * Get navigation menu items from ACF Options or fallback
 *
 * @return array Menu items
 */
function adam_klingeteg_get_navigation_items() {
    $items = array();

    // Try ACF Options first
    if (function_exists('get_field')) {
        $menu_items = get_field('navigation_items', 'option');
        
        if ($menu_items && is_array($menu_items)) {
            foreach ($menu_items as $item) {
                $items[] = array(
                    'label' => isset($item['label']) ? esc_html($item['label']) : '',
                    'url'   => isset($item['url']) ? esc_url($item['url']) : '',
                );
            }
        }
    }

    // Fallback to default menu items
    if (empty($items)) {
        $items = array(
            array(
                'label' => 'Work',
                'url'   => get_post_type_archive_link('project'),
            ),
            array(
                'label' => 'Contact',
                'url'   => get_permalink(get_page_by_path('contact')),
            ),
        );
    }

    return $items;
}

/**
 * Get contact information from ACF Options
 *
 * @return array Contact info
 */
function adam_klingeteg_get_contact_info() {
    $contact = array(
        'name'     => 'Adam Klingeteg',
        'title'    => 'Photographer',
        'email'    => 'HELLO@ADAMKLINGETEG.COM',
        'phone'    => '+46 (0) 735 28 74 41',
        'instagram' => 'https://instagram.com/adamklingeteg',
    );

    // Override with ACF Options if available
    if (function_exists('get_field')) {
        $name = get_field('contact_name', 'option');
        if (!empty($name)) {
            $contact['name'] = esc_html($name);
        }

        $title = get_field('contact_title', 'option');
        if (!empty($title)) {
            $contact['title'] = esc_html($title);
        }

        $email = get_field('contact_email', 'option');
        if (!empty($email)) {
            $contact['email'] = esc_html($email);
        }

        $phone = get_field('contact_phone', 'option');
        if (!empty($phone)) {
            $contact['phone'] = esc_html($phone);
        }

        $instagram = get_field('contact_instagram', 'option');
        if (!empty($instagram)) {
            $contact['instagram'] = esc_url($instagram);
        }
    }

    return $contact;
}

