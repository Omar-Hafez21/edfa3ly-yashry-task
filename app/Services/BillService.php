<?php


namespace App\Services;

use App\Traits\CurrencyConversionTrait as CurrencyConversion;
use App\Product;

class BillService
{
    use CurrencyConversion;
    public function initializePrices(){
        $price = new Product();
        $price->t_shirt = 10.99;
        $price->jacket = 19.99;
        $price->pants = 14.99;
        $price->shoes = 24.99;
        $price->taxes = .14;
        return $price;
    }

    public function subTotal($price,$products){
        $no_of_t_shirt = isset($products["T-shirt"]) ? $products["T-shirt"] : 0;
        $no_of_jacket = isset($products["Jacket"]) ? $products["Jacket"] : 0;
        $no_of_pants = isset($products["Pants"]) ? $products["Pants"] : 0;
        $no_of_shoes = isset($products["Shoes"]) ? $products["Shoes"] : 0;
        $sub_total = ($no_of_jacket * $price->jacket) + ($no_of_pants * $price->pants)
                +($no_of_shoes * $price->shoes) + ($no_of_t_shirt * $price->t_shirt);
        return $sub_total;
    }

    public function taxes($sub_total,$price){
        $taxes = $sub_total * $price->taxes;
        return $taxes;
    }

    public function offers($products,$price){
        $discount = new \stdClass;
        $no_of_t_shirt = isset($products["T-shirt"]) ? $products["T-shirt"] : 0;
        $no_of_jacket = isset($products["Jacket"]) ? $products["Jacket"] : 0;
        $no_of_shoes = isset($products["Shoes"]) ? $products["Shoes"] : 0;
        if($no_of_shoes > 0){
            $discount->shoes_offer = -.1 * $no_of_shoes * $price->shoes;
        }
        if($no_of_t_shirt>1 && $no_of_jacket>0){
            $discount->jacket_offer = -.5 * min($no_of_jacket,(int) ($no_of_t_shirt/2)) * $price->jacket;
        }
        if(!isset($discount->shoes_offer) && !isset($discount->jacket_offer)){
            $discount = null;
        }
        return $discount;
    }
    public function bill($currency , $products){
        $bill = new \stdClass;
        $price = $this->initializePrices();
        $currency_rate = $this->CurrencyConversionRate($currency)->original;
        $products = collect($products)->countBy();
        $bill->sub_total = $this->subTotal($price,$products) * $currency_rate;
        $bill->taxes = $this->taxes($bill->sub_total,$price);
        $discounts = $this->offers($products,$price);

        if(isset($discounts)){
            $bill->discounts = $discounts;
        }

        $bill->total = $bill->sub_total + $bill->taxes
            + (isset($bill->discounts) && isset($bill->discounts->shoes_offer)? $bill->discounts->shoes_offer : 0)
            + (isset($bill->discounts) && isset($bill->discounts->jacket_offer)? $bill->discounts->jacket_offer : 0);

        return $bill;
    }
}
