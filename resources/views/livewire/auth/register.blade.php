<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-slate-100">
        <div>
            <div class="flex justify-center">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-2xl">P</div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight">
                Create Agent Account
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Join the PortFlow network to manage your fleet.
            </p>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="register">
            
            <div class="space-y-4">
                <!-- Personal Info -->
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">User Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="sr-only">Full Name</label>
                            <input wire:model="name" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Full Name">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="sr-only">Email address</label>
                            <input wire:model="email" type="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Email address">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="sr-only">Password</label>
                                <input wire:model="password" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Password">
                            </div>
                            <div>
                                <label class="sr-only">Confirm Password</label>
                                <input wire:model="password_confirmation" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Confirm">
                            </div>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Company Info -->
                <div>
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Organization Details</h3>
                     <div class="space-y-3">
                        <div>
                            <label class="sr-only">Company Name</label>
                            <input wire:model="company_name" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Company Name (e.g. Maersk)">
                            @error('company_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="sr-only">Agent Code</label>
                            <input wire:model="company_code" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-mono uppercase" placeholder="Agent Code (e.g. MSK)">
                            @error('company_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                         <div>
                            <label class="sr-only">Billing Address</label>
                            <textarea wire:model="billing_address" rows="2" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-medium" placeholder="Billing Address"></textarea>
                            @error('billing_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                     </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase tracking-widest shadow-lg shadow-indigo-500/30 transition-all">
                    Register Account
                </button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-slate-500 font-bold hover:text-indigo-600 transition-colors">Already registered? Sign in</a>
            </div>
        </form>
    </div>
</div>
