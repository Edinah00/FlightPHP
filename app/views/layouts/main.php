<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>benefits</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>Système de Livraison</h1>
            <ul>
                <li><a href="/deliveries">Livraisons</a></li>
                <li><a href="/deliveries/create">Nouvelle livraison</a></li>
                <li><a href="/benefits">Bénéfices</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="container">
        <?= $content ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 : binome ETU004285_ETU004280 </p>
        </div>
    </footer>
    
    <script src="/public/js/app.js"></script>
</body>
</html>