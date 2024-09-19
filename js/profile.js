// load page
window.addEventListener("load", function () {
  const loader = document.querySelector(".loader");
  setTimeout(function() {
    loader.classList.add("hidden");
  }, 350); // 350ms = 0.35 seconds delay
});


const userPhotoInput = document.getElementById('user_photo');
const userPhotoImg = document.querySelector('.profile-image img');

userPhotoInput.addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      userPhotoImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});



const backButton = document.querySelector('a[href="index.php"] button');

backButton.addEventListener('click', function(event) {
  event.preventDefault();

  // Show the alert
  showAlert('Are you sure you want to go back? Any unsaved changes will be lost.');

  // If the user confirms, redirect to index.php
  if (confirm('Are you sure you want to go back?')) {
    window.location.href = 'index.php';
  } else {
    // Remove the alert if the user cancels
    const alertDiv = document.querySelector('.alert');
    alertDiv.remove();
  }
});

function showAlert(message) {
  const alertDiv = document.createElement('div');
  alertDiv.classList.add('alert');
  alertDiv.textContent = message;

  document.body.appendChild(alertDiv);
}