/*
 * Website SQL v2.1.0
 * 
 * File: 	wsqlToast
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 1.0.3
 */

class WsqlToast {
    static instance = null;
  
    constructor() {
        if (!WsqlToast.instance) {
            this.createToastContainer();
            WsqlToast.instance = this;
        }
        return WsqlToast.instance;
    }
  
    createToastContainer() {
        // Create a container div to hold the toast messages
        this.container = document.createElement('div');
        this.container.id = 'wsql-toast-container';
        this.container.className = 'fixed bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center space-y-4 z-50';
        document.body.appendChild(this.container);
    }
  
    static show(message, type = 'info', duration = 3000) {
        if (!WsqlToast.instance) {
            new WsqlToast();
        }
        WsqlToast.instance._show(message, type, duration);
    }
  
    _show(message, type = 'info', duration = 3000) {
        // Define type-specific styles
        let typeClass = '';
        let iconSVG = '';
        switch (type) {
            case 'success':
                typeClass = 'bg-green-700';
                iconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>';
                break;
            case 'info':
                typeClass = 'bg-blue-700';
                iconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c-13.3 0-24 10.7-24 24V264c0 13.3 10.7 24 24 24s24-10.7 24-24V152c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>';
                break;
            case 'warning':
                typeClass = 'bg-yellow-700';
                iconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M248.4 84.3c1.6-2.7 4.5-4.3 7.6-4.3s6 1.6 7.6 4.3L461.9 410c1.4 2.3 2.1 4.9 2.1 7.5c0 8-6.5 14.5-14.5 14.5H62.5c-8 0-14.5-6.5-14.5-14.5c0-2.7 .7-5.3 2.1-7.5L248.4 84.3zm-41-25L9.1 385c-6 9.8-9.1 21-9.1 32.5C0 452 28 480 62.5 480h387c34.5 0 62.5-28 62.5-62.5c0-11.5-3.2-22.7-9.1-32.5L304.6 59.3C294.3 42.4 275.9 32 256 32s-38.3 10.4-48.6 27.3zM288 368a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm-8-184c0-13.3-10.7-24-24-24s-24 10.7-24 24v96c0 13.3 10.7 24 24 24s24-10.7 24-24V184z"/></svg>';
                break;
            case 'danger':
                typeClass = 'bg-red-700';
                iconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM186.3 136.5c-8.6-10.1-23.7-11.4-33.8-2.8s-11.4 23.7-2.8 33.8L224.6 256l-74.9 88.5c-8.6 10.1-7.3 25.3 2.8 33.8s25.3 7.3 33.8-2.8L256 293.2l69.7 82.3c8.6 10.1 23.7 11.4 33.8 2.8s11.4-23.7 2.8-33.8L287.4 256l74.9-88.5c8.6-10.1 7.3-25.3-2.8-33.8s-25.3-7.3-33.8 2.8L256 218.8l-69.7-82.3z"/></svg>';
                break;
        }
  
        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `${typeClass} text-white font-baloo text-md leading-4 px-6 py-4 rounded shadow opacity-0 transition-opacity duration-500 ease-in-out flex space-x-4 items-center max-w-xl w-auto`;
  
        // Create the icon element
        const iconWrapper = document.createElement('div');
        iconWrapper.innerHTML = iconSVG;
        iconWrapper.className = 'w-5 h-5 fill-white';
  
        // Create the message element
        const messageElement = document.createElement('div');
        messageElement.innerText = message;
  
        // Create a close button
        const closeButton = document.createElement('button');
        closeButton.className = 'w-3 h-3 fill-white';
        closeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M393.4 41.4c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3L269.3 256 438.6 425.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 301.3 54.6 470.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 9.4 86.6C-3.1 74.1-3.1 53.9 9.4 41.4s32.8-12.5 45.3 0L224 210.7 393.4 41.4z"/></svg>';
        closeButton.addEventListener('click', () => {
            this.hideToast(toast);
        });
  
        // Append the icon, message, and close button to the toast
        toast.appendChild(iconWrapper);
        toast.appendChild(messageElement);
        toast.appendChild(closeButton);
  
        // Append toast to the container
        this.container.appendChild(toast);
  
        // Show the toast with an animation (fade in)
        setTimeout(() => {
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');
        }, 1);
  
        // Hide and remove the toast after the duration
        setTimeout(() => {
            this.hideToast(toast);
        }, duration);
    }
  
    hideToast(toast) {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
      
        // Wait for the animation to finish before removing
        setTimeout(() => {
            if (toast.parentElement) {
                this.container.removeChild(toast);
            }
        }, 500);
    }
}
  
// Example usage

// Show a toast
// WsqlToast.show('Hello, this is a success message!', 'success', 3000);
// WsqlToast.show('This is an info message!', 'info', 3000);
// WsqlToast.show('Warning! Something might be wrong.', 'warning', 3000);
// WsqlToast.show('Failure! Something went wrong.', 'danger', 3000);
  
// To trigger it, you can use:
// document.querySelector('button').addEventListener('click', () => WsqlToast.show('This is a button toast!', 'info', 4000));