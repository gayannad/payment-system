<?php

namespace App\Providers;

use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\PaymentLogRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\PaymentLogRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(PaymentLogRepositoryInterface::class, PaymentLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
