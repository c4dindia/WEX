const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

 let accountType = document.querySelector("#business_affiliation");
 let registerBtn = document.querySelector("#register-btn");
 accountType.addEventListener('change', function() {
    if (accountType.value == "0") {
         registerBtn.disabled = true; // Disable the button
     } else {
        registerBtn.disabled = false; // Enable the button
 } });
