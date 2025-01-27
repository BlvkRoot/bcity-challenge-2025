
<section class="home__container">
    <div class="center">
        <div class="row">
            <div class="col s12">
                <a class="btn btn-large orange" href="/clients">
                    Add Client
                    <i class="material-icons right">person_add</i>
                </a>
            </div>
            <div class="clients__table col s12 center">
                <div id="client_list"></div>
                <div id="loading_spinner" class="hidden">Loading...</div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', async function() {await loadClients();});
    // Load and display clients
    async function loadClients() {
        const clientListContainer = document.getElementById('client_list');
        // const loadingSpinner = document.getElementById('loading_spinner');

        try {
            clientListContainer.innerHTML = '';

            const result = await clientAPI.getAllClients();
            const clients = result?.data;

            if (clients?.length < 1) {
                clientListContainer.innerHTML = '<p class="text-gray-500">No client(s) found.</p>';
                return;
            }

            // loadingSpinner.classList.remove('hidden');

            clientListContainer.innerHTML = `
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th><p class="text-left">Name</p></th>
                            <th><p class="text-left">Client Code</p></th>
                            <th><p class="text-center">No. of Linked Contacts</p></th>
                        </tr>
                    </thead>
                    <tbody>
                    ${clients.map(client => `
                                    <tr>
                                        <td><p class="text-left">${client.name}</p></td>
                                        <td><p class="text-left">${client.client_code}</p></td>
                                        <td><p class="text-center">${client.contact_count}</p></td>
                                    </tr>`)}

                    </tbody>
                </table>`;

        } catch (error) {
            clientListContainer.innerHTML = `
                <div class="text-red-500">
                    Error loading clients: ${error.message}
                </div>
            `;
        } finally {
            // loadingSpinner.classList.add('hidden');
        }
    }
</script>
