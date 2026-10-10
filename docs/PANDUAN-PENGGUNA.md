# Panduan Pengguna

## Masuk

1. Buka `/login`.
2. Masuk menggunakan akun yang diberikan oleh pengelola aplikasi.
3. Menu yang tersedia mengikuti role akun.

Jangan memakai akun seeder atau password contoh di server bersama/produksi. Minta administrator membuat kredensial pribadi yang unik.

## Super Admin: Mengelola Perusahaan

1. Masuk dengan akun `super_admin`.
2. Buka menu **Perusahaan** atau tombol **Kelola perusahaan** di dashboard.
3. Isi nama perusahaan lalu pilih **Tambah perusahaan**.
4. Perusahaan akan muncul di daftar serta menjadi pilihan pada form kecelakaan kerja.

Nama perusahaan unik; sistem menolak nama yang sudah terdaftar.

## Admin dan Super Admin: Mencatat Pemeriksaan

1. Buka **Pemeriksaan**, lalu pilih **Input pemeriksaan**.
2. Ketik nama karyawan. Daftar saran menampilkan nama dan nilai perusahaan pada akun.
3. Akun dengan label **Belum ada riwayat** belum pernah diperiksa, tetapi dapat dipilih untuk mencatat pemeriksaan pertama.
4. Pilih hasil yang benar, isi tanggal, poli, diagnosis, tindakan/pengobatan, dan catatan bila ada.
5. Simpan. Rekam baru akan terhubung ke akun karyawan dan terlihat di riwayat pemeriksaan serta halaman detail karyawan.

Pencarian hanya mencakup akun ber-role `employee` yang sudah terdaftar. Mengetik nama yang tidak ada pada daftar tidak membuat akun karyawan baru.

## Admin dan Super Admin: Laporan Kecelakaan

1. Buka menu **Input Kecelakaan**.
2. Pilih perusahaan dan isi identitas karyawan, tanggal kejadian, kronologi, cedera, tindakan, dan terapi.
3. Simpan laporan. Jika nama cocok dengan akun pengguna, laporan dihubungkan ke akun itu; input kecelakaan tetap menyimpan data identitas laporan.

## Direktori Karyawan

Menu **Karyawan** menampilkan hanya pengguna yang memiliki role `employee`, termasuk yang belum pernah diperiksa. Pilih **View** untuk melihat informasi karyawan, pemeriksaan, dan riwayat kecelakaan. Akun role admin/super admin tidak dianggap sebagai karyawan pada direktori ini.

## Riwayat Pemeriksaan

Halaman riwayat memuat catatan terbaru. Pilih **View** untuk melihat tanggal, karyawan, klinik, diagnosis, tindakan, dan catatan yang tersimpan.

## Dashboard

- **Kunjungan hari ini**: hitungan catatan pemeriksaan berdasarkan `created_at` hari ini, dengan batas yang diterapkan route dashboard.
- **Menunggu pemeriksaan**: mengikuti definisi hitungan dashboard saat ini. Tabel belum memiliki status hadir/menunggu/selesai yang terpisah.
- **Total karyawan**: jumlah seluruh akun role `employee`.

## Bantuan Pengelola

Jika nama karyawan tidak muncul, pastikan akun karyawan sudah dibuat dan role-nya `employee`. Saat ini aplikasi tidak memiliki halaman untuk membuat akun karyawan. Bila perusahaan pada saran salah/kosong, perbarui `company_name` pada data pengguna melalui prosedur administrasi yang disetujui.