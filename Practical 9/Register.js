// Practical 9: Client-Side Registration Validation
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");

    form.addEventListener("submit", function (e) {
        let isValid = true;

        const name = document.getElementById("name");
        const username = document.getElementById("username");
        const email = document.getElementById("email");
        const mobile = document.getElementById("mobile");
        const password = document.getElementById("password");

        // Validate Name
        if (!/^[a-zA-Z\s]{2,50}$/.test(name.value.trim())) {
            showError("nameError", true);
            isValid = false;
        } else {
            showError("nameError", false);
        }

        // Validate Username
        if (username.value.trim().length < 4) {
            showError("usernameError", true);
            isValid = false;
        } else {
            showError("usernameError", false);
        }

        // Validate Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            showError("emailError", true);
            isValid = false;
        } else {
            showError("emailError", false);
        }

        // Validate Mobile (10 digits)
        if (!/^[0-9]{10}$/.test(mobile.value.trim())) {
            showError("mobileError", true);
            isValid = false;
        } else {
            showError("mobileError", false);
        }

        // Validate Password (min 6 chars)
        if (password.value.length < 6) {
            showError("passwordError", true);
            isValid = false;
        } else {
            showError("passwordError", false);
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(elementId, show) {
        const el = document.getElementById(elementId);
        if (el) {
            el.style.display = show ? "block" : "none";
        }
    }
});
