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
    @inertia
</body>

</html>