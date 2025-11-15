<?php
/**
 * Contact Page Template
 *
 * @package Adam_Klingeteg
 */

get_header();

$contact = adam_klingeteg_get_contact_info();
?>

<main style="min-height: 100vh; background-color: #000000; color: #ffffff; display: flex; align-items: center; justify-content: center; padding-top: 77px; box-sizing: border-box;">
    <div style="width: 100%; max-width: 56rem; margin: 0 auto; padding: 2rem; text-align: center; box-sizing: border-box;">
        
        <!-- Name - Large, centered, at top -->
        <h1 style="font-family: 'Roboto', system-ui, sans-serif; font-weight: 700; font-size: clamp(2.5rem, 8vw, 4.5rem); line-height: 1.1; color: #ffffff; margin: 0 0 0.5rem 0; letter-spacing: -0.02em; padding: 0;">
            <?php echo esc_html($contact['name']); ?>
        </h1>

        <!-- Title - Below name, smaller -->
        <h2 style="font-family: 'Tenor Sans', system-ui, sans-serif; font-weight: 400; font-size: clamp(1.25rem, 4vw, 2rem); line-height: 1.6; color: rgba(255, 255, 255, 0.8); margin: 0 0 0 0; padding: 0;">
            <?php echo esc_html($contact['title']); ?>
        </h2>

        <!-- Contact Information - Centered below, with icons -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem; align-items: center; margin-top: 4rem; padding: 0;">
            <!-- Email -->
            <a
                href="mailto:<?php echo esc_attr($contact['email']); ?>"
                style="color: rgba(255, 255, 255, 0.8); text-decoration: none; font-family: 'Tenor Sans', system-ui, sans-serif; font-size: clamp(0.875rem, 2vw, 1rem); display: flex; align-items: center; gap: 0.5rem; transition: opacity 0.3s ease;"
                onmouseover="this.style.opacity='0.7'" 
                onmouseout="this.style.opacity='1'"
            >
                <span style="font-size: 1.2em; opacity: 0.7;">✉</span>
                <span><?php echo esc_html($contact['email']); ?></span>
            </a>

            <!-- Phone -->
            <a
                href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact['phone'])); ?>"
                style="color: rgba(255, 255, 255, 0.8); text-decoration: none; font-family: 'Tenor Sans', system-ui, sans-serif; font-size: clamp(0.875rem, 2vw, 1rem); display: flex; align-items: center; gap: 0.5rem; transition: opacity 0.3s ease;"
                onmouseover="this.style.opacity='0.7'" 
                onmouseout="this.style.opacity='1'"
            >
                <span style="font-size: 1.2em; opacity: 0.7;">📞</span>
                <span><?php echo esc_html($contact['phone']); ?></span>
            </a>

            <!-- Instagram -->
            <a
                href="<?php echo esc_url($contact['instagram']); ?>"
                target="_blank"
                rel="noopener noreferrer"
                style="color: rgba(255, 255, 255, 0.8); text-decoration: none; font-family: 'Tenor Sans', system-ui, sans-serif; font-size: clamp(0.875rem, 2vw, 1rem); display: flex; align-items: center; gap: 0.5rem; transition: opacity 0.3s ease;"
                onmouseover="this.style.opacity='0.7'" 
                onmouseout="this.style.opacity='1'"
            >
                <span style="font-size: 1.2em; opacity: 0.7;">📷</span>
                <span>@ADAMKLINGETEG</span>
            </a>
        </div>

    </div>
</main>

<?php
get_footer();
