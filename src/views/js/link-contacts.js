
const contactSelect = document.getElementById('contact_ids');

document.getElementById('link_contact').addEventListener('click', async () => {
    const clientCode = document.getElementById('client_code').value;
    const selectedContacts = Array.from(contactSelect.selectedOptions).map(option => option.value);

    try {

        console.log(selectedContacts);

        // Send the AJAX request
        const response = await fetch('http://localhost:3000/clients/link', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ client_code: clientCode, contact_ids: selectedContacts }),
        });

        const result = await response.json();

        // Handle the response
        if (response.ok) {
            globals.showNotification(result.message, 'success');

            const clientAPI = new ClientAPI();
            await clientAPI.loadContactsLinkedToClient(clientCode);
        } else {
            globals.showNotification(result.error, 'error');
        }
    } catch (error) {
        globals.showNotification(`An error occurred: ${error.message}`, 'error');
    }
});
