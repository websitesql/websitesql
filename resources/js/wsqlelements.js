/*
 * Website SQL v2.1.0
 * 
 * File: 	wsqlElements
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 1.0.0
 */

class WsqlElements {
    /*
     * Button
     */
    static button(text, icon, callback, attributes = {}) {
        // Get attributes
        const divClass = attributes.divClass || '';

        // Create a button element
        const button = document.createElement('button');
        button.className = `flex items-center justify-center gap-2 h-10 py-1 px-5 border border-transparent rounded-md shadow-sm text-base font-baloo font-medium text-white leading-4 bg-neutral-700 hover:bg-neutral-700/90 dark:hover:bg-neutral-700/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-600 cursor-pointer transition-all duration-300 ${divClass}`;


        // Create an i element for the button icon
        const buttonI = document.createElement('i');
        buttonI.className = icon;

        // Create a span element for the button text
        const buttonSpan = document.createElement('span');
        buttonSpan.textContent = text;

        // Append the icon and text to the button
        button.appendChild(buttonI);
        button.appendChild(buttonSpan);

        // Register event listener if callback is provided
        if (callback) {
            button.addEventListener('click', callback);
        }

        // Return the button
        return button;
    }

    /*
     * Switch Input
     */
    static switchInput(name, description, divClass = '') {
        // Create container div
        const divContainer = document.createElement("div");
        divContainer.className = `flex items-center justify-between ${divClass}`;

        // Create first nested span (flex column)
        const spanFlexGrow = document.createElement("span");
        spanFlexGrow.className = "flex flex-grow flex-col";

        // Create inner text spans
        const spanText1 = document.createElement("span");
        spanText1.className = "text-base font-baloo font-medium text-gray-900 dark:text-white";
        spanText1.id = "availability-label";
        spanText1.textContent = name;

        const spanText2 = document.createElement("span");
        spanText2.className = "text-sm font-baloo text-gray-500 dark:text-gray-400";
        spanText2.id = "availability-description";
        spanText2.textContent = description;

        // Append text spans to the flex-grow span
        spanFlexGrow.appendChild(spanText1);
        spanFlexGrow.appendChild(spanText2);

        // Create the button element (role switch)
        const buttonSwitch = document.createElement("button");
        buttonSwitch.type = "button";
        buttonSwitch.className = "bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-neutral-600 focus:ring-offset-2";
        buttonSwitch.setAttribute("role", "switch");
        buttonSwitch.setAttribute("checked", "false");

        // Create the inner span for the button
        const spanButton = document.createElement("span");
        spanButton.className = "translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out";

        // Append the inner span to the button
        buttonSwitch.appendChild(spanButton);

        // Register event listener
        buttonSwitch.addEventListener("click", () => {
            const checked = buttonSwitch.getAttribute("checked") === "true";
            buttonSwitch.setAttribute("checked", checked ? "false" : "true");

            buttonSwitch.classList.toggle("bg-gray-200");
            buttonSwitch.classList.toggle("bg-neutral-600");
            spanButton.classList.toggle("translate-x-5");
            spanButton.classList.toggle("translate-x-0");
        });

        // Append the flex-grow span and button to the container div
        divContainer.appendChild(spanFlexGrow);
        divContainer.appendChild(buttonSwitch);

        return divContainer;
    }

    /*
     * Text Input
     */
    static textInput(inputName, inputType, attributes = {}) {
        // Get attributes
        const inputClass = attributes.class || '';
        const inputRequired = attributes.required || false;
        const inputPlaceholder = attributes.placeholder || '';
        const inputDisabled = attributes.disabled || false;
        const inputId = attributes.id || inputName;
        const inputStyle = attributes.style || '';

        // Create input element
        const input = document.createElement('input');
        input.className = `block w-full font-baloo h-10 py-1 px-5 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-neutral-600 focus:border-neutral-600 text-sm sm:text-base bg-transparent ${inputClass}`;
        
        // Set input attributes
        input.name = inputName;
        input.id = inputId;
        input.type = inputType;
        input.placeholder = inputPlaceholder;
        input.disabled = inputDisabled;
        input.required = inputRequired;
        input.style = inputStyle;

        // Return input
        return input;
    }

