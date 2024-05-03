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
        var test = hamMenu.children[0].classList.value;
        if (!test.includes("active")) {
          hamMenu.children[0].classList.add("active-login");
        }
      });
      overlay.addEventListener("click", function () {
        hamMenu.children[0].classList.toggle("active");
        allBackground.classList.toggle("no-scroll");
        var test = hamMenu.children[0].classList.value;
        if (test.includes("active")) {
          hamMenu.children[0].classList.remove("active-login");
        }
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

(function ($, Drupal) {
  Drupal.behaviors.loginBtn = {
    attach: function (context, setting) {
      var loginbtn =
        document.getElementsByClassName("Login-popup-link")[0].children[0];
      var loginMenu =
        document.getElementsByClassName("menu--login")[0].children[1]
          .childNodes[1].childNodes[3];

      $(document).ready(function () {
        $(loginbtn).hover(
          function () {
            $(loginMenu).css("display", "flex");
          },
          function () {
            setTimeout(function () {
              $(loginMenu).css("display", "none");
            }, 2000);
          }
        );
      });
    },
  };
})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.loginPageModal = {
    attach: function (context, setting) {
      var beforBox = document.querySelector("#drupal-modal");
      var classname = beforBox.children[0].classList.value;
      var sidePannelImg = document.createElement("p");
      sidePannelImg.classList.add("bottom-img");
      if (classname == "user-login-form") {
        var login_para = document.createElement("p");
        login_para.classList.add("login-para");
        var login_heading = document.createElement("h2");
        var login_titel = document.createTextNode("Login");
        var login_subtext = document.createElement("h5");
        var login_text = document.createTextNode(
          "Get access to your Orders, Wishlist and Recommendations"
        );
        login_subtext.appendChild(login_text);
        login_heading.appendChild(login_titel);
        login_para.appendChild(login_heading);
        login_para.appendChild(login_subtext);
        login_para.appendChild(sidePannelImg);
        beforBox.appendChild(login_para);
      } else {
        var register_para = document.createElement("p");
        register_para.classList.add("register-para");
        var register_heading = document.createElement("h2");
        var register_titel = document.createTextNode(
          "Looks like you're new here!"
        );
        var register_subtext = document.createElement("h5");
        var register_text = document.createTextNode(
          "Sign up with your mobile number to get started"
        );

        register_subtext.appendChild(register_text);
        register_heading.appendChild(register_titel);
        register_para.appendChild(register_heading);
        register_para.appendChild(register_subtext);
        register_para.appendChild(sidePannelImg);
        beforBox.appendChild(register_para);
      }
    },
  };
})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.multiImage = {
    attach: function (context, setting) {
      $(document).ready(function(){
        var smallImg = document.querySelectorAll('.small-image-wrapper img');
        var largerimage = document.querySelectorAll('.media--view-mode-default img')[1];   
        smallImg.forEach(function(item) {
          item.addEventListener('click', function(){
            largerimage.src = item.src;
            console.log(largerimage);
          });
        });
      });
    },
  };
})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.mymoduleAccessData = {
    attach: function (context, setting) {
      var data = drupalSettings.myname;
      console.log(data);
    }
  };
})(jQuery, Drupal, drupalSettings);