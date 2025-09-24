document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector("[data-collapse-toggle='navbar-sticky']");
    const menu = document.getElementById("navbar-sticky");

    toggleBtn.addEventListener("click", function () {
        menu.classList.toggle("hidden");
    });
});