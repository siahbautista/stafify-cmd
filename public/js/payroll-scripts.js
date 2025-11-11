const WAIT_PAYSLIPS = "Sending payout slips to employees. Please wait...";
const WAIT_ADD_PAYROLL = "Creating new payroll from template. Please wait...";
const WAIT_NEW_CATEGORY = "Creating new category. Please wait...";

let payrolls = []; let paginatedPayrolls = []; let searchedPayrolls = []; let categories = []; let templates = []; let users = {"EE": [], "ISP": []};
let fileName = ""; let categoryValue = "";
let settings = {
    id: 0,
    frequency: 0,
    disbursement: 0,
    deduction_schedule: "",
    benefits_url: ""
};

let pgntrIndex = 0; let pgntrStart = 0; let pgntrEnd = 0; let pgntrInterval = 4;

getFiles(); /* call on start */

async function callApi({ method = 'GET', params = {}, data = null }) {
    // Build URL with query parameters
    const query = new URLSearchParams(params).toString();
    const fullUrl = query ? `/api/payroll?${query}` : `/api/payroll`;

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Set up fetch options
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };

    // Add CSRF token for non-GET requests
    if (csrfToken && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
        options.headers['X-CSRF-TOKEN'] = csrfToken;
    }

    // Add body for POST/PUT/PATCH
    if (data && ['POST', 'PUT', 'PATCH'].includes(method.toUpperCase())) {
        options.body = JSON.stringify(data);
    }

    // Call API
    try {
        const response = await fetch(fullUrl, options);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        return null;
    }
}

function search(event) {
    const searchValue = event.target.value;

    searchedPayrolls = payrolls.filter((element) => 
        element.name.toLowerCase().includes(searchValue.toLowerCase()) || searchValue.trim() == ''
    );

    updatePaginator();
}

function openPopup(type) {
    let template = '';
    switch(type) {
        case 'payroll':
        template = SWAL_PAYROLL_TEMPLATE;
        break;

        case 'category':
        template = SWAL_PAYROLL_CATEGORY_TEMPLATE;
        break;
    }

    Swal.fire({
        title: `Add ${type.toUpperCase()}`,
        html: template,
        customClass: {
        popup: "swal-popup"
        },
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: "#1F5497",
        confirmButtonText: `CONFIRM`,
        didOpen: () => {
            // Populate template dropdown if it's a payroll popup
            if (type == "payroll") {
                const templateSelect = document.getElementById("swal-input-template");
                if (templateSelect && templates.length > 0) {
                    templates.forEach(template => {
                        const option = document.createElement("option");
                        option.value = template.id;
                        option.textContent = template.name;
                        templateSelect.appendChild(option);
                    });
                }
            }
        },
        preConfirm: () => {
        const val1 = document.getElementById("swal-input1").value;
        
        if(type == "payroll") {
            const val2 = document.getElementById("swal-input2").value;
            const templateId = document.getElementById("swal-input-template").value;
            if (!val1.trim() || !val2.trim() || !templateId.trim()) {
                Swal.showValidationMessage("Please fill out all fields!");
                return false;
            }
            
            return { val1, val2, templateId };
        } else {
            if (!val1.trim()) {
                Swal.showValidationMessage("Please fill out all fields!");
                return false;
            }
            
            return { val1 };
        }
        }
    }).then((res) => {
        if (res.isConfirmed) {
        if(res.value.val1 && res.value.val2 && res.value.templateId) {
            createNewPayroll(res.value.val1, res.value.val2, res.value.templateId);
        } else if(res.value.val1 && res.value.val2) {
            createNewPayroll(res.value.val1, res.value.val2);
        } else if(res.value.val1) {
            createNewCategory(res.value.val1);
        }
        }
    });
}

