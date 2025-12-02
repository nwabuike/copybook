<?php
// Fetch current pricing from database
require_once 'php/db.php';

// Default prices (fallback if database is unavailable)
$defaultPrices = [
    'starter' => ['price' => 18000, 'original' => 22500],
    'bundle' => ['price' => 32000, 'original' => 45000],
    'collection' => ['price' => 45000, 'original' => 67500]
];

// Fetch current prices from database
$prices = [];
try {
    $sql = "SELECT package_type, price FROM package_pricing ORDER BY FIELD(package_type, 'starter', 'bundle', 'collection')";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $currentPrice = (float)$row['price'];
            // Calculate original price (assuming 25% discount for starter, 29% for bundle, 33% for collection)
            $originalPrice = match($row['package_type']) {
                'starter' => round($currentPrice * 1.25, -2), // 25% markup
                'bundle' => round($currentPrice * 1.40625, -2), // 40.625% markup
                'collection' => round($currentPrice * 1.50, -2), // 50% markup
                default => round($currentPrice * 1.25, -2)
            };
            
            $prices[$row['package_type']] = [
                'price' => $currentPrice,
                'original' => $originalPrice,
                'savings' => $originalPrice - $currentPrice,
                'discount' => round((($originalPrice - $currentPrice) / $originalPrice) * 100)
            ];
        }
    }
} catch (Exception $e) {
    error_log("Error fetching prices: " . $e->getMessage());
}

// Use default prices if database fetch failed
if (empty($prices)) {
    foreach ($defaultPrices as $type => $priceData) {
        $prices[$type] = [
            'price' => $priceData['price'],
            'original' => $priceData['original'],
            'savings' => $priceData['original'] - $priceData['price'],
            'discount' => round((($priceData['original'] - $priceData['price']) / $priceData['original']) * 100)
        ];
    }
}

