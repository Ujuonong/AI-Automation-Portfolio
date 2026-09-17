/* Admin interactions */
(function () {
    "use strict";

    var sidebar = document.getElementById("adminSidebar");
    var menuBtn = document.getElementById("menuToggle");
    if (sidebar && menuBtn) {
        menuBtn.addEventListener("click", function () {
            sidebar.classList.toggle("open");
        });
        document.addEventListener("click", function (e) {
            if (sidebar.classList.contains("open") && !sidebar.contains(e.target) && e.target !== menuBtn) {
                sidebar.classList.remove("open");
            }
        });
    }

    /* Confirm destructive forms */
    document.addEventListener("submit", function (e) {
        var form = e.target;
        var message = form.getAttribute("data-confirm");
        if (message && !window.confirm(message)) e.preventDefault();
        if (form.getAttribute("data-confirm") === null && form.classList.contains("js-confirm")) {
            if (!window.confirm(form.getAttribute("data-message") || "Are you sure?")) e.preventDefault();
        }
    });

    /* Slide-in sidebar via backdrop for mobile */
    function closeBackdrop() {
        document.querySelectorAll(".admin-sidebar").forEach(function (el) { el.classList.remove("open"); });
    }
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeBackdrop();
    });

    /* Auto-hide flash alerts */
    document.querySelectorAll(".alert").forEach(function (el) {
        setTimeout(function () {
            el.style.transition = "opacity .4s ease";
            el.style.opacity = "0";
            setTimeout(function () { el.remove(); }, 450);
        }, 6000);
    });
})();