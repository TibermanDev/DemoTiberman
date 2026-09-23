<?php

/*
| Halaman flipbook PDF, meneruskan halaman statis di situs lama
| (tiberman.com/{slug}/). Kunci = slug URL; 'pdf' = URL yang dibaca browser
| (pdf.js butuh CORS kalau beda domain — jsDelivr sudah mengizinkan).
*/

return [

    'katalog' => [
        'title' => 'Katalog Komik',
        // PDF-nya (±25 MB) terlalu besar untuk jsDelivr, jadi disajikan dari
        // aplikasi ini. Selama file belum ditaruh di public/files/, route
        // proxy di routes/web.php meneruskannya dari server lama.
        'pdf' => '/files/katalog-komik.pdf',
    ],

    'company-profile' => [
        'title' => 'Company Profile',
        'pdf' => 'https://cdn.jsdelivr.net/gh/TibermanDev/bizpro/compro2026.pdf',
    ],

    'proposal' => [
        'title' => 'Proposal Bisnis',
        'pdf' => 'https://cdn.jsdelivr.net/gh/TibermanDev/bizpro/TBIZPRO2025-V5.pdf',
    ],

];
