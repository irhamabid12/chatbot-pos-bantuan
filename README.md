# Telegram Chatbot Pos Pengaduan

[![made-with-laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/) [![made-with-php](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://laravel.com/)  [![made-with-mysql](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

[Telegram Chatbot PosBantuan](https://t.me/pospengaduanbot) merupakan elemen sistem penanganan pengaduan customers PT Pos Indonesia yang dibuat untuk mempermudah dalam penanganan pengaduan dengan diintegrasikan dengan fitur Telegram Bot agar proses pelayanan pelanggan dapat lebih responsive.

## Fitur

- Cek Resi
- Lacak Status Pengaduan
- Ajukan Pengaduan
- Penilaian Customer Service
- Kritik dan Saran

## Teknologi

PosBantuan menggunakan sejumlah proyek open source untuk bekerja dengan baik:
- [PHP](https://www.php.net/) - bahasa skrip dengan fungsi umum yang terutama digunakan untuk pengembangan web.
- [Botman](https://botman.io/) - The PHP framework for chatbot development.
- [MySQL](https://www.mysql.com/) -  sebuah sistem manajemen database relasional (Relational Database Management System – RDBMS).

## Requirement
- Laravel >= 5.5
- PHP >= 7.1.3
- MySQL

## Instalasi

Untuk menggunakan studio BotMan dengan cara yang paling efisien, instal penginstal BotMan Studio secara global dengan:

```composer global require "botman/installer"```

Membuat proyek BotMan baru ke direktori baru dengan perintah berikut:

```botman new <directory>```

Atau, Anda juga dapat membuat proyek BotMan baru dengan mengeluarkan perintah Composer create-project di terminal Anda:

```composer create-project --prefer-dist botman/studio <directory>```

Setelah instalasi berhasil, masuk ke direktori yang dibuat dan gunakan perintah berikut, untuk memulai server PHP sederhana:

```php artisan serve```

## Credit
> Irham Maulana Abid
> Politeknik Elektronika Negeri Surabaya
> D3 Teknik Telekomunikasi
> 2022
