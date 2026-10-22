let loginForm = document.getElementById("loginForm");
let identity = document.getElementById("identity");
let password = document.getElementById("password");
let showPassword = document.getElementById("showPassword");

if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
        let value = identity.value.trim();

        if (value === "") {
            event.preventDefault();
            alert("Please enter Enrollment Number, Username, or Email");
            return;
        }

        if (password.value === "") {
            event.preventDefault();
            alert("Please enter Password");
        }
    });
}

if (showPassword && password) {
    showPassword.addEventListener("click", function () {
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    });
}
