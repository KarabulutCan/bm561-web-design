<?php
// layout.php
$pageTitle = $pageTitle ?? "Kurs Otomasyonu";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome CSS -->
  <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      integrity="sha512-51HrLTS2JlN/vAOurO6ka8vUAnUZkVj39coST3vGy0yPPmRFNULGjG6LlfveJ+zE/3YSrYpT6WjsffF4RW1VuQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Kendimize ait CSS (assets/css/style.css) -->
  <link rel="stylesheet" href="assets/css/style.css" />

</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Üst Menü -->
  <nav class="bg-blue-600 text-white p-4">
    <div class="container mx-auto flex justify-between">
      <div class="font-bold">
        <a href="index.php" class="hover:text-gray-200">Kurs Otomasyonu</a>
      </div>
      <div>
        <a href="courses.php" class="mr-4 hover:text-gray-200">Kurslar</a>
        <a href="students.php" class="mr-4 hover:text-gray-200">Öğrenciler</a>
        <a href="enrollments.php" class="mr-4 hover:text-gray-200">Atama</a>
        <a href="payments.php" class="hover:text-gray-200">Ödemeler</a>
      </div>
    </div>
  </nav>

  <!-- İçerik Alanı -->
  <main class="container mx-auto flex-1 p-4">
    <?php
    // Her sayfada oluşturulan $pageContent buraya basılacak
    echo $pageContent ?? "";
    ?>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white p-4 mt-auto">
    <div class="container mx-auto text-center">
      <p class="text-sm">&copy; <?php echo date('Y'); ?> Kurs Otomasyonu - Can Karabulut.</p>
    </div>
  </footer>

  <!-- Kendimize ait JS (assets/js/script.js) -->
  <script src="assets/js/script.js"></script>
</body>
</html>
