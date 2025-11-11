// Workforce Records JavaScript Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchUser');
    const departmentFilter = document.getElementById('departmentFilter');
    const userCards = document.querySelectorAll('.user-card');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const departmentValue = departmentFilter.value;
        
        userCards.forEach(card => {
            const userName = card.getAttribute('data-name').toLowerCase();
            const userDept = card.getAttribute('data-department');
            
            const matchesSearch = userName.includes(searchTerm);
            const matchesDepartment = departmentValue === 'all' || userDept === departmentValue;
            
            card.style.display = (matchesSearch && matchesDepartment) ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('keyup', filterUsers);
    departmentFilter.addEventListener('change', filterUsers);
    
    // Close all dropdowns when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
    
    // Initialize star ratings
    initializeStarRatings();
});

// Toggle dropdown menu
function toggleKebabDropdown(element) {
    // Close all other dropdowns first
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== element.nextElementSibling) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle the clicked dropdown
    const dropdown = element.nextElementSibling;
    dropdown.classList.toggle('hidden');
    
    // Prevent the click from closing the dropdown immediately
    event.stopPropagation();
}

// User rates modal functions
function openRatesModal(userId, userName) {
    // Set the user ID and name in the modal
    document.getElementById('rateUserId').value = userId;
    document.getElementById('ratesModalTitle').textContent = `Rates for ${userName}`;
    
    // Reset form fields
    document.getElementById('hourlyRate').value = '';
    document.getElementById('dailyRate').value = '';
    document.getElementById('monthlyRate').value = '';
    
    // Fetch user rates from database
    fetchUserRates(userId);
    
    // Show the modal
    document.getElementById('userRatesModal').classList.remove('hidden');
}

function closeRatesModal() {
    document.getElementById('userRatesModal').classList.add('hidden');
}

function calculateRates(sourceField) {
    // Constants for calculations (assuming 8 working hours per day, 22 working days per month)
    const HOURS_PER_DAY = 8;
    const DAYS_PER_MONTH = 22;
    
    let hourlyRate = parseFloat(document.getElementById('hourlyRate').value) || 0;
    let dailyRate = parseFloat(document.getElementById('dailyRate').value) || 0;
    let monthlyRate = parseFloat(document.getElementById('monthlyRate').value) || 0;
    
    // Calculate based on which field was changed
    switch(sourceField) {
        case 'hourly':
            // Calculate daily and monthly from hourly
            dailyRate = hourlyRate * HOURS_PER_DAY;
            monthlyRate = dailyRate * DAYS_PER_MONTH;
            break;
        case 'daily':
            // Calculate hourly and monthly from daily
            hourlyRate = dailyRate / HOURS_PER_DAY;
            monthlyRate = dailyRate * DAYS_PER_MONTH;
            break;
        case 'monthly':
            // Calculate daily and hourly from monthly
            dailyRate = monthlyRate / DAYS_PER_MONTH;
            hourlyRate = dailyRate / HOURS_PER_DAY;
            break;
    }
    
    // Update the input fields with calculated values (rounded to 2 decimal places)
    document.getElementById('hourlyRate').value = hourlyRate.toFixed(2);
    document.getElementById('dailyRate').value = dailyRate.toFixed(2);
    document.getElementById('monthlyRate').value = monthlyRate.toFixed(2);
}

