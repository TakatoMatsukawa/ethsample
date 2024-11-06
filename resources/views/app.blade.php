<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', '佐川町立図書館データベース') }}</title>

    <meta name="description" content=" ">
    <meta name="author" content="">
    <meta name="keyword" content="">

    <meta name="robots" content="noindex" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Open Graph Meta -->
    <meta property="og:title" content="佐川町立図書館データベース" />
    <meta property="og:site_name" content="佐川町立図書館データベース">
    <meta property="og:description" content="" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    {{-- <meta property="og:image" content={{ asset('common/img/ogimg.jpg') }} /> --}}
    <meta property="og:locale" content="ja_JP" />
    <meta property="fb:app_id" content="">
    <meta name="p:domain_verify" content="" />

    @routes
    @if (request()->root() === config('app.admin_url'))
        @vite(['resources/assets/admin/scss/main.scss', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @else
        @vite(['resources/assets/site/scss/main.scss', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @endif

    @inertiaHead
    <script>
        var __locale = '{{ app()->getLocale() }}'
    </script>

</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
