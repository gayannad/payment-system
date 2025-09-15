<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    private Payment $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a paginated list of all payments
     */
    public function all(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->model
            ->select([
                'id',
                'customer_name',
                'amount',
                'usd_amount',
                'currency',
                'payment_date',
                'is_processed',
                'created_at',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create a new payment
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
