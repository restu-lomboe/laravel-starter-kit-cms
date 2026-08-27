import "./dashboard";
import Swal from "sweetalert2";
import { Passkeys } from "@laravel/passkeys";

window.Swal = Swal;
window.Passkeys = Passkeys;

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
