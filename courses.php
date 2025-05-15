<?php
require_once "db.php"; // MySQL bağlantımız
$pageTitle = "Kurs Yönetimi";
ob_start();

// 1) Kurs Silme
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
    $stmt->execute([':id' => $deleteId]);
    echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Kurs silindi (ID: $deleteId)</div>";
}

// 2) Kurs Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO courses (name, description) VALUES (:n, :d)");
        $stmt->execute([':n' => $name, ':d' => $desc]);
        echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Yeni kurs eklendi.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-700 p-2 mb-4'>Kurs adı boş olamaz.</div>";
    }
}

// 3) Kurs Güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $editId = $_POST['edit_id'];
    $name   = $_POST['name'] ?? '';
    $desc   = $_POST['description'] ?? '';

    if (!empty($editId) && !empty($name)) {
        $stmt = $pdo->prepare("UPDATE courses SET name=:n, description=:d WHERE id=:id");
        $stmt->execute([':n' => $name, ':d' => $desc, ':id' => $editId]);
        echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Kurs güncellendi.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-700 p-2 mb-4'>Geçersiz veri!</div>";
    }
}

// 4) Düzenleme moduna geçilecekse, ilgili kursu getir
$editData = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id=:id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 5) Tüm kursları çek
$courses = $pdo->query("SELECT * FROM courses ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">Kurs Yönetimi</h1>

<!-- Kurs Ekleme Formu (sadece edit modunda değilsek) -->
<?php if (!$editData): ?>
<form action="courses.php" method="POST" class="mb-6 p-4 bg-white rounded shadow">
  <input type="hidden" name="action" value="add" />
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Kurs Adı</label>
    <input type="text" name="name" class="border w-full p-2" required />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Açıklama</label>
    <textarea name="description" class="border w-full p-2" rows="2"></textarea>
  </div>
  <button class="bg-blue-500 text-white px-4 py-2 rounded" type="submit">Ekle</button>
</form>
<?php endif; ?>

<!-- Kurs Düzenleme Formu (editData doluysa göster) -->
<?php if ($editData): ?>
<form action="courses.php" method="POST" class="mb-6 p-4 bg-white rounded shadow">
  <input type="hidden" name="action" value="edit" />
  <input type="hidden" name="edit_id" value="<?php echo $editData['id']; ?>" />

  <div class="mb-4">
    <label class="block mb-1 font-semibold">Kurs Adı</label>
    <input type="text" name="name" class="border w-full p-2" 
           value="<?php echo htmlspecialchars($editData['name']); ?>" required />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Açıklama</label>
    <textarea name="description" class="border w-full p-2" rows="2"><?php
      echo htmlspecialchars($editData['description']);
    ?></textarea>
  </div>
  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Güncelle</button>
  <a href="courses.php" class="bg-gray-300 px-3 py-2 rounded ml-2">Vazgeç</a>
</form>
<?php endif; ?>

<!-- Arama Kutusu (JS ile tablo filtreleme yapmak istersen) -->
<div class="mb-4">
  <label for="courseSearchInput" class="font-semibold mr-2">Kurslarda Ara:</label>
  <input type="text" id="courseSearchInput" class="border p-2 w-64" placeholder="Kurs adına göre arama..." />
</div>

<!-- Kurs Listesi -->
<table id="coursesTable" class="striped w-full bg-white rounded shadow border">
  <thead class="bg-gray-200">
    <tr>
      <th class="p-2 text-left">ID</th>
      <th class="p-2 text-left">Kurs Adı</th>
      <th class="p-2 text-left">Açıklama</th>
      <th class="p-2 text-left">İşlem</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($courses as $c): ?>
      <tr class="border-b">
        <td class="p-2"><?php echo $c['id']; ?></td>
        <td class="p-2"><?php echo htmlspecialchars($c['name']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($c['description']); ?></td>
        <td class="p-2">
          <!-- DÜZENLE (Sarı Buton) -->
          <a href="courses.php?edit_id=<?php echo $c['id']; ?>"
             class="bg-yellow-500 text-white px-3 py-1 rounded mr-2">
            Düzenle
          </a>
          
          <!-- SİL (Kırmızı Buton) -->
          <a href="courses.php?delete_id=<?php echo $c['id']; ?>"
             class="bg-red-500 text-white px-3 py-1 rounded"
             onclick="return confirm('Silmek istediğinize emin misiniz?');">
            Sil
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$pageContent = ob_get_clean();
require_once "layout.php";
