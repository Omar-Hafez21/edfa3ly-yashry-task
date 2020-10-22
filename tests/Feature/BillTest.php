<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BillTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_jacket_offer()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
    "currency"=>"USD",
    "products"=>[
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "T-shirt",
        "Jacket"
    ]
]);
        $response->assertJson([
            'sub_total' => 129.89000000000001,
            'taxes' => 18.184600000000003,
            'discounts' => [
                'jacket_offer' => -9.995
            ],
            'total' => 138.07960000000003,
        ]);

        $response->assertStatus(200);
    }

    public function test_shoes_offer()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "T-shirt",
                "T-shirt",
                "Shoes"
            ]
        ]);
        $response->assertJson([
            'sub_total' => 46.97,
            'taxes' => 6.5758,
            'discounts' => [
                'shoes_offer' => -2.499
            ],
            'total' => 51.0468,
        ]);

        $response->assertStatus(200);
    }
    public function test_all_offers()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "T-shirt",
                "T-shirt",
                "Shoes",
                "Jacket"
            ]
        ]);
        $response->assertJson([
            'sub_total' => 66.96,
            'taxes' => 9.3744,
            'discounts' => [
                'shoes_offer' => -2.499,
                'jacket_offer' => -9.995
            ],
            'total' => 63.840399999999995,
        ]);

        $response->assertStatus(200);
    }

    public function test_without_offers()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "T-shirt",
                "T-shirt",
                "Pants",
                "Pants",
                "Pants"
            ]
        ]);
        $response->assertJson([
            "sub_total" => 66.95,
            "taxes" => 9.373000000000001,
            "total" => 76.32300000000001
        ]);

        $response->assertStatus(200);
    }

    public function test_shirts_only()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "T-shirt",
                "T-shirt",
                "T-shirt",
                "T-shirt",
                "T-shirt"
            ]
        ]);
        $response->assertJson([
            "sub_total" => 54.95,
            "taxes" => 7.693000000000001,
            "total" => 62.643
        ]);

        $response->assertStatus(200);
    }


    // 2j 4t 1s
    public function test_jacket_offer_case2()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "Jacket",
                "Jacket",
                "T-shirt",
                "T-shirt",
                "T-shirt",
                "T-shirt",
                "Shoes"
            ]
        ]);
        $response->assertJson([
            "sub_total" => 108.93,
            "taxes" => 15.250200000000003,
            "discounts" => [
                "shoes_offer" => -2.499,
                "jacket_offer" => -19.99
            ],
            "total" => 101.69120000000002
        ]);

        $response->assertStatus(200);
    }

    // 5j
    public function test_jackets_only()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "Jacket",
                "Jacket",
                "Jacket",
                "Jacket",
                "Jacket"
            ]
        ]);
        $response->assertJson([
            "sub_total" => 99.94999999999999,
            "taxes" => 13.993,
            "total" => 113.94299999999998
        ]);

        $response->assertStatus(200);
    }

    public function test_pants_only()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "Pants",
                "Pants",
                "Pants",
                "Pants",
                "Pants",
            ]
        ]);
        $response->assertJson([
             "sub_total" => 74.95,
             "taxes" => 10.493000000000002,
             "total" => 85.44300000000001
        ]);

        $response->assertStatus(200);
    }

    public function test_shoeses_only()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[
                "Shoes",
                "Shoes",
                "Shoes",
                "Shoes",
                "Shoes",
            ]
        ]);
        $response->assertJson([
            "sub_total" => 124.94999999999999,
            "taxes" => 17.493,
            "discounts" => [
                "shoes_offer" => -12.495
            ],
            "total" => 129.94799999999998
        ]);

        $response->assertStatus(200);
    }

    public function test_without_products()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET', 'api/bill', [
            "currency"=>"USD",
            "products"=>[

            ]
        ]);
        $response->assertJson([
            "sub_total" => 0,
            "taxes" => 0,
            "total" => 0
        ]);

        $response->assertStatus(200);
    }



}
