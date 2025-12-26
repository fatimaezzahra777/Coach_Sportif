<?php
session_start();
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../classes/Reservation.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'coach') {
    header("Location: login.php");
    exit;
}


$pdo = Database::getConnection();

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: profilC.php");
    exit;
}

$id_reserv = (int) $_GET['id'];
$action = $_GET['action'];

if (!in_array($action, ['accept', 'refuse'])) {
    die("Action invalide");
}




$reservation = new Réservation($id_reserv);
$loaded = $reservation->load($pdo);

$stmt = $pdo->prepare("SELECT id_coach FROM coach WHERE id_user = :id_user");
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$id_coach = $stmt->fetchColumn();

if ($loaded === false) {
    die("Réservation introuvable");
}

if ($reservation->getIdCoach() != $id_coach) {
    die("Vous n'êtes pas autorisé à modifier cette réservation.");
}

if ($action === 'accept') {
    $reservation->setStatut('acceptée');
} elseif ($action === 'refuse') {
    $reservation->setStatut('refusée');
}

$reservation->save($pdo);

header("Location: profilC.php");
exit;
