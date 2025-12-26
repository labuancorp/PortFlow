<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Service to handle integration with Enterprise Resource Planning (ERP) systems.
 * Mocks connection to SAP/Oracle Financials.
 */
class ErpSyncService
{
    /**
     * Sync a specific invoice to the ERP
     * 
     * @param Invoice $invoice
     * @return array Result status and message
     */
    public function syncInvoice(Invoice $invoice)
    {
        // Simulate network latency
        sleep(1);

        // Simulate 90% success rate
        $success = rand(1, 100) <= 90;

        if ($success) {
            $erpRef = 'SAP-' . strtoupper(Str::random(10));
            $xmlPayload = $this->generateXmlPayload($invoice);

            $invoice->update([
                'erp_status' => 'synced',
                'erp_synced_at' => Carbon::now(),
                'erp_reference_id' => $erpRef,
                'erp_logs' => "Successfully transmitted payload to API endpoint."
            ]);

            return [
                'success' => true,
                'message' => "Invoice synced to ERP successfully. Ref: {$erpRef}",
                'payload' => $xmlPayload
            ];
        } else {
            $errorMsg = "Connection timeout: Gateway unavailable at 10.0.0.51";
            
            $invoice->update([
                'erp_status' => 'failed',
                'erp_logs' => $errorMsg
            ]);

            return [
                'success' => false,
                'message' => "Failed to sync: {$errorMsg}",
                'payload' => null
            ];
        }
    }

    /**
     * Generate XML Payload (Simulation of standard financial interchange format)
     */
    public function generateXmlPayload(Invoice $invoice)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<FinancialTransaction>\n";
        $xml .= "  <Header>\n";
        $xml .= "    <TransactionID>{$invoice->invoice_no}</TransactionID>\n";
        $xml .= "    <Date>{$invoice->issued_date->format('Y-m-d')}</Date>\n";
        $xml .= "    <SourceSystem>PORTFLOW_OS</SourceSystem>\n";
        $xml .= "    <TargetSystem>SAP_ECC_6</TargetSystem>\n";
        $xml .= "  </Header>\n";
        $xml .= "  <Customer>\n";
        $xml .= "    <ID>{$invoice->organization->id}</ID>\n";
        $xml .= "    <Name>{$invoice->organization->name}</Name>\n";
        $xml .= "  </Customer>\n";
        $xml .= "  <LineItems>\n";
        
        foreach($invoice->invoiceItems as $item) {
            $xml .= "    <Item>\n";
            $xml .= "      <Description>{$item->description}</Description>\n";
            $xml .= "      <Quantity>{$item->quantity}</Quantity>\n";
            $xml .= "      <UnitPrice>{$item->unit_price}</UnitPrice>\n";
            $xml .= "      <Total>{$item->total_price}</Total>\n";
            $xml .= "    </Item>\n";
        }
        
        $xml .= "  </LineItems>\n";
        $xml .= "  <TotalAmount currency=\"MYR\">{$invoice->total_amount}</TotalAmount>\n";
        $xml .= "</FinancialTransaction>";

        return $xml;
    }

    /**
     * Get Sync Status Badge Color
     */
    public static function getStatusColor($status)
    {
        return match($status) {
            'synced' => 'green',
            'failed' => 'red',
            'pending' => 'amber',
            default => 'slate'
        };
    }
}
