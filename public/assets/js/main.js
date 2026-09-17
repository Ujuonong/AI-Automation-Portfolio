/* ==========================================================================
   DE-JUNONG AI — public interactions
   ========================================================================== */
(function () {
    "use strict";

    /* Mobile navigation */
    var toggle = document.getElementById("navToggle");
    var links = document.getElementById("navLinks");
    if (toggle && links) {
        toggle.addEventListener("click", function () {
            var open = links.classList.toggle("open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        });
        document.addEventListener("click", function (e) {
            if (!links.contains(e.target) && !toggle.contains(e.target)) {
                links.classList.remove("open");
                toggle.setAttribute("aria-expanded", "false");
            }
        });
    }

    /* IntersectionObserver reveal */
    var revealEls = document.querySelectorAll(".reveal");
    if ("IntersectionObserver" in window && revealEls.length) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add("visible"); });
    }

    /* Modal helpers */
    window.openModal = function (id) {
        var m = document.getElementById(id);
        if (m) { m.classList.add("open"); document.body.style.overflow = "hidden"; }
    };
    window.closeModal = function (id) {
        var m = document.getElementById(id);
        if (m) { m.classList.remove("open"); document.body.style.overflow = ""; }
    };
    document.addEventListener("click", function (e) {
        if (e.target.classList && e.target.classList.contains("modal-overlay")) {
            e.target.classList.remove("open");
            document.body.style.overflow = "";
        }
    });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            var open = document.querySelector(".modal-overlay.open");
            if (open) { open.classList.remove("open"); document.body.style.overflow = ""; }
        }
    });

    /* Confirm on destructive actions */
    document.addEventListener("submit", function (e) {
        var form = e.target;
        var message = form.getAttribute("data-confirm");
        if (message && !window.confirm(message)) {
            e.preventDefault();
        }
    });
})();