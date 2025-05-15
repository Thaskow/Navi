<?php
// Inclusion des composants de la barre de navigation et de l'en-tête
include 'templates/component/navbar/navbar.php';
include 'templates/component/header/header.php';
?>
<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
    .container {
        height: 100vh;
        overflow: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
        margin-left: 15rem;
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
    tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }
</style>
<script>
    // Fonction pour rediriger vers les détails du switch
    function redirectToSwitchDetails(ip) {
        window.location.href = 'switch_detail.php?ip=' + ip;
    }
</script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="container p-4">
    <?php
    // Chargement des données JSON et décodage
    $json = file_get_contents("data/json/warning.json");
    $data = json_decode($json, true);

    // Affichage des avertissements dans une table
    echo "<div class='bg-white shadow-lg rounded-lg p-6 mb-8'>";
    echo "<h2 class='text-xl font-semibold mb-4'>Warnings</h2>";
    echo "<table class='min-w-full bg-white'>";
    echo "<thead><tr>
                <th class='py-2'>Type</th>
                <th class='py-2'>Interconnexion</th>
                <th class='py-2'>Vendor</th>
                <th class='py-2'>Date</th>
                <th class='py-2'>Status</th>
                <th class='py-2'>MAC Address</th>
                <th class='py-2'>Name</th>
                <th class='py-2'>Check Status</th>
                <th class='py-2'>Check Base</th>
                <th class='py-2'>Switch Name</th>
                <th class='py-2'>Switch IP</th>
                <th class='py-2'>Switch Port</th>
              </tr></thead>";
    echo "<tbody>";

    // Fonction pour formater l'adresse MAC
    function formatMacAddress($mac) {
        $mac = strtoupper($mac);
        $mac = str_split($mac, 2);
        return implode(":", $mac);
    }

    // Parcours des avertissements et affichage dans les lignes du tableau
    foreach ($data['warnings'] as $warning) {
        $macFormatted = formatMacAddress($warning['mac']);
        echo "<tr onclick='redirectToSwitchDetails(\"{$warning['switch']['ip']}\")'>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['type']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['interconnexion']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['vendor']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['date']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['status']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($macFormatted) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['name']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['check']['status']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['check']['base']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['switch']['name']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['switch']['ip']) . "</td>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['switch']['port']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    ?>
</div>
<?php
// Inclusion du composant du pied de page
include 'templates/component/footer/footer.php';
?>
