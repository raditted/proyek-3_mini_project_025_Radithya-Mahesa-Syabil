<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
use App\Models\Distribution;
use App\Models\Report;
use App\Models\ReportForwarding;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelapor1    = User::where('email', 'suki@gmail.com')->first();
        $pelapor2    = User::where('email', 'panikga@gmail.com')->first();
        $koordinator = User::where('email', 'koordinator@gmail.com')->first();
        $smpNgawi   = School::where('name', 'SMP Ngawi Barat')->first();
        $sdNgawi    = School::where('name', 'SD Ngawi Timur')->first();
        $smaRongawi = School::where('name', 'SMA Rongawi')->first();

        //bagian untuk mengambil distribusi yang sudah di-seed
        $dist_smp_20 = Distribution::where('school_id', $smpNgawi->id)->where('distribution_date', '2026-10-20')->first();
        $dist_smp_21 = Distribution::where('school_id', $smpNgawi->id)->where('distribution_date', '2026-10-21')->first();
        $dist_smp_22 = Distribution::where('school_id', $smpNgawi->id)->where('distribution_date', '2026-10-22')->first();
        $dist_sd_20  = Distribution::where('school_id', $sdNgawi->id)->where('distribution_date', '2026-10-20')->first();
        $dist_sd_21  = Distribution::where('school_id', $sdNgawi->id)->where('distribution_date', '2026-10-21')->first();
        $dist_sma_20 = Distribution::where('school_id', $smaRongawi->id)->where('distribution_date', '2026-10-20')->first();
        $dist_sma_22 = Distribution::where('school_id', $smaRongawi->id)->where('distribution_date', '2026-10-22')->first();

        //1. status dikirim
        Report::create([
            'user_id' => $pelapor1->id,
            'school_id' => $smpNgawi->id,
            'distribution_id' => $dist_smp_20->id,
            'incident_at' => '2026-10-20 13:00:00',
            'description' => 'Anak saya dan beberapa teman sekelasnya tiba tiba mual dan sakit perut beberapa jam abis makan siang dari MBG.',
            'affected_count' => 8,
            'status' => ReportStatus::Dikirim,
        ]);
        //2. status dalam peninjauan
        Report::create([
            'user_id' => $pelapor1->id,
            'school_id' => $smpNgawi->id,
            'distribution_id' => $dist_smp_21->id,
            'incident_at' => '2026-10-21 12:30:00',
            'description' => 'Beberapa siswa melapor ada gangguan di pencernaannya setelah mengonsumsi makanan dari distribusi hari Selasa. Gejalanya muncul kira kira 2 jam abis makan.',
            'affected_count' => 5,
            'status' => ReportStatus::DalamPeninjauan,
        ]);
        //3. status perlu dilengkapi
        Report::create([
            'user_id' => $pelapor2->id,
            'school_id' => $sdNgawi->id,
            'distribution_id' => $dist_sd_20->id,
            'incident_at' => '2026-10-20 14:00:00',
            'description' => 'Ini Anak saya mengalami sakit perut setelah makan siang di sekolah pada tanggal 20 Oktober.',
            'affected_count' => 3,
            'status' => ReportStatus::PerluDilengkapi,
            'review_note' => 'Mohon lengkapi informasi gejala yang dialami dan apakah anak sudah mendapat penanganan medis.',
        ]);
        //4. status ditandai untuk diteruskan
        Report::create([
            'user_id' => $pelapor2->id,
            'school_id' => $sdNgawi->id,
            'distribution_id' => $dist_sd_21->id,
            'incident_at' => '2026-10-21 13:30:00',
            'description' => 'Beberapa anak saya kelas 3 ngeluh mual, pusing, sama sakit perut setelah makan dari MBG. Orang tua mereka udah bawa anak anaknya ke klinik terdekat.',
            'affected_count' => 12,
            'status' => ReportStatus::DitandaiUntukDiteruskan,
            'review_note' => 'Laporan perlu diteruskan ke fasilitas kesehatan dan pihak sekolah mengingat jumlah terdampak cukup banyak.',
        ]);
        //5. status sudah diteruskan + record forwarding
        $reportDiteruskan = Report::create([
            'user_id' => $pelapor1->id,
            'school_id' => $smaRongawi->id,
            'distribution_id' => $dist_sma_20->id,
            'incident_at' => '2026-10-20 14:30:00',
            'description' => 'Beberapa siswa SMA Rongawi mengalami gangguan kesehatan setelah makan siang. Gejala berupa mual dan pusing. Sudah ada 6 siswa yang dibawa ke UKS.',
            'affected_count' => 6,
            'status' => ReportStatus::SudahDiteruskan,
            'review_note' => 'Laporan telah diverifikasi dan perlu ditindaklanjuti segera.',
        ]);
        ReportForwarding::create([
            'report_id' => $reportDiteruskan->id,
            'recipient_type' => 'fasilitas_kesehatan',
            'recipient_name' => 'Puskesmas Kota Ngawi',
            'note' => 'Informasi diteruskan untuk pemantauan lebih lanjut.',
            'recorded_by' => $koordinator->id,
            'forwarded_at' => '2026-10-21 09:00:00',
        ]);
        //6. status ditutup
        Report::create([
            'user_id'  => $pelapor2->id,
            'school_id' => $smaRongawi->id,
            'distribution_id' => $dist_sma_22->id,
            'incident_at' => '2026-10-22 13:00:00',
            'description' => 'Ada beberapa laporan mual dari siswa setelah makan MBG. Gejala ringan dan sudah membaik pada hari yang sama.',
            'affected_count' => 2,
            'status' => ReportStatus::Ditutup,
            'review_note'  => 'Laporan telah ditinjau. Gejala ringan dan sudah membaik. Tidak diperlukan tindak lanjut lebih.',
        ]);
        // laporan tambahan, kebutuhan agar pagination terlihat dan butuh min 12 total
        $extraDistributions = [
            [
                $smpNgawi,
                '2026-10-23',
                'Nasi, ayam panggang madu, sup sayur, semangka'
            ],
            [
                $smpNgawi,
                '2026-10-24',
                'Nasi goreng spesial, telur mata sapi, kerupuk, susu'
            ],
            [
                $sdNgawi,
                '2026-10-23',
                'Nasi, pepes ikan mas, tumis buncis, jeruk'
            ],
            [
                $sdNgawi,
                '2026-10-24',
                'Nasi, soto ayam, tempe goreng, pisang'
            ],
            [
                $smaRongawi,
                '2026-10-23',
                'Nasi, bakso kuah, sayur lodeh, melon'
            ],
            [
                $smaRongawi,
                '2026-10-24',
                'Nasi, ikan goreng sambal, urap sayur, susu'
            ],
        ];
        $statuses = [
            ReportStatus::Dikirim,
            ReportStatus::DalamPeninjauan,
            ReportStatus::Ditutup,
            ReportStatus::Dikirim,
            ReportStatus::DalamPeninjauan,
            ReportStatus::Ditutup,
        ];
        foreach ($extraDistributions as $i => [$school, $date, $menu]) {
            $dist = Distribution::create([
                'school_id' => $school->id,
                'distribution_date' => $date,
                'menu_description' => $menu,
            ]);
            $pelapor = ($i % 2 === 0) ? $pelapor1 : $pelapor2;
            Report::create([
                'user_id' => $pelapor->id,
                'school_id' => $school->id,
                'distribution_id' => $dist->id,
                'incident_at' => $date . ' 13:00:00',
                'description' => 'Beberapa siswa melaporkan gangguan ringan setelah makan siang dari program MBG pada distribusi ini. Orang tua meminta agar laporan ini ditindaklanjuti.',
                'affected_count' => ($i + 1) * 2,
                'status' => $statuses[$i],
            ]);
        }
    }
}