function getFiles() {
    callApi({
        method: 'GET',
        params: { path: 'initial' },
    }).then(res => {
        if (!res || !res.success || !res.data) {
            console.error('Failed to fetch data:', res);
            return;
        }
        
        try {
            let data = JSON.parse(res.data);
            payrolls = data.payrolls || [];
            categories = data.categories || [];
            templates = data.templates || [];
            settings.id = data.settings?.id || 0;
            settings.frequency = data.settings?.frequency || 0;
            settings.disbursement = data.settings?.disbursement || 0;
            settings.deduction_schedule = data.settings?.deduction_schedule || '';
            settings.benefits_url = data.settings?.benefits_url || '';

            searchedPayrolls = payrolls;
            paginatedPayrolls = payrolls.slice(0, pgntrInterval);
            updateCategories();
            updatePaginator();
            const paginator = document.getElementById("payroll-paginator-container");
            if (paginator) {
                paginator.style.display = "flex";
            }
        } catch (error) {
            console.error('Error parsing response data:', error);
        }
    }).catch(error => {
        console.error('Error fetching files:', error);
    });
}

/* SENDING PAYSLIPS ACTIONS */
function sendPayslips(index, sheetIndex) {
    Swal.fire({
        title: "How do you want to save it?",
        text: "Select one of the options below:",
        icon: "question",
        reverseButtons: true,
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: "Send to all",
        denyButtonText: "Send to an employee",
        cancelButtonText: `<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>`,
        confirmButtonColor: "#1F5497",
        denyButtonColor: "#22A00E"
    }).then((result) => {
        if (result.isConfirmed) {
            popupWithConfirmation(
            "question", 
            "Send payout slips?", 
            "This will send payout slips to all registered employees in this payroll. Are you sure you want to continue?", 
            () => {
            updateButtons(true);
            popupToast('info', WAIT_PAYSLIPS, null);
            google.script.run
                .withSuccessHandler(function(res) {
                if(res.success) { 
                    popupToast('success', res.message);
                } else {
                    popupSimple('error', res.message, "Are you sure this is a valid template? If yes, kindly contact your service provider");
                }
                updateButtons(false);
                })
                .sendPayslips(paginatedPayrolls[index].id, sheetIndex);
            },
            "Yes, send via email"
            );
        } else if (result.isDenied) {
            const category = categories.find(element => element.id == categoryValue).name;
            const inputOptions = users[category].reduce((acc, item) => {
                acc[item.id] = item.name;
                return acc;
            }, {});
            Swal.fire({
            title: "Select a Recipient",
            input: "select",
            inputOptions: inputOptions,
            inputPlaceholder: "Choose a recipient",
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonText: "Send",
            confirmButtonColor: "#1F5497",
            cancelButtonText: `<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>`
            }).then((result) => {
            if (result.isConfirmed) {
                popupToast("info", `Sending payslip via email`);
                google.script.run
                .withSuccessHandler(function(res) {
                if(res.success) { 
                    popupToast('success', res.message);
                } else {
                    popupSimple('error', res.message, "Are you sure this is a valid template? If yes, kindly contact your service provider");
                }
                updateButtons(false);
                })
                .sendPayslips(paginatedPayrolls[index].id, sheetIndex, result.value);
            }
            });
        }
    });
}

