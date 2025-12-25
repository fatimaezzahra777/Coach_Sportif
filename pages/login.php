<?php
session_start();
require __DIR__  . '/../config/database.php';
require __DIR__ . '/../classes/Utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = Utilisateur::login($email, $password);

    if ($user) {
        $_SESSION['id_user'] = $user->getId();
        $_SESSION['nom'] = $user->getNom();
        $_SESSION['role'] = $user->getRole();

        if ($user->getRole() === 'sportif') {
            header('Location: sportif.php');
            exit;
        } elseif ($user->getRole() === 'coach') {
            header('Location: coach.php');
            exit;
        } else {
            header('Location: index.php');
            exit;
        }

    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportCoach - Plateforme de Coaching Sportif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
</head>
<body class="relative min-h-screen bg-black overflow-hidden text-white">
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
                    <a href="../public/index.php" class="hover:text-emerald-400 transition">Accueil</a>
                    <a href="login.php" class="text-emerald-400 font-bold">Connexion</a>
                </div>

                <button class="md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-2">
                <a href="index.php" class="block hover:text-emerald-400">Accueil</a>
                <a href="./pages/login.php" class="block text-emerald-400 font-bold">Connexion</a>
            </div>
        </div>
    </nav>

    <div id="login" class="page">
        <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-black">
            <div class="absolute inset-0">
                <div class="absolute -left-40 top-1/2 w-[700px] h-[700px] bg-gradient-to-r from-blue-500 to-green-500 opacity-30 rounded-full blur-3xl"></div>
                <div class="absolute -right-40 top-1/3 w-[700px] h-[700px] bg-gradient-to-r from-green-500 to-blue-500 opacity-30 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl p-10 text-white">
                <div class="text-center mb-8">
                    <i class="fas fa-dumbbell text-emerald-400 text-6xl mb-4"></i>
                    <h2 class="text-3xl font-bold">Connexion</h2>
                    <p class="text-gray-600 mt-2">Accédez à votre espace personnel</p>
                </div>
                
                <form id="login" class="space-y-6" method="POST" action="">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input 
                            name="email"
                            type="email"
                            placeholder="Email"
                            class="w-full bg-white/20 text-white placeholder-gray-300
                                rounded-full px-6 py-3 focus:outline-none 
                                focus:ring-2 focus:ring-emerald-400"
                            required
                        />
                        <span class="text-red-500 text-sm hidden" id="emailError">Email invalide</span>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                        <input 
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full bg-white/20 text-white placeholder-gray-300
                                rounded-full px-6 py-3 focus:outline-none 
                                focus:ring-2 focus:ring-emerald-400"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" />
                            <span class="text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="text-sm text-white hover:underline">Mot de passe oublié?</a>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-white/30 hover:bg-white/40 
                            text-white py-3 rounded-full 
                            transition font-semibold backdrop-blur-md"
                    >
                    Se connecter
                    </button>

                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Pas encore de compte? 
                        <a href="register.php" onclick="showPage('register')" class="text-white hover:underline ml-1 font-medium">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <script src="./assets/js/index.js"></script>

</body>
</html>