function fetchUserRates(userId) {
    // Fetch user rates from the server
    fetch(`/api/workforce-records/user-rates?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the form with the user's rates
                document.getElementById('hourlyRate').value = data.rates.hourly_rate;
                document.getElementById('dailyRate').value = data.rates.daily_rate;
                document.getElementById('monthlyRate').value = data.rates.monthly_rate;
            } else {
                // If no rates found, set defaults
                document.getElementById('hourlyRate').value = '0.00';
                document.getElementById('dailyRate').value = '0.00';
                document.getElementById('monthlyRate').value = '0.00';
            }
        })
        .catch(error => {
            console.error('Error fetching user rates:', error);
            // Set default values if error occurs
            document.getElementById('hourlyRate').value = '0.00';
            document.getElementById('dailyRate').value = '0.00';
            document.getElementById('monthlyRate').value = '0.00';
        });
}

function saveUserRates() {
    const userId = document.getElementById('rateUserId').value;
    const hourlyRate = document.getElementById('hourlyRate').value;
    const dailyRate = document.getElementById('dailyRate').value;
    const monthlyRate = document.getElementById('monthlyRate').value;
    
    // Create form data
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('hourly_rate', hourlyRate);
    formData.append('daily_rate', dailyRate);
    formData.append('monthly_rate', monthlyRate);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Send data to server
    fetch('/api/workforce-records/user-rates', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('User rates updated successfully');
            closeRatesModal();
        } else {
            // Show error message
            alert('Error updating user rates: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving user rates:', error);
        alert('An error occurred while saving user rates');
    });
}

// User settings modal functions
function openSettingsModal(userId, userName) {
    // Set the user ID and name in the modal
    document.getElementById('settingsUserId').value = userId;
    document.getElementById('settingsModalTitle').textContent = `Settings for ${userName}`;
    
    // Reset form fields to default
    document.getElementById('userSettingsForm').reset();
    
    // Fetch user settings from database
    fetchUserSettings(userId);
    
    // Show the modal
    document.getElementById('userSettingsModal').classList.remove('hidden');
}

function closeSettingsModal() {
    document.getElementById('userSettingsModal').classList.add('hidden');
}

function fetchUserSettings(userId) {
    // Fetch user settings from the server
    fetch(`/api/workforce-records/user-settings?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate the form with the user's settings
                // For radio buttons
                const engagementStatus = document.querySelector(`input[name="engagement_status"][value="${data.settings.engagement_status}"]`);
                if (engagementStatus) engagementStatus.checked = true;
                
                const userType = document.querySelector(`input[name="user_type"][value="${data.settings.user_type}"]`);
                if (userType) userType.checked = true;
                
                const silStatus = document.querySelector(`input[name="sil_status"][value="${data.settings.sil_status}"]`);
                if (silStatus) silStatus.checked = true;
                
                // Update wage status options based on user type
                updateWageStatusOptions(data.settings.user_type);
                
                // Set wage status value after options are updated
                setTimeout(() => {
                    // For radio buttons (employee)
                    const wageStatusRadio = document.querySelector(`input[name="wage_type"][value="${data.settings.wage_type}"]`);
                    if (wageStatusRadio) {
                        wageStatusRadio.checked = true;
                    }
                    
                    // For dropdown (ISP)
                    const wageStatusSelect = document.querySelector(`select[name="wage_type"]`);
                    if (wageStatusSelect) {
                        wageStatusSelect.value = data.settings.wage_type;
                    }
                }, 100);
                
                // For select dropdown
                document.getElementById('userStatus').value = data.settings.user_status;
            } else {
                console.error('Error:', data.message);
                alert('Error loading settings: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching user settings:', error);
            alert('Could not load user settings. Please check the console for details.');
        });
}

function updateWageStatusOptions(userType) {
    const wageStatusContainer = document.getElementById('wageStatusContainer');
    const wageStatusLabel = document.getElementById('wageStatusLabel');
    
    if (!wageStatusContainer) {
        console.error('Wage status container not found');
        return;
    }
    
    let optionsHTML = '';
    
    if (userType === 'employee') {
        // Change label to "Wage Status"
        if (wageStatusLabel) {
            wageStatusLabel.textContent = 'Wage Status';
        }
        
        optionsHTML = `
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="wage_type" value="mwe" class="form-radio text-blue-600">
                    <span class="ml-2">MWE (Minimum Wage Earner)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="wage_type" value="non_mwe" class="form-radio text-blue-600">
                    <span class="ml-2">Non-MWE</span>
                </label>
            </div>
        `;
    } else if (userType === 'isp') {
        // Change label to "Earning Status"
        if (wageStatusLabel) {
            wageStatusLabel.textContent = 'Earning Status';
        }
        
        optionsHTML = `
            <select name="wage_type" class="w-full px-3 py-2 border rounded-md">
                <option value="">Select Withholding Tax Type</option>
                <option value="prof_vat">Professional Fees (VAT Registered) - 10%</option>
                <option value="prof_non_vat">Professional Fees (Non-VAT Registered) - 15%</option>
                <option value="rental">Rental Fees - 5%</option>
                <option value="services">Income Payments to Suppliers of Services - 2%</option>
                <option value="goods">Income Payments to Supplier of Goods - 1%</option>
                <option value="commissions">Commissions - 10%</option>
                <option value="film_distributors">Payments to Film Owners/Distributors (Non-Resident) - 5%</option>
                <option value="contractors">Income Payments to Certain Contractors - 2%</option>
                <option value="advertising">Advertising Services - 2%</option>
                <option value="insurance_agents">Payments to Insurance Agents - 10%</option>
                <option value="gpp_partners">Income Payments to Partners of General Professional Partnerships - 10%/15%</option>
                <option value="estates_trusts">Income Distribution to Beneficiaries of Estates and Trusts - 15%</option>
                <option value="govt_goods">Sale or Lease of Properties to Government on Goods - 1%</option>
                <option value="govt_services">Sale or Lease of Properties to Government on Services - 2%</option>
            </select>
        `;
    }
    
    wageStatusContainer.innerHTML = optionsHTML;
}

