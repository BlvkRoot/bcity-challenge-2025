<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>enatIs</title>
    <!-- Compiled and minified CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header>
        <nav>
            <div class="nav-wrapper container">
                
                <img src="/../Assets/img/logo.png" height="100px" width="80px" alt="logo" class="circle responsive-img">
                <a href="/" class="brand-logo">
                    eNatis
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

    {{content}}

    <!--footer-->
    <footer class="page-footer black">
        <div class="footer-copyright">
            <div class="container">
                Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
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

            var elems = document.querySelectorAll('.slider');
            let options = {
                duration: 600,
                interval: 6000,
            };
            var instances = M.Slider.init(elems, options);
        });
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems, {});


        });
    </script>



</body>

</html>