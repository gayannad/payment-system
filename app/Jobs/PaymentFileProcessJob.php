<?php

namespace App\Jobs;

use App\Services\PaymentProcessService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

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
            $stream = Storage::disk('s3')->readStream($this->filePath);

            LazyCollection::make(function () use ($stream) {
                while (($line = fgets($stream)) !== false) {
                    yield trim($line);
                }
                fclose($stream);
            })
                ->filter(fn ($line) => ! empty($line))
                ->chunk(1000)
                ->each(function ($chunk, $chunkIndex) use ($paymentProcessService) {
                    $headers = null;

                    foreach ($chunk as $lineIndex => $line) {
                        $rowNumber = ($chunkIndex * 1000) + $lineIndex + 1;

                        if ($rowNumber === 1) {
                            $headers = str_getcsv($line);

                            continue;
                        }

                        if ($headers) {
                            $rowData = array_combine($headers, str_getcsv($line));
                            $paymentProcessService->processPayment($rowData, $rowNumber + 1, $this->fileName);
                        }
                    }
                });

        } catch (\Exception $e) {
            logger()->error('Payment file processing failed', [
                'file' => $this->fileName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
