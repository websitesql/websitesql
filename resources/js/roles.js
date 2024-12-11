/*
 * Website SQL v2.1.0
 * 
 * File: 	Roles
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 2.1.0
 */

document.addEventListener("DOMContentLoaded", function(event) {
    const roleTables = {};
    const roleFunctions = {};

    /*
     * Render the roles table
     */
    if (document.querySelector('[data-table="wsql-roles"]')) {
        // Usage
        roleTables.roleTable = new WsqlTable(document.querySelector('[data-table="wsql-roles"]'));

        roleTables.roleTable.setColumns([
            {
                key: "name",
                label: "Name",
                callback: (value, row) => {
                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'block leading-5 mt-1';
                    nameSpan.textContent = value;

                    const descriptionSpan = document.createElement('span');
                    descriptionSpan.className = 'text-gray-400 font-light text-sm whitespace-break-spaces';
                    descriptionSpan.textContent = row.description;

                    const div = document.createElement('div');
                    div.appendChild(nameSpan);
                    div.appendChild(descriptionSpan);

                    return div;
                }
            },
            {
                key: "app_access",
                label: "App Access",
                callback: (value, row) => {
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${value === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                    span.textContent = value === 1 ? 'Yes' : 'No';
                    return span;
                }
            },
            {
                key: "administrator",
                label: "Administrator",
                callback: (value, row) => {
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${value === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                    span.textContent = value === 1 ? 'Yes' : 'No';
                    return span;
                }
            },
            {
                key: "public_access",
                label: "Public Access",
                callback: (value, row) => {
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${value === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                    span.textContent = value === 1 ? 'Yes' : 'No';
                    return span;
                }
            },
        ]);

        roleTables.roleTable.setActions([
            {
                name: "Edit",
                icon: "fa-solid fa-edit",
                callback: (row) => {
                    window.location.href = `/admin/access-control/${row.uuid}`;
                },
            },
            {
                name: "Delete",
                icon: "fa-solid fa-trash-can",
                callback: (row) => roleFunctions.deleteRoleOpenModel(row),
            },
        ]);

        roleTables.roleTable.setSource({
            apiEndpoint: roles_endpoint,
            dataSelector: "data",
        });

        roleTables.roleTable.setButtons([
            {
                name: "Create role",
                icon: "fa-solid fa-shield-halved",
                callback: () => {
                    roleFunctions.createRoleOpenModel(function () {
                        roleTables.roleTable.render();
                    });
                }
            },
        ]);

        roleTables.roleTable.setName("roles");

        roleTables.roleTable.render();
    }

    /*
     * Create new role model
     */
    roleFunctions.createRoleOpenModel = async function (callback) {
        // Modal container
        const modelContainer = document.createElement('div');

        // Create user text
        const wsqlCreateRoleText = document.createElement('div');
        wsqlCreateRoleText.className = 'mb-8';

        // Modal Title
        const wsqlCreateRoleTextTitle = document.createElement('h1');
        wsqlCreateRoleTextTitle.className = 'text-xl text-gray-700 dark:text-white font-baloo font-semibold';
        wsqlCreateRoleTextTitle.textContent = 'Create role';
        wsqlCreateRoleText.appendChild(wsqlCreateRoleTextTitle);

        // Modal Paragraph
        const wsqlCreateRoleTextParagraph = document.createElement('p');
        wsqlCreateRoleTextParagraph.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        wsqlCreateRoleTextParagraph.textContent = 'Enter a name and description for the new role.';
        wsqlCreateRoleText.appendChild(wsqlCreateRoleTextParagraph);

        // Form container
        const wsqlCreateRoleForm = document.createElement('div');
        wsqlCreateRoleForm.className = 'grid grid-cols-1 gap-4';

        // Create and append the name input field
        const nameField = WsqlElements.labelTextInput('wsql-role-name', 'Name', 'text');
        wsqlCreateRoleForm.appendChild(nameField);

        // Create and append the description input field
        const descriptionField = WsqlElements.labelTextareaInput('wsql-role-description', 'Description', 4);
        wsqlCreateRoleForm.appendChild(descriptionField);

        // Create and append the app access switch field
        const appAccessField = WsqlElements.switchInput('App Access', 'This will allow the role to access the application');
        wsqlCreateRoleForm.appendChild(appAccessField);

        // Create and append the administrator switch field
        const administratorField = WsqlElements.switchInput('Administrator', 'This will give the role full access to the application and API');
        wsqlCreateRoleForm.appendChild(administratorField);

        // Create and append the public access switch field
        const publicAccessField = WsqlElements.switchInput('Public Access', 'Permissions assigned to the role will be available to the public');
        wsqlCreateRoleForm.appendChild(publicAccessField);

        // Actions container
        const wsqlCreateRoleActions = document.createElement('div');
        wsqlCreateRoleActions.className = 'flex gap-4 mt-8';

        // Create button
        const actionConfirmButton = WsqlElements.button('Create', 'fas fa-shield-halved', () => {
            // Chek the name and description fields
            if (!nameField.querySelector('input').value || !descriptionField.querySelector('textarea').value) {
                WsqlToast.show('Please fill in all required fields', 'danger');
                return;
            }

            // Create the role
            roleFunctions.createRole({
                name: nameField.querySelector('input').value,
                description: descriptionField.querySelector('textarea').value,
                app_access: appAccessField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                administrator: administratorField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                public_access: publicAccessField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
            }, (status, message) => {
                if (status) {
                    roleFunctions.closeModel(modelElement);
                    WsqlToast.show('Role created successfully', 'success');
                    roleTables.roleTable.reload();
                } else {
                    WsqlToast.show(message, 'danger');
                }
            });
        });
        wsqlCreateRoleActions.appendChild(actionConfirmButton);

        // Cancel button
        const wsqlCreateRoleButtonCancel = WsqlElements.button('Cancel', 'fas fa-times', () => {
            roleFunctions.closeModel(modelElement);
        });
        wsqlCreateRoleActions.appendChild(wsqlCreateRoleButtonCancel);

        // Append elements
        modelContainer.appendChild(wsqlCreateRoleText);
        modelContainer.appendChild(wsqlCreateRoleForm);
        modelContainer.appendChild(wsqlCreateRoleActions);

        // Create the model
        const modelElement = roleFunctions.createModel(modelContainer);
    }
    
    /*
     * Create delete role model
     */
    roleFunctions.deleteRoleOpenModel = function (row) {
        // Modal container
        const modelContainer = document.createElement('div');
    
        // Modal text
        const confirmDeleteText = document.createElement('p');
        confirmDeleteText.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        confirmDeleteText.textContent = 'Are you sure you want to delete the following role?';
    
        // User card
        const roleDeleteName = document.createElement('h1');
        roleDeleteName.className = 'font-semibold text-lg py-4';
        roleDeleteName.textContent = row.name;
    
        // Confirm delete actions
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'flex gap-4';
    
        // Confirm delete button
        const buttonConfirm = WsqlElements.button('Delete', 'fas fa-trash-can', () => {
            roleFunctions.deleteRole(row.uuid, (success, message) => {
                if (success) {
                    roleFunctions.closeModel(modelElement);
                    WsqlToast.show('Role deleted successfully', 'success');
                    roleTables.roleTable.reload();
                } else {
                    WsqlToast.show(message, 'danger');
                }
            });
        });
    
        // Cancel delete button
        const buttonCancel = WsqlElements.button('Cancel', 'fas fa-times', () => {
            roleFunctions.closeModel(modelElement);
        });
    
        // Append buttons
        actionsDiv.appendChild(buttonConfirm);
        actionsDiv.appendChild(buttonCancel);
    
        // Append elements
        modelContainer.appendChild(confirmDeleteText);
        modelContainer.appendChild(roleDeleteName);
        modelContainer.appendChild(actionsDiv);
    
        // Create the model
        const modelElement = roleFunctions.createModel(modelContainer);
    }
    
    /*
     * Get role ID from URL
     */
    roleFunctions.getRoleId = function () {
        const url = new URL(window.location.href);
        return url.pathname.split('/').pop();
    }

    /*
     * Role details form
     */
    if (document.querySelector('[data-form="wsql-role-details"]')) {
        // Load role details
        fetch(roles_single_endpoint.replace('{id}', roleFunctions.getRoleId()), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            roleFunctions.wsqlRoleDetailsForm(data);
        });
    }

    /*
     * Render the role details form
     */
    roleFunctions.wsqlRoleDetailsForm = function (data) {
        const container = document.querySelector('[data-form="wsql-role-details"]');
    
        // Container
        const div = document.createElement('div');
        div.className = 'grid grid-cols-2 gap-8';

        // Left column
        const leftColumn = document.createElement('div');
        leftColumn.className = 'col-span-1 grid grid-cols-1 gap-4';

        // Name
        const nameField = WsqlElements.labelTextInput('wsql-role-name', 'Name', 'text');
        nameField.querySelector('input').value = data.name;

        // Description
        const descriptionField = WsqlElements.labelTextareaInput('wsql-role-description', 'Description', 4);
        descriptionField.querySelector('textarea').value = data.description;

        // Append fields
        leftColumn.appendChild(nameField);
        leftColumn.appendChild(descriptionField);

        // Right column
        const rightColumn = document.createElement('div');
        rightColumn.className = 'col-span-1 grid grid-cols-1 gap-4';

        // App Access
        const appAccessField = WsqlElements.switchInput('App Access', 'This will allow the role to access the application');
        if (data.app_access) {
            appAccessField.querySelector('button').click();
        }

        // Administrator
        const administratorField = WsqlElements.switchInput('Administrator', 'This will give the role full access to the application and API');
        if (data.administrator) {
            administratorField.querySelector('button').click();
        }

        // Public Access
        const publicAccessField = WsqlElements.switchInput('Public Access', 'Permissions assigned to the role will be available to the public');
        if (data.public_access) {
            publicAccessField.querySelector('button').click();
        }

        // Append fields
        rightColumn.appendChild(appAccessField);
        rightColumn.appendChild(administratorField);
        rightColumn.appendChild(publicAccessField);

        // Actions container
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'col-span-2 flex gap-4';

        // Save button
        const buttonSave = WsqlElements.button('Update', 'fas fa-save', () => {
            roleFunctions.updateRole(data.uuid, {
                name: nameField.querySelector('input').value,
                description: descriptionField.querySelector('textarea').value,
                app_access: appAccessField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                administrator: administratorField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                public_access: publicAccessField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
            }, (success, message) => {
                if (success) {
                    WsqlToast.show('Role updated successfully', 'success');
                } else {
                    WsqlToast.show(message, 'error');
                }
            });
        });
        actionsDiv.appendChild(buttonSave);

        // Append columns
        div.appendChild(leftColumn);
        div.appendChild(rightColumn);
        div.appendChild(actionsDiv);

        // Append container
        container.innerHTML = '';
        container.appendChild(div);
    }

    /*
     * Role permissions table
     */
    if (document.querySelector('[data-table="wsql-role-permissions"]')) {
        // Usage
        roleTables.permissionsTable = new WsqlTable(document.querySelector('[data-table="wsql-role-permissions"]'));

        roleTables.permissionsTable.setColumns([
            {
                key: "name",
                label: "Name"
            },
            {
                key: "enabled",
                label: "Enabled",
                callback: (value, row) => {
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${value === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                    span.textContent = value === 1 ? 'Yes' : 'No';
                    return span;
                }
            },
            {
                key: "filter",
                label: "Filter",
                callback: (value, row) => {
                    
                    const span = document.createElement('span');
                    span.className = `inline-flex items-center px-3.5 py-1 rounded-full text-sm font-medium ${!value ? 'bg-gray-100 text-gray-800' : 'whitespace-pre truncate'}`;
                    span.textContent = !value ? 'None' : value;
                    return span;
                }
            },
        ]);

        roleTables.permissionsTable.setActions([
            {
                name: "Edit",
                icon: "fas fa-edit",
                callback: (row) => console.log("Edit clicked for:", row),
            },
            {
                name: "Delete",
                icon: "fas fa-trash",
                callback: (row) => {
                    roleFunctions.deletePermissionOpenModel(row);
                },
            },
        ]);

        roleTables.permissionsTable.setSource({
            apiEndpoint: roles_single_permissions_endpoint.replace('{id}', roleFunctions.getRoleId()),
            dataSelector: "data",
        });

        roleTables.permissionsTable.setButtons([
            {
                name: "Add permission",
                icon: "fas fa-edit",
                callback: () => {
                    roleFunctions.createPermissionOpenModel();
                }
            },
        ]);

        roleTables.permissionsTable.setName("permissions");

        roleTables.permissionsTable.render();
    }

    /*
     * Create new permission model
     */
    roleFunctions.createPermissionOpenModel = async function () {
        let selectedPermission = [];

        // Modal container
        const modelContainer = document.createElement('div');

        // Create user text
        const createPermissionText = document.createElement('div');
        createPermissionText.className = 'mb-8';

        // Modal Title
        const createPermissionTextTitle = document.createElement('h1');
        createPermissionTextTitle.className = 'text-xl text-gray-700 dark:text-white font-baloo font-semibold';
        createPermissionTextTitle.textContent = 'Add permission';
        createPermissionText.appendChild(createPermissionTextTitle);

        // Modal Paragraph
        const createPermissionTextParagraph = document.createElement('p');
        createPermissionTextParagraph.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        createPermissionTextParagraph.textContent = 'Select a permission from the dropdown to add a new permission.';
        createPermissionText.appendChild(createPermissionTextParagraph);

        // Form container
        const createPermissionForm = document.createElement('div');
        createPermissionForm.className = 'grid grid-cols-1 gap-4';

        // Get the permissions
        const response = await fetch(permissions_endpoint);
        const availablePermissions = await response.json(); // Parse the JSON data
        const options = [];
        availablePermissions.forEach(permission => {
            options.push({
                id: permission.name,
                title: permission.name,
                description: permission.description || 'No description available',
                tag: permission.type || null
            });
        });

        const nameContainer = document.createElement('div');

        // Create and append the name input field
        const nameField = WsqlElements.labelCustomDropdown('wsql-permissions-permission', 'Permission', options, {
            onSelect: (selectedOption) => {
                // Clear and set the selected permission
                selectedPermission = [];
                selectedPermission.push(selectedOption.id);

                // Get the selected option from the available permissions list
                const selectionData = availablePermissions.find(permission => permission.name === selectedOption.id);

                // If selectionData.dependents is not null, show a warning for dependent permissions
                if (selectionData.dependents) {
                    warningField.innerHTML = '';

                    const warningDialog = document.createElement('div');
                    warningDialog.className = 'mt-4 bg-blue-100 text-blue-800 border border-blue-600 p-4 rounded-lg font-baloo font-normal text-sm';
                    warningDialog.textContent = 'This permission requires the following dependent permissions, these will be added automatically if not already:';

                    const warningList = document.createElement('ul');
                    warningList.className = 'list-disc ml-4 font-baloo font-normal text-sm';
                    selectionData.dependents.forEach(dependent => {
                        // Push the dependent permission to the selected permissions
                        selectedPermission.push(dependent);

                        // Create and append the list item
                        const warningListItem = document.createElement('li');
                        warningListItem.textContent = dependent;
                        warningList.appendChild(warningListItem);
                    });
                    
                    // Append dialog
                    warningDialog.appendChild(warningList);
                    warningField.appendChild(warningDialog);
                } else {
                    warningField.innerHTML = '';
                }
            }
        });
        nameContainer.appendChild(nameField);

        // Create and append the warning text placeholder field
        const warningField = document.createElement('span');
        nameContainer.appendChild(warningField);

        // Append fields
        createPermissionForm.appendChild(nameContainer);

        // Create and append the description input field
        const filterField = WsqlElements.labelTextareaInput('wsql-permissions-filter', 'Filter', 4);
        createPermissionForm.appendChild(filterField);

        // Create and append the app access switch field
        const enabledField = WsqlElements.switchInput('Enabled', 'This will enable the permission for the role');
        createPermissionForm.appendChild(enabledField);

        // Actions container
        const actionDiv = document.createElement('div');
        actionDiv.className = 'flex gap-4 mt-8';

        // Create button
        const actionConfirmButton = WsqlElements.button('Add', 'fas fa-shield-halved', () => {
            try {
                selectedPermission.forEach(permission => {
                    // Create the permission
                    roleFunctions.createPermission({
                        name: permission,
                        filter: filterField.querySelector('textarea').value,
                        enabled: enabledField.querySelector('button').getAttribute('checked') === 'true' ? 1 : 0,
                    }, (status, message) => {
                        if (!status) {
                            if (message != 'A permission with the same name already exists') {
                                throw new Error(message);
                            }
                        }
                    });
                });

                // Close the model
                roleFunctions.closeModel(modelElement);
                WsqlToast.show('Permission added successfully', 'success');
                roleTables.permissionsTable.reload();
            } catch (error) {
                roleFunctions.closeModel(modelElement);
                WsqlToast.show(error.message, 'danger');
            }
        });
        actionDiv.appendChild(actionConfirmButton);

        // Cancel button
        const wsqlCreateRoleButtonCancel = WsqlElements.button('Cancel', 'fas fa-times', () => {
            roleFunctions.closeModel(modelElement);
        });
        actionDiv.appendChild(wsqlCreateRoleButtonCancel);

        // Append elements
        modelContainer.appendChild(createPermissionText);
        modelContainer.appendChild(createPermissionForm);
        modelContainer.appendChild(actionDiv);

        // Create the model
        const modelElement = roleFunctions.createModel(modelContainer);
    }

    /*
     * Create edit permission model
     */
    roleFunctions.editPermissionOpenModel = function (row) {

    }

    /*
     * Create delete permission model
     */
    roleFunctions.deletePermissionOpenModel = function (row) {
        // Modal container
        const modelContainer = document.createElement('div');

        // Modal text
        const confirmDeleteText = document.createElement('p');
        confirmDeleteText.className = 'text-md text-gray-700 dark:text-white font-baloo font-normal';
        confirmDeleteText.textContent = 'Are you sure you want to delete the following permission?';

        // User card
        const permissionDeleteName = document.createElement('h1');
        permissionDeleteName.className = 'font-semibold text-lg py-4';
        permissionDeleteName.textContent = row.name;

        // Confirm delete actions
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'flex gap-4';

        // Confirm delete button
        const buttonConfirm = WsqlElements.button('Delete', 'fas fa-trash-can', () => {
            roleFunctions.deletePermission(row.id, (success, message) => {
                if (success) {
                    roleFunctions.closeModel(modelElement);
                    WsqlToast.show('Permission deleted successfully', 'success');
                    roleTables.permissionsTable.reload();
                } else {
                    WsqlToast.show(message, 'danger');
                }
            });
        });

        // Cancel delete button
        const buttonCancel = WsqlElements.button('Cancel', 'fas fa-times', () => {
            roleFunctions.closeModel(modelElement);
        });

        // Append buttons
        actionsDiv.appendChild(buttonConfirm);
        actionsDiv.appendChild(buttonCancel);

        // Append elements
        modelContainer.appendChild(confirmDeleteText);
        modelContainer.appendChild(permissionDeleteName);
        modelContainer.appendChild(actionsDiv);

        // Create the model
        const modelElement = roleFunctions.createModel(modelContainer);
    }
    
    /*
     * This function creates a model
     */
    roleFunctions.createModel = function(elements, modelClass = 'max-w-xl') {
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
    roleFunctions.closeModel = function(container) {
        container.classList.remove('visible', 'opacity-100');
        container.classList.add('invisible', 'opacity-0');
        setTimeout(() => {
            container.remove();
        }, 100);
    }

    /*
     * This function creates a role
     */
    roleFunctions.createRole = async function (data, callback) {
        try {
            // Submit role
            const response = await fetch(roles_endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });

            // Check response
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
     * This function creates a permission
     */
    roleFunctions.createPermission = async function (data, callback) {
        try {
            // Submit permission
            const response = await fetch(roles_single_permissions_endpoint.replace('{id}', roleFunctions.getRoleId()), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });

            // Check response
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
     * This function updates a role
     */
    roleFunctions.updateRole = async function (uuid, data, callback) {
        try {
            const { name, description, app_access, administrator, public_access } = data;

            // Validate input
            if (!name || !description) {
                throw new Error('Please fill in all required fields');
            }

            // Submit role
            const response = await fetch(roles_single_endpoint.replace('{id}', uuid), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name,
                    description,
                    app_access,
                    administrator,
                    public_access,
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
     * This function deletes a role
     */
    roleFunctions.deleteRole = async function (uuid, callback) {
        try {
            // Submit delete request
            const response = await fetch(roles_single_endpoint.replace('{id}', uuid), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            // Check response
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
     * This function deletes a permission
     */
    roleFunctions.deletePermission = async function (id, callback) {
        try {
            // Submit delete request
            const response = await fetch(roles_single_permissions_single_endpoint.replace('{role_id}', roleFunctions.getRoleId()).replace('{permission_id}', id), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            // Check response
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
});