const menuButton = document.getElementById("menuButton");
const navbar = document.getElementById("navbar");
const themeButton = document.getElementById("themeButton");
const notification = document.getElementById("notification");
const modal = document.getElementById("modal");

if (menuButton && navbar) {
    menuButton.addEventListener("click", function () {
        navbar.classList.toggle("show");
        menuButton.setAttribute(
            "aria-expanded",
            navbar.classList.contains("show")
        );
    });
}

const closeNotifBtn = document.getElementById("closeNotification");
if (closeNotifBtn && notification) {
    closeNotifBtn.addEventListener("click", function () {
        notification.style.display = "none";
    });
}

function openModal() {
    if (modal) modal.hidden = false;
}

function closeModal() {
    if (modal) modal.hidden = true;
}

const regBtn = document.getElementById("registerButton");
if (regBtn) {
    regBtn.addEventListener("click", function () {
        window.location.href = "Register.html";
    });
}

const loginBtn = document.getElementById("loginButton");
if (loginBtn) {
    loginBtn.addEventListener("click", function () {
        window.location.href = "login.php";
    });
}

const modalRegBtn = document.getElementById("modalRegisterButton");
if (modalRegBtn) {
    modalRegBtn.addEventListener("click", function () {
        window.location.href = "Register.html";
    });
}

const modalLoginBtn = document.getElementById("modalLoginButton");
if (modalLoginBtn) {
    modalLoginBtn.addEventListener("click", function () {
        window.location.href = "login.php";
    });
}

const closeModBtn = document.getElementById("closeModal");
if (closeModBtn) {
    closeModBtn.addEventListener("click", closeModal);
}

if (modal) {
    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });
}

function applyTheme() {
    const savedTheme = localStorage.getItem("studenthub-theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        if (themeButton) themeButton.textContent = "Light Mode";
    } else {
        document.body.classList.remove("dark");
        if (themeButton) themeButton.textContent = "Dark Mode";
    }
}

if (themeButton) {
    themeButton.addEventListener("click", function () {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            localStorage.setItem("studenthub-theme", "dark");
            themeButton.textContent = "Light Mode";
        } else {
            localStorage.setItem("studenthub-theme", "light");
            themeButton.textContent = "Dark Mode";
        }
    });
}

const activities = [
    {
        title: "Tech Fest 2026",
        description: "Join the university Tech Fest and participate in technical competitions. Lead: Raj Patel."
    },
    {
        title: "Web Development Hackathon",
        description: "Participate in 36-hour sprint coding modern full-stack web applications. Incharge: Manish Kumar."
    },
    {
        title: "Coding Competition & Algothon",
        description: "Test your programming skills and solve algorithmic challenges for exciting cash prizes."
    }
];

let currentSlide = 0;

function showSlide() {
    const slideContent = document.getElementById("slideContent");
    if (slideContent && activities[currentSlide]) {
        slideContent.innerHTML = `
            <h3>${activities[currentSlide].title}</h3>
            <p>${activities[currentSlide].description}</p>
        `;
    }
}

const prevBtn = document.getElementById("previousButton");
if (prevBtn) {
    prevBtn.addEventListener("click", function () {
        currentSlide = (currentSlide - 1 + activities.length) % activities.length;
        showSlide();
    });
}

const nextBtn = document.getElementById("nextButton");
if (nextBtn) {
    nextBtn.addEventListener("click", function () {
        currentSlide = (currentSlide + 1) % activities.length;
        showSlide();
    });
}

applyTheme();
showSlide();
