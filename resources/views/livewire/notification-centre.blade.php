<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Notification Centre</h1>
            <p class="text-slate-500">Stay updated on your port operations and deadlines</p>
        </div>
        @if($unreadCount > 0)
        <button wire:click="markAllAsRead" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all">
            Mark All as Read
        </button>
        @endif
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl border border-emerald-100 font-bold mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-6">
        <div class="flex gap-3 overflow-x-auto">
            <button wire:click="setFilter('all')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Notifications
            </button>
            <button wire:click="setFilter('unread')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2 {{ $filter === 'unread' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Unread
                @if($unreadCount > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                @endif
            </button>
            <button wire:click="setFilter('warning')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2 {{ $filter === 'warning' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⚠️ Warnings
                @if($warningCount > 0)
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $warningCount }}</span>
                @endif
            </button>
            <button wire:click="setFilter('overdue')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2 {{ $filter === 'overdue' ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔴 Overdue
                @if($overdueCount > 0)
                <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $overdueCount }}</span>
                @endif
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
        <div wire:click="viewDetails({{ $notification->id }})" class="bg-white rounded-2xl p-5 border-2 transition-all cursor-pointer hover:shadow-lg {{ $notification->is_read ? 'border-slate-100' : 'border-indigo-200 bg-indigo-50/30' }} {{ $notification->type === 'overdue' ? 'border-l-4 border-l-red-500' : ($notification->type === 'warning' ? 'border-l-4 border-l-amber-500' : '') }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    <!-- Icon -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl
                        {{ $notification->type === 'overdue' ? 'bg-red-100' : ($notification->type === 'warning' ? 'bg-amber-100' : 'bg-blue-100') }}">
                        @if($notification->category === 'marine')
                            ⚓
                        @elseif($notification->category === 'yard')
                            📦
                        @elseif($notification->category === 'asset')
                            🚜
                        @else
                            📢
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-black text-slate-900 {{ $notification->is_read ? '' : 'text-indigo-900' }}">
                                {{ $notification->title }}
                            </h3>
                            @if(!$notification->is_read)
                            <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $notification->message }}</p>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if($notification->deadline_at)
                            <span class="flex items-center gap-1 {{ $notification->deadline_at->isPast() ? 'text-red-600' : 'text-amber-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Deadline: {{ $notification->deadline_at->format('d M, H:i') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Type Badge -->
                <div>
                    @if($notification->type === 'overdue')
                    <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-black uppercase">OVERDUE</span>
                    @elseif($notification->type === 'warning')
                    <span class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 text-xs font-black uppercase">WARNING</span>
                    @else
                    <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-black uppercase">INFO</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 border border-slate-200 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                🔔
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">No Notifications</h3>
            <p class="text-slate-500">You're all caught up! No new notifications at this time.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedNotification)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl
                            {{ $selectedNotification->type === 'overdue' ? 'bg-red-100' : ($selectedNotification->type === 'warning' ? 'bg-amber-100' : 'bg-blue-100') }}">
                            @if($selectedNotification->category === 'marine')
                                ⚓
                            @elseif($selectedNotification->category === 'yard')
                                📦
                            @elseif($selectedNotification->category === 'asset')
                                🚜
                            @else
                                📢
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 mb-1">{{ $selectedNotification->title }}</h3>
                            <p class="text-sm text-slate-500">{{ $selectedNotification->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="space-y-4">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-slate-700 leading-relaxed">{{ $selectedNotification->message }}</p>
                    </div>

                    @if($selectedNotification->deadline_at)
                    <div class="flex items-center gap-3 p-4 rounded-xl {{ $selectedNotification->deadline_at->isPast() ? 'bg-red-50 border border-red-200' : 'bg-amber-50 border border-amber-200' }}">
                        <svg class="w-6 h-6 {{ $selectedNotification->deadline_at->isPast() ? 'text-red-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider {{ $selectedNotification->deadline_at->isPast() ? 'text-red-600' : 'text-amber-600' }}">
                                {{ $selectedNotification->deadline_at->isPast() ? 'Overdue Since' : 'Deadline' }}
                            </p>
                            <p class="font-black {{ $selectedNotification->deadline_at->isPast() ? 'text-red-900' : 'text-amber-900' }}">
                                {{ $selectedNotification->deadline_at->format('d M Y, H:i') }}
                                <span class="text-sm font-normal">({{ $selectedNotification->deadline_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Reference Info -->
                    @if($selectedNotification->reference_type)
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Reference</p>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-sm font-bold">
                                {{ class_basename($selectedNotification->reference_type) }} #{{ $selectedNotification->reference_id }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-slate-200 flex gap-3">
                    <button wire:click="closeModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold uppercase tracking-widest transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
