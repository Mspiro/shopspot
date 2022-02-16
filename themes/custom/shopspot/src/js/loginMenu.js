// window.onscroll = function () {
//   scrollFunction();
// };

// function scrollFunction() {
//   if (document.documentElement.scrollTop > 990) {
//     console.log("Hello");
//   }
// }

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
      console.log(hamburger);
      var hamMenu = document.getElementsByClassName(
        "menu-link-contentmobilenavmenu"
        )[0];
      var overlay = hamMenu.children[1];
        console.log(overlay);
      var list = hamMenu.classList.add("hidden");
      hamburger.addEventListener("click", function () {
        hamMenu.classList.toggle("hidden");
        hamMenu.children[0].setAttribute('id','hello');
      });
      overlay.addEventListener("click", function () {
        hamMenu.classList.toggle("hidden");
      });
    },
  };
})(Drupal);
