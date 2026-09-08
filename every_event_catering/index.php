<?php
// Every Event Catering Services System
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Every Event | Catering Services</title>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="page-home auth-required">

<header class="top-header">
    <form class="search-box" onsubmit="searchSite(event)">
        <input id="siteSearch" type="search" placeholder="SEARCH HERE" aria-label="Search this website">
        <button class="search-icon" type="submit" aria-label="Search">⌕</button>
    </form>
    <a href="#home" class="brand" onclick="showPage(event, 'home')" aria-label="Every Event home">
        <img src="logo.image.png" alt="Every Event logo">
    </a>
    <div class="header-actions">
        <a href="#" id="authLink" onclick="handleAuthClick(event)">REGISTER/LOGIN</a>
        <button class="heart" type="button" onclick="openBookingSummary()" aria-label="Open booking summary">
            ♡ <span id="bookingCount">0</span>
        </button>
        <button class="cart" type="button" onclick="openCart()" aria-label="Open booking cart">
            🛒 <span id="cartCount">0</span>
        </button>
    </div>
</header>

<nav class="navbar">
    <a href="#home" onclick="showPage(event, 'home')">HOME</a>
    <a href="#about" onclick="showPage(event, 'about')">ABOUT US</a>
    <a href="#services" onclick="showPage(event, 'services')">SERVICES</a>
    <a href="#pricing" onclick="showPage(event, 'pricing')">PRICING</a>
</nav>