// Helper function to format price
function formatPrice($amount) {
    return '₦' . number_format($amount, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary SEO Meta Tags -->
    <title>Buy Sank Magic Copybook for Kids | Facebook Marketplace | Reusable 4-in-1 Handwriting Practice Book Nigeria</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <meta name="description" content="🎁 Best Reusable Magic Copybook for Kids 3+ in Nigeria! <?php echo formatPrice($prices['starter']['price']); ?> - <?php echo formatPrice($prices['collection']['price']); ?>. Improve handwriting fast with grooved practice books. FREE delivery nationwide. Pay on delivery. Black Friday cashback ₦10,000 + free gifts worth ₦12,000. Perfect gift for children, nieces, nephews!">
    <meta name="keywords" content="magic copybook Nigeria, reusable copybook for kids, Sank magic copybook, handwriting practice book, calligraphy copybook Nigeria, kids writing practice book, disappearing ink copybook, 4 in 1 magic book, children educational toys Nigeria, handwriting improvement book, alphabet practice book, number writing book, buy copybook online Nigeria, best copybook for kids, magic pen copybook, grooved copybook, pen control books kids, Lagos copybook delivery, Abuja kids books, free delivery copybook Nigeria, pay on delivery copybook, kids learning materials Nigeria, preschool writing books, kindergarten practice books, cursive writing copybook, letter tracing books, Black Friday kids books, copybook discount Nigeria, children gift ideas Nigeria, educational gifts for kids, back to school books Nigeria, homeschool materials Nigeria, montessori writing materials, fine motor skills books, toddler writing practice, ages 3-12 copybooks">
    <meta name="author" content="Emerald Tech Hub">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="https://yourdomain.com/magicbook/index.php">
    
    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="product" />
    <meta property="og:url" content="https://yourdomain.com/magicbook/index.php" />
    <meta property="og:site_name" content="Emerald Tech Hub - Kids Educational Products" />
    <meta property="og:title" content="🔥 Sank Magic Copybook Nigeria | Reusable Handwriting Practice Book for Kids | Black Friday Sale" />
    <meta property="og:description" content="💰 Save up to ₦22,500! Get ₦10,000 cashback + ₦12,000 free gifts. Reusable 4-in-1 magic copybook improves kids handwriting in weeks. FREE delivery across Nigeria. Ages 3+. Perfect surprise gift!" />
    <meta property="og:image" content="https://yourdomain.com/magicbook/images/magic-book-3-1024x1024.jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:locale" content="en_NG" />
    <meta property="product:price:amount" content="<?php echo $prices['starter']['price']; ?>" />
    <meta property="product:price:currency" content="NGN" />
    <meta property="product:availability" content="in stock" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="🎁 Sank Magic Reusable Copybook Nigeria | Kids Handwriting Practice | Black Friday Deal" />
    <meta name="twitter:description" content="<?php echo formatPrice($prices['starter']['price']); ?> - <?php echo formatPrice($prices['collection']['price']); ?> | Improve children's handwriting fast! Reusable 4-in-1 copybook. FREE delivery. Pay on delivery. Get ₦10,000 cashback + gifts" />
    <meta name="twitter:image" content="https://yourdomain.com/magicbook/images/magic-book-3-1024x1024.jpg" />
    <meta name="twitter:site" content="@EmeraldTechHub" />
    
    <!-- Additional SEO Meta Tags -->
    <meta name="geo.region" content="NG" />
    <meta name="geo.placename" content="Nigeria" />
    <meta name="geo.position" content="9.082;8.6753" />
    <meta name="ICBM" content="9.082, 8.6753" />
    <meta name="language" content="English">
    <meta name="coverage" content="Nigeria">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="target" content="parents, teachers, educators, gift buyers">
    <meta name="audience" content="parents with kids ages 3-12">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Structured Data for Google Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "Sank Magic Reusable Copybook 4-in-1 for Kids",
      "image": [
        "https://yourdomain.com/magicbook/images/magic-book-3-1024x1024.jpg",
        "https://yourdomain.com/magicbook/images/slider1.jpg",
        "https://yourdomain.com/magicbook/images/magic_sank.jpg"
      ],
      "description": "Reusable magic copybook for kids ages 3-12. Improves handwriting with grooved letters and numbers. Ink disappears in 5 minutes for reuse. 4-in-1 practice book includes alphabet, numbers, math and drawing exercises. Perfect educational gift.",
      "brand": {
        "@type": "Brand",
        "name": "Sank Magic"
      },
      "offers": {
        "@type": "AggregateOffer",
        "url": "https://yourdomain.com/magicbook/index.php",
        "priceCurrency": "NGN",
        "lowPrice": "<?php echo $prices['starter']['price']; ?>",
        "highPrice": "<?php echo $prices['collection']['price']; ?>",
        "priceValidUntil": "2025-12-31",
        "availability": "https://schema.org/InStock",
        "seller": {
          "@type": "Organization",
          "name": "Emerald Tech Hub"
        }
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "247"
      },
      "category": "Educational Toys & Books",
      "audience": {
        "@type": "PeopleAudience",
        "suggestedMinAge": 3,
        "suggestedMaxAge": 12
      }
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Emerald Tech Hub - Kids Educational Products",
      "url": "https://yourdomain.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://yourdomain.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Emerald Tech Hub",
      "url": "https://yourdomain.com",
      "logo": "https://yourdomain.com/magicbook/images/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+234-902-902-6782",
        "contactType": "Customer Service",
        "areaServed": "NG",
        "availableLanguage": ["English"]
      },
      "sameAs": [
        "https://facebook.com/emeraldtechhub",
        "https://instagram.com/emeraldtechhub"
      ]
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://yourdomain.com"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "Kids Educational Products",
        "item": "https://yourdomain.com/kids-products"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "Magic Copybooks",
        "item": "https://yourdomain.com/magicbook"
      }]
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [{
        "@type": "Question",
        "name": "What is Sank Magic Copybook?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Sank Magic Copybook is a reusable 4-in-1 handwriting practice book for kids ages 3-12. It features grooved letters and numbers that guide children's hand movements. The special magic pen ink disappears within 5 minutes, allowing unlimited reuse without needing new books."
        }
      },{
        "@type": "Question",
        "name": "How does the magic copybook work?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "The copybook has grooved letters, numbers, and shapes that guide your child's hand for proper pen control and muscle memory. Kids write with the included magic pen, and the ink automatically fades away after 5 minutes, making the pages ready to use again. This helps children practice repeatedly without wasting paper."
        }
      },{
        "@type": "Question",
        "name": "What age is the magic copybook suitable for?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "The Sank Magic Copybook is perfect for children aged 3 years and above. It's ideal for preschoolers, kindergarten, and primary school students learning to write letters, numbers, and improve their handwriting skills."
        }
      },{
        "@type": "Question",
        "name": "Do you deliver nationwide in Nigeria?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes! We offer FREE delivery to all 36 states in Nigeria including Lagos, Abuja, Port Harcourt, Kano, Ibadan, and everywhere else. We also offer pay on delivery option - you only pay when you receive your order."
        }
      },{
        "@type": "Question",
        "name": "What's included in the copybook package?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Each package includes: 4-in-1 magic copybook (alphabet, numbers, math, and drawing), 10 premium refill pens, pencil grip helper, free nationwide delivery, and multiple free gifts (water game pad, U-shaped toothbrush, skipping rope). Black Friday buyers also get ₦10,000 cashback!"
        }
      },{
        "@type": "Question",
        "name": "How long does delivery take?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Delivery takes 24-48 hours within Lagos and Abuja, and 2-4 days for other states across Nigeria. We work with reliable courier services to ensure safe and timely delivery to your doorstep."
        }
      },{
        "@type": "Question",
        "name": "Can I order as a gift for someone?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Absolutely! The Sank Magic Copybook makes a perfect surprise gift for children, nieces, nephews, cousins, or friends' children for birthdays, Christmas, Children's Day, or any special occasion. Just provide the recipient's delivery address during checkout."
        }
      }]
    }
    </script>
    
    <style>
        /* Load clear, bold sale-friendly fonts for this page */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap');

        :root {
            --primary: #0a7c42;
            --primary-dark: #066633;
            --primary-light: #e8f5e9;
            --secondary: #ff6b6b;
            --accent: #ffd166;
            --dark: #2d3047;
            --light: #f7f9fc;
            --text: #222222;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Page-wide typography: use Inter / Roboto and stronger weights for sale readability */
        body {
            font-family: 'Inter', 'Roboto', Arial, sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            font-weight: 500;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Headings and CTAs stronger on mobile */
        h1, h2, h3, h4, h5, h6 { font-weight: 800; }
        .cta-button, .bf-badge, .md-badge, .price-chip .new-price { font-weight: 800; }

        @media (max-width: 576px) {
            body { font-size: 16px; }
            h1 { font-size: 1.8rem; }
            .cta-button { padding: 14px 18px; font-size: 1rem; }
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
            min-height: 44px; /* Minimum touch target for mobile */
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent; /* Remove tap highlight on mobile */
            touch-action: manipulation; /* Prevent double-tap zoom */
            transition: var(--transition);
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1);
        }
        
        .cta-button:hover {
            background-color: #ffc145;
            transform: translateY(-3px);
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.1);
        }
        
        /* Hero Section */
        .hero {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary-light) 0%, #c8e6c9 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><path d="M20,20 Q40,5 50,30 T80,20" stroke="%230a7c42" fill="none" stroke-width="2"/><path d="M30,50 Q15,65 40,80 T50,50" stroke="%230a7c42" fill="none" stroke-width="2"/><circle cx="70" cy="70" r="5" fill="%230a7c42"/><circle cx="20" cy="80" r="3" fill="%230a7c42"/></svg>');
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            line-height: 1.2;
            color: var(--dark);
            text-shadow: 2px 2px 0 rgba(255, 255, 255, 0.5);
        }

        /* Animated badges for hero */
        .hero-badge { display:inline-block; padding:6px 14px; border-radius:999px; color:white; font-weight:800; margin-right:10px; font-size:0.9rem; }
        .bf-badge { background: linear-gradient(90deg,#ff3b3b,#ff6b6b); animation: float 2.8s ease-in-out infinite, glow 2.5s ease-in-out infinite; }
        .md-badge { background: #000; color: #fff; animation: pop 1.8s ease-in-out infinite; padding:8px 16px; font-size:1.05rem; box-shadow:0 8px 20px rgba(0,0,0,0.2); }

        @keyframes float { 0%{ transform: translateY(0);} 50%{ transform: translateY(-6px);} 100%{ transform: translateY(0);} }
        @keyframes glow { 0%{ box-shadow:0 0 6px rgba(255,59,59,0.12);} 50%{ box-shadow:0 14px 40px rgba(255,59,59,0.14);} 100%{ box-shadow:0 0 6px rgba(255,59,59,0.12);} }
        @keyframes pop { 0%{ transform: scale(1);} 50%{ transform: scale(1.06);} 100%{ transform: scale(1);} }

        /* Hero pricing chips */
        .hero-pricing { display:flex; gap:14px; justify-content:center; margin:18px 0 26px; flex-wrap:wrap; }
        .price-chip { background: white; border-radius:12px; padding:12px 16px; min-width:160px; box-shadow:0 8px 30px rgba(10,124,66,0.06); text-align:center; border:1px solid rgba(10,124,66,0.06); position:relative; cursor:pointer; transition: transform 220ms ease, box-shadow 220ms ease; -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
        .price-chip:hover { transform: translateY(-8px) scale(1.02); box-shadow:0 24px 60px rgba(10,124,66,0.12); }
        .price-chip.featured { transform: translateY(-6px); border:2px solid rgba(10,124,66,0.12); box-shadow:0 18px 48px rgba(10,124,66,0.12); }
        .price-chip .ribbon { position:absolute; left:-18px; top:12px; background:#ff3b3b; color:white; padding:6px 10px; font-weight:800; font-size:0.78rem; transform:rotate(-14deg); box-shadow:0 6px 18px rgba(0,0,0,0.12); border-radius:4px; }
        .chip-title { font-weight:700; color:var(--dark); margin-bottom:6px; }
        .chip-prices { margin-bottom:6px; }
        .old-price { text-decoration:line-through; color:#888; margin-right:8px; }
        .new-price { font-weight:800; color:var(--primary); font-size:1.2rem; }
        .chip-note { font-size:0.85rem; color:#666; }
        /* default perks line used in pricing cards */
        .default-perks { margin-top:8px; font-weight:700; color:var(--primary); font-size:0.95rem; }

        /* Limited Slots Banner */
        .limited-slots-banner {
            background: linear-gradient(135deg, #ff3b3b 0%, #ff6b6b 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(255, 59, 59, 0.4);
            animation: pulse-banner 2s ease-in-out infinite;
            position: relative;
        }

        .pulse-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-banner {
            0%, 100% { transform: scale(1); box-shadow: 0 8px 25px rgba(255, 59, 59, 0.4); }
            50% { transform: scale(1.02); box-shadow: 0 12px 35px rgba(255, 59, 59, 0.6); }
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        /* Stock Badges on Price Chips */
        .stock-badge {
            background: linear-gradient(135deg, #ffd166 0%, #ffb347 100%);
            color: #2d3047;
            font-weight: 800;
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(255, 193, 102, 0.3);
        }

        .stock-badge.hot {
            background: linear-gradient(135deg, #ff3b3b 0%, #ff6b6b 100%);
            color: white;
            animation: shake 0.5s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }

        /* Limited Stock Tag for Pricing Cards */
        .limited-stock-tag {
            background: #ffd166;
            color: #2d3047;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin: -20px -20px 15px -20px;
            border-bottom: 3px solid #ffb347;
        }

        .limited-stock-tag.hot {
            background: linear-gradient(135deg, #ff3b3b 0%, #ff6b6b 100%);
            color: white;
            animation: blink-tag 1.5s ease-in-out infinite;
        }

        @keyframes blink-tag {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Urgency Banner */
        .urgency-banner {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff3b3b 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(255, 59, 59, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .blink-icon {
            display: inline-block;
            animation: blink-icon 1s ease-in-out infinite;
            font-size: 1.3rem;
        }

        @keyframes blink-icon {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.2); }
        }

        .stock-number, .stock-counter {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            padding: 2px 8px;
            border-radius: 5px;
            font-weight: 900;
            font-size: 1.1em;
        }

        /* Notification Toast */
        .stock-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: linear-gradient(135deg, #ff3b3b 0%, #ff6b6b 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(255, 59, 59, 0.5);
            z-index: 9999;
            font-weight: 700;
            max-width: 350px;
            animation: slide-in-right 0.5s ease-out;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes slide-in-right {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .stock-notification.fade-out {
            animation: fade-out 0.5s ease-out forwards;
        }

        @keyframes fade-out {
            to { opacity: 0; transform: translateX(400px); }
        }

        @media (max-width:768px){ 
            .hero h1{font-size:2rem;} 
            .hero-pricing{gap:10px; flex-direction: column; align-items: center;} 
            .price-chip{min-width:280px; max-width: 100%; padding:15px; font-size: 1rem;} 
            .price-chip .chip-title { font-size: 1.1rem; }
            .price-chip .new-price { font-size: 1.8rem; }
            .price-chip .old-price { font-size: 1rem; }
            .limited-slots-banner { font-size: 0.95rem; padding: 10px 15px; }
            .stock-notification { right: 10px; max-width: 90%; font-size: 0.9rem; }
            .stock-badge { padding: 4px 8px; font-size: 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .hero-pricing { gap: 8px; }
            .price-chip { min-width: 260px; padding: 12px; }
            .price-chip .chip-title { font-size: 1rem; }
            .price-chip .new-price { font-size: 1.5rem; }
            .limited-slots-banner { font-size: 0.85rem; padding: 8px 12px; }
        }
        
        .hero h1 span {
            color: var(--primary);
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            color: var(--dark);
        }
        
        .countdown {
            background-color: rgba(10, 124, 66, 0.1);
            border: 2px solid var(--primary);
            border-radius: 15px;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto 40px;
        }
        
        .countdown h3 {
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .timer {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .timer-item {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            min-width: 80px;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1);
        }
        
        .timer-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .timer-label {
            font-size: 0.8rem;
            color: var(--dark);
        }
        
        /* Payment Security Badge */
        .payment-security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 20px 30px;
            max-width: 600px;
            margin: 30px auto;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.2);
        }
        
        .security-icon {
            font-size: 48px;
            color: #0ea5e9;
        }
        
        .security-text {
            text-align: left;
        }
        
        .security-text strong {
            font-size: 18px;
            color: #0c4a6e;
            display: block;
            margin-bottom: 5px;
        }
        
        .security-text p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .payment-security-badge {
                flex-direction: column;
                text-align: center;
                padding: 15px 20px;
            }
            
            .security-icon {
                font-size: 40px;
            }
            
            .security-text {
                text-align: center;
            }
            
            .security-text strong {
                font-size: 16px;
            }
            
            .security-text p {
                font-size: 13px;
            }
        }
        
        /* Free Gifts Section */
        .free-gifts {
            padding: 80px 0;
            background-color: white;
        }
        
        .gifts-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .gift-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            transition: var(--transition);
            border: 2px solid var(--primary-light);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .gift-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gift-image {
            width: 100%;
            height: 200px;
            background-color: var(--primary-light);
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary);
            overflow: hidden;
        }

        /* make images inside gift cards responsive */
        .gift-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        .gift-card h3 {
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .gift-card p {
            color: #666;
        }
        
        /* Video / Content7 Section */
        .video-section {
            padding: 100px 0;
            background-color: var(--primary-light);
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2 {
            font-size: 2rem;
            margin-bottom: 12px;
            color: var(--dark);
        }

        .section-title p {
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Responsive video container and image grid used for content7-1 */
        #content7-1 .video-wrap { max-width: 980px; margin: 0 auto 18px; padding: 8px; border-radius: 14px; background: #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.10); }
        #content7-1 .video-embed { position: relative; padding-top: 56.25%; /* 16:9 ratio */ height: 0; border-radius:8px; overflow: hidden; background:#000; }
        #content7-1 .video-embed iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; display: block; }
        .content-grid { display:flex; flex-wrap:wrap; gap:18px; margin-top:18px; }
        .content-grid figure.img-block { flex: 1 1 calc(50% - 18px); margin:0; border-radius:10px; overflow:hidden; background:white; display:flex; flex-direction:column; }
        .content-grid .img-block img { width:100%; height:100%; object-fit:cover; display:block; }
        .content-grid .img-block figcaption { padding:10px; background: #fff; color:#2b6cb0; font-weight:700; text-align:center; font-size:0.95rem; }

        /* Mobile adjustments for the content7-1 area */
        @media (max-width: 768px) {
            #content7-1 { padding: 40px 0; }
            #content7-1 .section-title h2 { font-size: 1.4rem; }
            #content7-1 iframe { height: 200px; }
            .content-grid .img-block { min-height: 120px; flex-basis: 100%; }
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

        /* Highlight benefits paragraphs with eye-catching styling */
        .gallery-section .section-title p {
            background: linear-gradient(135deg, #ffd166 0%, #ffb347 100%);
            color: #2d3047;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 14px 24px;
            border-radius: 12px;
            margin: 12px auto;
            max-width: 700px;
            box-shadow: 0 4px 15px rgba(255, 193, 102, 0.3);
            border-left: 5px solid #ff6b6b;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .gallery-section .section-title p::before {
            content: '✨';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.3rem;
            animation: sparkle 2s ease-in-out infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 1; transform: translateY(-50%) scale(1); }
            50% { opacity: 0.7; transform: translateY(-50%) scale(1.2); }
        }

        .gallery-section .section-title p:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 102, 0.5);
        }

        @media (max-width: 768px) {
            .gallery-section .section-title p {
                font-size: 1rem;
                padding: 12px 18px 12px 38px;
            }
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

        /* small CTA for gallery items */
        .cta-button.small {
            display: inline-block;
            padding: 8px 12px;
            font-size: 0.9rem;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            margin-top: 8px;
        }
        .cta-button.small:hover { background: var(--primary-dark); }
        
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

        /* Testimonials Section */
        .testimonials {
            padding: 80px 0;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        
        .testimonials .section-title h2 {
            font-size: 2.2rem;
            margin-bottom: 15px;
            color: var(--dark);
            font-weight: 800;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #0ea5e9;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        
        .testimonial-card .rating {
            color: #fbbf24;
            font-size: 18px;
            margin-bottom: 12px;
        }
        
        .testimonial-text {
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }
        
        .author-info h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }
        
        .author-info p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }
        
        .social-proof-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 60px;
            text-align: center;
        }
        
        .proof-stat {
            background: white;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .proof-stat h3 {
            font-size: 2.5rem;
            color: #0a7c42;
            margin: 0 0 8px 0;
            font-weight: 800;
        }
        
        .proof-stat p {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .testimonials {
                padding: 60px 0;
            }
            
            .testimonials .section-title h2 {
                font-size: 1.8rem;
            }
            
            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .testimonial-card {
                padding: 20px;
            }
            
            .social-proof-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-top: 40px;
            }
        }
        
        @media (max-width: 576px) {
            .testimonials {
                padding: 40px 0;
            }
            
            .testimonials .section-title h2 {
                font-size: 1.5rem;
            }
            
            .social-proof-stats {
                grid-template-columns: 1fr;
            }
            
            .proof-stat h3 {
                font-size: 2rem;
            }
        }
        
        /* Products Section */
        .products {
            padding: 100px 0;
            background-color: white;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            height: 200px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 3rem;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-info h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .product-info p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
        }
        
        .discount-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        /* Package Comparison Table */
        .comparison-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }
        
        .comparison-table-wrapper {
            overflow-x: auto;
            margin-top: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .comparison-table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            min-width: 700px;
        }
        
        .comparison-table thead {
            background: linear-gradient(135deg, #0a7c42 0%, #066633 100%);
            color: white;
        }
        
        .comparison-table th {
            padding: 20px 15px;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
        }
        
        .comparison-table .feature-column {
            text-align: left;
            width: 25%;
            background: #065f46;
        }
        
        .comparison-table .package-column {
            width: 25%;
        }
        
        .comparison-table .package-column.popular {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            position: relative;
        }
        
        .popular-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 5px;
        }
        
        .price-mini {
            display: block;
            font-size: 20px;
            font-weight: 800;
            margin-top: 8px;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .comparison-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .comparison-table tbody tr:hover {
            background: #f9fafb;
        }
        
        .comparison-table td {
            padding: 18px 15px;
            text-align: center;
            font-size: 15px;
        }
        
        .comparison-table .feature-name {
            font-weight: 600;
            text-align: left;
            background: #f3f4f6;
            color: #374151;
        }
        
        .comparison-table .starter {
            color: #0c4a6e;
        }
        
        .comparison-table .bundle {
            background: #fffbeb;
            color: #92400e;
        }
        
        .comparison-table .collection {
            color: #065f46;
        }
        
        .text-success {
            color: #10b981;
            font-size: 20px;
        }
        
        .comparison-table .action-row td {
            padding-top: 25px;
            padding-bottom: 25px;
        }
        
        .comparison-table .cta-button.small {
            padding: 12px 24px;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .comparison-section {
                padding: 60px 0;
            }
            
            .comparison-table-wrapper {
                border-radius: 10px;
            }
            
            .comparison-table {
                font-size: 13px;
            }
            
            .comparison-table th {
                padding: 15px 10px;
                font-size: 14px;
            }
            
            .comparison-table td {
                padding: 12px 8px;
                font-size: 13px;
            }
            
            .price-mini {
                font-size: 16px;
            }
        }
        
        /* Pricing Section */
        .pricing {
            padding: 100px 0;
            background-color: var(--primary-light);
        }
        
        .pricing-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .pricing-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            transition: var(--transition);
            border: 2px solid #f0f0f0;
            position: relative;
        }
        
        .pricing-card.featured {
            border: 2px solid var(--primary);
            transform: scale(1.05);
        }
        
        .pricing-card.featured::before {
            content: 'MOST POPULAR';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }
        
        .pricing-card h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .price {
            margin-bottom: 30px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.2rem;
        }
        
        .discount-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .savings {
            background-color: rgba(10, 124, 66, 0.1);
            color: var(--primary);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .features {
            list-style: none;
            margin: 30px 0;
            text-align: left;
        }
        
        .features li {
            margin-bottom: 15px;
            padding-left: 30px;
            position: relative;
        }
        
        .features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }
        
        /* Order Form Section */
        .order-form-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%);
        }
        
        .order-container {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            align-items: flex-start;
        }
        
        .order-info {
            flex: 1;
            min-width: 300px;
        }
        
        .order-info h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .order-info p {
            margin-bottom: 20px;
            color: #555;
        }
        
        .benefits {
            list-style: none;
            margin: 30px 0;
        }
        
        .benefits li {
            margin-bottom: 15px;
            padding-left: 40px;
            position: relative;
        }
        
        .benefits li::before {
            content: '✓';
            position: absolute;
            left: 0;
            width: 30px;
            height: 30px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .form-container {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .trust-badges {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 10px;
            border: 1px solid #bae6fd;
        }
        
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #0c4a6e;
        }
        
        .trust-badge i {
            font-size: 18px;
            color: #0ea5e9;
        }
        
        @media (max-width: 768px) {
            .trust-badges {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                padding: 12px;
            }
            
            .trust-badge {
                font-size: 12px;
            }
            
            .trust-badge i {
                font-size: 16px;
            }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            min-height: 44px; /* Minimum touch target for mobile */
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px; /* Prevents zoom on iOS when focusing */
            transition: var(--transition);
            -webkit-appearance: none; /* Remove default iOS styling */
            appearance: none;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 124, 66, 0.2);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .payment-info {
            background: var(--primary-light);
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .payment-info i {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .submit-button {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            min-height: 50px; /* Larger touch target for important action */
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(10, 124, 66, 0.4);
        }
        
        /* Referral Section */
        .referral {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark) 0%, #3a3e5c 100%);
            color: white;
        }
        
        .referral-content {
            display: flex;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
        }
        
        .referral-text {
            flex: 1;
            min-width: 300px;
        }
        
        .referral-text h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .referral-text h2 span {
            color: var(--accent);
        }
        
        .referral-text p {
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .referral-steps {
            flex: 1;
            min-width: 300px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
        }
        
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        
        .step-number {
            background-color: var(--accent);
            color: var(--dark);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .step-content h4 {
            margin-bottom: 5px;
            color: white;
        }
        
        .step-content p {
            color: #ccc;
            font-size: 0.9rem;
        }
        
        /* WhatsApp Section */
        .whatsapp-section {
            padding: 80px 0;
            background-color: white;
            text-align: center;
        }
        
        .whatsapp-container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border-radius: 15px;
            padding: 40px;
            color: white;
        }
        
        .whatsapp-container h2 {
            margin-bottom: 20px;
        }
        
        .whatsapp-numbers {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin: 30px 0;
        }
        
        .whatsapp-number {
            background: white;
            color: #25D366;
            padding: 15px 25px;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .whatsapp-number:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
        @media (max-width: 992px) {
            .container { width: 94%; padding: 0 16px; }
            .hero { padding: 60px 0; }
            .hero h1 { font-size: 2.25rem; }
            .section-title h2 { font-size: 2rem; }
            .gallery-image { height: 200px; }
            .product-image { height: 180px; }
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 1.9rem; line-height: 1.2; }
            .hero h2 { font-size: 1.3rem; line-height: 1.4; }
            .hero p { font-size: 1rem; padding: 0 10px; }
            .nav-links { display: none; }
            .header-content { flex-wrap: wrap; gap: 10px; }
            .cta-button { width: 100%; text-align: center; padding: 15px 20px; font-size: 1.1rem; }
            .gifts-container { flex-direction: column; align-items: center; }
            .gallery-container { grid-template-columns: 1fr; }
            .timer { flex-direction: row; flex-wrap: wrap; gap: 10px; justify-content: center; }
            .timer-item { min-width: 70px; padding: 10px; }
            .timer-number { font-size: 1.5rem; }
            .order-container { flex-direction: column; }
            .form-row { flex-direction: column; gap: 0; }
            .section-title h2 { font-size: 1.8rem; }
            .section-title p { font-size: 1rem; }
            /* Mobile-friendly notice banner */
            .form-container > div[style*="gradient"] { padding: 12px; font-size: 0.95rem; }
            .form-container > div[style*="gradient"] h4 { font-size: 1rem; }
            .form-container > div[style*="gradient"] p { font-size: 0.9rem; margin: 6px 0; }
        }

        @media (max-width: 480px) {
            /* reduce vertical padding on small phones */
            .hero h1 { font-size: 1.5rem; }
            .hero h2 { font-size: 1.1rem; }
            .hero p { font-size: 0.95rem; }
            #content7-1 .video-wrap { padding: 6px; }
            .content-grid .img-block figcaption { font-size: 0.9rem; }
            .gift-image { height: 140px; }
            .cta-button { padding: 12px 16px; font-size: 1rem; }
            .timer-item { min-width: 60px; padding: 8px; }
            .timer-number { font-size: 1.3rem; }
            .timer-label { font-size: 0.7rem; }
            .form-container > div[style*="gradient"] { padding: 10px; }
            .form-container > div[style*="gradient"] h4 { font-size: 0.95rem; }
            .form-container > div[style*="gradient"] p { font-size: 0.85rem; }
            #order-toast-container { right: 10px; bottom: 10px; max-width: 90%; }
        }

        /* Demo order toast notifications */
        #order-toast-container {
            position: fixed;
            right: 20px;
            bottom: 24px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
            max-width: 320px;
        }

        .order-toast {
            background: rgba(255,255,255,0.98);
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            display: flex;
            gap: 10px;
            padding: 10px;
            align-items: center;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 420ms cubic-bezier(.22,.9,.25,1), opacity 300ms ease;
            pointer-events: auto;
        }

        .order-toast.show { transform: translateX(0); opacity: 1; }

        .order-toast img { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; flex-shrink:0; }

        .order-toast .ot-body { font-size: 0.92rem; color: #333; }
        .order-toast .ot-body strong { display:block; font-weight:700; color:var(--dark); }
        .order-toast .ot-meta { font-size:0.8rem; color:#666; margin-top:4px; }
        .order-toast .ot-pkg { font-size:0.85rem; color:var(--primary); font-weight:700; margin-top:6px; }

        @media (max-width:480px){
            #order-toast-container { left: 12px; right: 12px; bottom: 12px; max-width:calc(100% - 24px); }
            .order-toast img { width:48px; height:48px; }
        }

        /* Fireworks canvas styling */
        .fireworks-canvas {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 500;
        }

        /* ensure hero is positioning context for the fireworks canvas */
        .hero { position: relative; overflow: visible; }

        /* Payment success overlay */
        .payment-success-overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
            z-index: 3000;
        }

        .payment-success-card {
            background: #fff;
            padding: 28px 28px 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            max-width: 520px;
            width: 90%;
            transform: translateY(8px);
        }

        .payment-success-card h2 { margin: 6px 0 4px; color: #0a7c42; }
        .payment-success-card p { color: #444; margin: 0 0 12px; }
        .success-check {
            font-size: 56px;
            width: 84px;
            height: 84px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(135deg,#2dd4bf,#06b6d4);
            color: white;
            box-shadow: 0 8px 20px rgba(6,182,212,0.25);
            margin-bottom: 8px;
        }
    </style>
    <!-- Meta Pixel Code (copied from index.php) -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq) return; n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq) f._fbq=n; n.push=n; n.loaded=!0; n.version='2.0'; n.queue=[]; t=b.createElement(e); t.async=!0; t.src=v; s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1478749616293388');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1478749616293388&ev=PageView&noscript=1"/></noscript>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-54PRKH52XY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);} 
        gtag('js', new Date());
        gtag('config', 'G-54PRKH52XY');
    </script>
    <style>
        /* Testimonial cards layout and basic look */
        .testimonial-cards{display:flex;flex-wrap:wrap;gap:18px;margin-top:18px}
        .testimonial-card{flex:1 1 calc(25% - 18px);background:#fff;border:1px solid #ececec;border-radius:10px;padding:16px;box-shadow:0 6px 18px rgba(15,23,42,0.06);color:#111;min-width:220px}
        .testimonial-text{font-size:15px;line-height:1.45;color:#263238}
        .testimonial-author{display:flex;align-items:center;gap:12px;margin-top:12px}
        .author-avatar{width:44px;height:44px;border-radius:50%;background:#147bf1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
        .author-info h4{margin:0;font-size:15px}
        .author-info p{margin:0;font-size:13px;color:#555}

        /* WhatsApp and Facebook snippet accents */
        .testimonial-card.whatsapp{background:#f6ffed;border-color:#e6fbb7}
        .testimonial-card.fb{background:#f0f2f5;border-color:#d8dde3}
        .testimonial-card a{color:#147bf1;text-decoration:underline}

        /* Rotating hero phrases (large caption style) */
        .rotating-phrases{margin:18px 0 22px;text-align:center}
        .rotating-phrases #rotatingWord{display:inline-block;padding:6px 18px;border-radius:10px;background:linear-gradient(90deg,#ffffff,#f8fafc);box-shadow:0 12px 36px rgba(11,54,83,0.08);font-weight:800;color:#0b3653;font-size:32px;line-height:1.05;letter-spacing:0.2px}
        .rotating-phrases.fade{opacity:0;transition:opacity 320ms ease}
        @media (max-width:900px){.rotating-phrases #rotatingWord{font-size:26px;padding:6px 14px}}
        @media (max-width:520px){.rotating-phrases #rotatingWord{font-size:20px;padding:6px 12px}}

        /* Responsive adjustments */
        @media (max-width:900px){.testimonial-card{flex:1 1 calc(50% - 18px)}}
        @media (max-width:520px){.testimonial-card{flex:1 1 100%}}
    </style>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1384965173173073');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1384965173173073&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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
                        <div class="logo-text">Smartkids Edu</div>
                </div>
                <nav class="nav-links">
                    <a href="#products">Products</a>
                    <a href="#pricing">Pricing</a>
                    <a href="#testimonials">Reviews</a>
                    <a href="#order">Order Now</a>
                </nav>
                <button class="cta-button" data-scroll-to="#order">Buy Now</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="limited-slots-banner">
                <span class="pulse-dot"></span>
                🔥 BLACK FRIDAY SPECIAL 🔥 Limited Slots Available! Only <span id="hero-stock" class="stock-number">7</span> Sets Remaining!
            </div>
            <h1>⏳🎯 BLACK FRIDAY MEGA SALE 🎯⏳<br>Sank Magic Reusable Copybook for Kids - Best Handwriting Practice Book Nigeria 🔥💥
            </h1>
            <h2>Order now to get cashback of <b>💰₦10,000💰</b> + free gifts worth up to <b>🎁₦12,000🎁</b>!</h2>
            <p style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); padding: 12px 16px; border-radius: 10px; border-left: 4px solid #0ea5e9; margin: 15px 0; font-weight: 500;">🎉 <strong>Perfect Surprise Gift!</strong> Buy this amazing copybook set for your children, cousins, nieces, nephews, friends' children, or any special kid in your life! 🎁✨</p> 
            <!-- <p>&#128293;&#128293;Order Now & Get High-Value Free Gifts! of <b>&#8358;12,000</b> and above..&#128293;&#128293;</p> -->
            <p>Perfect for kids <strong>3 years and above</strong> — compact, easy-to-follow activities that build pen control, confidence and neat handwriting fast.</p>
            <p><b>This Amazing 4 in 1 Sank magic practice copybook will improve child’s learning while having Fun
                                    just in weeks of practice.</b> </p>

            <!-- Rotating benefit phrases -->
            <div class="rotating-phrases" aria-live="polite">
                <span id="rotatingWord">Reusable sank magic copybook</span>
            </div>

            <div class="hero-pricing" aria-hidden="false">
                <div class="price-chip" data-package="Starter">
                    <div class="stock-badge">⚡ Only <span class="stock-counter" data-stock="starter">9</span> Left!</div>
                    <div class="chip-title">Starter Set</div>
                    <div class="chip-prices"><span class="old-price"><?php echo formatPrice($prices['starter']['original']); ?></span><span class="new-price"><?php echo formatPrice($prices['starter']['price']); ?></span></div>
                    <div class="chip-note">4-in-1 Book</div>
                    <div class="default-perks">Pay on Delivery • Free delivery • 3 Free Gifts</div>
                </div>

                <div class="price-chip featured" data-package="Bundle">
                    <span class="ribbon">-<?php echo $prices['bundle']['discount']; ?>% OFF</span>
                    <div class="stock-badge hot">🔥 ONLY <span class="stock-counter" data-stock="bundle">5</span> LEFT!</div>
                    <div class="chip-title">Learning Bundle</div>
                    <div class="chip-prices"><span class="old-price"><?php echo formatPrice($prices['bundle']['original']); ?></span><span class="new-price"><?php echo formatPrice($prices['bundle']['price']); ?></span></div>
                    <div class="chip-note">2 Sets (4-in-1 Book)</div>
                    <div class="default-perks">Pay on Delivery • Free delivery • 6 Free Gifts</div>
                </div>

                <div class="price-chip" data-package="Collection">
                    <div class="stock-badge">⚡ Only <span class="stock-counter" data-stock="collection">8</span> Left!</div>
                    <div class="chip-title">Mastery Collection</div>
                    <div class="chip-prices"><span class="old-price"><?php echo formatPrice($prices['collection']['original']); ?></span><span class="new-price"><?php echo formatPrice($prices['collection']['price']); ?></span></div>
                    <div class="chip-note">3 Sets (4-in-1 Book)</div>
                    <div class="default-perks">Pay on Delivery • Free delivery • 9 Free Gifts</div>
                </div>
            </div>
            <!-- Fireworks canvas (decorative around Black Friday promotion) -->
            <canvas id="fireworks-canvas" class="fireworks-canvas" aria-hidden="true"></canvas>
            
            <div class="countdown">
                <h3>HURRY! OFFER ENDS IN:</h3>
                <div class="timer">
                    <div class="timer-item">
                        <div class="timer-number" id="days">10</div>
                        <div class="timer-label">DAYS</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-number" id="hours">23</div>
                        <div class="timer-label">HOURS</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-number" id="minutes">59</div>
                        <div class="timer-label">MINUTES</div>
                    </div>
                    <div class="timer-item">
                        <div class="timer-number" id="seconds">59</div>
                        <div class="timer-label">SECONDS</div>
                    </div>
                </div>
            </div>
            
            <!-- Pay on Delivery Badge -->
            <div class="payment-security-badge">
                <div class="security-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div class="security-text">
                    <strong>100% Safe - Pay When You Receive</strong>
                    <p>Free Delivery • No Upfront Payment • Money-Back Guarantee</p>
                </div>
            </div>
            
            <button class="cta-button" data-scroll-to="#order">Claim Your Discount Now</button>
        </div>
    </section>

  
 <!-- Content7-1: Video + Image Grid (combined, with H2 header) -->
    <section id="content7-1" class="video-section">
        <div class="container">
            <div class="section-title">
                <h2>Watch & See How Sank Magic Copybooks Work</h2>
                <p>Short demo showing how the grooves guide young learners to form letters and numbers — then reuse the pages again and again.</p>
            </div>

            <div class="video-wrap">
                <div class="video-embed">
                    <iframe src="https://www.youtube.com/embed/UXHP3WxupBQ" title="Magic Calligraphy Book for Kids" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>

            <div style="text-align:center;margin-top:14px;margin-bottom:8px;">
                <h2 style="color:#147bf1;"><b>PLEASE WATCH THE VIDEO ABOVE</b></h2>
            </div>

            <div class="content-grid">
                <figure class="img-block">
                    <img src="images/slider1.jpg" alt="Magic copybook demo" loading="lazy">
                    <figcaption>Fast Results in 1-3weeks, improves handwriting and much more</figcaption>
                </figure>

                <figure class="img-block">
                    <img src="images/sliddd.jpg" alt="Practice activities" loading="lazy">
                    <figcaption>Disappears after 5mins of writing for Easy Reuse</figcaption>
                </figure>

                <figure class="img-block">
                    <img src="images/slidde.jpg" alt="Reusable pages" loading="lazy">
                    <figcaption>4 in 1 Magic Copybook with extra Refill Pen</figcaption>
                </figure>

                <figure class="img-block">
                    <img src="images/magic-book-3-1024x1024.jpg" alt="Durable copybook" loading="lazy">
                    <figcaption>Durable, kid-friendly materials</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="section-title">
                <h2>Benefits of This magic Reusable practice book</h2>
                <p>Practice Makes Perfect: Reusable Copybook for Growing Minds</p>
                <p>Sank Magic: Make Learning Letters & Numbers Disappear (Like Magic!).</p>
                <p>A Magical Start: Develop Fine Motor Skills & Confidence with Sank Magic.</p>
                
            </div>
            
            <div class="gallery-container">
                <!-- Replaced gallery items with images and write-ups from index.php #blog2-1 -->
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/number_magic.jpg" alt="Number Magic" data-index="0" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">This magic practice copy book lets kids practice writing letters and numbers that they often struggle with in school right at home in their own space.</p>
                        <p><a class="cta-button small" href="#order" data-package="Starter">Order Starter Set</a></p>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/letter_magic.jpg" alt="Letter Magic" data-index="1" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">The books are designed with grooved Letters and Numbers that guides your child’s hand resulting in improved muscle memory and beautiful handwriting.</p>
                        <p><a class="cta-button small" href="#order" data-package="Bundle">Order Learning Bundle</a></p>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/magic_sank.jpg" alt="Magic Sank" data-index="2" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">Kids will love this fun way to develop their skills, while parents can breathe a sigh of relief knowing they are developing important life skills without any mess or fuss.</p>
                        <p><a class="cta-button small" href="#order" data-package="Collection">Order Mastery Collection</a></p>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/maths_magic.jpg" alt="Maths Magic" data-index="3" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">The special magic pen’s ink gradually fades over time, disappearing within minutes of writing. This allows the books to be used over and over without the need for extra paper and new books every time your child would like to start again.</p>
                        <p><a class="cta-button small" href="#order" data-package="Starter">Order Starter Set</a></p>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/paint_magi.jpg" alt="Paint Magic" data-index="4" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">🎁 <strong>Perfect Surprise Gift!</strong> Buy this as an amazing gift for your children, cousins, nieces, nephews, friends' children, or any special kid in your life on occasions such as Birthday, Children's Day, Christmas, or any special celebration!</p>
                        <p><a class="cta-button small" href="#order" data-package="Bundle">Order Learning Bundle</a></p>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="images/1.jpg" alt="Durable Paper" data-index="5" class="gallery-thumb" loading="lazy" />
                    </div>
                    <div class="gallery-info">
                        <p class="lead">The paper of this practice copybook is also durable because it is made to be thicker than normal. Thus, it does not easily smudge, squeeze or tear. It will last for every child.</p>
                        <p><a class="cta-button small" href="#order" data-package="Collection">Order Mastery Collection</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

      <!-- Free Gifts Section -->
    <section class="free-gifts">
        <div class="container">
            <div class="section-title">
                <h2>Our Free  Gifts With Every Order! and It's Benfits</h2>
                <p>Order any copybook package during our Black Friday sale and receive these amazing free gifts</p>

            </div>
            
            <div class="gifts-container">
                <div class="gift-card">
                    <div class="gift-image">
                            <img src="images/4322-1.jpg" alt="Water Game Pad Toy" loading="lazy" />
                        </div>
                    <h3>Water Game Pad Toy</h3>
                    <p>Reusable water gaming pad for endless creative fun without the mess! Encourages hand-eye coordination, concentration, and problem-solving skills.</p>
                </div>
                
                <div class="gift-card">
                    <div class="gift-image">
                        <img src="images/u brush.jpg" alt="U-Shaped Toothbrush" loading="lazy" />
                    </div>
                    <h3>U-Shaped Toothbrush</h3>
                    <p>Make brushing fun with our kid-friendly U-shaped toothbrush design!</p>
                    <p>it can also help prevent cavities, tooth decay, and gum disease, and instill good oral hygiene habits in your child.</p>
                    <p>These 360-degree u shape toothbrushes are special designed for kids, the bristle is soft and hygienic for massaging toddlers’ gum and sensitive teeth.</p>
                    <p>Easy to Use: Apply an appropriate amount of Children's Foam Toothpaste on both sides of the toothbrush, and then brush the teeth with repeated swings from side to side.</P>
                    <p></p>
                </div>
                
                <div class="gift-card">
                          <div class="gift-image">
                              <img src="images/skipp_rope.jpg" alt="Adjustable Skipping Rope" loading="lazy" />
                          </div>
                    <h3>Adjustable Skipping Rope</h3>
                    <p>Full-body workout for kids! Builds muscle strength, endurance, and coordination in a fun and engaging way.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
                <span class="lightbox-close" id="lightbox-close" role="button" aria-label="Close gallery" tabindex="0">&times;</span>
                <img id="lightbox-image" src="" alt="">
                <div class="lightbox-nav">
                    <button id="lightbox-prev" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
                    <button id="lightbox-next" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
    </div>

    <!-- Demo Order Toasts Container -->
    <div id="order-toast-container" aria-live="polite" aria-atomic="true"></div>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>See What Other Smart Parents Are Saying About This Amazing Product!</h2>
                <p>Real reviews from happy customers who have transformed their children's handwriting</p>
                <p style="color: #0a7c42; font-weight: 600; margin-top: 10px;">💡 Tip: Click any review image to view in full screen for easier reading!</p>
            </div>
            <style>
                .testimonial-item:hover {
                    transform: scale(1.03);
                }
                @media (max-width: 768px) {
                    .testimonials-grid {
                        grid-template-columns: 1fr !important;
                    }
                }
            </style>
            
            <div class="testimonials-grid" style="grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 30px;">
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/amazon-testimonial1.png" alt="Customer testimonial" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/Amazon-review-sank-3-728x267.png" alt="Customer review" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/amazon-review-sank-2-728x229.png" alt="Amazon review" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/1testimo.JPG" alt="Parent testimonial" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/2testimo.JPG" alt="Teacher review" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/3testimo.JPG" alt="Customer feedback" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
                
                <div class="testimonial-item" style="cursor: pointer; transition: transform 0.3s ease;" onclick="this.querySelector('img').requestFullscreen ? this.querySelector('img').requestFullscreen() : alert('Click and hold to view full size')">
                    <img src="images/review_1.JPG" alt="Product review" loading="lazy" style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" />
                </div>
            </div>
        </div>
    </section>

    <!-- Package Comparison Table Section -->
    <section class="comparison-section" id="compare">
        <div class="container">
            <div class="section-title">
                <h2>📊 Compare Packages - Find Your Perfect Match</h2>
                <p>Not sure which package is right for you? Compare features side-by-side!</p>
            </div>

            <div class="comparison-table-wrapper">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="feature-column">Features</th>
                            <th class="package-column starter">Starter Set<br><span class="price-mini"><?php echo formatPrice($prices['starter']['price']); ?></span></th>
                            <th class="package-column bundle popular">Learning Bundle<br><span class="price-mini"><?php echo formatPrice($prices['bundle']['price']); ?></span><br><span class="popular-badge">MOST POPULAR</span></th>
                            <th class="package-column collection">Mastery Collection<br><span class="price-mini"><?php echo formatPrice($prices['collection']['price']); ?></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Number of Copybooks</td>
                            <td class="starter">1 Set (4-in-1 Book)</td>
                            <td class="bundle">2 Sets (4-in-1 Books)</td>
                            <td class="collection">3 Sets (4-in-1 Books)</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Magic Pens Included</td>
                            <td class="starter">5 Premium Refill Pens</td>
                            <td class="bundle">10 Premium Refill Pens</td>
                            <td class="collection">15 Premium Refill Pens</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Free Gifts</td>
                            <td class="starter">3 Gifts</td>
                            <td class="bundle">6 Gifts</td>
                            <td class="collection">9 Gifts</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Pencil Grip Helper</td>
                            <td class="starter"><i class="fas fa-check text-success"></i></td>
                            <td class="bundle"><i class="fas fa-check text-success"></i></td>
                            <td class="collection"><i class="fas fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Free Delivery</td>
                            <td class="starter"><i class="fas fa-check text-success"></i></td>
                            <td class="bundle"><i class="fas fa-check text-success"></i></td>
                            <td class="collection"><i class="fas fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Pay on Delivery</td>
                            <td class="starter"><i class="fas fa-check text-success"></i></td>
                            <td class="bundle"><i class="fas fa-check text-success"></i></td>
                            <td class="collection"><i class="fas fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Money-Back Guarantee</td>
                            <td class="starter"><i class="fas fa-check text-success"></i></td>
                            <td class="bundle"><i class="fas fa-check text-success"></i></td>
                            <td class="collection"><i class="fas fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Total Discount</td>
                            <td class="starter"><?php echo $prices['starter']['discount']; ?>% OFF</td>
                            <td class="bundle"><?php echo $prices['bundle']['discount']; ?>% OFF</td>
                            <td class="collection"><?php echo $prices['collection']['discount']; ?>% OFF</td>
                        </tr>
                        <tr>
                            <td class="feature-name">You Save</td>
                            <td class="starter"><?php echo formatPrice($prices['starter']['savings']); ?></td>
                            <td class="bundle"><?php echo formatPrice($prices['bundle']['savings']); ?></td>
                            <td class="collection"><?php echo formatPrice($prices['collection']['savings']); ?></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Best For</td>
                            <td class="starter">Single child<br>First-time buyers</td>
                            <td class="bundle">Multiple kids<br>Best value</td>
                            <td class="collection">3+ children<br>Maximum savings</td>
                        </tr>
                        <tr class="action-row">
                            <td class="feature-name"></td>
                            <td class="starter"><button class="cta-button small" data-scroll-to="#order">Order Now</button></td>
                            <td class="bundle"><button class="cta-button small" data-scroll-to="#order">Order Now</button></td>
                            <td class="collection"><button class="cta-button small" data-scroll-to="#order">Order Now</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Incredible Black Friday Pricing</h2>
                <p>Choose the perfect package for your child's handwriting journey. All sets include progressive exercises.</p>
            </div>
            
            <div class="pricing-cards">
                <!-- 1 Set Package -->
                <div class="pricing-card">
                    <div class="limited-stock-tag">📦 <span class="stock-counter" data-stock="starter">9</span> Sets Remaining</div>
                    <h3>Starter Set</h3>
                    <div class="price">
                        <div class="original-price" style="text-decoration: line-through; color: #999; font-size: 1.2rem;"><?php echo formatPrice($prices['starter']['original']); ?></div>
                        <div class="discount-price" style="font-size: 2rem; color: #0a7c42; font-weight: bold; margin: 5px 0;"><?php echo formatPrice($prices['starter']['price']); ?></div>
                        <div class="savings" style="background: #ff3b3b; color: white; padding: 5px 10px; border-radius: 5px; display: inline-block; font-weight: 600;">Save <?php echo formatPrice($prices['starter']['savings']); ?> (<?php echo $prices['starter']['discount']; ?>% OFF)</div>
                    </div>
                    <ul class="features">
                        <li>1 Comprehensive Copybook</li>
                        <li>Letters A-Z (uppercase & lowercase)</li>
                        <li>Numbers 1-20</li>
                        <li>Basic words and sentences</li>
                        <li>Fun practice activities</li>
                        <li>Free pencil grip helper</li>
                        <li>10 Premium Refill Pens</li>
                        <li>+ 3 Free Gifts</li>
                    </ul>
                    <button class="cta-button" data-scroll-to="#order">Buy Now</button>
                </div>
                
                <!-- 2 Sets Package -->
                <div class="pricing-card featured">
                    <div class="limited-stock-tag hot">🔥 HURRY! Only <span class="stock-counter" data-stock="bundle">5</span> Left!</div>
                    <h3>Learning Bundle</h3>
                    <div class="price">
                        <div class="original-price" style="text-decoration: line-through; color: #999; font-size: 1.2rem;"><?php echo formatPrice($prices['bundle']['original']); ?></div>
                        <div class="discount-price" style="font-size: 2rem; color: #0a7c42; font-weight: bold; margin: 5px 0;"><?php echo formatPrice($prices['bundle']['price']); ?></div>
                        <div class="savings" style="background: #ff3b3b; color: white; padding: 5px 10px; border-radius: 5px; display: inline-block; font-weight: 600;">Save <?php echo formatPrice($prices['bundle']['savings']); ?> (<?php echo $prices['bundle']['discount']; ?>% OFF)</div>
                    </div>
                    <ul class="features">
                        <li>2 Progressive Copybooks</li>
                        <li>Book 1: Letters & Numbers</li>
                        <li>Book 2: Words & Sentences</li>
                        <li>10 Premium Refill Pens</li>
                        <li>Free pencil grip helper</li>
                        <li>+ 6 Free Gifts</li>
                    </ul>
                    <button class="cta-button" data-scroll-to="#order">Buy Now</button>
                </div>
                
                <!-- 3 Sets Package -->
                <div class="pricing-card">
                    <div class="limited-stock-tag">📦 <span class="stock-counter" data-stock="collection">8</span> Sets Remaining</div>
                    <h3>Mastery Collection</h3>
                    <div class="price">
                        <div class="original-price" style="text-decoration: line-through; color: #999; font-size: 1.2rem;"><?php echo formatPrice($prices['collection']['original']); ?></div>
                        <div class="discount-price" style="font-size: 2rem; color: #0a7c42; font-weight: bold; margin: 5px 0;"><?php echo formatPrice($prices['collection']['price']); ?></div>
                        <div class="savings" style="background: #ff3b3b; color: white; padding: 5px 10px; border-radius: 5px; display: inline-block; font-weight: 600;">Save <?php echo formatPrice($prices['collection']['savings']); ?> (<?php echo $prices['collection']['discount']; ?>% OFF)</div>
                    </div>
                    <ul class="features">
                        <li>3 Comprehensive Copybooks</li>
                        <li>Book 1: Foundation</li>
                        <li>Book 2: Development</li>
                        <li>Book 3: Mastery</li>
                        <li>Free pencil grip helper</li>
                        <li>10 Premium Refill Pens</li>
                        <li>+ 9 Free Gifts</li>
                    </ul>
                    <button class="cta-button" data-scroll-to="#order">Buy Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Form Section -->
    <section class="order-form-section" id="order">
        <div class="container">
            <div class="order-container">
                <div class="order-info">
                    <div class="urgency-banner">
                        <span class="blink-icon">⚠️</span> <strong>LIMITED TIME OFFER!</strong> Only <span id="total-stock" class="stock-number">22</span> sets left at this price!
                    </div>
                    <h2>🎯 Order Your Copybooks Today! 🔥</h2>
                    <p>Take advantage of our Black Friday sale and give your child the gift of beautiful handwriting. <strong style="color:#ff3b3b;">⏰ Limited stock available at these prices - Act now before they're gone!</strong></p>
                    
                    <ul class="benefits">
                        <li>Developed by handwriting experts</li>
                        <li>Progressive learning approach</li>
                        <li>Fun, engaging activities</li>
                        <li>High-quality, durable paper</li>
                        <li>Age-appropriate content</li>
                        <li>Money-back guarantee</li>
                        <li>3 Free Gifts with every order</li>
                        <li><strong>FREE DELIVERY</strong> to all Nigeria states</li>
                    </ul>
                    
                    <div class="countdown" style="max-width: 100%;">
                        <h3>OFFER EXPIRES IN:</h3>
                        <div class="timer">
                            <div class="timer-item">
                                <div class="timer-number" id="order-days">10</div>
                                <div class="timer-label">DAYS</div>
                            </div>
                            <div class="timer-item">
                                <div class="timer-number" id="order-hours">23</div>
                                <div class="timer-label">HOURS</div>
                            </div>
                            <div class="timer-item">
                                <div class="timer-number" id="order-minutes">59</div>
                                <div class="timer-label">MINUTES</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-container">
                    <h3>Place Your Order</h3>
                    
                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>100% Safe</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-lock"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-undo"></i>
                            <span>Money-Back Guarantee</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>Verified Seller</span>
                        </div>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); padding: 16px; border-radius: 10px; border: 2px solid #10b981; margin-bottom: 20px;">
                        <h4 style="color: #059669; margin: 0 0 12px 0; font-size: 1.1rem;">✅ READY TO ORDER? GREAT! HERE'S WHAT TO EXPECT:</h4>
                        <p style="margin: 8px 0; color: #065f46; font-weight: 500;">📦 <strong>Fast Delivery:</strong> Your order will arrive within <strong>24-48 hours</strong> after confirmation!</p>
                        <p style="margin: 8px 0; color: #065f46; font-weight: 500;">💵 <strong>Pay on Delivery:</strong> Have your payment ready when our delivery agent arrives at your doorstep.</p>
                        <p style="margin: 8px 0; color: #065f46; font-weight: 500;">📍 <strong>Be Available:</strong> Please ensure someone is available to receive the delivery during this period.</p>
                        <p style="margin: 12px 0 8px 0; color: #0a7c42; font-weight: 600; background: white; padding: 10px; border-radius: 6px;">⏰ <strong>BLACK FRIDAY SPECIAL:</strong> Lock in your 💰₦10,000💰 cashback and discounted prices (₦18,000/₦32,000/₦45,000) by ordering NOW! After promo ends, prices return to ₦22,500+</p>
                        <p style="margin: 8px 0; color: #065f46; font-weight: 500; font-size: 0.95rem;">💬 <strong>Not Ready Yet?</strong> Save our WhatsApp numbers (09029026782 or 08102609396) or bookmark this page to order when you're ready!</p>
                    </div>
                    <p style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #0a7c42; margin-bottom: 15px;">📍 <strong>Delivery Information:</strong> Please provide an accurate mobile phone number and precise delivery address. Use landmarks (church, bank, eatery, etc.) to help our delivery agent locate you easily.</p>
                    <div id="formMessage" style="display:none;margin-bottom:12px;padding:12px;border-radius:8px;"></div>
                    <form id="orderForm" method="post" action="php/order.php">
                        <div class="form-group">
                            <label for="package">Select Package</label>
                            <select class="form-control" id="package" name="pack" required>
                                <option value="">Choose a package</option>
                                <option value="Starter">Starter 1Set(4 in 1 Book) - <?php echo formatPrice($prices['starter']['price']); ?></option>
                                <option value="Bundle">Learning Bundle 2Sets(4 in 1 Book) - <?php echo formatPrice($prices['bundle']['price']); ?></option>
                                <option value="Collection">Mastery Collection 3Sets(4 in 1 Book) - <?php echo formatPrice($prices['collection']['price']); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                    
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="altphone">Alternate Phone (Optional)</label>
                            <input type="tel" name="altphone" class="form-control" id="altphone" placeholder="Alternate phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="state">State</label>
                            <select class="form-control" name="state" id="state" required>
                                <option value="">Select your state</option>
                                <option value="Abia">Abia</option>
                                <option value="Adamawa">Adamawa</option>
                                <option value="Akwa Ibom">Akwa Ibom</option>
                                <option value="Anambra">Anambra</option>
                                <option value="Bauchi">Bauchi</option>
                                <option value="Bayelsa">Bayelsa</option>
                                <option value="Benue">Benue</option>
                                <option value="Borno">Borno</option>
                                <option value="Cross River">Cross River</option>
                                <option value="Delta">Delta</option>
                                <option value="Ebonyi">Ebonyi</option>
                                <option value="Edo">Edo</option>
                                <option value="Ekiti">Ekiti</option>
                                <option value="Enugu">Enugu</option>
                                <option value="FCT">Federal Capital Territory</option>
                                <option value="Gombe">Gombe</option>
                                <option value="Imo">Imo</option>
                                <option value="Jigawa">Jigawa</option>
                                <option value="Kaduna">Kaduna</option>
                                <option value="Kano">Kano</option>
                                <option value="Katsina">Katsina</option>
                                <option value="Kebbi">Kebbi</option>
                                <option value="Kogi">Kogi</option>
                                <option value="Kwara">Kwara</option>
                                <option value="Lagos">Lagos</option>
                                <option value="Nasarawa">Nasarawa</option>
                                <option value="Niger">Niger</option>
                                <option value="Ogun">Ogun</option>
                                <option value="Ondo">Ondo</option>
                                <option value="Osun">Osun</option>
                                <option value="Oyo">Oyo</option>
                                <option value="Plateau">Plateau</option>
                                <option value="Rivers">Rivers</option>
                                <option value="Sokoto">Sokoto</option>
                                <option value="Taraba">Taraba</option>
                                <option value="Yobe">Yobe</option>
                                <option value="Zamfara">Zamfara</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Delivery Address (Optional)</label>
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter your delivery address"></textarea>
                        </div>
                        
                        <!-- Hidden field for tracking traffic source -->
                        <input type="hidden" name="source" value="facebook-marketplace">
                        
                        <div class="payment-info">
                            <i class="fas fa-truck"></i>
                            <h3>Pay on Delivery</h3>
                            <p>Free delivery to all Nigeria states</p>
                            <p>Pay when your order arrives</p>
                        </div>
                        
                        <button type="submit" class="submit-button">Complete Order</button>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="referralCode" style="font-size: 14px; color: #6b7280;">Have a referral code? (Optional)</label>
                            <input type="text" name="referral" class="form-control" id="referralCode" placeholder="Enter referral code if you have one">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>⭐ What Parents Are Saying About Sank Magic Copybook ⭐</h2>
                <p>Join thousands of happy Nigerian parents who have transformed their children's handwriting!</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"My 5-year-old son's handwriting improved dramatically in just 2 weeks! The grooved letters really help him form shapes correctly. Best educational investment I've made. Highly recommend!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">AM</div>
                        <div class="author-info">
                            <h4>Amaka Okonkwo</h4>
                            <p>Lagos, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Received my order in 2 days! My twins love practicing with these books. The ink disappears so they can practice over and over. Worth every naira. Delivery was fast and professional."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">CT</div>
                        <div class="author-info">
                            <h4>Chioma Tunde</h4>
                            <p>Abuja, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"I bought the bundle for my niece and nephew as birthday gifts. They were so excited! The books are durable and high quality. Even got free gifts which was a pleasant surprise!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">OB</div>
                        <div class="author-info">
                            <h4>Oluwaseun Bello</h4>
                            <p>Ibadan, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"My daughter struggled with handwriting for months. After using this copybook for 3 weeks, her teacher noticed the improvement! The pay-on-delivery option made it easy to trust and order."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">FK</div>
                        <div class="author-info">
                            <h4>Fatima Khalid</h4>
                            <p>Kano, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Best purchase for my 4-year-old! The magic pen is fascinating for kids. He practices willingly now. The customer service was also very responsive on WhatsApp. 5 stars!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">DE</div>
                        <div class="author-info">
                            <h4>David Eze</h4>
                            <p>Enugu, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"Ordered the collection set and it arrived quickly. My three kids share them and their handwriting has improved so much. The books are reusable which saves money. Excellent value!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">NM</div>
                        <div class="author-info">
                            <h4>Ngozi Madu</h4>
                            <p>Port Harcourt, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"My son is 6 and autistic. These grooved books help him tremendously with motor skills. The repetition and tactile feedback work perfectly for him. Thank you for this amazing product!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">BA</div>
                        <div class="author-info">
                            <h4>Blessing Adeyemi</h4>
                            <p>Lagos, Nigeria</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"I was skeptical about online ordering but the pay-on-delivery convinced me. Product is exactly as described. My daughter's confidence in writing has grown. Will order again!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">IA</div>
                        <div class="author-info">
                            <h4>Ibrahim Abubakar</h4>
                            <p>Kaduna, Nigeria</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="social-proof-stats">
                <div class="proof-stat">
                    <h3>10,000+</h3>
                    <p>Happy Customers</p>
                </div>
                <div class="proof-stat">
                    <h3>4.9/5</h3>
                    <p>Average Rating</p>
                </div>
                <div class="proof-stat">
                    <h3>98%</h3>
                    <p>Satisfaction Rate</p>
                </div>
                <div class="proof-stat">
                    <h3>24-48hrs</h3>
                    <p>Fast Delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral Section -->
    <section class="referral" id="referral">
        <div class="container">
            <div class="referral-content">
                <div class="referral-text">
                    <h2>Earn <span>💰₦10,000💰 Cashback</span> With Our Referral Program</h2>
                    <p>Refer friends and earn cashback when they purchase using your unique referral code. The more you refer, the more you earn!</p>
                    <p>After making a purchase, you'll receive a unique referral code on your thank you page. Share this code with up to 3 friends, and when they make a purchase, you'll get 💰₦10,000💰 cashback credited to your account.</p>
                    <p style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 10px 14px; border-radius: 8px; border-left: 4px solid #f59e0b; margin-top: 12px;">🎉 <strong>Gift Idea:</strong> Share this amazing deal with friends who have kids - it makes a perfect surprise gift for their children too! 🎁</p>
                    <button class="cta-button" data-scroll-to="#order">Start Referring Now</button>
                </div>
                
                <div class="referral-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Make a Purchase</h4>
                            <p>Buy any of our kids copybook packages during the Black Friday sale.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Get Your Referral Code</h4>
                            <p>After purchase, you'll find your unique referral code on the thank you page.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Share With Friends</h4>
                            <p>Share your code with up to 3 friends and encourage them to purchase.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Earn Cashback</h4>
                            <p>When your friends purchase using your code, you'll get 💰₦10,000💰 cashback per referral.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Section -->
    <section class="whatsapp-section">
        <div class="container">
            <div class="whatsapp-container">
                <h2>Have Questions? Chat With Us on WhatsApp!</h2>
                <p>Our customer service team is available to answer any questions you may have about our products.</p>
                
                <div class="whatsapp-numbers">
                    <a href="https://wa.me/23409029026782" class="whatsapp-number" target="_blank">
                        <i class="fab fa-whatsapp"></i> 09029026782
                    </a>
                    <a href="https://wa.me/2348102609396" class="whatsapp-number" target="_blank">
                        <i class="fab fa-whatsapp"></i> 08102609396
                    </a>
                </div>
                
                <p>We're here to help you choose the right copybook for your child!</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Smartkids Edu</h4>
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
                &copy; 2019 - <?php echo date("Y"); ?> Emerald Tech Hub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Dynamic pricing from database
        const PACKAGE_PRICES = {
            'starter': <?php echo $prices['starter']['price']; ?>,
            'bundle': <?php echo $prices['bundle']['price']; ?>,
            'collection': <?php echo $prices['collection']['price']; ?>
        };
        
        // Wait for DOM to be fully loaded before initializing interactive elements
        document.addEventListener('DOMContentLoaded', function() {
        // Countdown Timer - fixed calendar deadline (today -> tomorrow end of day)
        (function() {
            // You can change FIXED_DEADLINE to a fixed ISO datetime string (e.g. '2025-11-30T23:59:59')
            // If null, we compute a fixed deadline of tomorrow at 23:59:59 (same for all users).
            const FIXED_DEADLINE = null; // set to null to use tomorrow 23:59:59

            function getFixedDeadline() {
                if (FIXED_DEADLINE) return new Date(FIXED_DEADLINE);
                const d = new Date();
                d.setDate(d.getDate() + 1);
                d.setHours(23,59,59,999);
                return d;
            }

            const deadline = getFixedDeadline();

            function updateCountdown() {
                const now = new Date();
                const diff = deadline - now;
                if (diff <= 0) {
                    ['days','hours','minutes','seconds','order-days','order-hours','order-minutes'].forEach(id=>{
                        const el = document.getElementById(id);
                        if(el) el.textContent = '00';
                    });
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                if (document.getElementById('days')) document.getElementById('days').textContent = String(days).padStart(2,'0');
                if (document.getElementById('hours')) document.getElementById('hours').textContent = String(hours).padStart(2,'0');
                if (document.getElementById('minutes')) document.getElementById('minutes').textContent = String(minutes).padStart(2,'0');
                if (document.getElementById('seconds')) document.getElementById('seconds').textContent = String(seconds).padStart(2,'0');

                if (document.getElementById('order-days')) document.getElementById('order-days').textContent = String(days).padStart(2,'0');
                if (document.getElementById('order-hours')) document.getElementById('order-hours').textContent = String(hours).padStart(2,'0');
                if (document.getElementById('order-minutes')) document.getElementById('order-minutes').textContent = String(minutes).padStart(2,'0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
        
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
        
        // CTA buttons: scroll to order section when clicked
        function initScrollButtons() {
            document.querySelectorAll('.cta-button, button.cta-button').forEach(btn => {
                // Remove any existing listeners
                btn.removeEventListener('click', handleButtonClick);
                // Add new listener
                btn.addEventListener('click', handleButtonClick);
            });
        }
        
        function handleButtonClick(e) {
            const target = this.getAttribute('data-scroll-to') || this.getAttribute('href');
            console.log('Button clicked:', target); // Debug log
            if (target && target.startsWith('#')) {
                e.preventDefault();
                e.stopPropagation();
                const el = document.querySelector(target);
                if (el) {
                    const offsetTop = el.offsetTop - 80;
                    window.scrollTo({ 
                        top: offsetTop, 
                        behavior: 'smooth' 
                    });
                    console.log('Scrolling to:', offsetTop); // Debug log
                } else {
                    console.error('Target element not found:', target);
                }
            }
        }
        
        // Initialize scroll buttons
        initScrollButtons();
        
        // Re-initialize after a short delay to catch any dynamically added buttons
        setTimeout(initScrollButtons, 500);

        // Price chip clicks: auto-select package and jump to order form
        document.querySelectorAll('.price-chip').forEach(function(chip){
            chip.addEventListener('click', function(e){
                const pkg = chip.getAttribute('data-package');
                if(!pkg) return;
                const select = document.getElementById('package');
                if(select){
                    // try to pick matching option (case-insensitive compare)
                    let matched = false;
                    for(let i=0;i<select.options.length;i++){
                        if(select.options[i].value.toLowerCase() === pkg.toLowerCase()){
                            select.selectedIndex = i; matched = true; break;
                        }
                    }
                    // if not exact match, try partial match by text
                    if(!matched){
                        for(let i=0;i<select.options.length;i++){
                            if(select.options[i].text.toLowerCase().indexOf(pkg.toLowerCase()) !== -1){
                                select.selectedIndex = i; matched = true; break;
                            }
                        }
                    }
                    // dispatch change if any
                    select.dispatchEvent(new Event('change'));
                }

                // scroll to order
                const orderEl = document.getElementById('order');
                if(orderEl){
                    window.scrollTo({ top: orderEl.offsetTop - 60, behavior: 'smooth' });
                    // focus first input after a short delay
                    setTimeout(function(){ const name = document.getElementById('name'); if(name) name.focus(); }, 700);
                }
            });
        });

        // Form submission (AJAX to php/order.php)
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitButton = document.querySelector('.submit-button');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Processing...';
            submitButton.disabled = true;

            // helper: show inline messages above the form
            function showFormMessage(text, type){
                const el = document.getElementById('formMessage');
                if(!el) return;
                el.style.display = 'block';
                el.textContent = text;
                el.style.color = type === 'error' ? '#7f1d1d' : '#064e3b';
                el.style.background = type === 'error' ? '#fff1f2' : '#ecfdf5';
                el.style.border = type === 'error' ? '1px solid #fecaca' : '1px solid #bbf7d0';
            }

            // Build form data expected by php/order.php
            const fd = new FormData();
            // Use the single 'name' input (site uses one name field)
            const nameEl = document.getElementById('name');
            const fullName = nameEl ? nameEl.value.trim() : '';
            fd.append('name', fullName);
            // order.php expects 'pack'
            fd.append('pack', document.getElementById('package').value || 'starter');
            fd.append('email', document.getElementById('email').value || '');
            fd.append('phone', document.getElementById('phone').value || '');
            // include altphone if provided
            fd.append('altphone', document.getElementById('altphone') ? document.getElementById('altphone').value : '');
            fd.append('address', document.getElementById('address').value || '');
            fd.append('state', document.getElementById('state').value || '');
            // Facebook traffic source
            fd.append('source', 'facebook-marketplace');

            console.log('Submitting order...', {name: fullName, pack: document.getElementById('package').value});
            showFormMessage('Processing your order — please wait...', 'info');

            // Add timeout to fetch request
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
            
            fetch('php/submit_order.php', {
                method: 'POST',
                body: fd,
                signal: controller.signal
            }).then(r => {
                clearTimeout(timeoutId);
                if (!r.ok) {
                    throw new Error('Server returned error: ' + r.status);
                }
                return r.json();
            }).then(res => {
                console.log('Server response', res);
                if (res.type === 'message' || res.success) {
                    // Play payment-success animation, then redirect to thank you page
                    const targetUrl = res.referral_code ? ('thank-You.new.php?ref=' + encodeURIComponent(res.referral_code)) : ('thank-You.new.php?order_id=' + encodeURIComponent(res.order_id));
                    // optional: pass order meta to showSuccessMessage if desired
                            try {
                                // Track Purchase via Facebook Pixel (if available)
                                try {
                                    const pkg = (document.getElementById('package') && document.getElementById('package').value) ? document.getElementById('package').value.toLowerCase() : '';
                                    const purchaseValue = PACKAGE_PRICES[pkg] || null;
                                    if(window.fbq && purchaseValue){ fbq('track', 'Purchase', { currency: 'NGN', value: purchaseValue }); }
                                } catch(e){ /* ignore pixel errors */ }

                                playPaymentSuccessAnimation(function(){ window.location = targetUrl; });
                            } catch (e) {
                                // fallback: if animation fails, redirect immediately
                                window.location = targetUrl;
                            }
                } else if (res.type === 'error') {
                    showFormMessage(res.text || 'There was an error processing your order.', 'error');
                } else {
                    showFormMessage(res.text || 'Unexpected response from server.', 'error');
                }
            }).catch(err => {
                clearTimeout(timeoutId);
                console.error('Fetch error:', err);
                let errorMsg = 'Network error — please check your connection and try again.';
                if (err.name === 'AbortError') {
                    errorMsg = 'Request timeout — the server took too long to respond. Please try again.';
                } else if (err.message) {
                    errorMsg = 'Error: ' + err.message;
                }
                showFormMessage(errorMsg, 'error');
            }).finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
        
        // Function to show success message
        function showSuccessMessage(orderId, referralCode, formData) {
            const packageNames = {
                'starter': 'Starter Set (1 Set)',
                'bundle': 'Learning Bundle (2 Sets)',
                'collection': 'Mastery Collection (3 Sets)'
            };
            
            const packageName = packageNames[formData.package] || formData.package;
            const packagePrice = PACKAGE_PRICES[formData.package] ? '₦' + PACKAGE_PRICES[formData.package].toLocaleString() : '';
            
            const message = `
                <div style="text-align: center; padding: 20px;">
                    <h2 style="color: #0a7c42;">Order Successful!</h2>
                    <p>Thank you for your order. Here are your order details:</p>
                    <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 15px 0;">
                        <p><strong>Order ID:</strong> ${orderId}</p>
                        <p><strong>Package:</strong> ${packageName}</p>
                        <p><strong>Amount:</strong> ${packagePrice}</p>
                        <p><strong>Delivery to:</strong> ${formData.state} State</p>
                        <p><strong>Your Referral Code:</strong> ${referralCode}</p>
                    </div>
                    <p>You will receive a confirmation email shortly. Our team will contact you within 24 hours to confirm delivery details.</p>
                    <p><strong>Share your referral code with friends to earn 💰₦10,000💰 cashback!</strong></p>
                    <button onclick="this.parentElement.parentElement.style.display='none'" style="background: #0a7c42; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px;">Close</button>
                </div>
            `;
            
            // Create and show modal
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '1000';
            
            const modalContent = document.createElement('div');
            modalContent.style.background = 'white';
            modalContent.style.padding = '30px';
            modalContent.style.borderRadius = '15px';
            modalContent.style.maxWidth = '500px';
            modalContent.style.width = '90%';
            modalContent.innerHTML = message;
            
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        /* Payment success animation (confetti) and overlay
           callback is called when animation completes (use to redirect)
        */
        function playPaymentSuccessAnimation(callback){
            // create overlay
            const overlay = document.createElement('div'); overlay.className = 'payment-success-overlay';
            overlay.setAttribute('role','dialog'); overlay.setAttribute('aria-live','polite');

            const card = document.createElement('div'); card.className = 'payment-success-card';
            const check = document.createElement('div'); check.className = 'success-check'; check.innerHTML = '&#10003;';
            const title = document.createElement('h2'); title.textContent = 'Order Successful!';
            const text = document.createElement('p'); text.textContent = 'Thank you — your order was placed successfully. Redirecting to the thank you page...';

            // confetti canvas
            const cvs = document.createElement('canvas'); cvs.style.width = '100%'; cvs.style.height = '160px';
            cvs.width = 900; cvs.height = 200; cvs.style.display = 'block';

            card.appendChild(check);
            card.appendChild(title);
            card.appendChild(text);
            card.appendChild(cvs);
            overlay.appendChild(card);
            document.body.appendChild(overlay);

            // simple confetti particle system
            const ctx = cvs.getContext('2d');
            let particles = [];
            function rand(min,max){ return Math.random()*(max-min)+min; }
            function spawnConfetti(count){
                for(let i=0;i<count;i++){
                    particles.push({
                        x: rand(20, cvs.width-20),
                        y: rand(20, 60),
                        vx: rand(-3,3),
                        vy: rand(1,5),
                        size: rand(6,12),
                        color: ['#ff595e','#ffca3a','#8ac926','#1982c4','#6a4c93'][Math.floor(Math.random()*5)],
                        rot: rand(0,Math.PI*2),
                        rotSpeed: rand(-0.2,0.2),
                        ttl: Math.floor(rand(60,140))
                    });
                }
            }

            function update(){
                ctx.clearRect(0,0,cvs.width,cvs.height);
                for(let i=particles.length-1;i>=0;i--){
                    const p = particles[i];
                    p.x += p.vx; p.y += p.vy; p.vy += 0.12; p.rot += p.rotSpeed; p.ttl--;
                    ctx.save(); ctx.translate(p.x,p.y); ctx.rotate(p.rot);
                    ctx.fillStyle = p.color; ctx.fillRect(-p.size/2,-p.size/2,p.size,p.size);
                    ctx.restore();
                    if(p.ttl<=0 || p.y>cvs.height+50) particles.splice(i,1);
                }
            }

            let frames = 0; const duration = 3000; // ms (longer for more celebration)
            let raf;
            function loop(){
                update();
                frames += 16; // approximate
                // spawn a larger burst periodically for a stronger effect
                if(frames % 160 === 0) spawnConfetti(18);
                if(frames < duration){ raf = requestAnimationFrame(loop); }
                else { cancelAnimationFrame(raf); overlay.classList.add('hide'); setTimeout(()=>{ document.body.removeChild(overlay); if(typeof callback === 'function') callback(); }, 280); }
            }

            // size canvas for crisp rendering
            function fit(){
                const rect = cvs.getBoundingClientRect();
                cvs.width = Math.max(320, Math.floor(rect.width));
                cvs.height = Math.max(120, Math.floor(rect.height));
            }
            fit(); spawnConfetti(80); loop();
        }

        // Lightbox: open gallery images in modal with prev/next
        (function(){
            const thumbnails = Array.from(document.querySelectorAll('.gallery-thumb'));
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const closeBtn = document.getElementById('lightbox-close');
            const prevBtn = document.getElementById('lightbox-prev');
            const nextBtn = document.getElementById('lightbox-next');
            let current = 0;

            function show(idx){
                if(idx < 0) idx = thumbnails.length - 1;
                if(idx >= thumbnails.length) idx = 0;
                current = idx;
                const src = thumbnails[current].getAttribute('src') || thumbnails[current].getAttribute('data-src');
                if(lightboxImage) lightboxImage.src = src;
                if(lightbox) lightbox.style.display = 'flex';
            }

            thumbnails.forEach((t, i) => {
                t.addEventListener('click', function(e){ e.preventDefault(); show(i); });
            });

            if(closeBtn) closeBtn.addEventListener('click', function(){ if(lightbox) lightbox.style.display = 'none'; });
            if(lightbox) lightbox.addEventListener('click', function(e){ if(e.target === lightbox) lightbox.style.display = 'none'; });
            if(prevBtn) prevBtn.addEventListener('click', function(e){ e.stopPropagation(); show(current-1); });
            if(nextBtn) nextBtn.addEventListener('click', function(e){ e.stopPropagation(); show(current+1); });
        })();

        /* Fireworks controller: start/stop and mobile-disable toggle
           - Respects localStorage 'fireworksEnabled' (string 'false' disables)
           - Automatically disabled on narrow screens (<480px)
        */
        (function(){
            const cvs = document.getElementById('fireworks-canvas');
            if(!cvs) return;
            const ctx = cvs.getContext('2d');
            let W = 0, H = 0, rafId = null;
            let fireworks = [];

            function rand(min,max){ return Math.random()*(max-min)+min; }

            function resize(){
                const rect = cvs.getBoundingClientRect();
                W = Math.max(300, Math.floor(rect.width));
                H = Math.max(200, Math.floor(rect.height));
                const dpr = window.devicePixelRatio || 1;
                cvs.width = Math.max(300, Math.floor(rect.width * dpr));
                cvs.height = Math.max(200, Math.floor(rect.height * dpr));
                cvs.style.width = rect.width + 'px';
                cvs.style.height = rect.height + 'px';
                ctx.setTransform(dpr,0,0,dpr,0,0);
            }

            window.addEventListener('resize', resize);

            function spawnFirework(){
                const x = rand(40, W-40);
                const y = rand(H*0.06, H*0.6);
                const hue = Math.floor(rand(0,360));
                const sparks = [];
                const count = Math.floor(rand(28,60));
                for(let i=0;i<count;i++){
                    const angle = (Math.PI*2) * (i/count) + rand(-0.06,0.06);
                    const speed = rand(3.2,8.0);
                    sparks.push({ x, y, vx: Math.cos(angle)*speed + rand(-1.2,1.2), vy: Math.sin(angle)*speed + rand(-1.2,1.2), life: Math.floor(rand(70,180)), age:0, hue, size: rand(4,12) });
                }
                fireworks.push({ sparks });
            }

            function update(){
                ctx.clearRect(0,0,W,H);
                for(let i=fireworks.length-1;i>=0;i--){
                    const f = fireworks[i];
                    for(let j=f.sparks.length-1;j>=0;j--){
                        const s = f.sparks[j];
                        s.x += s.vx; s.y += s.vy; s.vy += 0.06; s.vx *= 0.997; s.age++;
                        const alpha = Math.max(0, 1 - s.age/s.life);
                        const r = s.size || 5;
                        const g = ctx.createRadialGradient(s.x, s.y, 0, s.x, s.y, r*2);
                        g.addColorStop(0, `rgba(255,255,255,${Math.min(1, alpha+0.3)})`);
                        g.addColorStop(0.2, `hsla(${s.hue},90%,60%,${alpha})`);
                        g.addColorStop(1, `hsla(${s.hue},90%,45%,${alpha*0.02})`);
                        ctx.globalCompositeOperation = 'lighter';
                        ctx.fillStyle = g;
                        ctx.fillRect(s.x - r, s.y - r, r*2, r*2);
                    }
                    f.sparks = f.sparks.filter(s=> s.age < s.life && s.y < H + 50);
                    if(f.sparks.length === 0) fireworks.splice(i,1);
                }
            }

            let next = Date.now() + 600;
            function loop(){
                const now = Date.now();
                if(now > next){ spawnFirework(); next = now + rand(400, 1700); }
                update();
                rafId = requestAnimationFrame(loop);
            }

            // controller exposed to window for toggle
            window._fireworksController = {
                start: function(){ if(rafId) return; resize(); next = Date.now() + 300; loop(); },
                stop: function(){ if(rafId){ cancelAnimationFrame(rafId); rafId = null; fireworks = []; ctx.clearRect(0,0,W,H); } },
                isRunning: function(){ return !!rafId; }
            };

            // Fireworks toggle removed - auto-start only

            const userDisabled = (localStorage.getItem('fireworksEnabled') === 'false');
            if(!userDisabled && window.innerWidth >= 480){ window._fireworksController.start(); }

        })();

        // Auto-select package when gallery CTA is clicked
        (function(){
            document.querySelectorAll('.cta-button.small[data-package]').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    // if href is an anchor to #order, prevent default scrolling to handle selection first
                    const pkg = (btn.getAttribute('data-package') || '').toLowerCase();
                    const select = document.getElementById('package');
                    if(select && pkg){
                        let matched = false;
                        for(let i=0;i<select.options.length;i++){
                            if(select.options[i].value.toLowerCase() === pkg){
                                select.selectedIndex = i; matched = true; break;
                            }
                        }
                        if(!matched){
                            for(let i=0;i<select.options.length;i++){
                                if(select.options[i].text.toLowerCase().indexOf(pkg) !== -1){
                                    select.selectedIndex = i; matched = true; break;
                                }
                            }
                        }
                        select.dispatchEvent(new Event('change'));
                    }
                    // let the default anchor behavior scroll to #order
                });
            });
        })();

        // Demo order slide-in notifications (generate 20 fake orders and cycle them)
        (function(){
            const first = ['Ada','Emeka','Chinelo','Ifeanyi','Sade','Kunle','Ngozi','Tunde','Aisha','Olu','Maya','Ijeoma','Amaka','Ibrahim','Zainab','Uche','Kemi','Bola','Ikenna','Opeyemi'];
            const last = ['Nwosu','Okonkwo','Adetunji','Eze','Olawale','Ibrahim','Abiodun','Onyeka','Balogun','Chukwu','Afolabi','Odenigbo','Ejiro','Madu','Ilesanmi','Ogunleye','Suleiman','Ojo','Obi','Omotayo'];
            const packages = ['Starter','Learning Bundle','Mastery Collection'];
            const states = ['Lagos','Abuja','Rivers','Enugu','Oyo','Delta','Kaduna','Anambra','Kano','Katsina'];
            const thumbs = ['images/number_magic.jpg','images/letter_magic.jpg','images/magic_sank.jpg','images/maths_magic.jpg','images/paint_magi.jpg','images/1.jpg'];

            function rnd(arr){ return arr[Math.floor(Math.random()*arr.length)]; }
            function genName(i){ return first[i%first.length] + ' ' + last[(i*3)%last.length]; }

            const demoOrders = [];
            for(let i=0;i<20;i++){
                demoOrders.push({
                    name: genName(i),
                    pkg: rnd(packages),
                    state: rnd(states),
                    time: (Math.floor(Math.random()*50)+1) + 'm ago',
                    img: thumbs[i % thumbs.length]
                });
            }

            const container = document.getElementById('order-toast-container');
            let idx = 0;

            function showOrder(o){
                const el = document.createElement('div'); el.className = 'order-toast';
                el.innerHTML = `<img src="${o.img}" loading="lazy" alt="order thumbnail"><div class="ot-body"><strong>${o.name}</strong><div class="ot-pkg">${o.pkg}</div><div class="ot-meta">${o.time} • ${o.state}</div></div>`;
                container.appendChild(el);
                // animate in
                requestAnimationFrame(()=> el.classList.add('show') );
                // remove after show duration
                setTimeout(()=>{ el.classList.remove('show'); setTimeout(()=> el.remove(), 420); }, 4200);
            }

            // cycle through demo orders every 3.8s
            function cycle(){
                showOrder(demoOrders[idx]);
                idx = (idx + 1) % demoOrders.length;
            }

            // start immediately with a few seeds
            cycle();
            setTimeout(cycle, 1800);
            const interval = setInterval(cycle, 3800);

            // expose for debugging
            window._demoOrders = { orders: demoOrders, stop: ()=> clearInterval(interval) };
        })();

        // Facebook addToCart / quick Purchase tracking
        (function(){
            function getPurchaseValue(){
                try{
                    var pkg = (document.getElementById('package') && document.getElementById('package').value) ? document.getElementById('package').value.toLowerCase() : '';
                    return PACKAGE_PRICES[pkg] || PACKAGE_PRICES['starter'];
                }catch(e){ return PACKAGE_PRICES['starter']; }
            }

            var btn = document.getElementById('addToCartButton');
            if(btn){
                btn.addEventListener('click', function(e){
                    try{
                        var value = getPurchaseValue();
                        if(window.fbq){ fbq('track', 'Purchase', { currency: 'NGN', value: value }); }
                    }catch(err){ /* ignore */ }
                });
            }
        })();

        // Rotating hero benefit phrases
        (function(){
            const phrases = [
                'Reusable sank copybook',
                'The ink disappears after 5mins',
                'Perfect for kids 3 years and above'
            ];
            const el = document.getElementById('rotatingWord');
            if(!el) return;
            let idx = 0;
            function showNext(){
                el.classList.add('fade');
                setTimeout(()=>{
                    idx = (idx + 1) % phrases.length;
                    el.textContent = phrases[idx];
                    el.classList.remove('fade');
                }, 320);
            }
            el.textContent = phrases[0];
            setInterval(showNext, 2600);
        })();

        // Dynamic Stock Counter System with Random Values (1-10)
        (function(){
            // Initialize stock with random values between 3-10
            const stockData = {
                starter: Math.floor(Math.random() * 8) + 3,  // 3-10
                bundle: Math.floor(Math.random() * 6) + 3,   // 3-8 (more popular)
                collection: Math.floor(Math.random() * 8) + 3 // 3-10
            };

            // Save to localStorage to persist across page reloads
            const savedStock = localStorage.getItem('blackFridayStock');
            if(savedStock) {
                try {
                    const parsed = JSON.parse(savedStock);
                    Object.assign(stockData, parsed);
                } catch(e) {}
            }

            function updateStockDisplays() {
                // Update all stock counters on page
                document.querySelectorAll('.stock-counter').forEach(el => {
                    const stockType = el.getAttribute('data-stock');
                    if(stockType && stockData[stockType] !== undefined) {
                        el.textContent = stockData[stockType];
                    }
                });

                // Update hero stock (total remaining)
                const heroStock = document.getElementById('hero-stock');
                if(heroStock) {
                    const total = stockData.starter + stockData.bundle + stockData.collection;
                    heroStock.textContent = total;
                }

                // Update total stock in order section
                const totalStock = document.getElementById('total-stock');
                if(totalStock) {
                    const total = stockData.starter + stockData.bundle + stockData.collection;
                    totalStock.textContent = total;
                }

                // Save to localStorage
                localStorage.setItem('blackFridayStock', JSON.stringify(stockData));
            }

            function decreaseStock(type) {
                if(stockData[type] > 1) {
                    stockData[type]--;
                    updateStockDisplays();
                    return true;
                }
                return false;
            }

            // Simulate random stock decreases every 2-5 minutes
            function simulateStockDecrease() {
                const types = ['starter', 'bundle', 'collection'];
                const randomType = types[Math.floor(Math.random() * types.length)];
                
                if(decreaseStock(randomType)) {
                    showStockNotification(randomType, stockData[randomType]);
                }

                // Schedule next decrease in 2-5 minutes
                const nextDecrease = (Math.random() * 180000) + 120000; // 2-5 min
                setTimeout(simulateStockDecrease, nextDecrease);
            }

            function showStockNotification(type, remaining) {
                const packageNames = {
                    starter: 'Starter Set',
                    bundle: 'Learning Bundle',
                    collection: 'Mastery Collection'
                };

                const notification = document.createElement('div');
                notification.className = 'stock-notification';
                notification.innerHTML = `
                    <div style="font-size:1.2rem;margin-bottom:5px;">⚡ STOCK UPDATE!</div>
                    <div>Someone just ordered a <strong>${packageNames[type]}</strong>!</div>
                    <div style="margin-top:8px;font-size:1.1rem;">
                        📦 Only <strong style="font-size:1.3rem;">${remaining}</strong> ${packageNames[type]}${remaining > 1 ? 's' : ''} left!
                    </div>
                `;

                document.body.appendChild(notification);

                // Remove after 6 seconds
                setTimeout(() => {
                    notification.classList.add('fade-out');
                    setTimeout(() => notification.remove(), 500);
                }, 6000);
            }

            // Show initial reminder notification after 10 seconds
            setTimeout(() => {
                const notification = document.createElement('div');
                notification.className = 'stock-notification';
                const total = stockData.starter + stockData.bundle + stockData.collection;
                notification.innerHTML = `
                    <div style="font-size:1.2rem;margin-bottom:5px;">🔥 BLACK FRIDAY ALERT!</div>
                    <div><strong>Limited Time Offer</strong> - Only ${total} sets remaining!</div>
                    <div style="margin-top:8px;">⏰ Don't miss out on this amazing deal!</div>
                `;
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.classList.add('fade-out');
                    setTimeout(() => notification.remove(), 500);
                }, 7000);
            }, 10000);

            // Show periodic reminders every 3-4 minutes
            setInterval(() => {
                const total = stockData.starter + stockData.bundle + stockData.collection;
                if(total > 0) {
                    const notification = document.createElement('div');
                    notification.className = 'stock-notification';
                    notification.innerHTML = `
                        <div style="font-size:1.2rem;margin-bottom:5px;">⏳ HURRY!</div>
                        <div>Black Friday prices ending soon!</div>
                        <div style="margin-top:8px;font-size:1.1rem;">
                            Only <strong style="font-size:1.3rem;">${total}</strong> sets left at this price!
                        </div>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.classList.add('fade-out');
                        setTimeout(() => notification.remove(), 500);
                    }, 6000);
                }
            }, 210000); // 3.5 minutes

            // Initialize displays
            updateStockDisplays();

            // Start simulated stock decreases after 30 seconds
            setTimeout(simulateStockDecrease, 30000);

            // Expose for debugging
            window._stockSystem = { stockData, updateStockDisplays, decreaseStock, showStockNotification };
        })();
        
        }); // End DOMContentLoaded
    </script>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/2349029026782?text=Hi!%20I%20want%20to%20order%20the%20Sank%20Magic%20Copybook" 
       class="whatsapp-float" 
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="whatsapp-text">Chat with us</span>
    </a>

    <style>
        /* Floating WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 9999;
            transition: all 0.3s ease;
            text-decoration: none;
            animation: whatsapp-pulse 2s infinite;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
            background: linear-gradient(135deg, #128c7e 0%, #075e54 100%);
        }
        
        .whatsapp-float .whatsapp-text {
            display: none;
            position: absolute;
            right: 70px;
            background: white;
            color: #128c7e;
            padding: 10px 15px;
            border-radius: 8px;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }
        
        .whatsapp-float:hover .whatsapp-text {
            display: block;
        }
        
        .whatsapp-float .whatsapp-text::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-left-color: white;
        }
        
        @keyframes whatsapp-pulse {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            }
            50% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.8), 0 0 0 10px rgba(37, 211, 102, 0.2);
            }
        }
        
        @media (max-width: 768px) {
            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                font-size: 28px;
            }
            
            .whatsapp-float .whatsapp-text {
                display: none !important;
            }
        }
    </style>
</body>
</html>
