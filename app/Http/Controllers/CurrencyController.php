<?php

namespace App\Http\Controllers;

use App\Traits\CurrencyConversionTrait;
use GuzzleHttp\Client as Client;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use CurrencyConversionTrait;
    public function CurrencyConversionRates(){

        return response()->json($this->CurrencyConversionRate('EGP')->original);
    }
}
