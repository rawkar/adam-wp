/**
 * Adam Klingeteg Portfolio Theme - Main JavaScript
 */

(function() {
    'use strict';

    // Navigation scroll effect
    const nav = document.getElementById('main-navigation');
    if (nav) {
        let scrollTimer = null;
        
        function handleScroll() {
            nav.classList.add('scrolled');
            
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                nav.classList.remove('scrolled');
            }, 150);
        }
        
        window.addEventListener('scroll', handleScroll);
    }

    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    
    if (mobileMenuToggle && mobileMenuOverlay) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = mobileMenuOverlay.style.display === 'flex';
            mobileMenuOverlay.style.display = isOpen ? 'none' : 'flex';
            document.body.style.overflow = isOpen ? '' : 'hidden';
        });

        // Close on overlay click
        mobileMenuOverlay.addEventListener('click', function(e) {
            if (e.target === mobileMenuOverlay) {
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenuOverlay.style.display === 'flex') {
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Close on menu link click
        const menuLinks = mobileMenuOverlay.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = '';
            });
        });
    }

    // Project hover effects
    const projectItems = document.querySelectorAll('.mosaic-item[data-index]');
    projectItems.forEach((item, index) => {
        item.addEventListener('mouseenter', function() {
            // Add hover class for styling
            this.classList.add('hovered');
        });
        
        item.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });

})();