<main>
    <section id="home" class="hero section">
        <div class="hero-copy">
            <p class="hero-label">PREMIUM CATERING IN DUMAGUETE</p>
            <h1>EVERY EVENT</h1>
            <h3>A TASTE FOR EVERY OCCASION</h3>
            <p>Exceptional food, thoughtful service, and flexible packages for weddings, corporate events, birthdays, and private celebrations.</p>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=85" alt="Catering food">
        </div>
        <div class="button-row">
            <a class="btn" href="#booking" onclick="goToBooking(event)">BOOK NOW</a>
            <a class="text-link" href="#about" onclick="showPage(event, 'about')">EXPLORE ABOUT US <span>→</span></a>
        </div>
    </section>

    <section id="about" class="about section">
        <div class="section-heading">
            <h2>ABOUT US</h2>
            <p>FOUNDED IN DUMAGUETE, EVERY EVENT WAS<br>
            CREATED WITH SINGULAR VISION TO BE A PREMIERE<br>
            CATERING SERVICE IN THE REGION.</p>
        </div>

        <div class="about-grid">
            <div class="about-text">
                <h2>OUR MISSION</h2>
                <h4>EXCELLENCE IN EVERY DETAIL</h4>
                <p>TO DELIVER EXCEPTIONAL CULINARY<br>
                EXPERIENCES THAT ELEVATE EVERY EVENT.</p>
                <p>THIS COMMITMENT GIVES EVERYTHING WE DO.<br>
                WE PROMISE FRESH INGREDIENTS, QUALITY FOOD AND<br>
                IMPECCABLE SERVICE, ENSURING THAT EVERY<br>
                OCCASION IS A MEMORABLE AND DELICIOUS<br>
                SUCCESS.</p>
                <a class="btn" href="#services">READ MORE</a>
                <a class="square-btn" href="#services">↗</a>
            </div>
            <img src="https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=800&q=85" alt="Buffet catering">
        </div>

        <div class="about-grid reverse">
            <img src="https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=800&q=85" alt="Elegant dining">
            <div class="about-text">
                <h2>OUR VISION</h2>
                <p>WE ARE KNOWN FOR OUR INNOVATIVE<br>
                MENUS, UNWAVERING COMMITMENT<br>
                TO QUALITY, AND ABILITY TO TURN<br>
                ANY EVENT INTO A TRULY CELEBRATION.</p>
                <p>WE BELIEVE GREAT FOOD IS THE HEART<br>
                OF EVERY MEMORABLE MOMENT.</p>
                <a class="btn" href="#services">READ MORE</a>
                <a class="square-btn" href="#services">↗</a>
            </div>
        </div>
    </section>

    <section id="services" class="services section">
        <div class="section-heading">
            <h2>SERVICES</h2>
            <p>WE TREAT YOUR EVENT AS OUR OWN.<br>
            FROM THE INITIAL CONSULTATION TO THE FINAL<br>
            CLEANUP, OUR DEDICATED TEAM PROFESSIONALS<br>
            ENSURE YOUR CLIENT EXPERIENCE.</p>
        </div>

        <div class="service-item">
            <div>
                <h3>Customizable Catering Packages</h3>
                <p>TAILORED MENUS FOR ALL TYPES OF EVENTS,<br>
                INCLUDING WEDDINGS, CORPORATE FUNCTIONS,<br>
                BIRTHDAYS, AND PRIVATE PARTIES.</p>
                <button class="outline-btn" onclick="goToBooking(event, 'Essential Setup Packages', 350)">GET A CUSTOM QUOTE</button>
            </div>
            <img src="https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=500&q=80" alt="Catering buffet">
        </div>

        <div class="service-item">
            <div>
                <h3>Event Planning Support</h3>
                <p>WE ASSIST WITH MENU SELECTION, EVENT<br>
                LAYOUT, AND COORDINATION TO ENSURE A<br>
                SEAMLESS EXPERIENCE FROM CONSULTATION<br>
                TO CLEANUP.</p>
                <button class="outline-btn" onclick="goToBooking(event, 'Signature Full-Service', 550)">GET A CUSTOM QUOTE</button>
            </div>
            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=500&q=80" alt="Event planning">
        </div>

        <div class="service-item">
            <div>
                <h3>Specialty Menus</h3>
                <p>OPTIONS ARE AVAILABLE FOR DIETARY<br>
                RESTRICTIONS SUCH AS VEGETARIAN, VEGAN,<br>
                AND GLUTEN-FREE MEALS.</p>
                <button class="outline-btn" onclick="goToBooking(event, 'Bespoke & Specialty', 750)">GET A CUSTOM QUOTE</button>
            </div>
            <img src="luxury images.webp" alt="Luxury specialty menu">
        </div>
    </section>

    <section id="pricing" class="pricing section">
        <div class="section-heading">
            <h2>PRICING</h2>
            <p>BECAUSE EVERY EVENT IS UNIQUE, OUR PRICING<br>
            IS CUSTOMIZED BASED ON YOUR GUEST COUNT,<br>
            CHOSEN MENU, AND SPECIFIC SERVICE REQUIREMENT.</p>
        </div>

        <div class="pricing-grid">
            <article class="price-card">
                <h3>Essential Setup<br>Package s</h3>
                <h2>₱350 <small>/ Person</small></h2>
                <p>CUSTOMIZABLE MENUS</p>
                <p>PROFESSIONAL<br>FOOD DELIVERY</p>
                <p>DETAILED DELIVERY &<br> TIMING COORDINATION</p>
                <button class="outline-btn" type="button" onclick="addToCart('Essential Setup Packages', 350)">ADD TO CART</button>
                <button class="btn" onclick="goToBooking(event, 'Essential Setup Packages', 350)">GET A CUSTOM QUOTE</button>
            </article>
            <article class="price-card">
                <h3>Signature Full-Service</h3>
                <h2>₱550 <small>/ Person</small></h2>
                <p>TRAINED SERVERS &<br>WAITSTAFF CREW</p>
                <p>FULL MENU SELECTION</p>
                <p>EVENT PLANNING SUPPORT</p>
                <p>CONSULTATION & LAYOUT</p>
                <button class="outline-btn" type="button" onclick="addToCart('Signature Full-Service', 550)">ADD TO CART</button>
                <button class="btn" onclick="goToBooking(event, 'Signature Full-Service', 550)">GET A CUSTOM QUOTE</button>
            </article>
            <article class="price-card">
                <h3>Bespoke & Specialty</h3>
                <h2>₱750 <small>/ Person</small></h2>
                <p>ALL FEATURES OF<br>SIGNATURE SERVICE</p>
                <p>SPECIALTY MENUS<br>(DIETARY ALLERGENS)</p>
                <p>ADVANCED VENDOR<br>COORDINATION</p>
                <button class="outline-btn" type="button" onclick="addToCart('Bespoke & Specialty', 750)">ADD TO CART</button>
                <button class="btn" onclick="goToBooking(event, 'Bespoke & Specialty', 750)">GET A CUSTOM QUOTE</button>
            </article>
        </div>
    </section>

    <section id="booking" class="booking-page">
        <div class="booking-card">
            <div class="section-heading">
                <h1>BOOK YOUR EVENT</h1>
                <p>Tell us about your event and our team will prepare a custom quote for you.</p>
            </div>

            <form id="bookingForm" onsubmit="submitBooking(event)">
                <label for="bookingName">Your Name</label>
                <input type="text" id="bookingName" required>

                <label for="bookingEmail">Email Address</label>
                <input type="email" id="bookingEmail" required>

                <label for="bookingDate">Event Date</label>
                <input type="date" id="bookingDate" required>

                <label for="bookingTime">Event Time</label>
                <input type="time" id="bookingTime" required>

                <label for="bookingGuests">Number of Guests</label>
                <input type="number" id="bookingGuests" min="1" required>

                <label for="bookingMessage">Tell us about your event</label>
                <textarea id="bookingMessage" rows="5"></textarea>

                <button class="btn" type="submit">SEND BOOKING REQUEST</button>
            </form>
        </div>
    </section>
