<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-[#0a0c10] font-sans">
    <!-- Premium Dynamic Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-violet-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10 px-4">
        <div class="bg-white/5 backdrop-blur-2xl p-10 rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.5)] border border-white/10 relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
            
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-indigo-500/40">P</div>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight">
                    Reset Access
                </h2>
                <p class="mt-3 text-sm text-slate-400 font-medium">
                    Enter your endpoint to initiate recovery protocol.
                </p>
            </div>

            @if($message)
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold leading-relaxed">
                    {{ $message }}
                </div>
            @endif

            <form class="space-y-6" wire:submit.prevent="resetPassword">
                <div>
                    <label for="email-address" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Registered Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input wire:model="email" id="email-address" type="email" required 
                            class="bg-white/5 border border-white/10 w-full pl-12 pr-4 py-4 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all font-bold text-sm" 
                            placeholder="name@organization.com">
                    </div>
                    @error('email') <span class="text-red-400 text-[10px] font-bold mt-2 px-1 block uppercase tracking-wider">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-xs font-black rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase tracking-[0.2em] shadow-[0_20px_40px_-15px_rgba(79,70,229,0.5)] transition-all transform active:scale-[0.98]">
                        Send Reset Link
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition-colors">
                        &larr; Return to Secure Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