/* QUICK CREATE PAYROLL - Creates a new payroll using the same template as the current category */
function quickCreatePayroll() {
    // Check if category is selected
    if (!categoryValue || categories.length === 0) {
        popupSimple('warning', 'Please select a category first');
        return;
    }

    // Get current category
    const currentCategory = categories.find(cat => cat.id === categoryValue);
    if (!currentCategory) {
        popupSimple('error', 'Category not found');
        return;
    }

    // Check if templates are available
    if (!templates || templates.length === 0) {
        popupSimple('error', 'No templates available. Please try again later.');
        return;
    }

    // Try to find a matching template based on category name
    // First, try to match by category name
    let selectedTemplate = templates.find(template => 
        template.name && currentCategory.name && 
        template.name.toLowerCase().includes(currentCategory.name.toLowerCase())
    );

    // If no match found, try to use category's template_id if it exists and is valid
    if (!selectedTemplate && currentCategory.template_id) {
        selectedTemplate = templates.find(template => template.id === currentCategory.template_id);
    }

    // If still no match, use the first available template
    if (!selectedTemplate) {
        selectedTemplate = templates[0];
    }

    if (!selectedTemplate || !selectedTemplate.id) {
        popupSimple('error', 'No valid template found. Please use the full create option.');
        return;
    }

    // Get frequency from settings (default to 2 if not set)
    const frequency = settings.frequency || 2;

    // Prompt for payroll name
    Swal.fire({
        title: 'Create New Payroll',
        html: `
            <div class="input-row" style="margin-bottom: 16px;">
                <label for="quick-payroll-name" class="swal2-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Payroll Name:</label>
                <input id="quick-payroll-name" class="swal2-input" placeholder="Enter payroll name" style="width: 100%; padding: 12px; border: 2px solid #D1D5DB; border-radius: 8px;">
            </div>
            <div style="margin-top: 12px; font-size: 14px; color: #6B7280;">
                <strong>Template:</strong> ${selectedTemplate.name || 'Default Template'}
            </div>
        `,
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: "#1F5497",
        confirmButtonText: "CREATE",
        cancelButtonText: "CANCEL",
        preConfirm: () => {
            const nameInput = document.getElementById('quick-payroll-name');
            if (!nameInput || !nameInput.value.trim()) {
                Swal.showValidationMessage("Please enter a payroll name!");
                return false;
            }
            return nameInput.value.trim();
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Create payroll using the selected template
            createNewPayroll(result.value, frequency, selectedTemplate.id);
        }
    });
}

/* PAYROLL FUNCTIONS */
function createNewPayroll(value, weeks, templateId = null) {
    popupToast('info', WAIT_ADD_PAYROLL, null);

    const params = { 
        path: 'payrolls/create', 
        name: value, 
        folderID: categoryValue, 
        weeks: weeks 
    };
    
    if (templateId) {
        params.templateId = templateId;
    }

    callApi({
        method: 'POST',
        params: params,
    }).then(res => {
        if(!res.success) {
            popupSimple('error', "Oops! Can't create file", res.message);
            return;
        }
        popupToast('success', res.message);

        const data = JSON.parse(res.data);
        payrolls.unshift(data);
        pgntrEnd = payrolls.length - 1;

        searchedPayrolls = payrolls;
        paginatedPayrolls = payrolls.slice(0, pgntrInterval);

        updatePaginator();
    });
}

function syncPayroll(index) {
    popupWithConfirmation(
        "question",
        "Sync Payroll?",
        "Are you sure you want to sync this payroll?",
        function () {
            popupToast("info", "Syncing file...", null);

            callApi({
                method: 'POST',
                params: { path: 'payrolls/sync', id: payrolls[index]?.id, category: categories.find(element => element.id == categoryValue).name }
            }).then(res => {      
                popupToast('success', res.message);
            });
        },
        "Yes, sync payroll"
    );
}

function editPayroll(event) {
    const index = event.target.getAttribute("data-index");

    popupWithInput(
        "question",
        "Rename Payroll?",
        "Enter Payroll Name",
        function (name) {
            if (!name.trim()) return; // Prevent empty input
            popupToast("info", "Renaming file...", null);

            callApi({
                method: 'POST',
                params: { path: 'payrolls/rename', id: payrolls[index]?.id, name: name }
            }).then(res => {       
                popupToast('success', res.message);
                payrolls[index].name = name;

                searchedPayrolls = payrolls;
                paginatedPayrolls = searchedPayrolls.slice(0, pgntrInterval);
                updatePaginator();
            });
        }
    );
}

