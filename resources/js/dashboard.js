/**
 * Dashboard UI Interactions
 * Handles theme toggling, sidebar collapse, mobile sidebar drawer, and profile menu
 */

// ==================== THEME TOGGLE ====================
function setThemeIcons(isDark) {
    const iconSun = document.getElementById("icon-sun");
    const iconMoon = document.getElementById("icon-moon");

    if (iconSun) iconSun.classList.toggle("hidden", isDark);
    if (iconMoon) iconMoon.classList.toggle("hidden", !isDark);
}

function toggleTheme() {
    const isDark = document.documentElement.classList.toggle("dark");
    try {
        localStorage.setItem("anchor-hr-theme", isDark ? "dark" : "light");
    } catch (e) {
        console.error("Failed to save theme preference:", e);
    }
    setThemeIcons(isDark);
}

function bindActionButtons() {
    document.querySelectorAll("[data-action]").forEach((element) => {
        if (element.dataset.bound === "true") {
            return;
        }

        const actionName = element.dataset.action;
        const handlerMap = {
            toggleTheme,
            toggleSidebar,
            toggleMobileSidebar,
            toggleProfileMenu,
        };

        if (typeof handlerMap[actionName] === "function") {
            element.addEventListener("click", handlerMap[actionName]);
            element.dataset.bound = "true";
        }
    });
}

// Initialize theme on page load
function initTheme() {
    try {
        const savedTheme = localStorage.getItem("anchor-hr-theme");
        if (savedTheme === "dark") {
            document.documentElement.classList.add("dark");
        }
    } catch (e) {
        console.error("Failed to load theme preference:", e);
    }
    setThemeIcons(document.documentElement.classList.contains("dark"));
}

// ==================== DESKTOP SIDEBAR COLLAPSE ====================
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    if (!sidebar) return;

    const collapsed = sidebar.classList.toggle("collapsed");
    const iconToggle = document.getElementById("sidebar-collapse-icon");

    try {
        localStorage.setItem(
            "anchor-hr-sidebar",
            collapsed ? "collapsed" : "expanded",
        );

        if (iconToggle) {
            if (iconToggle.classList.contains("fa-angle-left")) {
                iconToggle.classList.remove("fa-angle-left");
                iconToggle.classList.add("fa-angle-right");
            } else {
                iconToggle.classList.remove("fa-angle-right");
                iconToggle.classList.add("fa-angle-left");
            }
        }
    } catch (e) {
        console.error("Failed to save sidebar preference:", e);
    }
}

// Initialize sidebar state on page load
function initSidebar() {
    try {
        if (localStorage.getItem("anchor-hr-sidebar") === "collapsed") {
            const sidebar = document.getElementById("sidebar");
            if (sidebar) {
                sidebar.classList.add("collapsed");
                const iconToggle = document.getElementById(
                    "sidebar-collapse-icon",
                );
                if (iconToggle) {
                    iconToggle.classList.remove("fa-angle-left");
                    iconToggle.classList.add("fa-angle-right");
                }
            }
        }
    } catch (e) {
        console.error("Failed to load sidebar preference:", e);
    }
}

// ==================== MOBILE SIDEBAR DRAWER ====================
function toggleMobileSidebar() {
    const mobileSidebar = document.getElementById("mobile-sidebar");
    const overlay = document.getElementById("mobile-sidebar-overlay");

    if (mobileSidebar) {
        mobileSidebar.classList.toggle("hidden");
        mobileSidebar.classList.toggle("flex");
    }
    if (overlay) {
        overlay.classList.toggle("hidden");
    }
}

// ==================== PROFILE MENU ====================
function toggleProfileMenu() {
    const menu = document.getElementById("profile-menu");
    if (menu) {
        menu.classList.toggle("hidden");
    }
}

// Close profile menu when clicking outside
function initProfileMenu() {
    document.addEventListener("click", function (e) {
        const menu = document.getElementById("profile-menu");
        const trigger = document.getElementById("profile-trigger");

        if (menu && trigger) {
            if (!menu.contains(e.target) && !trigger.contains(e.target)) {
                menu.classList.add("hidden");
            }
        }
    });
}

// ==================== INITIALIZATION ====================
// Initialize all dashboard functionality when DOM is ready
function initDashboard() {
    initTheme();
    initSidebar();
    initProfileMenu();
    bindActionButtons();
}

if (typeof window !== "undefined") {
    const jsApi = (window.$js = window.$js || {});
    jsApi.toggleTheme = toggleTheme;
    jsApi.toggleSidebar = toggleSidebar;
    jsApi.toggleMobileSidebar = toggleMobileSidebar;
    jsApi.toggleProfileMenu = toggleProfileMenu;
    jsApi.initDashboard = initDashboard;
    globalThis.$js = jsApi;
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDashboard);
} else {
    initDashboard();
}

document.addEventListener("livewire:navigated", () => {
    initTheme();
    bindActionButtons();
});

if (typeof window !== "undefined") {
    window.addEventListener("pageshow", () => {
        initTheme();
        bindActionButtons();
    });
}
