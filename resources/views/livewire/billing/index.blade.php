<div class="p-8 bg-slate-50 min-h-screen font-sans">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Financials & Billing</h1>
            <p class="text-slate-500 mt-1">Generate invoices and track payments.</p>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white p-1 rounded-lg border border-slate-200 inline-flex shadow-sm">
            <button wire:click="$set('activeTab', 'unbilled')" 
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $activeTab === 'unbilled' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                Pending Billing
                @if($unbilledCalls->total() > 0)
                <span class="ml-2 bg-amber-400 text-slate-900 text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $unbilledCalls->total() }}</span>
                @endif
            </button>
            <button wire:click="$set('activeTab', 'invoices')" 
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $activeTab === 'invoices' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                Invoices History
            </button>
        </div>
    </div>

    <!-- Notifications -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Content -->
    <div>
        @if($activeTab === 'unbilled')
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vessel / Agent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Berth & Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($unbilledCalls as $call)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $call->vessel->name }}</div>
                                <div class="text-xs text-slate-500">{{ $call->agent ? $call->agent->name : 'No Agent' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $call->berth ? $call->berth->name : 'N/A' }}
                                </span>
                                <div class="text-xs text-slate-500 mt-1">{{ $call->reference_no ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($call->atb && $call->atd)
                                    <div class="text-sm text-slate-800">{{ $call->atb->format('d M H:i') }} - {{ $call->atd->format('d M H:i') }}</div>
                                    <div class="text-xs font-mono text-slate-500 mt-1">
                                        {{ ceil($call->atb->diffInHours($call->atd, false)) }} Hours
                                    </div>
                                @else
                                    <span class="text-red-500 text-xs italic">Missing Timestamps</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="generateInvoice({{ $call->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-sm transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    Generate Invoice
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <p>No pending unbilled port calls.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($unbilledCalls->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $unbilledCalls->links() }}
                    </div>
                @endif
            </div>

        @else
            <!-- Invoice List -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Invoice No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Billed To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vessel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ERP Sync</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-slate-700">
                                {{ $inv->invoice_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $inv->organization->name ?? '-' }}
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">
                                {{ $inv->portCall->vessel->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                RM {{ number_format($inv->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($inv->status === 'paid')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                @elseif($inv->status === 'draft')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">Draft</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">{{ ucfirst($inv->status) }}</span>
                                @endif
                            </td>
                            <!-- ERP Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($inv->erp_status === 'synced')
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-700">SAP Synced</div>
                                            <div class="text-[10px] text-slate-500">{{ $inv->erp_reference_id }}</div>
                                        </div>
                                    </div>
                                @elseif($inv->erp_status === 'failed')
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-2.5 w-2.5 rounded-full bg-red-400 mr-2"></div>
                                        <div class="text-xs font-bold text-red-600">Failed</div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Pending</span>
                                @endif
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($inv->erp_status === 'synced')
                                    <button wire:click="downloadErpPayload({{ $inv->id }})" class="text-slate-400 hover:text-slate-600 text-[10px] uppercase font-bold" title="Download Payload XML">XML</button>
                                    <span class="text-slate-300">|</span>
                                @else
                                    <button wire:click="syncToErp({{ $inv->id }})" class="text-blue-600 hover:text-blue-900 text-[10px] uppercase font-bold">Sync SAP</button>
                                    <span class="text-slate-300">|</span>
                                @endif

                                @if($inv->status !== 'paid')
                                <button wire:click="markAsPaid({{ $inv->id }})" class="text-green-600 hover:text-green-900 text-[10px] uppercase tracking-wide font-bold">Mark Paid</button>
                                <span class="text-slate-300">|</span>
                                @endif
                                <button class="text-teal-600 hover:text-teal-900 font-semibold">View</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                No invoices generated yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                 @if($invoices->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
