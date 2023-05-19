<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use WithFaker;
    /**
     * A feature test_the_payment_method.
     *
     * @return void
     */
    public function test_the_payment_method()
    {
        PaymentMethod::create([
            'name' => $this->faker->words(5, true),
            'is_active' => 1,
        ]);

        $this->json('GET', 'api/payment-method', [], [
            'Accept' => 'application/json',
        ])->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }
}
