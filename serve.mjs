/* Static server sederhana: node serve.mjs  ->  http://localhost:4322 */
import http from 'http';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const root = path.dirname(fileURLToPath(import.meta.url));
const types = {
  '.html': 'text/html; charset=utf-8', '.css': 'text/css', '.js': 'text/javascript',
  '.png': 'image/png', '.jpg': 'image/jpeg', '.webp': 'image/webp', '.svg': 'image/svg+xml',
  '.mp4': 'video/mp4', '.webm': 'video/webm',
  '.md': 'text/markdown; charset=utf-8'
};

http.createServer((req, res) => {
  let p = decodeURIComponent(req.url.split('?')[0]);
  
  if (p === '/') p = '/index.html';
  const file = path.join(root, p);
  if (!file.startsWith(root)) { res.writeHead(403); res.end('forbidden'); return; }
  fs.stat(file, (err, st) => {
    if (err || !st.isFile()) { res.writeHead(404, { 'Content-Type': 'text/plain' }); res.end('404 ' + p); return; }
    const ext = path.extname(file).toLowerCase();
    const type = types[ext] || 'application/octet-stream';
    const tag = `W/"${st.size}-${st.mtimeMs}"`;
    const modified = st.mtime.toUTCString();
    /* Intro diputar tiap refresh, dan browser mengambil video lewat range
       request yang tidak kena revalidasi — tanpa max-age file 6 MB itu diunduh
       ulang terus. Hanya video yang di-cache; html/css/js/gambar tetap
       revalidasi supaya hasil edit langsung kelihatan. */
    const cache = (ext === '.mp4' || ext === '.webm') ? 'public, max-age=86400' : 'no-cache';

    /* Sisanya revalidasi biasa: kalau berkasnya tidak berubah cukup 304,
       begitu diedit mtime-nya berubah dan browser dapat 200 yang baru. */
    if (!req.headers.range &&
        (req.headers['if-none-match'] === tag ||
         req.headers['if-modified-since'] === modified)) {
      res.writeHead(304, { 'ETag': tag, 'Last-Modified': modified, 'Cache-Control': cache });
      res.end();
      return;
    }

    /* <video> di Safari/iOS minta byte-range; tanpa ini videonya tidak mau jalan */
    const range = /^bytes=(\d*)-(\d*)$/.exec(req.headers.range || '');
    if (range) {
      let start = range[1] ? parseInt(range[1], 10) : 0;
      let end = range[2] ? parseInt(range[2], 10) : st.size - 1;
      if (isNaN(start) || isNaN(end) || start > end || end >= st.size) {
        res.writeHead(416, { 'Content-Range': `bytes */${st.size}` }); res.end(); return;
      }
      res.writeHead(206, {
        'Content-Type': type,
        'Content-Length': end - start + 1,
        'Content-Range': `bytes ${start}-${end}/${st.size}`,
        'Accept-Ranges': 'bytes',
        'ETag': tag,
        'Last-Modified': modified,
        'Cache-Control': cache
      });
      fs.createReadStream(file, { start, end }).pipe(res);
      return;
    }

    res.writeHead(200, {
      'Content-Type': type,
      'Content-Length': st.size,
      'Accept-Ranges': 'bytes',
      'ETag': tag,
      'Last-Modified': modified,
      'Cache-Control': cache
    });
    fs.createReadStream(file).pipe(res);
  });
}).listen(4322, () => console.log('Tiberman web: http://localhost:4322'));
