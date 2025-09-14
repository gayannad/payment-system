<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private $apiUrl;

    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.exchange_rate.url', 'https://api.apilayer.com/exchangerates_data/latest');
        $this->apiKey = config('services.exchange_rate.api_key');
    }

    /**
     * Retrieves the USD exchange rate for a given currency
     */
    public function getUSDRate(): int
    {
        $currency = 'EUR';

        if ($currency === 'USD') {
            return 1;
        }

        $cacheKey = 'usd_rate_'.$currency.time();

        return cache()->remember($cacheKey, 3600, function () use ($currency) {
            try {
                $response = Http::withHeaders([
                    'apikey' => $this->apiKey,
                ])->get($this->apiUrl, [
                    'base' => $currency,
                    'symbols' => 'USD',
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return $data['rates']['USD'] ?? null;
                }
                throw new \Exception('Failed to fetch exchange rate');
            } catch (\Exception $e) {
                logger()->error('Error fetching exchange rate: '.$e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Converts an amount to USD
     */
    public function convertToUSD($amount, $currency)
    {
        if ($currency === 'USD') {
            return $amount;
        }

        $rate = $this->getUSDRate();

        return $amount * $rate;
    }
}
