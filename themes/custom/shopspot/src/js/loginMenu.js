(function (Drupal) {
  Drupal.behaviors.goToTop = {
    attach: function (context, setting) {
      var topBtn = document.querySelector(".go-top");
      window.onscroll = function () {
        if (
          document.body.scrollTop > 450 ||
          document.documentElement.scrollTop > 450
        ) {
          topBtn.style.display = "flex";
        } else {
          topBtn.style.display = "none";
        }
      };

      topBtn.addEventListener("click", function () {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
      });
    },
  };
})(Drupal);

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
  Drupal.behaviors.minmaxField = {
    attach: function (context, setting) {
      var minMaxField = document.getElementById("edit-specify-wrapper--3");
      var priceWrapper = document.querySelectorAll(
        "#edit-field-price-value--3"
      )[0].children[4];
      priceWrapper.addEventListener("click", function () {
        if (minMaxField.style.display == "none") {
          minMaxField.style.display = "flex";
        } else {
          minMaxField.style.display = "none";
        }
      });
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
      var mobileNav = document.getElementsByClassName(
        "menu--lite-cart-login-menu"
      )[0];
      var lietCard = mobileNav.getElementsByTagName("ul")[0].children[0];
      var filterForm = document.querySelector(".region-category");
      var minMaxField = document.getElementById("edit-specify-wrapper--3");
      lietCard.addEventListener("click", function () {
        filterForm.classList.toggle("visible");
      });
      var applayFilterBtn = document.getElementById("edit-submit-frontpage--3");
      applayFilterBtn.addEventListener("click", function () {
        filterForm.classList.remove("visible");
        minMaxField.style.display = "none";
      });
    },
  };
})(Drupal);
