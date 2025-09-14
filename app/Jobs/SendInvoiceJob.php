<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\InvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable,InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private ?string $date = null)
    {
        $this->date = $date ?? now()->toDateString();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $invoiceService = app(InvoiceService::class);

            $paymentGroups = Payment::where('is_processed', false)
                ->whereDate('created_at', $this->date)
                ->get()
                ->groupBy('customer_id');

            foreach ($paymentGroups as $payments) {
                $invoiceService->createInvoiceForCustomer($payments);
            }

            Log::info('Daily invoices generated successfully', ['date' => $this->date]);

        } catch (Exception $e) {
            Log::error('Failed to generate daily invoices', [
                'date' => $this->date,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
