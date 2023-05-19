<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use WithFaker;
     /**
     * A feature test_the_product.
     *
     * @return void
     */
    public function test_the_product()
    {
        Product::create([
            'name' => $this->faker->words(5, true),
            'price' => $this->faker->numberBetween(5000, 200000),
        ]);
        $this->json('GET', 'api/product', [], [
            'Accept' => 'application/json',
        ])->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }
}
