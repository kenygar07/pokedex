<?php

class PokemonAPI {
    private $baseUrl = 'https://pokeapi.co/api/v2';

    public function __construct() {
        // Ensure cURL is available
        if (!function_exists('curl_init')) {
            throw new Exception('cURL is required but not available on this system');
        }
    }

    /**
     * Get Pokemon data by name or ID
     * @param string|int $identifier Pokemon name or ID
     * @return array Pokemon data or error
     */
    public function getPokemon($identifier) {
        try {
            // Convert to lowercase if it's a name
            $identifier = is_numeric($identifier) ? $identifier : strtolower($identifier);
            
            $url = $this->baseUrl . "/pokemon/{$identifier}";
            $data = $this->makeRequest($url);
            
            if ($data === false) {
                return ['error' => true, 'message' => 'Error al obtener el Pokémon'];
            }
            
            return $this->formatPokemonData($data);
        } catch (Exception $e) {
            return ['error' => true, 'message' => 'Error inesperado: ' . $e->getMessage()];
        }
    }

    /**
     * Get a list of popular Pokemon
     * @return array List of popular Pokemon
     */
    public function getPopularPokemon() {
        $popularIds = [1, 4, 7, 25, 39, 133, 143, 150, 151]; // Popular Pokemon IDs
        $pokemons = [];
        
        foreach ($popularIds as $id) {
            $pokemon = $this->getPokemon($id);
            if (!isset($pokemon['error'])) {
                $pokemons[] = $pokemon;
            }
        }
        
        return $pokemons;
    }

    /**
     * Make HTTP request using cURL
     * @param string $url The URL to request
     * @return array|false Response data or false on error
     */
    private function makeRequest($url) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Pokedex App 1.0');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL error: ' . $error);
        }
        
        if ($httpCode === 404) {
            throw new Exception('Pokémon no encontrado', 404);
        }
        
        if ($httpCode !== 200) {
            throw new Exception('HTTP error: ' . $httpCode);
        }
        
        return json_decode($response, true);
    }

    /**
     * Format Pokemon data for frontend display
     * @param array $data Raw Pokemon data from API
     * @return array Formatted Pokemon data
     */
    private function formatPokemonData($data) {
        return [
            'id' => $data['id'],
            'name' => ucfirst($data['name']),
            'height' => $data['height'] / 10, // Convert decimeters to meters
            'weight' => $data['weight'] / 10, // Convert hectograms to kilograms
            'types' => array_map(function($type) {
                return $type['type']['name'];
            }, $data['types']),
            'abilities' => array_map(function($ability) {
                return $ability['ability']['name'];
            }, $data['abilities']),
            'stats' => [
                'hp' => $data['stats'][0]['base_stat'],
                'attack' => $data['stats'][1]['base_stat'],
                'defense' => $data['stats'][2]['base_stat'],
                'special-attack' => $data['stats'][3]['base_stat'],
                'special-defense' => $data['stats'][4]['base_stat'],
                'speed' => $data['stats'][5]['base_stat']
            ],
            'sprite' => $data['sprites']['front_default'],
            'artwork' => $data['sprites']['other']['official-artwork']['front_default'] ?? $data['sprites']['front_default']
        ];
    }
}