function deletePayroll(event) {
    const index = event.target.getAttribute("payroll-index");
    
    popupWithConfirmation(
        "question",
        "Delete Payroll?",
        "Are you sure you want to delete this payroll?",
        function () {
            popupToast("info", "Deleting file...", null);
            callApi({
                method: 'POST',
                params: { path: 'payrolls/delete', id: payrolls[index]?.id }
            }).then(res => {  
                if(res.success) {
                    popupToast('success', res.message);
                    payrolls.splice(index, 1);
            
                    searchedPayrolls = payrolls;
                    paginatedPayrolls = searchedPayrolls.slice(0, pgntrInterval);
                    updatePaginator();
                } else {
                    popupSimple('error', res.message);
                }
            });
        },
        "Yes, delete payroll",
        true
    );
}

/* CATEGORY */
function checkCategoryName(name) {
    name = name.trim().toLowerCase();

    const isDuplicate = categories.some(element => name === element.name.trim().toLowerCase());

    if (isDuplicate) {
        popupSimple('error', "Duplicate name", "Duplicate name for category detected!");
        return false;
    }

    return true;
}

function createNewCategory(value) {
    popupToast('info', WAIT_NEW_CATEGORY, null);
    google.script.run
        .withSuccessHandler(function(res) {
        popupToast('success', res.message);
        const data = JSON.parse(res.data)

        categories.unshift(data);
        payrolls = []; /* new category has no data */
        searchedPayrolls = payrolls;
        updateCategories();
        updatePaginator();
        })
        .createNewCategory(value);
}

function editCategory() {    
    popupWithInput(
        "question",
        "Rename Category?",
        "Enter Category Name",
        function (name) {
        if(!checkCategoryName(name)) return;

        popupToast("info", "Renaming category...", null);

        google.script.run
            .withSuccessHandler(function (res) {
            popupToast('success', res.message);
            category[index].name = name;

            // Update tab text content
            const tab = document.querySelector(`.tabs-container > .tab-container > .tab[data-index="${index}"]`);
            if (tab) tab.textContent = name;
            })
            .renameFolder(categoryValue, name);
        }
    );
}

function deleteCategory() {
    popupWithConfirmation(
        "question",
        "Delete Category?",
        "Are you sure you want to delete this category? All payrolls within this category will be deleted!",
        function () {
        popupToast("info", "Deleting category...", null);
        
        google.script.run
            .withSuccessHandler(function (res) {
            if(res.success) {
                popupToast('success', res.message);
                categories = categories.filter(element => element.id != categoryValue);
                payrolls = JSON.parse(res.data);

                searchedPayrolls = payrolls;
                paginatedPayrolls = searchedPayrolls.slice(0, pgntrInterval);
                updateCategories();
                updatePayrolls();
                updatePaginator();
            } else {
                popupSimple('error', 'Oops!', res.message);
            }
            })
            .deleteFolder(categoryValue); // Use optional chaining to prevent errors
        },
        "Yes, delete category",
        true
    );
}

function changePeriod(event, payrollIndex) {
    const data = paginatedPayrolls[payrollIndex].sheets[event.target.value];
    const container = event.target.closest(".card-content");

    let rows = container.querySelectorAll(".row-content");
    rows[0].textContent = data.start;
    rows[1].textContent = data.end;
    rows[2].textContent = data.disbursement;

    container.querySelector(".email-btn").removeEventListener("click", sendPayslips);    
    container.querySelector(".email-btn").addEventListener("click", () => sendPayslips(payrollIndex, event.target.value));
    }

    /* AVOID MULTIPLE BUTTON CLICKS FOR HEAVY REQUESTS */
    function updateButtons(isDisabled) {
    document.querySelectorAll(".email-btn").forEach(button => {
        button.disabled = isDisabled;
    });
}

