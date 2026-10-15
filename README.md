Pembuatan WEB Color berkelompok

tutorial utama pengubahan code https://youtu.be/eLmpKKaQL54?si=HLdii5BUlRN2UrJy

Anggotanya
- saya jiro nrp 98765
- saya Fiqral ardhiansyah nrp 062
sesrx
- jiro lagi tes pake VS code

## Menyiapkan penyimpanan Color Picker

1. Buka halaman utama melalui `index.php`; tautan Color Picker & History akan meminta login jika belum masuk.
2. Pastikan MySQL berjalan dan konfigurasi database ada di `color_history/color_palette/koneksi.php`.
3. Pilih database `color_palette_db` di phpMyAdmin, lalu jalankan isi file `color_history/color_palette/users.sql` untuk membuat tabel akun.
4. Jalankan isi file `fitur_warna/color_picker.sql` untuk membuat tabel riwayat dan Color Library.
5. Daftarkan akun melalui `color_history/color_palette/register.php`, lalu login melalui `color_history/color_palette/login.php`. Dari dashboard atau halaman utama, buka Color Picker & History.
6. Riwayat dan Color Library akan disimpan per akun ke tabel MySQL.

History menyimpan maksimal 25 warna terbaru untuk setiap akun. Pilih swatch pada History Log atau Color Library untuk memakai warna kembali.
