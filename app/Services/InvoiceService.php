<?php

namespace App\Services;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Generates a unique invoice number
     */
    public function generateInvoiceNumber(): string
    {
        $nextId = (Invoice::max('id') ?? 0) + 1;

        return sprintf('INV-%04d', $nextId);
    }

    /**
     * Creates an invoice for the customer
     */
    public function createInvoiceForCustomer($payments): void
    {
        $customer = $payments->first();
        $totalUSD = $payments->sum('usd_amount');

        $invoice = $this->invoiceRepository->create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'customer_name' => $customer->customer_name,
            'customer_email' => $customer->customer_email,
            'amount' => $totalUSD,
            'is_sent' => true,
        ]);

        $paymentIds = $payments->pluck('id');

        Payment::whereIn('id', $paymentIds)->update([
            'invoice_id' => $invoice->id,
            'is_processed' => true,
        ]);

        $invoice->load('payments');

        Mail::to($invoice->customer_email)->later(now()->addSeconds(2), new InvoiceMail($invoice));

        $invoice->update([
            'is_sent' => true,
        ]);

        Log::info('Invoice generated and sent', [
            'invoice_id' => $invoice->id,
            'customer_email' => $invoice->customer_email,
            'payments_count' => $payments->count(),
        ]);

    }

    //    public function generateInvoiceHTML($invoice, $payments)
    //    {
    //        return view('emails.invoice', compact('invoice', 'payments'))->render();
    //    }
}
