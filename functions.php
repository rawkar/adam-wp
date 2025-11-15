<?php
/**
 * Adam Klingeteg Portfolio Theme Functions
 *
 * @package Adam_Klingeteg
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
define('ADAM_KLINGETEG_VERSION', time()); // Use timestamp to force cache refresh
define('ADAM_KLINGETEG_TEMPLATE_DIR', get_template_directory());
define('ADAM_KLINGETEG_TEMPLATE_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function adam_klingeteg_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'adam-klingeteg'),
    ));

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'adam_klingeteg_setup');

/**
 * Enqueue scripts and styles
 */
function adam_klingeteg_scripts() {
    // Styles
    wp_enqueue_style(
        'adam-klingeteg-style',
        get_stylesheet_uri(),
        array(),
        ADAM_KLINGETEG_VERSION
    );

    wp_enqueue_style(
        'adam-klingeteg-main',
        ADAM_KLINGETEG_TEMPLATE_URI . '/assets/css/main.css',
        array(),
        ADAM_KLINGETEG_VERSION
    );

    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Tenor+Sans:wght@400&display=swap',
        array(),
        null
    );

    // Scripts
    wp_enqueue_script(
        'adam-klingeteg-main',
        ADAM_KLINGETEG_TEMPLATE_URI . '/assets/js/main.js',
        array(),
        ADAM_KLINGETEG_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'adam_klingeteg_scripts');

/**
 * Include theme files
 */
require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/custom-post-types.php';
require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/helpers.php';
require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/acf-fields.php';

// Include ACF fields if ACF is active
if (function_exists('acf_add_local_field_group')) {
    require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/acf-project-fields.php';
    require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/acf-options-fields.php';
}

// Include import script
require_once ADAM_KLINGETEG_TEMPLATE_DIR . '/inc/import-projects.php';

/**
 * Add custom columns to project admin list
 */
function adam_klingeteg_project_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __('Rubrik', 'adam-klingeteg');
    $new_columns['featured_image'] = __('Omslagsbild', 'adam-klingeteg');
    $new_columns['menu_order'] = __('Ordning', 'adam-klingeteg');
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_project_posts_columns', 'adam_klingeteg_project_admin_columns');

/**
 * Display custom column content
 */
function adam_klingeteg_project_admin_column_content($column, $post_id) {
    if ($column === 'featured_image') {
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
        if ($thumbnail) {
            echo $thumbnail;
        } else {
            echo '—';
        }
    }
    if ($column === 'menu_order') {
        $order = get_post_field('menu_order', $post_id);
        echo '<span class="menu-order-value">' . esc_html($order) . '</span>';
    }
}
add_action('manage_project_posts_custom_column', 'adam_klingeteg_project_admin_column_content', 10, 2);

/**
 * Make menu_order column sortable
 */
