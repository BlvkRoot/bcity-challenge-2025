class ClientAPI {
    constructor(baseUrl = 'http://localhost:3000') {
        this.baseUrl = baseUrl;
    }

    // Create a new client
    async createClient(clientData) {
        try {
            const response = await fetch(`${this.baseUrl}/clients`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(clientData)
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

            const clientCode = document.getElementById('client_code');
            const code = result.data.clientCode;
            clientCode.value = code;

            // Populate list of contacts
            await this.getContacts();
            await this.loadContactsLinkedToClient(code);
            return result;
        } catch (error) {
            console.error('Error creating client:', error);
            throw error;
        }
    }

    // Get all clients
    async getAllClients() {
        try {
            const response = await fetch(`${this.baseUrl}/clients/list`, {
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

    async getContacts() {
        try {
            const contactSelect = document.getElementById('contact_ids');

            // Send AJAX request to fetch contacts
            const response = await fetch('/contacts/list');
            const result = await response.json();
    
            console.log(result);

            if (response.ok) {
                // Clear the current options
                contactSelect.innerHTML = '';
    
                // Append the contacts as options
                if (result.data && result.data.length > 0) {
                    result.data.forEach(contact => {
                        const option = document.createElement('option');
                        option.value = contact.id;
                        option.textContent = `${contact.surname} ${contact.name}`;
                        contactSelect.appendChild(option);
                    });
                } else {
                    const noOption = document.createElement('option');
                    noOption.textContent = 'No contacts available';
                    noOption.disabled = true;
                    contactSelect.appendChild(noOption);
                }

                M.FormSelect.init(contactSelect);
            } else {
                globals.showNotification(`Error: ${result.error}`, 'error');
            }
        } catch (error) {
            globals.showNotification(`An error occurred: ${error.message}`, 'error');
        }
    }

    // Get all contacts
    async getAllContactsByClientCode($clientCode) {
        try {
            const response = await fetch(`${this.baseUrl}/client-contacts?client_code=${$clientCode}`, {
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
            console.error('Error fetching contacts:', error);
            throw error;
        }
    }

    // Load and display contacts
    async loadContactsLinkedToClient($clientCode) {
        const contactListContainer = document.getElementById('client_contacts');
        try {
            contactListContainer.innerHTML = '';

            const result = await this.getAllContactsByClientCode($clientCode);
            const contacts = result?.data;

            console.log(contacts);

            if (contacts?.length < 1) {
                contactListContainer.innerHTML = '<p class="text-gray-500">No contact(s) found.</p>';
                return;
            }

            contactListContainer.innerHTML = `
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th><p class="text-left">Contact FullName</p></th>
                            <th><p class="text-left">Contact Email address</p></th>
                            <th><p class="text-center"></p></th>
                        </tr>
                    </thead>
                    <tbody>
                    ${contacts.map(contact => `
                                    <tr>
                                        <td><p class="text-left">${contact.surname} ${contact.name}</p></td>
                                        <td><p class="text-left">${contact.email}</p></td>
                                        <td><a href="" class="text-center">link to unlink here</a></td>
                                    </tr>`)}

                    </tbody>
                </table>`;

        } catch (error) {
            contactListContainer.innerHTML = `
                <div class="text-red-500">
                    Error loading contacts: ${error.message}
                </div>
            `;
        }
    }
    
}