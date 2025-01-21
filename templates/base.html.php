<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" #}
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://bootswatch.com/5/united/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/custom.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">EventManager</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
                    aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>" href="/">Accueil
                                <span class="visually-hidden">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/users') ? 'active' : '' ?>"
                                href="/event">Événements</a>
                        </li>
                    </ul>

                    <form class="d-flex">
                        <input class="form-control me-sm-2" type="search" placeholder="Search">
                        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="container min-80-vh">
        <?php
        include __DIR__ . "templates/meteo.html.php" ?>
    </main>

    <footer class="bd-footer bg-body-tertiary text-body-secondary py-5">
        <div class="container">
            <p>
                Made by Robin. &copy; 2025
            </p>
        </div>
    </footer>
</body>

</html>