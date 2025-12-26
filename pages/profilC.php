
<?php
session_start();

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../classes/disponibilite.php';
require __DIR__ . '/../classes/Utilisateur.php';
require __DIR__ . '/../classes/Coach.php';
require __DIR__ . '/../classes/Reservation.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'coach') {
    header("Location: login.php");
    exit;
}

$pdo = Database::getConnection();

$stmt = $pdo->prepare("
    SELECT u.*, c.id_coach, c.biographie, c.discipline, c.experience, c.certif, c.photo
    FROM users u
    JOIN coach c ON u.id_user = c.id_user
    WHERE u.id_user = :id_user
");

$stmt->execute(['id_user' => $_SESSION['id_user']]);
$coach = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coach) {
    die("Coach introuvable");
}

$coachObj = new Coach(
    $coach['id_user'],  
    $coach['nom'],
    $coach['email'],
    $coach['telephone'],
    $coach['role'],
    $coach['id_coach'],
    $coach['biographie'],
    $coach['discipline'],
    $coach['experience'],
    $coach['certif'],
    $coach['photo']
);

$id_coach = $coach['id_coach'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $jour = $_POST['jour'] ?? null;
    $heure_d = $_POST['heure_d'] ?? null;
    $heure_f = $_POST['heure_f'] ?? null;

    if ($jour && $heure_d && $heure_f) {
        $dispo = new Disponibilite(
            $id_coach,
            $jour,
            $heure_d,
            $heure_f
        );

        $dispo->save($pdo);
    }
}

$stmt = $pdo->prepare("SELECT * FROM disponibilite WHERE id_coach = :id_coach ORDER BY jour, heure_d");
$stmt->execute(['id_coach' => $id_coach]);
$disponibilite = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT r.*, u.nom AS sportif_nom
    FROM reservation r
    JOIN sportif s ON r.id_sportif = s.id_sportif
    JOIN users u ON s.id_user = u.id_user
    WHERE r.id_coach = :id_coach
    ORDER BY r.date_r, r.heure
");
$stmt->execute(['id_coach' => $id_coach]);
$reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

$photoPath = !empty($coachObj->getPhoto()) ? 'assets//images/' . $coachObj->getPhoto() : 'assets/images/profile.avif';
if (!file_exists($photoPath)) {
    $photoPath = 'assets/images/profile.avif';
}


if (isset($_GET['delete_dispo'])) {
    $id_dispo = (int) $_GET['delete_dispo'];
    $stmt = $pdo->prepare("DELETE FROM disponibilite WHERE id_dispo = :id_dispo AND id_coach = :id_coach");
    $stmt->execute([
        'id_dispo' => $id_dispo,
        'id_coach' => $id_coach
    ]);
    header("Location: profilC.php"); 
    exit;
}




?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-10 overflow-x-hidden">
    <div class="absolute inset-0 -z-10">
            <div class="absolute -left-40 top-1/2 w-[700px] h-[700px]
                        bg-gradient-to-r from-blue-500 to-green-500
                        opacity-30 rounded-full blur-3xl"></div>

            <div class="absolute -right-40 top-1/3 w-[700px] h-[700px]
                    bg-gradient-to-r from-green-500 to-blue-500
                    opacity-30 rounded-full blur-3xl"></div>
       </div>

        <nav class="bg-white/10 backdrop-blur-xl border-b border-white/20
            text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <div class="flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-dumbbell text-3xl text-emerald-400"></i>
                    <span class="text-2xl font-bold">SportCoach</span>
                </div>

                <div class="hidden md:flex items-center space-x-8 font-medium">
                    <a href="profilC.php" class="text-emerald-400 font-bold">Profile</a>
                    <a href="coach.php" class="hover:text-emerald-400 transition">Dashboard</a>
                    <a href="logout.php" class="hover:text-emerald-400 transition">Deconnexion</a>
                </div>

                <button class="md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-2">
                <a href="profilC.php" class="block text-emerald-400 font-bold">Profile</a>
                <a href="coach.php" class="block hover:text-emerald-400">Dashboard</a>
                <a href="logout.php" class="block hover:text-emerald-400">Deconnexion</a>
            </div>
        </div>
    </nav>
<div class="mt-20 grid grid-cols-2">


