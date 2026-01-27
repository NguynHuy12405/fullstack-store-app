<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Fashion Shop')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @yield('link')
    @yield('head-script')
    @yield('style')
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden bg-gray-50">
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden"></div>
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-[#0f1117] text-white transition-transform duration-300 transform -translate-x-full md:translate-x-0 md:static md:inset-0 flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-slate-700 shadow-md">
                <span class="text-2xl font-bold tracking-wider">ADMIN STORE</span>
            </div>
            @include('admin.layouts.navigation')
        </aside>
        <main class="flex-1 flex flex-col overflow-hidden">
            @include('admin.layouts.header')
            @yield('content')
        </main>
        @yield('pop-add')
        @yield('pop-edit')
    </div>
    @stack('scripts')
</body>

</html>
