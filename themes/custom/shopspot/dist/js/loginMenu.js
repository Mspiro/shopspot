
(function (Drupal) {
  Drupal.behaviors.searchField = {
    attach: function (context, setting) {
      var searchField = document.getElementsByClassName("form-text");
      searchField[0].placeholder = "Search for products, brands and more";
    },
  };
})(Drupal);

(function (Drupal) {
  Drupal.behaviors.priceTag = {
    attach: function (context, setting) {
      var anyTag = document.getElementsByClassName(
        "form-item-field-price-value"
      )[5];
      anyTag.style.display = "none";
    },
  };
})(Drupal);

(function (Drupal) {
  Drupal.behaviors.hamburger = {
    attach: function (context, setting) {
      var hamburger = document.querySelector(".hamburger");
      var allBackground = document.body;
      var hamMenu = document.getElementsByClassName(
        "menu-link-contentmobilenavmenu"
      )[0];
      var overlay = hamMenu.children[1];
      overlay.style.display = "none";
      hamburger.addEventListener("click", function () {
        hamMenu.children[0].classList.toggle("active");
        allBackground.classList.toggle("no-scroll");
        overlay.style.display = "block";
      });
      overlay.addEventListener("click", function () {
        hamMenu.children[0].classList.toggle("active");
        allBackground.classList.toggle("no-scroll");
        overlay.style.display = "none";
      });
    },
  };
})(Drupal);

(function (Drupal) {
  Drupal.behaviors.filterForm = {
    attach: function (context, setting) {
      var mobileNav = document.getElementsByClassName("menu--lite-cart-login-menu")[0];
      var lietCard = mobileNav.getElementsByTagName('ul')[0].children[0];
      var filterForm = document.querySelector('.region-category');
      lietCard.addEventListener('click', function(){
        filterForm.classList.toggle('visible');
      });
      var applayFilterBtn = document.getElementById('edit-submit-frontpage--3');
      applayFilterBtn.addEventListener('click', function(){
        filterForm.classList.remove('visible');
      });
    },
  };
})(Drupal);
