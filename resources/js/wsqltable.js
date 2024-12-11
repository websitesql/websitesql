/*
 * Website SQL v2.1.0
 * 
 * File: 	wsqlTable
 * Author: 	Alan Tiller
 * Date: 	2024-11-30
 * Version: 1.0.8
 */

class WsqlTable {
    constructor(container) {
        this.container = container;
        this.columnsConfig = [];
        this.actionsConfig = [];
        this.buttonsConfig = [];
        this.limitConfig = [{value: 5, label: "5", selected: true}, {value: 10, label: "10"}, {value: 25, label: "25"}, {value: 50, label: "50"}, {value: 100, label: "100"}];
        this.apiEndpoint = null; // API endpoint
        this.dataSelector = null; // Path to extract data from the API response
        this.data = [];
        this.totalRecords = 0;
        this.currentPage = 1;
        this.currentLimit = this.limitConfig[0]['value']; // Default limit (first in the list)
        this.searchQuery = ""; // Search query
        this.tableName = "table"; // Default table name
        this.elements = {}; // Store created elements
    }

    // Set limit options
    setLimits(limitOptions) {
        this.limitConfig = limitOptions;
        this.currentLimit = limitOptions[0];
        return this; // Enable method chaining
    }

    // Set columns configuration
    setColumns(columns) {
        this.columnsConfig = columns;
        return this; // Enable method chaining
    }

    // Set actions configuration
    setActions(actions) {
        this.actionsConfig = actions;
        
        return this; // Enable method chaining
    }

    // Set buttons configuration
    setButtons(buttons) {
        this.buttonsConfig = buttons;
        return this; // Enable method chaining
    }

    // Set table name
    setName(name) {
        this.tableName = name;
        return this; // Enable method chaining
    }

    // Set API endpoint and data selector
    setSource({ apiEndpoint, dataSelector }) {
        this.apiEndpoint = apiEndpoint;
        this.dataSelector = dataSelector;
        return this; // Enable method chaining
    }

    async loadData() {
        if (!this.apiEndpoint || !this.dataSelector) {
            console.error("API endpoint or dataSelector is not configured.");
            return;
        }
    
        try {
            const url = new URL(this.apiEndpoint);
            const offset = (this.currentPage - 1) * this.currentLimit; // Calculate offset
            url.searchParams.append("offset", offset); // Add offset to API request
            url.searchParams.append("limit", this.currentLimit); // Add limit to API request
            if (this.searchQuery) {
                url.searchParams.append("search", this.searchQuery);
            }
    
            const response = await fetch(url.toString());
            const json = await response.json();
            this.data = this.dataSelector.split('.').reduce((obj, key) => obj[key], json);
            this.totalRecords = json.total || this.data.length;
        } catch (error) {
            console.error("Error loading data from API:", error);
            this.data = [];
            this.totalRecords = 0;
        }
    }    

    // Render the table
    async render() {
        if (!this.apiEndpoint || !this.dataSelector) {
            console.error("API endpoint or dataSelector is not set. Call `.data()` before `.render()`.");
            return;
        }

        await this.loadData();
        this.createTableStructure();
        this.renderTable();
        this.renderPagination();
        this.updateRecordInfo();
        this.attachEventListeners();
    }

    // Reload data and refresh the table
    async reload() {
        console.log(this);
        await this.loadData();
        this.renderTable();
        this.renderPagination();
        this.updateRecordInfo();
    }

