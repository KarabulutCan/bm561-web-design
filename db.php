<?php
// db.php
// MySQL veritabanına PDO ile bağlantı

$host = 'localhost';
$dbName = 'ogrenci_otomasyon';
$dbUser = 'root';      // XAMPP varsayılan kullanıcı
$dbPass = '';          // Varsayılan şifre genelde boş ("")

try {
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    // Hata yakalama modu
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
