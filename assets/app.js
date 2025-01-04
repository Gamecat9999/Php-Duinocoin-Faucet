function applyDarkMode() {
    var body = document.body;
    var prefersDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;

    if (prefersDarkMode) {
        body.classList.add("dark-mode");
    } else {
        body.classList.remove("dark-mode");
    }
}

function validateForm() {
    var response = grecaptcha.getResponse();
    if (!response) {
        alert('Dont be a Evil Bot. Please complete the Captcha.');
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', (event) => {
    let lastClickTime = 0;

    document.addEventListener('click', (e) => {
        const currentTime = new Date().getTime();
        if (currentTime - lastClickTime < 1000) {
            e.stopImmediatePropagation();
            e.preventDefault();
        } else {
            lastClickTime = currentTime;
        }
    }, true);
});

        
