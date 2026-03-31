<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homelab Dashboard</title>
    <!-- PWA / Mobile / Desktop Install -->
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }
    </script>
    <style>
        :root {
            --background: 0 0% 3.9%;
            --foreground: 0 0% 98%;
            --card: 0 0% 3.9%;
            --primary: 263.4 70% 50.4%;
            --muted: 0 0% 14.9%;
            --accent: 0 0% 14.9%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #09090b;
            color: #fafafa;
        }

        h1, h2, h3, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(24, 24, 27, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(39, 39, 42, 1);
        }

        .sidebar-item-active {
            background: rgba(39, 39, 42, 1);
            color: #fafafa;
        }

        .grid-bg {
            background-image: radial-gradient(circle at 2px 2px, rgba(39, 39, 42, 0.3) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        sidebar: '#09090b',
                        accent: '#18181b',
                    },
                    borderRadius: {
                        'xl': '0.75rem',
                        '2xl': '1rem',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen grid-bg overflow-x-hidden">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-8 ml-64">