<div class="bg-white/10 p-8 rounded-xl max-w-3xl">
    <h1 class="text-4xl font-bold text-emerald-400 mb-8">Mon Profil</h1>

    <div class="flex items-center space-x-6 mb-6">

       <img src="<?= !empty($coachObj->getPhoto()) ? htmlspecialchars($coachObj->getPhoto()) : 'assets/images/profile.avif' ?>" alt="Photo de profil" 
            class="w-28 h-28 rounded-full object-cover">
    </div>

    <p class="mb-4"><b>Nom : <?= $coachObj->getNom() ?></b></p>
    <p class="mb-4"><b>Email : <?= $coachObj->getEmail() ?></b></p>
    <p class="mb-4"><b>Telephone : <?= $coachObj->getTelephone() ?></b></p>
    <p class="mb-4"><b>Biographie : <?= $coachObj->getBiographie() ?></b></p>
    <p class="mb-2"><b>experience : <?= $coachObj->getExperience() ?></b></p>
    <p class="mb-2"><b>Certification : <?= $coachObj->getCertif() ?></b></p>

    <a href="editC.php" 
       class="bg-emerald-400 text-black px-6 py-2 rounded-lg font-bold hover:bg-emerald-300">
       Modifier Profil
    </a>
</div>
<div>

<form method="POST" class="bg-white/10 p-6 rounded-xl w-96 mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">Ajouter disponibilité</h2>

    <input type="date" name="jour" required class="w-full mb-3 p-2 rounded text-black">
    <input type="time" name="heure_d" required class="w-full mb-3 p-2 rounded text-black">
    <input type="time" name="heure_f" required class="w-full mb-3 p-2 rounded text-black">

    <button type="submit" class="bg-emerald-500 w-full py-2 rounded">Ajouter</button>
</form>
</div>


</div>
<div class="bg-white/10 p-6 rounded-xl max-w-3xl mt-8">
    <h2 class="text-xl font-bold mb-4 text-emerald-400">Mes disponibilités</h2>
    <?php if ($disponibilite): ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/20 text-white/70">
                    <th class="py-2">Jour</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disponibilite as $d): ?>
                <tr class="border-b border-white/10">
                    <td><?= htmlspecialchars($d['jour']) ?></td>
                    <td><?= htmlspecialchars($d['heure_d']) ?></td>
                    <td><?= htmlspecialchars($d['heure_f']) ?></td>
                    <td class="text-center">
                        <a href="?delete_dispo=<?= $d['id_dispo'] ?>"
                            onclick="return confirm('Supprimer cette disponibilité ?')"
                            class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm">
                            Supprimer
                        </a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-white/70">Aucune disponibilité ajoutée.</p>
    <?php endif; ?>
</div>

<!-- Réservations reçues -->
<div class="bg-white/10 p-6 rounded-xl max-w-4xl mt-10">
    <h2 class="text-xl font-bold mb-4 text-emerald-400">Réservations reçues</h2>
    <?php if ($reservation): ?>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/20 text-white/70">
                    <th>Sportif</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservation as $r): ?>
                <tr class="border-b border-white/10">
                    <td><?= htmlspecialchars($r['sportif_nom']) ?></td>
                    <td><?= htmlspecialchars($r['date_r']) ?></td>
                    <td><?= htmlspecialchars($r['heure']) ?></td>
                    <td>
                        <?php if ($r['statut'] === 'en_attente'): ?>
                            <span class="text-yellow-400">En attente</span>
                        <?php elseif ($r['statut'] === 'acceptée'): ?>
                            <span class="text-green-400">Acceptée</span>
                        <?php elseif ($r['statut'] === 'refusée'): ?>
                            <span class="text-red-400">Refusée</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center space-x-2">
                        <a href="reserv_button.php?id=<?= $r['id_reserv'] ?>&action=accept"
                        class="bg-emerald-500 px-3 py-1 rounded text-sm">Accepter</a>
                        <a href="reserv_button.php?id=<?= $r['id_reserv'] ?>&action=refuse"
                        class="bg-red-500 px-3 py-1 rounded text-sm">Refuser</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-white/70">Aucune réservation reçue.</p>
    <?php endif; ?>
</div>

</body>
</html>
