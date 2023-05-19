<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now()->toDateTimeString();
        $data = [
            ['name' => 'John Doe', 'email' => 'johndoe@example.net', 'password' => bcrypt('password'), 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Doe John', 'email' => 'doejohn@example.net', 'password' => bcrypt('password'), 'created_at' => $now, 'updated_at' => $now]
        ];

        Customer::insert($data);
    }
}
