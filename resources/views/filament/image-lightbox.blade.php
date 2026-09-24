{{--
    Popup gambar besar untuk seluruh panel CMS (didaftarkan lewat render hook
    di AdminPanelProvider). Menangkap klik pada:
      - pratinjau gambar di field upload (FilePond), termasuk ikon "buka"
        yang bawaannya membuka tab baru;
      - thumbnail gambar di tabel.
--}}
<div class="tbm-lightbox" data-tbm-lightbox hidden role="dialog" aria-modal="true" aria-label="Pratinjau gambar">
    <button type="button" class="tbm-lightbox__close" data-tbm-lightbox-close aria-label="Tutup">&times;</button>
    <img class="tbm-lightbox__img" alt="">
</div>

<style>
    .tbm-lightbox {
        position: fixed; inset: 0; z-index: 100;
        display: flex; align-items: center; justify-content: center;
        padding: 48px 24px;
        background: rgba(0, 0, 0, .85);
        cursor: zoom-out;
    }
    .tbm-lightbox[hidden] { display: none; }
    .tbm-lightbox__img {
        max-width: 100%; max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
        background: repeating-conic-gradient(#e5e5e5 0 25%, #fff 0 50%) 50% / 20px 20px;
        cursor: default;
    }
    .tbm-lightbox__close {
        position: absolute; top: 12px; right: 16px;
        width: 40px; height: 40px;
        border: 0; border-radius: 50%;
        background: rgba(255, 255, 255, .12); color: #fff;
        font-size: 28px; line-height: 1; cursor: pointer;
    }
    .tbm-lightbox__close:hover { background: rgba(255, 255, 255, .25); }

    /* tanda bisa diklik */
    .filepond--item:has(.filepond--image-preview-wrapper) .filepond--file,
    .fi-ta-image img { cursor: zoom-in; }
</style>

<script>
    (function () {
        if (window.tbmLightbox) return;

        var box = document.querySelector('[data-tbm-lightbox]');
        var img = box.querySelector('img');

        function open(src) {
            if (!src) return;
            img.src = src;
            box.hidden = false;
        }

        function close() {
            box.hidden = true;
            img.removeAttribute('src');
        }

        window.tbmLightbox = { open: open, close: close };

        box.addEventListener('click', function (e) {
            if (e.target !== img) close();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !box.hidden) close();
        });

        /* URL gambar dari satu item FilePond: ikon "buka" kalau ada, kalau
           tidak pakai canvas pratinjaunya (resolusi lebih kecil). */
        function filepondSrc(item) {
            var link = item.querySelector('a.filepond--open-icon');
            if (link) return link.href;
            var canvas = item.querySelector('.filepond--image-preview canvas');
            return canvas ? canvas.toDataURL() : null;
        }

        // capture: jalan sebelum handler FilePond / klik baris tabel
        document.addEventListener('click', function (e) {
            var t = e.target;

            var item = t.closest('.filepond--item');
            if (item && item.querySelector('.filepond--image-preview-wrapper')) {
                // tombol hapus/edit/unduh tetap berfungsi seperti biasa
                if (t.closest('button, .filepond--download-icon')) return;
                e.preventDefault();
                e.stopPropagation();
                open(filepondSrc(item));
                return;
            }

            var thumb = t.closest('.fi-ta-image img');
            if (thumb) {
                e.preventDefault();
                e.stopPropagation();
                open(thumb.currentSrc || thumb.src);
            }
        }, true);
    })();
</script>
