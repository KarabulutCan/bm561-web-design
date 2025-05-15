<?php
require_once "db.php";
$pageTitle = "Öğrenci-Kurs Atamaları";
ob_start();

// Atama Ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $studentId = $_POST['student_id'] ?? '';
    $courseId = $_POST['course_id'] ?? '';

    if (!empty($studentId) && !empty($courseId)) {
        // Aynı atama zaten var mı (opsiyonel kontrol)
        $check = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id=? AND course_id=?");
        $check->execute([$studentId, $courseId]);
        if ($check->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
            $stmt->execute([$studentId, $courseId]);
            echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Atama eklendi.</div>";
        } else {
            echo "<div class='bg-yellow-100 text-yellow-700 p-2 mb-4'>Bu öğrenci zaten bu kursa atanmış.</div>";
        }
    }
}

// Atama Sil
if (isset($_GET['del_stu']) && isset($_GET['del_crs'])) {
    $stu = $_GET['del_stu'];
    $crs = $_GET['del_crs'];
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE student_id=? AND course_id=?");
    $stmt->execute([$stu, $crs]);
    echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Atama silindi.</div>";
}

// Dropdownlar için öğrenci ve kurs listesi
$students = $pdo->query("SELECT id, full_name FROM students ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Mevcut atamaları JOIN ile getirelim
$sql = "SELECT e.student_id, e.course_id, s.full_name, c.name AS course_name
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN courses c ON e.course_id = c.id
        ORDER BY s.full_name, c.name";
$enrollments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">Öğrenci-Kurs Atamaları</h1>

<!-- Atama Ekle Formu -->
<form action="enrollments.php" method="POST" class="mb-6 p-4 bg-white rounded shadow flex space-x-4">
  <input type="hidden" name="action" value="add" />

  <div>
    <label class="block mb-1 font-semibold">Öğrenci Seç</label>
    <select name="student_id" class="border p-2">
      <option value="">Seçiniz</option>
      <?php foreach ($students as $st): ?>
        <option value="<?php echo $st['id']; ?>"><?php echo $st['full_name']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label class="block mb-1 font-semibold">Kurs Seç</label>
    <select name="course_id" class="border p-2">
      <option value="">Seçiniz</option>
      <?php foreach ($courses as $c): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="flex items-end">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ekle</button>
  </div>
</form>

<!-- Mevcut Atamalar -->
<table class="w-full bg-white rounded shadow border">
  <thead class="bg-gray-200">
    <tr>
      <th class="p-2 text-left">Öğrenci</th>
      <th class="p-2 text-left">Kurs</th>
      <th class="p-2 text-left">İşlem</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($enrollments as $en): ?>
    <tr class="border-b">
      <td class="p-2"><?php echo htmlspecialchars($en['full_name']); ?></td>
      <td class="p-2"><?php echo htmlspecialchars($en['course_name']); ?></td>
      <td class="p-2">
        <a href="enrollments.php?del_stu=<?php echo $en['student_id']; ?>&del_crs=<?php echo $en['course_id']; ?>"
           class="bg-red-500 text-white px-3 py-1 rounded"
           onclick="return confirm('Bu atamayı silmek istediğinize emin misiniz?');">
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
