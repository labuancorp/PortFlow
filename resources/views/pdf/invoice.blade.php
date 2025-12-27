<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 font-sans p-10 max-w-4xl mx-auto">
    <!-- Print Controls -->
    <div class="no-print mb-8 flex justify-between items-center bg-slate-100 p-4 rounded-lg">
        <p class="text-sm font-bold text-slate-500">Preview Mode</p>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold text-sm shadow hover:bg-indigo-500">Print / Save as PDF</button>
            <button onclick="window.close()" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded font-bold text-sm hover:bg-slate-50">Close</button>
        </div>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-start mb-12 border-b border-slate-200 pb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tighter text-indigo-900 mb-2">PortFlow</h1>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Port Management Authority</p>
        </div>
        <div class="text-right">
            <h2 class="text-3xl font-bold text-slate-900 mb-1">INVOICE</h2>
            <p class="text-lg font-mono text-slate-500 mb-1">{{ $invoice->invoice_no }}</p>
            <p class="text-sm font-bold text-slate-400 uppercase">Issued: {{ $invoice->created_at->format('d M Y') }}</p>
             <div class="mt-4">
                <span class="inline-block px-3 py-1 rounded border-2 font-bold uppercase tracking-wide text-xs
                    {{ $invoice->status === 'paid' ? 'border-green-500 text-green-700 bg-green-50' : 'border-amber-500 text-amber-700 bg-amber-50' }}">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>
    </div>

    <!-- Addresses -->
    <div class="flex justify-between mb-12">
        <div class="w-1/2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Bill To</h3>
            <p class="font-bold text-xl text-slate-900">{{ $invoice->organization->name ?? 'Unknown Organization' }}</p>
            <p class="text-slate-600">{{ $invoice->organization->address ?? 'No Address on File' }}</p>
        </div>
        <div class="w-1/2 text-right">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Vessel / Port Call</h3>
            <p class="font-bold text-xl text-slate-900">{{ $invoice->portCall->vessel->name ?? 'Unknown Vessel' }}</p>
            <p class="text-slate-600 mb-1">{{ $invoice->portCall->vessel->vessel_type ?? '' }} ({{ $invoice->portCall->vessel->imo_number ?? '' }})</p>
            <p class="text-sm text-slate-500">Ref: {{ $invoice->portCall->reference_no ?? '-' }}</p>
        </div>
    </div>

    <!-- Items -->
    <table class="w-full mb-12">
        <thead>
            <tr class="border-b-2 border-slate-900">
                <th class="text-left py-3 text-xs font-black text-slate-900 uppercase tracking-wider">Description</th>
                <th class="text-center py-3 text-xs font-black text-slate-900 uppercase tracking-wider">Qty</th>
                <th class="text-right py-3 text-xs font-black text-slate-900 uppercase tracking-wider">Unit Price</th>
                <th class="text-right py-3 text-xs font-black text-slate-900 uppercase tracking-wider">Total (RM)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @foreach($invoice->invoiceItems as $item)
            <tr>
                <td class="py-4 text-sm font-bold text-slate-700">{{ $item->description }}</td>
                <td class="py-4 text-center text-sm font-mono text-slate-500">{{ $item->quantity }}</td>
                <td class="py-4 text-right text-sm font-mono text-slate-500">{{ number_format($item->unit_price, 2) }}</td>
                <td class="py-4 text-right text-sm font-bold font-mono text-slate-900">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-t-2 border-slate-900">
                <td colspan="3" class="pt-4 text-right text-sm font-black text-slate-900 uppercase tracking-wider">Grand Total</td>
                <td class="pt-4 text-right text-3xl font-black text-indigo-600">RM {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="border-t border-slate-200 pt-8 text-center text-slate-500 text-sm">
        <p class="font-bold">Thank you for your business.</p>
        <p class="mt-1 text-xs">Payment is due within 30 days. Please include invoice number on your check.</p>
        <p class="mt-4 text-[10px] uppercase tracking-widest text-slate-300">Generated by PortFlow Intelligent System</p>
    </div>
</body>
</html>
