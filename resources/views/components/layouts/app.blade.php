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
                <ul class="space-y-6 px-3">
                    
                    <!-- Core Operations -->
                    <li>
                        <div class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Core Operations</div>
                        <ul class="space-y-1">
                            @if(auth()->user()->role !== 'hse')
                            <li>
                                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    <span class="ml-3">Command Center</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('home') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="ml-3">Berth Planner</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('analytics') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('analytics') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    <span class="ml-3">Analytics</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Marine & Logistics -->
                    <li>
                        <div class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Marine & Logistics</div>
                        <ul class="space-y-1">
                            @if(auth()->user()->role !== 'hse')
                            <li>
                                <a href="{{ route('vessels.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('vessels.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span class="ml-3">Vessels</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cargo.manifests.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('cargo.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <span class="ml-3">Cargo Logistics</span>
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'agent' && auth()->user()->organization->warehouse_subscribed))
                            <li>
                                <a href="{{ route('warehouse.map') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('warehouse.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    <span class="ml-3">Yard Operations</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('crew.terminal') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('crew.terminal') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="ml-3">Crew Terminal</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('map.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('map.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    <span class="ml-3">GIS Map View</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gate.scanner') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('gate.scanner') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <span class="ml-3">Gate Scanner</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('ops.mobile') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('ops.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="ml-3">Mobile Ops</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Assets & Facilities -->
                    <li>
                        <div class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Assets & Facilities</div>
                        <ul class="space-y-1">
                            @if(in_array(auth()->user()->role, ['admin', 'hse']))
                            <li>
                                <a href="{{ route('assets.inventory') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('assets.inventory') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                                    <span class="ml-3">Asset Inventory</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('warehouse.spatial.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('warehouse.spatial.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                    <span class="ml-3">Space Lease</span>
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <a href="{{ route('assets.booking') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('assets.booking') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="ml-3">Rentals & Booking</span>
                                </a>
                            </li>
                            
                            @if(auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('wharfs.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('wharfs.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <span class="ml-3">Wharf Registry</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Safety & Compliance -->
                    <li>
                        <div class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Safety & Compliance</div>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('hse.permits.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('hse.permits.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span class="ml-3">Permit to Work</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hse.incidents.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('hse.incidents.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="ml-3">Safety Intel</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- System & Admin -->
                    <li>
                         <div class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">System & Admin</div>
                         <ul class="space-y-1">
                            @if(auth()->user()->role !== 'hse')
                            <li>
                                <a href="{{ route('billing.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="ml-3">Billing & Invoices</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('agents.index') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('agents.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="ml-3">Agent Registry</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.health') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('admin.health') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <span class="ml-3">System Health</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.audit') }}" class="flex items-center px-3 py-2 rounded-lg group {{ request()->routeIs('admin.audit') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <span class="ml-3">Audit Trail</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->role === 'agent')
                            <li>
                                <a href="{{ route('agent.portal') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-emerald-400 hover:bg-slate-800 hover:text-emerald-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span class="ml-3 font-bold">Client Portal View</span>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->role !== 'hse')
                            <li>
                                <a href="{{ route('gate.request') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-indigo-400 hover:bg-slate-800 hover:text-indigo-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span class="ml-3 font-bold">Vendor Request Portal</span>
                                </a>
                            </li>
                            @endif
                         </ul>
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
