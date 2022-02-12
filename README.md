# JIBAS WhatsApp Gateway

JBSWAGW ini merupakan script open source yang befungsi menggantikan sebagian fungsi dari **Telegram Gateway** dari [JIBAS](http://jibas.id).

Script ini hanya dapat digunakan jika aplikasi JIBAS Anda sudah _live_ atau dapat diakses secara publik.

## Contoh
Saat ini, script hanya bisa digunakan untuk melihat data keuangan siswa. Adapun data keuangan untuk calon siswa maupun pegawai masih dalam pengembangan. Untuk menu penilaian dan kehadiran juga masih dalam masa pengembangan. :D

## Prasyarat
1. Pengiriman pesan _list_ hanya bisa dilakukan dari akun WhatsApp reguler, bukan akun WhatsApp Bisnis.
2. Orangtua, siswa/calon siswa, maupun pegawai tidak perlu melakukan proses registrasi seperti di Telegram Gateway. Selama nomor WA user terdaftar di bagian nomor HP di JIBAS, maka bisa menggunakan fitur WA Gateway ini.
3. Format nomor HP yang harus didaftarkan di Modul Akademik JIBAS adalah 628xxxx atau 08xxxx.

## Instalasi
Silakan masuk ke folder JIBAS Anda. Kita asumsikan URL Anda adalah [https://domainsekolah.sch.id/jibas/](https://domainsekolah.sch.id/jibas/), maka silakan unduh atau klon repo ini.
1. ``` cd path/to/jibas ```
2. ``` git clone https://github.com/arifnrw/jbswa.git jbswa ```
3. ``` cd jbswa/core ```
4. ``` cp config.sample.php config.php```
5. Ubah **my_secret_key** di callback.php ```$mysecret   = "my_secret_key";``` sesuai yang Anda inginkan.


## Daftar Akun
Silakan mendaftarkan akun di sistem WhatsApp Gateway di [warayang.com](https://warayang.com).
1. Daftar akun
2. Japri WA 0819886180 agar diberikan saldo trial
3.  Masuk ke menu Device
4.  Tambah Device, pilih Trial
5.  Isi webhook/callback dengan https://domainsekolah.sch.id/jibas/jbswa/core/callback.php?secret=my_secret_key
6.  Scan QR Code dengan aplikasi WhatsApp Anda yang akan dijadikan bot hingga _connected_
7.  Kirim pesan ke nomor bot Anda dengan format **/start**