function saveUserSettings() {
    const userId = document.getElementById('settingsUserId').value;
    const form = document.getElementById('userSettingsForm');
    
    // Create form data from all form elements
    const formData = new FormData(form);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Debug: Log form data
    console.log('Form Data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Validate wage_type is selected for ISP
    const userType = formData.get('user_type');
    const wageType = formData.get('wage_type');
    
    if (userType === 'isp' && !wageType) {
        alert('Please select a withholding tax type for ISP');
        return;
    }
    
    // Send data to server
    fetch('/api/workforce-records/user-settings', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            // Show success message
            alert('User settings updated successfully');
            closeSettingsModal();
            // Optionally reload the page to reflect changes
            // location.reload();
        } else {
            // Show error message
            alert('Error updating user settings: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving user settings:', error);
        alert('An error occurred while saving user settings. Please check the console for details.');
    });
}

function openUserFilesModal(userId, userName, userEmail) {
    // Set the user name in the modal title
    document.getElementById('filesModalTitle').textContent = `Files for ${userName}`;
    
    // Reset modal state
    document.getElementById('filesLoading').classList.remove('hidden');
    document.getElementById('filesContent').classList.add('hidden');
    document.getElementById('filesError').classList.add('hidden');
    document.getElementById('filesList').innerHTML = '';
    document.getElementById('noFilesMessage').classList.add('hidden');
    
    // Show the modal
    document.getElementById('userFilesModal').classList.remove('hidden');
    
    // Fetch user files data
    fetchUserFiles(userEmail);
}

function closeFilesModal() {
    document.getElementById('userFilesModal').classList.add('hidden');
}

// Updated fetchUserFiles function for the modal
function fetchUserFiles(userEmail) {
    // Encode the email to safely pass it in the URL
    const encodedEmail = encodeURIComponent(userEmail);
    
    // For debugging
    console.log('Fetching files for email:', userEmail);
    
    // Fetch user files from the server
    fetch(`/api/workforce-records/user-files?email=${encodedEmail}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // For debugging
            
            // Hide loading indicator
            document.getElementById('filesLoading').classList.add('hidden');
            document.getElementById('filesContent').classList.remove('hidden');
            
            if (data.success && data.files) {
                const filesList = document.getElementById('filesList');
                let hasFiles = false;
                
                // Define file types and labels
                const fileTypes = [
                    { key: 'resume', label: 'Resume', icon: 'ti-file-text' },
                    { key: 'nbi', label: 'NBI/Police Clearance', icon: 'ti-file-check' },
                    { key: 'license', label: 'License', icon: 'ti-license' },
                    { key: 'health', label: 'Health Clearance', icon: 'ti-heart' }
                ];
                
                // Generate file link elements
                fileTypes.forEach(fileType => {
                    let fileLink = data.files[fileType.key];
                    
                    if (fileLink && fileLink.trim() !== '') {
                        hasFiles = true;
                        
                        // Ensure Google Drive links are properly formatted for direct access
                        if (fileLink.includes('drive.google.com/open')) {
                            // Extract the ID from the "open" link format
                            const idMatch = fileLink.match(/id=([^&]+)/);
                            if (idMatch && idMatch[1]) {
                                // Convert to direct view link
                                fileLink = `https://drive.google.com/file/d/${idMatch[1]}/view`;
                            }
                        } else if (fileLink.includes('drive.google.com/file/d/')) {
                            // Ensure the link ends with /view for proper viewing
                            if (!fileLink.includes('/view')) {
                                fileLink = fileLink.replace(/\/[^\/]*$/, '/view');
                            }
                        }
                        
                        // Create file link element
                        const fileItem = document.createElement('div');
                        fileItem.className = 'file-item bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition';
                        
                        fileItem.innerHTML = `
                            <a href="${fileLink}" target="_blank" class="flex items-center">
                                <div class="file-icon w-10 h-10 rounded-full flex items-center justify-center bg-blue-100 text-blue-600 mr-3">
                                    <i class="${fileType.icon}"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">${fileType.label}</h4>
                                    <p class="text-xs text-gray-500 truncate">View Document</p>
                                </div>
                                <div class="action">
                                    <i class="ti ti-external-link text-blue-600"></i>
                                </div>
                            </a>
                        `;
                        
                        filesList.appendChild(fileItem);
                    }
                });
                
                // Show "no files" message if no files were found
                if (!hasFiles) {
                    document.getElementById('noFilesMessage').classList.remove('hidden');
                }
            } else {
                // Show no files message
                document.getElementById('noFilesMessage').classList.remove('hidden');
                console.error('Error or no files in response:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching user files:', error);
            document.getElementById('filesLoading').classList.add('hidden');
            document.getElementById('filesError').classList.remove('hidden');
        });
}

