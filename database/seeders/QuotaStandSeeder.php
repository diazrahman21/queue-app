<?php

namespace Database\Seeders;

use App\Models\QuotaStand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuotaStandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuotaStand::create([
            'kd_stand' => 'FT',
            'nama_stand' => 'Foto',
            'kuota' => 50,
        ]);

        QuotaStand::create([
            'kd_stand' => 'LK',
            'nama_stand' => 'Lukis',
            'kuota' => 30,
        ]);
    }
}
