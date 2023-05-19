<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\CustomerAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use WithFaker;
     /**
     * A feature test_the_transaction_by_customer.
     *
     * @return void
     */
    public function test_the_transaction_by_customer()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->words(6, true),
        ]);
        $data = Customer::find($cust->id);

        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password",
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $token = $login['token'];

        $this->json('GET', 'api/transaction', [], ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
            'data',
        ]);
    }

    /**
     * A feature test_create_transaction.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->words(6, true),
        ]);
        $data = Customer::find($cust->id);

        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password",
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $token = $login['token'];

        $product = Product::create([
            'name' => $this->faker->words(6, true),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);

        $product2 = Product::create([
            'name' => $this->faker->words(6, true),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);

        $paymentMethod = PaymentMethod::create([
            'name' => $this->faker->words(6, true),
            'is_active' => 1,
        ]);

        $paymentMethod2 = PaymentMethod::create([
            'name' => $this->faker->words(6, true),
            'is_active' => 1,
        ]);


        $this->json('POST', 'api/transaction',
            [
                "products" => [
                        [
                            "id" => $product->id,
                            "quantity" => $this->faker->numberBetween(1, 30),
                        ],
                        [
                            "id" => $product2->id,
                            "quantity" => $this->faker->numberBetween(1, 30),
                        ],
                ],
                "payment_method" => [
                    [
                        "id" => $paymentMethod->id,
                        "amount" => $this->faker->numberBetween(1000, 200000),
                    ],
                    [
                         "id" => $paymentMethod2->id,
                         "amount" => $this->faker->numberBetween(1000, 200000),
                    ],
                ]
            ], ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
            'data',
        ]);
    }
}
