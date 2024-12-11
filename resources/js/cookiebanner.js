/*
 * Website SQL v2.1.0
 * 
 * File:    Cookie Banner Update
 * Author:  Alan Tiller
 * Date:    2024-12-01
 * Version: 2.1.0
 */

document.addEventListener('DOMContentLoaded', () => {
    // Check if the cookie consent decision has already been made
    const cookieConsent = getCookie('cookieConsent');
    if (cookieConsent === 'accepted') { 
        return; // Exit if the banner has already been interacted with and accepted
    }

    // Check if js variable disableCookieBanner is set to true
    if (typeof disableCookieBanner !== 'undefined' && disableCookieBanner) {
        return; // Exit if the cookie banner should be disabled
    }

    // Create the dark overlay
    const darkOverlay = document.createElement('div');
    darkOverlay.className = 'fixed bottom-0 left-0 h-full w-full z-40 bg-black opacity-60';

    // Create the cookie banner container
    const bannerContainer = document.createElement('div');
    bannerContainer.className = 'fixed bottom-0 left-0 w-full z-50';

    // Create the content wrapper
    const contentWrapper = document.createElement('div');
    contentWrapper.className = 'relative w-full max-w-5xl mx-auto px-8 pb-8 text-gray-800 dark:text-white';

    // Create the banner content
    const bannerContent = document.createElement('div');
    bannerContent.className = 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-neutral-800 text-gray-700 dark:text-white p-4 rounded-xl shadow-md font-baloo text-sm flex justify-between items-center gap-4 flex-wrap md:flex-nowrap';

    // Create the banner text
    const bannerText = document.createElement('p');
    bannerText.textContent = 'This application uses cookies as they are required for its function. For more information, please contact the site owner for their cookie policy. By clicking "Accept", you agree to the use of cookies for 365 days. By clicking "Decline", you will be redirected away from this site and a cookie will be stored for 30 days to remember your rejection.';

    // Create the button container
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex gap-3';

    // Create the Accept button
    const acceptButton = document.createElement('button');
    acceptButton.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer';
    acceptButton.innerHTML = '<i class="fas fa-check"></i><span>Accept</span>';
    acceptButton.addEventListener('click', () => {
        setCookie('cookieConsent', 'accepted', 365);
        document.body.removeChild(darkOverlay);
        document.body.removeChild(bannerContainer);
    });

    // Create the Deny button
    const denyButton = document.createElement('button');
    denyButton.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer';
    denyButton.innerHTML = '<i class="fas fa-xmark"></i><span>Decline</span>';
    denyButton.addEventListener('click', () => {
        setCookie('cookieConsent', 'denied', 30);
        window.location.href = 'https://www.google.com'; // Redirect to Google
    });

    // Append buttons to the container
    buttonContainer.appendChild(acceptButton);
    buttonContainer.appendChild(denyButton);

    // Append text and buttons to the banner content
    bannerContent.appendChild(bannerText);
    bannerContent.appendChild(buttonContainer);

    // Append banner content to the content wrapper
    contentWrapper.appendChild(bannerContent);

    // Append content wrapper to the banner container
    bannerContainer.appendChild(contentWrapper);

    // Append the dark overlay and the banner container to the body
    document.body.appendChild(darkOverlay);
    document.body.appendChild(bannerContainer);

    // Helper function to set a cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Helper function to get a cookie
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});