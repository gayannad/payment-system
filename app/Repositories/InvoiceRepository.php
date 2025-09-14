<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    private Invoice $model;

    public function __construct(Invoice $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a paginated list of all invoices
     */
    public function all(int $perPage = 15)
    {
        return $this->model
            ->select([
                'id',
                'invoice_number',
                'customer_name',
                'amount',
                'is_sent',
                'created_at',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create a new invoice
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
