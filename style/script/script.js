document.addEventListener("DOMContentLoaded", function () {
    const modeToggleInput = document.querySelector('.mode-toggle .input');
    const body = document.body;

    // Function to update dark mode state
    function updateDarkMode(isDarkMode) {
        if (isDarkMode) {
            body.classList.add('dark-mode');
            localStorage.setItem('dark-mode', 'true');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('dark-mode', 'false');
        }
    }

    // Check for saved user preference
    const savedMode = localStorage.getItem('dark-mode');
    if (savedMode === 'true') {
        modeToggleInput.checked = true;
        updateDarkMode(true);
    }

    modeToggleInput.addEventListener('change', function () {
        const isDarkMode = modeToggleInput.checked;
        updateDarkMode(isDarkMode);
    });
});
