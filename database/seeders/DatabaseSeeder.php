<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        User::factory()->create([
            'name' => 'Admin Donasi',
            'email' => 'admin@donasi.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Donatur Peduli',
            'email' => 'user@donasi.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Seed Seedlings (Bibit Pohon)
        \App\Models\Seedling::create([
            'name' => 'Mahoni (Swietenia mahagoni)',
            'description' => 'Pohon pelindung dengan kayu bernilai tinggi. Sangat baik untuk reboisasi dan penyerap polusi udara.',
            'price' => 15000,
            'quantity_available' => 100,
            'image_path' => null,
        ]);

        \App\Models\Seedling::create([
            'name' => 'Sengon (Falcataria moluccana)',
            'description' => 'Pohon dengan pertumbuhan sangat cepat, ideal untuk pemulihan lahan kritis dengan cepat.',
            'price' => 12000,
            'quantity_available' => 150,
            'image_path' => null,
        ]);

        \App\Models\Seedling::create([
            'name' => 'Trembesi (Samanea saman)',
            'description' => 'Pohon peneduh raksasa dengan kemampuan luar biasa dalam menyerap karbon dioksida (CO2) di atmosfer.',
            'price' => 18000,
            'quantity_available' => 80,
            'image_path' => null,
        ]);

        \App\Models\Seedling::create([
            'name' => 'Ketapang Kencana (Terminalia mantaly)',
            'description' => 'Pohon hias peneduh dengan struktur percabangan mendatar yang indah, sering digunakan untuk penghijauan jalan.',
            'price' => 25000,
            'quantity_available' => 50,
            'image_path' => null,
        ]);
    }
}
