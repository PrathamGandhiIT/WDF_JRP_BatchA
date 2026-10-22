let loginForm = document.getElementById("loginForm");
let identity = document.getElementById("identity");
let password = document.getElementById("password");
let showPassword = document.getElementById("showPassword");
let LoginLogout = document.getElementById("LoginLogout");

if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
        let value = identity.value.trim();

        if (value === "") {
            event.preventDefault();
            alert("Please enter your Enrollment Number or Username");
            return;
        }

        if (password.value === "") {
            event.preventDefault();
            alert("Please enter your Password");
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

if (LoginLogout) {
    LoginLogout.addEventListener("click", function (event) {
        if (LoginLogout.innerText.trim() === "Logout") {
            event.preventDefault();
            window.location.href = "logout.php";
        }
    });
}
