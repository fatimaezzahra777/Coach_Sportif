<?php
session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'sportif') {
    header("Location: login.php");
    exit;
}



$pdo = Database::getConnection();

$stmt = $pdo->prepare("
    SELECT 
        r.date_r,
        r.heure,
        r.statut,
        u.nom AS coach_nom,
        c.discipline
    FROM reservation r
    JOIN coach c ON r.id_coach = c.id_coach
    JOIN users u ON c.id_user = u.id_user
    WHERE r.id_sportif = :id_sportif
    ORDER BY r.date_r ASC, r.heure ASC
");

$stmt->execute([
    'id_sportif' => $_SESSION['id_user']
]);

$reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<!-- Background graphique glassmorphism -->
<div class="absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute -left-52 top-1/2 w-[900px] h-[900px]
        bg-gradient-to-r from-blue-500 to-emerald-500
        opacity-30 rounded-full blur-3xl"></div>

    <div class="absolute -right-52 top-1/3 w-[900px] h-[900px]
        bg-gradient-to-r from-emerald-500 to-blue-500
        opacity-30 rounded-full blur-3xl"></div>
</div>

<!-- NAVBAR -->
<nav class="bg-white/10 backdrop-blur-xl border-b border-white/20
            text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center space-x-2">
                <i class="fas fa-dumbbell text-3xl text-emerald-400"></i>
                <span class="text-2xl font-bold">SportCoach</span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="sportif.php" class="text-emerald-400 font-bold">Sportif</a>
                <a href="Liste_c.php" class="hover:text-red-400">Coachs</a>
                <a href="logout.php" class="hover:text-red-400">Deconnexion</a>
            </div>
        </div>
    </div>
</nav>


<section class="relative z-10 py-12 px-4">
    <div class="max-w-7xl mx-auto">

        <!-- Titre -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold">Mon Espace Sportif</h1>
            <p class="text-gray-300 mt-2">
                Gérez vos réservations et suivez votre progression
            </p>
        </div>


       <div class="grid md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-xl">
                <i class="fas fa-calendar text-emerald-400 text-4xl mb-4"></i>
                <div class="text-3xl font-bold"></div>
                <div class="text-gray-300">Séances Réservées</div>
            </div>

            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-xl">
                <i class="fas fa-check-circle text-emerald-400 text-4xl mb-4"></i>
                <div class="text-3xl font-bold"></div>
                <div class="text-gray-300">Séances Complétées</div>
            </div>
       </div>

        <div class="grid lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl border border-white/20
            rounded-2xl p-6 shadow-xl">
                <h2 class="text-2xl font-bold mb-6">Prochaines Séances</h2>

                <?php if (empty($reservation)): ?>
                    <p class="text-gray-300">Aucune séance programmée pour le moment.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r->getIdSportif()) ?></td>
                                <td><?= htmlspecialchars($r->getDate()) ?></td>
                                <td><?= htmlspecialchars($r->getHeure()) ?></td>
                                <td>
                                    <?php
                                    $stat = $r->getStatut();
                                    if ($stat === 'en_attente') echo '<span class="text-yellow-400">En attente</span>';
                                    elseif ($stat === 'acceptée') echo '<span class="text-green-400">Acceptée</span>';
                                    elseif ($stat === 'refusée') echo '<span class="text-red-400">Refusée</span>';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                    </div>
                <?php endif; ?>
            </div>


            <div class="space-y-6">

                <div class="bg-gradient-to-br from-blue-500 to-emerald-500
                            rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold mb-3">Réserver une Séance</h3>
                    <p class="mb-4 opacity-90">
                        Trouvez le coach idéal pour vos objectifs
                    </p>
                    <a href="Liste_c.php">
                        <button class="w-full bg-white/90 text-black py-3 rounded-full
                                       font-semibold hover:bg-white transition">
                            Découvrir les Coachs
                        </button>
                    </a>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-xl font-bold mb-4">Mon Profil</h3>

                   <div class="bg-white/10 p-8 rounded-xl max-w-3xl">

                    <div class="flex items-center space-x-6 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold"></h2>
                            <p class="text-white/70"></p>
                        </div>
                    </div>

                    <p class="mb-4"><b>Niveau :</b></p>

                    <a href="editS.php" 
                    class="bg-emerald-400 text-black px-6 py-2 rounded-lg font-bold hover:bg-emerald-300">
                    Modifier Profil
                    </a>
                </div>

                 <div class="bg-balck py-16">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-4xl font-bold text-emerald-400 mb-12 text-center">Comment Ça Marche ?</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-white text-black w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                        <h3 class="text-xl font-bold mb-3">Choisissez Votre Coach</h3>
                        <p class="text-gray-500">Parcourez les profils des coachs certifiés et trouvez celui qui correspond à vos objectifs</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white text-black w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                        <h3 class="text-xl font-bold mb-3">Réservez une Séance</h3>
                        <p class="text-gray-500">Sélectionnez un créneau disponible et confirmez votre réservation en quelques clics</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-white text-black w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                        <h3 class="text-xl font-bold mb-3">Atteignez Vos Objectifs</h3>
                        <p class="text-gray-500">Profitez d'un accompagnement personnalisé pour progresser rapidement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

            </div>
        </div>
    </div>
</section>

</body>
</html>
