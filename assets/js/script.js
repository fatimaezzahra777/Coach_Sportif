let selectedRole = 'athlete';

        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
            window.scrollTo(0, 0);
        }

        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }

        function selectRole(role) {
            selectedRole = role;
            document.getElementById("role").value = role;

            const athleteBtn = document.getElementById('athleteBtn');
            const coachBtn = document.getElementById('coachBtn');
            const niveau = document.getElementById("niveau");
            
            const coachFields = document.getElementById('coachFields');

            // reset styles
            athleteBtn.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-600');
            coachBtn.classList.remove('bg-blue-50', 'border-blue-600', 'text-blue-600');

            athleteBtn.classList.add('border-gray-300', 'text-gray-600');
            coachBtn.classList.add('border-gray-300', 'text-gray-600');

            if (role === 'sportif') {
                athleteBtn.classList.remove('border-gray-300', 'text-gray-600');
                athleteBtn.classList.add('bg-white/10', 'border-emerald-400', 'text-emerald-400');
                niveau.style.display = "block";
                niveau.required = true;
                coachFields.classList.add('hidden');
            } else {
                coachBtn.classList.remove('border-gray-300', 'text-gray-600');
                coachBtn.classList.add('bg-white/10', 'border-emerald-400', 'text-emerald-400');
                niveau.style.display = "none";
                niveau.required = false;
                niveau.value = "";
                coachFields.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            selectRole('sportif');
        });
       
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            
            const phoneRegex = /^(\+212|0)[67][0-9]{8}$/;

          
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

          
            if (!emailRegex.test(email)) {
                Swal.fire('Erreur', 'Email invalide', 'error');
                return false;
            }

            if (!phoneRegex.test(phone)) {
                Swal.fire('Erreur', 'Téléphone invalide (ex: +2126XXXXXXXX)', 'error');
                return false;
            }

            if (!passwordRegex.test(password)) {
                Swal.fire('Erreur', 'Le mot de passe doit avoir au moins 8 caractères, 1 majuscule et 1 chiffre', 'error');
                return false;
            }

            if (password !== confirmPassword) {
                Swal.fire('Erreur', 'Les mots de passe ne correspondent pas', 'error');
                return false;
            }

            return true;
        }

      
        function handleRegister(event) {
            event.preventDefault();

            if (!validateForm()) return;

            Swal.fire({
                icon: 'success',
                title: 'Compte créé',
                text: 'Votre compte a été créé avec succès',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                // Ici tu peux envoyer le formulaire si PHP est prêt
                // document.getElementById('registerForm').submit();
            });
        }



        function handleLogin(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Connexion réussie',
                text: 'Bienvenue sur SportCoach',
                confirmButtonColor: '#2563eb'
            });

            if (selectedRole === 'coach') {
                showPage('coach-dashboard');
            } else {
                showPage('athlete-dashboard');
            }
        }

        function handleRegister(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Compte créé',
                text: 'Votre compte a été créé avec succès',
                confirmButtonColor: '#2563eb'
            });

            if (selectedRole === 'coach') {
                showPage('coach-dashboard');
            } else {
                showPage('athlete-dashboard');
            }
        }

        // Données fictives coachs
        const coaches = [
            { name: "Ahmed Benali", sport: "Football", city: "Casablanca", price: "300 DH" },
            { name: "Sara El Amrani", sport: "Tennis", city: "Rabat", price: "400 DH" },
            { name: "Youssef Karim", sport: "Natation", city: "Marrakech", price: "350 DH" },
        ];

        function loadCoaches() {
            const list = document.getElementById('coachesList');
            if (!list) return;

            list.innerHTML = '';
            coaches.forEach(c => {
                list.innerHTML += `
                    <div class="bg-white rounded-xl shadow-lg p-6 hover-scale">
                        <h3 class="text-xl font-bold mb-2">${c.name}</h3>
                        <p class="text-gray-600">${c.sport}</p>
                        <p class="text-gray-500">${c.city}</p>
                        <p class="font-bold text-blue-600 mt-2">${c.price}</p>
                        <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            Réserver
                        </button>
                    </div>
                `;
            });
        }

        document.addEventListener('DOMContentLoaded', loadCoaches);
