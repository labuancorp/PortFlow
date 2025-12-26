<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PortFlow' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        @if(auth()->user()->role !== 'client')
        <!-- Sidebar (Hidden for Clients) -->
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
                        <a href="{{ route('map.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('map.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3 text-teal-400 font-bold">GIS Map View</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('crew.terminal') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('crew.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3 text-indigo-400 font-bold">Crew Terminal</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analytics') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('analytics') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3 text-purple-400 font-bold">Analytics</span>
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
                    
                    @if(auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('agents.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('agents.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Agents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wharfs.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('wharfs.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Wharf Registry</span>
                        </a>
                    </li>
                    @endif
                    
                    <li>
                         <a href="{{ route('cargo.manifests.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('cargo.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                             <span class="ml-3 text-amber-400 font-bold">Cargo Logistics</span>
                         </a>
                    </li>
                    <li>
                         <a href="{{ route('warehouse.map') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('warehouse.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                             <span class="ml-3 text-amber-400 font-bold">Yard / Warehouse Map</span>
                         </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('billing.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="ml-3">Billing</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->role === 'admin')
                    <li class="mt-8 border-t border-slate-800 pt-4">
                        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Administration</p>
                        <a href="{{ route('admin.health') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('admin.health') ? 'bg-red-900/20 text-red-400' : 'text-slate-400 hover:bg-slate-800 hover:text-red-400' }}">
                            <svg class="w-5 h-5 group-hover:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span class="ml-3 font-bold">System Health</span>
                        </a>
                        <a href="{{ route('admin.audit') }}" class="mt-1 flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('admin.audit') ? 'bg-blue-900/20 text-blue-400' : 'text-slate-400 hover:bg-slate-800 hover:text-blue-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="ml-3 font-bold">Audit Trail</span>
                        </a>
                    </li>
                    @endif

                    <li class="mt-4 border-t border-slate-800 pt-4">
                        <a href="{{ route('agent.portal') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-emerald-400 hover:bg-slate-800 hover:text-emerald-300">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            <span class="ml-3 font-bold">Client Portal View</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-xs font-bold">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role ?? 'Visitor' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('settings') }}" title="Settings" class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                        <a href="{{ route('logout') }}" title="Sign Out" class="text-slate-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </aside>
        @endif

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 relative">
             {{ $slot }}
        </main>
    </div>
    @livewireScripts
    @stack('scripts')
</body>
</html>
