<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            color: white;
            padding: 50px 60px;
            position: relative;
            overflow: hidden;
        }
        
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .invoice-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .company-info h1 {
            font-size: 48px;
            font-weight: 900;
            letter-spacing: -2px;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .company-info p {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 3px;
            opacity: 0.9;
            font-weight: 600;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        
        .invoice-number {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 12px;
            backdrop-filter: blur(10px);
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 8px;
        }
        
        .status-paid {
            background: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .status-unpaid {
            background: #f59e0b;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
        
        .invoice-body {
            padding: 60px;
        }
        
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            gap: 40px;
        }
        
        .billing-section {
            flex: 1;
        }
        
        .billing-section h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b;
            font-weight: 800;
            margin-bottom: 12px;
        }
        
        .billing-section .name {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        .billing-section .details {
            color: #475569;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .items-table thead {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }
        
        .items-table th {
            padding: 18px 20px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 800;
            color: #334155;
            border-bottom: 3px solid #3730a3;
        }
        
        .items-table th:nth-child(2),
        .items-table th:nth-child(3),
        .items-table th:nth-child(4) {
            text-align: right;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }
        
        .items-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .items-table td {
            padding: 20px;
            font-size: 14px;
        }
        
        .items-table td:first-child {
            font-weight: 700;
            color: #1e293b;
        }
        
        .items-table td:nth-child(2),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: right;
            font-family: 'Courier New', monospace;
            color: #475569;
        }
        
        .items-table td:last-child {
            font-weight: 700;
            color: #0f172a;
        }
        
        .total-section {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            color: white;
            padding: 30px 40px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(55, 48, 163, 0.3);
        }
        
        .total-label {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 800;
        }
        
        .total-amount {
            font-size: 48px;
            font-weight: 900;
            letter-spacing: -1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .invoice-footer {
            background: #f8fafc;
            padding: 40px 60px;
            text-align: center;
            border-top: 3px solid #e2e8f0;
        }
        
        .invoice-footer p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 8px;
        }
        
        .invoice-footer .thank-you {
            font-weight: 700;
            color: #334155;
            font-size: 16px;
            margin-bottom: 12px;
        }
        
        .invoice-footer .generated {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #cbd5e1;
            margin-top: 20px;
            font-weight: 600;
        }
        
        .print-controls {
            max-width: 900px;
            margin: 0 auto 30px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 30px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .print-controls p {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .button-group {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3730a3 0%, #1e3a8a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(55, 48, 163, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(55, 48, 163, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .print-controls {
                display: none;
            }
            
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <p>Preview Mode</p>
        <div class="button-group">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save as PDF</button>
            <button onclick="window.close()" class="btn btn-secondary">✕ Close</button>
        </div>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <h1>PortFlow</h1>
                    <p>Port Management Authority</p>
                </div>
                <div class="invoice-meta">
                    <h2>INVOICE</h2>
                    <div class="invoice-number">{{ $invoice->invoice_no }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-bottom: 8px;">
                        Issued: {{ $invoice->created_at->format('d M Y') }}
                    </div>
                    <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Billing Information -->
            <div class="billing-info">
                <div class="billing-section">
                    <h3>Bill To</h3>
                    <div class="name">{{ $invoice->organization->name ?? 'Unknown Organization' }}</div>
                    <div class="details">{{ $invoice->organization->billing_address ?? 'No Address on File' }}</div>
                </div>
                <div class="billing-section" style="text-align: right;">
                    <h3>Vessel / Reference</h3>
                    @if($invoice->portCall)
                        <div class="name">{{ $invoice->portCall->vessel->name ?? 'Unknown Vessel' }}</div>
                        <div class="details">
                            {{ $invoice->portCall->vessel->vessel_type ?? '' }} 
                            @if($invoice->portCall->vessel->imo_number)
                                (IMO: {{ $invoice->portCall->vessel->imo_number }})
                            @endif
                            <br>
                            Reference: {{ $invoice->portCall->reference_no ?? '-' }}
                        </div>
                    @else
                        <div class="name">General Billing</div>
                        <div class="details">
                            {{ $invoice->created_at->format('F Y') }} Services<br>
                            Type: {{ $invoice->invoiceItems->first()->description ?? 'Miscellaneous' }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->invoiceItems as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-label">Grand Total</div>
                <div class="total-amount">RM {{ number_format($invoice->total_amount, 2) }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="thank-you">Thank you for your business.</p>
            <p>Payment is due within 30 days from the invoice date.</p>
            <p>Please include the invoice number on your payment reference.</p>
            <p class="generated">Generated by PortFlow Intelligent System</p>
        </div>
    </div>
</body>
</html>
