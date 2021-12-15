var select_first_word = document.querySelectorAll(".form-select");
for (var i = 0; i < select_first_word.length; i++) {
  var element = (select_first_word[i][0].innerHTML = "All categories");
}

var form_type_redio = document.querySelectorAll(".form-type-radio");
form_type_redio[0].style.display = "none";

var other = document.querySelectorAll(".option");
console.log(other[4]);
other[4].addEventListener("click", function () {
  var min_max_wrapper = document.getElementById("edit-specify-wrapper");
  if (min_max_wrapper.style.display === "none") {
    min_max_wrapper.style.display = "inline-flex";
  } else {
    min_max_wrapper.style.display = "none";
  }
});
