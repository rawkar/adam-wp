<?php
/**
 * Navigation Template
 *
 * @package Adam_Klingeteg
 */

$home_url = esc_url(home_url('/'));
$work_url = get_post_type_archive_link('project');
if (!$work_url) {
    $work_url = home_url('/projects/');
}

// Get contact page URL - try multiple methods to find the contact page
$contact_url = '';
$contact_page = null;

// Method 1: Try by slug
$contact_page = get_page_by_path('contact');
if ($contact_page) {
    $contact_url = get_permalink($contact_page->ID);
}

// Method 2: Try by title if slug didn't work
if (!$contact_url) {
    $contact_page = get_page_by_title('Contact');
    if ($contact_page) {
        $contact_url = get_permalink($contact_page->ID);
    }
}

// Method 3: Try to find any page with 'contact' in slug
if (!$contact_url) {
    $pages = get_pages(array(
        'number' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-contact.php'
    ));
    if (!empty($pages)) {
        $contact_url = get_permalink($pages[0]->ID);
    }
}

// Method 4: Fallback to direct URL
if (!$contact_url) {
    $contact_url = home_url('/contact/');
}

// Ensure URL is properly formatted
$contact_url = esc_url($contact_url);

// Calculate top position based on admin bar
$admin_bar_top = is_admin_bar_showing() ? '32px' : '0';
if (wp_is_mobile() && is_admin_bar_showing()) {
    $admin_bar_top = '46px';
}
?>
<nav id="main-navigation" style="position: fixed !important; top: <?php echo $admin_bar_top; ?> !important; left: 0 !important; right: 0 !important; width: 100% !important; height: 77px !important; z-index: 9999 !important; background-color: transparent !important; display: block !important; visibility: visible !important; margin: 0 !important; padding: 0 !important;">
    <div style="max-width: 80rem; margin-left: auto; margin-right: auto; padding-left: 1.5rem; padding-right: 1.5rem; height: 100%; display: flex !important; align-items: center !important; justify-content: space-between !important; visibility: visible !important; opacity: 1 !important;">
        <!-- Logo -->
        <a href="<?php echo $home_url; ?>" style="text-decoration: none !important; display: inline-block !important; padding-left: 32px; color: #ffffff !important; visibility: visible !important; opacity: 1 !important;">
            <div style="display: flex !important; align-items: center !important; visibility: visible !important; opacity: 1 !important;">
                <span style="color: #ffffff !important; font-weight: 700 !important; font-size: 1.5rem !important; line-height: 2rem !important; font-family: Georgia, 'Times New Roman', serif !important; text-transform: lowercase !important; visibility: visible !important; opacity: 1 !important; display: inline !important;">
                    adam
                </span>
                <span style="color: #ffffff !important; font-weight: 400 !important; font-size: 1.5rem !important; line-height: 2rem !important; font-family: Georgia, 'Times New Roman', serif !important; margin-left: 0.5rem; text-transform: lowercase !important; visibility: visible !important; opacity: 1 !important; display: inline !important;">
                    klingeteg
                </span>
            </div>
        </a>

        <!-- Navigation Links -->
        <div style="display: flex !important; align-items: center !important; gap: 24px !important; visibility: visible !important; opacity: 1 !important;">
            <a 
                href="<?php echo esc_url($work_url); ?>" 
                style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none !important; font-family: 'Tenor Sans', system-ui, sans-serif !important; font-weight: 500 !important; letter-spacing: 0.025em !important; font-size: 1rem !important; transition: color 0.3s ease !important; visibility: visible !important; opacity: 1 !important; display: inline-block !important;"
                onmouseover="this.style.color='#ffffff'" 
                onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'"
            >
                Work
            </a>
            <a 
                href="<?php echo esc_url($contact_url); ?>" 
                style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none !important; font-family: 'Tenor Sans', system-ui, sans-serif !important; font-weight: 500 !important; letter-spacing: 0.025em !important; font-size: 1rem !important; transition: color 0.3s ease !important; visibility: visible !important; opacity: 1 !important; display: inline-block !important;"
                onmouseover="this.style.color='#ffffff'" 
                onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'"
            >
                Contact
            </a>
        </div>
    </div>
</nav>
