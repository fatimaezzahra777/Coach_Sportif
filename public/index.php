<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportCoach - Plateforme de Coaching Sportif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link href="./assets/css/index.css" rel="stylesheet">
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

        <nav class="bg-white/10 backdrop-blur-xl border-b border-white/20 text-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-2 cursor-pointer" onclick="showPage('home')">
                        <i class="fas fa-dumbbell text-3xl text-emerald-400"></i>
                        <span class="text-2xl font-bold">SportCoach</span>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="../public/index.php" class="text-emerald-400 font-bold">Accueil</a>
                        <a href="../pages/login.php" class="hover:text-emerald-400 transition">Connexion</a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-2">
                    <a href="../public/index.php" class="font-bold underline">Accueil</a>
                    <a href="../pages/login.php">Connexion</a>
                </div>
            </div>
        </nav>

    <!-- Page d'accueil -->

        <div id="home" class="page active relative bg-cover bg-center min-h-screen"
            style="background-image: url('../assets/images/hero.jpg');">

            <!-- Overlay sombre -->
            <div class="absolute inset-0 bg-black/70"></div>

            <!-- Hero Section -->
            <div class="relative z-10 py-64 px-32 ">
                <div class="max-w-xxl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                    <!-- Texte -->
                    <div class="text-center md:text-left">
                        <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
                            Trouvez Votre Coach Sportif Idéal
                        </h1>

                        <p class="text-xl text-gray-300 mb-10">
                            Une plateforme moderne pour réserver des séances sportives
                            personnalisées avec des coachs certifiés.
                        </p>

                  <div class="flex flex-col sm:flex-row gap-4">
                            <a href="coach.php">
                                <button class="bg-white/30 backdrop-blur-md px-8 py-4 rounded-full
                                            hover:bg-white/40 transition text-white font-semibold shadow-lg">
                                    Découvrir les Coachs
                                </button>
                            </a>
                            <a href="inscri.php">
                                <button class="border border-white/40 px-8 py-4 rounded-full
                                            hover:bg-white/20 transition text-white font-semibold">
                                    Devenir Coach
                                </button>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <section id="about" class="relative py-24 px-4 bg-black text-white">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                <div>
                    <img src="../assets/images/about.jpg"
                        class="rounded-3xl shadow-2xl mx-auto"
                        alt="À propos SportCoach">
                </div>

                <!-- Contenu -->
                <div>
                    <h2 class="text-4xl font-bold mb-6">
                        À propos de <span class="text-emerald-400">SportCoach</span>
                    </h2>

                    <p class="text-gray-300 mb-6 leading-relaxed">
                        SportCoach est une plateforme innovante dédiée à la mise en relation
                        entre sportifs passionnés et coachs professionnels certifiés.
                        Notre objectif est de faciliter l'accès à un coaching sportif
                        personnalisé, efficace et sécurisé.
                    </p>

                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Que vous soyez débutant ou athlète confirmé, SportCoach vous accompagne
                        dans votre progression grâce à des séances adaptées à vos objectifs.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white/10 backdrop-blur-xl border border-white/20
                                    rounded-2xl p-6 text-center">
                            <i class="fas fa-dumbbell text-emerald-400 text-4xl mb-3"></i>
                            <h3 class="font-bold text-lg">Expertise</h3>
                        </div>

                        <div class="bg-white/10 backdrop-blur-xl border border-white/20
                                    rounded-2xl p-6 text-center">
                            <i class="fas fa-shield-alt text-emerald-400 text-4xl mb-3"></i>
                            <h3 class="font-bold text-lg">Sécurité</h3>
                        </div>

                        <div class="bg-white/10 backdrop-blur-xl border border-white/20
                                    rounded-2xl p-6 text-center">
                            <i class="fas fa-heart text-emerald-400 text-4xl mb-3"></i>
                            <h3 class="font-bold text-lg">Motivation</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="./assets/js/index.js" ></script>

</body>
</html>