<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportCoach - Plateforme de Coaching Sportif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="./assets/css/index.css" rel="stylesheet">
</head>
<body class="relative min-h-screen bg-black overflow-x-hidden text-white">
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
                    <a href="profil.php" class="hover:text-emerald-400 transition">Profile</a>
                    <a href="coach.php" class="text-emerald-400 font-bold">Dashboard</a>
                    <a href="logout.php" class="hover:text-emerald-400 transition">Deconnexion</a>
                </div>
                <button class="md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-2">
                <a href="profil.php" class="block hover:text-emerald-400">Profile</a>
                <a href="coach.php" class="block text-emerald-400 font-bold">Dashboard</a>
                <a href="logout.php" class="block hover:text-emerald-400">Deconnexion</a>
            </div>
        </div>
    </nav>
       <div class="max-w-7xl mx-auto px-4 py-16">
            <h2 class="text-4xl font-bold text-emerald-400 mb-8 text-center">Nos Coachs Vedettes</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" id="featuredCoaches"></div>
        </div>
        <div class="grid md:grid-cols-4 px-32 gap-6 mb-12">
            <div class="bg-white/10 p-6 rounded-xl">
                Demandes en attente<br>
                <span class="text-2xl font-bold"><?= $attente ?></span>
            </div>

            <div class="bg-white/10 p-6 rounded-xl">
                Séances aujourd'hui<br>
                <span class="text-2xl font-bold"></span>
            </div>

            <div class="bg-white/10 p-6 rounded-xl">
                Séances demain<br>
                <span class="text-2xl font-bold"></span>
            </div>

            <div class="bg-white/10 p-6 rounded-xl">
                Prochaine séance<br>
            </div>
       </div>
       

       

</div>

     <script src="./assets/js/index.js" ></script>
    
</body>
</html>