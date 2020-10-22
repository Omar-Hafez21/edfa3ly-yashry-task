<?php

namespace App\Http\Controllers;

use App\Services\BillService;
use Illuminate\Http\Request;
use App\Product;
use App\Traits\CurrencyConversionTrait as CurrencyConversion;

class BillController extends Controller
{
    use CurrencyConversion;
    protected $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    public function cartBill(Request $request){

        $result = $this->billService->bill($request->currency,$request->products);

        return response()->json($result);
    }
}
