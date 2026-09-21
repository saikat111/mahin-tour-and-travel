/**
 * Mahin Travel & Tours - Master Frontend Interactions
 * Vanilla JavaScript (zero external dependencies)
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Drawer Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mobileDrawer = document.querySelector('.mobile-drawer');
    const drawerClose = document.querySelector('.mobile-close');
    const drawerOverlay = document.querySelector('.drawer-overlay');

    function openDrawer() {
        if (mobileDrawer) mobileDrawer.classList.add('open');
        if (drawerOverlay) drawerOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if (mobileDrawer) mobileDrawer.classList.remove('open');
        if (drawerOverlay) drawerOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (mobileToggle) mobileToggle.addEventListener('click', openDrawer);
    if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
    if (drawerOverlay) drawerOverlay.addEventListener('click', closeDrawer);

    // 2. Sticky Navbar Glassmorphism on Scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            navbar?.classList.add('scrolled');
        } else {
            navbar?.classList.remove('scrolled');
        }
    }, { passive: true });

    // 3. Cinematic Hero Slider Engine
    initHeroSlider();

    function initHeroSlider() {
        const slides = document.querySelectorAll('.hero-slide');
        const prevBtn = document.querySelector('.slider-arrow.prev');
        const nextBtn = document.querySelector('.slider-arrow.next');
        const pills = document.querySelectorAll('.slider-pill');
        const progressFill = document.querySelector('.slider-progress-fill');
        const container = document.querySelector('.hero-slider-container');

        if (!slides || slides.length === 0) return;

        let currentIndex = 0;
        let slideInterval = null;
        const duration = 6000; // 6 seconds per slide
        let startTime = Date.now();
        let animationFrame = null;

        function updateProgress() {
            const elapsed = Date.now() - startTime;
            const percentage = Math.min(100, (elapsed / duration) * 100);
            if (progressFill) {
                progressFill.style.width = percentage + '%';
            }
            if (elapsed < duration) {
                animationFrame = requestAnimationFrame(updateProgress);
            }
        }

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });

            pills.forEach((pill, i) => {
                if (i === index) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });

            currentIndex = index;
            startTime = Date.now();
            cancelAnimationFrame(animationFrame);
            updateProgress();
        }

        function nextSlide() {
            let nextIndex = (currentIndex + 1) % slides.length;
            showSlide(nextIndex);
        }

        function prevSlide() {
            let prevIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(prevIndex);
        }

        function startAutoPlay() {
            stopAutoPlay();
            startTime = Date.now();
            updateProgress();
            slideInterval = setInterval(nextSlide, duration);
        }

        function stopAutoPlay() {
            if (slideInterval) clearInterval(slideInterval);
            cancelAnimationFrame(animationFrame);
        }

        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                startAutoPlay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                startAutoPlay();
            });
        }

        pills.forEach((pill, idx) => {
            pill.addEventListener('click', () => {
                showSlide(idx);
                startAutoPlay();
            });
        });

        if (container) {
            container.addEventListener('mouseenter', stopAutoPlay);
            container.addEventListener('mouseleave', startAutoPlay);

            // Touch swipe gestures
            let touchStartX = 0;
            let touchEndX = 0;

            container.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            container.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 50) {
                    nextSlide();
                    startAutoPlay();
                } else if (touchEndX > touchStartX + 50) {
                    prevSlide();
                    startAutoPlay();
                }
            }, { passive: true });
        }

        // Initialize first slide and kick off timer
        showSlide(0);
        startAutoPlay();
    }

    // 4. FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const questionBtn = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');

        questionBtn?.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Close other items for single-open experience
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    const otherAns = otherItem.querySelector('.faq-answer');
                    if (otherAns) otherAns.style.maxHeight = null;
                }
            });

            if (!isActive) {
                item.classList.add('active');
                if (answer) answer.style.maxHeight = answer.scrollHeight + 'px';
            } else {
                item.classList.remove('active');
                if (answer) answer.style.maxHeight = null;
            }
        });
    });

    // 5. Quick Inquiry Modal Triggering (For Tour & Service pages)
    const modalTriggers = document.querySelectorAll('[data-inquiry-modal]');
    const modal = document.getElementById('inquiryModal');
    const modalClose = document.querySelector('.modal-close');
    const modalServiceInput = document.getElementById('modalServiceInput');

    modalTriggers.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const serviceName = btn.getAttribute('data-service-name') || '';
            if (modalServiceInput) modalServiceInput.value = serviceName;
            if (modal) modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    if (modalClose) {
        modalClose.addEventListener('click', () => {
            if (modal) modal.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    }

    // 6. Contact Form Client-side Submission feedback
    const contactForms = document.querySelectorAll('.ajax-contact-form');
    contactForms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Sending Message...';
            }
        });
    });
});
