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
</head>

<body>
    <header>
        <nav>
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo">
                    BCity Challenge
                </a>
                <a href="#" data-target="mobilenav" class="sidenav-trigger right"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="/">Home</a></li>
                    <li><a href="/clients">Clients</a></li>
                    <li><a href="/contacts">Contacts</a></li>
                </ul>
            </div>
        </nav>
        <ul id="mobilenav" class="sidenav">
            <li><a href="/">Home</a></li>
            <li><a href="/client">Clients</a></li>
            <li><a href="/contact">Contacts</a></li>
        </ul>
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
    <script src="./js/cards.js"></script>
    <script src="./js/global.js"></script>


    <!-- Materialize JS Initialization here -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidenav = document.querySelectorAll('.sidenav');
            var slider = document.querySelectorAll('.slider');
            var tab = document.querySelectorAll('.tabs');

            M.Slider.init(slider, {
                duration: 600,
                interval: 6000,
            });
            M.Sidenav.init(sidenav, {});
            M.Tabs.init(tab, {});
        });
    </script>
</body>
</html>
