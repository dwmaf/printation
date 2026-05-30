<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @viteReactRefresh 
    @vite(['resources/js/app.tsx', "resources/js/Pages/{$page['component']}.tsx"])
    @routes
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Koulen&display=swap"
        rel="stylesheet" />
    @inertiaHead
</head>

<body>
    <!-- Initial Loader (Hanya muncul saat pertama kali buka web) -->
    <div id="initial-loader" style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: #f9fafb;">
        <style>
            .spinner {
                width: 40px; height: 40px;
                border: 4px solid #e5e7eb; border-top: 4px solid #3b82f6;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        </style>
        <div class="spinner"></div>
    </div>

    @inertia
</body>

</html>