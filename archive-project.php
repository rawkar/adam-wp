<?php
/**
 * Project Archive Template
 *
 * @package Adam_Klingeteg
 */

get_header();

$projects_query = adam_klingeteg_get_projects();
?>

<main class="min-h-screen bg-black text-white">
    <div class="main-content mt-[77px]">
        <?php if ($projects_query->have_posts()) : ?>
            <div class="archive-grid work-grid">
                <?php while ($projects_query->have_posts()) : 
                    $projects_query->the_post();
                    $cover_image = adam_klingeteg_get_project_cover_image();
                    $project_title = get_the_title();
                    $project_url = get_permalink();
                ?>
                    <a
                        href="<?php echo esc_url($project_url); ?>"
                        class="work-project-item"
                        style="position: relative; display: block; width: 100%; height: 100%; overflow: hidden; cursor: pointer;"
                    >
                        <?php if ($cover_image) : ?>
                            <img
                                src="<?php echo esc_url($cover_image); ?>"
                                alt="<?php echo esc_attr($project_title); ?>"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'"
                            />
                        <?php endif; ?>
                        
                        <!-- Dark overlay for text readability -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.5)); pointer-events: none;"></div>
                        
                        <!-- Project title - always visible -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; text-align: center; padding: 1rem; pointer-events: none;">
                            <h3 style="color: #ffffff; font-family: 'Roboto', system-ui, sans-serif; font-weight: 700; font-size: clamp(1rem, 2.5vw, 1.5rem); text-shadow: 0 2px 10px rgba(0,0,0,0.9), 0 4px 20px rgba(0,0,0,0.7); line-height: 1.2; margin: 0;">
                                <?php echo esc_html($project_title); ?>
                            </h3>
                        </div>
                    </a>
                <?php endwhile; 
                wp_reset_postdata();
                ?>
            </div>
        <?php else : ?>
            <div class="responsive-padding">
                <p><?php esc_html_e('No projects found.', 'adam-klingeteg'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();

