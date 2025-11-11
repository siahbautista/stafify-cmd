// Mobile menu functionality
document.addEventListener("DOMContentLoaded", function () {
    const menuButton = document.querySelector(".hamburger-menu");
    const closeButton = document.querySelector(".sidebar-close-btn");
    const sidebar = document.querySelector(".sidebar-container-mobile");
    const overlay = document.querySelector(".sidebar-overlay-mobile");

    // Function to open sidebar
    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add("active");
        }
        if (overlay) {
            overlay.classList.add("active");
        }
    }

    // Function to close sidebar
    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove("active");
        }
        if (overlay) {
            overlay.classList.remove("active");
        }
    }

    // Open Sidebar on Hamburger Click
    if (menuButton) {
        menuButton.addEventListener("click", openSidebar);
    }

    // Close Sidebar on Close Button Click
    if (closeButton) {
        closeButton.addEventListener("click", closeSidebar);
    }

    // Close Sidebar when clicking outside (on overlay)
    if (overlay) {
        overlay.addEventListener("click", closeSidebar);
    }
});

// Menu toggle functionality
function toggleMenu(event) {
    event.preventDefault();
    
    // Find the closest menu container and its menu
    const button = event.target.closest('.bento-menu');
    const menuContainer = button ? button.closest('.menu-container') : null;
    
    if (menuContainer) {
        const menu = menuContainer.querySelector('.menu');
        const dropdownMenu = menuContainer.querySelector('.dropdownMenu');
        
        if (menu) {
            menu.classList.toggle('active');
        }
        
        if (dropdownMenu) {
            dropdownMenu.classList.toggle('active');
        }
    }
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menuContainers = document.querySelectorAll('.menu-container');
    
    menuContainers.forEach(menuContainer => {
        if (!menuContainer.contains(event.target)) {
            const menu = menuContainer.querySelector('.menu');
            const dropdownMenu = menuContainer.querySelector('.dropdownMenu');
            
            if (menu) {
                menu.classList.remove('active');
            }
            
            if (dropdownMenu) {
                dropdownMenu.classList.remove('active');
            }
        }
    });
});
