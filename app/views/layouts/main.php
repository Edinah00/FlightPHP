<?php
// ============================================
// FILE: app/views/layouts/main.php
// Layout principal avec navigation complète
// ============================================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Système de Livraison' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-link {
            @apply px-4 py-2 rounded-lg transition-colors duration-200;
        }
        .nav-link:hover {
            @apply bg-blue-100;
        }
        .nav-link.active {
            @apply bg-blue-500 text-white;
        }
        .danger-link {
            @apply text-red-600 hover:text-red-800 hover:bg-red-50;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg mb-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">DeliveryApp</span>
                </div>

                <!-- Menu principal -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="/deliveries" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/deliveries') !== false ? 'active' : '' ?>">
                        📦 Livraisons
                    </a>
                    
                    <a href="/zones" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/zones') !== false ? 'active' : '' ?>">
                        🗺️ Zones
                    </a>
                    
                    <a href="/benefits" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/benefits') !== false ? 'active' : '' ?>">
                        💰 Bénéfices
                    </a>

                    <!-- Dropdown Admin -->
                    <div class="relative group">
                        <button class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false ? 'active' : '' ?> flex items-center">
                            ⚙️ Admin
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="hidden group-hover:block absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-50">
                            <a href="/admin/stats" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                📊 Statistiques
                            </a>
                            <hr>
                            <a href="/admin/danger-zone" class="block px-4 py-3 danger-link rounded-b-lg">
                                ⚠️ Zone dangereuse
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bouton action rapide -->
                <a href="/deliveries/create" 
                   class="hidden md:inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle livraison
                </a>

                <!-- Menu mobile (hamburger) -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Menu mobile (caché par défaut) -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a href="/deliveries" class="nav-link">📦 Livraisons</a>
                    <a href="/zones" class="nav-link">🗺️ Zones</a>
                    <a href="/benefits" class="nav-link">💰 Bénéfices</a>
                    <a href="/admin/stats" class="nav-link">📊 Statistiques</a>
                    <a href="/admin/danger-zone" class="nav-link danger-link">⚠️ Zone dangereuse</a>
                    <hr class="my-2">
                    <a href="/deliveries/create" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-center">
                        + Nouvelle livraison
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Accueil</a>
            <?php
            $uri = trim($_SERVER['REQUEST_URI'], '/');
            $parts = explode('/', $uri);
            $breadcrumb = '';
            
            foreach ($parts as $index => $part) {
                if (empty($part) || is_numeric($part)) continue;
                
                $breadcrumb .= '/' . $part;
                $isLast = ($index === count($parts) - 1);
                
                // Traductions
                $translations = [
                    'deliveries' => 'Livraisons',
                    'zones' => 'Zones',
                    'benefits' => 'Bénéfices',
                    'admin' => 'Administration',
                    'create' => 'Créer',
                    'edit' => 'Modifier',
                    'stats' => 'Statistiques',
                    'danger-zone' => 'Zone dangereuse'
                ];
                
                $label = $translations[$part] ?? ucfirst($part);
                
                if ($isLast) {
                    echo ' / <span class="text-gray-900 font-semibold">' . $label . '</span>';
                } else {
                    echo ' / <a href="' . $breadcrumb . '" class="hover:text-blue-500">' . $label . '</a>';
                }
            }
            ?>
        </nav>
    </div>

    <!-- Contenu principal -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="mt-12 bg-white border-t border-gray-200">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                <div class="mb-4 md:mb-0">
                    <p>&copy; 2025 Système de Livraison - Examen Décembre 2025</p>
                    <p class="text-xs mt-1">FlightPHP MVC • MySQL • Tailwind CSS</p>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="/admin/stats" class="hover:text-blue-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistiques
                    </a>
                    
                    <a href="https://github.com/Edinah00/FlightPHP" target="_blank" 
                       class="hover:text-blue-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z" clip-rule="evenodd"/>
                        </svg>
                        GitHub
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Menu mobile toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        // Fermer le menu mobile quand on clique en dehors
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const btn = document.getElementById('mobileMenuBtn');
            
            if (!menu.contains(event.target) && !btn.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Confirmation avant suppression
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                    e.preventDefault();
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Format numbers with thousands separator
        document.querySelectorAll('[data-format="number"]').forEach(el => {
            const value = parseFloat(el.textContent);
            el.textContent = value.toLocaleString('fr-FR');
        });
    </script>

    <!-- Analytics (optionnel) -->
    <?php if (isset($_ENV['ANALYTICS_ID'])): ?>
    <script>
        // Votre code analytics ici
    </script>
    <?php endif; ?>
</body>
</html>