/* INIT */
function updateCategories() {
    const container = document.querySelector(".tabs-container");
    
    // Create a flex container for the entire row
    const rowContainer = document.createElement("div");
    rowContainer.classList.add("flex", "items-center", "justify-between", "w-full");
    
    // Create container for category select and paginator select (left side)
    const categoryContainer = document.createElement("div");
    categoryContainer.classList.add("flex-1", "h-full", "payroll-category-select", "flex", "items-center", "gap-2");
    
    // Create category select
    const categorySelect = document.createElement("select");
    categorySelect.classList.add("category-select");
    
    for(let i = 0; i < categories.length; i++) {
        const catOption = document.createElement("option");
        catOption.value = categories[i].id;
        catOption.textContent = categories[i].name;
        categorySelect.append(catOption);

        if(i == 0) categoryValue = categories[i].id;
    }
    categorySelect.addEventListener("change", switchCategory);
    
    // Create pagination select
    const paginatorSelect = document.createElement("select");
    paginatorSelect.addEventListener("change", changePaginator);
    paginatorSelect.classList.add("pagination-select");
    
    // Add options to paginator select
    const paginatorOptions = [4, 8, 12];
    paginatorOptions.forEach(value => {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = value;
        paginatorSelect.append(option);
    });
    
    // Create container for search and settings (right side)
    const rightContainer = document.createElement("div");
    rightContainer.classList.add("flex", "items-center", "gap-2");
    
    // Create search box wrapper div with position relative
    const searchBoxDiv = document.createElement("div");
    searchBoxDiv.classList.add("search-box", "flex", "items-center", "relative");
    
    // Create input element
    const searchInput = document.createElement("input");
    searchInput.placeholder = "Search..";
    searchInput.classList.add("input-field", "search", "pl-8"); // Added padding-left for icon space
    searchInput.addEventListener("keyup", search);
    
    // Create search icon wrapper with absolute positioning
    const searchIcon = document.createElement("div");
    searchIcon.innerHTML = SEARCH_SVG;
    searchIcon.classList.add("absolute", "left-2"); // Position the icon inside the input
    searchIcon.style.pointerEvents = "none"; // Make the icon not interfere with input focus
    
    // Create settings button
    const settings = document.createElement("button");
    settings.innerHTML = SETTINGS_SVG;
    settings.classList.add("settings-btn");
    settings.addEventListener("click", () => togglePayrollSettings(true));
    
    // Assemble the search box
    searchBoxDiv.append(searchInput, searchIcon);
    
    // Put search then settings in the right container
    rightContainer.append(searchBoxDiv, settings);
    
    // Put the category select and paginator select in the left container
    categoryContainer.append(categorySelect, paginatorSelect);
    
    // Add both containers to the row
    rowContainer.append(categoryContainer, rightContainer);
    
    // Clear the main container and add the row
    container.innerHTML = "";
    container.append(rowContainer);
}

function switchCategory(event) {
    const selection = event.target.value;
    // const action = document.getElementById("category-action");
    categoryValue = selection;
    payrolls = [];
    searchedPayrolls = [];
    updatePaginator();
    // document.getElementById("create-card").style.display = "none";
    event.target.disabled = true;
    // action.disabled = true;

    callApi({
        method: 'GET',
        params: { path: 'payrolls', id: categoryValue }
    }).then(res => {
        if (!res || !res.success || !res.data) {
            console.error('Failed to fetch payrolls:', res);
            event.target.disabled = false;
            return;
        }
        
        try {
            const data = JSON.parse(res.data);
            payrolls = Array.isArray(data) ? data : [];
            searchedPayrolls = payrolls;
            event.target.disabled = false;
            // action.disabled = false;
            updatePaginator();
        } catch (error) {
            console.error('Error parsing payroll data:', error);
            event.target.disabled = false;
        }
    }).catch(error => {
        console.error('Error fetching payrolls:', error);
        event.target.disabled = false;
    });
}

    function categoryAction(event) {
    switch(event.target.value) {
        case "add":
        openPopup("category");
        break;

        case "rename":
        editCategory();
        break;

        case "delete":
        deleteCategory();
        break;
    }

    event.target.value = "";
}

// REPLACE your old updatePayrolls function with this one:

