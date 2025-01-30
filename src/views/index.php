
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
                <table id="client_list" class="table-auto">
                    <tbody></tbody>
                </table>
                <div id="loading_spinner" class="hidden">Loading...</div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', async function() {await loadClients();});
    // Load and display clients
    async function loadClients() {
        const clientListContainer = document.querySelector('#client_list tbody');

        try {
            const result = await clientAPI.getAllClients();
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
                            <th><p class="text-center">No. of Linked Contacts</p></th>
                        </tr>
                    </thead>`;
            clients.forEach(client =>{ 
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><p class="text-left">${client.name}</p></td>
                    <td><p class="text-left">${client.client_code}</p></td>
                    <td><p class="text-center">${client.contact_count}</p></td>`;
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
</script>
