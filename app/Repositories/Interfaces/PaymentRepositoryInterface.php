<?php

namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    public function all();

    public function create(array $data);
}
