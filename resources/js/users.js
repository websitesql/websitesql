/*
 * Website SQL v2.1.0
 * 
 * File: 	Users
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener("DOMContentLoaded", function(event) {
    const userElements = {};
    const userFunctions = {};

    /*
     * This function is used to load the users table
     */
    if (document.querySelector('[data-table="wsql-users"]')) {
        // Usage
        userElements.usersTable = new WsqlTable(document.querySelector('[data-table="wsql-users"]'));

        userElements.usersTable.setColumns([
            {
                key: null,
                label: "Status",
                callback: (value, row) => {
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${row.approved === 0 ? 'bg-yellow-100 text-yellow-800' : row.locked === 1 ? 'bg-red-100 text-red-800' : row.email_verified === 0 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'}`;
                    span.textContent = row.approved === 0 ? 'Unapproved' : row.locked === 1 ? 'Locked' : row.email_verified === 0 ? 'Email unverified' : 'Active';
                    return span;
                }
            },
            {
                key: null,
                label: "User",
                callback: (value, row) => {
                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'block leading-5 mt-1';
                    nameSpan.textContent = row.firstname + ' ' + row.lastname;

                    const descriptionSpan = document.createElement('span');
                    descriptionSpan.className = 'text-gray-400 font-light text-sm whitespace-break-spaces';
                    descriptionSpan.textContent = row.email;

                    const div = document.createElement('div');
                    div.appendChild(nameSpan);
                    div.appendChild(descriptionSpan);

                    return div;
                }
            },
            {
                key: null,
                label: "Role",
                callback: (value, row) => {
                    console.log(value);
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${row.role ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}`;
                    span.textContent = row.role ? `${row.role.name}` : 'No Role';
                    return span;
                }
            },
            {
                key: "created_at",
                label: "Created at"
            },
        ]);

        userElements.usersTable.setActions([
            {
                name: "Edit",
                icon: "fa-solid fa-edit",
                callback: (row) => {
                    window.location.href = `/admin/users/${row.uuid}`;
                },
            },
            {
                name: "Delete",
                icon: "fa-solid fa-trash-can",
                callback: (row) => {
                    userFunctions.deleteUserOpenModel(row);
                },
            },
        ]);

        userElements.usersTable.setSource({
            apiEndpoint: users_endpoint,
            dataSelector: "data",
        });

        userElements.usersTable.setButtons([
            {
                name: "Create user",
                icon: "fa-solid fa-user-plus",
                callback: () => {
                    userFunctions.createUserOpenModel();
                }
            },
        ]);

        userElements.usersTable.setName("users");

        userElements.usersTable.render();
    }

    /*
     * This function is used to open the create user modal.
     */
    userFunctions.createUserOpenModel = async function() {
        // Modal container
        const modelContainer = document.createElement('div');

        // Create user text
        const wsqlCreateUserText = document.createElement('div');
        wsqlCreateUserText.className = 'mb-8';

        // Modal Title
        const wsqlCreateUserTextTitle = document.createElement('h1');
        wsqlCreateUserTextTitle.className = 'text-xl text-gray-700 dark:text-white font-baloo font-semibold';
        wsqlCreateUserTextTitle.textContent = 'Create User';
        wsqlCreateUserText.appendChild(wsqlCreateUserTextTitle);

        // Modal Paragraph
        const wsqlCreateUserTextParagraph = document.createElement('p');
        wsqlCreateUserTextParagraph.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        wsqlCreateUserTextParagraph.textContent = 'Enter the user details below to create a new user.';
        wsqlCreateUserText.appendChild(wsqlCreateUserTextParagraph);

        // Form container
        const wsqlCreateUserForm = document.createElement('div');
        wsqlCreateUserForm.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4';

        // Create and append the firstname input field
        const firstNameField = WsqlElements.labelTextInput('wsql-user-firstname', 'Firstname', 'text');
        wsqlCreateUserForm.appendChild(firstNameField);

        // Create and append the lastname input field
        const lastNameField = WsqlElements.labelTextInput('wsql-user-lastname', 'Lastname', 'text');
        wsqlCreateUserForm.appendChild(lastNameField);

        // Create the email input field wrapper
        const emailField = WsqlElements.labelTextInput('wsql-user-email', 'Email', 'email', 'col-span-2');
        wsqlCreateUserForm.appendChild(emailField);

        // Create the password input field wrapper
        const passwordField = WsqlElements.labelTextInput('wsql-user-password', 'Password', 'password', 'col-span-2');
        wsqlCreateUserForm.appendChild(passwordField);

        // Create the role select field wrapper
        const roleField = document.createElement('div');
        roleField.className = 'col-span-2';

        // Role label
        const roleLabel = document.createElement('p');
        roleLabel.className = 'block text-base font-medium text-gray-700 dark:text-white transition-all duration-300';
        roleLabel.textContent = 'Role';

        // Role select
        const roleSelect = document.createElement('select');
        roleSelect.className = 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent';

        // Role options
        const roleOption = document.createElement('option');
        roleOption.value = '';
        roleOption.textContent = 'Select a role';
        roleOption.disabled = true;
        roleOption.selected = true;
        roleSelect.appendChild(roleOption);

        // Fetch roles
        const response = await fetch(roles_endpoint);
        const roles = await response.json();
        console.log(roles);
        roles.data.forEach(role => {
            const option = document.createElement('option');
            option.value = role.id;
            option.textContent = role.name;
            roleSelect.appendChild(option);
        });

        // Append elements
        roleField.appendChild(roleLabel);
        roleField.appendChild(roleSelect);
        wsqlCreateUserForm.appendChild(roleField);

        // Create the role switch field
        const wqslUserApprovedSwitch = WsqlElements.switchInput('Approved', 'Toggle the switch to approve the user.', 'col-span-2');
        wsqlCreateUserForm.appendChild(wqslUserApprovedSwitch);

        // Create the role switch field
        const wqslUserEmailVerifiedSwitch = WsqlElements.switchInput('Email verified', 'Toggle the switch to verify the user\'s email address.', 'col-span-2');
        wsqlCreateUserForm.appendChild(wqslUserEmailVerifiedSwitch);

        // Actions container
        const wsqlCreateUserActions = document.createElement('div');
        wsqlCreateUserActions.className = 'flex gap-4 mt-8';

        // Create button
        const createButton = WsqlElements.button('Create', 'fas fa-user-plus', () => {
            userFunctions.createUser({
                firstname: firstNameField.querySelector('input').value,
                lastname: lastNameField.querySelector('input').value,
                email: emailField.querySelector('input').value,
                password: passwordField.querySelector('input').value,
                role: roleSelect.value,
                approved: wqslUserApprovedSwitch.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                email_verified: wqslUserEmailVerifiedSwitch.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
            }, (success, message) => {
                if (success) {
                    userFunctions.closeModel(modelElement);
                    WsqlToast.show('User created successfully', 'success');
                    userElements.usersTable.reload();
                } else {
                    WsqlToast.show(message, 'danger');
                }
            });
        });
        wsqlCreateUserActions.appendChild(createButton);

        // Cancel button
        const cancelButton = WsqlElements.button('Cancel', 'fas fa-times', () => {
            userFunctions.closeModel(modelElement);
        });
        wsqlCreateUserActions.appendChild(cancelButton);

        // Append elements
        modelContainer.appendChild(wsqlCreateUserText);
        modelContainer.appendChild(wsqlCreateUserForm);
        modelContainer.appendChild(wsqlCreateUserActions);
        
        // Create the model
        const modelElement = userFunctions.createModel(modelContainer);
        
    }

    /*
     * This function is used to open the delete user model
     */
    userFunctions.deleteUserOpenModel = async function(row) {
        // Modal container
        const modelContainer = document.createElement('div');

        // Modal text
        const confirmDeleteText = document.createElement('p');
        confirmDeleteText.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        confirmDeleteText.textContent = 'Are you sure you want to delete the following user?';

        // Gravatar
        const sanitizedValue = row.email.trim().toLowerCase();
        const hashBuffer = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(sanitizedValue));
        const hashArray = Array.from(new Uint8Array(hashBuffer)); // Convert buffer to byte array
        const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join(''); // Convert bytes to hex

        // User card
        const userCardDelete = document.createElement('div');
        userCardDelete.className = 'my-6 cursor-pointer w-full flex items-center gap-3 py-3 px-4 border border-zinc-100 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white text-sm font-baloo font-normal leading-3 rounded-xl transition-all duration-100';

        // Avatar wrapper
        const avatarWrapper = document.createElement('div');
        avatarWrapper.className = 'w-10 h-10 bg-zinc-200 dark:bg-neutral-700 rounded-full';

        // Avatar image
        const avatarImage = document.createElement('img');
        avatarImage.src = `https://gravatar.com/avatar/${hashHex}?size=40&d=mp`;
        avatarImage.alt = 'Avatar';
        avatarImage.className = 'w-full h-full object-cover rounded-full shadow';
        avatarWrapper.appendChild(avatarImage);
        userCardDelete.appendChild(avatarWrapper);

        // Text wrapper
        const textWrapper = document.createElement('div');
        textWrapper.className = 'flex flex-col';

        // Full name
        const fullName = document.createElement('h1');
        fullName.className = 'font-baloo font-semibold m-0 text-lg leading-5 text-zinc-800 dark:text-white';
        fullName.textContent = `${row.firstname} ${row.lastname}`;

        // Email address
        const emailAddress = document.createElement('p');
        emailAddress.className = 'flex gap-2 font-baloo font-normal m-0 text-sm leading-3 text-zinc-600 dark:text-zinc-300';
        emailAddress.textContent = row.email;

        fullName.appendChild(emailAddress);
        textWrapper.appendChild(fullName);
        userCardDelete.appendChild(textWrapper);

        // Confirm delete actions
        const confirmDeleteActions = document.createElement('div');
        confirmDeleteActions.className = 'flex gap-4';

        // Confirm delete button
        const actionDeleteButton = WsqlElements.button('Delete', 'fas fa-trash-can', () => {
            userFunctions.deleteUser(row.uuid, (success, message) => {
                if (success) {
                    userFunctions.closeModel(modelElement);
                    WsqlToast.show('User deleted successfully', 'success');
                    userElements.usersTable.reload();
                } else {
                    WsqlToast.show(message, 'danger');
                }
            });
        });
        confirmDeleteActions.appendChild(actionDeleteButton);

        // Cancel delete button
        const actionCancelButton = WsqlElements.button('Cancel', 'fas fa-times', () => {
            userFunctions.closeModel(modelElement);
        });
        confirmDeleteActions.appendChild(actionCancelButton);

        // Append elements
        modelContainer.appendChild(confirmDeleteText);
        modelContainer.appendChild(userCardDelete);
        modelContainer.appendChild(confirmDeleteActions);

        // Create the model
        const modelElement = userFunctions.createModel(modelContainer);
    }

    /*
     * This function creates a model
     */
    userFunctions.createModel = function(elements, modelClass = 'max-w-xl') {
        // Modal container
        const modelContainer = document.createElement('div');
        modelContainer.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 invisible opacity-0 transition-all duration-100';

        // Modal dialog
        const modelDialog = document.createElement('div');
        modelDialog.className = `bg-white dark:bg-zinc-900 rounded-lg shadow-lg p-8 w-full ${modelClass}`;

        // Append elements
        modelDialog.appendChild(elements);

        // Append dialog to container
        modelContainer.appendChild(modelDialog);

        // Append container to body
        document.body.appendChild(modelContainer);

        // Show modal
        setTimeout(() => {
            modelContainer.classList.remove('invisible', 'opacity-0');
            modelContainer.classList.add('visible', 'opacity-100');
        }, 1);

        return modelContainer;
    }

    /*
     * This function closes a model
     */
    userFunctions.closeModel = function(container) {
        container.classList.remove('visible', 'opacity-100');
        container.classList.add('invisible', 'opacity-0');
        setTimeout(() => {
            container.remove();
        }, 100);
    }

    /*
     * This function creates a new user
     */
    userFunctions.createUser = async function (data, callback) {
        try {
            const { firstname, lastname, email, password, role, approved, email_verified } = data;

            // Validate input
            if (!firstname || !lastname || !email || !password) {
                WsqlToast.show('Please fill in all required fields', 'warning');
                return;
            }

            // Submit user
            const response = await fetch(users_endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    firstname,
                    lastname,
                    email,
                    password,
                    role,
                    approved,
                    email_verified,
                }),
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message);
            }
            
            if (callback && typeof callback === "function") {
                callback(true);
            }
        } catch (error) {
            if (callback && typeof callback === "function") {
                callback(false, error.message);
            }
        }
    }

    /*
     * This function deletes a user
     */
    userFunctions.deleteUser = async function (uuid, callback) {
        try {
            // Submit delete request
            const response = await fetch(users_single_endpoint.replace('{id}', uuid), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            // Check response
            if (response.ok) {
                if (callback && typeof callback === "function") {
                    callback(true);
                }
            } else {
                const result = await response.json();
                throw new Error(result.message);
            }
        } catch (error) {
            if (callback && typeof callback === "function") {
                callback(false, error.message);
            }
        }
    }
});