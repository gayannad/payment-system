<?php

namespace App\Console\Commands;

use App\Jobs\SendInvoiceJob;
use Illuminate\Console\Command;

class ProcessPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-payouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send invoices for customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendInvoiceJob::dispatch(now()->toDateString())
            ->delay(now()->addSeconds(5));

        $this->info('SendInvoiceJob dispatched for date: '.(now()->toDateString()));
    }
}
