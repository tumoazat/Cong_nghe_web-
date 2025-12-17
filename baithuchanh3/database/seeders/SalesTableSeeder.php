<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales')->insert([
            [
                'sale_id' => 1,
                'medicine_id' => 1,
                'quantity' => 2,
                'sale_date' => '2025-12-10 09:30:00',
                'customer_phone' => '0901234567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 2,
                'medicine_id' => 2,
                'quantity' => 1,
                'sale_date' => '2025-12-10 10:15:00',
                'customer_phone' => '0912345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 3,
                'medicine_id' => 3,
                'quantity' => 5,
                'sale_date' => '2025-12-10 11:00:00',
                'customer_phone' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 4,
                'medicine_id' => 4,
                'quantity' => 1,
                'sale_date' => '2025-12-10 14:30:00',
                'customer_phone' => '0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 5,
                'medicine_id' => 1,
                'quantity' => 3,
                'sale_date' => '2025-12-10 16:45:00',
                'customer_phone' => '0909876543',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
