<?php

namespace App\Repositories\Interfaces;

interface InvoiceRepositoryInterface
{
    public function all();

    public function create(array $data);
}
