// Handle dynamic view switching for the dashboard
document.querySelectorAll('.sidebar button').forEach(button => {
    button.addEventListener('click', function () {
        const section = this.getAttribute('data-section');
        loadSection(section);
    });
});



function loadSection(section) {
    const mainSection = document.querySelector('.main-section');
    const xhr = new XMLHttpRequest();

    // Show loading feedback while requesting
    mainSection.innerHTML = "Loading...";

    xhr.open('GET', '../' + section + '.php', true);    
    xhr.onload = function () {
        if (this.status === 200) {
            mainSection.innerHTML = this.responseText;

            // Reattach form submission event listeners after loading
            const form = mainSection.querySelector('form');
            if (form) {
                form.addEventListener('submit', handleFormSubmission);
            }
        } else {
            mainSection.innerHTML = "Error loading section: " + this.status;
        }
    };

    xhr.onerror = function() {
        mainSection.innerHTML = "Request failed.";
    };

    xhr.send();
}

function handleFormSubmission(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target;
    const formData = new FormData(form);

    // Create an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        const messageContainer = document.querySelector('#responseMessage');
        
        if (response.status === 'success') {
            messageContainer.innerHTML = `<p style="color: green;">${response.message}</p>`;
        } else {
            messageContainer.innerHTML = `<p style="color: red;">${response.message}</p>`;
        }
    };
    xhr.onerror = function() {
        const messageContainer = document.querySelector('#responseMessage');
        messageContainer.innerHTML = `<p style="color: red;">There was an error processing your request. Please try again later.</p>`;
    };

    xhr.send(formData);
}