function adam_klingeteg_project_sortable_columns($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter('manage_edit-project_sortable_columns', 'adam_klingeteg_project_sortable_columns');

/**
 * Enable drag & drop ordering for projects
 */
function adam_klingeteg_enable_project_ordering() {
    add_submenu_page(
        'edit.php?post_type=project',
        __('Ordna Kundcase', 'adam-klingeteg'),
        __('Ordna Kundcase', 'adam-klingeteg'),
        'edit_posts',
        'order-projects',
        'adam_klingeteg_project_order_page'
    );
}
add_action('admin_menu', 'adam_klingeteg_enable_project_ordering');

/**
 * Add gallery management page for each project
 */
function adam_klingeteg_add_gallery_management() {
    add_submenu_page(
        'edit.php?post_type=project',
        __('Hantera Bildgalleri', 'adam-klingeteg'),
        __('Bildgalleri', 'adam-klingeteg'),
        'edit_posts',
        'manage-project-gallery',
        'adam_klingeteg_gallery_management_page'
    );
}
add_action('admin_menu', 'adam_klingeteg_add_gallery_management');

/**
 * Register AJAX handler for getting attachment URLs
 */
function adam_klingeteg_get_attachment_url_ajax() {
    check_ajax_referer('get_attachment_url_nonce', 'nonce');
    $attachment_id = intval($_POST['attachment_id']);
    $url = wp_get_attachment_image_url($attachment_id, 'medium');
    
    if ($url) {
        wp_send_json_success(array('url' => $url));
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_get_attachment_url', 'adam_klingeteg_get_attachment_url_ajax');

/**
 * Add quick add form to project list page
 */
function adam_klingeteg_add_quick_add_form() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'edit-project') {
        // Handle form submission
        if (isset($_POST['quick_add_project']) && check_admin_referer('quick_add_project_nonce', 'quick_add_project_nonce')) {
            $title = sanitize_text_field($_POST['project_title']);
            $description = wp_kses_post($_POST['project_description']);
            
            // Handle gallery - can be comma-separated string or array
            $gallery = array();
            if (isset($_POST['project_gallery'])) {
                if (is_array($_POST['project_gallery'])) {
                    $gallery = array_map('intval', $_POST['project_gallery']);
                } else {
                    $gallery_ids = explode(',', sanitize_text_field($_POST['project_gallery']));
                    $gallery = array_filter(array_map('intval', $gallery_ids));
                }
            }
            
            if (!empty($title)) {
                $post_data = array(
                    'post_title'    => $title,
                    'post_content'  => $description,
                    'post_status'   => 'publish',
                    'post_type'     => 'project',
                );
                
                $post_id = wp_insert_post($post_data);
                
                if ($post_id && !is_wp_error($post_id)) {
                    // Save gallery to ACF field
                    if (!empty($gallery)) {
                        update_field('gallery', $gallery, $post_id);
                    }
                    
                    // Redirect to list page with success message
                    wp_redirect(admin_url('edit.php?post_type=project&added=1'));
                    exit;
                }
            }
        }
        
        // Show success message
        if (isset($_GET['added']) && $_GET['added'] == '1') {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Kundcase skapat!</p></div>';
            });
        }
        
        // Add form HTML
        add_action('admin_footer', 'adam_klingeteg_quick_add_form_html');
    }
}
add_action('current_screen', 'adam_klingeteg_add_quick_add_form');

/**
 * Quick add form HTML
 */
