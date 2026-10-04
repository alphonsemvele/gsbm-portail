<!DOCTYPE html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>GSBM NDAZOA - Groupe Scolaire Bilingue La MAJESTUEUSE</title>
  <meta name="author" content="GSBM NDAZOA">
  <meta name="description" content="Groupe Scolaire Bilingue La MAJESTUEUSE - Excellence éducative à Yaoundé">
  <meta name="keywords" content="école bilingue, maternelle, Yaoundé, éducation, GSBM, La Majestueuse">
  <meta name="robots" content="INDEX,FOLLOW">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="57x57" href="{{asset('asset_vitrine/assets/img/logo.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('asset_vitrine/assets/img/logo.png')}}">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&amp;family=Baloo+2:wght@400..800&amp;family=Roboto:ital,wght@0,100..900;1,100..900&amp;display=swap" rel="stylesheet">
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/fontawesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/magnific-popup.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/swiper-bundle.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/animate.min.css')}}">
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/fontawesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/magnific-popup.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/swiper-bundle.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/animate.min.css')}}">
  <link rel="stylesheet" href="{{asset('asset_vitrine/assets/css/style.css')}}">
  
  <style>
    /* Sections Francophone et Anglophone */


  gallery-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 80px 0;
  }

  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 50px;
  }
  
  .gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    height: 350px;
  }
  
  .gallery-item:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 25px 60px rgba(112, 22, 126, 0.3);
  }
  
  .gallery-item__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }
  
  .gallery-item:hover .gallery-item__image {
    transform: scale(1.15);
  }
  
  .gallery-item__overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(112, 22, 126, 0.95) 0%, rgba(112, 22, 126, 0.7) 50%, transparent 100%);
    padding: 30px;
    transform: translateY(calc(100% - 80px));
    transition: transform 0.4s ease;
  }
  
  .gallery-item:hover .gallery-item__overlay {
    transform: translateY(0);
  }
  
  .gallery-item__category {
    display: inline-block;
    background: white;
    color: #70167E;
    padding: 6px 20px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  
  .gallery-item__title {
    color: white;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 10px 0;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.4s ease 0.1s;
  }

  .gallery-item:hover .gallery-item__title {
    opacity: 1;
    transform: translateY(0);
  }

  .gallery-item__description {
    color: rgba(255,255,255,0.95);
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.4s ease 0.2s;
  }

  .gallery-item:hover .gallery-item__description {
    opacity: 1;
    transform: translateY(0);
  }
  
  .gallery-item__icon {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 55px;
    height: 55px;
    background: rgba(255,255,255,0.95);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0) rotate(-180deg);
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }
  
  .gallery-item:hover .gallery-item__icon {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }
  
  .gallery-item__icon i {
    color: #70167E;
    font-size: 22px;
  }
  
  .gallery-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 50px;
  }
  
  .gallery-filter__btn {
    padding: 14px 35px;
    background: white;
    border: 3px solid #70167E;
    border-radius: 50px;
    color: #70167E;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }
  
  .gallery-filter__btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(112, 22, 126, 0.3);
  }

  .gallery-filter__btn.active {
    background: linear-gradient(135deg, #70167E 0%, #9B1BAF 100%);
    color: white;
    border-color: #9B1BAF;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(112, 22, 126, 0.4);
  }

  .gallery-view-more {
    text-align: center;
    margin-top: 60px;
  }

  .gallery-view-more .vs-btn {
    padding: 16px 45px;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* Responsive Design */
  @media (max-width: 991px) {
    .gallery-section {
      padding: 60px 0;
    }

    .gallery-grid {
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }
    
    .gallery-item {
      height: 300px;
    }
  }
  
  @media (max-width: 767px) {
    .gallery-section {
      padding: 50px 0;
    }

    .gallery-grid {
      grid-template-columns: 1fr;
      gap: 20px;
    }
    
    .gallery-item {
      height: 320px;
    }
    
    .gallery-filter {
      gap: 10px;
    }
    
    .gallery-filter__btn {
      padding: 12px 25px;
      font-size: 13px;
    }

    .gallery-item__title {
      font-size: 18px;
    }

    .gallery-item__description {
      font-size: 13px;
    }
  }

  /* Animation d'entrée */
  .gallery-item {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .gallery-item:nth-child(1) { animation-delay: 0.1s; }
  .gallery-item:nth-child(2) { animation-delay: 0.2s; }
  .gallery-item:nth-child(3) { animation-delay: 0.3s; }
  .gallery-item:nth-child(4) { animation-delay: 0.4s; }
  .gallery-item:nth-child(5) { animation-delay: 0.5s; }
  .gallery-item:nth-child(6) { animation-delay: 0.6s; }
  .gallery-item:nth-child(7) { animation-delay: 0.7s; }
  .gallery-item:nth-child(8) { animation-delay: 0.8s; }
  .gallery-item:nth-child(9) { animation-delay: 0.9s; }





    .language-section {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
      overflow: hidden;
      transition: transform 0.3s ease;
      height: 100%;
    }
    
    .language-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }
    
    .language-section__header {
      background: linear-gradient(135deg, #70167E 0%, #9B1BAF 100%);
      padding: 30px;
      text-align: center;
      color: white;
    }
    
    .francophone-section .language-section__header {
      background: linear-gradient(135deg, #002395 0%, #0055A4 100%);
    }
    
    .anglophone-section .language-section__header {
      background: linear-gradient(135deg, #C8102E 0%, #012169 100%);
    }
    
    .language-section__icon {
      margin-bottom: 15px;
    }
    
    .language-section__icon img {
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .language-section__title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }
    
    .language-section__subtitle {
      font-size: 16px;
      opacity: 0.9;
    }
    
    .language-section__body {
      padding: 30px;
    }
    
    .class-category {
      margin-bottom: 25px;
    }
    
    .class-category:last-child {
      margin-bottom: 0;
    }
    
    .class-category__title {
      font-size: 20px;
      font-weight: 700;
      color: #70167E;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .class-category__title i {
      margin-right: 8px;
    }
    
    .class-items {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .class-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 15px;
      margin-bottom: 8px;
      background: #f8f9fa;
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    
    .class-item:hover {
      background: #e9ecef;
      transform: translateX(5px);
    }
    
    .class-item__info {
      display: flex;
      flex-direction: column;
    }
    
    .class-item__name {
      font-weight: 600;
      color: #333;
      font-size: 15px;
    }
    
    .class-item__age {
      font-size: 13px;
      color: #666;
      margin-top: 3px;
    }
    
    .class-item__details .badge {
      padding: 6px 12px;
      font-size: 12px;
    }
    
    /* Transport Section */
    .transport-image {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
    }
    
    .transport-image img {
      width: 100%;
      height: auto;
    }
    
    .transport-badge {
      position: absolute;
      bottom: 20px;
      right: 20px;
      background: #70167E;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(112, 22, 126, 0.4);
    }
    
    .transport-features {
      margin-top: 30px;
    }
    
    .transport-feature-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 25px;
      padding: 20px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    
    .transport-feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .transport-feature-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #70167E 0%, #9B1BAF 100%);
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 20px;
      flex-shrink: 0;
    }
    
    .transport-feature-icon i {
      font-size: 24px;
      color: white;
    }
    
    .transport-feature-content h4 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #333;
    }
    
    .transport-feature-content p {
      margin: 0;
      color: #666;
      line-height: 1.6;
    }
    
    /* Playground Cards */
    .playground-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      height: 100%;
    }
    
    .playground-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }
    
    .playground-card__image {
      position: relative;
      overflow: hidden;
      height: 220px;
    }
    
    .playground-card__image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .playground-card:hover .playground-card__image img {
      transform: scale(1.1);
    }
    
    .playground-card__overlay {
      position: absolute;
      top: 15px;
      right: 15px;
    }
    
    .playground-card__age {
      background: rgba(112, 22, 126, 0.95);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }
    
    .playground-card__content {
      padding: 25px;
    }
    
    .playground-card__title {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 12px;
      color: #333;
    }
    
    .playground-card__title i {
      color: #70167E;
      margin-right: 8px;
    }
    
    .playground-card__desc {
      color: #666;
      line-height: 1.7;
      margin-bottom: 15px;
    }
    
    .playground-card__features {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .playground-card__features li {
      padding: 8px 0;
      color: #555;
      font-size: 14px;
    }
    
    .playground-card__features i {
      color: #4F830E;
      margin-right: 8px;
    }
    
    /* Location Section */
    .location-info-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }
    
    .location-info-card:hover {
      transform: translateX(10px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .location-info-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #70167E 0%, #9B1BAF 100%);
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 20px;
      flex-shrink: 0;
    }
    
    .location-info-icon i {
      font-size: 24px;
      color: white;
    }
    
    .location-info-content h4 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #333;
    }
    
    .location-info-content p {
      margin: 0;
      color: #666;
      line-height: 1.6;
    }
    
    .location-info-content a {
      color: #70167E;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .location-info-content a:hover {
      color: #9B1BAF;
    }
    
    .map-container {
      position: relative;
    }
    
    .map-wrapper {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .map-overlay-info {
      position: absolute;
      top: 20px;
      left: 20px;
    }
    
    .map-badge {
      background: white;
      padding: 15px 25px;
      border-radius: 50px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .map-badge i {
      color: #70167E;
      font-size: 24px;
    }
    
    .map-badge span {
      font-weight: 700;
      color: #333;
      font-size: 16px;
    }
    
    .landmark-section {
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    }
    
    .landmark-title {
      font-size: 24px;
      font-weight: 700;
      color: #333;
    }
    
    .landmark-title i {
      color: #70167E;
      margin-right: 10px;
    }
    
    .landmark-card {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .landmark-card:hover {
      background: #70167E;
      color: white;
      transform: translateY(-5px);
    }
    
    .landmark-card i {
      font-size: 32px;
      color: #70167E;
      margin-bottom: 10px;
      display: block;
    }
    
    .landmark-card:hover i {
      color: white;
    }
    
    .landmark-card span {
      font-weight: 600;
      font-size: 14px;
    }
    
    /* Counter Cards */
    .counter-card {
      text-align: center;
      padding: 30px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.06);
      transition: all 0.3s ease;
    }
    
    .counter-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    
    .counter-card__icon {
      margin-bottom: 20px;
    }
    
    .counter-card__number {
      font-size: 48px;
      font-weight: 700;
      color: #70167E;
      margin-bottom: 10px;
    }
    
    .counter-card__text {
      color: #666;
      font-size: 16px;
      margin: 0;
    }
    
    /* Responsive */
    @media (max-width: 991px) {
      .language-section__header {
        padding: 25px;
      }
      
      .language-section__title {
        font-size: 24px;
      }
      
      .transport-feature-item {
        padding: 15px;
      }
      
      .playground-card__image {
        height: 180px;
      }
    }
    
    @media (max-width: 767px) {
      .class-item {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .class-item__details {
        margin-top: 10px;
      }
      
      .transport-feature-icon {
        width: 50px;
        height: 50px;
      }
      
      .transport-feature-icon i {
        font-size: 20px;
      }
      
      .location-info-card {
        flex-direction: column;
        text-align: center;
      }
      
      .location-info-icon {
        margin-right: 0;
        margin-bottom: 15px;
      }
    }
  </style>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <button class="vs-btn preloaderCls">Annuler le préchargement</button>
    <div class="preloader-inner">
      <img src="{{asset('asset_vitrine/assets/img/logo.png')}}" style="height: 400px" alt="logo">
      <span class="loader"></span>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="vs-menu-wrapper">
    <div class="vs-menu-area text-center">
      <div class="mobile-logo">
        <a href="index.html"><img src="{{asset('asset_vitrine/assets/img/logo.png')}}" style="height: 150px" alt="GSBM NDAZOA" class="logo"></a>
        <button class="vs-menu-toggle">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="vs-header__right pt-4">
        <button class="searchBoxTggler" type="button">
          <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.4174 16.6954L17.2213 13.4773C19.3155 10.0703 18.8936 5.54217 15.9593 2.58766C12.5328 -0.862552 6.9769 -0.862552 3.55037 2.58766C0.123835 6.03787 0.123835 11.6322 3.55037 15.0824C6.5354 18.088 11.1341 18.4736 14.5333 16.2469L17.7019 19.4335C18.4521 20.1888 19.6711 20.1888 20.4213 19.4335C21.1675 18.6781 21.1675 17.4507 20.4174 16.6954ZM5.711 12.9029C3.48395 10.6604 3.48395 7.00959 5.711 4.76715C7.93805 2.52471 11.5638 2.52471 13.7909 4.76715C16.018 7.00959 16.018 10.6604 13.7909 12.9029C11.5638 15.1453 7.93805 15.1453 5.711 12.9029Z" fill="#F6F5F5"></path>
          </svg>
        </button>
        <button class="sideMenuToggler" type="button">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.4307 36H4.16306C1.86757 36 0 34.1324 0 31.8369V23.5693C0 21.2738 1.86757 19.4062 4.16306 19.4062H12.4307C14.7262 19.4062 16.5938 21.2738 16.5938 23.5693V31.8369C16.5938 34.1324 14.7262 36 12.4307 36Z" fill="#4A2559"></path>
          </svg>
        </button>
      </div>
      <div class="vs-mobile-menu">
        <ul>
          <li><a class="vs-svg-assets" href="index.html">Accueil</a></li>
          <li><a class="vs-svg-assets" href="about.html">À propos de nous</a></li>
          <li><a class="vs-svg-assets" href="javascript:void(0)">Actualités</a></li>
          <li><a class="vs-svg-assets" href="shop.html">FAQ</a></li>
          <li><a class="vs-svg-assets" href="contact.html">Contact</a></li>
        </ul>
      </div>
      <div class="px-20 py-20">
        <div class="sidemenu-contact style2">
          <ul>
            <li><a href="tel:+237657316683" class="sidemenu-link">+237 657 316 683</a></li>
            <li><a href="/cdn-cgi/l/email-protection#a4cdcac2cbe4c3d7c6c989cac0c5decbc58ac7cbc9" class="sidemenu-link"><span class="__cf_email__" data-cfemail="0960676f66496e7a6b6424676d68736668276a6664">[email&#160;protected]</span></a></li>
            <li><a href="index.html">Bankomo, Yaoundé</a></li>
          </ul>
        </div>
        <div class="footer-social mb-20">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-behance"></i></a>
        </div>
        <p class="sidemenu-text sidemenu-text--footer text-center mb-0">
          Copyright © 2025 <a class="vs-theme-color" href="index.html">GSBM NDAZOA</a>. Tous droits réservés.
        </p>
      </div>
    </div>
  </div>

  <!-- Popup Search Box -->
  <div class="popup-search-box">
    <button class="searchClose"><i class="fal fa-times"></i></button>
    <form action="#">
      <input id="search-field" type="text" class="border-theme" placeholder="Que recherchez-vous ?">
      <button type="submit"><i class="fal fa-search"></i></button>
    </form>
  </div>

  <!-- Header -->
  <header class="vs-header">
    <div class="vs-balls"></div>
    
    <!-- Header Top -->
    <div class="vs-header__top">
      <div class="container container--custom">
        <div class="row align-items-center justify-content-between gy-1 text-center text-lg-start">
          <div class="col-lg-auto d-none d-lg-block">
            <div class="d-flex align-items-center flex-wrap gap-4">
              <div class="vs-header__info">
                <i class="fa-solid fa-phone-volume"></i>
                <span>Téléphone : <a href="tel:+237657316683">+237 657 316 683</a></span>
              </div>
              <div class="vs-header__info">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span class="text-theme-color5">Horaires : <a href="#">7h30 - 17h00</a></span>
              </div>
            </div>
          </div>
          <div class="col-lg-auto">
            <div class="social-style">
              <span class="social-style__label">Suivez-nous :</span>
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sticky Header -->
    <div class="sticky-wrapper">
      <div class="sticky-active">
        <div class="container container--custom">
          <div class="row justify-content-between align-items-center">
            <div class="col">
              <div class="vs-header__logo">
                <a href="index.html"><img src="{{asset('asset_vitrine/assets/img/logo.png')}}" style="height: 90px" alt="GSBM NDAZOA" class="logo"></a>
              </div>
            </div>
            <div class="col-auto">
              <nav class="main-menu d-none d-lg-block">
                <ul>
                  <li><a class="vs-svg-assets" href="index.html">Accueil</a></li>
                  <li><a class="vs-svg-assets" href="about.html">À propos de nous</a></li>
                  <li><a class="vs-svg-assets" href="javascript:void(0)">Actualités</a></li>
                  <li><a class="vs-svg-assets" href="shop.html">FAQ</a></li>
                  <li><a class="vs-svg-assets" href="contact.html">Contact</a></li>
                </ul>
              </nav>
            </div>
            <div class="col-auto">
              <div class="vs-header__action">
                <div class="d-none d-md-inline-flex align-items-center">
                  <button class="searchBoxTggler">
                    <i class="far fa-search"></i>
                  </button>
                </div>
                <div class="d-none d-xxl-inline-flex">
                  <a href="/login" class="vs-btn"><span class="vs-btn__border"></span>Se connecter</a>
                </div>
                <button class="vs-menu-toggle style2 d-inline-block d-lg-none">
                  <i class="fal fa-bars"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="vs-main">
    <!-- Hero Section -->
    <section class="vs-hero vs-hero--style2 overflow-hidden">
      <div class="vs-balls vs-balls--screen" data-balls-bottom="-6px" data-balls-color="#ffffff"></div>
      <div class="swiper vs-hero__active--zoom">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="vs-hero__bg vs-hero__bg--zoom" data-bg-src="{{asset('asset_vitrine/assets/img/hero/banner-2-1.png')}}"></div>
            <div class="container">
              <div class="row">
                <div class="col-xl-7">
                  <div class="vs-hero__content">
                    <h1 class="vs-hero__title--main vs-hero__anim">
                      Bienvenue à La Majestueuse
                    </h1>
                    <p class="vs-hero__desc vs-hero__anim">
                      Un avenir brillant commence ici - Excellence éducative bilingue depuis 2010
                    </p>
                    <a href="contact.html" class="vs-btn vs-hero__btn vs-hero__anim">
                      <span class="vs-btn__border"></span>Découvrir notre école
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="vs-hero__bg vs-hero__bg--zoom" data-bg-src="{{asset('asset_vitrine/assets/img/hero/banner-2-1-1.png')}}"></div>
            <div class="container">
              <div class="row">
                <div class="col-xl-7">
                  <div class="vs-hero__content">
                    <h1 class="vs-hero__title--main vs-hero__anim">
                      Meilleure éducation pour votre enfant
                    </h1>
                    <p class="vs-hero__desc vs-hero__anim">
                      Programme d'apprentissage innovant avec suivi personnalisé
                    </p>
                    <a href="contact.html" class="vs-btn vs-hero__btn vs-hero__anim">
                      <span class="vs-btn__border"></span>Inscription en ligne
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="vs-hero__direction">
          <div class="vs-swiper-button-next">
            <i class="fa-solid fa-arrow-right"></i>
          </div>
          <div class="vs-swiper-button-prev">
            <i class="fa-solid fa-arrow-left"></i>
          </div>
        </div>
      </div>
    </section>

    <!-- Feature Section -->
    <section class="vs-feature--area space-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="vs-feature bg-color4 mb-30">
              <div class="vs-feature__top">
                <svg width="51" height="25" viewBox="0 0 51 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M1.22334 5.96216C-2.80791 4.16304 4.56635 -0.155225 6.66735 0.0125662C9.56929 0.24275 11.4149 2.05692 10.614 6.14164C10.1961 8.27957 12.3436 11.0964 13.9067 13.1524C16.2476 16.2384 19.165 18.8641 21.6955 21.8096C23.4947 23.9047 27.5419 23.8813 29.465 21.7121C32.5719 18.2125 35.8918 14.8964 38.8788 11.2991L38.8865 11.303C39.6488 10.2066 39.8693 8.81775 39.4902 7.53425C38.4145 5.09589 39.3044 3.93324 41.4905 2.96964C45.53 1.19454 48.7956 1.59245 49.7436 4.22198C50.7263 6.94908 49.1322 8.40037 46.7024 9.2665C40.0821 11.623 33.5052 22.32 34.6341 23.2657C35.464 23.9609 38.0209 24.2114 38.1012 24.2513C29.5541 24.2114 19.5821 24.2253 13.9857 24.2513C13.9145 24.2516 17.1281 23.5893 18.265 23.4549C21.4636 23.0766 8.86433 9.37229 1.22334 5.96216Z" fill="#4F830E" />
                </svg>
              </div>
              <div class="vs-feature__icon">
                <img src="{{asset('asset_vitrine/assets/img/icons/feature-icon-h2-1.svg')}}" alt="icône apprentissage">
              </div>
              <div class="vs-feature__content">
                <h3 class="vs-feature__title">Apprentissage Bilingue</h3>
                <p class="vs-feature__text">Programme éducatif français-anglais adapté à chaque niveau, favorisant le bilinguisme dès la maternelle avec des méthodes pédagogiques innovantes.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="vs-feature bg-color1 mb-30">
              <div class="vs-feature__top">
                <svg width="51" height="25" viewBox="0 0 51 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M1.22334 5.96216C-2.80791 4.16304 4.56635 -0.155225 6.66735 0.0125662C9.56929 0.24275 11.4149 2.05692 10.614 6.14164C10.1961 8.27957 12.3436 11.0964 13.9067 13.1524C16.2476 16.2384 19.165 18.8641 21.6955 21.8096C23.4947 23.9047 27.5419 23.8813 29.465 21.7121C32.5719 18.2125 35.8918 14.8964 38.8788 11.2991L38.8865 11.303C39.6488 10.2066 39.8693 8.81775 39.4902 7.53425C38.4145 5.09589 39.3044 3.93324 41.4905 2.96964C45.53 1.19454 48.7956 1.59245 49.7436 4.22198C50.7263 6.94908 49.1322 8.40037 46.7024 9.2665C40.0821 11.623 33.5052 22.32 34.6341 23.2657C35.464 23.9609 38.0209 24.2114 38.1012 24.2513C29.5541 24.2114 19.5821 24.2253 13.9857 24.2513C13.9145 24.2516 17.1281 23.5893 18.265 23.4549C21.4636 23.0766 8.86433 9.37229 1.22334 5.96216Z" fill="#70167E" />
                </svg>
              </div>
              <div class="vs-feature__icon">
                <img src="{{asset('asset_vitrine/assets/img/icons/feature-icon-h2-2.svg')}}" alt="icône cours en ligne">
              </div>
              <div class="vs-feature__content">
                <h3 class="vs-feature__title">Plateforme Numérique</h3>
                <p class="vs-feature__text">Accès à notre plateforme d'apprentissage en ligne pour compléter les cours en classe, avec ressources interactives et suivi des progrès en temps réel.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="vs-feature bg-color2 mb-30">
              <div class="vs-feature__top">
                <svg width="51" height="25" viewBox="0 0 51 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M1.22334 5.96216C-2.80791 4.16304 4.56635 -0.155225 6.66735 0.0125662C9.56929 0.24275 11.4149 2.05692 10.614 6.14164C10.1961 8.27957 12.3436 11.0964 13.9067 13.1524C16.2476 16.2384 19.165 18.8641 21.6955 21.8096C23.4947 23.9047 27.5419 23.8813 29.465 21.7121C32.5719 18.2125 35.8918 14.8964 38.8788 11.2991L38.8865 11.303C39.6488 10.2066 39.8693 8.81775 39.4902 7.53425C38.4145 5.09589 39.3044 3.93324 41.4905 2.96964C45.53 1.19454 48.7956 1.59245 49.7436 4.22198C50.7263 6.94908 49.1322 8.40037 46.7024 9.2665C40.0821 11.623 33.5052 22.32 34.6341 23.2657C35.464 23.9609 38.0209 24.2114 38.1012 24.2513C29.5541 24.2114 19.5821 24.2253 13.9857 24.2513C13.9145 24.2516 17.1281 23.5893 18.265 23.4549C21.4636 23.0766 8.86433 9.37229 1.22334 5.96216Z" fill="#D18109" />
                </svg>
              </div>
              <div class="vs-feature__icon">
                <img src="{{asset('asset_vitrine/assets/img/icons/feature-icon-h2-3.svg')}}" alt="icône aire de jeu">
              </div>
              <div class="vs-feature__content">
                <h3 class="vs-feature__title">Infrastructures Modernes</h3>
                <p class="vs-feature__text">Aires de jeux sécurisées, salles de classe climatisées, bibliothèque, laboratoire informatique et espaces verts pour l'épanouissement des enfants.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="vs-about--section space-extra-top space-extra-bottom z-index-common parallax-wrap" data-bg-src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-bg.png')}}">
      <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-ele-4.png')}}" alt="éléments" class="vs-about--ele1">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-30 wow animate__fadeInUp" data-wow-delay="0.25s">
            <div class="vs-about--image">
              <div class="vs-about--image__figure1 wow animate__fadeInUp" data-wow-delay="0.25s">
                <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-1.jpg')}}" alt="GSBM NDAZOA" width="198" height="214" loading="lazy">
              </div>
              <div class="vs-about--image__figure2 wow animate__fadeInUp" data-wow-delay="0.35s">
                <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h2-2.jpg')}}" alt="École bilingue" width="400" height="461" loading="lazy">
              </div>
              <div class="vs-about--image__ele1 parallax-element" data-move="80">
                <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-ele-1.svg')}}" alt="éléments">
              </div>
              <div class="vs-about--image__ele2 parallax-element" data-move="50">
                <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-ele-2.svg')}}" alt="éléments">
              </div>
              <div class="vs-about--image__ele3 wow animate__zoomIn" data-wow-delay="0.55s"></div>
              <div class="vs-about--yoe">
                <span class="vs-about--yoe__number">15+</span>
                <span class="vs-about--yoe__text">années d'excellence</span>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-30 wow animate__fadeInUp" data-wow-delay="0.45s">
            <div class="vs-about--right">
              <div class="vs-title title-anime animation-style2">
                <div class="title-anime__wrap">
                  <span class="vs-title__sub">Pourquoi choisir GSBM ?</span>
                  <h2 class="vs-title__main">
                    Opportunités <span>d'apprentissage</span> exceptionnelles
                  </h2>
                </div>
              </div>
              <div class="vs-about--story">
                <div class="vs-about--story__tab mb-30">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab">
                        Notre Histoire
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab">
                        Notre École
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
                        Nos Enfants
                      </button>
                    </li>
                  </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="history-tab-pane" role="tabpanel">
                    <p class="vs-about__text vs-text">Fondée en 2010, GSBM La Majestueuse s'est imposée comme une référence en matière d'éducation bilingue à Yaoundé. Notre mission est de former des citoyens du monde équilibrés, créatifs et bilingues.</p>
                    <ul class="vs-list pt-15 mb-35">
                      <li>Plus de 15 ans d'excellence éducative</li>
                      <li>Plus de 2000 élèves formés avec succès</li>
                      <li>Taux de réussite de 98% aux examens nationaux</li>
                      <li>Équipe pédagogique qualifiée et expérimentée</li>
                    </ul>
                    <a href="contact.html" class="vs-btn"><span class="vs-btn__border"></span>Nous contacter</a>
                  </div>
                  <div class="tab-pane fade" id="home-tab-pane" role="tabpanel">
                    <p class="vs-about__text vs-text">GSBM La Majestueuse dispose d'infrastructures modernes : salles de classe climatisées, laboratoire informatique, bibliothèque fournie, aires de jeux sécurisées et espaces verts.</p>
                    <ul class="vs-list pt-15 mb-35">
                      <li>Classes à effectifs réduits (max 25 élèves)</li>
                      <li>Matériel pédagogique de qualité</li>
                      <li>Programme bilingue équilibré</li>
                      <li>Activités parascolaires variées</li>
                    </ul>
                    <a href="contact.html" class="vs-btn"><span class="vs-btn__border"></span>Visiter l'école</a>
                  </div>
                  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
                    <p class="vs-about__text vs-text">Nous accordons une attention particulière à l'épanouissement de chaque enfant à travers un suivi personnalisé, des activités ludiques et éducatives qui stimulent la créativité et la confiance en soi.</p>
                    <ul class="vs-list pt-15 mb-35">
                      <li>Suivi personnalisé de chaque élève</li>
                      <li>Développement des compétences sociales</li>
                      <li>Éveil artistique et sportif</li>
                      <li>Communication régulière avec les parents</li>
                    </ul>
                    <a href="contact.html" class="vs-btn"><span class="vs-btn__border"></span>Inscription</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Grade Programs Section -->
    <section class="vs-pro--area layout-h2 position-relative parallax-wrap" data-bg-src="{{asset('asset_vitrine/assets/img/bg/program-bg.png')}}">
      <div class="vs-pro--ele1 parallax-element" data-move="200">
        <img src="{{asset('asset_vitrine/assets/img/pro/pro-ele-1.png')}}" alt="élément">
      </div>
      <div class="vs-pro--ele2 parallax-element" data-move="100">
        <img src="{{asset('asset_vitrine/assets/img/pro/pro-ele-2.png')}}" alt="élément">
      </div>
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-auto text-center text-lg-start">
            <div class="vs-title title-anime animation-style2">
              <div class="title-anime__wrap">
                <span class="vs-title__sub text-white">NOS NIVEAUX</span>
                <h2 class="vs-title__main text-white">Programmes par Niveau</h2>
              </div>
              <p class="text-white fw-bold text-capitalize font-title vs-text-bolder mb-0">De la maternelle au primaire</p>
            </div>
            <div class="vs-pro--slider__direction justify-content-center justify-content-lg-start mb-md-4">
              <div class="vs-pro--slider__next">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
              <div class="vs-pro--slider__prev">
                <i class="fa-solid fa-arrow-right"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-7 flex-grow-1">
            <div class="swiper vs-carousel" data-xl="3" data-space-xl="15" data-md="3" data-sm="2" data-space-lg="5" data-nav-next=".vs-pro--slider__next" data-nav-prev=".vs-pro--slider__prev">
              <div class="swiper-wrapper p-4">
                <div class="swiper-slide wow animate__fadeInUp" data-wow-delay="0.25s">
                  <div class="vs-pro">
                    <span class="vs-pro__grade bg-color1">Maternelle<span>1</span></span>
                    <h2 class="vs-pro__title">Petite Section</h2>
                    <span class="vs-pro__age">Âge 3 - 4 ans</span>
                  </div>
                </div>
                <div class="swiper-slide wow animate__fadeInUp" data-wow-delay="0.27s">
                  <div class="vs-pro">
                    <span class="vs-pro__grade bg-color2">Maternelle<span>2</span></span>
                    <h2 class="vs-pro__title">Moyenne Section</h2>
                    <span class="vs-pro__age">Âge 4 - 5 ans</span>
                  </div>
                </div>
                <div class="swiper-slide wow animate__fadeInUp" data-wow-delay="0.29s">
                  <div class="vs-pro">
                    <span class="vs-pro__grade bg-color3">Maternelle<span>3</span></span>
                    <h2 class="vs-pro__title">Grande Section</h2>
                    <span class="vs-pro__age">Âge 5 - 6 ans</span>
                  </div>
                </div>
                <div class="swiper-slide wow animate__fadeInUp" data-wow-delay="0.3s">
                  <div class="vs-pro">
                    <span class="vs-pro__grade bg-color4">Primaire<span>1-6</span></span>
                    <h2 class="vs-pro__title">Classes Primaires</h2>
                    <span class="vs-pro__age">Âge 6 - 12 ans</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Sections Francophone et Anglophone -->
    <section class="space bg-white position-relative">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="vs-title title-anime animation-style2 text-center mb-50">
              <div class="title-anime__wrap">
                <span class="vs-title__sub">Nos Filières</span>
                <h2 class="vs-title__main">
                  Sections <span>Bilingues</span>
                </h2>
                <p class="mt-3">Programme équilibré français-anglais adapté à chaque niveau</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row gy-4">
          <!-- Section Francophone -->
          <div class="col-lg-6">
            <div class="language-section francophone-section">
              <div class="language-section__header">
                <div class="language-section__icon">
                  <img src="https://flagcdn.com/w80/fr.png" alt="Drapeau France" width="60">
                </div>
                <h3 class="language-section__title text-white">Section Francophone</h3>
                <p class="language-section__subtitle text-white">Programme éducatif camerounais en français</p>
              </div>
              <div class="language-section__body">
                <div class="class-list">
                  <!-- Maternelle -->
                  <div class="class-category">
                    <h4 class="class-category__title">
                      <i class="fas fa-baby"></i> Maternelle
                    </h4>
                    <ul class="class-items">
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Petite Section (PS)</span>
                          <span class="class-item__age">3-4 ans</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Moyenne Section (MS)</span>
                          <span class="class-item__age">4-5 ans</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Grande Section (GS)</span>
                          <span class="class-item__age">5-6 ans</span>
                        </div>
                        
                      </li>
                    </ul>
                  </div>
                  
                  <!-- Primaire -->
                  <div class="class-category">
                    <h4 class="class-category__title">
                      <i class="fas fa-school"></i> Primaire
                    </h4>
                    <ul class="class-items">
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Cours Préparatoire (CP)</span>
                          <span class="class-item__age">6-7 ans</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Cours Élémentaire 1 (CE1)</span>
                          <span class="class-item__age">7-8 ans</span>
                        </div>
                      
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Cours Élémentaire 2 (CE2)</span>
                          <span class="class-item__age">8-9 ans</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Cours Moyen 1 (CM1)</span>
                          <span class="class-item__age">9-10 ans</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Cours Moyen 2 (CM2)</span>
                          <span class="class-item__age">10-11 ans</span>
                        </div>
                      
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Section Anglophone -->
          <div class="col-lg-6">
            <div class="language-section anglophone-section">
              <div class="language-section__header">
                <div class="language-section__icon">
                  <img src="https://flagcdn.com/w80/gb.png" alt="Drapeau UK" width="60">
                </div>
                <h3 class="language-section__title text-white">English Section</h3>
                <p class="language-section__subtitle text-white">Cameroon Educational System in English</p>
              </div>
              <div class="language-section__body">
                <div class="class-list">
                  <!-- Nursery -->
                  <div class="class-category">
                    <h4 class="class-category__title">
                      <i class="fas fa-baby"></i> Nursery School
                    </h4>
                    <ul class="class-items">
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Nursery 1</span>
                          <span class="class-item__age">3-4 years</span>
                        </div>
                      
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Nursery 2</span>
                          <span class="class-item__age">4-5 years</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Kindergarten (KG)</span>
                          <span class="class-item__age">5-6 years</span>
                        </div>
                        
                      </li>
                    </ul>
                  </div>
                  
                  <!-- Primary -->
                  <div class="class-category">
                    <h4 class="class-category__title">
                      <i class="fas fa-school"></i> Primary School
                    </h4>
                    <ul class="class-items">
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 1</span>
                          <span class="class-item__age">6-7 years</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 2</span>
                          <span class="class-item__age">7-8 years</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 3</span>
                          <span class="class-item__age">8-9 years</span>
                        </div>
                      
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 4</span>
                          <span class="class-item__age">9-10 years</span>
                        </div>
                       
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 5</span>
                          <span class="class-item__age">10-11 years</span>
                        </div>
                        
                      </li>
                      <li class="class-item">
                        <div class="class-item__info">
                          <span class="class-item__name">Class 6</span>
                          <span class="class-item__age">11-12 years</span>
                        </div>
                       
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Service de Transport Scolaire -->
    <section class="space bg-theme-color-5 position-relative overflow-hidden">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-30">
            <div class="transport-image wow animate__fadeInLeft" data-wow-delay="0.25s">
              <img src="{{asset('images/car.jpeg')}}" alt="Bus scolaire GSBM" class="img-fluid rounded shadow-lg">
              <div class="transport-badge">
                <i class="fas fa-bus fa-3x text-white"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-30">
            <div class="transport-content wow animate__fadeInRight" data-wow-delay="0.35s">
              <div class="vs-title title-anime animation-style2">
                <div class="title-anime__wrap">
                  <span class="vs-title__sub">Transport Scolaire</span>
                  <h2 class="vs-title__main">
                    Service de <span>Bus</span> Disponible
                  </h2>
                </div>
              </div>
              <p class="mb-4">
                GSBM La Majestueuse met à disposition de ses élèves un service de transport scolaire sécurisé et confortable couvrant plusieurs quartiers de Yaoundé.
              </p>
              
              <div class="transport-features">
                <div class="transport-feature-item">
                  <div class="transport-feature-icon">
                    <i class="fas fa-shield-alt"></i>
                  </div>
                  <div class="transport-feature-content">
                    <h4>Sécurité Maximale</h4>
                    <p>Bus récents équipés de ceintures de sécurité et surveillance permanente</p>
                  </div>
                </div>
                
                <div class="transport-feature-item">
                  <div class="transport-feature-icon">
                    <i class="fas fa-user-tie"></i>
                  </div>
                  <div class="transport-feature-content">
                    <h4>Personnel Qualifié</h4>
                    <p>Chauffeurs expérimentés et accompagnateurs dédiés dans chaque bus</p>
                  </div>
                </div>
                
                <div class="transport-feature-item">
                  <div class="transport-feature-icon">
                    <i class="fas fa-route"></i>
                  </div>
                  <div class="transport-feature-content">
                    <h4>Itinéraires Multiples</h4>
                    <p>7 circuits couvrant Yaoundé : Bastos, Essos, Mvan, Nsimeyong, Mendong, Emana, Odza</p>
                  </div>
                </div>
                
                <div class="transport-feature-item">
                  <div class="transport-feature-icon">
                    <i class="fas fa-clock"></i>
                  </div>
                  <div class="transport-feature-content">
                    <h4>Horaires Flexibles</h4>
                    <p>Ramassage : 6h30-7h30 | Retour : 15h30-17h00</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-4">
                <a href="contact.html" class="vs-btn me-3">
                  <span class="vs-btn__border"></span>
                  Réserver le bus
                </a>
                <a href="tel:+237657316683" class="vs-btn style2">
                  <span class="vs-btn__border"></span>
                  <i class="fas fa-phone-alt me-2"></i> Appeler
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Espaces de Jeux -->
    <section class="space position-relative">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="vs-title title-anime animation-style2 text-center mb-50">
              <div class="title-anime__wrap">
                <span class="vs-title__sub">Infrastructures Ludiques</span>
                <h2 class="vs-title__main">
                  Espaces de <span>Jeux</span> & Loisirs
                </h2>
                <p class="mt-3">Des aires de jeux modernes et sécurisées pour l'épanouissement de vos enfants</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row gy-4">
          <!-- Aire de jeux maternelle -->
          <div class="col-lg-4 col-md-6">
            <div class="playground-card wow animate__fadeInUp" data-wow-delay="0.25s">
              <div class="playground-card__image">
                <img src="{{asset('images/air.jpeg')}}" alt="Aire de jeux maternelle" class="img-fluid">
                <div class="playground-card__overlay">
                  <span class="playground-card__age">3-6 ans</span>
                </div>
              </div>
              <div class="playground-card__content">
                <h3 class="playground-card__title">
                  <i class="fas fa-baby-carriage"></i> Aire Maternelle
                </h3>
                <p class="playground-card__desc">
                  Espace sécurisé avec sol amortissant, toboggans adaptés, balançoires, maisons de jeux et bac à sable.
                </p>
                <ul class="playground-card__features">
                  <li><i class="fas fa-check-circle"></i> Sol souple anti-choc</li>
                  <li><i class="fas fa-check-circle"></i> Équipements adaptés</li>
                  <li><i class="fas fa-check-circle"></i> Surveillance permanente</li>
                </ul>
              </div>
            </div>
          </div>
          
          <!-- Terrain de sport -->
          <div class="col-lg-4 col-md-6">
            <div class="playground-card wow animate__fadeInUp" data-wow-delay="0.35s">
              <div class="playground-card__image">
                <img src="{{asset('images/terrain.jpeg')}}" alt="Terrain de sport" class="img-fluid">
                <div class="playground-card__overlay">
                  <span class="playground-card__age">6-12 ans</span>
                </div>
              </div>
              <div class="playground-card__content">
                <h3 class="playground-card__title">
                  <i class="fas fa-futbol"></i> Terrain Multisports
                </h3>
                <p class="playground-card__desc">
                  Grand terrain polyvalent pour football, basketball, volleyball et handball avec équipements professionnels.
                </p>
                <ul class="playground-card__features">
                  <li><i class="fas fa-check-circle"></i> Terrain gazonné synthétique</li>
                  <li><i class="fas fa-check-circle"></i> Paniers de basket</li>
                  <li><i class="fas fa-check-circle"></i> Buts de football</li>
                </ul>
              </div>
            </div>
          </div>
          
          <!-- Jardin pédagogique -->
          <div class="col-lg-4 col-md-6">
            <div class="playground-card wow animate__fadeInUp" data-wow-delay="0.45s">
              <div class="playground-card__image">
                <img src="{{asset('images/jardin.jpeg')}}" alt="Jardin pédagogique" class="img-fluid">
                <div class="playground-card__overlay">
                  <span class="playground-card__age">Tous âges</span>
                </div>
              </div>
              <div class="playground-card__content">
                <h3 class="playground-card__title">
                  <i class="fas fa-seedling"></i> Jardin Pédagogique
                </h3>
                <p class="playground-card__desc">
                  Espace vert aménagé pour l'éveil écologique : potager, arbres fruitiers, observation de la nature.
                </p>
                <ul class="playground-card__features">
                  <li><i class="fas fa-check-circle"></i> Potager bio</li>
                  <li><i class="fas fa-check-circle"></i> Arbres fruitiers</li>
                  <li><i class="fas fa-check-circle"></i> Zone de repos ombragée</li>
                </ul>
              </div>
            </div>
          </div>
          
         
          
          <!-- Espace artistique -->
          <div class="col-lg-4 col-md-6">
            <div class="playground-card wow animate__fadeInUp" data-wow-delay="0.35s">
              <div class="playground-card__image">
                <img src="{{asset('images/atelier.jpeg')}}" alt="Espace artistique" class="img-fluid">
                <div class="playground-card__overlay">
                  <span class="playground-card__age">3-12 ans</span>
                </div>
              </div>
              <div class="playground-card__content">
                <h3 class="playground-card__title">
                  <i class="fas fa-palette"></i> Atelier d'Arts Créatifs
                </h3>
                <p class="playground-card__desc">
                  Studio dédié aux activités artistiques : peinture, dessin, modelage, artisanat et travaux manuels.
                </p>
                <ul class="playground-card__features">
                  <li><i class="fas fa-check-circle"></i> Matériel artistique complet</li>
                  <li><i class="fas fa-check-circle"></i> Tables adaptées</li>
                  <li><i class="fas fa-check-circle"></i> Exposition des œuvres</li>
                </ul>
              </div>
            </div>
          </div>
       
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="space bg-white">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-lg-3 col-md-6 mb-30">
            <div class="counter-card">
              <div class="counter-card__icon">
                <i class="fas fa-users fa-3x text-theme-color1"></i>
              </div>
              <div class="counter-card__content">
                <h2 class="counter-card__number">2000+</h2>
                <p class="counter-card__text">Élèves formés</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-30">
            <div class="counter-card">
              <div class="counter-card__icon">
                <i class="fas fa-chalkboard-teacher fa-3x text-theme-color2"></i>
              </div>
              <div class="counter-card__content">
                <h2 class="counter-card__number">45+</h2>
                <p class="counter-card__text">Enseignants qualifiés</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-30">
            <div class="counter-card">
              <div class="counter-card__icon">
                <i class="fas fa-trophy fa-3x text-theme-color3"></i>
              </div>
              <div class="counter-card__content">
                <h2 class="counter-card__number">98%</h2>
                <p class="counter-card__text">Taux de réussite</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-30">
            <div class="counter-card">
              <div class="counter-card__icon">
                <i class="fas fa-award fa-3x text-theme-color4"></i>
              </div>
              <div class="counter-card__content">
                <h2 class="counter-card__number">15+</h2>
                <p class="counter-card__text">Années d'expérience</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="gallery-section space position-relative">
  <div class="container">
    <!-- Section Title -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="vs-title title-anime animation-style2 text-center mb-50">
          <div class="title-anime__wrap">
            <span class="vs-title__sub">Notre École en Images</span>
            <h2 class="vs-title__main">
              Galerie <span>Photos</span>
            </h2>
            <p class="mt-3">Découvrez la vie quotidienne à GSBM La Majestueuse à travers nos installations et activités</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery Filter -->
    <div class="gallery-filter">
      <button class="gallery-filter__btn active" data-filter="all">
        <i class="fas fa-th me-2"></i> Tout Voir
      </button>
      <button class="gallery-filter__btn" data-filter="classes">
        <i class="fas fa-chalkboard me-2"></i> Salles de Classe
      </button>
      <button class="gallery-filter__btn" data-filter="activities">
        <i class="fas fa-running me-2"></i> Activités
      </button>
      <button class="gallery-filter__btn" data-filter="events">
        <i class="fas fa-calendar-alt me-2"></i> Événements
      </button>
      <button class="gallery-filter__btn" data-filter="facilities">
        <i class="fas fa-building me-2"></i> Infrastructures
      </button>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid">
      <!-- Item 1 - Salle de classe -->
      <div class="gallery-item" data-category="classes">
        <img src="{{asset('images/classe.jpeg')}}" alt="Salle de classe moderne" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Classes</span>
          <h4 class="gallery-item__title">Salle de Classe Moderne</h4>
          <p class="gallery-item__description">Environnement d'apprentissage climatisé et équipé avec tableau interactif</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 2 - Aire de jeux -->
      <div class="gallery-item" data-category="activities">
        <img src="{{asset('images/air.jpeg')}}" alt="Aire de jeux maternelle" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Activités</span>
          <h4 class="gallery-item__title">Aire de Jeux Maternelle</h4>
          <p class="gallery-item__description">Espace sécurisé avec sol amortissant pour le développement moteur des enfants</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 3 - Terrain de sport -->
      <div class="gallery-item" data-category="activities">
        <img src="{{asset('images/terrain.jpeg')}}" alt="Terrain multisports" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Activités</span>
          <h4 class="gallery-item__title">Terrain Multisports</h4>
          <p class="gallery-item__description">Terrain polyvalent pour football, basketball et autres activités sportives</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 4 - Jardin pédagogique -->
      <div class="gallery-item" data-category="facilities">
        <img src="{{asset('images/jardin.jpeg')}}" alt="Jardin pédagogique" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Infrastructures</span>
          <h4 class="gallery-item__title">Jardin Pédagogique</h4>
          <p class="gallery-item__description">Espace vert dédié à l'éveil écologique et à l'apprentissage de la nature</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 5 - Atelier d'arts -->
      <div class="gallery-item" data-category="activities">
        <img src="{{asset('images/atelier.jpeg')}}" alt="Atelier d'arts créatifs" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Activités</span>
          <h4 class="gallery-item__title">Atelier d'Arts Créatifs</h4>
          <p class="gallery-item__description">Studio équipé pour peinture, dessin, modelage et travaux manuels</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 6 - Bus scolaire -->
      <div class="gallery-item" data-category="facilities">
        <img src="{{asset('images/car.jpeg')}}" alt="Transport scolaire" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Infrastructures</span>
          <h4 class="gallery-item__title">Transport Scolaire</h4>
          <p class="gallery-item__description">Flotte de bus modernes et sécurisés couvrant plusieurs quartiers de Yaoundé</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 7 - Événement 1 -->
      <div class="gallery-item" data-category="events">
        <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-1.jpg')}}" alt="Cérémonie de rentrée" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Événements</span>
          <h4 class="gallery-item__title">Cérémonie de Rentrée</h4>
          <p class="gallery-item__description">Moment festif marquant le début d'une nouvelle année scolaire pleine de promesses</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 8 - Événement 2 -->
      <div class="gallery-item" data-category="events">
        <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-2.jpg')}}" alt="Journée portes ouvertes" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Événements</span>
          <h4 class="gallery-item__title">Journée Portes Ouvertes</h4>
          <p class="gallery-item__description">Les parents découvrent nos installations et rencontrent l'équipe pédagogique</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 9 - Classe en action -->
      <div class="gallery-item" data-category="classes">
        <img src="{{asset('images/classe2.jpeg')}}" alt="Cours de lecture" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Classes</span>
          <h4 class="gallery-item__title">Cours de Lecture Bilingue</h4>
          <p class="gallery-item__description">Apprentissage interactif en français et anglais avec méthodes pédagogiques modernes</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 10 - Bibliothèque -->
      <div class="gallery-item" data-category="facilities">
        <img src="{{asset('images/infracstructure.jpeg')}}" alt="Bibliothèque" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Infrastructures</span>
          <h4 class="gallery-item__title">Bibliothèque Scolaire</h4>
          <p class="gallery-item__description">Espace de lecture calme avec une riche collection de livres bilingues</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 11 - Activité sportive -->
      <div class="gallery-item" data-category="activities">
        <img src="{{asset('images/activite.jpeg')}}" alt="Activité sportive" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Activités</span>
          <h4 class="gallery-item__title">Cours d'Éducation Physique</h4>
          <p class="gallery-item__description">Développement physique et esprit d'équipe à travers le sport</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>

      <!-- Item 12 - Événement festif -->
      <div class="gallery-item" data-category="events">
        <img src="{{asset('asset_vitrine/assets/img/about/vs-about-h1-1.jpg')}}" alt="Fête de fin d'année" class="gallery-item__image">
        <div class="gallery-item__overlay">
          <span class="gallery-item__category">Événements</span>
          <h4 class="gallery-item__title">Fête de Fin d'Année</h4>
          <p class="gallery-item__description">Spectacles, jeux et célébration des réussites de nos élèves</p>
        </div>
        <div class="gallery-item__icon">
          <i class="fas fa-search-plus"></i>
        </div>
      </div>
    </div>

    <!-- View More Button -->
    <!-- <div class="gallery-view-more">
      <a href="gallery.html" class="vs-btn">
        <span class="vs-btn__border"></span>
        <i class="fas fa-images me-2"></i> Voir Toute la Galerie
      </a>
    </div> -->
  </div>
</section>

    <!-- Admission Form Section -->
    <section class="bg-title z-index-common space overflow-hidden parallax-wrap">
      <img src="{{asset('asset_vitrine/assets/img/elements/sec-divider-ele1.svg')}}" alt="diviseur" class="sec-ele1 position-absolute start-0 end-0 bottom-0 z-index-n2">
      <div class="feedback-image wow animate__fadeInLeft" data-wow-delay="0.25s" data-bg-src="{{asset('asset_vitrine/assets/img/feedback/feedback-image-h2-1.jpg')}}"></div>
      <div class="feedback-image--right" data-bg-src="{{asset('asset_vitrine/assets/img/feedback/feedback-image-1-2.png')}}"></div>
      <img class="vs-contact--ele1 parallax-element" data-move="80" src="{{asset('asset_vitrine/assets/img/contact/vs-cta-ele1.png')}}" alt="élément">
      <img class="vs-contact--ele2 parallax-element" data-move="50" src="{{asset('asset_vitrine/assets/img/contact/vs-cta-ele2.png')}}" alt="élément">
      <img class="vs-contact--ele3 parallax-element" data-move="20" src="{{asset('asset_vitrine/assets/img/contact/vs-cta-ele3.png')}}" alt="élément">
      <div class="container">
        <div class="row justify-content-end">
          <div class="col-lg-7">
            <div class="feedback-wrapper admission-form wow animate__fadeInUp" data-wow-delay="0.25s">
              <div class="vs-title vs-title--style2 title-anime animation-style2">
                <div class="title-anime__wrap">
                  <span class="vs-title__sub">Inscription</span>
                  <h2 class="vs-title__main pe-xl-3">
                    Demande <span>d'admission</span>
                  </h2>
                  <p class="mt-3">Inscrivez votre enfant dès maintenant et bénéficiez d'un mois d'essai gratuit !</p>
                </div>
              </div>
              <form action="mail.php" method="post" class="form-style ajax-contact">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <input name="child_name" type="text" class="form-control" placeholder="Nom complet de l'enfant *" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <input name="birth_date" type="text" class="form-control" placeholder="Date de naissance (JJ/MM/AAAA) *" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <input name="parent_name" type="text" class="form-control" placeholder="Nom du parent/tuteur *" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <select name="grade" class="form-control" required>
                      <option value="">Sélectionner le niveau *</option>
                      <option value="petite-section">Petite Section (3-4 ans)</option>
                      <option value="moyenne-section">Moyenne Section (4-5 ans)</option>
                      <option value="grande-section">Grande Section (5-6 ans)</option>
                      <option value="class-1">Class 1 (6-7 ans)</option>
                      <option value="class-2">Class 2 (7-8 ans)</option>
                      <option value="class-3">Class 3 (8-9 ans)</option>
                      <option value="class-4">Class 4 (9-10 ans)</option>
                      <option value="class-5">Class 5 (10-11 ans)</option>
                      <option value="class-6">Class 6 (11-12 ans)</option>
                    </select>
                  </div>
                  <div class="col-md-12 form-group">
                    <input name="address" type="text" class="form-control" placeholder="Adresse complète *" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <input name="email" type="email" class="form-control" placeholder="Votre email *" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <input name="phone" type="tel" class="form-control" placeholder="Numéro de téléphone *" required>
                  </div>
                  <div class="col-md-12 form-group">
                    <textarea name="message" class="form-control" rows="3" placeholder="Message ou informations supplémentaires (optionnel)"></textarea>
                  </div>
                  <div class="col-md-12 form-group">
                    <div class="vs-custom-checkbox">
                      <input type="checkbox" name="newsletter" id="newsletter">
                      <label for="newsletter">Je souhaite recevoir les newsletters et actualités de l'école</label>
                    </div>
                  </div>
                  <div class="col-md-12 form-group">
                    <div class="vs-custom-checkbox">
                      <input type="checkbox" name="notify" id="notify">
                      <label for="notify">Recevoir des notifications hebdomadaires sur les progrès de mon enfant</label>
                    </div>
                  </div>
                  <div class="col-12 form-group mb-0">
                    <button class="vs-btn" type="submit">
                      <span class="vs-btn__border"></span>
                      Soumettre la demande
                    </button>
                  </div>
                </div>
              </form>
              <p class="form-messages my-3"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="vs-balls vs-balls--screen" data-balls-top="-6px" data-balls-color="#FFEFE4"></div>
      <div class="vs-balls vs-balls--screen" data-balls-bottm="-6px" data-balls-color="#F8F8F8"></div>
    </section>

    <!-- Testimonials Section -->
    <section class="vs-client--area space space-extra-bottom bg-theme-color-1 z-index-common">
      <div class="vs-session--bg-image" data-bg-src="{{asset('asset_vitrine/assets/img/client/client-bg.png')}}"></div>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="vs-title text-center title-anime animation-style2">
              <div class="title-anime__wrap">
                <span class="vs-title__sub text-white">Témoignages</span>
                <h2 class="vs-title__main text-white px-xl-5">Ce que disent nos parents</h2>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper vs-client--slider">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="vs-client">
                <img class="vs-session__bg" src="{{asset('asset_vitrine/assets/img/client/vs-client-bg.png')}}" alt="témoignage">
                <div class="vs-client__content">
                  <p class="vs-client__desc">
                    « GSBM La Majestueuse a transformé l'expérience éducative de ma fille. Le programme bilingue est excellent et les enseignants sont dévoués. Je recommande vivement cette école ! »
                  </p>
                  <h4 class="vs-client__title">Mme Pascaline Mbarga</h4>
                  <span class="vs-client__title--sub">Mère d'élève - Class 3</span>
                  <div class="vs-client__quote">
                    <img src="{{asset('asset_vitrine/assets/img/icons/svg-quote-icon.svg')}}" alt="citation">
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="vs-client">
                <img class="vs-session__bg" src="{{asset('asset_vitrine/assets/img/client/vs-client-bg.png')}}" alt="témoignage">
                <div class="vs-client__content">
                  <p class="vs-client__desc">
                    « Mon fils est épanoui depuis qu'il fréquente GSBM. L'environnement est sécurisé, les infrastructures modernes et le suivi pédagogique exceptionnel. Bravo à toute l'équipe ! »
                  </p>
                  <h4 class="vs-client__title">M. Éric Fouda</h4>
                  <span class="vs-client__title--sub">Père d'élève - Grande Section</span>
                  <div class="vs-client__quote">
                    <img src="{{asset('asset_vitrine/assets/img/icons/svg-quote-icon.svg')}}" alt="citation">
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="vs-client">
                <img class="vs-session__bg" src="{{asset('asset_vitrine/assets/img/client/vs-client-bg.png')}}" alt="témoignage">
                <div class="vs-client__content">
                  <p class="vs-client__desc">
                    « Une école qui allie rigueur académique et épanouissement personnel. Mes enfants adorent aller à l'école et leurs progrès en anglais et français sont remarquables. »
                  </p>
                  <h4 class="vs-client__title">Mme Clarisse Ngo Bisseck</h4>
                  <span class="vs-client__title--sub">Mère de 2 élèves - Class 2 et 5</span>
                  <div class="vs-client__quote">
                    <img src="{{asset('asset_vitrine/assets/img/icons/svg-quote-icon.svg')}}" alt="citation">
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="vs-client">
                <img class="vs-session__bg" src="{{asset('asset_vitrine/assets/img/client/vs-client-bg.png')}}" alt="témoignage">
                <div class="vs-client__content">
                  <p class="vs-client__desc">
                    « La communication avec les parents est excellente. Nous recevons régulièrement des rapports sur les progrès de notre enfant. Une école que je recommande sans hésitation. »
                  </p>
                  <h4 class="vs-client__title">M. Samuel Tabe</h4>
                  <span class="vs-client__title--sub">Père d'élève - Moyenne Section</span>
                  <div class="vs-client__quote">
                    <img src="{{asset('asset_vitrine/assets/img/icons/svg-quote-icon.svg')}}" alt="citation">
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="vs-client">
                <img class="vs-session__bg" src="{{asset('asset_vitrine/assets/img/client/vs-client-bg.png')}}" alt="témoignage">
                <div class="vs-client__content">
                  <p class="vs-client__desc">
                    « Les activités parascolaires sont variées et enrichissantes. Mon fils a découvert sa passion pour le football et l'informatique grâce aux clubs de l'école. Merci GSBM ! »
                  </p>
                  <h4 class="vs-client__title">Mme Josephine Ekomo</h4>
                  <span class="vs-client__title--sub">Mère d'élève - Class 4</span>
                  <div class="vs-client__quote">
                    <img src="{{asset('asset_vitrine/assets/img/icons/svg-quote-icon.svg')}}" alt="citation">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Blog Section -->
    <section class="space space-extra-bottom z-index-common" data-bg-src="{{asset('asset_vitrine/assets/img/blog/h1-bg-blog.png')}}">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 mx-auto">
            <div class="vs-title title-anime animation-style2 text-center">
              <div class="title-anime__wrap">
                <span class="vs-title__sub">Actualités GSBM</span>
                <h2 class="vs-title__main">
                  Dernières <span>Nouvelles</span> & Événements
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div class="row vs-carousel swiper" data-xl="3" data-autoplay="false">
          <div class="swiper-wrapper">
            <div class="col-lg-4 swiper-slide">
              <div class="vs-blog vs-blog--style2">
                <div class="vs-blog__inner">
                  <div class="vs-blog__img">
                    <a href="blog-details.html">
                      <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-1.jpg')}}" alt="Actualité" loading="lazy">
                    </a>
                  </div>
                  <div class="vs-blog__content">
                    <div class="vs-blog__meta">
                      <a class="vs-blog__meta--link" href="#">
                        <i class="fa-regular fa-calendar-days"></i>15 Janvier 2025
                      </a>
                    </div>
                    <a class="vs-blog__heading--link" href="blog-details.html">
                      <h3 class="vs-blog__heading">Rentrée scolaire 2025 : Nouvelles classes</h3>
                    </a>
                    <p class="vs-blog__desc">
                      GSBM La Majestueuse annonce l'ouverture de nouvelles classes pour la rentrée 2025 avec des équipements modernes et un programme enrichi.
                    </p>
                    <div class="vs-blog__bottom">
                      <a href="blog-details.html" class="vs-blog__link">
                        Lire plus<i class="fa-solid fa-arrow-right"></i>
                      </a>
                      <div class="vs-blog__share">
                        <ul>
                          <li>
                            <a href="javascript:void(0)"><i class="fa-solid fa-share-nodes"></i></a>
                            <ul>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 swiper-slide">
              <div class="vs-blog vs-blog--style2">
                <div class="vs-blog__inner">
                  <div class="vs-blog__img">
                    <a href="blog-details.html">
                      <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-2.jpg')}}" alt="Actualité" loading="lazy">
                    </a>
                  </div>
                  <div class="vs-blog__content">
                    <div class="vs-blog__meta">
                      <a class="vs-blog__meta--link" href="#">
                        <i class="fa-regular fa-calendar-days"></i>08 Janvier 2025
                      </a>
                    </div>
                    <a class="vs-blog__heading--link" href="blog-details.html">
                      <h3 class="vs-blog__heading">Excellence : 98% de réussite aux examens</h3>
                    </a>
                    <p class="vs-blog__desc">
                      Nos élèves ont brillé aux examens de fin d'année 2024 avec un taux de réussite exceptionnel de 98%. Félicitations à tous !
                    </p>
                    <div class="vs-blog__bottom">
                      <a href="blog-details.html" class="vs-blog__link">
                        Lire plus<i class="fa-solid fa-arrow-right"></i>
                      </a>
                      <div class="vs-blog__share">
                        <ul>
                          <li>
                            <a href="javascript:void(0)"><i class="fa-solid fa-share-nodes"></i></a>
                            <ul>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 swiper-slide">
              <div class="vs-blog vs-blog--style2">
                <div class="vs-blog__inner">
                  <div class="vs-blog__img">
                    <a href="blog-details.html">
                      <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-3.jpg')}}" alt="Actualité" loading="lazy">
                    </a>
                  </div>
                  <div class="vs-blog__content">
                    <div class="vs-blog__meta">
                      <a class="vs-blog__meta--link" href="#">
                        <i class="fa-regular fa-calendar-days"></i>20 Décembre 2024
                      </a>
                    </div>
                    <a class="vs-blog__heading--link" href="blog-details.html">
                      <h3 class="vs-blog__heading">Fête de fin d'année : Moments inoubliables</h3>
                    </a>
                    <p class="vs-blog__desc">
                      La fête de fin d'année 2024 a été un grand succès avec des spectacles, des jeux et de nombreuses surprises pour nos élèves.
                    </p>
                    <div class="vs-blog__bottom">
                      <a href="blog-details.html" class="vs-blog__link">
                        Lire plus<i class="fa-solid fa-arrow-right"></i>
                      </a>
                      <div class="vs-blog__share">
                        <ul>
                          <li>
                            <a href="javascript:void(0)"><i class="fa-solid fa-share-nodes"></i></a>
                            <ul>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 swiper-slide">
              <div class="vs-blog vs-blog--style2">
                <div class="vs-blog__inner">
                  <div class="vs-blog__img">
                    <a href="blog-details.html">
                      <img src="{{asset('asset_vitrine/assets/img/blog/blog-h1-4.jpg')}}" alt="Actualité" loading="lazy">
                    </a>
                  </div>
                  <div class="vs-blog__content">
                    <div class="vs-blog__meta">
                      <a class="vs-blog__meta--link" href="#">
                        <i class="fa-regular fa-calendar-days"></i>10 Décembre 2024
                      </a>
                    </div>
                    <a class="vs-blog__heading--link" href="blog-details.html">
                      <h3 class="vs-blog__heading">Nouveau laboratoire informatique inauguré</h3>
                    </a>
                    <p class="vs-blog__desc">
                      GSBM investit dans l'éducation numérique avec l'inauguration d'un laboratoire informatique équipé de 30 ordinateurs modernes.
                    </p>
                    <div class="vs-blog__bottom">
                      <a href="blog-details.html" class="vs-blog__link">
                        Lire plus<i class="fa-solid fa-arrow-right"></i>
                      </a>
                      <div class="vs-blog__share">
                        <ul>
                          <li>
                            <a href="javascript:void(0)"><i class="fa-solid fa-share-nodes"></i></a>
                            <ul>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                              <li><a href="#" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Localisation Section -->
    <section class="space-extra bg-theme-color-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="vs-title title-anime animation-style2 text-center mb-50">
              <div class="title-anime__wrap">
                <span class="vs-title__sub">Nous Trouver</span>
                <h2 class="vs-title__main">
                  Notre <span>Localisation</span>
                </h2>
                <p class="mt-3">Venez nous rendre visite à MBankomo, Yaoundé</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row align-items-center gy-4">
          <!-- Informations de contact -->
          <div class="col-lg-4">
            <div class="location-info-wrapper">
              <div class="location-info-card wow animate__fadeInLeft" data-wow-delay="0.25s">
                <div class="location-info-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="location-info-content">
                  <h4>Adresse</h4>
                  <p>MBankomo, Ndazoa<br>Yaoundé, Cameroun</p>
                </div>
              </div>
              
              <div class="location-info-card wow animate__fadeInLeft" data-wow-delay="0.35s">
                <div class="location-info-icon">
                  <i class="fas fa-phone-alt"></i>
                </div>
                <div class="location-info-content">
                  <h4>Téléphone</h4>
                  <p>
                    <a href="tel:+237657316683">+237 657 316 683</a><br>
                    <a href="tel:+237699123456">+237 699 123 456</a>
                  </p>
                </div>
              </div>
              
              <div class="location-info-card wow animate__fadeInLeft" data-wow-delay="0.45s">
                <div class="location-info-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div class="location-info-content">
                  <h4>Email</h4>
                  <p>
                    <a href="/cdn-cgi/l/email-protection#5d34333b321d3a2e3f307033393c27323c733e3230"><span class="__cf_email__" data-cfemail="f69f989099b69185949bdb9892978c9997d895999b">info@gsbm-ndazoa.com</span></a><br>
                  </p>
                </div>
              </div>
              
              <div class="location-info-card wow animate__fadeInLeft" data-wow-delay="0.55s">
                <div class="location-info-icon">
                  <i class="fas fa-clock"></i>
                </div>
                <div class="location-info-content">
                  <h4>Horaires</h4>
                  <p>
                    Lun - Ven : 8h00 - 15h00<br>
                  
                  </p>
                </div>
              </div>
              
              <div class="mt-4">
                <a href="https://maps.google.com/?q=Bankomo+Yaoundé+Cameroun" target="_blank" class="vs-btn w-100 text-center">
                  <span class="vs-btn__border"></span>
                  <i class="fas fa-directions me-2"></i> Obtenir l'itinéraire
                </a>
              </div>
            </div>
          </div>
          
          <!-- Carte Google Maps -->
          <div class="col-lg-8">
            <div class="map-container wow animate__fadeInRight" data-wow-delay="0.35s">
              <div class="map-wrapper">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3980.8337736851733!2d11.516666!3d3.8666667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM8KwNTInMDAuMCJOIDExwrAzMScwMC4wIkU!5e0!3m2!1sfr!2scm!4v1234567890123!5m2!1sfr!2scm" 
                  width="100%" 
                  height="500" 
                  style="border:0; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>
              <div class="map-overlay-info">
                <div class="map-badge">
                  <i class="fas fa-school"></i>
                  <span>GSBM La Majestueuse</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Points de repère -->
        <div class="row mt-5">
          <div class="col-12">
            <div class="landmark-section">
              <h4 class="landmark-title text-center mb-4">
                <i class="fas fa-location-arrow"></i> Points de Repère
              </h4>
              <div class="row gy-3">
                <div class="col-lg-3 col-md-6">
                  <div class="landmark-card">
                    <i class="fas fa-road"></i>
                    <span>À 500m de la route principale</span>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="landmark-card">
                    <i class="fas fa-church"></i>
                    <span>Face à l'Église Catholique</span>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="landmark-card">
                    <i class="fas fa-hospital"></i>
                    <span>Près du Centre de Santé</span>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="landmark-card">
                    <i class="fas fa-store"></i>
                    <span>Après le marché Ndazoa</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <div class="vs-footer bg-title">
    <div class="vs-footer__bottom bg-theme-color-1">
      <div class="container">
        <div class="row gy-3 gx-5 align-items-center justify-content-center justify-content-lg-between flex-column-reverse flex-lg-row">
          <div class="col-md-auto">
            <p class="vs-footer__copyright mb-0">
              Copyright © <span id="currentYear">2025</span>
              <a href="index.html">GSBM LA Majestueuse</a>. Tous droits réservés.
            </p>
          </div>
          <div class="col-md-auto">
            <ul class="vs-footer__bottom--menu">
              <li><a href="about.html">Conditions générales</a></li>
              <li><a href="about.html">Politique de confidentialité</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Back To Top -->
  <button class="back-to-top" id="backToTop" aria-label="Retour en haut">
    <span class="progress-circle">
      <svg viewBox="0 0 100 100">
        <circle class="bg" cx="50" cy="50" r="40"></circle>
        <circle class="progress" cx="50" cy="50" r="40"></circle>
      </svg>
      <span class="progress-percentage" id="progressPercentage">0%</span>
    </span>
  </button>

  <!-- JavaScript Files -->
  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="{{asset('asset_vitrine/assets/js/vendor/jquery-3.7.1.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/wow.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/jquery.magnific-popup.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/gsap.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/ScrollTrigger.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/gsap-scroll-to-plugin.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/SplitText.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/lenis.min.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/swiper-bundle.js')}}"></script>
  <script src="{{asset('asset_vitrine/assets/js/main.js')}}"></script>
  
  <script>
    // Update current year
    document.getElementById('currentYear').textContent = new Date().getFullYear();
  </script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const filterBtns = document.querySelectorAll('.gallery-filter__btn');
  const galleryItems = document.querySelectorAll('.gallery-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      // Retirer la classe active de tous les boutons
      filterBtns.forEach(b => b.classList.remove('active'));
      // Ajouter la classe active au bouton cliqué
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      galleryItems.forEach((item, index) => {
        if (filter === 'all' || item.getAttribute('data-category') === filter) {
          item.style.display = 'block';
          item.style.animation = 'none';
          setTimeout(() => {
            item.style.animation = `fadeInUp 0.6s ease forwards`;
            item.style.animationDelay = `${index * 0.1}s`;
          }, 10);
        } else {
          item.style.opacity = '0';
          item.style.transform = 'scale(0.8)';
          setTimeout(() => {
            item.style.display = 'none';
          }, 300);
        }
      });
    });
  });

  // Animation au scroll (optionnel)
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
      }
    });
  }, observerOptions);

  galleryItems.forEach(item => {
    observer.observe(item);
  });
});
</script>
</body>
</html>