function validate() {
  var name = document.getElementById("name").value;
  var email = document.getElementById("email").value;
  var mobile = document.getElementById("mobile").value;
  var pass = document.getElementById("pass").value;
  var cpass = document.getElementById("cpass").value;
  var course = document.getElementById("course").value;
  var year = document.getElementById("year").value;
  var male = document.getElementById("male").checked;
  var female = document.getElementById("female").checked;
  var terms = document.getElementById("terms").checked;

  if (!/^[a-zA-Z ]+$/.test(name)) {
    alert("Please enter a valid name (letters only)");
    return false;
  }

  if (!/^\S+@\S+\.\S+$/.test(email)) {
    alert("Please enter a valid email address");
    return false;
  }

  if (!/^[0-9]{10}$/.test(mobile)) {
    alert("Please enter a 10-digit mobile number");
    return false;
  }

  if (pass.length < 6) {
    alert("Password must be at least 6 characters long");
    return false;
  }

  if (pass != cpass) {
    alert("Passwords do not match");
    return false;
  }

  if (course == "") {
    alert("Please select a course");
    return false;
  }

  if (year == "") {
    alert("Please select a year");
    return false;
  }

  if (!male && !female) {
    alert("Please select a gender");
    return false;
  }

  if (!terms) {
    alert("Please accept the terms");
    return false;
  }

  alert("Registration Successful!");
  return true;
} 