<?php


namespace App\Traits;

use GuzzleHttp\Client as Client;

trait CurrencyConversionTrait
{
    public function CurrencyConversionRate($convert_to){
        $client = new Client();
        $result = $client->get('https://api.exchangerate-api.com/v4/latest/USD');
        $result = json_decode($result->getBody());
        $exchange_rates = get_object_vars($result->rates);
        return response()->json($exchange_rates[$convert_to]);
    }
}
