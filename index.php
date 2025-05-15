<?php
$pageTitle = "Ana Sayfa";

// HTML çıktısını yakalamak için:
ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Hoş Geldiniz</h1>
<p class="mb-2">Bu sistemde kursları, öğrencileri ve ödemeleri yönetebilirsiniz.</p>
<p class="text-gray-700">Lütfen yukarıdaki menüyü kullanarak işlem yapınız.</p>
<?php
$pageContent = ob_get_clean();

// Şablonu dahil et
require_once "layout.php";
