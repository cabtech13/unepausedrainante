<?php
include "config.php";
header('Content-Type: application/json');

$rows = $pdo->query("
  SELECT c.*, s.nom AS service_nom, s.couleur, p.nom AS prof_nom, p.prenom AS prof_prenom
  FROM creneaux c
  JOIN services s ON c.service_id = s.id
  JOIN profs p ON c.prof_id = p.id
")->fetchAll();

$events = [];
foreach($rows as $r){
  $events[] = [
    'id' => $r['id'],
    'title' => $r['service_nom']." - ".$r['prof_prenom'],
    'start' => $r['date_creneau']."T".$r['heure_debut'],
    'end' => $r['date_creneau']."T".$r['heure_fin'],
    'color' => $r['couleur']
  ];
}

echo json_encode($events);
