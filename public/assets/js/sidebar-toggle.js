(() => {
  const sidebar = document.getElementById("sidebar");
  const backdrop = document.getElementById("backdrop");
  const openBtn = document.getElementById("sidebar-open");
  const closeBtn = document.getElementById("sidebar-close");
  const appMain = document.getElementById("app-content");

  const peopleToggle = document.getElementById("people-toggle");
  const peopleMenu = document.getElementById("people-menu");
  const peopleCaret = document.getElementById("people-caret");

  function openSidebar() {
    sidebar.classList.remove("-translate-x-full");
    backdrop.classList.remove("hidden");
    document.body.style.overflow = "hidden";
    closeBtn && closeBtn.focus();
  }

  function closeSidebar() {
    sidebar.classList.add("-translate-x-full");
    backdrop.classList.add("hidden");
    document.body.style.overflow = "";
    openBtn && openBtn.focus();
  }

  openBtn && openBtn.addEventListener("click", openSidebar);
  closeBtn && closeBtn.addEventListener("click", closeSidebar);
  backdrop && backdrop.addEventListener("click", closeSidebar);

  // Close on ESC in mobile
  document.addEventListener("keydown", (e) => {
    if (
      e.key === "Escape" &&
      window.innerWidth < 768 &&
      !sidebar.classList.contains("-translate-x-full")
    ) {
      closeSidebar();
    }
  });

  // Keep correct state on resize
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 768) {
      // desktop: visible, no backdrop, allow page scroll in right pane
      sidebar.classList.remove("-translate-x-full");
      backdrop.classList.add("hidden");
      document.body.style.overflow = "";
    } else {
      // mobile: start hidden; right pane scrolls
      sidebar.classList.add("-translate-x-full");
    }
  });

  // People submenu
  if (peopleToggle && peopleMenu && peopleCaret) {
    peopleToggle.addEventListener("click", () => {
      const expanded = peopleToggle.getAttribute("aria-expanded") === "true";
      peopleToggle.setAttribute("aria-expanded", String(!expanded));
      peopleMenu.classList.toggle("hidden");
      peopleCaret.classList.toggle("rotate-180");
    });
  }

  // (logout modal behavior moved to logout-modal.js)
})();
