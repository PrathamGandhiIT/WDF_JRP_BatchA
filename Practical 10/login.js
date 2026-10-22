// Practical 10: Client-Side Login Form Validation
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            const identifier = document.getElementById("identifier").value.trim();
            const password = document.getElementById("password").value;

            if (!identifier || !password) {
                alert("Please enter both username/email and password.");
                e.preventDefault();
            }
        });
    }
});
