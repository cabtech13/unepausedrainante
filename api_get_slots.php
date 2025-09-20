<?php
session_start();
include "config.php";
header('Content-Type: application/json');

$days_ahead = 30;
$today = new DateTimeImmutable();
$end   = $today->modify("+$days_ahead days");

// Récup tous les créneaux
$creneaux = $pdo->query("
    SELECT c.*, s.nom AS service, s.couleur, p.nom AS prof
    FROM creneaux c
    JOIN services s ON c.service_id = s.id
    JOIN profs p ON c.prof_id = p.id
")->fetchAll(PDO::FETCH_ASSOC);

// Récup réservations
$reservations = $pdo->query("SELECT * FROM reservations")->fetchAll(PDO::FETCH_ASSOC);
$reserved = [];
foreach ($reservations as $r) {
    $reserved[$r['creneau_id']] = true;
}

$events = [];
$joursMap = [
    "Lundi"=>1,"Mardi"=>2,"Mercredi"=>3,
    "Jeudi"=>4,"Vendredi"=>5,"Samedi"=>6,"Dimanche"=>7
];

foreach ($creneaux as $c) {
    if ($c['type_creneau'] == 'ponctuel' && !empty($c['date_creneau'])) {
        $id = $c['id'];
        $isReserved = isset($reserved[$id]);

        $events[] = [
            "id"    => $id,
            "title" => $isReserved ? "Réservé (".$c['service'].")" : $c['service']." (".$c['prof'].")",
            "start" => $c['date_creneau']."T".$c['heure_debut'],
            "end"   => $c['date_creneau']."T".$c['heure_fin'],
            "color" => $isReserved ? "#9A9A92" : $c['couleur'],
            "dispo" => $isReserved ? 0 : 1,
            "service" => $c['service'],
            "prof"    => $c['prof']
        ];
    }

    if ($c['type_creneau'] == 'recurrent' && !empty($c['jour_semaine'])) {
        $dow = $joursMap[$c['jour_semaine']] ?? null;
        if (!$dow) continue;

        $period = new DatePeriod($today, new DateInterval('P1D'), $end);
        foreach ($period as $date) {
            if ((int)$date->format("N") === $dow) {
                $id = "rec_".$c['id']."_".$date->format("Ymd");
                $isReserved = false; // TODO: gérer les réservations récurrentes individuellement

                $events[] = [
                    "id"    => $id,
                    "title" => $isReserved ? "Réservé (".$c['service'].")" : $c['service']." (".$c['prof'].")",
                    "start" => $date->format("Y-m-d")."T".$c['heure_debut'],
                    "end"   => $date->format("Y-m-d")."T".$c['heure_fin'],
                    "color" => $isReserved ? "#9A9A92" : $c['couleur'],
                    "dispo" => $isReserved ? 0 : 1,
                    "service" => $c['service'],
                    "prof"    => $c['prof']
                ];
            }
        }
    }
}

echo json_encode($events);
