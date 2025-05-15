<?php
require_once "db.php";
$pageTitle = "Ödemeler";
ob_start();

// Ödeme Sil
if (isset($_GET['delete_id'])) {
    $delId = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM payments WHERE id=?");
    $stmt->execute([$delId]);
    echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Ödeme silindi (ID: $delId)</div>";
}

// Ödeme Ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $studentId = $_POST['student_id'] ?? '';
    $courseId = $_POST['course_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $paymentDate = $_POST['payment_date'] ?? date('Y-m-d');
    $note = $_POST['note'] ?? '';

    if (!empty($studentId) && $amount > 0) {
        $sql = "INSERT INTO payments (student_id, course_id, amount, payment_date, note)
                VALUES (:stu, :crs, :amt, :pdate, :n)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':stu' => $studentId,
            ':crs' => !empty($courseId) ? $courseId : null,
            ':amt' => $amount,
            ':pdate' => $paymentDate,
            ':n' => $note
        ]);
        echo "<div class='bg-green-100 text-green-700 p-2 mb-4'>Ödeme kaydı eklendi.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-700 p-2 mb-4'>Gerekli alanları doldurun!</div>";
    }
}

// Dropdownlar için öğrenci ve kurs listesi
$students = $pdo->query("SELECT id, full_name FROM students ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Ödemeleri listele (JOIN)
$sql = "SELECT p.id, p.student_id, p.course_id, p.amount, p.payment_date, p.note,
               s.full_name, c.name AS course_name
        FROM payments p
        JOIN students s ON p.student_id = s.id
        LEFT JOIN courses c ON p.course_id = c.id
        ORDER BY p.payment_date DESC, p.id DESC";
$payments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">Ödemeler</h1>

<!-- Ödeme Ekle Formu -->
<form action="payments.php" method="POST" class="mb-6 p-4 bg-white rounded shadow">
  <input type="hidden" name="action" value="add" />

  <div class="flex space-x-4 mb-4">
    <div class="w-1/3">
      <label class="block mb-1 font-semibold">Öğrenci</label>
      <select name="student_id" class="border p-2 w-full">
        <option value="">Seçiniz</option>
        <?php foreach ($students as $st): ?>
        <option value="<?php echo $st['id']; ?>"><?php echo $st['full_name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="w-1/3">
      <label class="block mb-1 font-semibold">Kurs (opsiyonel)</label>
      <select name="course_id" class="border p-2 w-full">
        <option value="">Seçiniz</option>
        <?php foreach ($courses as $c): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="flex space-x-4 mb-4">
    <div class="w-1/3">
      <label class="block mb-1 font-semibold">Tutar (TL)</label>
      <input type="number" step="0.01" name="amount" class="border p-2 w-full" required />
    </div>
    <div class="w-1/3">
      <label class="block mb-1 font-semibold">Ödeme Tarihi</label>
      <input type="date" name="payment_date" class="border p-2 w-full" value="<?php echo date('Y-m-d'); ?>" />
    </div>
  </div>

  <div class="mb-4">
    <label class="block mb-1 font-semibold">Not</label>
    <input type="text" name="note" class="border p-2 w-full" />
  </div>

  <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kaydet</button>
</form>

<!-- Ödeme Listesi -->
<table class="w-full bg-white rounded shadow border">
  <thead class="bg-gray-200">
    <tr>
      <th class="p-2 text-left">ID</th>
      <th class="p-2 text-left">Öğrenci</th>
      <th class="p-2 text-left">Kurs</th>
      <th class="p-2 text-left">Tutar (TL)</th>
      <th class="p-2 text-left">Tarih</th>
      <th class="p-2 text-left">Not</th>
      <th class="p-2 text-left">İşlem</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($payments as $pay): ?>
    <tr class="border-b">
      <td class="p-2"><?php echo $pay['id']; ?></td>
      <td class="p-2"><?php echo htmlspecialchars($pay['full_name']); ?></td>
      <td class="p-2"><?php echo htmlspecialchars($pay['course_name'] ?? ''); ?></td>
      <td class="p-2"><?php echo $pay['amount']; ?></td>
      <td class="p-2"><?php echo $pay['payment_date']; ?></td>
      <td class="p-2"><?php echo htmlspecialchars($pay['note']); ?></td>
      <td class="p-2">
        <a href="payments.php?delete_id=<?php echo $pay['id']; ?>"
           class="bg-red-500 text-white px-3 py-1 rounded"
           onclick="return confirm('Bu ödeme kaydını silmek istediğinize emin misiniz?');">
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
