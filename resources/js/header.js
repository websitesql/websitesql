/*
 * Website SQL v2.1.0
 * 
 * File: 	Header
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    headerElements = {};
    headerFunctions = {};

    /*
     * This function opens the header
     */
    headerFunctions.open = function() {
        document.querySelector('[data-component="wsql-header"]').classList.remove('-left-full');
        document.querySelector('[data-component="wsql-header"]').classList.add('left-0');
    }

    /*
     * This function closes the header
     */
    headerFunctions.close = function() {
        document.querySelector('[data-component="wsql-header"]').classList.remove('left-0');
        document.querySelector('[data-component="wsql-header"]').classList.add('-left-full');
    }

    // If button exists, add event listener to open the header
    if (document.querySelector('[data-action="wsql-header-open"]')) {
        document.querySelector('[data-action="wsql-header-open"]').addEventListener('click', function() { 
            headerFunctions.open();
        });
    }

    // If button exists, add event listener to close the header
    if (document.querySelector('[data-action="wsql-header-close"]')) {
        document.querySelector('[data-action="wsql-header-close"]').addEventListener('click', function() {
            headerFunctions.close();
        });
    }

    // If dark mode toggle exists, add event listener to toggle dark mode
    if (document.querySelector('[data-action="wsql-darkmode-toggle"]')) {
        document.querySelector('[data-action="wsql-darkmode-toggle"]').addEventListener('click', function() {
            headerFunctions.close();
        });
    }

    // If the logout button exists, add event listener to logout
    if (document.querySelector('[data-action="wsql-logout"]')) {
        document.querySelector('[data-action="wsql-logout"]').addEventListener('click', function() {
            headerFunctions.close();
        });
    }

    // Any A tags in the header should close the header
    document.querySelectorAll('[data-component="wsql-header"] a').forEach(function(element) {
        element.addEventListener('click', function() {
            headerFunctions.close();
        });
    });

});