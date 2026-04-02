const nav = document.querySelector(".main-nav");
const button = document.querySelector(".nav-toggle");
const navLinks = document.querySelectorAll(".nav-list a");

if (nav && button) {
  button.addEventListener("click", function () {
    nav.classList.toggle("nav-open");

    const isOpen = nav.classList.contains("nav-open");
    button.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  navLinks.forEach(function (link) {
    link.addEventListener("click", function () {
      nav.classList.remove("nav-open");
      button.setAttribute("aria-expanded", "false");
    });
  });
}