function adam_klingeteg_quick_add_form_html() {
    ?>
    <div id="quick-add-project-form" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 100000; overflow-y: auto;">
        <div style="max-width: 800px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="margin: 0;">Lägg till nytt Kundcase</h2>
                <button type="button" id="close-quick-add-form" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">×</button>
            </div>
            
            <form method="post" action="" id="quick-add-project-form-inner">
                <?php wp_nonce_field('quick_add_project_nonce', 'quick_add_project_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="project_title">Rubrik</label>
                        </th>
                        <td>
                            <input type="text" id="project_title" name="project_title" class="regular-text" required style="width: 100%; padding: 8px;">
                            <p class="description">Ange rubriken för detta kundcase</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="project_description">Brödtext</label>
                        </th>
                        <td>
                            <?php
                            wp_editor('', 'project_description', array(
                                'textarea_name' => 'project_description',
                                'textarea_rows' => 10,
                                'media_buttons' => true,
                                'teeny' => false,
                            ));
                            ?>
                            <p class="description">Ange brödtext för detta kundcase</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label>Bildgalleri</label>
                        </th>
                        <td>
                            <div id="project-gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-bottom: 15px;"></div>
                            <button type="button" id="add-gallery-images" class="button button-secondary" style="margin-right: 10px;">
                                + Lägg till bilder
                            </button>
                            <button type="button" id="remove-all-gallery-images" class="button button-secondary" style="display: none;">
                                Ta bort alla bilder
                            </button>
                            <input type="hidden" id="project_gallery" name="project_gallery" value="">
                            <p class="description">Ladda upp bilder som ska visas på kundcase-sidan. Alla bilder kommer att visas direkt på sidan.</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="quick_add_project" class="button button-primary" value="Spara Kundcase">
                    <button type="button" id="cancel-quick-add-form" class="button" style="margin-left: 10px;">Avbryt</button>
                </p>
            </form>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        var galleryImages = [];
        
        // Open form when clicking "Lägg till nytt Kundcase"
        $('a[href*="post-new.php?post_type=project"]').on('click', function(e) {
            e.preventDefault();
            $('#quick-add-project-form').fadeIn(200);
        });
        
        // Close form
        $('#close-quick-add-form, #cancel-quick-add-form').on('click', function() {
            $('#quick-add-project-form').fadeOut(200);
        });
        
        // Add gallery images
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: 'Välj bilder för galleri',
                button: {
                    text: 'Lägg till bilder'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            mediaUploader.on('select', function() {
                var selection = mediaUploader.state().get('selection');
                selection.map(function(attachment) {
                    var attId = attachment.id;
                    if (galleryImages.indexOf(attId) === -1) {
                        galleryImages.push(attId);
                    }
                });
                updateGalleryPreview();
            });
            
            mediaUploader.open();
        });
        
        // Remove all gallery images
        $('#remove-all-gallery-images').on('click', function() {
            galleryImages = [];
            updateGalleryPreview();
        });
        
        // Update gallery preview
        function updateGalleryPreview() {
            var preview = $('#project-gallery-preview');
            var hiddenInput = $('#project_gallery');
            var removeBtn = $('#remove-all-gallery-images');
            
            preview.empty();
            hiddenInput.val('');
            
            if (galleryImages.length > 0) {
                removeBtn.show();
                galleryImages.forEach(function(id) {
                    // Get attachment from WordPress media library
                    var attachment = wp.media.attachment(id);
                    attachment.fetch().then(function() {
                        var imgUrl = attachment.get('sizes') && attachment.get('sizes').medium ? 
                                    attachment.get('sizes').medium.url : 
                                    attachment.get('url');
                        
                        var img = $('<img>').attr('src', imgUrl).css({
                            'width': '100%',
                            'height': '150px',
                            'object-fit': 'cover',
                            'border-radius': '4px',
                            'cursor': 'pointer'
                        });
                        
                        var removeBtn = $('<button>')
                            .text('×')
                            .attr('type', 'button')
                            .css({
                                'position': 'absolute',
                                'top': '5px',
                                'right': '5px',
                                'background': 'red',
                                'color': 'white',
                                'border': 'none',
                                'border-radius': '50%',
                                'width': '24px',
                                'height': '24px',
                                'cursor': 'pointer',
                                'font-size': '16px',
                                'line-height': '1'
                            })
                            .on('click', function() {
                                galleryImages = galleryImages.filter(function(imgId) {
                                    return imgId != id;
                                });
                                updateGalleryPreview();
                            });
                        
                        var container = $('<div>').css({
                            'position': 'relative',
                            'overflow': 'hidden'
                        }).append(img).append(removeBtn);
                        
                        preview.append(container);
                    });
                });
                
                hiddenInput.val(galleryImages.join(','));
            } else {
                removeBtn.hide();
            }
        }
        
        // Get attachment URL via AJAX
        if (typeof ajaxurl === 'undefined') {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        }
    });
    </script>
    
    <?php
}

/**
 * Enqueue jQuery UI Sortable for admin
 */
function adam_klingeteg_admin_scripts($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'order-projects') {
        wp_enqueue_script('jquery-ui-sortable');
    }
    
    // Enqueue media uploader for project gallery
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'project') {
            wp_enqueue_media();
        }
    }
}
add_action('admin_enqueue_scripts', 'adam_klingeteg_admin_scripts');

/**
 * Add custom meta box for project gallery - Force it to show with high priority
 */
function adam_klingeteg_add_gallery_meta_box() {
    global $wp_meta_boxes;
    
    // Add our custom meta box with highest priority
    add_meta_box(
        'project_gallery_meta_box',
        __('📷 BILDGALLERI - Ladda upp alla bilder här', 'adam-klingeteg'),
        'adam_klingeteg_gallery_meta_box_callback',
        'project',
        'normal',
        'core' // Use 'core' priority to ensure it shows
    );
}
// Use very early priority to ensure it's added first
add_action('add_meta_boxes_project', 'adam_klingeteg_add_gallery_meta_box', 1);
add_action('add_meta_boxes', 'adam_klingeteg_add_gallery_meta_box', 1);

/**
 * Gallery meta box callback
 */