function updatePayrolls() {
    let container = document.getElementById("payroll-container");
    if (container) {
        container.querySelectorAll("*").forEach(element => element.remove());
    }

    for(let i = 0; i < paginatedPayrolls.length; i++) {
        let card = document.createElement("div");
        let headerContainer = document.createElement("div");
        let header = document.createElement("div");
        let kebabMenuContainer = document.createElement("div");
        let kebabButton = document.createElement("button");
        let kebabDropdown = document.createElement("div");
        let content = document.createElement("div");
        let row2 = document.createElement("div");
        let rowHeader2 = document.createElement("div");
        let rowContent2 = document.createElement("div");
        let row3 = document.createElement("div");
        let rowHeader3 = document.createElement("div");
        let rowContent3 = document.createElement("div");
        let row4 = document.createElement("div");
        let rowHeader4 = document.createElement("div");
        let rowContent4 = document.createElement("div");
        let row5 = document.createElement("div");
        let sendEmailBtn = document.createElement("button");
        let openBtn = document.createElement("a");

        // Add classes
        card.classList.add("card");
        headerContainer.classList.add("payroll-card-header-container");
        header.classList.add("payroll-card-header");
        kebabMenuContainer.classList.add("kebab-menu-container");
        kebabButton.classList.add("kebab-button");
        kebabDropdown.classList.add("kebab-dropdown");
        content.classList.add("card-content");
        
        const elements = [
            [row2, "row"], [row3, "row"], [row4, "row"], 
            [rowHeader2, "row-header"], [rowHeader3, "row-header"], [rowHeader4, "row-header"], 
            [rowContent2, "row-content"], [rowContent3, "row-content"], [rowContent4, "row-content"], 
            [row5, "btn-row"], [sendEmailBtn, "email-btn"], [openBtn, "open-btn"]
        ];

        elements.forEach(([element, className]) => element.classList.add(className));

        // Set up header section with flex layout
        headerContainer.style.display = "flex";
        headerContainer.style.justifyContent = "space-between";
        headerContainer.style.alignItems = "center";
        
        // Set up header content
        header.textContent = paginatedPayrolls[i].name;
        header.setAttribute("data-index", i);
        header.disabled = true;
        
        // --- START KEBAB FIX ---

        // Create kebab menu button (three vertical dots)
        kebabButton.innerHTML = `
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
        `;
        // Use the toggleKebabMenu function you added
        kebabButton.addEventListener("click", toggleKebabMenu);
        
        // --- This is the dropdown menu ---
        kebabDropdown.style.visibility = "hidden";
        kebabDropdown.style.opacity = "0";
        kebabDropdown.style.transform = "translateY(-20px)";

        // Create edit dropdown item (as <a> tag)
        const editItem = document.createElement("a");
        editItem.classList.add("kebab-dropdown-item");
        editItem.innerHTML = `${EDIT_SVG} <span>Edit</span>`;
        editItem.setAttribute("data-index", i); // Set index for the edit function
        // Add listener *to the item itself*
        editItem.addEventListener("click", editPayroll);
        kebabDropdown.appendChild(editItem);
        
        // Create delete dropdown item (as <a> tag)
        const deleteItem = document.createElement("a");
        deleteItem.classList.add("kebab-dropdown-item", "delete"); // Added 'delete' class for red styling
        deleteItem.innerHTML = `${TRASH_SVG} <span>Delete</span>`;
        deleteItem.setAttribute("payroll-index", i); // Set index for the delete function
        // Add listener *to the item itself*
        deleteItem.addEventListener("click", deletePayroll);
        kebabDropdown.appendChild(deleteItem);
        
        // Create sync dropdown item (as <a> tag)
        const syncItem = document.createElement("a");
        syncItem.classList.add("kebab-dropdown-item");
        syncItem.innerHTML = `
            <svg  xmlns="http://www.w3.org/2000/svg"  width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-refresh"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            <span>Sync</span>
        `;
        syncItem.addEventListener("click", () => {
            syncPayroll(i);
        });
        kebabDropdown.appendChild(syncItem);
        
        // Assemble kebab menu
        kebabMenuContainer.appendChild(kebabButton);
        kebabMenuContainer.appendChild(kebabDropdown);
        
        // --- END KEBAB FIX ---
        
        headerContainer.append(header, kebabMenuContainer);
        
        // Set up card content - same as before
        rowHeader2.textContent = "Date last modified:";
        rowContent2.textContent = paginatedPayrolls[i].modifiedTime;
        row2.append(rowHeader2, rowContent2);
        content.append(row2);
        
        rowHeader3.textContent = "Date created:";
        rowContent3.textContent = paginatedPayrolls[i].createdTime;
        row3.append(rowHeader3, rowContent3);
        content.append(row3);
        
        rowHeader4.textContent = "Owner:";
        rowContent4.textContent = paginatedPayrolls[i].ownerName;
        row4.append(rowHeader4, rowContent4);
        content.append(row4);

        sendEmailBtn.textContent = "Send";
        sendEmailBtn.addEventListener("click", () => sendPayslips(i, 0));
        openBtn.textContent = "Open";
        openBtn.href = paginatedPayrolls[i].url;
        openBtn.target = "_blank";
        row5.append(sendEmailBtn, openBtn);
        content.append(row5);

        card.append(headerContainer, content);
        container.append(card);
    }
}