    createTableStructure() {
        // Create controls container
        const controls = document.createElement("div");
        controls.className = "flex justify-between items-center mb-6";

        // Left controls (search and buttons)
        const leftControlsDiv = document.createElement("div");
        leftControlsDiv.className = `flex items-center gap-3`;

        // Create buttons
        this.buttonsConfig.forEach((button) => {
            const btn = WsqlElements.button(button.name, button.icon, () => button.callback());
            leftControlsDiv.appendChild(btn);
        });
        
        // Search input
        const searchDiv = document.createElement("div");
        searchDiv.className = "flex items-center";
        const searchInput = WsqlElements.textInput("wsql-table-search", "text", {
            placeholder: `Search ${this.tableName}...`,
        });
        searchDiv.appendChild(searchInput);
        leftControlsDiv.appendChild(searchDiv);

        // Right controls (limit)
        const rightControlsDiv = document.createElement("div");
        rightControlsDiv.className = "flex items-center gap-3";

        // Limit select
        const limitDiv = document.createElement("div");
        limitDiv.className = "flex items-center gap-3";
        const limitLabel = document.createElement("label");
        limitLabel.textContent = "Show";
        limitLabel.className = "text-base";
        const limitSelect = WsqlElements.selectInput('wsql-roles-limit', this.limitConfig);
        limitDiv.appendChild(limitLabel);
        limitDiv.appendChild(limitSelect);
        rightControlsDiv.appendChild(limitDiv);

        // Append left and right controls
        controls.appendChild(leftControlsDiv);
        controls.appendChild(rightControlsDiv);

        // Create table
        const tableWrapper = document.createElement("div");
        tableWrapper.className = "overflow-x-auto overflow-y-hidden shadow ring-1 ring-gray-600 dark:ring-zinc-600 ring-opacity-5 md:rounded-md";
        const table = document.createElement("table");
        table.className = "min-w-full divide-y divide-gray-300 dark:divide-zinc-700";
        const thead = document.createElement("thead");
        thead.className = "bg-gray-50 dark:bg-zinc-800";
        const theadRow = document.createElement("tr");
        this.columnsConfig.forEach((column, index) => {
            const th = document.createElement("th");
            th.textContent = column.label;
            th.className = `${index === 0 ? 'py-3.5 pl-4 pr-3' : 'py-3.5 px-3'}  text-left text-base font-semibold text-gray-900 dark:text-white font-baloo font-regular text-nowrap`;
            theadRow.appendChild(th);
        });
        if (this.actionsConfig.length > 0) {
            const thActions = document.createElement("th");
            thActions.className = "py-3.5 pl-3 pr-4 text-left text-base font-semibold text-gray-900 dark:text-white font-baloo font-regular text-nowrap";
            const thSpan = document.createElement("span");
            thSpan.className = "sr-only";
            thSpan.textContent = "Actions";
            thActions.appendChild(thSpan);
            theadRow.appendChild(thActions);
        }
        thead.appendChild(theadRow);
        table.appendChild(thead);

        const tbody = document.createElement("tbody");
        tbody.className = "divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800";
        table.appendChild(tbody);
        tableWrapper.appendChild(table);

        // Pagination
        const pagination = document.createElement("div");
        pagination.className = "flex justify-between items-center mt-6";
        const recordInfo = document.createElement("p");
        recordInfo.className = "text-sm text-gray-700";
        const paginationNav = document.createElement("div");
        paginationNav.className = "flex gap-2";

        // Store references to elements
        this.elements = {
            controls,
            searchInput,
            limitSelect,
            tableWrapper,
            tbody,
            pagination,
            recordInfo,
            paginationNav,
        };

        // Append elements to container
        this.container.innerHTML = ""; // Clear any existing content
        this.container.appendChild(controls);
        this.container.appendChild(tableWrapper);
        this.container.appendChild(pagination);
        pagination.appendChild(recordInfo);
        pagination.appendChild(paginationNav);
    }

    attachEventListeners() {
        // Debounced Search Input
        this.elements.searchInput.addEventListener("input", this.debounce(async (e) => {
            this.searchQuery = e.target.value.trim();
            this.currentPage = 1;
            await this.loadData();
            this.renderTable();
            this.renderPagination();
            this.updateRecordInfo();
        }, 300));

        // Limit select
        this.elements.limitSelect.addEventListener("change", async (e) => {
            this.currentLimit = parseInt(e.target.value, 10);
            this.currentPage = 1;
            await this.loadData();
            this.renderTable();
            this.renderPagination();
            this.updateRecordInfo();
        });
    }

    debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    renderTable() {
        const { tbody } = this.elements;
        tbody.innerHTML = ""; // Clear existing rows

        this.data.forEach((row) => {
            const tr = document.createElement("tr");

            this.columnsConfig.forEach((col, index) => {
                const td = document.createElement("td");
                td.className = `${index === 0 ? 'py-3.5 pl-4 pr-3' : 'py-3.5 px-3'} whitespace-nowrap text-wrap text-base text-gray-700 dark:text-white font-baloo font-regular ${col.className || ""}`;
                
                // Use resolveNestedKey to handle both flat and nested keys
                const value = col.key ? this.resolveNestedKey(row, col.key) : undefined;

                if (col.callback) {
                    td.appendChild(col.callback(value, row));
                } else {
                    td.textContent = value ?? "";
                }
                tr.appendChild(td);
            });

            // Actions
            if (this.actionsConfig.length > 0) {
                const actionsTd = document.createElement("td");
                actionsTd.className = "whitespace-nowrap py-3.5 pl-3 pr-4 text-base text-gray-700 dark:text-white font-baloo font-regular w-56";
                const actiondDiv = document.createElement("div");
                actiondDiv.className = "flex gap-3";
                this.actionsConfig.forEach((action) => {
                    const button = WsqlElements.button(action.name, action.icon, () => action.callback(row));
                    actiondDiv.appendChild(button);
                });
                actionsTd.appendChild(actiondDiv);
                tr.appendChild(actionsTd);
            }

            tbody.appendChild(tr);
        });
    }

