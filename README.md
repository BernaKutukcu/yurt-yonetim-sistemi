# Öğrenci Yurt Yönetim Sistemi

Laravel 11 ile geliştirilmiş web tabanlı yurt yönetim sistemi. Admin, personel ve öğrenci panelleri içerir.

## Özellikler

- Rol bazlı erişim kontrolü (admin, personel, öğrenci)
- Öğrenci ve oda yönetimi (blok bazlı, doluluk takibi)
- Giriş-çıkış takibi ve geç kalma kaydı
- İzin talebi ve onay sistemi
- Aylık ödeme takibi
- Aylık yemekhane menüsü planlama ve servis takibi
- Duyuru sistemi
- Arıza ve şikayet bildirimi
- Personel nöbet takvimi ve temizlik görev takibi
- Departman bazlı personel panelleri (güvenlik, yemekhane, temizlik)

## Kurulum

    composer install
    cp .env.example .env
    php artisan key:generate

.env dosyasında veritabanı bilgilerini düzenleyin:

    DB_DATABASE=yurt_yonetim
    DB_USERNAME=root
    DB_PASSWORD=

Migration'ları çalıştırın:

    php artisan migrate

Admin kullanıcısı oluşturun:

    php artisan tinker

    App\Models\User::create([
        'name' => 'Yurt Müdürü',
        'email' => 'admin@yurt.com',
        'password' => bcrypt('sifreniz'),
        'role' => 'admin'
    ]);

## Teknolojiler

- Laravel 11, PHP 8.2, MySQL
- Blade şablon motoru
- Özel CSS (DM Sans & Fraunces)
