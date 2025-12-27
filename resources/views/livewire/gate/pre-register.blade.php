<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            PortFlow Gate Access
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Vendor Pre-Registration Portal
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-200">
            
            @if($entryDetails)
                <!-- Success State: QR Code -->
                <div class="text-center space-y-6">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-emerald-800">
                                    Gate Pass Generated Successfully
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center py-4 bg-white p-4 rounded-lg border border-slate-100">
                        <!-- QR Code Generation -->
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($entryDetails['uuid']) !!}
                    </div>

                    <div class="bg-slate-50 p-3 rounded text-left text-sm space-y-1">
                        <p><span class="font-bold text-slate-700">Driver:</span> {{ $entryDetails['driver_name'] }}</p>
                        <p><span class="font-bold text-slate-700">Plate:</span> {{ $entryDetails['vehicle_plate'] }}</p>
                        <p><span class="font-bold text-slate-700">Pass ID:</span> <span class="font-mono text-xs">{{ $entryDetails['uuid'] }}</span></p>
                    </div>

                    <button wire:click="$set('entryDetails', null)" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Register Another
                    </button>
                    
                    <p class="text-xs text-slate-500">
                        Please present this QR code at the security gate for scanning.
                    </p>
                </div>
            @else
                <!-- Registration Form -->
                <form wire:submit.prevent="register" class="space-y-6">
                    <div>
                        <label for="driver_name" class="block text-sm font-medium text-slate-700">
                            Driver Name
                        </label>
                        <div class="mt-1">
                            <input wire:model="driver_name" id="driver_name" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('driver_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="driver_ic" class="block text-sm font-medium text-slate-700">
                            Driver IC / Passport
                        </label>
                        <div class="mt-1">
                            <input wire:model="driver_ic" id="driver_ic" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('driver_ic') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="vehicle_plate" class="block text-sm font-medium text-slate-700">
                            Vehicle Plate Number
                        </label>
                        <div class="mt-1">
                            <input wire:model="vehicle_plate" id="vehicle_plate" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('vehicle_plate') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="cargo_description" class="block text-sm font-medium text-slate-700">
                            Cargo Items / Manifest Declaration
                        </label>
                        <div class="mt-1">
                            <textarea wire:model="cargo_description" id="cargo_description" rows="3" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @error('cargo_description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <p class="mt-1 text-xs text-slate-500">List all major items being transported.</p>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Generate Gate Pass
                        </button>
                    </div>
                </form>
            @endif
        </div>
        <div class="mt-4 text-center">
             <a href="/" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                &larr; Back to PortFlow
            </a>
        </div>
    </div>
</div>
