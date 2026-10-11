// =============================================
// AURA DÉCOR — MAIN JAVASCRIPT
// =============================================

document.addEventListener('DOMContentLoaded', function () {

    // ---- NAVBAR SCROLL ----
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // ---- HAMBURGER MENU ----
    const hamburger = document.getElementById('hamburger');
    hamburger.addEventListener('click', () => {
        document.body.classList.toggle('nav-mobile-open');
    });
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => document.body.classList.remove('nav-mobile-open'));
    });

    // ---- GALLERY FILTER ----
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            galleryItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.classList.remove('hidden');
                    item.style.animation = 'fadeUp 0.4s ease';
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });

    // ---- LIGHTBOX ----
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxClose = document.getElementById('lightboxClose');

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            const title = item.querySelector('h4');
            lightboxImg.src = img.src;
            lightboxTitle.textContent = title ? title.textContent : '';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    lightboxClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ---- BOOKING FORM SUBMISSION ----
    const bookingForm = document.getElementById('bookingForm');
    const bookingMsg = document.getElementById('bookingMsg');
    const submitBtn = document.getElementById('submitBtn');

    if (bookingForm) {
        bookingForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.querySelector('span').textContent = 'Submitting...';

            const formData = new FormData(bookingForm);

            try {
                const response = await fetch('php/api.php?action=submit_booking', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                bookingMsg.style.display = 'block';
                if (data.success) {
                    bookingMsg.className = 'booking-msg success';
                    bookingMsg.textContent = data.message;
                    bookingForm.reset();
                } else {
                    bookingMsg.className = 'booking-msg error';
                    bookingMsg.textContent = data.message;
                }
            } catch (err) {
                bookingMsg.style.display = 'block';
                bookingMsg.className = 'booking-msg error';
                bookingMsg.textContent = 'Something went wrong. Please try again or call us directly.';
            }

            submitBtn.disabled = false;
            submitBtn.querySelector('span').textContent = 'Confirm Booking';
            bookingMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    }

    // ---- CONTACT FORM ----
    const contactForm = document.getElementById('contactForm');
    const contactMsg = document.getElementById('contactMsg');

    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(contactForm);

            try {
                const response = await fetch('php/api.php?action=submit_contact', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                contactMsg.style.display = 'block';
                contactMsg.className = 'booking-msg ' + (data.success ? 'success' : 'error');
                contactMsg.textContent = data.message;
                if (data.success) contactForm.reset();
            } catch (err) {
                contactMsg.style.display = 'block';
                contactMsg.className = 'booking-msg error';
                contactMsg.textContent = 'Failed to send. Please email us directly.';
            }
        });
    }

    // ---- SMOOTH SCROLL ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ---- SCROLL REVEAL ANIMATION ----
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.service-card, .testi-card, .gallery-item, .ab-feat').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(24px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // ---- SET MIN DATE FOR BOOKING ----
    const dateInput = document.querySelector('input[name="event_date"]');
    if (dateInput) {
        const today = new Date();
        const minDate = new Date(today.setDate(today.getDate() + 3));
        dateInput.min = minDate.toISOString().split('T')[0];
    }
});
