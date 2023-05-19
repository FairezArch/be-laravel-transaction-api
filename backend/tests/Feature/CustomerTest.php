<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use WithFaker;
    /**
     * A feature test_the_customer.
     *
     * @return void
     */
    public function test_the_customer()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(6),
        ]);
        $data = Customer::find($cust->id);
        $login = $this->post('api/auth/login', [
            "email" =>  $data->email,
            "password" => 'password', // password
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $data = $login['data']['id'];
        $token = $login['token'];

        $this->json('GET', 'api/customer/'.$data, [], ['Authorization' => 'Bearer ' . $token,'Accept' => 'application/json',])
        ->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

     /**
     * A feature test_create_customer_if_email_exits.
     *
     * @return void
     */
    public function test_create_customer_if_email_exits()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(6),
        ]);
        $data = Customer::find($cust->id);
        $this->json('POST', 'api/customer', [
            "name" => $this->faker->name(),
            "email" => $data->email,
            "password" => "password",
            "address" => $this->faker->word(6),
        ], ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJsonStructure([
            "message",
            "errors" => [
                "email" => []
            ]
        ]);
    }

    /**
     * A feature test_create_customer_success.
     *
     * @return void
     */
    public function test_create_customer_success()
    {
        $this->json('POST', 'api/customer', [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "password" => "password",
            "address" => $this->faker->word(6),
        ], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
        ]);
    }

     /**
     * A feature test_update_customer_success.
     *
     * @return void
     */
    public function test_update_customer_success()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(6),
        ]);
        $data = Customer::find($cust->id);
        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password",
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $id = $login['data']['id'];
        $token = $login['token'];

        $this->json('PUT', 'api/customer/'.$id, [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "address" => $this->faker->word(6),
        ], ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "message",
        ]);
    }

     /**
     * A feature test_update_customer_if_email_exits.
     *
     * @return void
     */
    public function test_update_customer_if_email_exits()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(6),
        ]);
        $data = Customer::find($cust->id);

        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password"
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $id = $login['data']['id'];
        $token = $login['token'];

        $cust2 = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust2->id,
            "address" => $this->faker->word(6),
        ]);
        $data2 = Customer::find($cust2->id);

        $this->json('PUT', 'api/customer/'.$id, [
            "name" => $this->faker->name(),
            "email" => $data2->email,
            "address" => $this->faker->word(6),
        ], ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJsonStructure([
            "message",
            "errors" => [
                "email" => []
            ]
        ]);
    }

     /**
     * A feature test_delete_customer_then_logout.
     *
     * @return void
     */
    public function test_delete_customer_then_logout()
    {
        $cust = Customer::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ]);
        CustomerAddress::create([
            'customer_id' => $cust->id,
            "address" => $this->faker->word(6),
        ]);
        $data = Customer::find($cust->id);

        $login = $this->post('api/auth/login', [
            "email" => $data->email,
            "password" => "password"
        ],['Accept' => 'application/json'])->assertStatus(200)->decodeResponseJson();

        $id = $login['data']['id'];
        $token = $login['token'];

        $this->json('DELETE', 'api/customer/'.$id, [], ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
        ->assertStatus(204);
    }
}
