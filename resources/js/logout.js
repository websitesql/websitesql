/*
 * Website SQL v2.1.0
 * 
 * File: 	Logout
 * Author: 	Alan Tiller
 * Date: 	2024-12-06
 * Version: 2.1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get button with data-action="toggleDarkMode"
    const logoutSelector = document.querySelector('[data-action="wsql-logout"]');

    if (logoutSelector) {
        // Detect when logout button is clicked
        logoutSelector.addEventListener('click', function() {
            // Post to logout route
            fetch(logout_endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // If successful, redirect to login page
                if (data.status === 'success') {
                    WsqlToast.show('Logged out successfully, redirecting...', 'success');
                    window.location.href = public_url;
                } else {
                    // If unsuccessful, show error
                    WsqlToast.show('Failed to logout: ' + data.message, 'danger');
                }
            })
        });

    }
});