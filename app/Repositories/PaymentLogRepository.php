<?php

namespace App\Repositories;

use App\Models\PaymentLog;
use App\Repositories\Interfaces\PaymentLogRepositoryInterface;

class PaymentLogRepository implements PaymentLogRepositoryInterface
{
    private PaymentLog $model;

    public function __construct(PaymentLog $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new payment log
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
