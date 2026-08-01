<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MyPapyrus– Archivage Numérique Sécurisé</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
      --orange: #F06A1D;
      --orange-light: #FF8C42;
      --teal: #1B5E57;
      --teal-dark: #0E3D38;
      --teal-light: #2D7A70;
      --cream: #FBF7F2;
      --warm-white: #FFFDF9;
      --text-dark: #1A1A1A;
      --text-muted: #6B7280;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--warm-white);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* ── TOPBAR ── */
    .topbar {
      background: var(--teal-dark);
      color: #fff;
      font-size: 0.78rem;
      padding: 8px 0;
      letter-spacing: 0.02em;
      max-width: 1250px;
      border-radius: 0 0 8px 8px;
      margin: auto;
    }
    .topbar a { color: #cce8e5; text-decoration: none; }
    .topbar a:hover { color: var(--orange-light); }
    .topbar .social-icons a {
      display: inline-flex; align-items: center; justify-content: center;
      width: 26px; height: 26px; border-radius: 50%;
      background: rgba(255,255,255,0.1);
      color: #fff; margin-left: 5px; transition: background 0.2s;
    }
    .topbar .social-icons a:hover { background: var(--orange); }

    /* ── NAVBAR ── */
    .navbar {
      background: var(--warm-white);
      padding: 14px 0;
      position: sticky; top: 0; z-index: 1000;
    }
    .navbar-brand { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 900; color: var(--teal-dark) !important; }
    .navbar-brand span { color: var(--orange); }
    .navbar-brand .brand-heart { color: var(--orange); margin-right: 8px; }
    .nav-link { color: var(--text-dark) !important; font-weight: 500; font-size: 0.9rem; padding: 6px 14px !important; border-radius: 6px; transition: color 0.2s; }
    .nav-link:hover, .nav-link.active { color: var(--orange) !important; }
    .btn-donate {
      background: var(--orange);
      color: #fff !important;
      border-radius: 50px;
      padding: 10px 26px !important;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 0.05em;
      transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 4px 14px rgba(240,106,29,0.35);
    }
    .btn-donate:hover { background: var(--orange-light); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(240,106,29,0.45); }

    /* ── HERO ── */
    .hero {
      background: linear-gradient(135deg, var(--teal-dark) 0%, var(--teal) 55%, #1e7068 100%);
      min-height: 88vh;
      position: relative;
      overflow: hidden;
      display: flex; align-items: center;
      max-width: 1250px;
      border-radius: 11px;
      margin: auto;
    }
    .hero::before {
      content: '';
      position: absolute; inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-blob {
      position: absolute; right: -60px; top: 50%; transform: translateY(-50%);
      width: 55%; max-width: 680px; height: 105%;
      border-radius: 40% 0 0 40%;
      overflow: hidden;
    }
    .hero-blob img {
      width: 100%; height: 100%;
      object-fit: cover; object-position: center;
      opacity: 0.85;
      mix-blend-mode: luminosity;
      filter: sepia(10%) saturate(90%);
    }
    .hero-blob::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(90deg, var(--teal) 0%, transparent 40%);
    }
    .hero-content { position: relative; z-index: 2; }
    .hero-eyebrow {
      font-size: 0.78rem; font-weight: 600; letter-spacing: 0.18em;
      color: var(--orange-light); text-transform: uppercase; margin-bottom: 18px;
      display: flex; align-items: center; gap: 8px;
    }
    .hero-eyebrow::before { content: ''; display: inline-block; width: 28px; height: 2px; background: var(--orange-light); }
    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.6rem, 5vw, 4rem);
      font-weight: 900;
      color: #fff;
      line-height: 1.12;
      margin-bottom: 22px;
    }
    .hero h1 em { font-style: normal; color: var(--orange-light); }
    .hero p { color: rgba(255,255,255,0.75); font-size: 1rem; line-height: 1.7; max-width: 420px; margin-bottom: 36px; }
    .btn-hero-primary {
      background: var(--orange);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 14px 34px;
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 0.04em;
      text-decoration: none;
      transition: all 0.25s;
      box-shadow: 0 6px 24px rgba(240,106,29,0.4);
    }
    .btn-hero-primary:hover { background: var(--orange-light); transform: translateY(-2px); color: #fff; }
    .btn-hero-ghost {
      background: transparent;
      color: #fff;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50px;
      padding: 12px 30px;
      font-weight: 500;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.25s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-hero-ghost:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,0.08); }
    .hero-stats {
      display: flex; gap: 36px; margin-top: 52px;
      padding-top: 32px;
      border-top: 1px solid rgba(255,255,255,0.12);
    }
    .hero-stat-value { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: #fff; }
    .hero-stat-label { font-size: 0.78rem; color: rgba(255,255,255,0.6); letter-spacing: 0.05em; }

    /* ── TRUST BAR ── */
    .trust-bar {
      background: #fff;
      padding: 32px 0;
      border-bottom: 1px solid #f0ece6;
    }
    .trust-item {
      display: flex; align-items: center; gap: 14px;
      padding: 10px 20px;
      border-right: 1px solid #f0ece6;
    }
    .trust-item:last-child { border-right: none; }
    .trust-icon {
      width: 48px; height: 48px; border-radius: 14px;
      background: linear-gradient(135deg, #fff5ee, #ffe8d6);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; color: var(--orange); flex-shrink: 0;
    }
    .trust-title { font-weight: 700; font-size: 0.95rem; color: var(--text-dark); }
    .trust-sub { font-size: 0.8rem; color: var(--text-muted); }

    /* ── SECTIONS ── */
    .section-label {
      font-size: 0.75rem; font-weight: 700; letter-spacing: 0.2em;
      color: var(--orange); text-transform: uppercase; margin-bottom: 10px;
    }
    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3.5vw, 2.6rem);
      font-weight: 900; color: var(--text-dark); line-height: 1.2;
    }

    .services-section { background: var(--cream); padding: 90px 0; }
    .service-card {
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(0,0,0,0.06);
      transition: transform 0.3s, box-shadow 0.3s;
      height: 100%;
    }
    .service-card:hover { transform: translateY(-6px); box-shadow: 0 12px 36px rgba(0,0,0,0.12); }
    .service-card-img {
      position: relative;
      height: 220px; overflow: hidden;
    }
    .service-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
    .service-card:hover .service-card-img img { transform: scale(1.05); }
    .service-icon-badge {
      position: absolute; bottom: -22px; left: 24px;
      width: 46px; height: 46px; border-radius: 50%;
      background: var(--orange);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 1.1rem;
      box-shadow: 0 4px 14px rgba(240,106,29,0.4);
      border: 3px solid #fff;
    }
    .service-card-body { padding: 38px 24px 28px; }
    .service-card-body h5 { font-weight: 700; font-size: 1.05rem; margin-bottom: 10px; }
    .service-card-body p { color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; }

    .cta-dark-card {
      background: linear-gradient(145deg, var(--teal-dark), var(--teal));
      border-radius: 20px;
      padding: 40px 32px;
      height: 100%;
      display: flex; flex-direction: column; justify-content: space-between;
      position: relative; overflow: hidden;
    }
    .cta-dark-card::before {
      content: '';
      position: absolute; top: -40px; right: -40px;
      width: 160px; height: 160px; border-radius: 50%;
      background: rgba(255,255,255,0.04);
    }
    .cta-dark-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.7rem; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 14px;
    }
    .cta-dark-card p { color: rgba(255,255,255,0.65); font-size: 0.88rem; line-height: 1.6; margin-bottom: 28px; }

    /* ── ABOUT ── */
    .about-section { background: #fff; padding: 100px 0; }
    .about-img-wrap { position: relative; }
    .about-img-wrap img {
      width: 100%; height: 440px; object-fit: cover;
      border-radius: 24px;
    }
    .about-badge {
      position: absolute; bottom: 28px; right: -20px;
      background: var(--orange);
      color: #fff;
      border-radius: 18px;
      padding: 18px 24px;
      box-shadow: 0 8px 28px rgba(240,106,29,0.35);
      text-align: center;
      min-width: 130px;
    }
    .about-badge-num { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; }
    .about-badge-label { font-size: 0.75rem; opacity: 0.9; }
    .feature-pill {
      display: inline-flex; align-items: center; gap: 10px;
      background: var(--cream); border-radius: 50px;
      padding: 10px 18px; margin-bottom: 14px; margin-right: 10px;
    }
    .feature-pill-icon {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--orange);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 0.85rem; flex-shrink: 0;
    }
    .feature-pill span { font-weight: 600; font-size: 0.85rem; }
    .check-list { list-style: none; padding: 0; }
    .check-list li {
      display: flex; align-items: flex-start; gap: 10px;
      font-size: 0.88rem; color: var(--text-muted);
      margin-bottom: 10px;
    }
    .check-list li .bi { color: var(--orange); margin-top: 2px; }
    .btn-teal {
      background: var(--teal);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 13px 32px;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-teal:hover { background: var(--teal-light); color: #fff; transform: translateY(-2px); }

    /* ── SERVICES 2 ── */
    .best-services { background: var(--cream); padding: 90px 0; }
    .service-icon-card {
      background: #fff;
      border-radius: 20px;
      padding: 36px 28px;
      text-align: center;
      height: 100%;
      box-shadow: 0 4px 24px rgba(0,0,0,0.05);
      transition: all 0.3s;
    }
    .service-icon-card:hover { transform: translateY(-6px); box-shadow: 0 12px 36px rgba(0,0,0,0.1); }
    .service-icon-wrap {
      width: 72px; height: 72px; border-radius: 22px;
      background: linear-gradient(135deg, #fff5ee, #ffe0c2);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 20px;
      font-size: 1.7rem; color: var(--orange);
      transition: all 0.3s;
    }
    .service-icon-card:hover .service-icon-wrap {
      background: var(--orange);
      color: #fff;
    }
    .service-icon-card h5 { font-weight: 700; margin-bottom: 10px; }
    .service-icon-card p { color: var(--text-muted); font-size: 0.86rem; line-height: 1.6; }

    /* ── FOOTER ── */
    footer {
      background: var(--teal-dark);
      color: rgba(255,255,255,0.7);
      padding: 70px 0 28px;
    }
    .footer-brand {
      font-family: 'Playfair Display', serif;
      font-size: 1.6rem; font-weight: 900; color: #fff;
    }
    .footer-brand span { color: var(--orange-light); }
    footer p { font-size: 0.85rem; line-height: 1.7; }
    .footer-heading { color: #fff; font-weight: 700; font-size: 0.95rem; margin-bottom: 20px; letter-spacing: 0.04em; }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.87rem; transition: color 0.2s; }
    .footer-links a:hover { color: var(--orange-light); }
    .footer-divider { border-color: rgba(255,255,255,0.1); margin: 40px 0 20px; }
    .footer-social a {
      display: inline-flex; align-items: center; justify-content: center;
      width: 36px; height: 36px; border-radius: 50%;
      background: rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.7);
      text-decoration: none; margin-right: 8px;
      transition: all 0.2s;
    }
    .footer-social a:hover { background: var(--orange); color: #fff; }

    /* ── ANIMATIONS ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.7s ease both; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.22s; }
    .delay-3 { animation-delay: 0.34s; }
    .delay-4 { animation-delay: 0.46s; }

    @media (max-width: 991px) {
      .hero-blob { display: none; }
      .hero h1 { font-size: 2.2rem; }
      .about-badge { right: 0; }
    }
    @media (max-width: 768px) {
      .trust-item { border-right: none; border-bottom: 1px solid #f0ece6; }
    }
  </style>
</head>
<body>

<div class="topbar">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <span><i class="bi bi-shield-lock-fill me-1 text-warning"></i> SÉCURITÉ & CONFIDENTIALITÉ GARANTIES</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="mailto:contact@docurural.ci"><i class="bi bi-envelope me-1"></i>contact@docurural.ci</a>
        <a href="tel:+22500000000"><i class="bi bi-telephone me-1"></i>+225 00 00 00 00</a>
        <div class="social-icons">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="/">
        <img src="logo_p.jpeg" height="90" alt="Paysage rural">
    </a>
    
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <i class="bi bi-list fs-4"></i>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link active" href="#">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Nos Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
      </ul>
      <a href="{{ route('auto-enregistrement.login') }}" class="btn-donate nav-link">Espace Personnel</a>
    </div>
  </div>
</nav>

<section class="hero" id="home">
  <div class="hero-blob">
    <img src="pla.jfif" alt="Paysage rural">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="hero-content">
          
          <h1 class="fade-up delay-1">
            Sécurisez vos <em>Documents</em><br>Protégez votre<br><em>Patrimoine</em>
          </h1>
          <p class="fade-up delay-2">
            Un système d'archivage électronique conçu pour les populations rurales. Conservez vos extraits de naissance, titres fonciers et pièces d'identité en toute sécurité.
          </p>
          <div class="hero-stats fade-up delay-4">
            <div>
              <div class="hero-stat-value">10K+</div>
              <div class="hero-stat-label">DOSSIERS ARCHIVÉS</div>
            </div>
            <div>
              <div class="hero-stat-value">100%</div>
              <div class="hero-stat-label">SÉCURISÉ</div>
            </div>
            <div>
              <div class="hero-stat-value">50+</div>
              <div class="hero-stat-label">VILLAGES COUVERTS</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="trust-bar">
  <div class="container">
    <div class="row g-0">
      <div class="col-md-4">
        <div class="trust-item">
          <div class="trust-icon"><i class="bi bi-fingerprint"></i></div>
          <div>
            <div class="trust-title">Identité Protégée</div>
            <div class="trust-sub">Accès biométrique sécurisé</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="trust-item">
          <div class="trust-icon"><i class="bi bi-geo-alt"></i></div>
          <div>
            <div class="trust-title">Sécurité Foncière</div>
            <div class="trust-sub">Archivez vos droits de terre</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="trust-item">
          <div class="trust-icon"><i class="bi bi-clock-history"></i></div>
          <div>
            <div class="trust-title">Disponibilité 24/7</div>
            <div class="trust-sub">Vos papiers partout, tout le temps</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="services-section" id="causes">
  <div class="container">
    <div class="row mb-5">
      <div class="col-lg-6">
        <div class="section-label">Nos Services</div>
        <h2 class="section-title">Une Solution Adaptée<br>à vos Besoins Réels</h2>
      </div>
      <div class="col-lg-6 d-flex align-items-end">
        <p class="text-muted">Nous facilitons l'accès aux services publics et la préservation de la mémoire communautaire grâce à une gestion documentaire simplifiée.</p>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-card-img">
            <img src="cni.png" alt="État civil">
            <div class="service-icon-badge"><i class="bi bi-person-vcard"></i></div>
          </div>
          <div class="service-card-body">
            <h5>État Civil & Identité</h5>
            <p>Sauvegardez vos extraits de naissance et CNI pour éviter les pertes liées aux sinistres ou au temps.</p>
            <a href="#" class="text-decoration-none" style="color:var(--orange);font-weight:600;font-size:.85rem;">En savoir plus <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-card-img">
            <img src="doc.jfif" alt="Foncier">
            <div class="service-icon-badge"><i class="bi bi-map"></i></div>
          </div>
          <div class="service-card-body">
            <h5>Documents Fonciers</h5>
            <p>Sécurisez vos attestations villageoises et titres de propriété pour garantir vos droits sur vos terres.</p>
            <a href="#" class="text-decoration-none" style="color:var(--orange);font-weight:600;font-size:.85rem;">En savoir plus <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="cta-dark-card">
          <div>
            <h3>Prêt pour la Transition Numérique ?</h3>
            <p>Rejoignez MyPapyruset ne craignez plus jamais de perdre vos documents essentiels.</p>
          </div>
          <a href="#" class="btn-orange-full">CRÉER MON COMPTE <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about-section" id="about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <div class="about-img-wrap">
          <img src="vache.jfif" alt="Travail communautaire">
          <div class="about-badge">
            <div class="about-badge-num">ONG</div>
            <div class="about-badge-label">Partenaire local</div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="section-label">L'ONG FOBMIR à vos côtés</div>
        <h2 class="section-title mb-4">Un Projet pour le<br>Développement Local</h2>
        <p class="text-muted mb-4" style="line-height:1.8">
          Le Forum de Bienfaisance en Milieu Rural (FOBMIR) s'engage à résoudre les difficultés d'accès aux documents administratifs qui freinent le développement socio-économique.
        </p>
        <div class="d-flex flex-wrap mb-4">
          <div class="feature-pill">
            <div class="feature-pill-icon"><i class="bi bi-phone"></i></div>
            <span>Capture via Smartphone</span>
          </div>
          <div class="feature-pill">
            <div class="feature-pill-icon"><i class="bi bi-translate"></i></div>
            <span>Langues locales disponibles</span>
          </div>
        </div>
        <ul class="check-list mb-5">
          <li><i class="bi bi-check-circle-fill"></i> Transparence et gouvernance locale améliorées.</li>
          <li><i class="bi bi-check-circle-fill"></i> Préservation de la mémoire familiale et communautaire.</li>
          <li><i class="bi bi-check-circle-fill"></i> Accès facilité aux services publics (Santé, Éducation).</li>
        </ul>
        <a href="#" class="btn-teal">DÉCOUVRIR L'ONG <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>
</section>

<section class="best-services" id="services">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-label">Nos Atouts</div>
      <h2 class="section-title">Pourquoi Choisir<br>MyPapyrus?</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="service-icon-card">
          <div class="service-icon-wrap"><i class="bi bi-shield-check"></i></div>
          <h5>Intégrité</h5>
          <p>Vos documents sont cryptés et protégés contre toute modification non autorisée.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-icon-card">
          <div class="service-icon-wrap"><i class="bi bi-lightning-charge"></i></div>
          <h5>Rapidité</h5>
          <p>Retrouvez n'importe quel document en quelques secondes via notre barre de recherche.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-icon-card">
          <div class="service-icon-wrap"><i class="bi bi-cloud-slash"></i></div>
          <h5>Mode Hors-ligne</h5>
          <p>Accédez à vos documents même sans connexion internet dans les zones reculées.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-icon-card">
          <div class="service-icon-wrap"><i class="bi bi-hdd-network"></i></div>
          <h5>Interopérabilité</h5>
          <p>Partagez facilement vos documents avec les administrations partenaires.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<footer id="volunteer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="footer-brand mb-3"><i class="bi bi-archive-fill me-2" style="color:var(--orange-light)"></i>My<span>Papyrus</span></div>
        <p>Une initiative dédiée à l'autonomisation des populations rurales par la sécurisation de leur patrimoine documentaire.</p>
        <div class="footer-social mt-4">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="footer-heading">Liens Rapides</div>
        <ul class="footer-links">
          <li><a href="#">Accueil</a></li>
          <li><a href="#">L'Initiative</a></li>
          <li><a href="#">Comment scanner ?</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="footer-heading">Services</div>
        <ul class="footer-links">
          <li><a href="#">Archivage Foncier</a></li>
          <li><a href="#">Gestion d'État Civil</a></li>
          <li><a href="#">Papiers Agricoles</a></li>
          <li><a href="#">Support Technique</a></li>
        </ul>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="d-flex justify-content-between flex-wrap gap-2" style="font-size:.82rem">
      <span>© 2026 MyPapyruspar FOBMIR. Tous droits réservés.</span>
      <span>
        <a href="#" class="text-decoration-none me-3" style="color:rgba(255,255,255,.5)">Politique de Confidentialité</a>
        <a href="#" class="text-decoration-none" style="color:rgba(255,255,255,.5)">Mentions Légales</a>
      </span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>