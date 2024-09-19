
// load page
window.addEventListener("load", function () {
  const loader = document.querySelector(".loader");
  setTimeout(function() {
    loader.classList.add("hidden");
  }, 350); // 350ms = 0.35 seconds delay
});

// Tambahkan fungsi untuk mengurutkan tabel berdasarkan kolom
const table = document.querySelector('.transaction-history table');
const thElements = table.querySelectorAll('th');

thElements.forEach(th => {
  th.addEventListener('click', () => {
    const columnIndex = Array.from(th.parentNode.children).indexOf(th);
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    rows.sort((a, b) => {
      const aValue = a.children[columnIndex].textContent.toLowerCase();
      const bValue = b.children[columnIndex].textContent.toLowerCase();

      if (aValue < bValue) return -1;
      if (aValue > bValue) return 1;
      return 0;
    });

    const tbody = table.querySelector('tbody');
    rows.forEach(row => {
      tbody.appendChild(row);
    });
  });
});