// Fringe Benefits Modal Functions
function openFringeBenefitsModal(userId, userName) {
    // Set the user ID and name in the modal
    document.getElementById('fringeUserId').value = userId;
    document.getElementById('fringeModalTitle').textContent = `Fringe Benefits for ${userName}`;
    
    // Reset all form fields
    document.getElementById('fringeBenefitsForm').reset();
    
    // Fetch user's fringe benefits data
    fetchFringeBenefits(userId);
    
    // Show the modal
    document.getElementById('fringeBenefitsModal').classList.remove('hidden');
}

function closeFringeBenefitsModal() {
    document.getElementById('fringeBenefitsModal').classList.add('hidden');
}

function fetchFringeBenefits(userId) {
    // Fetch fringe benefits data from the server
    fetch(`/api/workforce-records/fringe-benefits?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate the form with the user's fringe benefits data
                const benefits = data.benefits;
                document.getElementById('hazardPay').value = benefits.hazard_pay || '0.00';
                document.getElementById('fixedRepAllowance').value = benefits.fixed_representation_allowance || '0.00';
                document.getElementById('fixedTransAllowance').value = benefits.fixed_transportation_allowance || '0.00';
                document.getElementById('fixedHousingAllowance').value = benefits.fixed_housing_allowance || '0.00';
                document.getElementById('vehicleAllowance').value = benefits.vehicle_allowance || '0.00';
                document.getElementById('educationalAssistance').value = benefits.educational_assistance || '0.00';
                document.getElementById('medicalAssistance').value = benefits.medical_assistance || '0.00';
                document.getElementById('insurance').value = benefits.insurance || '0.00';
                document.getElementById('membership').value = benefits.membership || '0.00';
                document.getElementById('householdPersonnel').value = benefits.household_personnel || '0.00';
                document.getElementById('vacationExpense').value = benefits.vacation_expense || '0.00';
                document.getElementById('travelExpense').value = benefits.travel_expense || '0.00';
                document.getElementById('commissions').value = benefits.commissions || '0.00';
                document.getElementById('profitSharing').value = benefits.profit_sharing || '0.00';
                document.getElementById('fees').value = benefits.fees || '0.00';
                document.getElementById('totalTaxable13').value = benefits.total_taxable_13 || '0.00';
                document.getElementById('otherTaxable').value = benefits.other_taxable || '0.00';
                document.getElementById('totalTaxableBenefits').value = benefits.total_taxable_benefits || '0.00';
                
                // Calculate total benefits after populating fields
                calculateTotalBenefits();
            } else {
                // If no data found or error, set all fields to 0
                resetFringeBenefitsForm();
            }
        })
        .catch(error => {
            console.error('Error fetching fringe benefits:', error);
            resetFringeBenefitsForm();
        });
}

function resetFringeBenefitsForm() {
    // Set all numeric inputs to 0.00
    const inputs = document.getElementById('fringeBenefitsForm').querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        input.value = '0.00';
    });
    document.getElementById('totalTaxableBenefits').value = '0.00';
}

function calculateTotalBenefits() {
    // Get values from all input fields except hazard_pay
    const fixedRepAllowance = parseFloat(document.getElementById('fixedRepAllowance').value) || 0;
    const fixedTransAllowance = parseFloat(document.getElementById('fixedTransAllowance').value) || 0;
    const fixedHousingAllowance = parseFloat(document.getElementById('fixedHousingAllowance').value) || 0;
    const vehicleAllowance = parseFloat(document.getElementById('vehicleAllowance').value) || 0;
    const educationalAssistance = parseFloat(document.getElementById('educationalAssistance').value) || 0;
    const medicalAssistance = parseFloat(document.getElementById('medicalAssistance').value) || 0;
    const insurance = parseFloat(document.getElementById('insurance').value) || 0;
    const membership = parseFloat(document.getElementById('membership').value) || 0;
    const householdPersonnel = parseFloat(document.getElementById('householdPersonnel').value) || 0;
    const vacationExpense = parseFloat(document.getElementById('vacationExpense').value) || 0;
    const travelExpense = parseFloat(document.getElementById('travelExpense').value) || 0;
    const commissions = parseFloat(document.getElementById('commissions').value) || 0;
    const profitSharing = parseFloat(document.getElementById('profitSharing').value) || 0;
    const fees = parseFloat(document.getElementById('fees').value) || 0;
    const totalTaxable13 = parseFloat(document.getElementById('totalTaxable13').value) || 0;
    const otherTaxable = parseFloat(document.getElementById('otherTaxable').value) || 0;
    
    // Calculate total taxable benefits (sum of all fields except hazard_pay)
    const totalTaxableBenefits = 
        fixedRepAllowance + 
        fixedTransAllowance + 
        fixedHousingAllowance + 
        vehicleAllowance + 
        educationalAssistance + 
        medicalAssistance + 
        insurance + 
        membership + 
        householdPersonnel + 
        vacationExpense + 
        travelExpense + 
        commissions + 
        profitSharing + 
        fees + 
        totalTaxable13 + 
        otherTaxable;
    
    // Update the total taxable benefits field
    document.getElementById('totalTaxableBenefits').value = totalTaxableBenefits.toFixed(2);
}

function saveFringeBenefits() {
    const form = document.getElementById('fringeBenefitsForm');
    const formData = new FormData(form);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Send data to server
    fetch('/api/workforce-records/fringe-benefits', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Fringe benefits updated successfully');
            closeFringeBenefitsModal();
        } else {
            // Show error message
            alert('Error updating fringe benefits: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving fringe benefits:', error);
        alert('An error occurred while saving fringe benefits');
    });
}

// De Minimis Benefits Modal Functions
function openDeMinimisModal(userId, userName) {
    // Set the user ID and name in the modal
    document.getElementById('deminimisUserId').value = userId;
    document.getElementById('deminimisModalTitle').textContent = `De Minimis Benefits for ${userName}`;
    
    // Reset all form fields
    document.getElementById('deminimisForm').reset();
    
    // Fetch user's de minimis benefits data
    fetchDeMinimisData(userId);
    
    // Show the modal
    document.getElementById('deMinimisModal').classList.remove('hidden');
}

function closeDeMinimisModal() {
    document.getElementById('deMinimisModal').classList.add('hidden');
}

function fetchDeMinimisData(userId) {
    // Fetch de minimis benefits data from the server
    fetch(`/api/workforce-records/deminimis-benefits?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate the form with the user's de minimis benefits data
                const benefits = data.benefits;
                document.getElementById('riceSubsidy').value = benefits.rice_subsidy || '0.00';
                document.getElementById('mealAllowance').value = benefits.meal_allowance || '0.00';
                document.getElementById('uniformClothing').value = benefits.uniform_clothing || '0.00';
                document.getElementById('laundryAllowance').value = benefits.laundry_allowance || '0.00';
                document.getElementById('medicalAllowance').value = benefits.medical_allowance || '0.00';
                document.getElementById('collectiveBargaining').value = benefits.collective_bargaining_agreement || '0.00';
                document.getElementById('totalNonTaxable13').value = benefits.total_non_taxable_13 || '0.00';
                document.getElementById('serviceIncentiveLeave').value = benefits.service_incentive_leave || '0.00';
                document.getElementById('paidTimeOff').value = benefits.paid_time_off || '0.00';
                document.getElementById('otherAllowances').value = benefits.other_allowances || '0.00';
                document.getElementById('totalNonTaxableBenefits').value = benefits.total_non_taxable_benefits || '0.00';
                
                // Calculate total benefits after populating fields
                calculateTotalDeMinimis();
            } else {
                // If no data found or error, set all fields to 0
                resetDeMinimisForm();
            }
        })
        .catch(error => {
            console.error('Error fetching de minimis benefits:', error);
            resetDeMinimisForm();
        });
}

