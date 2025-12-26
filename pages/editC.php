<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Coach.php';
require_once __DIR__ . '/../classes/Utilisateur.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'coach') {
    header("Location: login.php");
    exit;
}

$pdo = Database::getConnection();

// Récupérer le coach
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
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $biographie = $_POST['biographie'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $certif = $_POST['certif'] ?? '';

    $photoPath = $coachObj->getPhoto(); 
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['photo']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            $photoPath = 'assets/images/' . $fileName;
        }
    }

    // Mise à jour dans la base
    $stmt = $pdo->prepare("
        UPDATE users SET nom = :nom, email = :email, telephone = :telephone WHERE id_user = :id_user
    ");
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'telephone' => $telephone,
        'id_user' => $_SESSION['id_user']
    ]);

    $stmt = $pdo->prepare("
        UPDATE coach SET biographie = :biographie, experience = :experience, certif = :certif, photo = :photo
        WHERE id_coach = :id_coach
    ");
    $stmt->execute([
        'biographie' => $biographie,
        'experience' => $experience,
        'certif' => $certif,
        'photo' => $photoPath,
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
    <title>Modifier Profil Coach</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen p-10">

<div class="max-w-3xl mx-auto bg-white/10 p-8 rounded-xl">
    <h1 class="text-3xl font-bold text-emerald-400 mb-6">Modifier mon profil</h1>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <label class="block">
            Nom :
            <input type="text" name="nom" value="<?= htmlspecialchars($coachObj->getNom()) ?>" class="w-full p-2 rounded text-black">
        </label>

        <label class="block">
            Email :
            <input type="email" name="email" value="<?= htmlspecialchars($coachObj->getEmail()) ?>" class="w-full p-2 rounded text-black">
        </label>

        <label class="block">
            Téléphone :
            <input type="text" name="telephone" value="<?= htmlspecialchars($coachObj->getTelephone()) ?>" class="w-full p-2 rounded text-black">
        </label>

        <label class="block">
            Biographie :
            <textarea name="biographie" class="w-full p-2 rounded text-black"><?= htmlspecialchars($coachObj->getBiographie()) ?></textarea>
        </label>

        <label class="block">
            Expérience :
            <input type="text" name="experience" value="<?= htmlspecialchars($coachObj->getExperience()) ?>" class="w-full p-2 rounded text-black">
        </label>

        <label class="block">
            Certification :
            <input type="text" name="certif" value="<?= htmlspecialchars($coachObj->getCertif()) ?>" class="w-full p-2 rounded text-black">
        </label>

        <label class="block">
            Photo de profil :
            <input type="file" name="photo" class="w-full p-2 rounded text-black">
            <?php if ($coachObj->getPhoto() && file_exists(__DIR__ . '/../' . $coachObj->getPhoto())): ?>
                <img src="<?= htmlspecialchars($coachObj->getPhoto()) ?>" class="w-28 h-28 rounded-full mt-2">
            <?php endif; ?>
        </label>

        <button type="submit" class="bg-emerald-400 text-black px-6 py-2 rounded font-bold">Enregistrer</button>
    </form>
</div>

</body>
</html>
