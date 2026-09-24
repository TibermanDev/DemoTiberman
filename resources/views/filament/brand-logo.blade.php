{{-- Logo putih situs; mode terang diwarnai merah brand (--red situs) lewat mask. --}}
@php($logo = asset('assets/img/logo-white.png'))
<span class="tbm-brand-logo" role="img" aria-label="Tiberman" style="--tbm-logo: url('{{ $logo }}');"></span>
<style>
    .tbm-brand-logo {
        display: block;
        height: 100%;
        aspect-ratio: 720 / 116;
        background-color: #ef3936;
        -webkit-mask: var(--tbm-logo) center / contain no-repeat;
        mask: var(--tbm-logo) center / contain no-repeat;
    }
    .dark .tbm-brand-logo { background-color: #fff; }
</style>
