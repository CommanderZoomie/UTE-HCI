// Ensure the DOM is fully loaded before running the script
document.addEventListener('DOMContentLoaded', function() {
    // Get references to the theme toggle button and the body element
    const themeToggleBtn = document.getElementById('toggle-theme');
    const body = document.body;

    // Function to apply the selected theme and save it to localStorage
    function applyTheme(theme) {
        if (theme === 'light') {
            // Add 'light-mode' class to the body for light theme styles
            body.classList.add('light-mode');
            // Update the toggle button's text to show the sun icon (for light mode)
            themeToggleBtn.innerHTML = '☀️';
            // Save 'light' as the preferred theme in localStorage
            localStorage.setItem('theme', 'light');
        } else {
            // Remove 'light-mode' class from the body for dark theme styles
            body.classList.remove('light-mode');
            // Update the toggle button's text to show the moon icon (for dark mode)
            themeToggleBtn.innerHTML = '🌙';
            // Save 'dark' as the preferred theme in localStorage
            localStorage.setItem('theme', 'dark');
        }
    }

    // Initialize theme on page load
    // 1. Check if a theme preference is saved in localStorage
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme) {
        // If a theme is saved, apply it
        applyTheme(savedTheme);
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
        // 2. If no theme is saved, check the user's system preference
        // If the system prefers light mode, apply light theme
        applyTheme('light');
    } else {
        // 3. If no saved theme and no system preference for light, default to dark theme
        applyTheme('dark');
    }

    // Add an event listener to the theme toggle button
    if (themeToggleBtn) { // Check if the button exists before adding listener
        themeToggleBtn.addEventListener('click', function() {
            // If the body currently has 'light-mode', switch to dark
            if (body.classList.contains('light-mode')) {
                applyTheme('dark');
            } else {
                // Otherwise, switch to light mode
                applyTheme('light');
            }
        });
    }
});
