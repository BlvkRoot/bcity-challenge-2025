<section class="contacts_container">
    <div class="center">
        <div class="row">
            <div class="col s12">
                <a class="btn btn-large orange modal-trigger" href="#contact_create_modal">
                    Add Contact
                    <i class="material-icons right">add</i>
                </a>
            </div>
            <div class="contacts__table col s12 center">
                <table id="contact_list" class="table-auto">
                    <tbody></tbody>
                </table>
                <div id="loading_spinner" class="hidden">Loading...</div>
            </div>
        </div>
    </div>
</section>
<section id="contact_create_modal" class="modal col s12">
    <div class="center modal-content">
        <h2>Capture contacts</h2>
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active"  href="#general_contact">General</a></li>
                    <li class="tab col s3"><a href="#contact_clients">Clients</a></li>
                </ul>
            </div>
            <div id="general_contact" class="col s12">
                <div class="row">
                    <form class="col s12" id="create_contact_form">
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_name" type="text" name="name" class="validate">
                                <label for="contact_name">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_surname" type="text" name="surname" class="validate">
                                <label for="contact_surname">Surname</label>
                            </div>
                        </div>
                        <div class="row hidden">
                            <div class="input-field col s4">
                                <input disabled value="" id="contact_id" type="text" name="id" class="validate">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_email" type="email" name="email" class="validate">
                                <label for="contact_email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div id="contact_link_trigger" class="col s4 text-left hidden">
                                <a class="waves-effect waves-light modal-trigger" href="#contact_modal">Link client</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s4">
                                <button class="btn btn-large orange" type="submit">
                                    Submit
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                        <div id="create_spinner" class="hidden">Loading...</div>
                    </form>
                </div>
                <div class="row">
                    <div id="contact_modal" class="modal">
                        <div class="modal-content">
                            <form class="col s12" id="link_contact_form">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select multiple id="client_codes">
                                        </select>
                                        <label>Clients</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="modal-footer col s6">
                                        <a href="#!" class="close-modal waves-effect waves-green btn-flat orange" id="link_client">
                                            Link
                                            <i class="material-icons right">send</i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div id="contact_clients" class="col s12">
                <table id="contact_clients_table" class="table-auto">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="./src/views/js/link-clients.js"></script>
<script src="./src/views/js/unlink-client.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        await loadContacts();
    });

    // Load and display contacts
    async function loadContacts() {
            const contactListContainer = document.querySelector('#contact_list tbody');

            try {
                const result = await contactAPI.getAllContacts();
                const contacts = result?.data;

                if (contacts?.length < 1) {
                    contactListContainer.innerHTML = '<p class="text-gray-500">No contact(s) found.</p>';
                    return;
                }

                contactListContainer.innerHTML = `
                        <thead>
                            <tr>
                                <th><p class="text-left">Name</p></th>
                                <th><p class="text-left">Surname</p></th>
                                <th><p class="text-left">Email address</p></th>
                                <th><p class="text-center">No. of Linked clients</p></th>
                            </tr>
                        </thead>`;

                        contacts.forEach(contact => {
                            const row =document.createElement('tr');
                            row.innerHTML = `
                                <td><p class="text-left">${contact.name}</p></td>
                                <td><p class="text-left">${contact.surname}</p></td>
                                <td><p class="text-left">${contact.email}</p></td>
                                <td><p class="text-center">${contact.client_count}</p></td>`;
                            contactListContainer.appendChild(row);
                        });

            } catch (error) {
                contactListContainer.innerHTML = `
                    <div class="text-red-500">
                        Error loading contacts: ${error.message}
                    </div>
                `;
            }
    }

    // Create client form submission
    document.getElementById('create_contact_form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const contactName = document.getElementById('contact_name').value;
        const contactSurname = document.getElementById('contact_surname').value;
        const contactEmail = document.getElementById('contact_email').value;
        const spinner = document.getElementById('create_spinner');
        const contactLinkTrigger = document.getElementById('contact_link_trigger');

        try {
            spinner.classList.remove('hidden');
            contactLinkTrigger.classList.remove('hidden');

            const result = await contactAPI.createContact({
                name: contactName,
                surname:contactSurname,
                email:contactEmail
            });
            console.log(result);

            // Show success message
            globals.showNotification('Contact created successfully!', 'success');

        } catch (error) {
            globals.showNotification('Error: ' + error.message, 'error');
            clientLinkTrigger.classList.add('hidden');
        } finally {
            spinner.classList.add('hidden');
        }
    });
</script>
