$(document).ready(function() {
    // Load popular Pokemon on page load
    loadPopularPokemon();
    
    // Handle search button click
    $('#searchButton').click(function() {
        const query = $('#searchInput').val().trim();
        if (query) {
            searchPokemon(query);
        }
    });
    
    // Handle Enter key in search input
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) { // Enter key
            const query = $(this).val().trim();
            if (query) {
                searchPokemon(query);
            }
        }
    });
    
    // Function to search for a Pokemon
    function searchPokemon(query) {
        // Show loading state
        $('#pokemonInfo').html(`
            <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Buscando Pokémon...</p>
                </div>
            </div>
        `);
        
        // Make AJAX request
        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: { pokemon: query },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    showError(response.message);
                } else {
                    displayPokemonInfo(response);
                }
            },
            error: function() {
                showError('Error al conectar con la API. Por favor, inténtalo de nuevo más tarde.');
            }
        });
    }
    
    // Function to display Pokemon information
    function displayPokemonInfo(pokemon) {
        // Create type badges HTML
        let typeBadges = '';
        pokemon.types.forEach(function(type) {
            typeBadges += `<span class="type-badge type-${type} me-1">${capitalizeFirstLetter(type)}</span>`;
        });
        
        // Calculate stat percentages (max stat is typically around 255)
        const maxStat = 255;
        let statsHtml = '';
        $.each(pokemon.stats, function(statName, value) {
            const percentage = Math.min(100, (value / maxStat) * 100);
            const statLabel = statName.replace('-', ' ').split(' ')
                .map(word => capitalizeFirstLetter(word))
                .join(' ');
                
            statsHtml += `
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span>${statLabel}</span>
                        <strong>${value}</strong>
                    </div>
                    <div class="stat-bar">
                        <div class="stat-progress" style="width: ${percentage}%"></div>
                    </div>
                </div>
            `;
        });
        
        // Create the Pokemon card HTML
        const pokemonCard = `
            <div class="card pokemon-card shadow-sm">
                <div class="card-header pokemon-header text-center py-3">
                    <h2 class="card-title mb-0">${pokemon.name}</h2>
                    <h5 class="card-subtitle text-white-50 mb-0">#${pokemon.id.toString().padStart(3, '0')}</h5>
                </div>
                <div class="card-body text-center">
                    <img src="${pokemon.artwork}" alt="${pokemon.name}" class="pokemon-image img-fluid" onerror="this.onerror=null; this.src='${pokemon.sprite}';">
                    
                    <div class="my-3">
                        <div class="pokemon-types">
                            ${typeBadges}
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Detalles</h5>
                            <p><strong>Altura:</strong> ${pokemon.height} m</p>
                            <p><strong>Peso:</strong> ${pokemon.weight} kg</p>
                            
                            <h5 class="mt-3">Habilidades</h5>
                            <ul class="list-unstyled">
                                ${pokemon.abilities.map(ability => `<li class="badge bg-secondary me-1">${capitalizeFirstLetter(ability)}</li>`).join('')}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Estadísticas</h5>
                            <div class="stats-container">
                                ${statsHtml}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#pokemonInfo').html(pokemonCard);
    }
    
    // Function to show error message
    function showError(message) {
        $('#pokemonInfo').html(`
            <div class="alert alert-danger text-center">
                <h4 class="alert-heading">Error</h4>
                <p>${message}</p>
            </div>
        `);
    }
    
    // Function to load popular Pokemon
    function loadPopularPokemon() {
        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: { popular: true },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    console.error('Error loading popular Pokemon:', response.message);
                } else {
                    displayPopularPokemon(response);
                }
            },
            error: function() {
                console.error('Error connecting to API for popular Pokemon');
            }
        });
    }
    
    // Function to display popular Pokemon
    function displayPopularPokemon(pokemons) {
        let popularHtml = '';
        
        pokemons.forEach(function(pokemon) {
            // Create type badges HTML
            let typeBadges = '';
            pokemon.types.forEach(function(type) {
                typeBadges += `<span class="type-badge type-${type} me-1">${capitalizeFirstLetter(type)}</span>`;
            });
            
            popularHtml += `
                <div class="col mb-4">
                    <div class="card h-100 pokemon-popular-card" data-pokemon="${pokemon.name.toLowerCase()}">
                        <img src="${pokemon.artwork}" class="card-img-top" alt="${pokemon.name}" style="height: 150px; object-fit: contain;" onerror="this.onerror=null; this.src='${pokemon.sprite}';">
                        <div class="card-body text-center">
                            <h5 class="card-title">${pokemon.name}</h5>
                            <div class="pokemon-types mb-2">
                                ${typeBadges}
                            </div>
                            <small class="text-muted">Nº ${pokemon.id.toString().padStart(3, '0')}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#popularPokemon').html(popularHtml);
        
        // Add click event to popular Pokemon cards
        $('.pokemon-popular-card').click(function() {
            const pokemonName = $(this).data('pokemon');
            $('#searchInput').val(pokemonName);
            searchPokemon(pokemonName);
        });
    }
    
    // Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
});