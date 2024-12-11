/*
 * Website SQL v2.1.0
 * 
 * File: 	Darkmode
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get button with data-action="toggleDarkMode"
    const darkmodetoggle = document.querySelector('[data-action="toggleDarkMode"]');

    if (darkmodetoggle) {
        // Check if darkmode cookie is set
        if (document.cookie.includes('darkmode=true')) {
            darkmodetoggle.innerHTML = '<i class="fa-regular fa-moon align-top"></i>';
        }

        // Detect when darkmode button is clicked
        darkmodetoggle.addEventListener('click', function() {
            if (darkmodetoggle.innerHTML.includes('fa-sun')) {
                darkmodetoggle.innerHTML = '<i class="fa-regular fa-moon align-top"></i>';
                document.body.classList.add('dark');
                document.cookie = 'darkmode=true;path=/';
            } else {
                darkmodetoggle.innerHTML = '<i class="fa-regular fa-sun align-top"></i>';
                document.body.classList.remove('dark');
                document.cookie = 'darkmode=false;path=/';
            }
        });

    }

    // New Dark Mode Toggle
    const darkModeToggle = document.querySelector('[data-action="wsql-darkmode-toggle"]');
    if (darkModeToggle) {
        // Check if darkmode cookie is set
        if (document.cookie.includes('darkmode=true')) {
            renderDarkModeButton('dark');
        } else {
            renderDarkModeButton('light');
        }

        // Detect when darkmode button is clicked
        darkModeToggle.addEventListener('click', function() {
            if (darkModeToggle.innerHTML.includes('fa-sun')) {
                renderDarkModeButton('light');
                document.body.classList.remove('dark');
                document.cookie = 'darkmode=false;path=/';
            } else {
                renderDarkModeButton('dark');
                document.body.classList.add('dark');
                document.cookie = 'darkmode=true;path=/';
            }
        });

        // Function to toggle dark mode
        function renderDarkModeButton(mode) {
            // Get the button icon
            darkModeToggle.querySelector('i').innerHTML = '';
            darkModeToggle.querySelector('i').classList.remove('fa-sun', 'fa-moon');
            darkModeToggle.querySelector('i').classList.add(mode === 'dark' ? 'fa-sun' : 'fa-moon');

            // Get the button text
            darkModeToggle.querySelector('h1').textContent = mode === 'dark' ? 'Light mode' : 'Dark mode';

        }
    }
});