<?php
require_once "db.php";
$pageTitle = "Öğrenci Yönetimi";
ob_start();

// Silme
if (isset($_GET['delete_id'])) {
    $delId = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id=:id");
    $stmt->execute([':id' => $delId]);
    echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Öğrenci silindi (ID: $delId)</div>";
}

// Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!empty($fullName)) {
        $sql = "INSERT INTO students (full_name, email, phone) VALUES (:fn, :em, :ph)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':fn' => $fullName, ':em' => $email, ':ph' => $phone]);
        echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Yeni öğrenci eklendi.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-700 p-2 mb-4'>İsim boş olamaz!</div>";
    }
}

// Güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $editId = $_POST['edit_id'];
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!empty($editId) && !empty($fullName)) {
        $sql = "UPDATE students SET full_name=:fn, email=:em, phone=:ph WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':fn' => $fullName, ':em' => $email, ':ph' => $phone, ':id' => $editId]);
        echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Öğrenci güncellendi.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-700 p-2 mb-4'>Geçersiz veri!</div>";
    }
}

// Edit mod?
$editData = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id=:id");
    $stmt->execute([':id' => $editId]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Tüm öğrenciler
$students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">Öğrenci Yönetimi</h1>

<!-- Öğrenci Ekleme Formu -->
<?php if (!$editData): ?>
<form action="students.php" method="POST" class="mb-6 p-4 bg-white rounded shadow">
  <input type="hidden" name="action" value="add" />
  
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Ad Soyad</label>
    <input type="text" name="full_name" class="border w-full p-2" required />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">E-posta</label>
    <input type="email" name="email" class="border w-full p-2" />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Telefon</label>
    <input type="text" name="phone" class="border w-full p-2" />
  </div>
  <button class="bg-blue-500 text-white px-4 py-2 rounded" type="submit">Ekle</button>
</form>
<?php endif; ?>

<!-- Öğrenci Düzenleme Formu -->
<?php if ($editData): ?>
<form action="students.php" method="POST" class="mb-6 p-4 bg-white rounded shadow">
  <input type="hidden" name="action" value="edit" />
  <input type="hidden" name="edit_id" value="<?php echo $editData['id']; ?>" />
  
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Ad Soyad</label>
    <input type="text" name="full_name" class="border w-full p-2" required
           value="<?php echo htmlspecialchars($editData['full_name']); ?>" />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">E-posta</label>
    <input type="email" name="email" class="border w-full p-2"
           value="<?php echo htmlspecialchars($editData['email']); ?>" />
  </div>
  <div class="mb-4">
    <label class="block mb-1 font-semibold">Telefon</label>
    <input type="text" name="phone" class="border w-full p-2"
           value="<?php echo htmlspecialchars($editData['phone']); ?>" />
  </div>
  <button class="bg-green-500 text-white px-4 py-2 rounded" type="submit">Güncelle</button>
  <a href="students.php" class="bg-gray-300 px-3 py-2 rounded ml-2">Vazgeç</a>
</form>
<?php endif; ?>

<!-- Öğrenciler Tablosu -->
<table class="w-full bg-white rounded shadow border">
  <thead class="bg-gray-200">
    <tr>
      <th class="p-2 text-left">ID</th>
      <th class="p-2 text-left">Ad Soyad</th>
      <th class="p-2 text-left">E-posta</th>
      <th class="p-2 text-left">Telefon</th>
      <th class="p-2 text-left">İşlem</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($students as $st): ?>
    <tr class="border-b">
      <td class="p-2"><?php echo $st['id']; ?></td>
      <td class="p-2"><?php echo htmlspecialchars($st['full_name']); ?></td>
      <td class="p-2"><?php echo htmlspecialchars($st['email']); ?></td>
      <td class="p-2"><?php echo htmlspecialchars($st['phone']); ?></td>
      <td class="p-2">
        <a href="students.php?edit_id=<?php echo $st['id']; ?>"
           class="bg-yellow-500 text-white px-3 py-1 rounded mr-2">Düzenle</a>
        <a href="students.php?delete_id=<?php echo $st['id']; ?>"
           class="bg-red-500 text-white px-3 py-1 rounded"
           onclick="return confirm('Silinsin mi ??');">Sil</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$pageContent = ob_get_clean();
require_once "layout.php";
