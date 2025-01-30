class ContactAPI {
    constructor(baseUrl = 'http://localhost:3000') {
        this.baseUrl = baseUrl;
    }

    // Create a new comtact
    async createContact(contactData) {
        try {
            const response = await fetch(`${this.baseUrl}/contacts`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(contactData)
            }).then();

            const result = await response.json();

            if (!response.ok) {
                if (result.message == 'Validation failed') {
                    const errors = Object.values(result.errors)
                    errors.forEach((i, error) => {
                        throw new Error(`${errors[error]}`);
                    });
                }
            }
            localStorage.setItem('$contactId', result.data.id);

            // Populate list of contacts
            await this.getClients();
            await this.loadClientsLinkedToContact(result.data.id);
            return result;
        } catch (error) {
            console.error('Error creating client:', error);
            throw error;
        }
    }

    // Get all contacts
    async getAllContacts() {
        try {
            const response = await fetch(`${this.baseUrl}/contacts/list`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(`${result.message}`);
            }

            return result;
        } catch (error) {
            console.error('Error fetching clients:', error);
            throw error;
        }
    }


    // Get all contacts
    async getAllClientsByContactId($contactId) {
        try {
            const response = await fetch(`${this.baseUrl}/contact-clients?contact_id=${$contactId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(`${result.message}`);
            }

            return result;
        } catch (error) {
            console.error('Error fetching clients:', error);
            throw error;
        }
    }

    // Populate client select box
    async getClients() {
        try {
            const clientSelect = document.getElementById('client_codes');
            // Send AJAX request to fetch clients
            const response = await fetch('/clients/list');
            const result = await response.json();

            
            
            if (response.ok) {
                // Clear the current options
                clientSelect.innerHTML = '';
                console.log(result);
    
                // Append the contacts as options
                if (result.data && result.data.length > 0) {
                    result.data.forEach(client => {
                        const option = document.createElement('option');
                        option.value = client.client_code;
                        option.textContent = client.name;
                        clientSelect.appendChild(option);
                    });
                } else {
                    const noOption = document.createElement('option');
                    noOption.textContent = 'No contacts available';
                    noOption.disabled = true;
                    clientSelect.appendChild(noOption);
                }

                M.FormSelect.init(clientSelect);
            } else {
                globals.showNotification(`Error: ${result.error}`, 'error');
            }
        } catch (error) {
            globals.showNotification(`An error occurred: ${error.message}`, 'error');
        }
    }

    // Load and display clients
    async loadClientsLinkedToContact($contactId) {
        const clientListContainer = document.querySelector('#contact_clients_table tbody');

        try {
            const result = await this.getAllClientsByContactId($contactId);
            const clients = result?.data;

            if (clients?.length < 1) {
                clientListContainer.innerHTML = '<p class="text-gray-500">No client(s) found.</p>';
                return;
            }

            clientListContainer.innerHTML = `
                <thead>
                    <tr>
                        <th><p class="text-left">Name</p></th>
                        <th><p class="text-left">Client Code</p></th>
                        <th><p class="text-center"></p></th>
                    </tr>
                </thead>`;

                clients.forEach(client => {
                    const row = document.createElement('tr');
                    row.id = `client-${client.client_code}`;
                    row.innerHTML = `
                        <td><p class="text-left">${client.name}</p></td>
                        <td><p class="text-left">${client.client_code}</p></td>
                        <td>
                            <a 
                                    href="#" 
                                    id="unlink_client" 
                                    data-contact-id="${$contactId}" 
                                    data-client-code="${client.client_code}"
                                    class="text-center">Unlink client</a>
                        </td>`;
                    clientListContainer.appendChild(row);
                });

        } catch (error) {
            clientListContainer.innerHTML = `
                <div class="text-red-500">
                    Error loading clients: ${error.message}
                </div>
            `;
        }
    }
    
}