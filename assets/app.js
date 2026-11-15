function applyDarkMode() {
    document.documentElement.classList.toggle('dark-mode', window.matchMedia('(prefers-color-scheme: dark)').matches);
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyDarkMode);

document.addEventListener('DOMContentLoaded', function () {
    const celebration = document.querySelector('.claim-celebration');
    const closeButton = document.querySelector('[data-close-celebration]');

    if (celebration instanceof HTMLDialogElement && closeButton instanceof HTMLButtonElement) {
        celebration.showModal();
        closeButton.focus();
        closeButton.addEventListener('click', function () {
            celebration.close();
        });
    }
});



