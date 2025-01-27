const clientSelect = document.getElementById('client_codes');

document.getElementById('link_client').addEventListener('click', async () => {
    const contactId = localStorage.getItem('$contactId');
    const selectedClients = Array.from(clientSelect.selectedOptions).map(option => option.value);

    try {

        // Send the AJAX request
        const response = await fetch('http://localhost:3000/contacts/link', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ contact_id: contactId, client_codes: selectedClients }),
        });

        const result = await response.json();

        // Handle the response
        if (response.ok) {
            globals.showNotification(result.message, 'success');
            
            const contactAPI = new ContactAPI();
            await contactAPI.loadClientsLinkedToContact(contactId);
        } else {
            globals.showNotification(result.error, 'error');
        }
    } catch (error) {
        globals.showNotification(`An error occurred: ${error.message}`, 'error');
    }
});
