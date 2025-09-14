<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\PaymentFileProcessJob;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Services\PaymentFileService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use ApiResponse;

    private PaymentFileService $paymentFileService;

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentFileService $paymentFileService, PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentFileService = $paymentFileService;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Handles payment file upload and processing
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_file' => 'required|file|mimes:csv,txt',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 'Validation failed');
            }

            $file = $request->file('payment_file');
            $originalFileName = $file->getClientOriginalName();

            // Upload file to S3
            $s3FileName = $this->paymentFileService->uploadToS3($file);

            // Payout processing
            PaymentFileProcessJob::dispatch($s3FileName, $originalFileName);

            Log::info('Payment file uploaded for processing', [
                'original_name' => $originalFileName,
                's3_file' => $s3FileName,
            ]);

            $data = [
                'original_filename' => $originalFileName,
                'file_id' => $s3FileName,
            ];

            return $this->sendSuccess($data, 'File uploaded successfully and queued for processing');

        } catch (\Exception $e) {
            Log::error('Failed to upload payment file', [
                'error' => $e->getMessage(),
            ]);

            return $this->sendError('', 'Failed to upload file');
        }
    }

    /**
     * Retrieves all payments
     */
    public function index()
    {
        $payments = $this->paymentRepository->all();

        return $this->sendSuccess($payments, 'Payments retrieved successfully.');
    }
}
