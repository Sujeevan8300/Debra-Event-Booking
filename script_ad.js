// You can add JavaScript code to enhance user interactivity here
document.addEventListener('DOMContentLoaded', function () {
    // Example: Show a confirmation dialog when clicking the "Logout" link
    const logoutLink = document.querySelector('a[href="logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (event) {
            const confirmLogout = confirm('Are you sure you want to log out?');
            if (!confirmLogout) {
                event.preventDefault(); // Prevent navigation if user cancels logout
            }
        });
    }
    
    // Example: Toggle visibility of event management section
    const eventManagementSection = document.querySelector('#event-management-section');
    const eventManagementToggle = document.querySelector('#event-management-toggle');

    if (eventManagementToggle && eventManagementSection) {
        eventManagementToggle.addEventListener('click', function () {
            eventManagementSection.classList.toggle('hidden');
        });
    }
    
    // Add more JavaScript functionality as needed
});