function resetDeMinimisForm() {
    // Set all numeric inputs to 0.00
    const inputs = document.getElementById('deminimisForm').querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        input.value = '0.00';
    });
    document.getElementById('totalNonTaxableBenefits').value = '0.00';
}

function calculateTotalDeMinimis() {
    // Get values from all input fields
    const riceSubsidy = parseFloat(document.getElementById('riceSubsidy').value) || 0;
    const mealAllowance = parseFloat(document.getElementById('mealAllowance').value) || 0;
    const uniformClothing = parseFloat(document.getElementById('uniformClothing').value) || 0;
    const laundryAllowance = parseFloat(document.getElementById('laundryAllowance').value) || 0;
    const medicalAllowance = parseFloat(document.getElementById('medicalAllowance').value) || 0;
    const collectiveBargaining = parseFloat(document.getElementById('collectiveBargaining').value) || 0;
    const totalNonTaxable13 = parseFloat(document.getElementById('totalNonTaxable13').value) || 0;
    const serviceIncentiveLeave = parseFloat(document.getElementById('serviceIncentiveLeave').value) || 0;
    const paidTimeOff = parseFloat(document.getElementById('paidTimeOff').value) || 0;
    const otherAllowances = parseFloat(document.getElementById('otherAllowances').value) || 0;
    
    // Calculate total non-taxable benefits (sum of all fields)
    const totalNonTaxableBenefits = 
        riceSubsidy + 
        mealAllowance + 
        uniformClothing + 
        laundryAllowance + 
        medicalAllowance + 
        collectiveBargaining + 
        totalNonTaxable13 + 
        serviceIncentiveLeave + 
        paidTimeOff + 
        otherAllowances;
    
    // Update the total non-taxable benefits field
    document.getElementById('totalNonTaxableBenefits').value = totalNonTaxableBenefits.toFixed(2);
}

