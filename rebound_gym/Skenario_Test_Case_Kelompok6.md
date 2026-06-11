# Skenario Test Case — Kelompok 6

---

## Test Case Positive

| ID TEST CASE | KATEGORI | SKENARIO PENGUJIAN | TEST DATA | EXPECTED RESULT | ACTUAL RESULT | STATUS | BUKTI (SCREENSHOOT) |
|---|---|---|---|---|---|---|---|
| TC-P-01 | POSITIVE | Login User Dengan Data Yang Valid | Email: Pw: | User Berhasil Masuk ke Dashboard User | Masuk Ke Halaman Dashboard User | PASS | |
| TC-P-02 | POSITIVE | Login Admin Dengan Data Yang Valid | Email: Pw: | Admin Berhasil Masuk ke Dashboard Admin | Masuk ke Halaman Dashboard Admin | PASS | |
| TC-P-03 | POSITIVE | Daftar Akun Baru | Nama Lengkap, email, no HP, Alamat, dan Password | User Berhasil Daftar dan Langsung Diarahkan ke Halaman Login | Daftar Berhasil dan Masuk ke Halaman Login | PASS | |
| TC-P-04 | POSITIVE | Menambahkan Paket Membership | Tambah - Basic 1 Bulan | Paket Membership Bertambah dan Paket Otomatis Masuk ke Riwayat Membership | Paket Berhasil Ditambahkan dan Muncul di Riwayat Membership | PASS | |
| TC-P-05 | POSITIVE | Admin Bisa Menghapus dan Mengedit Paket yang Ada | Pilih - Hapus - Oke | Paket Berhasil Dihapus/Diedit dan Tampilan Dashboard langsung berubah | Paket yang Dihapus/Diedit Akan Langsung Hilang Dari Tampilan | PASS | |

---

## Test Case Negative

| ID TEST CASE | KATEGORI | SKENARIO PENGUJIAN | TEST DATA | EXPECTED RESULT | ACTUAL RESULT | STATUS | BUKTI (SCREENSHOOT) |
|---|---|---|---|---|---|---|---|
| TC-N-01 | NEGATIVE | Admin Mendaftarkan Member Dengan Field Kosong | - | Validasi Gagal dan Pesan "Harap isi bidang ini" Muncul | Gagal Menambahkan Member dan Pesan "Harap isi bidang ini" Muncul | PASS | |
| TC-N-02 | NEGATIVE | Login Dengan Akun yang Salah | Email: Pw: | Login Gagal dan Pesan Error Muncul | Login Gagal dan Langsung Menampilkan Pesan Email/Username atau Password Salah | PASS | |
| TC-N-03 | NEGATIVE | Login Dengan Field Kosong | Email: - Pw: - | Validasi Gagal dan Pesan "Harap isi bidang ini" Muncul | Gagal Login dan Pesan "Harap isi bidang ini" Muncul | PASS | |
| TC-N-04 | NEGATIVE | Harga Menggunakan Huruf | Harga: Lima Ribu Rupiah | Sistem Menolak Input | Field pengisian harga tidak dapat diisi dengan huruf | PASS | |
| TC-N-05 | NEGATIVE | Pada bagian pengisian nomor akan ada peringatan jika tidak berisi angka | nur | Sistem memberi peringatan | Peringatan: Hanya angka yang diperbolehkan! | PASS | |

---

## Edge Case

| ID TEST CASE | KATEGORI | SKENARIO PENGUJIAN | TEST DATA | EXPECTED RESULT | ACTUAL RESULT | STATUS | BUKTI (SCREENSHOOT) |
|---|---|---|---|---|---|---|---|
| TC-E-01 | EDGE | Input nama pengguna dengan karakter yang panjang | abcdefghkforugjbbbbbbb | Sistem menolak input nama dengan nama panjang | Sistem tetap menerima proses pendaftaran | FAIL | |
| TC-E-02 | EDGE | Password dengan spasi | password123 | Sistem memproses dengan benar | Sistem menolak | FAIL | |
| TC-E-03 | EDGE | No WA berisi huruf | 0812-abc-def | Sistem menolak input | Sistem menyimpan | FAIL | |
| TC-E-04 | EDGE | Pendaftaran dengan email yang sama | nursellarahmah@gmail.com | Sistem menolak input email yang sama | Sistem menolak input email yang sama | PASS | |
| TC-E-05 | EDGE | Password < 6 karakter | 12345 | Sistem memberi peringatan | Sistem tetap menerima | FAIL | |
