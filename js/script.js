
// load page
window.addEventListener("load", function () {
    const loader = document.querySelector(".loader");
    setTimeout(function() {
      loader.classList.add("hidden");
    }, 350); // 350ms = 0.35 seconds delay
});

// login-register
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.addEventListener('click', () => {
  wrapper.classList.add('active');
});

loginLink.addEventListener('click', () => {
  wrapper.classList.remove('active');
});

btnPopup.addEventListener('click', () => {
  wrapper.classList.add('active-popup');
});

iconClose.addEventListener('click', () => {
  wrapper.classList.remove('active-popup');
});

// Function to update wrapper position on scroll
function updateWrapperPosition() {
  const windowHeight = window.innerHeight;
  const wrapperHeight = wrapper.offsetHeight;
  const scrollPosition = window.pageYOffset;

  // Calculate the vertical position of the wrapper
  const topPosition = (windowHeight - wrapperHeight) / 2;
  const newPosition = Math.max(topPosition, 0) + scrollPosition;

  // Set the wrapper's top position
  wrapper.style.top = `${newPosition}px`;
}

// Add scroll event listener to update wrapper position
window.addEventListener('scroll', updateWrapperPosition);


// toggle class active untuk hamburger menu
const navbarNav = document.querySelector('.navbars-nav');

// ketika hamburger menu di klik
document.querySelector('#hamburger-menu').onclick = (e) => {
    navbarNav.classList.toggle('active');
    e.preventDefault();
};

// toggle class active untuk search form
const searchForm = document.querySelector('.search-form');
const searchBox = document.querySelector('#search-box');

document.querySelector('#search-button').onclick = (e) => {
    searchForm.classList.toggle('active');
    searchBox.focus();
    e.preventDefault();
}

// toggle class active untuk shoppping cart
const shoppingCart = document.querySelector('.shopping-cart');
document.querySelector('#shopping-cart-button').onclick = (e) => {
    shoppingCart.classList.toggle('active');
    e.preventDefault();
}

// klik di luar elemen
const hm = document.querySelector('#hamburger-menu');
const sb = document.querySelector('#search-button');
const sc = document.querySelector('#shopping-cart-button');

document.addEventListener('click', function(e){
    if(!hm.contains(e.target) && !navbarNav.contains(e.target)){
        navbarNav.classList.remove('active');
    }
    if(!sb.contains(e.target) && !searchForm.contains(e.target)){
        searchForm.classList.remove('active');
    }
    if(!sc.contains(e.target) && !shoppingCart.contains(e.target)){
        shoppingCart.classList.remove('active');
    }
});

// modal box
const itemDetailModal = document.querySelector('#item-detail-modal');

const itemDetailButtons = document.querySelectorAll('.item-detail-button');

itemDetailButtons.forEach((btn) =>  {
    btn.onclick = (e) => {
        itemDetailModal.style.display = 'flex';
        e.preventDefault();
    };
});


// klik tombol close modal
document.querySelector('.modal .close-icon').onclick = (e) => {
    itemDetailModal.style.display = 'none';
    e.preventDefault();
}

// klik di luar modal
window.onclick = (e) => {
    if (e.target === itemDetailModal) {
        itemDetailModal.style.display = 'none';
    }
};

const userDropdown = document.querySelector('#user-dropdown');
const userToggle = userDropdown.querySelector('.dropdown-toggle');
const userMenu = userDropdown.querySelector('.dropdown-menu');

userToggle.addEventListener('click', (event) => {
  event.preventDefault();
  userMenu.classList.toggle('show');
  userToggle.setAttribute('aria-expanded', userMenu.classList.contains('show'));
});

window.addEventListener('click', (event) => {
  if (!userDropdown.contains(event.target)) {
    userMenu.classList.remove('show');
    userToggle.setAttribute('aria-expanded', false);
  }
});

// transisi index
const sliderItems = document.querySelectorAll('.slider-item');

let sliderActive = 1;

if(sliderItems) {
  sliderItems.forEach((slider, index) => {
    if(index == 0) {
      slider.setAttribute("data-show", "show");
    } else {
      slider.setAttribute("data-show", "hidden");
    }
  })

  setInterval(() => {
    sliderItems.forEach((slider, index) => {
      if(sliderActive === index) {
        slider.setAttribute("data-show", "show");
      } else {
        slider.setAttribute("data-show", "hidden");
      }
    });

    if(sliderActive === sliderItems.length - 1) {
      sliderActive = 0;
    } else {
      sliderActive++;
    }

  }, 5000)
}


// slide logo
var copy = document.querySelector('.logos-slide').cloneNode(true);
document.querySelector('.logos').appendChild(copy);


function addToCart(productId) {
  const formData = new FormData();
  formData.append('product_id', productId);

  fetch('shopping_cart.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.text())
  .then(data => {
      showNotification('Product added to cart successfully!');
  })
  .catch(error => console.error('Error:', error));
}

function showNotification(message) {
  const notification = document.getElementById('notification');
  notification.textContent = message;
  notification.classList.add('show');

  setTimeout(() => {
      notification.classList.remove('show');
  }, 3000);
}

document.addEventListener("DOMContentLoaded", function() {
  const addToCartButtons = document.querySelectorAll('.add-to-cart-button');
  addToCartButtons.forEach(button => {
      button.addEventListener('click', function(event) {
          event.preventDefault();
          const productId = button.getAttribute('data-product-id');
          addToCart(productId);
      });
  });
});
