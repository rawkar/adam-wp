<?php
/**
 * Template part for displaying posts
 *
 * @package Adam_Klingeteg
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header responsive-padding">
        <?php the_title('<h1 class="font-heading">', '</h1>'); ?>
    </header>

    <div class="entry-content responsive-padding">
        <?php
        the_content();
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'adam-klingeteg'),
            'after'  => '</div>',
        ));
        ?>
    </div>
</article>

