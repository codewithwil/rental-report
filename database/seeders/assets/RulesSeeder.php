<?php

namespace Database\Seeders\assets;

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};

class RulesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rules')->insert([
            [
                'content' => '
                1. Cuci mobil terlebih dahulu sebelum pemeriksaan untuk memastikan kerusakan/kotoran terlihat jelas.
                2. Pemeriksaan dilakukan setiap awal/akhir shift atau sesuai jadwal rutin mingguan.
                3. Gunakan form/tabel yang tersedia, dan isi dengan jujur dan lengkap.
                4. Semua foto kerusakan/lengkap dokumen disimpan di folder Drive yang sudah disediakan
                5. Wajib periksa 6 komponen utama:
                - Body Mobil
                - Lampu (hidup semua atau tidak)
                - Ban (ketebalan, tekanan, aus/tipis)
                - Wiper (fungsi saat hujan)
                - Mesin & Radiator (tidak bocor, suara normal)
                - Kelengkapan (Ac Depan,Dongkrak, Kunci Roda, Klakson,segitiga pengaman, Ban Serep, Buku servis, 
                Radio Tape, Spedometer)
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
