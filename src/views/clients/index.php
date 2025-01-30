<section>
    <div class="center">
        <h2>Capture client's data</h2>
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active"  href="#general_client">General</a></li>
                    <li class="tab col s3"><a href="#client_contacts">Contacts</a></li>
                </ul>
            </div>
            <div id="general_client" class="col s12">
                <div class="row">
                    <form class="col s12" id="create_client_form">
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="client_name" type="text" name="name" class="validate">
                                <label for="client_name">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input disabled placeholder="Client code auto-generated" value="" id="client_code" type="text" name="client_code" class="validate">
                                <label for="client_code">Client code</label>
                            </div>
                        </div>
                        <div class="row">
                            <div id="client_link_trigger" class="col s4 text-left hidden">
                                <a class="waves-effect waves-light modal-trigger" href="#client_modal">Link contact</a>
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
                    <div id="client_modal" class="modal">
                        <div class="modal-content">
                        <form class="col s12" id="link_client_form">
                            <div class="row">
                                <div class="input-field col s12">
                                    <select multiple id="contact_ids">
                                    </select>
                                    <label>Contacts</label>
                                </div>
                            </div>
                            <div class="row">

                                <div class="modal-footer col s6">
                                    <a href="#!" class="close-modal waves-effect waves-green btn-flat orange" id="link_contact">
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
            <div id="client_contacts" class="col s12">
                <table id="client_contacts_table" class="table-auto">
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="./src/views/js/link-contacts.js"></script>
<script src="./src/views/js/unlink-contact.js"></script>

<script>
    // Create client form submission
    document.getElementById('create_client_form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const clientName = document.getElementById('client_name').value;
        const spinner = document.getElementById('create_spinner');
        const clientLinkTrigger = document.getElementById('client_link_trigger');

        try {
            spinner.classList.remove('hidden');
            clientLinkTrigger.classList.remove('hidden');

            const result = await clientAPI.createClient({
                name: clientName,
            });

            // Show success message
            globals.showNotification('Client created successfully!', 'success');

        } catch (error) {
            globals.showNotification('Error: ' + error.message, 'error');
            clientLinkTrigger.classList.add('hidden');
        } finally {
            spinner.classList.add('hidden');
        }
    });
</script>
