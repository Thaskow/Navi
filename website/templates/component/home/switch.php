<?php
// Lire les données JSON pour les switches et les avertissements
$switchs_json = file_get_contents("data/json/switchs.json");
$switchs_data = json_decode($switchs_json);
$warning = file_get_contents("data/json/warning.json");
$warning_data = json_decode($warning, true);

// Boucle à travers chaque switch dans les données
foreach ($switchs_data->switchs as $s) {
    // Filtrer les avertissements pour le switch actuel
    $warnings = array_filter($warning_data['warnings'], function ($warning) use ($s) {
        return $warning['switch']['ip'] == $s->ip;
    });

    // Début du lien pour le détail du switch
    echo "<a href='switch_detail.php?ip={$s->ip}' class='block bg-white shadow-lg rounded-lg p-6 mb-8 hover:bg-gray-100 transition duration-200'>";

    // Conteneur pour les informations principales du switch
    echo "<div class='flex justify-between' style='position: relative'>";
    echo "<h1 class='text-2xl font-semibold mb-2'>{$s->name}</h1>";

    // Afficher les avertissements sous forme d'icône
    foreach ($warning_data['warnings'] as $warning) {
        if ($warning['switch']['ip'] == $s->ip) {
            echo '<div class="warning-svg" style="position: absolute; right: 0">';
            echo '<svg height="50px" width="50px" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.001 512.001" fill="#000000">';
            echo '<g><path style="fill:#EB6836;" d="M478.384,502.897L417.767,7.133C417.269,3.061,413.811,0,409.709,0H134.766c-2.529,0-4.913,1.178-6.449,3.187s-2.049,4.618-1.387,7.059l116.905,430.816c0.96,3.537,4.171,5.992,7.835,5.992h137.941l7.72,57.901c0.538,4.033,3.978,7.046,8.047,7.046h64.947C475.126,512,478.967,507.664,478.384,502.897z"></path>';
            echo '<path style="fill:#C74B38;" d="M417.767,7.133C417.269,3.061,413.811,0,409.709,0H134.766c-2.529,0-4.913,1.178-6.449,3.187s-2.049,4.618-1.387,7.059l58.282,214.783l259.27,0.611L417.767,7.133z"></path>';
            echo '<path style="fill:#F7D04A;" d="M383.314,2.738C381.773,0.997,379.559,0,377.235,0H102.292c-4.102,0-7.56,3.061-8.058,7.133L33.617,502.897c-0.583,4.768,3.258,9.103,8.058,9.103h64.947c4.069,0,7.509-3.012,8.047-7.046l7.72-57.901h128.668l-7.434,55.756c-0.639,4.795,3.213,9.191,8.047,9.191h64.947c4.103,0,7.56-3.061,8.058-7.133L385.292,9.103C385.576,6.796,384.856,4.478,383.314,2.738z"></path>';
            echo '<g><path style="fill:#F3B41B;" d="M102.292,0c-4.102,0-7.56,3.061-8.058,7.133L33.617,502.897c-0.583,4.768,3.258,9.103,8.058,9.103h64.947c4.069,0,7.509-3.012,8.047-7.046l7.72-57.901h92.162L118.044,0H102.292z"></path>';
            echo '<path style="fill:#F3B41B;" d="M415.781,2.731C414.241,0.994,412.031,0,409.709,0h-33.556c-4.103,0-7.56,3.061-8.058,7.133l-60.617,495.763c-0.283,2.308,0.438,4.625,1.98,6.366c1.54,1.741,3.754,2.738,6.079,2.738h34.638c4.109,0,7.571-3.07,8.06-7.151L417.77,9.086C418.046,6.782,417.323,4.467,415.781,2.731z"></path>';
            echo '</g><g><polygon style="fill:#666666;" points="311.518,0 253.247,58.272 311.418,58.272 369.689,0 "></polygon>';
            echo '<polygon style="fill:#666666;" points="218.428,0 160.156,58.272 218.327,58.272 276.599,0 "></polygon>';
            echo '<polygon style="fill:#666666;" points="125.337,0 90.895,34.441 87.981,58.272 125.236,58.272 183.508,0 "></polygon>';
            echo '</g>';
            echo '<path style="fill:#C74B38;" d="M288.619,386.436H108.653c-8.429,0-16.138-4.262-20.623-11.399c-4.485-7.138-4.977-15.933-1.318-23.527l113.122-234.812c4.429-9.194,13.854-14.567,24.029-13.695c10.167,0.875,18.542,7.782,21.337,17.597l66.845,234.813c2.111,7.414,0.656,15.193-3.988,21.344C303.411,382.908,296.326,386.436,288.619,386.436z M221.783,119.151c-1.781,0-5.371,0.542-7.323,4.593L101.34,358.557c-1.758,3.649-0.278,6.7,0.439,7.842c0.718,1.143,2.824,3.799,6.874,3.799h179.966c2.57,0,4.931-1.176,6.478-3.226c1.549-2.051,2.033-4.644,1.33-7.115l-66.845-234.813c-1.422-4.998-5.804-5.753-7.113-5.866C222.294,119.164,222.06,119.151,221.783,119.151z"></path>';
            echo '<g><path style="fill:#EB6836;" d="M191.067,341.518c-7.904,0-14.113-7.292-12.834-15.093l2.129-12.989c1.16-7.08,7.837-11.879,14.919-10.716c7.079,1.16,11.878,7.84,10.716,14.919l-2.129,12.989C202.826,336.996,197.316,341.518,191.067,341.518z"></path>';
            echo '<path style="fill:#EB6836;" d="M200.294,285.231c-7.904,0-14.113-7.292-12.834-15.093l12.422-75.772c1.16-7.08,7.841-11.878,14.919-10.716c7.079,1.16,11.878,7.84,10.716,14.919l-12.422,75.772C212.053,280.708,206.543,285.231,200.294,285.231z"></path>';
            echo '</g></svg></div>';
        }
    }
    echo "</div>";

    // Afficher les informations du switch
    echo "<p class='text-sm text-gray-600'>IP : <strong>{$s->ip}</strong></p>";
    $ports = count($s->ports);
    echo "<p class='text-sm text-gray-600'>Ports : <strong>{$ports}</strong></p>";
    echo "<p class='text-sm text-gray-600'>Emplacement : <strong>{$s->emplacement}</strong></p>";
    echo "<p class='text-sm text-gray-600'>Model : <strong>{$s->model}</strong></p>";

    // Afficher les ports avec leurs états
    echo "<div class='switch'>";
    for ($i = 0; $i < $ports; $i++) {
        $port = $s->ports[$i];
        $historyLength = count($port->history);
        $status = ($historyLength > 0 && $port->history[$historyLength - 1]->status == "UP") ? "up" : "down";

        // Vérifier les avertissements pour ce port
        $warningPort = array_filter($warnings, function ($w) use ($i) {
            return $w['switch']['port'] == $i + 1;
        });
        $warningPort = count($warningPort) > 0 ? "warning" : "";
        echo "<div class='port {$status} {$warningPort}'>" . ($i + 1) . "</div>";
    }
    echo "</div>";

    // Fin du lien
    echo "</a>";
}
?>
