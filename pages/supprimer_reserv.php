<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'sportif') {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['id_reserv'])) {
    header("Location: sportif.php");
    exit;
}

$pdo = Database::getConnection();
$id_reservation = $_POST['id_reserv'];

$stmtSportif = $pdo->prepare("
    SELECT id_sportif 
    FROM sportif 
    WHERE id_user = :id_user
");
$stmtSportif->execute([
    ':id_user' => $_SESSION['id_user']
]);
$sportif = $stmtSportif->fetch(PDO::FETCH_ASSOC);

if (!$sportif) {
    die("Sportif introuvable");
}

$id_sportif = $sportif['id_sportif'];


$stmt = $pdo->prepare("
    DELETE FROM reservation 
    WHERE id_reserv = :id_reserv
      AND id_sportif = :id_sportif
      AND statut != 'acceptée'
");

$stmt->execute([
    ':id_reserv' => $id_reservation,
    ':id_sportif' => $id_sportif
]);

header("Location: sportif.php");
exit;

?>