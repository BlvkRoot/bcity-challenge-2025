<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bcity Challenge</title>
    <!-- Compiled and minified CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./src/views/style.css">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="./src/views/js/client-operations.js"></script>
    <script src="./src/views/js/contact-operations.js"></script>
    <script src="./src/views/js/globals.js"></script>
</head>

<body>
    <header>
        <nav class="orange">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo">
                    BCity Challenge
                </a>
                <!-- <a href="#" data-target="mobilenav" class="sidenav-trigger right"><i class="material-icons">menu</i></a> -->
                <ul class="right">
                    <li><a href="/">Home</a></li>
                    <li><a href="/clients">Clients</a></li>
                    <li><a href="/contacts">Contacts</a></li>
                </ul>
            </div>
        </nav>
        <!-- <ul id="mobilenav" class="sidenav">
            <li><a href="/">Home</a></li>
            <li><a href="/client">Clients</a></li>
            <li><a href="/contact">Contacts</a></li>
        </ul> -->
    </header>
    <main>
        {{content}}
    </main>

    <!--footer-->
    <footer class="page-footer black">
        <div class="footer-copyright">
            <div class="container">
                Developed by <a class="orange-text text-lighten-3" href="https://github.com/BlvkRoot">Henriques Salucamba</a>
            </div>
        </div>
    </footer>


    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <!-- Materialize JS Initialization here -->
    <script>
        const clientAPI = new ClientAPI();
        const globals = new Globals();
        const contactAPI = new ContactAPI();

        document.addEventListener('DOMContentLoaded', async function() {
            var sidenav = document.querySelectorAll('.sidenav');
            var slider = document.querySelectorAll('.slider');
            var tab = document.querySelectorAll('.tabs');
            var modals = document.querySelectorAll('.modal');
            var select = document.querySelectorAll('select');
            var clientCode = document.getElementById('client_code');

            M.Slider.init(slider, {
                duration: 600,
                interval: 6000,
            });
            M.Sidenav.init(sidenav, {});
            M.Tabs.init(tab, {});
            M.Modal.init(modals, {});
            M.FormSelect.init(select, {});

            // Manually close modals to prevent closing all modals
            document.querySelectorAll(".close-modal").forEach((button) => {
                button.addEventListener("click", function () {
                    const modal = this.closest(".modal"); // Get the closest modal
                    const modalInstance = M.Modal.getInstance(modal);
                    modalInstance.close();
                });
            });
        });
    </script>
</body>
</html>
