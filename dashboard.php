<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Récup services (pour filtres + légende)
$services = $pdo->query("SELECT id, nom, couleur FROM services ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
// Récup profs (pour filtres)
$profs    = $pdo->query("SELECT id, nom, email, telephone FROM profs ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- FullCalendar v6 (build global) -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />

  <style>
    body { background:#f7f7f7; }
    .page-title { font-weight:700; }
    #adminCalendar {
      background:#fff; border-radius:12px; padding:1rem;
      box-shadow:0 6px 18px rgba(0,0,0,0.08);
    }
    .fc .fc-toolbar-title { font-size:1.2rem; color:#333; }
    .fc-event { font-size:0.9rem; padding:3px 6px; border-radius:6px; }
    .legend .dot {
      display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:6px;
      border:1px solid rgba(0,0,0,0.15);
    }
    .legend-item { margin-right:14px; margin-bottom:6px; display:inline-flex; align-items:center; }
    .filters .form-select, .filters .form-control { border-radius:10px; }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">

  <?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-info text-center">
      <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title text-success m-0"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</h2>
    <div class="d-flex gap-2">
      <a href="index.php" class="btn btn-outline-success">
        <i class="bi bi-house"></i> Retour au site
      </a>
      <a href="logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>

  <!-- Services -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-success text-white">
      <i class="bi bi-palette me-2"></i>Services
    </div>
    <div class="card-body">
      <form method="POST" action="service_add.php" class="row g-3 mb-3">
        <div class="col-md-6">
          <input type="text" name="nom" class="form-control" placeholder="Nom du service" required>
        </div>
        <div class="col-md-4">
          <input type="color" name="couleur" class="form-control form-control-color" value="#58624B" title="Choisir une couleur">
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-success">Ajouter</button>
        </div>
      </form>

      <div class="legend mb-2">
        <?php foreach($services as $s): ?>
          <span class="legend-item">
            <span class="dot" style="background: <?= htmlspecialchars($s['couleur']) ?>;"></span>
            <span><?= htmlspecialchars($s['nom']) ?></span>
          </span>
        <?php endforeach; ?>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr><th>Nom</th><th>Couleur</th><th class="text-end">Action</th></tr>
          </thead>
          <tbody>
            <?php foreach($services as $s): ?>
            <tr>
              <td><?= htmlspecialchars($s['nom']) ?></td>
              <td>
                <span class="dot" style="background:<?= htmlspecialchars($s['couleur']) ?>;"></span>
                <code class="ms-1"><?= htmlspecialchars($s['couleur']) ?></code>
              </td>
              <td class="text-end">
                <a href="service_delete.php?id=<?= (int)$s['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce service ?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($services)): ?>
              <tr><td colspan="3" class="text-center text-muted">Aucun service</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Profs -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      <i class="bi bi-person-badge me-2"></i>Professeurs
    </div>
    <div class="card-body">
      <form method="POST" action="prof_add.php" class="row g-3 mb-3">
        <div class="col-md-4">
          <input type="text" name="nom" class="form-control" placeholder="Nom" required>
        </div>
        <div class="col-md-4">
          <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="col-md-4">
          <input type="text" name="telephone" class="form-control" placeholder="Téléphone">
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary">Ajouter</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr><th>Nom</th><th>Email</th><th>Téléphone</th><th class="text-end">Action</th></tr>
          </thead>
          <tbody>
            <?php foreach($profs as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['nom']) ?></td>
              <td><?= htmlspecialchars($p['email']) ?></td>
              <td><?= htmlspecialchars($p['telephone']) ?></td>
              <td class="text-end">
                <a href="prof_delete.php?id=<?= (int)$p['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce professeur ?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($profs)): ?>
              <tr><td colspan="4" class="text-center text-muted">Aucun professeur</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Réservations via calendrier -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-warning">
      <i class="bi bi-people me-2"></i>Réservations (créneaux ponctuels + récurrents)
    </div>
    <div class="card-body">

      <!-- Filtres -->
      <div class="row g-3 mb-3 filters">
        <div class="col-md-4">
          <select id="filterService" class="form-select">
            <option value="">— Tous les services —</option>
            <?php foreach($services as $s): ?>
              <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <select id="filterProf" class="form-select">
            <option value="">— Tous les profs —</option>
            <?php foreach($profs as $p): ?>
              <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <input type="text" id="searchClient" class="form-control" placeholder="🔍 Rechercher un client (nom ou prénom)">
        </div>
      </div>

      <!-- Calendrier admin -->
      <div id="adminCalendar"></div>
    </div>
  </div>
</div>

  <!-- Créneaux -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-info text-white">
      <i class="bi bi-calendar-check me-2"></i>Gestion des créneaux
    </div>
    <div class="card-body">
      <form method="POST" action="creneau_add.php" class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">Service</label>
          <select name="service_id" class="form-select" required>
            <option value="">— Choisir —</option>
            <?php foreach($services as $s): ?>
              <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Professeur</label>
          <select name="prof_id" class="form-select" required>
            <option value="">— Choisir —</option>
            <?php foreach($profs as $p): ?>
              <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Heure début</label>
          <input type="time" name="heure_debut" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Heure fin</label>
          <input type="time" name="heure_fin" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Type</label>
          <select name="type" class="form-select" required>
            <option value="ponctuel">Ponctuel</option>
            <option value="recurrent">Récurrent</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Date (si ponctuel)</label>
          <input type="date" name="date" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">Jour (si récurrent)</label>
          <select name="jour" class="form-select">
            <option value="">—</option>
            <option value="1">Lundi</option>
            <option value="2">Mardi</option>
            <option value="3">Mercredi</option>
            <option value="4">Jeudi</option>
            <option value="5">Vendredi</option>
            <option value="6">Samedi</option>
            <option value="0">Dimanche</option>
          </select>
        </div>
        <div class="col-md-3 d-grid align-self-end">
          <button class="btn btn-info"><i class="bi bi-plus-circle"></i> Ajouter</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Service</th><th>Prof</th><th>Type</th><th>Jour/Date</th>
              <th>Heure</th><th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $creneaux = $pdo->query("
              SELECT c.*, s.nom AS service_nom, p.nom AS prof_nom
              FROM creneaux c
              JOIN services s ON s.id = c.service_id
              JOIN profs p ON p.id = c.prof_id
              ORDER BY type, date, jour, heure_debut
            ")->fetchAll(PDO::FETCH_ASSOC);

            foreach($creneaux as $c):
            ?>
            <tr>
              <td><?= htmlspecialchars($c['service_nom']) ?></td>
              <td><?= htmlspecialchars($c['prof_nom']) ?></td>
              <td>
                <?= $c['type']=='ponctuel' ? 'Ponctuel' : 'Récurrent' ?>
              </td>
              <td>
                <?= $c['type']=='ponctuel'
                     ? htmlspecialchars($c['date'])
                     : ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'][$c['jour']] ?>
              </td>
              <td><?= htmlspecialchars($c['heure_debut'].' - '.$c['heure_fin']) ?></td>
              <td class="text-end">
                <a href="creneau_delete.php?id=<?= (int)$c['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce créneau ?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($creneaux)): ?>
              <tr><td colspan="6" class="text-center text-muted">Aucun créneau défini</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


<!-- Modal Réservation (détails / ajout) -->
<div class="modal fade" id="resaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="resaModalTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <form method="POST" action="reservation_add.php" id="resaForm">
        <div class="modal-body">
          <input type="hidden" name="creneau_id" id="resa_creneau_id">
          <input type="hidden" name="slot_type" id="resa_slot_type"><!-- 'ponctuel' ou 'recurrent' -->
          <input type="hidden" name="occ_date" id="resa_occ_date"><!-- utile pour récurrent -->

          <p class="mb-2"><strong id="resa_info"></strong></p>
          <div id="resaDetails" class="mb-3"></div>

          <div id="resaFormFields" class="row g-3 d-none">
            <div class="col-md-6">
              <input type="text" name="prenom" class="form-control" placeholder="Prénom client">
            </div>
            <div class="col-md-6">
              <input type="text" name="nom" class="form-control" placeholder="Nom client">
            </div>
            <div class="col-md-6">
              <input type="email" name="email" class="form-control" placeholder="Email client">
            </div>
            <div class="col-md-6">
              <input type="text" name="telephone" class="form-control" placeholder="Téléphone client">
            </div>
          </div>
        </div>

        <div class="modal-footer" id="resaModalFooter"></div>
      </form>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const el = document.getElementById('adminCalendar');

  const calendar = new FullCalendar.Calendar(el, {
    initialView: 'timeGridWeek',
    locale: 'fr',
    slotMinTime: "08:00:00",
    slotMaxTime: "21:00:00",
    allDaySlot: false,
    expandRows: true,
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    events: {
      url: 'api_get_slots.php',
      extraParams: () => ({
        service_id: document.getElementById('filterService').value,
        prof_id:    document.getElementById('filterProf').value,
        search:     document.getElementById('searchClient').value
      })
    },
    eventClick: function(info) {
      const ev = info.event;
      const ext = ev.extendedProps || {};
      const modalEl = document.getElementById('resaModal');
      const m = new bootstrap.Modal(modalEl);

      // Remplir les champs cachés pour POST
      document.getElementById('resa_creneau_id').value = ext.slotId || '';
      document.getElementById('resa_slot_type').value  = ext.slotType || ''; // 'ponctuel' ou 'recurrent'
      // Pour un récurrent, on a besoin de la date d'occurrence
      document.getElementById('resa_occ_date').value   = ev.startStr.substring(0,10);

      // Titre modal + info haut
      document.getElementById('resaModalTitle').innerText = ext.reservation ? "Détails réservation" : "Ajouter une réservation";
      const dateFr = ev.start.toLocaleDateString("fr-FR");
      const hDeb   = (ext.heure_debut || (ev.startStr.substring(11,16)));
      const hFin   = (ext.heure_fin   || (ev.endStr   ? ev.endStr.substring(11,16) : ''));
      document.getElementById('resa_info').innerText =
        `📅 ${dateFr} (${hDeb} - ${hFin}) • ${ext.service || ''} / ${ext.prof || ''}`;

      const detailsDiv = document.getElementById('resaDetails');
      const formFields = document.getElementById('resaFormFields');
      const footer     = document.getElementById('resaModalFooter');

      // Affichage en cas de réservation existante
      if (ext.reservation) {
        const r = ext.reservation;
        detailsDiv.innerHTML = `
          <div class="alert alert-light border">
            <div><strong>Client :</strong> ${r.prenom ?? ''} ${r.nom ?? ''}</div>
            <div><strong>Email :</strong> ${r.email ?? '-'}</div>
            <div><strong>Téléphone :</strong> ${r.telephone ?? '-'}</div>
          </div>
        `;
        formFields.classList.add('d-none');
        footer.innerHTML = `
          <a href="reservation_delete.php?id=${r.id}" class="btn btn-danger"
             onclick="return confirm('Supprimer cette réservation ?');">
            <i class="bi bi-trash"></i> Supprimer
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        `;
      } else {
        // Créneau libre -> formulaire d'ajout
        detailsDiv.innerHTML = "";
        formFields.classList.remove('d-none');
        footer.innerHTML = `
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning">Enregistrer</button>
        `;
      }

      m.show();
    }
  });

  calendar.render();

  // Rafraîchir les événements quand on change les filtres / recherche
  document.getElementById('filterService').addEventListener('change', () => calendar.refetchEvents());
  document.getElementById('filterProf').addEventListener('change',    () => calendar.refetchEvents());
  document.getElementById('searchClient').addEventListener('keyup',   () => calendar.refetchEvents());
});
</script>
</body>
</html>
