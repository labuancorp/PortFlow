<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-[#0a0c10] font-sans">
    <!-- Premium Dynamic Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-violet-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10 px-4" x-data="{ showPassword: false }">
        <div class="bg-white/5 backdrop-blur-2xl p-10 rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.5)] border border-white/10 relative overflow-hidden">
            <!-- Subtle inner glow -->
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
            
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-indigo-500/40 transform -rotate-3 hover:rotate-0 transition-transform duration-500">P</div>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight">
                    PortFlow Access
                </h2>
                <p class="mt-3 text-sm text-slate-400 font-medium">
                    Secure Gateway for Asian Port Operations
                </p>
            </div>

            <form class="space-y-6" wire:submit.prevent="login">
                <div class="space-y-4">
                    <div>
                        <label for="email-address" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 px-1">Identity Provider / Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input wire:model="email" id="email-address" name="email" type="email" autocomplete="email" required 
                                class="bg-white/5 border border-white/10 w-full pl-12 pr-4 py-4 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all font-bold text-sm" 
                                placeholder="name@organization.com">
                        </div>
                        @error('email') <span class="text-red-400 text-[10px] font-bold mt-2 px-1 block uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Secret Credential</label>
                            <a href="#" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest transition-colors">Forgot Access?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input wire:model="password" id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required 
                                class="bg-white/5 border border-white/10 w-full pl-12 pr-12 py-4 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all font-bold text-sm" 
                                placeholder="••••••••">
                            
                            <!-- Password Toggle -->
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition-colors">
                                <template x-if="!showPassword">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </template>
                            </button>
                        </div>
                        @error('password') <span class="text-red-400 text-[10px] font-bold mt-2 px-1 block uppercase tracking-wider">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-xs font-black rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase tracking-[0.2em] shadow-[0_20px_40px_-15px_rgba(79,70,229,0.5)] transition-all transform active:scale-[0.98]">
                        Establish Connection
                    </button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        New Operator? <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">Register Credentials</a>
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Bottom security notice -->
        <p class="mt-8 text-center text-[10px] font-bold text-slate-600 uppercase tracking-[0.3em] flex items-center justify-center gap-2">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 4.908-3.033 9.325-7.703 11.164a.8.8 0 01-.594 0C5.033 16.326 2 11.908 2 7.002c0-.681.057-1.35.166-2.003zm9.447 2.133a1 1 0 011.386 1.414l-3.5 3.5a1 1 0 01-1.414 0l-1.5-1.5a1 1 0 011.414-1.414l.793.793 2.821-2.821z" clip-rule="evenodd"></path></svg>
            Encrypted Session Protocol
        </p>
    </div>
</div>
