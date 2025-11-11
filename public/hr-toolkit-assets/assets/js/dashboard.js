// Debug flag for development
const DEBUG = true;

// Debug logging function
function debugLog(message, data = null) {
    if (DEBUG) {
        console.log(`[Dashboard Debug] ${message}`, data || '');
    }
}

// Error logging function
function logError(error, context) {
    console.error(`[Dashboard Error] ${context}:`, error);
}

/**
 * Deal Functions
 */
function updateDealCounts(period) {
    try {
        const data = window.dealCounts[period];
        if (!data) {
            throw new Error(`No deal data found for period: ${period}`);
        }

        // Update counts
        document.getElementById('totalDeals').textContent = data.total;
        document.getElementById('pendingDeals').textContent = data.pending;
        document.getElementById('wonDeals').textContent = data.won;
        document.getElementById('lostDeals').textContent = data.lost;

        // Update button states
        updateButtonStates('.period-btn', period);
    } catch (error) {
        logError(error, 'Deal counts update failed');
    }
}

/**
 * Lead Functions
 */
function updateLeadCounts(period) {
    try {
        const data = window.leadCounts[period];
        if (!data) {
            throw new Error(`No lead data found for period: ${period}`);
        }

        // Update counts
        document.getElementById('totalLeads').textContent = data.total;
        document.getElementById('coldLeads').textContent = data.cold;
        document.getElementById('warmLeads').textContent = data.warm;
        document.getElementById('hotLeads').textContent = data.hot;

        // Update button states
        updateButtonStates('.lead-period-btn', period);
    } catch (error) {
        logError(error, 'Lead counts update failed');
    }
}

/**
 * Contact Functions
 */
function updateContactCounts(period) {
    try {
        const data = window.contactCounts[period];
        if (!data) {
            throw new Error(`No contact data found for period: ${period}`);
        }

        // Update counts
        document.getElementById('totalContacts').textContent = data.total_contacts;
        document.getElementById('activeContacts').textContent = data.contacts_with_open_deals;
        document.getElementById('inactiveContacts').textContent = data.contacts_with_closed_deals;
        document.getElementById('newContacts').textContent = data.contacts_without_deals;

        // Update button states
        updateButtonStates('.contact-period-btn', period);
    } catch (error) {
        logError(error, 'Contact counts update failed');
    }
}

/**
 * Utility Functions
 */
function updateButtonStates(selector, activePeriod) {
    const buttons = document.querySelectorAll(selector);
    buttons.forEach(button => {
        if (button.dataset.period === activePeriod) {
            button.classList.remove('bg-gray-200', 'text-gray-700');
            button.classList.add('bg-blue-500', 'text-white');
        } else {
            button.classList.remove('bg-blue-500', 'text-white');
            button.classList.add('bg-gray-200', 'text-gray-700');
        }
    });
}

/**
 * Initialize Dashboard
 */
function initializeDashboard() {
    try {
        // Set up period button click handlers
        setupPeriodButtons('.period-btn', updateDealCounts);
        setupPeriodButtons('.lead-period-btn', updateLeadCounts);
        setupPeriodButtons('.contact-period-btn', updateContactCounts);

        // Initialize with 'all' period
        updateDealCounts('all');
        updateLeadCounts('all');
        updateContactCounts('all');
    } catch (error) {
        logError(error, 'Dashboard initialization failed');
    }
}

function setupPeriodButtons(selector, updateFunction) {
    const buttons = document.querySelectorAll(selector);
    buttons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const period = button.dataset.period;
            updateFunction(period);
        });
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeDashboard); 