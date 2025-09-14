<?php

namespace App\Services;

use App\Repositories\Interfaces\PaymentLogRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class PaymentProcessService
{
    private ExchangeRateService $exchangeRateService;

    private PaymentRepositoryInterface $paymentRepository;

    private PaymentLogRepositoryInterface $paymentLogRepository;

    public function __construct(
        ExchangeRateService $exchangeRateService,
        PaymentRepositoryInterface $paymentRepository,
        PaymentLogRepositoryInterface $paymentLogRepository
    ) {
        $this->exchangeRateService = $exchangeRateService;
        $this->paymentRepository = $paymentRepository;
        $this->paymentLogRepository = $paymentLogRepository;
    }

    /**
     * Processes payments
     */
    public function processPayment($rowData, $rowNumber, $fileName): bool
    {
        try {
            $validatedData = $this->validatePaymentRow($rowData);

            if ($validatedData->fails()) {
                $this->logPaymentProcess($fileName, $rowNumber, $rowData, 'failed',
                    'Validation failed: '.implode(', ', $validatedData->errors()->all()));

                return false;
            }

            $usdAmount = $this->exchangeRateService->convertToUSD($rowData['amount'], $rowData['currency']);

            if ($usdAmount === null) {
                $this->logPaymentProcess($fileName, $rowNumber, $rowData, 'failed',
                    'Currency conversion failed');

                return false;
            }

            $this->paymentRepository->create([
                'customer_id' => $rowData['customer_id'],
                'customer_name' => $rowData['customer_name'],
                'customer_email' => $rowData['customer_email'],
                'amount' => $rowData['amount'],
                'usd_amount' => $usdAmount,
                'currency' => $rowData['currency'],
                'reference_no' => $rowData['reference_no'],
                'payment_date' => $rowData['date_time'],
            ]);

            $this->logPaymentProcess($fileName, $rowNumber, $rowData, 'success', 'Payment processed successfully');

            return true;

        } catch (\Exception $e) {
            $this->logPaymentProcess($fileName, $rowNumber, $rowData, 'failed', $e->getMessage());

            return false;
        }
    }

    /**
     * Validates the payment row data
     */
    public function validatePaymentRow($rowData)
    {
        return Validator::make($rowData, [
            'customer_id' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'reference_no' => 'required|string|unique:payments,reference_no',
            'date_time' => 'required|date',
        ]);
    }

    /**
     * Logs the payment process details
     */
    public function logPaymentProcess($fileName, $rowNumber, $rowData, $status, $message): void
    {
        $this->paymentLogRepository->create([
            'file_name' => $fileName,
            'row_number' => $rowNumber,
            'status' => $status,
            'message' => $message,
            'data' => $rowData,
        ]);
    }
}
