// Hardcoded Payroll Settings Modal Functions

// Initialize modal to be hidden on page load
function initPayrollSettingsModal() {
    const modal = document.getElementById("payroll-settings");
    const overlay = document.getElementById("payroll-settings-overlay");
    
    if(modal) {
        modal.style.cssText = "display: none !important; position: fixed !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; z-index: 1001 !important;";
        modal.classList.remove('active');
    }
    
    if(overlay) {
        overlay.style.cssText = "display: none !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background-color: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important; z-index: 1000 !important;";
        overlay.classList.remove('active');
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPayrollSettingsModal);
} else {
    initPayrollSettingsModal();
}

function togglePayrollSettings(toOpen = true) {
    const modal = document.getElementById("payroll-settings");
    const overlay = document.getElementById("payroll-settings-overlay");
    
    if(!modal || !overlay) {
        console.error("Modal elements not found");
        return;
    }
    
    if(toOpen) {
        // Show overlay with background
        overlay.style.cssText = "display: block !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background-color: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(4px) !important; -webkit-backdrop-filter: blur(4px) !important; z-index: 1000 !important;";
        void overlay.offsetWidth; // Force reflow
        overlay.classList.add('active');
        
        // Show modal with proper centering
        modal.style.cssText = "display: block !important; position: fixed !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; z-index: 1001 !important; max-width: 600px; width: 90%; background-color: white !important;";
        void modal.offsetWidth; // Force reflow
        modal.classList.add('active');
        
        // Reset form to default hardcoded values
        resetPayrollSettingsForm();
    } else {
        // Hide modal
        modal.classList.remove('active');
        overlay.classList.remove('active');
        
        // Wait for transition to complete before hiding
        setTimeout(() => {
            modal.style.display = "none";
            overlay.style.display = "none";
        }, 300); // Should match CSS transition time (0.3s)
    }
}

function resetPayrollSettingsForm() {
    // Hardcoded default values - reset to "Select" options
    const frequencySelect = document.getElementById("frequency-select");
    const deductionSelect = document.getElementById("deduction-schedule-select");
    const disbursementSelect = document.getElementById("disbursement-select");
    
    if(frequencySelect) frequencySelect.value = "0";
    if(deductionSelect) deductionSelect.value = "";
    if(disbursementSelect) disbursementSelect.value = "";
}

function updatePayrollSettings() {
    const form = document.getElementById("payroll-settings-form");
    
    if(!form) {
        console.error("Payroll settings form not found");
        return;
    }
    
    const frequency = form.querySelector('[name="frequency"]')?.value;
    const disbursement = form.querySelector('[name="disbursement"]')?.value;
    const deduction_schedule = form.querySelector('[name="deduction_schedule"]')?.value;
    
    // Hardcoded validation - just check if values are selected
    if(!frequency || frequency === "0") {
        if(typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a Payroll Frequency'
            });
        } else {
            alert('Please select a Payroll Frequency');
        }
        return;
    }
    
    if(!deduction_schedule) {
        if(typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a Deduction Schedule'
            });
        } else {
            alert('Please select a Deduction Schedule');
        }
        return;
    }
    
    if(!disbursement) {
        if(typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a Disbursement Date'
            });
        } else {
            alert('Please select a Disbursement Date');
        }
        return;
    }
    
    // Hardcoded success message (no API call)
    if(typeof popupToast !== 'undefined') {
        popupToast('success', 'Payroll settings updated successfully!');
    } else if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Payroll settings updated successfully!'
        });
    } else {
        alert('Payroll settings updated successfully!');
    }
    
    // Close modal after update
    setTimeout(() => {
        togglePayrollSettings(false);
    }, 500);
}
