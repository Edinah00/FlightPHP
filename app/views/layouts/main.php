<?php
// ============================================
// FILE: app/views/layouts/main.php
// Layout principal avec navigation complète - Version améliorée
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Navigation Links */
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover {
            background: linear-gradient(135deg, #EBF4FF 0%, #E0F2FE 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #3B82F6;
            border-radius: 50%;
        }
        
        /* Danger Link */
        .danger-link {
            color: #DC2626;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .danger-link:hover {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            color: #991B1B;
            transform: translateX(4px);
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid #E5E7EB;
            backdrop-filter: blur(10px);
        }
        
        .group:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .dropdown-item:hover {
            border-left-color: #3B82F6;
            padding-left: 1.25rem;
        }
        
        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        /* Mobile Menu Animation */
        #mobileMenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-in-out;
        }
        
        #mobileMenu.show {
            max-height: 500px;
        }
        
        /* Breadcrumb */
        .breadcrumb-link {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .breadcrumb-link:hover {
            color: #3B82F6;
        }
        
        .breadcrumb-link:hover::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #3B82F6, #60A5FA);
            border-radius: 2px;
        }
        
        /* Alert Animation */
        .alert {
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Footer Links */
        .footer-link {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #3B82F6;
            transition: width 0.3s ease;
        }
        
        .footer-link:hover::after {
            width: 100%;
        }
        
        /* Navbar Shadow on Scroll */
        .navbar-scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        /* Logo Animation */
        .logo-icon {
            transition: all 0.3s ease;
        }
        
        .logo-icon:hover {
            transform: rotate(360deg) scale(1.1);
        }
        
        /* Smooth Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3B82F6, #2563EB);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #2563EB, #1D4ED8);
        }
        
        /* Mobile Menu Button Animation */
        .hamburger-line {
            transition: all 0.3s ease;
        }
        
        .hamburger-active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger-active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger-active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav id="navbar" class="glass-effect sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent hover:from-blue-500 hover:to-blue-300 transition-all">
                        DeliveryApp
                    </span>
                </a>

                <!-- Menu principal -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="/deliveries" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/deliveries') !== false ? 'active' : '' ?>">
                        Livraisons
                    </a>
                    
                    <a href="/zones" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/zones') !== false ? 'active' : '' ?>">
                        Zones
                    </a>
                    
                    <a href="/benefits" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/benefits') !== false ? 'active' : '' ?>">
                        Bénéfices
                    </a>

                    <!-- Dropdown Admin -->
                    <div class="relative group">
                        <button class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false ? 'active' : '' ?>">
                            Admin
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl overflow-hidden">
                            <a href="/admin/stats" class="dropdown-item block px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50">
                                <span class="font-medium">Statistiques</span>
                            </a>
                            <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                            <a href="/admin/danger-zone" class="dropdown-item danger-link block px-4 py-3">
                                <span class="font-medium">Zone dangereuse</span>
                            </a>
                        </div>
                    </div>
                </div>

              

                <!-- Menu mobile (hamburger) -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-6 h-5 flex flex-col justify-between">
                        <span class="hamburger-line w-full h-0.5 bg-gray-600 rounded"></span>
                        <span class="hamburger-line w-full h-0.5 bg-gray-600 rounded"></span>
                        <span class="hamburger-line w-full h-0.5 bg-gray-600 rounded"></span>
                    </div>
                </button>
            </div>

            <!-- Menu mobile -->
            <div id="mobileMenu" class="md:hidden">
                <div class="flex flex-col space-y-2 pb-4">
                    <a href="/deliveries" class="nav-link justify-start">
                        Livraisons
                    </a>
                    <a href="/zones" class="nav-link justify-start">
                        Zones
                    </a>
                    <a href="/benefits" class="nav-link justify-start">
                        Bénéfices
                    </a>
                    <a href="/admin/stats" class="nav-link justify-start">
                        Statistiques
                    </a>
                    <a href="/admin/danger-zone" class="nav-link danger-link justify-start">
                        Zone dangereuse
                    </a>
                    <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-2"></div>
                    <a href="/deliveries/create" class="px-4 py-2.5 btn-primary text-white rounded-lg text-center font-medium">
                         Nouvelle livraison
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4">
        <nav class="text-sm text-gray-600 flex items-center space-x-2">
            <a href="/" class="breadcrumb-link hover:text-blue-500 font-medium">Accueil</a>
            <?php
            $uri = trim($_SERVER['REQUEST_URI'], '/');
            $parts = explode('/', $uri);
            $breadcrumb = '';
            
            foreach ($parts as $index => $part) {
                if (empty($part) || is_numeric($part)) continue;
                
                $breadcrumb .= '/' . $part;
                $isLast = ($index === count($parts) - 1);
                
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
                
                echo '<span class="text-gray-400">/</span>';
                
                if ($isLast) {
                    echo '<span class="text-gray-900 font-semibold">' . $label . '</span>';
                } else {
                    echo '<a href="' . $breadcrumb . '" class="breadcrumb-link hover:text-blue-500">' . $label . '</a>';
                }
            }
            ?>
        </nav>
    </div>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 pb-12">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="mt-12 glass-effect border-t border-gray-200">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                <div class="mb-4 md:mb-0 text-center md:textleft">
                    <p class="font-semibold text-gray-800">etu004280_004285</p>
                    <p class="text-xs mt-1 flex items-center justify-center md:justify-start gap-2 flex-wrap">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md font-medium">FlightPHP MVC</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md font-medium">MySQL</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md font-medium">Tailwind CSS</span>
                    </p>
                </div>
                
            
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Menu mobile toggle avec animation
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
            this.classList.toggle('hamburger-active');
        });

        // Fermer le menu mobile quand on clique en dehors
        document.addEventListener('click', function(event) {
            if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                mobileMenu.classList.remove('show');
                mobileMenuBtn.classList.remove('hamburger-active');
            }
        });

        // Navbar shadow on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Format numbers with thousands separator
        document.querySelectorAll('[data-format="number"]').forEach(el => {
            const value = parseFloat(el.textContent);
            if (!isNaN(value)) {
                el.textContent = value.toLocaleString('fr-FR');
            }
        });

        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s';
                document.body.style.opacity = '1';
            }, 100);
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