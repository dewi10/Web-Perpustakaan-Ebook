# Panduan File Publik dan Privat

Panduan ini dipakai saat proyek akan diunggah ke GitHub agar file yang aman dipublikasikan terpisah dari file yang hanya boleh disimpan lokal.

## File yang Aman Masuk Repo Publik

- source code aplikasi PHP
- aset tampilan seperti CSS, JS, gambar antarmuka, dan template
- `README.md`
- `data_perpus.sql` bila hanya dipakai sebagai struktur awal atau data contoh yang sudah aman
- `inc/koneksi.example.php`
- file helper dan tools yang tidak berisi data pribadi

## File yang Sebaiknya Tetap Privat

- `inc/koneksi.php` karena berisi kredensial database asli
- seluruh file `*.csv`, `*.xls`, `*.xlsx` yang berisi impor data
- file SQL hasil backup kerja seperti `*_backup*.sql`, `tmp_*.sql`, `import_*.sql`
- isi folder `uploads/ebooks/` jika file PDF bukan untuk distribusi publik
- file sementara hasil migrasi atau pembersihan data

## Langkah Praktis Sebelum Push

1. Pastikan file privat sudah tercakup di `.gitignore`.
2. Gunakan `inc/koneksi.example.php` sebagai file contoh publik.
3. Review ulang isi `data_perpus.sql` bila repo akan benar-benar publik.
4. Cek daftar file dengan `git status` sebelum commit.
5. Baru lakukan commit dan push ke GitHub.

## Saran Pemisahan

Jika nanti Anda ingin repo benar-benar aman untuk publik:

- simpan dump database nyata di luar repo
- simpan file nominatif anggota di folder lokal terpisah
- gunakan data dummy atau data yang sudah disanitasi untuk contoh