</main>

<p id="searchMessage" role="status" aria-live="polite"></p>

<footer>
    <p>© 2026 EVERY EVENT CATERING SERVICES. ALL RIGHTS RESERVED.</p>
</footer>

<div class="modal" id="registerModal">
    <div class="auth-modal-content">
        <div class="auth-welcome">
            <img class="auth-logo" src="logo.image.png" alt="Every Event logo">
            <p class="auth-eyebrow">EVERY EVENT CATERING</p>
            <h1>A Taste For Every Occasion</h1>
            <p>Welcome to Every Event. Log in to continue managing your bookings, services, and event details in one friendly place.</p>
        </div>
        <div class="auth-form-panel">
            <button class="close" onclick="closeModal('registerModal')">×</button>
            <div class="auth-form-heading">
                <img src="logo.image.png" alt="Every Event logo">
                <span>Every Event</span>
            </div>
            <h2>Welcome back</h2>
            <p class="auth-form-copy">Log in to continue to your account.</p>
            <form onsubmit="loginUser(event)">
                <label for="loginEmail">Email or Username</label>
                <input type="email" id="loginEmail" placeholder="you@example.com" required>
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" placeholder="Enter your password" required minlength="6">
                <button class="btn" type="submit">LOGIN</button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="cartModal">
    <div class="modal-content cart-content">
        <button class="close" onclick="closeModal('cartModal')">×</button>
        <h2>ADVANCE BOOKING CART</h2>
        <div id="cartItems"></div>
        <p class="cart-total">TOTAL: <strong id="cartTotal">₱0</strong> / PERSON</p>
        <button class="btn" type="button" onclick="proceedToBooking()">PROCEED TO BOOKING</button>
    </div>
</div>

<div class="modal" id="bookingSummaryModal">
    <div class="modal-content booking-summary-content">
        <button class="close" onclick="closeModal('bookingSummaryModal')">×</button>
        <h2>BOOKING SUMMARY</h2>
        <div id="bookingSummary"></div>
    </div>
</div>

<div id="toast"></div>
<script src="javascript.js"></script>
</body>
</html>
