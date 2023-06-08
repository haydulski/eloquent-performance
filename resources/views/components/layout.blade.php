<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
<header class="py-6 px-4 flex bg-gray-600 bg-gradient-to-bl from-gray-700/50 via-transparent gap-8">
    <a class="text-basic text-gray-200 font-bold hover:text-red-500" href="/">Home</a>
    <a class="text-basic text-gray-200 font-bold hover:text-red-500" href="{{route('users.status.bad')}}">Statuses</a>
    <a class="text-basic text-gray-200 font-bold hover:text-red-500" href="{{route('users.status.good')}}">Fixed statuses</a>
</header>
<div
    class="relative sm:flex sm:justify-center sm:items-start min-h-screen bg-center bg-dots-lighter bg-gray-900 selection:bg-red-500 selection:text-white
    pt-12">
    <div class="w-[50vw] mx-auto p-6 lg:p-8">
        {{$slot}}
    </div>
</div>
</body>
</html>
