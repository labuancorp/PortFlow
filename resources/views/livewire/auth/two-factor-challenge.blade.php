<div class="min-h-screen flex items-center justify-center bg-slate-900 p-6">
    <div class="bg-white max-w-md w-full rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-indigo-600 p-8 text-center">
            <h1 class="text-2xl font-black text-white tracking-tight">Two-Factor Authentication</h1>
            <p class="text-indigo-200 mt-2 text-sm">Please verify your identity to continue.</p>
        </div>
        
        <div class="p-8">
            <div class="mb-6 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <p class="text-slate-600 text-sm">
                    We have sent a 6-digit verification code to your registered email address.
                </p>
                <p class="text-xs text-slate-400 mt-2 font-mono">
                    (Dev Note: Check Audit Logs or Notifications)
                </p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 text-green-600 text-xs font-bold rounded-lg text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit="verify" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Security Code</label>
                    <input type="text" wire:model="code" 
                           class="w-full text-center text-3xl font-mono tracking-[0.5em] font-bold text-slate-800 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-300"
                           placeholder="000000" maxlength="6">
                    @error('code') <span class="text-red-500 text-xs block mt-1 text-center">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all uppercase tracking-widest text-sm">
                    Verify Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <button wire:click="resendCode" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors">
                    Didn't receive code? Resend
                </button>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                 <a href="{{ route('logout') }}" class="text-xs font-bold text-slate-400 hover:text-red-500 transition-colors">Abort Login</a>
            </div>
        </div>
    </div>
</div>
