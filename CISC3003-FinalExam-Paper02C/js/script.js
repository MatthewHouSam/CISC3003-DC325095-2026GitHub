document.addEventListener("DOMContentLoaded", () => {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");

    if (registerForm) {
        registerForm.addEventListener("submit", (event) => {
            const password = document.getElementById("password");
            const confirm = document.getElementById("confirm_password");
            if (!password || !confirm) {
                return;
            }

            if (password.value.length < 8) {
                event.preventDefault();
                alert("Password must be at least 8 characters.");
                return;
            }

            if (password.value !== confirm.value) {
                event.preventDefault();
                alert("Password and confirm password do not match.");
            }
        });

        const emailInput = document.getElementById("email");
        const emailCheck = document.getElementById("emailCheck");
        if (emailInput && emailCheck) {
            emailInput.addEventListener("blur", async () => {
                const value = emailInput.value.trim();
                if (!value) {
                    emailCheck.textContent = "";
                    return;
                }

                try {
                    const response = await fetch(`php/check_email.php?email=${encodeURIComponent(value)}`);
                    const data = await response.json();
                    emailCheck.textContent = data.message || "";
                    emailCheck.className = data.exists ? "danger" : "good";
                } catch (error) {
                    emailCheck.textContent = "Email check failed";
                    emailCheck.className = "danger";
                }
            });
        }
    }

    if (loginForm) {
        loginForm.addEventListener("submit", (event) => {
            if (!loginForm.checkValidity()) {
                event.preventDefault();
                alert("Please fill in a valid email and password.");
            }
        });
    }
});
