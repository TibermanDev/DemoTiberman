<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

/** 15 SuperArea — dulu di superarea.blade.php dan assets/js/superarea-spots.js. */
class LocationSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        // [nama, catatan, pulau, alamat, foto, kunci pin, kode, alias, lat, lng, tampil di footer]
        $rows = [
            ['Jabodetabek', null, 'jawa', 'Komplek Pergudangan Cahaya, Jl. Nurul Huda No.34, Jatimulya, Bekasi', 'plb-truck.webp', 'JAKARTA', '04', 'JKT', -6.308233955535468, 106.98128080952068],
            ['Surabaya', 'Head Office', 'jawa', 'Jl. Mustika No.10, Ngagel, Kec. Wonokromo, Kota Surabaya', 'warehouse-dark.webp', 'SURABAYA', '01', 'HO', -7.2927133348724675, 112.7447667292062],
            ['Mojokerto', null, 'jawa', 'Pergudangan Fie Min Logistics, Jl. Raya Pacing, Kec. Bangsal, Mojokerto', 'plb-mojokerto.webp', 'MOJOKERTO', '02', 'MJK', -7.512656365240839, 112.47431900567061],
            ['Gresik', null, 'jawa', 'Pergudangan Fie Min Logistics, Jl. Raya Bungah, Kec. Bungah, Gresik', 'plb-gresik.webp', 'GRESIK', '03', 'GRS', -7.039682723656871, 112.57357287668198],
            ['Palembang', null, 'sumatra', 'Jl. Musi 2, Kel. Karang Jaya, Kec. Gandus, Kota Palembang', 'plb-stock.webp', 'PALEMBANG', '15', 'PBG', -3.00996008204691, 104.72304592921462],
            ['Pontianak', null, 'kalimantan', 'Pergudangan Primaco, Jl. Komodor Yos Sudarso No.2, Pontianak', 'plb-truck.webp', 'PONTIANAK', '14', 'PTK', -0.0051165440176069, 109.31440332935568],
            ['Banjarbaru', null, 'kalimantan', 'Pergudangan Kalimantan Kencana Blok D No.13, Banjarbaru', 'warehouse-dark.webp', 'BANJARBARU', '05', 'BJM', -3.457763489259076, 114.70026753264581],
            ['Balikpapan', null, 'kalimantan', 'Jl. Soekarno Hatta Km. 11, RW.115, Karang Joang, Balikpapan', 'plb-mojokerto.webp', 'BALIKPAPAN', '06', 'BLP', -1.1739508958906903, 116.88050496945647],
            ['Manado', null, 'sulawesi', 'Kawasan Pergudangan, Jl. Raya Manado - Bitung, Sulawesi Utara', 'plb-gresik.webp', 'MANADO', '07', 'MND', 1.4756042813215693, 124.9095256292551],
            ['Kendari', null, 'sulawesi', 'Jl. Pajak, Korumba, Kec. Mandonga, Kota Kendari', 'delivery-forklift.webp', 'KENDARI', '08', 'KDR', -3.9674439731384497, 122.52371640076153],
            ['Morowali', null, 'sulawesi', 'Jl. Trans Sulawesi, Bahoruru, Kec. Bungku Tengah, Morowali', 'plb-stock.webp', 'MOROWALI', '09', 'MRW', -2.5110381889652817, 121.9510623664606],
            ['Luwuk Banggai', null, 'sulawesi', 'Jl. Tanjung Malaka No.1, Kelurahan Kraton, Luwuk, Banggai', 'truck-tiberman.webp', 'LUWUK', '11', 'LWK', -0.960613604695442, 122.79628783732583],
            ['Ternate', null, 'maluku-papua', 'Jl. Pertamina, Gambesi, Kec. Ternate Selatan, Maluku Utara', 'plb-truck.webp', 'TERNATE', '10', 'TNT', 0.7566079355207939, 127.33336067021476],
            ['Sofifi', null, 'maluku-papua', 'Jl. Trans Halmahera, Bukit Durian, Oba Utara, Kota Tidore Kepulauan', 'warehouse-dark.webp', 'SOFIFI', '12', 'SFF', 0.7230643999924186, 127.58013539621747],
            ['Weda', null, 'maluku-papua', 'Lelilef Sawai, Kec. Weda Tengah, Halmahera Tengah, Maluku Utara', 'plb-stock.webp', 'WEDA', '13', 'WDA', 0.4721350115859624, 127.95582399983205, false],
        ];

        foreach ($rows as $i => $r) {
            Location::query()->updateOrCreate(['map_key' => $r[5]], [
                'name' => $r[0],
                'note' => $r[1],
                'region' => $r[2],
                'address' => $r[3],
                'image' => $this->img($r[4]),
                'code' => $r[6],
                'alias' => $r[7],
                'lat' => $r[8],
                'lng' => $r[9],
                'show_in_footer' => $r[10] ?? true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
