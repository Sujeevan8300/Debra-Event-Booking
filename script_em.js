// Function to validate the event creation form
function validateEventForm() {
    const title = document.querySelector('#title').value;
    const description = document.querySelector('#description').value;
    const date = document.querySelector('#date').value;
    const price = document.querySelector('#price').value;

    // Check if any field is empty
    if (!title || !description || !date || !price) {
        alert('Please fill in all fields');
        return false;
    }

    // Validate the date (optional, you can use a library like Moment.js for more advanced date validation)
    const currentDate = new Date();
    const selectedDate = new Date(date);

    if (selectedDate < currentDate) {
        alert('Event date cannot be in the past');
        return false;
    }

    // Price should be a positive number
    if (isNaN(price) || price <= 0) {
        alert('Price must be a positive number');
        return false;
    }

    return true;
}

// Function to confirm event deletion
function confirmEventDeletion() {
    return confirm('Are you sure you want to delete this event?');
}

// Attach event listeners to form submission and delete links
document.addEventListener('DOMContentLoaded', function () {
    const createEventForm = document.querySelector('#create-event-form');
    const deleteEventLinks = document.querySelectorAll('.delete-event-link');

    createEventForm.addEventListener('submit', function (e) {
        if (!validateEventForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    deleteEventLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (!confirmEventDeletion()) {
                e.preventDefault(); // Prevent link click if deletion is canceled
            }
        });
    });
});
