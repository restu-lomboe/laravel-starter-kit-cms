import "./dashboard";
import Swal from "sweetalert2";

window.Swal = Swal;

// Listen for Livewire page transitions
document.addEventListener("livewire:navigating", () => {
    const isDark = localStorage.getItem("anchor-hr-theme") === "dark";
    if (isDark) {
        document.documentElement.classList.add("dark");
    }
});

document.addEventListener("livewire:navigated", () => {
    const isDark = localStorage.getItem("anchor-hr-theme") === "dark";
    if (isDark) {
        document.documentElement.classList.add("dark");
    }
});
