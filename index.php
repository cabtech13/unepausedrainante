<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Une Pause Drainante</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/png" href="https://placehold.co/32x32?text=🌿">


  <!-- FullCalendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

  <!-- Axios -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.11.0/axios.min.js"></script>
</head>
<body>

  <!-- LOGO -->
  <header>
    <div class="logo">
      <img src="https://placehold.co/200x80?text=LOGO" alt="Logo Une Pause Drainante">
    </div>
  </header>

  <!-- MENU -->
  <nav>
    <ul>
      <li><a href="#hero">Accueil</a></li>
      <li><a href="#quisuisje">Qui suis-je</a></li>
      <li><a href="#salon">Le salon</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#reservation">Réservation</a></li>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="#footer">Contact</a></li>
    </ul>
  </nav>

  <!-- HERO -->
  <section id="hero" class="hero" style="background:url('https://placehold.co/1200x600?text=Hero+Image') center/cover no-repeat;">
    <div class="hero-content">
      <h1>Une Pause Drainante</h1>
      <p>Offrez-vous un moment unique de détente et de bien-être</p>
      <a href="#reservation" class="btn">Réserver maintenant</a>
    </div>
  </section>

  <!-- QUI SUIS-JE -->
  <section id="quisuisje" class="about">
    <div class="about-text">
      <h2>Qui suis-je ?</h2>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Ma mission est d'apporter détente, équilibre et bien-être grâce au massage drainant.
      </p>
    </div>
    <div class="about-image">
      <img src="https://placehold.co/600x400?text=Portrait+Thérapeute" alt="Portrait thérapeute">
    </div>
  </section>

  <!-- LE SALON -->
  <section id="salon" class="salon">
    <h2>Le salon</h2>
    <p>Découvrez un espace chaleureux et apaisant, pensé pour votre confort et votre sérénité.</p>
    <div class="salon-gallery">
      <img src="https://placehold.co/400x300?text=Salon+1" alt="Salon 1">
      <img src="https://placehold.co/400x300?text=Salon+2" alt="Salon 2">
      <img src="https://placehold.co/400x300?text=Salon+3" alt="Salon 3">
      <img src="https://placehold.co/400x300?text=Salon+4" alt="Salon 4">
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services" class="services">
    <h2>Nos Services</h2>
    <div class="service-grid">
      <div class="service-card">
        <img src="https://placehold.co/150?text=Massage" alt="Massage drainant">
        <h3>Massage drainant</h3>
        <p>Un soin doux et efficace pour stimuler la circulation et réduire les tensions.</p>
      </div>
      <div class="service-card">
        <img src="https://placehold.co/150?text=Relaxation" alt="Relaxation">
        <h3>Relaxation</h3>
        <p>Une pause profonde pour retrouver calme, sérénité et bien-être intérieur.</p>
      </div>
      <div class="service-card">
        <img src="https://placehold.co/150?text=Bien-être" alt="Bien-être">
        <h3>Bien-être</h3>
        <p>Un moment unique, personnalisé selon vos besoins et vos envies.</p>
      </div>
    </div>
  </section>

  <!-- RESERVATION -->
  <section id="reservation" class="reservation">
    <h2>Réservation</h2>
    <p>Choisissez un créneau disponible dans le calendrier</p>

    <!-- Calendrier -->
    <div id="calendar"></div>

    <!-- Formulaire réservation -->
<div id="resaFormWrapper" style="display:none; margin-top:2rem;">
  <h3>Réserver ce créneau</h3>
  <form id="resaForm">
    <input type="hidden" name="slot_id" id="slot_id">

    <input type="text" name="nom" placeholder="Votre nom" required>
    <input type="text" name="prenom" placeholder="Votre prénom" required>
    <input type="email" name="email" placeholder="Votre email" required>
    <input type="tel" name="telephone" placeholder="Votre téléphone" pattern="[0-9+ ]{6,}" required>

    <button type="submit">Confirmer</button>
  </form>
</div>


  <!-- FOOTER -->
  <footer id="footer">
    <p>© 2025 Une Pause Drainante | Tous droits réservés</p>
    <p>Contact : <a href="mailto:contact@unepause.fr">contact@unepause.fr</a></p>
    <p>Suivez-nous : 🌿 Instagram | Facebook</p>
  </footer>

  <!-- Script FullCalendar -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let formWrapper = document.getElementById('resaFormWrapper');

    let calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'timeGridWeek',
  locale: 'fr',
  slotDuration: '01:00:00', // pas d’affichage toutes les heures
  slotMinTime: "08:00:00",  // début de la journée
  slotMaxTime: "20:00:00",  // fin de la journée
  allDaySlot: false,        // désactiver le bloc "Toute la journée"
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
  },
  events: 'api_get_slots.php',
  eventClick: function(info) {
    if(info.event.extendedProps.dispo == 1) {
      document.getElementById('slot_id').value = info.event.id;
      formWrapper.style.display = 'block';
      formWrapper.scrollIntoView({behavior: "smooth"});
    } else {
      alert("❌ Ce créneau est déjà réservé.");
    }
  }
});


    calendar.render();

    // Soumission réservation
    document.getElementById('resaForm').addEventListener('submit', function(e){
      e.preventDefault();
      let formData = new FormData(this);
      fetch('reserve.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(res => {
        alert(res.message);
        if(res.success){
          this.reset();
          formWrapper.style.display = 'none';
          calendar.refetchEvents();
        }
      });
    });
  });
  </script>
</body>
</html>