    /*
     * Label Text Input
     */
    static labelTextInput(inputName, labelText, inputType, divClass = '') {
        const div = document.createElement('div');
        div.className = divClass;

        const label = document.createElement('label');
        label.htmlFor = inputName;
        label.className = 'block mb-1 text-base font-medium font-baloo text-gray-700 dark:text-white transition-all duration-300';
        label.textContent = labelText;

        const input = this.textInput(inputName, inputType, true);
        
        div.appendChild(label);
        div.appendChild(input);

        return div;
    }

    /*
     * Textarea Input
     */
    static textareaInput(inputName, textareaRows = 4, attributes = {}) {
        // Get attributes
        const inputClass = attributes.class || '';
        const inputRequired = attributes.required || false;
        const inputPlaceholder = attributes.placeholder || '';
        const inputDisabled = attributes.disabled || false;
        const inputId = attributes.id || inputName;
        const inputStyle = attributes.style || '';

        // Create textarea element
        const input = document.createElement('textarea');
        input.className = `block w-full font-baloo px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base bg-transparent ${inputClass}`;
        
        // Set textarea attributes
        input.name = inputName;
        input.rows = textareaRows;
        input.id = inputId;
        input.style = inputStyle;
        input.required = inputRequired;
        input.disabled = inputDisabled;
        input.placeholder = inputPlaceholder;

        // Return input
        return input;
    }

    /*
     * Label Textarea Input
     */
    static labelTextareaInput(inputName, labelText, textareaRows = 4, divClass = '') {
        const div = document.createElement('div');
        div.className = divClass;

        const label = document.createElement('label');
        label.htmlFor = inputName;
        label.className = 'block mb-1 text-base font-baloo font-medium text-gray-700 dark:text-white transition-all duration-300';
        label.textContent = labelText;
        div.appendChild(label);

        const input = this.textareaInput(inputName, textareaRows);
        div.appendChild(input);

        return div;
    }