    renderPagination() {
        const { paginationNav } = this.elements;
        paginationNav.innerHTML = ""; // Clear existing pagination

        const totalPages = Math.ceil(this.totalRecords / this.currentLimit);

        const navigationDiv = document.createElement("div");
        navigationDiv.className = "isolate inline-flex -space-x-px rounded-md shadow-sm";

        // Previous button
        const previousButton = document.createElement("a");
        previousButton.className = `relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 ${this.currentPage > 1 ? "" : "cursor-not-allowed"}`;
        const previousButtonSpan = document.createElement("span");
        previousButtonSpan.className = "sr-only";
        previousButtonSpan.textContent = "Previous";
        const previousButtonIcon = document.createElement("div");
        previousButtonIcon.className = "size-5";
        previousButtonIcon.innerHTML = `<svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path></svg>`;
        previousButton.appendChild(previousButtonSpan);
        previousButton.appendChild(previousButtonIcon);
        navigationDiv.appendChild(previousButton);
        previousButton.addEventListener("click", async () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                await this.loadData();
                this.renderTable();
                this.renderPagination();
                this.updateRecordInfo();
            }
        });

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const className = this.currentPage !== i ? "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" : "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
            const page = document.createElement("a");
            page.className = `relative inline-flex items-center px-4 py-2 text-sm font-semibold ${className}`;
            page.textContent = i;
            navigationDiv.appendChild(page);
            page.addEventListener("click", async () => {
                this.currentPage = i;
                await this.loadData();
                this.renderTable();
                this.renderPagination();
                this.updateRecordInfo();
            });
        }

        // Next button
        const nextButton = document.createElement("a");
        nextButton.className = `relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 ${this.currentPage < totalPages ? "" : "cursor-not-allowed"}`;
        const nextButtonSpan = document.createElement("span");
        nextButtonSpan.className = "sr-only";
        nextButtonSpan.textContent = "Previous";
        const nextButtonIcon = document.createElement("div");
        nextButtonIcon.className = "size-5";
        nextButtonIcon.innerHTML = `<svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>`;
        nextButton.appendChild(nextButtonSpan);
        nextButton.appendChild(nextButtonIcon);
        navigationDiv.appendChild(nextButton);
        nextButton.addEventListener("click", async () => {
            if (this.currentPage < totalPages) {
                this.currentPage++;
                await this.loadData();
                this.renderTable();
                this.renderPagination();
                this.updateRecordInfo();
            }
        });

        // Append navigation buttons
        paginationNav.appendChild(navigationDiv);
    }

    updateRecordInfo() {
        const { recordInfo } = this.elements;
        const start = (this.currentPage - 1) * this.currentLimit + 1;
        const end = Math.min(this.currentPage * this.currentLimit, this.totalRecords);
    
        // Clear existing content
        recordInfo.innerHTML = "";
    
        // Create a new paragraph for the record info
        const recordInfoParagraph = document.createElement("p");
        recordInfoParagraph.className = "text-sm text-gray-700";
    
        // Add "Showing" text
        recordInfoParagraph.appendChild(document.createTextNode("Showing "));
    
        // Add start span
        const startRecordSpan = document.createElement("span");
        startRecordSpan.className = "font-medium";
        startRecordSpan.textContent = start;
        recordInfoParagraph.appendChild(startRecordSpan);
    
        // Add " to " text
        recordInfoParagraph.appendChild(document.createTextNode(" to "));
    
        // Add end span
        const endRecordSpan = document.createElement("span");
        endRecordSpan.className = "font-medium";
        endRecordSpan.textContent = end;
        recordInfoParagraph.appendChild(endRecordSpan);
    
        // Add " of " text
        recordInfoParagraph.appendChild(document.createTextNode(" of "));
    
        // Add total records span
        const totalRecordsSpan = document.createElement("span");
        totalRecordsSpan.className = "font-medium";
        totalRecordsSpan.textContent = this.totalRecords;
        recordInfoParagraph.appendChild(totalRecordsSpan);
    
        // Add " records" text
        recordInfoParagraph.appendChild(document.createTextNode(" records"));
    
        // Append the paragraph to the recordInfo element
        recordInfo.appendChild(recordInfoParagraph);
    }

    resolveNestedKey(obj, key) {
        if (!key) return undefined; // Return undefined if the key is null, undefined, or empty
        return key.split('.').reduce((acc, part) => (acc && acc[part] !== undefined ? acc[part] : undefined), obj);
    }
}