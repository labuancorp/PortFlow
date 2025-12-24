<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PortFlow' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col transition-all duration-300">
            <div class="p-6 border-b border-slate-800">
                <span class="text-xl font-bold tracking-tight text-teal-400">PortFlow</span>
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Command Center</span>
                        </a>
                    </li>
                     <li>
                        <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('home') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Berth Planner</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vessels.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('vessels.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Vessels</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wharfs.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('wharfs.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Wharf Registry</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('billing.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Billing</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-xs font-bold">
                        AD
                    </div>
                    <div>
                        <p class="text-sm font-medium">Admin User</p>
                        <p class="text-xs text-slate-400">Control Room</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 relative">
             {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>
