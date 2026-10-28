<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Distribution;
use App\Models\School;

class DistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributions = [
            //ini bagian untuk SMP ngawi barat
            [
                'school' => 'SMP Ngawi Barat',
                'date' => '2026-10-20',
                'menu' => 'Nasi, sayap ayam madu panggang, sayur sop, apel hijau asli malang'
            ],
            [
                'school' => 'SMP Ngawi Barat',
                'date' => '2026-10-21',
                'menu' => 'Nasi, pecel madiun, jamur enoki khas palangkaraya, susu'
            ],
            [
                'school' => 'SMP Ngawi Barat',
                'date' => '2026-10-22',
                'menu' => 'Nasi, bubur kacang madiun, tempe, apel hijau asli malang'
            ],

            //ini bagian untuk sd ngawi timur
            [
                'school' => 'SD Ngawi Timur',
                'date' => '2026-10-20',
                'menu' => 'Nasi, nugget, piscok lumer khas probolinggo, wortel rebus, susu'
            ],
            [
                'school' => 'SD Ngawi Timur',
                'date' => '2026-10-21',
                'menu' => 'Nasi cumi hitam khas madura, bayam, rambutan asli ponorogo'
            ],

            //ini bagian untuk sma rongawi
            [
                'school' => 'SMA Rongawi',
                'date' => '2026-10-20',
                'menu' => 'Nasi, rendang, capcay, risol ayam asli boyolali, salak asli ngawi'
            ],
            [
                'school' => 'SMA Rongawi',
                'date' => '2026-10-22',
                'menu' => 'Mie kuah kental khas wonosobi, Ceker mercon khas ngawi, lalapan, susu'
            ],
        ];
        foreach ($distributions as $data) {
            $school = School::where('name', $data['school'])->first();
            Distribution::create([
                'school_id'         => $school->id,
                'distribution_date' => $data['date'],
                'menu_description'  => $data['menu'],
            ]);
        }
    }
}
