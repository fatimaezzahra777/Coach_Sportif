<?php
session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'sportif') {
    header("Location: login.php");
    exit;
}

$pdo = Database::getConnection();

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
    die("Sportif ma l9itohch");
}

$id_sportif = $sportif['id_sportif'];


$stmt = $pdo->prepare("
    SELECT 
        r.date_r,
        r.heure,
        r.statut,
        u.nom AS coach_nom,
        c.discipline
    FROM reservation r
    INNER JOIN coach c ON r.id_coach = c.id_coach
    INNER JOIN users u ON c.id_user = u.id_user
    WHERE r.id_sportif = :id_sportif
    ORDER BY r.date_r ASC, r.heure ASC
");

$stmt->execute([
    ':id_sportif' => $id_sportif
]);

$reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalSeances = count($reservation);

$seancesAcceptees = 0;
$hasSeancesVisibles = false;

foreach ($reservation as $r) {
    if ($r['statut'] === 'acceptée') {
        $seancesAcceptees++;
    }
    if ($r['statut'] !== 'refusée') {
        $hasSeancesVisibles = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SportCoach - Dashboard Sportif</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="relative min-h-screen bg-black overflow-x-hidden text-white">


<div class="absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -left-52 top-1/2 w-[900px] h-[900px]
        bg-gradient-to-r from-blue-500 to-emerald-500
        opacity-30 rounded-full blur-3xl"></div>

    <div class="absolute -right-52 top-1/3 w-[900px] h-[900px]
        bg-gradient-to-r from-emerald-500 to-blue-500
        opacity-30 rounded-full blur-3xl"></div>
</div>


<nav class="bg-white/10 backdrop-blur-xl border-b border-white/20
            text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center space-x-2">
                <i class="fas fa-dumbbell text-3xl text-emerald-400"></i>
                <span class="text-2xl font-bold">SportCoach</span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="sportif.php" class="hover:text-emerald-400">Sportif</a>
                <a href="Liste_c.php" class="text-emerald-400 font-bold">Coachs</a>
                <a href="logout.php" class="hover:text-red-400">Deconnexion</a>
            </div>
        </div>
    </div>
</nav>

<!-- CONTENU -->
<div class="max-w-7xl mx-auto p-8">

    <!-- TITRE -->
    <h1 class="text-4xl font-bold mb-2">Mon Espace Sportif</h1>
    <p class="text-gray-400 mb-8">Gérez vos réservations</p>

    <!-- STATISTIQUES -->
    <div class="grid md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white/10 rounded-xl p-6">
            <i class="fas fa-calendar text-emerald-400 text-3xl mb-3"></i>
            <p class="text-gray-300">Total Séances</p>
            <p class="text-3xl font-bold"><?= $totalSeances ?></p>
        </div>

        <div class="bg-white/10 rounded-xl p-6">
            <i class="fas fa-check-circle text-emerald-400 text-3xl mb-3"></i>
            <p class="text-gray-300">Séances Acceptées</p>
            <p class="text-3xl font-bold"><?= $seancesAcceptees ?></p>
        </div>
    </div>

    <!-- RÉSERVATIONS -->
    <div class="bg-white/10 rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">Prochaines Séances</h2>

        <?php if (empty($reservation) || !$hasSeancesVisibles): ?>
            <p class="text-gray-400">Aucune séance programmée.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($reservation as $r): ?>
                    <?php if ($r['statut'] === 'refusée') continue; ?>

                    <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?= $r['coach_nom'] ?></p>
                            <p class="text-sm text-gray-400"><?= $r['discipline'] ?></p>
                            <p class="text-sm text-gray-400">
                                <?= $r['date_r'] ?> à <?= $r['heure'] ?>
                            </p>
                        </div>

                        <?php if ($r['statut'] === 'en_attente'): ?>
                            <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400">
                                En attente
                            </span>
                        <?php elseif ($r['statut'] === 'acceptée'): ?>
                            <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400">
                                Acceptée
                            </span>
                        <?php endif; ?>
                         <?php if ($r['statut'] !== 'acceptée'): ?>
                                <form method="POST" action="supprimer_reserv.php" 
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">
                                    <input type="hidden" name="id_reservation" value="<?= $r['id_reservation'] ?>">
                                    <button type="submit"
                                        class="ml-4 px-4 py-2 bg-red-500/20 text-red-400 rounded-full hover:bg-red-500/30">
                                        Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
       

    </div>

    <!-- BOUTON -->
    <div class="mt-10">
        <a href="Liste_c.php" class="inline-block bg-emerald-400 text-black px-8 py-3 rounded-full font-bold hover:bg-emerald-300">
            Réserver une séance
        </a>
    </div>

</div>

</body>
</html>