/* Paginator functions */
function paginatorNext() {
    pgntrIndex += pgntrInterval;

    if(pgntrIndex >= searchedPayrolls.length) pgntrIndex -= pgntrInterval;
    updatePaginator();
}

function paginatorBack() {
    pgntrIndex -= pgntrInterval;

    if(pgntrIndex < 0) pgntrIndex = 0;
    updatePaginator();
}

function first() {
    pgntrIndex = 0;
    updatePaginator();
}

function last() {
    pgntrIndex = searchedPayrolls.length - (searchedPayrolls.length % pgntrInterval);
    if(searchedPayrolls.length % pgntrInterval == 0) pgntrIndex -= pgntrInterval;
    updatePaginator();
}

function changePaginator(event) {
    pgntrIndex = 0;
    pgntrInterval = Number(event.target.value);
    updatePaginator();
}

function updatePaginator() {
    const label = document.getElementById("payroll-paginator-label");
    let end = pgntrIndex + pgntrInterval;
    if(end > searchedPayrolls.length) end = searchedPayrolls.length;
    let start = 0;
    if(searchedPayrolls.length > 0) start = pgntrIndex + 1;
    label.textContent = `Showing ${start} - ${end} items of ${searchedPayrolls.length}`;
    paginatedPayrolls = searchedPayrolls.slice(pgntrIndex, pgntrIndex + pgntrInterval);
    updatePayrolls();
}

function toggleKebabMenu(event) {
    // Stop the click from bubbling up (e.g., clicking the card)
    event.stopPropagation();
    
    const button = event.currentTarget;
    const dropdown = button.nextElementSibling;

    // Close all other open kebab menus
    document.querySelectorAll('.kebab-dropdown').forEach(menu => {
        if (menu !== dropdown) {
            menu.style.visibility = 'hidden';
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(-10px)';
        }
    });

    // Toggle the clicked menu
    dropdown.style.visibility = dropdown.style.visibility === 'hidden' ? 'visible' : 'hidden';
    dropdown.style.opacity = dropdown.style.opacity === '0' ? '1' : '0';
    dropdown.style.transform = dropdown.style.transform === 'translateY(-10px)' ? 'translateY(10px)' : 'translateY(-10px)';
}

// Add a global click listener to close menus when clicking anywhere else
window.addEventListener('click', (e) => {
    // Check if the click was outside a kebab menu
    if (!e.target.closest('.kebab-menu-container')) {
        document.querySelectorAll('.kebab-dropdown').forEach(menu => {
            menu.style.visibility = 'hidden';
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(-10px)';
        });
    }
});