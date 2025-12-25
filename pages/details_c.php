<?php
session_start();
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../classes/Reservation.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'sportif') {
    header("Location: login.php");
    exit;
}

$pdo = Database::getConnection();

// Vérifier que l'ID du coach est passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: Liste_c.php");
    exit;
}

$id_coach = (int) $_GET['id'];

$sql = "SELECT 
            coach.id_coach,
            coach.photo,
            coach.certif,
            coach.biographie,
            coach.discipline,
            coach.experience,
            users.nom
        FROM coach
        INNER JOIN users ON coach.id_user = users.id_user
        WHERE coach.id_coach = :id_coach";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_coach', $id_coach, PDO::PARAM_INT);
$stmt->execute();

$coach = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coach) {
    header("Location: Liste_c.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id_sportif FROM sportif WHERE id_user = ?");
$stmt->execute([$_SESSION['id_user']]);
$sportif = $stmt->fetch();

if (!$sportif) {
    die("Sportif introuvable");
}

$id_sportif = $sportif['id_sportif'];

// Récupérer les disponibilités du coach
$sql_dispo = "SELECT * FROM disponibilite WHERE id_coach = :id_coach ORDER BY jour, heure_d";
$stmt_dispo = $pdo->prepare($sql_dispo);
$stmt_dispo->bindParam(':id_coach', $id_coach, PDO::PARAM_INT);
$stmt_dispo->execute();
$dispos = $stmt_dispo->fetchAll(PDO::FETCH_ASSOC);

$message='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['date_r']) && !empty($_POST['heure'])) {
        $stmt = $pdo->prepare("
            INSERT INTO reservation (id_coach, id_sportif, date_r, heure, statut)
            VALUES (?, ?, ?, ?, 'en_attente')
        ");

        if ($stmt->execute([
            $id_coach,
            $id_sportif,
            $_POST['date_r'],
            $_POST['heure']
        ])) {
            $message = "Réservation envoyée avec succès";
        } else {
            $message = "Erreur lors de la réservation";
        }
    } else {
        $message = "Veuillez remplir tous les champs";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Coach</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen">

<div class="max-w-4xl mx-auto py-16 px-4">
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-xl p-8">
        <a href="Liste_c.php" class="flex justify-end ">
            <button class="md:col-span-2 bg-emerald-500 hover:bg-emerald-600 p-3 rounded-lg font-bold transition"> X </button>
        </a>

        <div class="flex flex-col md:flex-row gap-8">
            <img src="<?= !empty($coach['photo']) ? htmlspecialchars($coach['photo']) : 'assets/images/default.png' ?>"
                 class="w-64 h-64 object-cover rounded-xl">

            <!-- INFOS -->
            <div>
                <h1 class="text-3xl font-bold mb-2">Nom : <?= htmlspecialchars($coach['nom']) ?></h1>
                <p class="text-gray-300 text-xl mb-2">Certification : <?= htmlspecialchars($coach['certif']) ?></p>
                <p class="text-gray-300 text-xl mb-2">Biographie : <?= htmlspecialchars($coach['biographie']) ?></p>
                <p class="text-gray-300 text-xl mb-2">Expérience : <?= htmlspecialchars($coach['experience']) ?> ans</p>
                <p class="text-gray-300 text-xl mb-2">Discipline : <?= htmlspecialchars($coach['discipline']) ?></p>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4">Disponibilités</h2>

            <?php if (!empty($dispos)): ?>
                <table class="w-full text-left">
                    <tr class="border-b">
                        <th>Jour</th>
                        <th>De</th>
                        <th>À</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($dispos as $d): ?>
                        <tr class="border-b">
                            <td><?= htmlspecialchars($d['jour']) ?></td>
                            <td><?= htmlspecialchars($d['heure_d']) ?></td>
                            <td><?= htmlspecialchars($d['heure_f']) ?></td>
                            <td>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="date_r" value="<?= htmlspecialchars($d['jour']) ?>">
                                    <input type="hidden" name="heure" value="<?= htmlspecialchars($d['heure_d']) ?>">
                                    <button type="submit" class="bg-emerald-500 px-3 py-1 rounded">
                                        Réserver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune disponibilité</p>
            <?php endif; ?>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4 mt-6">Réserver une séance</h2>

            <form method="POST" class="grid md:grid-cols-2 gap-4">
                <input type="date" name="date_r" required
                       class="bg-black/30 border border-white/20 rounded-lg px-4 py-2">

                <input type="time" name="heure" required
                       class="bg-black/30 border border-white/20 rounded-lg px-4 py-2">

                <button type="submit"
                        class="md:col-span-2 bg-emerald-500 hover:bg-emerald-600 py-3 rounded-lg font-bold transition">
                    Confirmer la réservation
                </button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
