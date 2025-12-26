<div class="p-8 max-w-4xl mx-auto space-y-8" 
     x-data="{ 
        toast: { show: false, message: '', type: 'success' },
        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 4000);
        }
     }"
     @notify.window="showToast($event.detail.message, $event.detail.type || 'success')">
    
    <!-- Notification Toast -->
    <template x-if="toast.show">
        <div class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8">
            <div :class="toast.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-teal-500/20 text-teal-400'" class="p-2 rounded-lg">
                <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase" :class="toast.type === 'error' ? 'text-red-400' : 'text-teal-400'" x-text="toast.type === 'error' ? 'Error' : 'Success'"></h4>
                <p class="text-sm text-slate-300" x-text="toast.message"></p>
            </div>
        </div>
    </template>

    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Account Settings</h1>
        <p class="text-slate-500 mt-2">Manage your profile, security, and preferences.</p>
    </div>

    <!-- Profile Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Profile Information</h2>
            <p class="text-sm text-slate-500">Update your account's profile information and email address.</p>
        </div>
        <div class="p-8">
            <form wire:submit="updateProfile" class="space-y-6 max-w-xl">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500 font-medium">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500 font-medium">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Password Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-800">Update Password</h2>
                <p class="text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-8">
                <form wire:submit="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Current Password</label>
                        <input type="password" wire:model="current_password" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">New Password</label>
                        <input type="password" wire:model="password" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/10">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2FA Settings -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-800">Two-Factor Authentication</h2>
                <p class="text-sm text-slate-500">Add additional security to your account using two-factor authentication.</p>
            </div>
            <div class="p-8 flex-1 flex flex-col justify-between">
                <div>
                    @if($is_2fa_enabled)
                        <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex items-start gap-3 mb-6">
                            <svg class="w-6 h-6 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h4 class="font-bold text-teal-800 text-sm">You have enabled two-factor authentication.</h4>
                                <p class="text-sm text-teal-600 mt-1">When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication.</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start gap-3 mb-6">
                            <svg class="w-6 h-6 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <div>
                                <h4 class="font-bold text-slate-700 text-sm">You have not enabled two-factor authentication.</h4>
                                <p class="text-sm text-slate-500 mt-1">When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your email.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button wire:click="toggleTwoFactor" 
                        class="px-6 py-2 rounded-lg font-bold transition-all shadow-lg flex items-center gap-2
                        {{ $is_2fa_enabled 
                            ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 shadow-red-900/5' 
                            : 'bg-teal-600 text-white hover:bg-teal-500 shadow-teal-900/20' }}">
                        @if($is_2fa_enabled)
                            Disable 2FA
                        @else
                            Enable 2FA
                        @endif
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
