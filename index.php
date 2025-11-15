<?php
/**
 * Main template file
 *
 * @package Adam_Klingeteg
 */

get_header();
?>

<main class="min-h-screen bg-black text-white">
    <div class="main-content mt-[87px]">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                get_template_part('template-parts/content', get_post_type());
            }
        } else {
            get_template_part('template-parts/content', 'none');
        }
        ?>
    </div>
</main>

<?php
get_footer();

