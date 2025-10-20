document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const loginBtn = document.getElementById("loginBtn");

    function toggleButtonState() {
        const emailFilled = emailInput.value.trim() !== "";
        const passwordValid = passwordInput.value.length >= 6;

        if (emailFilled && passwordValid) {
            loginBtn.disabled = false;
            loginBtn.classList.remove("opacity-50");
            loginBtn.classList.add("cursor-pointer");
        } else {
            loginBtn.disabled = true;
            loginBtn.classList.add("opacity-50");
            loginBtn.classList.remove("cursor-pointer");
        }
    }

    emailInput.addEventListener("input", toggleButtonState);
    passwordInput.addEventListener("input", toggleButtonState);

    toggleButtonState(); // Cek awal
});
