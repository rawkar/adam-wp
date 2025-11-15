<?php
/**
 * Import Projects from assets/projects folder
 *
 * This script creates WordPress projects from the folder structure in assets/projects
 * Run this once via WordPress admin or WP-CLI
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import projects from assets/projects folder
 */
function adam_klingeteg_import_projects_from_assets() {
    $template_dir = get_template_directory();
    $projects_dir = $template_dir . '/assets/projects';
    
    if (!is_dir($projects_dir)) {
        return array(
            'success' => false,
            'message' => 'Projects directory not found: ' . $projects_dir
        );
    }
    
    // Get all project folders
    $folders = glob($projects_dir . '/*', GLOB_ONLYDIR);
    $imported = 0;
    $updated = 0;
    $errors = array();
    
    foreach ($folders as $folder) {
        $project_name = basename($folder);
        
        // Get images from folder
        $images = glob($folder . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        
        if (empty($images)) {
            $errors[] = "No images found in folder: " . $project_name;
            continue;
        }
        
        // Sort images
        sort($images);
        $cover_image = $images[0];
        $gallery_images = $images;
        
        // Check if project already exists
        $existing_project = get_page_by_path(sanitize_title($project_name), OBJECT, 'project');
        
        if ($existing_project) {
            // Update existing project
            $post_id = $existing_project->ID;
            $updated++;
        } else {
            // Create new project
            $post_data = array(
                'post_title'    => $project_name,
                'post_status'   => 'publish',
                'post_type'     => 'project',
                'post_name'     => sanitize_title($project_name),
            );
            
            $post_id = wp_insert_post($post_data);
            
            if (is_wp_error($post_id)) {
                $errors[] = "Failed to create project: " . $project_name . " - " . $post_id->get_error_message();
                continue;
            }
            
            $imported++;
        }
        
        // Set cover image URL in ACF (we'll use the file path)
        $template_uri = get_template_directory_uri();
        $cover_image_url = str_replace($template_dir, $template_uri, $cover_image);
        
        // Try to upload image to media library and set as cover
        $cover_attachment_id = adam_klingeteg_upload_image_to_media_library($cover_image, $project_name . ' - Cover');
        
        if ($cover_attachment_id) {
            // Set as featured image
            set_post_thumbnail($post_id, $cover_attachment_id);
            
            // Set in ACF
            if (function_exists('update_field')) {
                update_field('cover_image', $cover_attachment_id, $post_id);
            }
        } else {
            // Fallback: store URL in post meta
            update_post_meta($post_id, '_cover_image_url', $cover_image_url);
        }
        
        // Upload gallery images
        $gallery_ids = array();
        foreach ($gallery_images as $index => $image_path) {
            $attachment_id = adam_klingeteg_upload_image_to_media_library($image_path, $project_name . ' - Image ' . ($index + 1));
            if ($attachment_id) {
                $gallery_ids[] = $attachment_id;
            }
        }
        
        // Set gallery in ACF
        if (!empty($gallery_ids) && function_exists('update_field')) {
            update_field('gallery', $gallery_ids, $post_id);
        }
        
        // Set menu order based on folder order
        $menu_order = $imported + $updated;
        wp_update_post(array(
            'ID' => $post_id,
            'menu_order' => $menu_order
        ));
    }
    
    return array(
        'success' => true,
        'imported' => $imported,
        'updated' => $updated,
        'errors' => $errors
    );
}

/**
 * Upload image file to WordPress media library
 *
 * @param string $file_path Full path to image file
 * @param string $title Image title
 * @return int|false Attachment ID or false on failure
 */
function adam_klingeteg_upload_image_to_media_library($file_path, $title = '') {
    if (!file_exists($file_path)) {
        return false;
    }
    
    // Check if file already exists in media library by filename
    $filename = basename($file_path);
    $existing = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'any',
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => $filename,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($existing)) {
        return $existing[0]->ID;
    }
    
    // Copy file to temp location for WordPress upload
    $upload_dir = wp_upload_dir();
    $temp_file = $upload_dir['path'] . '/' . $filename;
    
    // Copy file
    if (!copy($file_path, $temp_file)) {
        return false;
    }
    
    // Prepare file array
    $file_array = array(
        'name' => $filename,
        'tmp_name' => $temp_file
    );
    
    // Include WordPress file handling functions
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    // Upload file
    $attachment_id = media_handle_sideload($file_array, 0, $title);
    
    // Clean up temp file if upload failed
    if (is_wp_error($attachment_id) && file_exists($temp_file)) {
        @unlink($temp_file);
        return false;
    }
    
    return $attachment_id;
}

/**
 * Admin page for importing projects
 */
function adam_klingeteg_add_import_page() {
    add_submenu_page(
        'edit.php?post_type=project',
        __('Import Projects', 'adam-klingeteg'),
        __('Import from Assets', 'adam-klingeteg'),
        'manage_options',
        'import-projects',
        'adam_klingeteg_import_page_callback'
    );
}
add_action('admin_menu', 'adam_klingeteg_add_import_page');

/**
 * Import page callback
 */
function adam_klingeteg_import_page_callback() {
    if (isset($_POST['import_projects']) && check_admin_referer('import_projects_action', 'import_projects_nonce')) {
        $result = adam_klingeteg_import_projects_from_assets();
        ?>
        <div class="wrap">
            <h1><?php _e('Import Projects', 'adam-klingeteg'); ?></h1>
            <?php if ($result['success']) : ?>
                <div class="notice notice-success">
                    <p>
                        <?php 
                        printf(
                            __('Successfully imported %d projects and updated %d projects.', 'adam-klingeteg'),
                            $result['imported'],
                            $result['updated']
                        );
                        ?>
                    </p>
                </div>
                <?php if (!empty($result['errors'])) : ?>
                    <div class="notice notice-warning">
                        <p><strong><?php _e('Errors:', 'adam-klingeteg'); ?></strong></p>
                        <ul>
                            <?php foreach ($result['errors'] as $error) : ?>
                                <li><?php echo esc_html($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="notice notice-error">
                    <p><?php echo esc_html($result['message']); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    } else {
        ?>
        <div class="wrap">
            <h1><?php _e('Import Projects from Assets', 'adam-klingeteg'); ?></h1>
            <p><?php _e('This will import all projects from the assets/projects folder and create WordPress projects for each folder.', 'adam-klingeteg'); ?></p>
            <form method="post" action="">
                <?php wp_nonce_field('import_projects_action', 'import_projects_nonce'); ?>
                <p>
                    <input type="submit" name="import_projects" class="button button-primary" value="<?php _e('Import Projects', 'adam-klingeteg'); ?>" />
                </p>
            </form>
        </div>
        <?php
    }
}

