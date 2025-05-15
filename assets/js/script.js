// script.js

// DOM yüklendiğinde çalışacak fonksiyon
document.addEventListener('DOMContentLoaded', function() {
    console.log("Custom JS yüklendi.");
  
    let courseSearchInput = document.getElementById('courseSearchInput');
    if (courseSearchInput) {
      courseSearchInput.addEventListener('keyup', function() {
        let filter = courseSearchInput.value.toLowerCase();
        // coursesTable tablosundaki satırları bul
        let rows = document.querySelectorAll('#coursesTable tbody tr');
        rows.forEach(function(row) {
          let rowText = row.innerText.toLowerCase();
          // Aranan metin row'da geçiyorsa göster, geçmiyorsa gizle
          row.style.display = rowText.includes(filter) ? '' : 'none';
        });
      });
    }
  });
  