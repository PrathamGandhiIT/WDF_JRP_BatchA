
// Theme

var themeButton = document.getElementById("themeButton");

if (themeButton) {

    if (localStorage.getItem("studentHubTheme") === "dark") {
        document.body.classList.add("dark-mode");
        themeButton.textContent = "Light Mode";
    }

    themeButton.addEventListener("click", function () {

        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {
            themeButton.textContent = "Light Mode";
            localStorage.setItem("studentHubTheme", "dark");
        } else {
            themeButton.textContent = "Dark Mode";
            localStorage.setItem("studentHubTheme", "light");
        }

    });
}


// Hamburger Menu

var menuButton = document.getElementById("menuButton");
var navigation = document.getElementById("navigation");

if (menuButton && navigation) {

    menuButton.addEventListener("click", function () {

        navigation.classList.toggle("show");

        if (navigation.classList.contains("show")) {
            menuButton.textContent = "X Close Menu";
        } else {
            menuButton.textContent = "☰ Menu";
        }

    });

    var navigationLinks = navigation.querySelectorAll("a");

    navigationLinks.forEach(function (link) {

        link.addEventListener("click", function () {
            navigation.classList.remove("show");
            menuButton.textContent = "☰ Menu";
        });

    });

}


// Notification Banner

var notification = document.getElementById("notification");
var closeNotification = document.getElementById("closeNotification");

if (notification && closeNotification) {

    closeNotification.addEventListener("click", function () {
        notification.style.display = "none";
    });

}


// Modal Popup

var modal = document.getElementById("modal");
var learnMoreButton = document.getElementById("learnMoreButton");
var closeModal = document.getElementById("closeModal");

if (modal && learnMoreButton && closeModal) {

    learnMoreButton.addEventListener("click", function () {
        modal.classList.add("active");
    });

    closeModal.addEventListener("click", function () {
        modal.classList.remove("active");
    });

    window.addEventListener("click", function (event) {

        if (event.target === modal) {
            modal.classList.remove("active");
        }

    });

}


// FAQ

var faqQuestions = document.querySelectorAll(".faq-question");

faqQuestions.forEach(function (question) {

    question.addEventListener("click", function () {

        var faqItem = question.parentElement;

        faqItem.classList.toggle("active");

        var symbol = question.querySelector("span");

        if (symbol) {
            if (faqItem.classList.contains("active")) {
                symbol.textContent = "-";
            } else {
                symbol.textContent = "+";
            }
        }

    });

});


// Content Slider

var slides = document.querySelectorAll(".slide");
var currentSlide = 0;

var nextSlide = document.getElementById("nextSlide");
var previousSlide = document.getElementById("previousSlide");

function showSlide(index) {

    if (slides.length === 0) {
        return;
    }

    slides.forEach(function (slide) {
        slide.classList.remove("active");
    });

    slides[index].classList.add("active");
}

if (slides.length > 0) {

    if (nextSlide) {

        nextSlide.addEventListener("click", function () {

            currentSlide++;

            if (currentSlide >= slides.length) {
                currentSlide = 0;
            }

            showSlide(currentSlide);

        });

    }

    if (previousSlide) {

        previousSlide.addEventListener("click", function () {

            currentSlide--;

            if (currentSlide < 0) {
                currentSlide = slides.length - 1;
            }

            showSlide(currentSlide);

        });

    }

    setInterval(function () {

        currentSlide++;

        if (currentSlide >= slides.length) {
            currentSlide = 0;
        }

        showSlide(currentSlide);

    }, 4000);

}
