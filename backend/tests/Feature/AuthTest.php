<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\CustomerAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use WithFaker;

     /**
     * A feature test_the_auth_login.
     *
     * @return void
     */
    public function test_the_auth_login()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(),
        ]);
        $data = Customer::find($cust->id);
        $this->json('POST', 'api/auth/login', [
            "email" =>  $data->email,
            "password" => 'password', // password
        ],['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'token',
        ]);
    }

    /**
     * A feature test_the_auth_login.
     *
     * @return void
     */
    public function test_the_auth_login_if_incorrect()
    {
        $this->json('POST', 'api/auth/login', [
            "email" => $this->faker->unique()->safeEmail(),
            "password" => 'password123', // password
        ],['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
        ]);
    }

    /**
     * A feature test_the_auth_logout.
     *
     * @return void
     */
    public function test_the_auth_logout()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(),
        ]);
        $data = Customer::find($cust->id);
        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password"
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $token = $login['token'];

        $this->json('POST', 'api/auth/logout',['Accept' => 'application/json'], [
            'Authorization' => 'Bearer ' . $token,
        ])->assertStatus(204);
    }
}
