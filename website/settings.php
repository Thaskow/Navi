<?php
include 'templates/component/navbar/navbar.php';
include 'templates/component/header/header.php';

// Charger les données JSON des switches
$switchs_json = file_get_contents("data/json/switchs.json");
$switchs_data = json_decode($switchs_json);
?>

<script>
    // Fonction pour envoyer les paramètres de configuration au serveur
    function submitConfig() {
        // Récupérer les valeurs des champs du formulaire
        const logRetention = document.getElementById('logRetention').value;
        const scanFrequency = document.getElementById('scanFrequency').value;
        const maxPorts = document.getElementById('maxPorts').value;

        // Envoyer les données au serveur via AJAX
        $.ajax({
            url: 'templates/component/add/config.php',
            type: 'POST',
            data: {
                logRetention: logRetention,
                scanFrequency: scanFrequency,
                maxPorts: maxPorts
            },
            success: function (response) {
                console.log(response); // Afficher la réponse du serveur dans la console
                alert('Configuration saved successfully!'); // Message de succès
                // window.location.reload(); // Décommentez pour recharger la page après succès
            },
            error: function (xhr, status, error) {
                console.error('Error:', status, error); // Afficher les erreurs éventuelles
                alert('An error occurred while saving the configuration.'); // Message d'erreur
            }
        });
    }
</script>

<style>
    /* Styles globaux pour le corps de la page */
    body {
        font-family: 'Montserrat', sans-serif;
    }

    /* Style pour le conteneur principal */
    .container {
        margin-left: 15rem;
        padding: 2rem;
    }

    /* Style pour le conteneur du formulaire */
    .form-container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        width: 100%;
        max-width: 600px;
        margin: auto;
    }

    /* Style pour les champs du formulaire */
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        background-color: #f9fafb;
    }

    /* Style pour les champs du formulaire lorsqu'ils sont focus */
    .form-input:focus {
        border-color: #3182ce;
        outline: none;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
    }

    /* Style pour les labels des champs du formulaire */
    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    /* Style pour le bouton du formulaire */
    .form-button {
        background-color: #3182ce;
        color: #ffffff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        text-align: center;
    }

    /* Style pour le bouton du formulaire au survol */
    .form-button:hover {
        background-color: #2b6cb0;
    }
</style>

<!-- Contenu HTML principal -->
<div class="container">
    <div class="form-container">
        <h2 class="text-xl font-semibold mb-4">Configuration des Scans</h2>
        <!-- Formulaire de configuration -->
        <div class="mb-4">
            <label for="logRetention" class="form-label">Rétention des logs (j)</label>
            <input type="number" id="logRetention" class="form-input" placeholder="Entrez le nombre de jours"
                   value="<?= htmlspecialchars($switchs_data->keepLog); ?>">
        </div>
        <div class="mb-4">
            <label for="scanFrequency" class="form-label">Fréquence du scan (min)</label>
            <input type="number" id="scanFrequency" class="form-input" placeholder="Entrez la fréquence en minutes"
                   value="<?= htmlspecialchars($switchs_data->timeLog); ?>">
        </div>
        <div class="mb-4">
            <label for="maxPorts" class="form-label">Nombre de ports maximal à scanner</label>
            <input type="number" id="maxPorts" class="form-input" placeholder="Entrez le nombre maximal de ports"
                   value="<?= htmlspecialchars($switchs_data->maxPorts); ?>">
        </div>
        <button class="form-button" onclick="submitConfig()">Enregistrer</button>
    </div>
</div>

<?php
include 'templates/component/footer/footer.php';
?>
