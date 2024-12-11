/*
 * Website SQL v2.1.0
 * 
 * File: 	Settings
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener("DOMContentLoaded", function(event) {
    // Check if all three elements exist before running any of the code
    const brandingNameElement = document.getElementById('wsql-branding-name');
    const brandingLogoLightElement = document.getElementById('wsql-branding-logo-light');
    const brandingLogoDarkElement = document.getElementById('wsql-branding-logo-dark');
    
    if (!(brandingNameElement && brandingLogoLightElement && brandingLogoDarkElement)) {
        return;
    }

    /*
     * Fetch branding data from the API
     *
     * @return void
     */
    function fetchSettings() {
        // Function to fetch branding data
        fetch('/api/settings/branding')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched branding data:', data);

            // Now brandingData is available, proceed with populating elements
            if (brandingLogoLightElement) {
                brandingLogoLightElement.innerHTML = '';
            
                if (data.data.logo.light) {
                    renderLogo(data.data.logo.light, brandingLogoLightElement, 'light');
                } else {
                    renderUploadForm(brandingLogoLightElement, 'light');
                }
            }            

            
            if (brandingNameElement) {
                renderApplicationName(brandingNameElement, data.data.application_name);
            }

            if (brandingLogoDarkElement) {
                brandingLogoDarkElement.innerHTML = '';
            
                if (data.data.logo.dark) {
                    renderLogo(data.data.logo.dark, brandingLogoDarkElement, 'dark');
                } else {
                    renderUploadForm(brandingLogoDarkElement, 'dark');
                }
            }
        })
        .catch(error => {
            console.error('Error fetching branding data:', error);
        });
    }

    /*
     * Render logo
     *
     * @param {Object|null} logoData
     * @param {Element} logoElement
     * @param {String} version - 'light' or 'dark'
     * @return void
     */
    function renderLogo(logoData, logoElement, version) {
        // Create the preview container div
        const previewDiv = document.createElement('div');
        previewDiv.className = `flex rounded-lg shadow border justify-center p-4 mt-3 ${version === 'dark' ? 'bg-gray-700 border-gray-900' : 'bg-white border-gray-300'}`;

        const logoImage = document.createElement('img');
        logoImage.alt = `Application Logo (${version}-mode)`;
        logoImage.className = 'h-14';

        switch (logoData.type) {
            case 'image':
                logoImage.src = logoData.data.url;
                break;
            case 'media':
                logoImage.src = public_url + logoData.data.url;
                break;   
        }

        previewDiv.appendChild(logoImage);

        // Create the remove button container div
        const removeDiv = document.createElement('div');
        removeDiv.id = 'wsql-branding-logo-dark-remove';
        removeDiv.className = 'pt-3';

        // Create the remove button
        const removeButton = document.createElement('button');
        removeButton.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer';
        removeButton.id = `wsql-branding-logo-${version}-remove`;
        removeButton.textContent = 'Remove logo';

        removeButton.addEventListener('click', () => handleLogoRemoval(version));

        removeDiv.appendChild(removeButton);

        // Append preview and remove button elements to logoElement
        logoElement.appendChild(previewDiv);
        logoElement.appendChild(removeDiv);
    }


    /*
     * Render upload form for logo
     *
     * @param {Element} logoElement
     * @param {String} version - 'light' or 'dark'
     * @return void
     */
    function renderUploadForm(logoElement, version) {
        // Create the form element
        const formElement = document.createElement('form');
        formElement.id = `wsql-branding-logo-${version}`;

        // Create the div container for input and buttons
        const inputContainerDiv = document.createElement('div');
        inputContainerDiv.className = 'flex gap-3 pt-3';

        // Create the input element for file selection
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.id = `wsql-branding-logo-${version}-file`;
        fileInput.className = 'hidden';
        fileInput.accept = 'image/*';

        // Create the label for file input
        const fileLabel = document.createElement('label');
        fileLabel.htmlFor = `wsql-branding-logo-${version}-file`;
        fileLabel.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer';
        const fileLabelIcon = document.createElement('i');
        fileLabelIcon.className = 'fas fa-image';
        const fileLabelSpan = document.createElement('span');
        fileLabelSpan.textContent = 'Select image';

        fileLabel.appendChild(fileLabelIcon);
        fileLabel.appendChild(fileLabelSpan);

        // Create the upload button
        const uploadButton = document.createElement('button');
        uploadButton.type = 'submit';
        uploadButton.className = 'flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer';
        const uploadButtonIcon = document.createElement('i');
        uploadButtonIcon.className = 'fas fa-upload';
        const uploadButtonSpan = document.createElement('span');
        uploadButtonSpan.textContent = 'Upload';

        uploadButton.appendChild(uploadButtonIcon);
        uploadButton.appendChild(uploadButtonSpan);

        // Append input, label, and button to the input container
        inputContainerDiv.appendChild(fileInput);
        inputContainerDiv.appendChild(fileLabel);
        inputContainerDiv.appendChild(uploadButton);

        // Create the response div
        const responseDiv = document.createElement('div');
        responseDiv.id = `wsql-branding-logo-${version}-response`;
        responseDiv.className = 'mt-3';

        // Append the input container and response div to the form
        formElement.appendChild(inputContainerDiv);
        formElement.appendChild(responseDiv);

        // Append the form to logoElement
        logoElement.appendChild(formElement);

        // Add event listener to handle form submission
        formElement.addEventListener('submit', function (e) {
            e.preventDefault();
            handleLogoFormSubmission(fileInput, version);
        });
    }

    /*
     * Render the application name input field and handle changes
     *
     * @param {String|null} applicationName
     * @return void
     */
    function renderApplicationName(nameElement, applicationName) {
        nameElement.innerHTML = '';

        const applicationNameInput = document.createElement('input');
        applicationNameInput.type = 'text';
        applicationNameInput.name = 'branding_application_name';
        applicationNameInput.className = 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent';

        // Set application name if available
        if (applicationName) {
            applicationNameInput.value = applicationName;
        }

        nameElement.appendChild(applicationNameInput);

        // Add debounced event listener to handle changes to the application name
        let debounceTimeout;
        applicationNameInput.addEventListener('input', function () {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                handleApplicationNameUpdate(applicationNameInput.value);
            }, 500);
        });
    }

    /*
     * Handle the logo form submission
     *
     * @param {Element} logoInput
     * @param {String} version
     * @return void
     */
    async function handleLogoFormSubmission(logoInput, version) {
        if (!logoInput.files.length) {
            WsqlToast.show('Please select an image to upload', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('file', logoInput.files[0]);

        try {
            const response = await fetch('/api/media/upload', {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(`Error uploading image: ${result.message}`);
            }

            const action = version === 'light' ? 'add_lm_logo' : 'add_dm_logo';

            const responseBranding = await fetch('/api/settings/branding', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'action': action,
                    'data': {
                        type: 'media',
                        id: result.file.id,
                    },
                }),
            });

            if (!responseBranding.ok) {
                const resultBranding = await responseBranding.json();
                
                // Show toast message
                throw new Error(`Error updating branding: ${resultBranding.message}`);
            }

            // Show toast message
            WsqlToast.show('Logo uploaded successfully', 'success');

            // Reload settings after successful upload
            fetchSettings();
        } catch (error) {
            WsqlToast.show(error.message, 'danger');
        }
    }

    /*
     * Handle the logo removal
     *
     * @param {String} version
     * @return void
     */
    async function handleLogoRemoval(version) {
        const action = version === 'light' ? 'remove_lm_logo' : 'remove_dm_logo';

        try {
            const response = await fetch('/api/settings/branding', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'action': action,
                }),
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(`Error removing logo: ${result.message}`);
            }

            // Show toast message
            WsqlToast.show('Logo removed successfully', 'success');

            // Reload settings after successful removal
            fetchSettings();
        } catch (error) {
            WsqlToast.show(error.message, 'danger');
        }
    }

    /*
     * Handle updating the application name
     *
     * @param {String} newName
     * @return void
     */
    async function handleApplicationNameUpdate(newName) {
        try {
            const response = await fetch('/api/settings/branding', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'action': 'update_name',
                    'data': {
                        'application_name': newName,
                    },
                }),
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(`Error updating application name: ${result.message}`);
            }

            // Show toast message
            WsqlToast.show('Application name updated successfully', 'success');
        } catch (error) {
            WsqlToast.show(error.message, 'danger');
        }
    }

    // Fetch settings when the page loads
    fetchSettings();
});
