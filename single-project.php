<?php
/**
 * Single Project Template
 *
 * @package Adam_Klingeteg
 */

get_header();

while (have_posts()) :
    the_post();
    
    $project_title = get_the_title();
    $project_description = get_field('description');
    if (empty($project_description)) {
        $project_description = get_the_content();
    }
    $gallery = adam_klingeteg_get_project_gallery();
    $year = adam_klingeteg_get_project_year();
    $tags = adam_klingeteg_get_project_tags();
?>

<main class="min-h-screen bg-black text-white">
    <div class="main-content mt-[77px]">
        <!-- Project Header -->
        <div class="responsive-padding">
            <div class="mb-8 md:mb-12 project-header-text">
                <div class="responsive-gap">
                    <h1 class="font-heading tracking-tight" style="font-size: clamp(1.5rem, 5vw, 2.5rem)">
                        <?php echo esc_html($project_title); ?>
                    </h1>
                    <?php if (!empty($project_description)) : ?>
                        <p class="text-white-80 max-w-2xl font-body" style="font-size: clamp(0.875rem, 2.5vw, 1.125rem)">
                            <?php echo wp_kses_post($project_description); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Image Gallery - All images displayed -->
        <?php if (!empty($gallery)) : ?>
            <div class="responsive-mosaic-grid project-gallery-grid" id="project-gallery" style="display: grid; gap: 2px; background-color: #000000; width: 100%; margin: 0; padding: 0;">
                <?php foreach ($gallery as $index => $image_url) : ?>
                    <div 
                        class="mosaic-item project-gallery-item"
                        data-image-index="<?php echo esc_attr($index); ?>"
                        style="position: relative; overflow: hidden; cursor: pointer; background-color: #000;"
                    >
                        <img
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($project_title . ' - Image ' . ($index + 1)); ?>"
                            style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease;"
                            onmouseover="this.style.transform='scale(1.05)'"
                            onmouseout="this.style.transform='scale(1)'"
                        />
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.2); transition: background 0.3s ease; pointer-events: none;" 
                             onmouseover="this.style.background='rgba(0,0,0,0.1)'"
                             onmouseout="this.style.background='rgba(0,0,0,0.2)'"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php if (!empty($gallery)) : ?>
<!-- Lightbox Modal -->
<div id="lightbox-modal" class="lightbox-modal" style="display: none;">
    <div class="lightbox-overlay" id="lightbox-overlay"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightbox-close" aria-label="<?php esc_attr_e('Close', 'adam-klingeteg'); ?>">×</button>
        <?php if (count($gallery) > 1) : ?>
        <button class="lightbox-nav lightbox-prev" id="lightbox-prev" aria-label="<?php esc_attr_e('Previous', 'adam-klingeteg'); ?>">‹</button>
        <button class="lightbox-nav lightbox-next" id="lightbox-next" aria-label="<?php esc_attr_e('Next', 'adam-klingeteg'); ?>">›</button>
        <?php endif; ?>
        <img id="lightbox-image" src="" alt="" />
        <div class="lightbox-counter">
            <span id="lightbox-current">1</span> / <span id="lightbox-total"><?php echo esc_html(count($gallery)); ?></span>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($gallery)) : ?>
<script>
// Lightbox functionality
(function() {
    const gallery = <?php echo wp_json_encode($gallery); ?>;
    const modal = document.getElementById('lightbox-modal');
    const overlay = document.getElementById('lightbox-overlay');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    const image = document.getElementById('lightbox-image');
    const currentSpan = document.getElementById('lightbox-current');
    const totalSpan = document.getElementById('lightbox-total');
    let currentIndex = 0;

    if (!modal || gallery.length === 0) return;

    // Open lightbox
    document.querySelectorAll('[data-image-index]').forEach((item) => {
        item.addEventListener('click', function() {
            currentIndex = parseInt(this.getAttribute('data-image-index'), 10);
            openLightbox();
        });
    });

    function openLightbox() {
        updateImage();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function updateImage() {
        if (image && gallery[currentIndex]) {
            image.src = gallery[currentIndex];
            image.alt = '<?php echo esc_js($project_title); ?> - Image ' + (currentIndex + 1);
        }
        if (currentSpan) {
            currentSpan.textContent = currentIndex + 1;
        }
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % gallery.length;
        updateImage();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + gallery.length) % gallery.length;
        updateImage();
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeLightbox);
    }
    if (overlay) {
        overlay.addEventListener('click', closeLightbox);
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', nextImage);
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', prevImage);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal && modal.style.display === 'flex') {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        }
    });
})();
</script>
<?php endif; ?>

<?php
endwhile;
get_footer();

