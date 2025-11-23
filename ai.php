<?php
require_once 'php/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Gallery | Emerald Tech Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0a7c42;
            --primary-dark: #066633;
            --primary-light: #e8f5e9;
            --secondary: #ff6b6b;
            --accent: #ffd166;
            --dark: #2d3047;
            --light: #f7f9fc;
            --text: #333333;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Comic Neue', cursive, Arial, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            font-size: 28px;
            color: white;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .nav-links a:hover {
            color: var(--accent);
            transform: translateY(-2px);
        }
        
        .cta-button {
            background-color: var(--accent);
            color: var(--dark);
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1);
        }
        
        .cta-button:hover {
            background-color: #ffc145;
            transform: translateY(-3px);
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.1);
        }
        
        /* Gallery Section */
        .gallery-section {
            padding: 100px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .section-title p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .gallery-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            position: relative;
        }
        
        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .gallery-image {
            width: 100%;
            height: 250px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .gallery-item:hover .gallery-image img {
            transform: scale(1.05);
        }
        
        .gallery-info {
            padding: 20px;
        }
        
        .gallery-info h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .gallery-info p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .product-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .free-gift-tag {
            background: var(--secondary);
        }
        
        /* Lightbox Modal */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        
        .lightbox-content img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
        }
        
        .lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .lightbox-close:hover {
            color: var(--accent);
        }
        
        .lightbox-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        
        .lightbox-nav button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .lightbox-nav button:hover {
            background: var(--primary);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            padding: 60px 0 30px;
            color: white;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-column h4 {
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: var(--accent);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #999;
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .gallery-container {
                grid-template-columns: 1fr;
            }
            
            .lightbox-nav button {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="logo-text">Emerald Tech Hub</div>
                </div>
                <nav class="nav-links">
                    <a href="#gallery">Gallery</a>
                    <a href="#products">Products</a>
                    <a href="#order">Order Now</a>
                </nav>
                <button class="cta-button">Shop Now</button>
            </div>
        </div>
    </header>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="section-title">
                <h2>Our Product Gallery</h2>
                <p>Explore our range of educational copybooks and free gifts designed to make learning fun for children</p>
            </div>
            
            <div class="gallery-container">
                <!-- Product 1 -->
                <div class="gallery-item">
                    <div class="product-tag">Copybook</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1589994965851-a8f479c573a9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2hpbGRyZW4lMjBib29rfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Alphabet Copybook">
                    </div>
                    <div class="gallery-info">
                        <h3>Alphabet Copybook</h3>
                        <p>Learn uppercase and lowercase letters with fun tracing activities and colorful illustrations.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="gallery-item">
                    <div class="product-tag">Copybook</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Y2hpbGRyZW4lMjBib29rfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Numbers Copybook">
                    </div>
                    <div class="gallery-info">
                        <h3>Numbers Copybook</h3>
                        <p>Master numbers 1-100 with counting exercises, tracing activities, and visual associations.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="gallery-item">
                    <div class="product-tag">Copybook</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Y2hpbGRyZW4lMjBib29rfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Math Copybook">
                    </div>
                    <div class="gallery-info">
                        <h3>Math Copybook</h3>
                        <p>Addition and subtraction practice with visual aids and engaging math problems for young learners.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="gallery-item">
                    <div class="product-tag">Copybook</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1568667256549-094345857637?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Y2hpbGRyZW4lMjBib29rfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Drawing Copybook">
                    </div>
                    <div class="gallery-info">
                        <h3>Drawing Copybook</h3>
                        <p>Develop fine motor skills with basic shapes, simple strokes, and creative drawing exercises.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
                
                <!-- Product 5 -->
                <div class="gallery-item">
                    <div class="product-tag free-gift-tag">Free Gift</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1611224923853-80b023f02d71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGtpZHMlMjB0b290aGJydXNofGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="U-Shaped Toothbrush">
                    </div>
                    <div class="gallery-info">
                        <h3>U-Shaped Toothbrush</h3>
                        <p>Make brushing fun with our kid-friendly U-shaped toothbrush that cleans all teeth at once.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
                
                <!-- Product 6 -->
                <div class="gallery-item">
                    <div class="product-tag free-gift-tag">Free Gift</div>
                    <div class="gallery-image">
                        <img src="https://images.unsplash.com/photo-1598808503746-f34cfb5c0a0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c2tpcHBpbmclMjByb3BlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="Skipping Rope">
                    </div>
                    <div class="gallery-info">
                        <h3>Adjustable Skipping Rope</h3>
                        <p>High-quality NBR skipping rope for active play, exercise, and developing coordination.</p>
                        <button class="cta-button">View Details</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <span class="lightbox-close" id="lightbox-close">&times;</span>
            <img id="lightbox-image" src="" alt="">
            <div class="lightbox-nav">
                <button id="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
                <button id="lightbox-next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Emerald Tech Hub</h4>
                    <p>Helping children develop beautiful handwriting through fun, educational copybooks since 2015.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h4>Shop</h4>
                    <ul class="footer-links">
                        <li><a href="#">All Products</a></li>
                        <li><a href="#">Starter Set</a></li>
                        <li><a href="#">Learning Bundle</a></li>
                        <li><a href="#">Mastery Collection</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 Emerald Tech Hub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Lightbox functionality
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        
        const galleryImages = document.querySelectorAll('.gallery-image img');
        let currentImageIndex = 0;
        
        // Open lightbox when clicking on gallery images
        galleryImages.forEach((image, index) => {
            image.addEventListener('click', () => {
                currentImageIndex = index;
                updateLightboxImage();
                lightbox.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Close lightbox
        lightboxClose.addEventListener('click', () => {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Close lightbox when clicking outside the image
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
        
        // Navigate to previous image
        lightboxPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            updateLightboxImage();
        });
        
        // Navigate to next image
        lightboxNext.addEventListener('click', (e) => {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            updateLightboxImage();
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (lightbox.style.display === 'flex') {
                if (e.key === 'Escape') {
                    lightbox.style.display = 'none';
                    document.body.style.overflow = 'auto';
                } else if (e.key === 'ArrowLeft') {
                    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                    updateLightboxImage();
                } else if (e.key === 'ArrowRight') {
                    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                    updateLightboxImage();
                }
            }
        });
        
        // Update lightbox image
        function updateLightboxImage() {
            const imageSrc = galleryImages[currentImageIndex].getAttribute('src');
            const imageAlt = galleryImages[currentImageIndex].getAttribute('alt');
            lightboxImage.setAttribute('src', imageSrc);
            lightboxImage.setAttribute('alt', imageAlt);
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>