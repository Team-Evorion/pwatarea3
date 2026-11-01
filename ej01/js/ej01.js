"use strict";

const dropdowns = [...document.querySelectorAll("[data-dropdown]")];
const mobileToggle = document.querySelector(".mobile-toggle");
const primaryNav = document.querySelector("#primary-nav");
const mobileQuery = window.matchMedia("(max-width: 860px)");
const selectableLinks = [
  ...document.querySelectorAll(".menu-card, .resource-link, .nav-link"),
];

function getParts(dropdown) {
  return {
    trigger: dropdown.querySelector(".nav-trigger"),
    panel: dropdown.querySelector("[data-panel]"),
    items: [...dropdown.querySelectorAll("[data-panel] a")],
  };
}

function clearActiveSelection() {
  selectableLinks.forEach((link) => {
    link.classList.remove("is-active");
    link.removeAttribute("aria-current");
  });

  dropdowns.forEach((dropdown) => {
    getParts(dropdown).trigger.classList.remove("has-active-item");
  });
}

function syncActiveSelection(hash = window.location.hash) {
  clearActiveSelection();
  if (!hash) return;

  const matchingLinks = selectableLinks.filter(
    (link) => link.getAttribute("href") === hash,
  );

  matchingLinks.forEach((link) => {
    link.classList.add("is-active");
    link.setAttribute("aria-current", "location");

    const parentDropdown = link.closest("[data-dropdown]");
    if (parentDropdown) {
      getParts(parentDropdown).trigger.classList.add("has-active-item");
    }
  });
}

function setDropdown(dropdown, isOpen, focusTarget = null) {
  const { trigger, panel, items } = getParts(dropdown);
  trigger.setAttribute("aria-expanded", String(isOpen));
  panel.hidden = !isOpen;

  if (isOpen && focusTarget === "first") items[0]?.focus();
  if (isOpen && focusTarget === "last") items.at(-1)?.focus();
}

function closeDropdowns(exception = null) {
  dropdowns.forEach((dropdown) => {
    if (dropdown !== exception) setDropdown(dropdown, false);
  });
}

function moveFocus(items, currentItem, direction) {
  const currentIndex = items.indexOf(currentItem);
  const nextIndex = (currentIndex + direction + items.length) % items.length;
  items[nextIndex]?.focus();
}

dropdowns.forEach((dropdown) => {
  const { trigger, panel, items } = getParts(dropdown);

  trigger.addEventListener("click", () => {
    const shouldOpen = trigger.getAttribute("aria-expanded") !== "true";
    closeDropdowns(dropdown);
    setDropdown(dropdown, shouldOpen);
  });

  trigger.addEventListener("keydown", (event) => {
    if (event.key === "ArrowDown" || event.key === "ArrowUp") {
      event.preventDefault();
      closeDropdowns(dropdown);
      setDropdown(dropdown, true, event.key === "ArrowDown" ? "first" : "last");
    }
  });

  panel.addEventListener("keydown", (event) => {
    const currentItem = event.target.closest("a");
    if (!currentItem) return;

    if (event.key === "ArrowDown") {
      event.preventDefault();
      moveFocus(items, currentItem, 1);
    }

    if (event.key === "ArrowUp") {
      event.preventDefault();
      moveFocus(items, currentItem, -1);
    }

    if (event.key === "Home") {
      event.preventDefault();
      items[0]?.focus();
    }

    if (event.key === "End") {
      event.preventDefault();
      items.at(-1)?.focus();
    }

    if (event.key === "Escape") {
      event.preventDefault();
      setDropdown(dropdown, false);
      trigger.focus();
    }
  });

  panel.addEventListener("click", (event) => {
    if (event.target.closest("a")) setDropdown(dropdown, false);
  });
});

selectableLinks.forEach((link) => {
  link.addEventListener("click", () => {
    syncActiveSelection(link.getAttribute("href"));
  });
});

document.addEventListener("click", (event) => {
  if (!event.target.closest("[data-dropdown]")) closeDropdowns();
});

function openMobileNavigation() {
  primaryNav.classList.add("is-open");
  mobileToggle.setAttribute("aria-expanded", "true");
  mobileToggle.querySelector(".sr-only").textContent = "Cerrar menú principal";
  document.body.classList.add("nav-locked");
}

function closeMobileNavigation() {
  primaryNav.classList.remove("is-open");
  mobileToggle.setAttribute("aria-expanded", "false");
  mobileToggle.querySelector(".sr-only").textContent = "Abrir menú principal";
  document.body.classList.remove("nav-locked");
  closeDropdowns();
}

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") return;

  const openDropdown = dropdowns.find(
    (dropdown) => getParts(dropdown).trigger.getAttribute("aria-expanded") === "true",
  );

  if (openDropdown) {
    const { trigger } = getParts(openDropdown);
    setDropdown(openDropdown, false);
    trigger.focus();
  } else if (primaryNav.classList.contains("is-open")) {
    closeMobileNavigation();
    mobileToggle.focus();
  }
});

mobileToggle.addEventListener("click", () => {
  if (primaryNav.classList.contains("is-open")) {
    closeMobileNavigation();
  } else {
    openMobileNavigation();
  }
});

primaryNav.addEventListener("click", (event) => {
  const link = event.target.closest("a");
  if (!mobileQuery.matches || !link) return;

  const hash = link.getAttribute("href");
  const target = hash?.startsWith("#") ? document.querySelector(hash) : null;

  closeMobileNavigation();

  if (target) {
    event.preventDefault();
    window.location.hash = hash;
    syncActiveSelection(hash);
    window.requestAnimationFrame(() => {
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }
});

mobileQuery.addEventListener("change", (event) => {
  if (!event.matches) closeMobileNavigation();
});

window.addEventListener("hashchange", () => syncActiveSelection());

syncActiveSelection();