    /*
     * Select Input
     */
    static selectInput(inputName, options = [], attributes = {}) {
        // Get attributes
        const inputClass = attributes.class || '';
        const inputTitle = attributes.title || '';
        const inputRequired = attributes.required || false;
        const inputId = attributes.id || inputName;

        // Create select element
        const select = document.createElement('select');
        select.className = `block w-full font-baloo leading-3 h-10 py-1 px-5 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-neutral-600 focus:border-neutral-600 text-sm sm:text-base bg-transparent ${inputClass}`;

        // Set select attributes
        select.name = inputName;
        select.id = inputId;
        select.required = inputRequired;
        select.title = inputTitle;

        // Create options
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.label;
            optionElement.selected = option.selected || false;
            optionElement.disabled = option.disabled || false;
            select.appendChild(optionElement);
        });

        // Return select
        return select;
    }

    /**
     * Date-Time Input
     */
    static dateTimeInput(inputName, attributes = {}) {
        // Get attributes
        const inputClass = attributes.class || '';
        const inputRequired = attributes.required || false;
        const inputPlaceholder = attributes.placeholder || '';
        const inputDisabled = attributes.disabled || false;
        const inputId = attributes.id || inputName;
        const inputStyle = attributes.style || '';
        const inputValue = attributes.value || '';

        // Create input element
        const input = document.createElement('input');
        input.className = `block font-baloo w-full h-10 py-1 px-5 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-neutral-600 focus:border-neutral-600 text-sm sm:text-base bg-transparent ${inputClass}`;
        
        // Set input attributes
        input.name = inputName;
        input.id = inputId;
        input.type = 'datetime-local';
        input.placeholder = inputPlaceholder;
        input.disabled = inputDisabled;
        input.required = inputRequired;
        input.style = inputStyle;
        input.value = inputValue;

        // Return input
        return input;
    }

    /**
     * Label Date-Time Input
     */
    static labelDateTimeInput(inputName, labelText, attributes = {}, divClass = '') {
        const div = document.createElement('div');
        div.className = divClass;

        const label = document.createElement('label');
        label.htmlFor = inputName;
        label.className = 'block mb-1 text-base font-baloo font-medium text-gray-700 dark:text-white transition-all duration-300';
        label.textContent = labelText;
        div.appendChild(label);

        const input = this.dateTimeInput(inputName, attributes);
        div.appendChild(input);

        return div;
    }

    /**
     * Custom Dropdown with Search
     */
    static customDropdown(inputName, options = [], attributes = {}) {
        const dropdownContainer = document.createElement('div');
        dropdownContainer.className = 'relative';

        // Hidden input for storing value
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = inputName;
        hiddenInput.value = ''; // Initial empty value

        const dropdownButton = document.createElement('button');
        dropdownButton.type = 'button';
        dropdownButton.className = `block w-full font-baloo h-10 py-1 px-5 border text-neutral-800 dark:text-white text-left border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-neutral-600 focus:border-neutral-600 text-sm sm:text-base bg-transparent ${attributes.class || ''}`;
        dropdownButton.textContent = attributes.placeholder || 'Select an option';

        const dropdownListContainer = document.createElement('div');
        dropdownListContainer.className = 'absolute z-10 mt-1 p-3 w-full bg-white dark:bg-neutral-800 shadow-lg rounded-md overflow-hidden border border-neutral-300 hidden';

        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'block w-full font-baloo h-10 py-1 px-5 border border-neutral-300 text-neutral-800 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-neutral-600 focus:border-neutral-600 text-sm sm:text-base bg-transparent';
        searchInput.placeholder = 'Search...';

        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'max-h-60 overflow-y-auto mt-2 flex flex-col gap-2';

        options.forEach(option => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'p-2 hover:bg-neutral-200 dark:hover:bg-neutral-700 cursor-pointer rounded-md';
            optionDiv.dataset.value = option.id;

            const optionTitle = document.createElement('div');
            optionTitle.className = 'font-baloo font-medium text-gray-900 dark:text-white mb-1 flex gap-2 items-center';
            optionTitle.textContent = option.title;

            if (option.tag) {
                // Split tag by ; and create a span for each tag
                const tags = option.tag.split(';');
                tags.forEach(tag => {
                    const optionTitleTag = document.createElement('span');
                    optionTitleTag.className = 'inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                    optionTitleTag.textContent = tag;
                    optionTitle.appendChild(optionTitleTag);
                });
            }

            const optionDescription = document.createElement('div');
            optionDescription.className = 'text-sm font-baloo text-gray-600 dark:text-gray-400';
            optionDescription.textContent = option.description;

            optionDiv.appendChild(optionTitle);
            optionDiv.appendChild(optionDescription);

            optionDiv.addEventListener('click', () => {
                dropdownButton.textContent = option.title;
                dropdownListContainer.classList.add('hidden');

                // Update hidden input value
                hiddenInput.value = option.id;

                if (attributes.onSelect) {
                    attributes.onSelect(option);
                }
            });

            optionsContainer.appendChild(optionDiv);
        });

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            Array.from(optionsContainer.children).forEach(optionDiv => {
                const title = optionDiv.querySelector('.font-medium').textContent.toLowerCase();
                const description = optionDiv.querySelector('.text-sm').textContent.toLowerCase();
                const matches = title.includes(searchTerm) || description.includes(searchTerm);
                optionDiv.style.display = matches ? '' : 'none';
            });
        });

        dropdownButton.addEventListener('click', () => {
            dropdownListContainer.classList.toggle('hidden');
        });

        dropdownListContainer.appendChild(searchInput);
        dropdownListContainer.appendChild(optionsContainer);
        dropdownContainer.appendChild(hiddenInput);
        dropdownContainer.appendChild(dropdownButton);
        dropdownContainer.appendChild(dropdownListContainer);

        // Close the dropdown if clicked outside
        document.addEventListener('click', (e) => {
            if (!dropdownContainer.contains(e.target)) {
                dropdownListContainer.classList.add('hidden');
            }
        });

        return dropdownContainer;
    }

    /**
     * Label Custom Dropdown with Search
     */
    static labelCustomDropdown(inputName, labelText, options = [], attributes = {}, divClass = '') {
        const div = document.createElement('div');
        div.className = divClass;

        const label = document.createElement('label');
        label.htmlFor = inputName;
        label.className = 'block mb-1 text-base font-baloo font-medium text-gray-700 dark:text-white transition-all duration-300';
        label.textContent = labelText;
        div.appendChild(label);

        const input = this.customDropdown(inputName, options, attributes);
        div.appendChild(input);

        return div;
    }

}