function adam_klingeteg_gallery_meta_box_callback($post) {
    wp_nonce_field('project_gallery_meta_box', 'project_gallery_meta_box_nonce');
    
    // Get existing gallery from multiple sources
    $gallery_ids = array();
    
    // Try custom meta first
    $gallery_meta = get_post_meta($post->ID, '_project_gallery', true);
    if (!empty($gallery_meta)) {
        $gallery_ids = array_filter(array_map('intval', explode(',', $gallery_meta)));
    }
    
    // Try ACF field as fallback
    if (empty($gallery_ids) && function_exists('get_field')) {
        $acf_gallery = get_field('gallery', $post->ID);
        if ($acf_gallery) {
            if (is_array($acf_gallery)) {
                foreach ($acf_gallery as $item) {
                    if (is_numeric($item)) {
                        $gallery_ids[] = intval($item);
                    } elseif (is_array($item) && isset($item['ID'])) {
                        $gallery_ids[] = intval($item['ID']);
                    } elseif (is_array($item) && isset($item['image']) && is_numeric($item['image'])) {
                        $gallery_ids[] = intval($item['image']);
                    }
                }
            }
        }
    }
    
    $gallery_ids = array_unique($gallery_ids);
    
    ?>
    <div id="project-gallery-container" style="padding: 20px; background: #f9f9f9; border: 2px solid #0073aa; border-radius: 4px;">
        <h3 style="margin-top: 0; color: #0073aa;"><?php _e('Ladda upp bilder för kundcase', 'adam-klingeteg'); ?></h3>
        <p class="description" style="font-size: 14px; margin-bottom: 20px;">
            <?php _e('Klicka på knappen nedan för att ladda upp alla bilder som ska visas på kundcase-sidan. Du kan välja flera bilder samtidigt. Alla bilder kommer att visas direkt på sidan när användare besöker projektet.', 'adam-klingeteg'); ?>
        </p>
        
        <div style="margin-bottom: 20px;">
            <button type="button" id="add-gallery-images" class="button button-primary button-large" style="font-size: 16px; padding: 10px 20px; height: auto;">
                <span style="font-size: 20px; margin-right: 8px;">+</span>
                <?php _e('Lägg till bilder', 'adam-klingeteg'); ?>
            </button>
            <?php if (!empty($gallery_ids)) : ?>
                <button type="button" id="remove-all-gallery-images" class="button button-secondary" style="margin-left: 10px;">
                    <?php _e('Ta bort alla bilder', 'adam-klingeteg'); ?>
                </button>
            <?php endif; ?>
        </div>
        
        <div id="project-gallery-preview" style="display: <?php echo !empty($gallery_ids) ? 'grid' : 'none'; ?>; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 4px;">
            <?php if (!empty($gallery_ids)) : ?>
                <?php foreach ($gallery_ids as $img_id) : 
                    $img_url = wp_get_attachment_image_url($img_id, 'medium');
                    if ($img_url) :
                ?>
                    <div style="position: relative; border: 2px solid #0073aa; border-radius: 4px; overflow: hidden; background: white;">
                        <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                        <button type="button" class="remove-gallery-image" data-id="<?php echo esc_attr($img_id); ?>" style="position: absolute; top: 5px; right: 5px; background: #dc3232; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 18px; line-height: 1; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">×</button>
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; font-size: 11px; text-align: center;">
                            ID: <?php echo esc_html($img_id); ?>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
            endif; ?>
        </div>
        
        <?php if (empty($gallery_ids)) : ?>
            <div style="padding: 40px; text-align: center; background: white; border: 2px dashed #ddd; border-radius: 4px; color: #666;">
                <p style="font-size: 16px; margin: 0;"><?php _e('Inga bilder tillagda ännu. Klicka på "Lägg till bilder" ovan för att börja.', 'adam-klingeteg'); ?></p>
            </div>
        <?php endif; ?>
        
        <input type="hidden" id="project_gallery_ids" name="project_gallery_ids" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
        <p style="margin-top: 15px; font-size: 12px; color: #666;">
            <strong><?php _e('Antal bilder:', 'adam-klingeteg'); ?></strong> <span id="gallery-count"><?php echo count($gallery_ids); ?></span>
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        var galleryIds = <?php echo json_encode($gallery_ids); ?>;
        
        // Update gallery IDs in hidden field
        function updateGalleryField() {
            $('#project_gallery_ids').val(galleryIds.join(','));
            // Also update ACF field if it exists
            if ($('#acf-field_project_gallery').length) {
                $('#acf-field_project_gallery').val(galleryIds.join(',')).trigger('change');
            }
        }
        
        // Add gallery images
        $('#add-gallery-images').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: '<?php _e('Välj bilder för galleri', 'adam-klingeteg'); ?>',
                button: {
                    text: '<?php _e('Lägg till bilder', 'adam-klingeteg'); ?>'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            mediaUploader.on('select', function() {
                var selection = mediaUploader.state().get('selection');
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    if (galleryIds.indexOf(attachment.id) === -1) {
                        galleryIds.push(attachment.id);
                    }
                });
                updateGalleryPreview();
            });
            
            mediaUploader.open();
        });
        
        // Remove single image
        $(document).on('click', '.remove-gallery-image', function() {
            var id = parseInt($(this).data('id'));
            galleryIds = galleryIds.filter(function(imgId) {
                return imgId != id;
            });
            updateGalleryPreview();
        });
        
        // Remove all images
        $('#remove-all-gallery-images').on('click', function() {
            if (confirm('<?php _e('Är du säker på att du vill ta bort alla bilder?', 'adam-klingeteg'); ?>')) {
                galleryIds = [];
                updateGalleryPreview();
            }
        });
        
        // Update gallery preview
        function updateGalleryPreview() {
            var preview = $('#project-gallery-preview');
            var removeBtn = $('#remove-all-gallery-images');
            var countSpan = $('#gallery-count');
            
            preview.empty();
            
            if (galleryIds.length > 0) {
                preview.show();
                if (removeBtn.length) removeBtn.show();
                countSpan.text(galleryIds.length);
                
                galleryIds.forEach(function(id) {
                    // Get attachment from WordPress media library
                    var attachment = wp.media.attachment(id);
                    attachment.fetch().then(function() {
                        var imgUrl = attachment.get('sizes') && attachment.get('sizes').medium ? 
                                    attachment.get('sizes').medium.url : 
                                    attachment.get('url');
                        
                        var img = $('<img>').attr('src', imgUrl).css({
                            'width': '100%',
                            'height': '150px',
                            'object-fit': 'cover',
                            'display': 'block'
                        });
                        
                        var removeBtn = $('<button>')
                            .text('×')
                            .attr('type', 'button')
                            .addClass('remove-gallery-image')
                            .attr('data-id', id)
                            .css({
                                'position': 'absolute',
                                'top': '5px',
                                'right': '5px',
                                'background': '#dc3232',
                                'color': 'white',
                                'border': 'none',
                                'border-radius': '50%',
                                'width': '28px',
                                'height': '28px',
                                'cursor': 'pointer',
                                'font-size': '18px',
                                'line-height': '1',
                                'font-weight': 'bold',
                                'box-shadow': '0 2px 4px rgba(0,0,0,0.3)'
                            });
                        
                        var idLabel = $('<div>')
                            .text('ID: ' + id)
                            .css({
                                'position': 'absolute',
                                'bottom': '0',
                                'left': '0',
                                'right': '0',
                                'background': 'rgba(0,0,0,0.7)',
                                'color': 'white',
                                'padding': '4px 8px',
                                'font-size': '11px',
                                'text-align': 'center'
                            });
                        
                        var container = $('<div>').css({
                            'position': 'relative',
                            'border': '2px solid #0073aa',
                            'border-radius': '4px',
                            'overflow': 'hidden',
                            'background': 'white'
                        }).append(img).append(removeBtn).append(idLabel);
                        
                        preview.append(container);
                    }).catch(function() {
                        // Fallback if attachment fetch fails
                        console.log('Failed to fetch attachment:', id);
                    });
                });
            } else {
                preview.hide();
                if (removeBtn.length) removeBtn.hide();
                countSpan.text('0');
            }
            
            updateGalleryField();
        }
    });
    </script>
    <?php
}

