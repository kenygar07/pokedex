<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/classes/PokemonAPI.php';

// Handle preflight requests
//probando si funciona
//ayuda
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $pokemonAPI = new PokemonAPI();
    
    if (isset($_GET['pokemon'])) {
        // Search for a specific Pokemon
        $result = $pokemonAPI->getPokemon($_GET['pokemon']);
        echo json_encode($result);
    } elseif (isset($_GET['popular'])) {
        // Get popular Pokemon
        $result = $pokemonAPI->getPopularPokemon();
        echo json_encode($result);
    } else {
        // Return error for invalid requests
        echo json_encode(['error' => true, 'message' => 'Parámetro inválido']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => true, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
}