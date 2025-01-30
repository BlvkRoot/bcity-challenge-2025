document.querySelector('#client_contacts_table').addEventListener('click', async function (event) {
    if (!(event.target && event.target.matches('a#unlink_contact'))) return;
    
    event.preventDefault(); // Prevent the default link behavior

    // Get the IDs from the link's data attributes
    const contactId = event.target.getAttribute('data-contact-id');
    const clientCode = event.target.getAttribute('data-client-code');

    try {
        // Send the POST request
        const response = await fetch('/contacts/unlink', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contact_id: contactId,
                client_code: clientCode,
            }),
        });

        const result = await response.json();

        if (response.ok && result.status) {
            globals.showNotification(result.message, 'success'); // Success message

            // Find the closest <tr> and remove it
            const row = event.target.closest('tr');
            if (!row) return;
            
            row.remove();
        } else {
            globals.showNotification(`Error: ${result.message}`, 'error'); // Error message
        }
    } catch (error) {
        globals.showNotification(`An error occurred: ${error.message}`, 'error');
    }
});