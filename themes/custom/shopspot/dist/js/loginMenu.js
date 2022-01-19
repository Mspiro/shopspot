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
      let searchField = document.getElementsByClassName("form-text");
      searchField[0].placeholder = "Search for products, brands and more";
    },
  };
})(Drupal);

(function (Drupal) {
  Drupal.behaviors.priceTag = {
    attach: function (context, setting) {
      let anyTag = document.getElementsByClassName(
        "form-item-field-price-value"
      )[5];
      anyTag.style.display = "none";
    },
  };
})(Drupal);

(function (Drupal) {
  Drupal.behaviors.minmaxBox = {
    attach: function (context, setting) {
      let otherRadioBtn = document.getElementsByClassName(
        "form-item-field-price-value"
      )[9];
      let minMaxBox = document.getElementsByClassName("form-wrapper")[4];
    },
  };
})(Drupal);
