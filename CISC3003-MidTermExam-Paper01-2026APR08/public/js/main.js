(function () {
  "use strict";

  var container = document.getElementById("container");
  var btnShowSignUp = document.getElementById("btnShowSignUp");
  var btnShowSignIn = document.getElementById("btnShowSignIn");

  if (!container || !btnShowSignUp || !btnShowSignIn) {
    return;
  }

  function showSignInView() {
    container.classList.remove("right-panel-active");
  }

  function showSignUpView() {
    container.classList.add("right-panel-active");
  }

  btnShowSignUp.addEventListener("click", showSignUpView);
  btnShowSignIn.addEventListener("click", showSignInView);
})();
