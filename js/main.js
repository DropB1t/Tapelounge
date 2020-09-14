/* search listeners */
document.querySelectorAll(".search-input").forEach((item) => {
  item.addEventListener("keyup", (event) => {
    var input = event.target.value.toLowerCase();
    var dropdown = $(item).data("id");
    if (input !== "") {
      api_call(input, dropdown);
    }
  });
});

$("#comment-box").keyup(function () {
  var characterCount = $(this).val().length;

  $("#comment-count").text(characterCount + "/1000");
});

/* Dropdown button function */
$(".dropdown-menu").on("click", ".dropdown-item", function (e) {
  e.preventDefault();
  location.href = $(this).attr("href");
  /* alert($(this).attr('href')); */
});

/* API movie scraper */
function api_call(input, dropdown) {
  tmdb.call(
    "/search/movie",
    { language: "en-US", query: input },
    function (e) {
      //console.log(input)
      var results = e.results.splice(0, Math.ceil(e.results.length / 2));
      $(dropdown).empty();
      //console.log(results)
      results.forEach((element) => {
        $(dropdown).append(
          '<a href="movie.php?id=' +
            element.id +
            '" class="dropdown-item">' +
            element.title +
            "</a>"
        );
      });
      $(dropdown).dropdown();
    },
    function (e) {
      console.log("Error: " + e);
    }
  );
}

/* Scroll to Top button */
$(document).ready(function () {
  $(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
      $("#back-to-top").fadeIn();
    } else {
      $("#back-to-top").fadeOut();
    }
  });
  // scroll body to 0px on click
  $("#back-to-top").click(function () {
    $("body,html").animate(
      {
        scrollTop: 0,
      },
      400
    );
    return false;
  });
});

//Accent color managment

document.querySelectorAll(".color").forEach((item) => {
  item.addEventListener("click", (event) => {
    $(".active-color").removeClass("active-color");
    $(item).addClass("active-color");
    var color = $(item).data("color");
    $("#input-color").attr("value", color);
    console.log(color);
  });
});

//Disabling form submissions if there are invalid fields
(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName("needs-validation");
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false
        );
      });
    },
    false
  );
})();
