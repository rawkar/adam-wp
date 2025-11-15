<?php
/**
 * Front Page Template
 *
 * @package Adam_Klingeteg
 */

get_header();

$projects_query = adam_klingeteg_get_projects(array(
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
?>

<main class="min-h-screen bg-black text-white">
    <div class="main-content mt-[87px]">
        <?php if ($projects_query->have_posts()) : ?>
            <div class="responsive-mosaic-grid">
                <?php 
                $index = 0;
                while ($projects_query->have_posts()) : 
                    $projects_query->the_post();
                    $cover_image = adam_klingeteg_get_project_cover_image();
                    $project_title = get_the_title();
                    $project_description = get_field('description');
                    if (empty($project_description)) {
                        $project_description = get_the_excerpt();
                    }
                    $project_url = get_permalink();
                ?>
                    <div class="mosaic-item group cursor-pointer bg-black" data-index="<?php echo esc_attr($index); ?>">
                        <a href="<?php echo esc_url($project_url); ?>" class="block h-full w-full">
                            <div class="relative h-full w-full overflow-hidden">
                                <?php if ($cover_image) : ?>
                                    <img
                                        src="<?php echo esc_url($cover_image); ?>"
                                        alt="<?php echo esc_attr($project_title); ?>"
                                        class="object-cover transition-all duration-700 ease-in-out scale-100 group-hover:scale-105"
                                        style="width: 100%; height: 100%;"
                                    />
                                <?php endif; ?>
                                
                                <!-- Dark overlay -->
                                <div class="project-hover-overlay absolute inset-0 transition-all duration-300 ease-out"></div>
                                
                                <!-- Text overlay -->
                                <div class="project-hover-text absolute inset-0 flex flex-col items-center justify-center transition-all duration-500 ease-out">
                                    <div class="text-center p-4 max-w-[90%]">
                                        <h3 class="text-white font-heading text-center mb-2 tracking-wide drop-shadow-lg responsive-title">
                                            <?php echo esc_html($project_title); ?>
                                        </h3>
                                        <?php if (!empty($project_description)) : ?>
                                            <p class="text-white font-body text-center leading-relaxed drop-shadow-lg responsive-description">
                                                <?php 
                                                $short_desc = wp_trim_words($project_description, 10, '...');
                                                echo esc_html($short_desc); 
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php 
                    $index++;
                endwhile; 
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

