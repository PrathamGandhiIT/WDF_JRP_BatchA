function validateLogin() {
    var user = document.getElementById("username").value.trim();
    var pass = document.getElementById("password").value;
    var role = document.getElementById("role").value;

    if (user === "") {
        alert("Please enter your Username / ID");
        document.getElementById("username").focus();
        return false;
    }

    if (pass === "") {
        alert("Please enter your password");
        document.getElementById("password").focus();
        return false;
    }

    if (pass.length < 6) {
        alert("Password must be at least 6 characters long");
        document.getElementById("password").focus();
        return false;
    }

    if (role === "") {
        alert("Please select your role");
        document.getElementById("role").focus();
        return false;
    }

    // Role-based redirection
    if (role === "student") {
        window.location.href = "StudentDashboard.html";
    } else if (role === "faculty") {
        window.location.href = "FacultyDashboard.html";
    } else if (role === "admin") {
        window.location.href = "AdminDashboard.html";
    }

    return false;
}