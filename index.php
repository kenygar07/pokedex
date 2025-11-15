<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokedex - Enciclopedia Pokémon</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Pokedex</h1>
            <p class="lead">Enciclopedia completa de Pokémon</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="input-group mb-4">
                    <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Buscar Pokémon por nombre o número..." aria-label="Buscar Pokémon">
                    <button class="btn btn-primary btn-lg" type="button" id="searchButton">Buscar</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div id="pokemonInfo" class="card-container">
                    <!-- Pokemon information will be loaded here -->
                    <div class="alert alert-info text-center">
                        <h4 class="alert-heading">¡Bienvenido a la Pokedex!</h4>
                        <p>Ingresa el nombre o número de un Pokémon en el campo de arriba para obtener información detallada.</p>
                        <p class="mb-0">Por ejemplo: "pikachu", "charizard", o "25"</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h3 class="text-center mb-4">Pokémon Populares</h3>
                <div id="popularPokemon" class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Popular Pokemon will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>