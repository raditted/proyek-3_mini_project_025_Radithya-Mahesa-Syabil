<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'SMP Ngawi Barat',
                'address' => 'Jl. Ngawi No. 1, Kecamatan NB'
            ],
            [
                'name' => 'SD Ngawi Timur',
                'address' => 'Jl. Ngawi No. 22, Kecamatan NT'
            ],
            [
                'name' => 'SMA Rongawi',
                'address' => 'Jl. Rongawi No. 5, Kecamatan R'
            ],
        ];
        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
