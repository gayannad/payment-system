<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Traits\ApiResponse;

class InvoiceController extends Controller
{
    use ApiResponse;

    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Retrieve a list of all invoices
     */
    public function index()
    {
        $invoices = $this->invoiceRepository->all();

        return $this->sendSuccess($invoices, 'Invoices retrieved successfully.');
    }
}