function saveDeMinimis() {
    const form = document.getElementById('deminimisForm');
    const formData = new FormData(form);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Send data to server
    fetch('/api/workforce-records/deminimis-benefits', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('De Minimis benefits updated successfully');
            closeDeMinimisModal();
        } else {
            // Show error message
            alert('Error updating De Minimis benefits: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving De Minimis benefits:', error);
        alert('An error occurred while saving De Minimis benefits');
    });
}

// Performance evaluation modal functions
function openEvaluationModal(userId, userName, evaluationId = null) {
    // Set the user ID and name in the modal
    document.getElementById('evaluationUserId').value = userId;
    document.getElementById('evaluationId').value = evaluationId || '';
    document.getElementById('evaluationModalTitle').textContent = `Performance Evaluation - ${userName}`;
    
    // Reset form fields
    resetEvaluationForm();
    
    if (evaluationId) {
        // Fetch existing evaluation data
        fetchEvaluationData(evaluationId);
        document.getElementById('deleteBtn').style.display = 'inline-block';
    } else {
        // Set default date to today
        document.getElementById('evaluationDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('deleteBtn').style.display = 'none';
    }
    
    // Show the modal
    document.getElementById('performanceEvaluationModal').classList.remove('hidden');
}

function closeEvaluationModal() {
    document.getElementById('performanceEvaluationModal').classList.add('hidden');
    resetEvaluationForm();
}

function fetchEvaluationData(evaluationId) {
    // Fetch evaluation data from the server
    fetch(`/api/workforce-records/get-evaluation?evaluation_id=${evaluationId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.evaluation) {
                const eval = data.evaluation;
                
                // Populate form fields
                document.getElementById('evaluationDate').value = eval.evaluation_date;
                document.getElementById('evaluatorName').value = eval.evaluator_name || '';
                document.getElementById('evaluationType').value = eval.evaluation_type || '';
                document.getElementById('remarks').value = eval.remarks || '';
                
                // Populate all rating fields
                const criteria = [
                    'job_knowledge', 'productivity', 'work_quality', 'technical_skills', 'work_consistency',
                    'enthusiasm', 'cooperation', 'attitude', 'initiative', 'work_relations', 'creativity',
                    'punctuality', 'attendance', 'dependability', 'written_comm', 'verbal_comm',
                    'appearance', 'uniform', 'personal_hygiene', 'tidiness'
                ];
                
                criteria.forEach(criterion => {
                    const inputId = getInputId(criterion);
                    const hiddenInput = document.getElementById(inputId);
                    if (hiddenInput && eval[criterion] !== undefined) {
                        const value = parseInt(eval[criterion]) || 0;
                        hiddenInput.value = value;
                        
                        // Update star rating visual
                        const rating = document.querySelector(`.star-rating[data-field="${criterion}"]`);
                        if (rating) {
                            const stars = rating.querySelectorAll('.star');
                            stars.forEach((star, index) => {
                                if (index < value) {
                                    star.classList.add('active');
                                } else {
                                    star.classList.remove('active');
                                }
                            });
                        }
                    }
                });
                
                // Update overall rating display
                updateOverallRating();
            } else {
                console.error('Error:', data.message);
                alert('Error loading evaluation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error fetching evaluation data:', error);
            alert('Could not load evaluation data. Please check the console for details.');
        });
}

function resetEvaluationForm() {
    // Reset all form fields
    document.getElementById('performanceEvaluationForm').reset();
    
    // Reset all star ratings
    const starRatings = document.querySelectorAll('.star-rating');
    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('.star');
        stars.forEach(star => star.classList.remove('active'));
        const fieldName = rating.getAttribute('data-field');
        const hiddenInput = document.getElementById(getInputId(fieldName));
        if (hiddenInput) hiddenInput.value = '0';
    });
    
    updateOverallRating();
}

function getInputId(fieldName) {
    // Convert field names to camelCase input IDs
    const fieldMap = {
        'job_knowledge': 'jobKnowledge',
        'productivity': 'productivity',
        'work_quality': 'workQuality',
        'technical_skills': 'technicalSkills',
        'work_consistency': 'workConsistency',
        'enthusiasm': 'enthusiasm',
        'cooperation': 'cooperation',
        'attitude': 'attitude',
        'initiative': 'initiative',
        'work_relations': 'workRelations',
        'creativity': 'creativity',
        'punctuality': 'punctuality',
        'attendance': 'attendance',
        'dependability': 'dependability',
        'written_comm': 'writtenComm',
        'verbal_comm': 'verbalComm',
        'appearance': 'appearance',
        'uniform': 'uniform',
        'personal_hygiene': 'personalHygiene',
        'tidiness': 'tidiness'
    };
    return fieldMap[fieldName] || fieldName;
}

// Star rating functionality
function initializeStarRatings() {
    const starRatings = document.querySelectorAll('.star-rating');
    
    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('.star');
        const fieldName = rating.getAttribute('data-field');
        
        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                
                // Update visual stars
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
                
                // Update hidden input
                const hiddenInput = document.getElementById(getInputId(fieldName));
                if (hiddenInput) {
                    hiddenInput.value = value;
                }
                
                // Update overall rating
                updateOverallRating();
            });
            
            star.addEventListener('mouseenter', function() {
                const hoverValue = parseInt(this.getAttribute('data-value'));
                stars.forEach((s, i) => {
                    if (i < hoverValue) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });
        
        rating.addEventListener('mouseleave', function() {
            const currentValue = parseInt(document.getElementById(getInputId(fieldName)).value);
            stars.forEach((s, i) => {
                if (i < currentValue) {
                    s.style.color = '#f59e0b';
                } else {
                    s.style.color = '#d1d5db';
                }
            });
        });
    });
}

function updateOverallRating() {
    const criteria = [
        'jobKnowledge', 'productivity', 'workQuality', 'technicalSkills', 'workConsistency',
        'enthusiasm', 'cooperation', 'attitude', 'initiative', 'workRelations', 'creativity',
        'punctuality', 'attendance', 'dependability', 'writtenComm', 'verbalComm',
        'appearance', 'uniform', 'personalHygiene', 'tidiness'
    ];
    
    let totalScore = 0;
    let ratedCriteria = 0;
    
    criteria.forEach(criterion => {
        const value = parseInt(document.getElementById(criterion).value) || 0;
        if (value > 0) {
            totalScore += value;
            ratedCriteria++;
        }
    });
    
    const overallScore = ratedCriteria > 0 ? Math.round((totalScore / (ratedCriteria * 5)) * 100) : 0;
    document.getElementById('overallScore').textContent = overallScore;
    
    // Update grade
    let grade = 'Not Rated';
    if (overallScore >= 90) grade = 'Outstanding';
    else if (overallScore >= 80) grade = 'Above Standard';
    else if (overallScore >= 60) grade = 'Meets Expectation';
    else if (overallScore >= 40) grade = 'Reasonable';
    else if (overallScore > 0) grade = 'Deficient';
    
    document.getElementById('overallGrade').textContent = grade;
}

function saveEvaluation() {
    const evaluationId = document.getElementById('evaluationId').value;
    const userId = document.getElementById('evaluationUserId').value;
    const evaluationDate = document.getElementById('evaluationDate').value;
    const evaluatorName = document.getElementById('evaluatorName').value;
    const evaluationType = document.getElementById('evaluationType').value;
    const remarks = document.getElementById('remarks').value;
    
    // Validate required fields
    if (!evaluationDate || !evaluatorName || !evaluationType) {
        alert('Please fill in all required fields (Date, Evaluator, Type)');
        return;
    }
    
    // Collect all ratings
    const criteria = [
        'job_knowledge', 'productivity', 'work_quality', 'technical_skills', 'work_consistency',
        'enthusiasm', 'cooperation', 'attitude', 'initiative', 'work_relations', 'creativity',
        'punctuality', 'attendance', 'dependability', 'written_comm', 'verbal_comm',
        'appearance', 'uniform', 'personal_hygiene', 'tidiness'
    ];
    
    // Create form data
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('evaluation_date', evaluationDate);
    formData.append('evaluator_name', evaluatorName);
    formData.append('evaluation_type', evaluationType);
    formData.append('remarks', remarks);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    if (evaluationId) {
        formData.append('evaluation_id', evaluationId);
    }
    
    // Add all criteria ratings
    criteria.forEach(criterion => {
        const inputId = getInputId(criterion);
        const value = document.getElementById(inputId).value || 0;
        formData.append(criterion, value);
    });
    
    // Calculate overall score
    let totalScore = 0;
    let ratedCriteria = 0;
    criteria.forEach(criterion => {
        const inputId = getInputId(criterion);
        const value = parseInt(document.getElementById(inputId).value) || 0;
        if (value > 0) {
            totalScore += value;
            ratedCriteria++;
        }
    });
    const overallScore = ratedCriteria > 0 ? Math.round((totalScore / (ratedCriteria * 5)) * 100) : 0;
    formData.append('overall_score', overallScore);
    
    // Send data to server
    const url = evaluationId ? '/api/workforce-records/update-evaluation' : '/api/workforce-records/save-evaluation';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(evaluationId ? 'Evaluation updated successfully' : 'Evaluation saved successfully');
            closeEvaluationModal();
            // Refresh the page or update the table
            location.reload();
        } else {
            alert('Error saving evaluation: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving evaluation:', error);
        alert('An error occurred while saving the evaluation');
    });
}

function deleteEvaluation() {
    const evaluationId = document.getElementById('evaluationId').value;
    
    if (!evaluationId) {
        alert('No evaluation selected for deletion');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this evaluation? This action cannot be undone.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('evaluation_id', evaluationId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('/api/workforce-records/delete-evaluation', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Evaluation deleted successfully');
            closeEvaluationModal();
            // Refresh the page or update the table
            location.reload();
        } else {
            alert('Error deleting evaluation: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting evaluation:', error);
        alert('An error occurred while deleting the evaluation');
    });
}
