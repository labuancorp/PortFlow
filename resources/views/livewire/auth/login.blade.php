<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-slate-100">
        <div>
             <div class="flex justify-center">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-2xl">P</div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight">
                PortFlow Access
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Sign in to manage your port operations.
            </p>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="login">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="email-address" class="sr-only">Email address</label>
                    <input wire:model="email" id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Email address">
                     @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-500 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm font-bold" placeholder="Password">
                     @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase tracking-widest shadow-lg shadow-indigo-500/30 transition-all">
                    Sign in
                </button>
            </div>
             <div class="text-center">
                <a href="{{ route('register') }}" class="text-slate-500 font-bold hover:text-indigo-600 transition-colors">New Agent? Register here</a>
            </div>
        </form>
    </div>
</div>
