{{--
    Pratinjau hasil pencarian Google untuk field SEO di CMS (Fields::seoPreview).
    Lebarnya dipatok 600px seperti kolom hasil Google di desktop, jadi judul
    yang kepanjangan terpotong "..." di tempat yang kira-kira sama.
--}}
@php
    $favicon = \App\Support\Favicon::url();
    $parts = parse_url($url);
    $crumb = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '')
        .collect(explode('/', trim($parts['path'] ?? '', '/')))->filter()->map(fn ($p) => ' › '.$p)->implode('');
@endphp
<div>
    <p style="margin:0 0 8px;font-size:.875rem;font-weight:500">Pratinjau di Google</p>
    <div style="max-width:600px;padding:16px 18px;border-radius:12px;background:#fff;color:#202124;font-family:Arial,sans-serif;border:1px solid rgba(0,0,0,.1)">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
            <span style="flex:none;display:grid;place-items:center;width:28px;height:28px;border-radius:50%;background:#f1f3f4;overflow:hidden">
                @if ($favicon)<img src="{{ $favicon }}" alt="" style="width:18px;height:18px;object-fit:contain">@endif
            </span>
            <span style="min-width:0;line-height:1.3">
                <span style="display:block;font-size:14px">{{ \App\Support\Seo::siteName() }}</span>
                <span style="display:block;font-size:12px;color:#4d5156;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $crumb }}</span>
            </span>
        </div>
        <div style="font-size:20px;line-height:1.3;color:#1a0dab;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $title ?: 'Judul halaman' }}</div>
        <div style="margin-top:4px;font-size:14px;line-height:1.58;color:#4d5156;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $description ?: 'Deskripsi kosong — Google akan mengambil potongan teks dari isi halaman sendiri.' }}</div>
    </div>
</div>
