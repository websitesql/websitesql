/*
 * Website SQL v2.1.0
 * 
 * File: 	Account
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener("DOMContentLoaded", function(event) {
    // Settings Users table
    if (document.querySelector('[data-page="account"]')) {
        const firstnameInput = document.querySelector('[data-input="wsql-account-firstname"]');
        const lastnameInput = document.querySelector('[data-input="wsql-account-lastname"]');
        const emailInput = document.querySelector('[data-input="wsql-account-email"]');
        const avatarInput = document.querySelector('[data-input="wsql-account-avatar"]');
        const saveButton = document.querySelector('[data-button="wsql-account-save"]');

        // Load the current user data
        async function loadUserData() {
            const response = await fetch(users_me_endpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            // Render the input fields
            generateInputField(firstnameInput, data.firstname);
            generateInputField(lastnameInput, data.lastname);
            generateInputField(emailInput, data.email);
            generateAvatarPreview(avatarInput, data.email);
            generateSaveButton(saveButton);
        }

        // Generate an input field
        function generateInputField(nameElement, fieldValue) {
            const inputField = document.createElement('input');
            inputField.type = 'text';
            inputField.className = 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent';
    
            // Set application name if available
            if (fieldValue) {
                inputField.value = fieldValue;
            }

            nameElement.innerHTML = '';
            nameElement.appendChild(inputField);
        }

        // Generate avatar preview
        async function generateAvatarPreview(avatarInput, fieldValue) {
            // Trim whitespace and convert to lowercase
            const sanitizedValue = fieldValue.trim().toLowerCase();
        
            // Generate SHA256 hash
            const hashBuffer = await crypto.subtle.digest(
                'SHA-256',
                new TextEncoder().encode(sanitizedValue) // Convert string to ArrayBuffer
            );
            const hashArray = Array.from(new Uint8Array(hashBuffer)); // Convert buffer to byte array
            const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join(''); // Convert bytes to hex

            const avatarPreviewContainer = document.createElement('div');
            avatarPreviewContainer.className = 'w-40 md:w-full h-40 md:h-full relative overflow-hidden shadow';

            const avatarPreview = document.createElement('img');
            avatarPreview.alt = 'Avatar';
            avatarPreview.src = `https://gravatar.com/avatar/${hashHex}?size=512&d=mp`;
            avatarPreview.className = `w-full h-full`;

            const avatarChangeButton = document.createElement('button');
            avatarChangeButton.type = 'button';
            avatarChangeButton.className = 'absolute bottom-0 left-0 w-full h-12 bg-black bg-opacity-50 flex items-center justify-center gap-3 hover:bg-opacity-75 transition-colors';
            avatarChangeButton.addEventListener('click', function() {
                window.open(`https://gravatar.com/profile`, '_blank');
            });

            const avatarChangeButtonSpan = document.createElement('span');
            avatarChangeButtonSpan.className = 'text-white text-lg';
            avatarChangeButtonSpan.textContent = 'Change Avatar';

            const avatarChangeButtonI = document.createElement('i');
            avatarChangeButtonI.className = 'fa-regular fa-image text-white text-lg';

            avatarChangeButton.appendChild(avatarChangeButtonI);
            avatarChangeButton.appendChild(avatarChangeButtonSpan);

            avatarPreviewContainer.appendChild(avatarPreview);
            avatarPreviewContainer.appendChild(avatarChangeButton);

            avatarInput.innerHTML = '';
            avatarInput.appendChild(avatarPreviewContainer);
        }

        // Generate save button
        async function generateSaveButton(saveButton) {
            const saveButtonElement = document.createElement('button');
            saveButtonElement.type = 'button';
            saveButtonElement.className = wsql_standard_button;
            saveButtonElement.addEventListener('click', function() {
                saveUserData(saveButton);
            });

            const saveButtonI = document.createElement('i');
            saveButtonI.className = 'fa-regular fa-save';

            const saveButtonSpan = document.createElement('span');
            saveButtonSpan.textContent = 'Save';

            saveButtonElement.appendChild(saveButtonI);
            saveButtonElement.appendChild(saveButtonSpan);

            saveButton.innerHTML = '';
            saveButton.appendChild(saveButtonElement);
        }

        // Save the user data
        async function saveUserData(saveButton) {
            // Disable the save button
            saveButton.querySelector('button').disabled = true;

            const firstnameValue = firstnameInput.querySelector('input').value;
            const lastnameValue = lastnameInput.querySelector('input').value;
            const emailValue = emailInput.querySelector('input').value;

            const response = await fetch(users_me_endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    firstname: firstnameValue,
                    lastname: lastnameValue,
                    email: emailValue
                })
            });

            if (response.ok) {
                WsqlToast.show('Your account was updated successfully', 'success', 3000);
            } else {
                WsqlToast.show('An error occurred while updating your account', 'danger', 3000);
            }

            // Enable the save button
            saveButton.querySelector('button').disabled = false;
        }

        // Load the current user data
        loadUserData();
    }


    // Account Update Password
    if (document.querySelector('[data-component="wsql-account-updatePassword"]')) {
        function wsqlAccountUpdatePasswordLoad() {
            // Create the main wrapper div
            const wrapper = document.createElement('div');
            wrapper.className = 'grid grid-cols-1 gap-5';

            // Create and append the current password input field
            const currentPasswordField = wsqlAccountUpdatePasswordCreateInputField('Current password');
            wrapper.appendChild(currentPasswordField);

            // Create and append the new password input field
            const newPasswordField = wsqlAccountUpdatePasswordCreateInputField('New password');
            wrapper.appendChild(newPasswordField);

            // Create and append the confirm new password input field
            const confirmPasswordField = wsqlAccountUpdatePasswordCreateInputField('Confirm new password');
            wrapper.appendChild(confirmPasswordField);

            // Create the save button wrapper div
            const buttonWrapper = document.createElement('div');

            // Create the button
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-3 rounded-xl shadow-sm transition-all duration-100 cursor-pointer disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50';
            button.addEventListener('click', function() {
                // Check if the new password and confirm password match
                const currentPassword = currentPasswordField.querySelector('input').value;
                const newPassword = newPasswordField.querySelector('input').value;
                const confirmPassword = confirmPasswordField.querySelector('input').value;

                // Check if all fields are filled
                if (!currentPassword || !newPassword || !confirmPassword) {
                    // Display an error message
                    WsqlToast.show('Please fill in all fields', 'danger', 3000);
                    return;
                }

                // Check if the current password is the same as the new password
                if (currentPassword === newPassword) {
                    // Display an error message
                    WsqlToast.show('The new password cannot be the same as the current password', 'warning', 3000);
                    return;
                }

                // Check if the new password and confirm password match
                if (newPassword !== confirmPassword) {
                    // Display an error message
                    WsqlToast.show('The new password and confirm password do not match', 'danger', 3000);
                    return;
                }

                // Update the user password
                wsqlAccountUpdatePasswordUpdate(currentPassword, newPassword);
            });

            // Create the icon
            const icon = document.createElement('i');
            icon.className = 'fa-regular fa-save';
            button.appendChild(icon);

            // Create the span with the "Save" text
            const span = document.createElement('span');
            span.textContent = 'Update';
            button.appendChild(span);

            // Append the button to the button wrapper
            buttonWrapper.appendChild(button);

            // Append the button wrapper to the main wrapper
            wrapper.appendChild(buttonWrapper);

            // Append the entire wrapper
            document.querySelector('[data-component="wsql-account-updatePassword"]').innerHTML = '';
            document.querySelector('[data-component="wsql-account-updatePassword"]').appendChild(wrapper);
        }

        function wsqlAccountUpdatePasswordCreateInputField(labelText) {
            const div = document.createElement('div');

            const label = document.createElement('p');
            label.className = 'block text-base font-medium text-gray-700 dark:text-white transition-all duration-300';
            label.textContent = labelText;
            div.appendChild(label);

            const input = document.createElement('input');
            input.type = 'password';
            input.className = 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent';
            div.appendChild(input);

            return div;
        }

        async function wsqlAccountUpdatePasswordUpdate(currentPassword, newPassword) {
            // Disable the button
            document.querySelector('[data-component="wsql-account-updatePassword"] button').disabled = true;

            try {
                // Fetch the update password endpoint
                const response = await fetch(users_me_password_reset_endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        currentPassword: currentPassword,
                        newPassword: newPassword,
                    }),
                });

                if (!response.ok) {
                    const result = await response.json();
                    throw new Error(result.message);
                }

                // Show toast message
                WsqlToast.show('Password updated successfully', 'success');
            } catch (error) {
                WsqlToast.show(error.message, 'danger');
            }

            // Enable the button
            document.querySelector('[data-component="wsql-account-updatePassword"] button').disabled = false;

        }

        wsqlAccountUpdatePasswordLoad();
    }
});