// (function (Drupal) {
//   Drupal.behaviors.loginBehaviors = {
//     attach: function (context, setting) {
//       let targetMenu = document.getElementById("block-login-menu");
//       let menu = document
//         .getElementById("block-login")
//         .getElementsByTagName("ul")[1];

//       targetMenu.addEventListener("mouseover", function () {
//         if(menu.style.display==="none"){
//           menu.style.display = "flex";
//         }
//         else{
//           menu.style.display = "none";
//         }
//       });
//       // menu.addEventListener("mouseleave", function () {
//       //   menu.style.display = "none";
//       // });
//     },
//   };
// })(Drupal);

// (function (Drupal) {
//     Drupal.behaviors.moreMenuBehaviors = {
//       attach: function (context, setting) {
//         let moreTitle = document.getElementById("more-title");
//         let arrow = document
//           .getElementById("down-arrow")
//           .getElementsByTagName("svg")[0];
//           // console.log(arrow.classList);
//         moreTitle.addEventListener("mouseenter", function () {
//           arrow.classList.add('rotate');
//           console.log(arrow.classList);
//         });
//         moreTitle.addEventListener("mouseleave", function () {
//           arrow.classList.remove('rotate');
//           // arrow.classList.toggle('rotate-reverse');
//           console.log(arrow.classList);
//         });
//       },
//     };
//   })(Drupal);
  