<?php
// Inclusion des composants de la barre de navigation et de l'en-tête
include 'templates/component/navbar/navbar.php';
include 'templates/component/header/header.php';
?>
<script>
    // Fonction pour gérer la suppression d'une adresse MAC de la liste blanche
    function handleDelete(macAddress) {
        if (confirm('Are you sure you want to delete this MAC address from the whitelist?')) {
            // Suppression de l'adresse MAC de la liste blanche
            console.log("MAC" + macAddress);

            $.ajax({
                url: 'templates/component/del/wl.php',
                type: 'POST',
                data: {
                    mac: macAddress
                },
                success: function (response) {
                    console.log(response);
                    location.reload(); // Rechargement de la page après suppression
                }
            });
        }
    }
</script>
<style>
    .delete-btn {
        background-color: #e53e3e;
        color: white;
        padding: 0.25rem 0.5rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
    }
    body {
        font-family: 'Montserrat', sans-serif;
    }
    .container {
        max-width: 800px;
        height: 100vh;
        overflow: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 0.5rem;
    }
    th {
        background-color: #f5f5f5;
    }
</style>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="container mx-auto p-4">
    <?php
    // Fonction pour formater une adresse MAC
    function formatMacAddress($mac) {
        $mac = strtoupper($mac);
        $mac = str_split($mac, 2);
        return implode(":", $mac);
    }

    // Fonction pour générer le tableau HTML pour chaque catégorie
    function renderTable($category) {
        echo "<div class='bg-white shadow-lg rounded-lg p-6 mb-8'>";
        echo "<h2 class='text-xl font-semibold mb-4'>" . ucfirst($category['name']) . "</h2>";
        echo "<table class='min-w-full bg-white'>";
        echo "<thead><tr><th class='py-2'>Name</th><th class='py-2'>MAC Address</th><th class='py-2'>Action</th></tr></thead>";
        echo "<tbody>";

        foreach ($category['wl'] as $item) {
            $macFormatted = formatMacAddress($item['mac']);
            echo "<tr>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($item['name']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($macFormatted) . "</td>";
            echo "<td class='border px-4 py-2'><button class='delete-btn' onclick='handleDelete(\"{$item['mac']}\")'>Supprimer</button></td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }

    // Chargement des données JSON et décodage
    $json = file_get_contents("data/json/white_list.json");
    $data = json_decode($json, true);

    // Parcours des catégories et génération du tableau pour chacune
    foreach ($data['white-list'] as $category) {
        renderTable($category);
    }
    ?>
</div>

<?php
// Inclusion du composant du pied de page
include 'templates/component/footer/footer.php';
?>
