function applyDarkMode() {
    document.documentElement.classList.toggle('dark-mode', window.matchMedia('(prefers-color-scheme: dark)').matches);
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyDarkMode);
}



