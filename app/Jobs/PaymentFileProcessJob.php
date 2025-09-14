<?php

namespace App\Jobs;

use App\Services\PaymentProcessService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PaymentFileProcessJob implements ShouldQueue
{
    use Dispatchable, \Illuminate\Bus\Queueable, InteractsWithQueue, SerializesModels;

    private $filePath;

    private $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $fileName)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentProcessService $paymentProcessService): void
    {
        try {
            $file = Storage::disk('s3')->get($this->filePath);
            $lines = explode("\n", $file);
            $headers = str_getcsv(array_shift($lines));

            foreach ($lines as $index => $line) {
                if (trim($line) === '') {
                    continue;
                }
                $rowData = array_combine($headers, str_getcsv($line));
                $paymentProcessService->processPayment($rowData, $index + 2, $this->fileName);
            }
        } catch (\Exception $e) {
            logger()->error('Payment file processing failed', [
                'file' => $this->fileName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
