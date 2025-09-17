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
    public function getUSDRate($currency): float
    {
        if ($currency === 'USD') {
            return 1.0;
        }

        $cacheKey = 'usd_rate_'.$currency;

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

                    return (float) $data['rates']['USD'];
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

        try {
            $rate = $this->getUSDRate($currency);
            return $amount * $rate;
        } catch (\Exception $e) {
            logger()->error('Error usd rate: ' . $e->getMessage());
            return 0;
        }
    }
}