/**
 * Save gallery meta box
 */
function adam_klingeteg_save_gallery_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['project_gallery_meta_box_nonce']) || !wp_verify_nonce($_POST['project_gallery_meta_box_nonce'], 'project_gallery_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save gallery
    if (isset($_POST['project_gallery_ids'])) {
        $gallery_ids = sanitize_text_field($_POST['project_gallery_ids']);
        update_post_meta($post_id, '_project_gallery', $gallery_ids);
        
        // Also save to ACF field if it exists
        if (function_exists('update_field')) {
            $ids_array = !empty($gallery_ids) ? explode(',', $gallery_ids) : array();
            $ids_array = array_filter(array_map('intval', $ids_array));
            update_field('gallery', $ids_array, $post_id);
        }
    }
}
add_action('save_post_project', 'adam_klingeteg_save_gallery_meta_box');

/**
 * Gallery management page
 */
function adam_klingeteg_gallery_management_page() {
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    
    // Enqueue media scripts
    wp_enqueue_media();
    
    // Handle form submission
    if (isset($_POST['save_gallery']) && isset($_POST['project_id']) && check_admin_referer('save_project_gallery', 'project_gallery_nonce')) {
        $project_id = intval($_POST['project_id']);
        $gallery_ids = isset($_POST['gallery_ids']) ? sanitize_text_field($_POST['gallery_ids']) : '';
        
        if ($project_id > 0) {
            update_post_meta($project_id, '_project_gallery', $gallery_ids);
            
            // Also save to ACF
            if (function_exists('update_field') && !empty($gallery_ids)) {
                $ids_array = array_filter(array_map('intval', explode(',', $gallery_ids)));
                update_field('gallery', $ids_array, $project_id);
            }
            
            echo '<div class="notice notice-success"><p>Bildgalleri sparad!</p></div>';
        }
    }
    
    // Get project ID from URL or POST
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : (isset($_POST['project_id']) ? intval($_POST['project_id']) : 0);
    
    // Get all projects
    $projects = get_posts(array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    
    // Get gallery for selected project
    $gallery_ids = array();
    if ($project_id > 0) {
        $gallery_meta = get_post_meta($project_id, '_project_gallery', true);
        if (!empty($gallery_meta)) {
            $gallery_ids = array_filter(array_map('intval', explode(',', $gallery_meta)));
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php _e('Hantera Bildgalleri för Kundcase', 'adam-klingeteg'); ?></h1>
        
        <form method="get" action="" style="margin: 20px 0;">
            <input type="hidden" name="post_type" value="project">
            <input type="hidden" name="page" value="manage-project-gallery">
            <label>
                <strong><?php _e('Välj kundcase:', 'adam-klingeteg'); ?></strong>
                <select name="project_id" style="margin-left: 10px; min-width: 300px;">
                    <option value="0"><?php _e('-- Välj kundcase --', 'adam-klingeteg'); ?></option>
                    <?php foreach ($projects as $project) : ?>
                        <option value="<?php echo esc_attr($project->ID); ?>" <?php selected($project_id, $project->ID); ?>>
                            <?php echo esc_html($project->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="button button-primary" style="margin-left: 10px;"><?php _e('Välj', 'adam-klingeteg'); ?></button>
        </form>
        
        <?php if ($project_id > 0) : 
            $project = get_post($project_id);
        ?>
            <div style="background: white; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px;">
                <h2><?php echo esc_html($project->post_title); ?></h2>
                
                <form method="post" action="" id="gallery-form">
                    <?php wp_nonce_field('save_project_gallery', 'project_gallery_nonce'); ?>
                    <input type="hidden" name="project_id" value="<?php echo esc_attr($project_id); ?>">
                    
                    <div style="margin: 20px 0;">
                        <button type="button" id="add-gallery-images-page" class="button button-primary button-large" style="font-size: 16px; padding: 10px 20px;">
                            <span style="font-size: 20px; margin-right: 8px;">+</span>
                            <?php _e('Lägg till bilder', 'adam-klingeteg'); ?>
                        </button>
                        <?php if (!empty($gallery_ids)) : ?>
                            <button type="button" id="remove-all-gallery-images-page" class="button button-secondary" style="margin-left: 10px;">
                                <?php _e('Ta bort alla bilder', 'adam-klingeteg'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div id="project-gallery-preview-page" style="display: <?php echo !empty($gallery_ids) ? 'grid' : 'none'; ?>; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
                        <?php if (!empty($gallery_ids)) : ?>
                            <?php foreach ($gallery_ids as $img_id) : 
                                $img_url = wp_get_attachment_image_url($img_id, 'medium');
                                if ($img_url) :
                            ?>
                                <div style="position: relative; border: 2px solid #0073aa; border-radius: 4px; overflow: hidden; background: white;">
                                    <img src="<?php echo esc_url($img_url); ?>" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                                    <button type="button" class="remove-gallery-image-page" data-id="<?php echo esc_attr($img_id); ?>" style="position: absolute; top: 5px; right: 5px; background: #dc3232; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 18px; line-height: 1; font-weight: bold;">×</button>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                        endif; ?>
                    </div>
                    
                    <?php if (empty($gallery_ids)) : ?>
                        <div style="padding: 40px; text-align: center; background: #f9f9f9; border: 2px dashed #ddd; border-radius: 4px; color: #666;">
                            <p style="font-size: 16px; margin: 0;"><?php _e('Inga bilder tillagda ännu. Klicka på "Lägg till bilder" ovan för att börja.', 'adam-klingeteg'); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" id="gallery_ids" name="gallery_ids" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
                    
                    <p style="margin-top: 20px;">
                        <button type="submit" name="save_gallery" class="button button-primary button-large">
                            <?php _e('Spara bildgalleri', 'adam-klingeteg'); ?>
                        </button>
                        <a href="<?php echo admin_url('post.php?action=edit&post=' . $project_id); ?>" class="button" style="margin-left: 10px;">
                            <?php _e('Tillbaka till kundcase', 'adam-klingeteg'); ?>
                        </a>
                    </p>
                </form>
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                var mediaUploader;
                var galleryIds = <?php echo json_encode($gallery_ids); ?>;
                
                function updateGalleryField() {
                    $('#gallery_ids').val(galleryIds.join(','));
                }
                
                $('#add-gallery-images-page').on('click', function(e) {
                    e.preventDefault();
                    
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    mediaUploader = wp.media({
                        title: 'Välj bilder för galleri',
                        button: { text: 'Lägg till bilder' },
                        multiple: true,
                        library: { type: 'image' }
                    });
                    
                    mediaUploader.on('select', function() {
                        var selection = mediaUploader.state().get('selection');
                        selection.map(function(attachment) {
                            var attId = attachment.id;
                            if (galleryIds.indexOf(attId) === -1) {
                                galleryIds.push(attId);
                            }
                        });
                        updateGalleryPreview();
                    });
                    
                    mediaUploader.open();
                });
                
                $(document).on('click', '.remove-gallery-image-page', function() {
                    var id = parseInt($(this).data('id'));
                    galleryIds = galleryIds.filter(function(imgId) {
                        return imgId != id;
                    });
                    updateGalleryPreview();
                });
                
                $('#remove-all-gallery-images-page').on('click', function() {
                    if (confirm('Ta bort alla bilder?')) {
                        galleryIds = [];
                        updateGalleryPreview();
                    }
                });
                
                function updateGalleryPreview() {
                    var preview = $('#project-gallery-preview-page');
                    var removeBtn = $('#remove-all-gallery-images-page');
                    
                    preview.empty();
                    
                    if (galleryIds.length > 0) {
                        preview.show();
                        removeBtn.show();
                        
                        galleryIds.forEach(function(id) {
                            var attachment = wp.media.attachment(id);
                            attachment.fetch().then(function() {
                                var imgUrl = attachment.get('sizes') && attachment.get('sizes').medium ? 
                                            attachment.get('sizes').medium.url : 
                                            attachment.get('url');
                                
                                var img = $('<img>').attr('src', imgUrl).css({
                                    'width': '100%',
                                    'height': '150px',
                                    'object-fit': 'cover',
                                    'display': 'block'
                                });
                                
                                var removeBtn = $('<button>')
                                    .text('×')
                                    .attr('type', 'button')
                                    .addClass('remove-gallery-image-page')
                                    .attr('data-id', id)
                                    .css({
                                        'position': 'absolute',
                                        'top': '5px',
                                        'right': '5px',
                                        'background': '#dc3232',
                                        'color': 'white',
                                        'border': 'none',
                                        'border-radius': '50%',
                                        'width': '28px',
                                        'height': '28px',
                                        'cursor': 'pointer',
                                        'font-size': '18px',
                                        'line-height': '1',
                                        'font-weight': 'bold'
                                    });
                                
                                var container = $('<div>').css({
                                    'position': 'relative',
                                    'border': '2px solid #0073aa',
                                    'border-radius': '4px',
                                    'overflow': 'hidden',
                                    'background': 'white'
                                }).append(img).append(removeBtn);
                                
                                preview.append(container);
                            });
                        });
                    } else {
                        preview.hide();
                        removeBtn.hide();
                    }
                    
                    updateGalleryField();
                }
            });
            </script>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Project ordering page
 */
function adam_klingeteg_project_order_page() {
    if (isset($_POST['project_order']) && check_admin_referer('save_project_order', 'project_order_nonce')) {
        $order = explode(',', sanitize_text_field($_POST['project_order']));
        foreach ($order as $index => $post_id) {
            wp_update_post(array(
                'ID' => intval($post_id),
                'menu_order' => $index
            ));
        }
        echo '<div class="notice notice-success"><p>' . __('Ordning sparad!', 'adam-klingeteg') . '</p></div>';
    }
    
    $projects = get_posts(array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    ?>
    <div class="wrap">
        <h1><?php _e('Ordna Kundcase', 'adam-klingeteg'); ?></h1>
        <p><?php _e('Dra och släpp för att ändra ordningen. Klicka på "Spara ordning" när du är klar.', 'adam-klingeteg'); ?></p>
        <form method="post" action="">
            <?php wp_nonce_field('save_project_order', 'project_order_nonce'); ?>
            <ul id="project-sortable" style="list-style: none; padding: 0;">
                <?php foreach ($projects as $project) : ?>
                    <li style="background: #fff; border: 1px solid #ccc; padding: 10px; margin: 5px 0; cursor: move;" data-id="<?php echo esc_attr($project->ID); ?>">
                        <?php echo get_the_post_thumbnail($project->ID, 'thumbnail', array('style' => 'width: 50px; height: 50px; object-fit: cover; margin-right: 10px; vertical-align: middle;')); ?>
                        <strong><?php echo esc_html($project->post_title); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" name="project_order" id="project_order" value="">
            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php _e('Spara ordning', 'adam-klingeteg'); ?>">
            </p>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#project-sortable').sortable({
            update: function() {
                var order = [];
                $('#project-sortable li').each(function() {
                    order.push($(this).data('id'));
                });
                $('#project_order').val(order.join(','));
            }
        });
    });
    </script>
    <?php
}

/**
 * Ensure contact page uses correct template
 */
function adam_klingeteg_contact_page_template($template) {
    if (is_page('contact') || is_page_template('page-contact.php')) {
        $contact_template = locate_template('page-contact.php');
        if ($contact_template) {
            return $contact_template;
        }
    }
    return $template;
}
add_filter('page_template', 'adam_klingeteg_contact_page_template');

/**
 * Fix permalink conflicts - ensure pages take priority over custom post types
 */
function adam_klingeteg_fix_permalink_conflicts($query) {
    if (!is_admin() && $query->is_main_query()) {
        // If querying for 'contact' and it's not a post type archive, treat as page
        if (isset($query->query_vars['name']) && $query->query_vars['name'] === 'contact') {
            if (!isset($query->query_vars['post_type']) || $query->query_vars['post_type'] !== 'project') {
                $query->set('post_type', 'page');
                $query->set('name', 'contact');
            }
        }
    }
}
add_action('pre_get_posts', 'adam_klingeteg_fix_permalink_conflicts', 1);

/**
 * Create contact page if it doesn't exist
 */
function adam_klingeteg_create_contact_page() {
    // Check if contact page already exists
    $contact_page = get_page_by_path('contact');
    
    if (!$contact_page) {
        // Create the contact page
        $page_data = array(
            'post_title'    => 'Contact',
            'post_name'     => 'contact',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
        );
        
        $page_id = wp_insert_post($page_data);
        
        // Set the page template
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-contact.php');
        }
    } else {
        // Ensure existing contact page uses the correct template
        update_post_meta($contact_page->ID, '_wp_page_template', 'page-contact.php');
    }
}
add_action('after_setup_theme', 'adam_klingeteg_create_contact_page');

/**
 * Auto-create contact page on theme activation
 */
function adam_klingeteg_theme_activation() {
    adam_klingeteg_create_contact_page();
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'adam_klingeteg_theme_activation');

