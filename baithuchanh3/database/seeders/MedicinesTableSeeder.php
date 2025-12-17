<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medicines')->insert([
            [
                'medicine_id' => 1,
                'name' => 'Paracetamol',
                'brand' => 'Panadol',
                'dosage' => '500mg tablets',
                'form' => 'Viên nén',
                'price' => 5000.00,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medicine_id' => 2,
                'name' => 'Amoxicillin',
                'brand' => 'Amoxil',
                'dosage' => '250mg capsules',
                'form' => 'Viên nang',
                'price' => 15000.00,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medicine_id' => 3,
                'name' => 'Vitamin C',
                'brand' => 'Nature Made',
                'dosage' => '1000mg tablets',
                'form' => 'Viên nén',
                'price' => 3000.00,
                'stock' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medicine_id' => 4,
                'name' => 'Cough Syrup',
                'brand' => 'Prospan',
                'dosage' => '100ml bottle',
                'form' => 'Xi-rô',
                'price' => 85000.00,
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medicine_id' => 5,
                'name' => 'Ibuprofen',
                'brand' => 'Advil',
                'dosage' => '200mg tablets',
                'form' => 'Viên nén',
                'price' => 8000.00,
                'stock' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
