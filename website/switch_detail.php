<?php
include 'templates/component/navbar/navbar.php';
include 'templates/component/header/header.php';
$ip = $_GET['ip'];
$switchs_json = file_get_contents("data/json/switchs.json");
$switchs_data = json_decode($switchs_json);
$warning = file_get_contents("data/json/warning.json");
$warning_data = json_decode($warning, true);
$white_list = file_get_contents("data/json/white_list.json");
$wl_data = json_decode($white_list, true);

// keep only the switch with the given IP
$s = array_values(array_filter($switchs_data->switchs, function ($s) use ($ip) {
    return $s->ip == $ip;
}))[0];

// return the warnings associated with the switch
$warnings = array_filter($warning_data['warnings'], function ($w) use ($ip) {
    return $w['switch']['ip'] == $ip;
});

?>

    <style>
        #whitelistModal {
            display: none;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        .switch {
            background-color: #2d3748;
            padding: 1rem;
            border-radius: 0.5rem;
            display: flex;
            justify-content: space-around;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .port {
            background-color: #4a5568;
            width: 25px;
            height: 15px;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            cursor: pointer;
            position: relative;
        }

        .port.up {
            background-color: #48bb78; /* green for active ports */
        }

        .port.down {
            background-color: #948484; /* red for inactive ports */
        }

        .port.warning {
            background-color: #f6ad55; /* orange for warning ports */
        }

        .container {
            margin-left: 15rem;
        }

        .history-popup {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 1rem;
            border-radius: 0.25rem;
            width: fit-content;
            z-index: 10;
            display: none;
            max-height: 300px;
            overflow-y: auto;
        }

        .history-popup.visible {
            display: block;
        }

        .history-popup table {
            width: 100%;
            color: white;
            border-collapse: collapse;
        }

        .history-popup th, .history-popup td {
            padding: 0.5rem;
            margin-right: 10px;
        }

        .history-popup th {
            background-color: #444;
            color: white;
        }

        .history-popup td {
            background-color: #333;
        }

        .history-popup thead th {
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .history-popup tbody td {
            text-align: left;
        }
    </style>
    <script>

        function handleAddToWhitelist(macAddress) {
            // Préremplir le champ macAddress dans la modal
            document.getElementById('macAddressInput').value = macAddress;
            // Ouvrir la modal
            document.getElementById('whitelistModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('whitelistModal').style.display = 'none';
        }

        function submitForm() {
            const macAddress = document.getElementById('macAddressInput').value;
            const name = document.getElementById('deviceName').value;
            const type = document.getElementById('deviceType').value;

            // whipe the form
            document.getElementById('deviceName').value = '';

            console.log(macAddress, name, type);
            // Ajouter l'adresse MAC à la whitelist via une requête AJAX
            $.ajax({
                url: 'templates/component/add/wl.php',
                type: 'POST',
                data: {
                    mac: macAddress,
                    name: name,
                    type: type
                },
                success: function (response) {
                    console.log(response);
                    // Fermer la modal après le succès
                    closeModal();
                    handleDelToWhitelist(macAddress);
                    // reload the page
                    window.location.reload();
                }
            });
        }


        function handleDelToWhitelist(macAddress) {
            // delete the mac address from the whitelist
            $.ajax({
                url: 'templates/component/del/warning.php',
                type: 'POST',
                data: {
                    mac: macAddress
                },
                success: function (response) {
                    console.log(response);
                    // reload the page
                    window.location.reload();
                }
            });

        }
    </script>
    </head>
    <body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <?php
        echo "<div class='block bg-white shadow-lg rounded-lg p-6 mb-8'>";
        echo "<div class='flex justify-between' style='position: relative'>";
        echo "<h1 class='text-2xl font-semibold mb-2'>{$s->name}</h1>";
        foreach ($warning_data['warnings'] as $warning) {
            if ($warning['switch']['ip'] == $s->ip) {
                //echo svg wrning orange logo
                echo '<div class="warning-svg" style="position: absolute; right: 0"><svg height="50px" width="50px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#EB6836;" d="M478.384,502.897L417.767,7.133C417.269,3.061,413.811,0,409.709,0H134.766 c-2.529,0-4.913,1.178-6.449,3.187s-2.049,4.618-1.387,7.059l116.905,430.816c0.96,3.537,4.171,5.992,7.835,5.992h137.941 l7.72,57.901c0.538,4.033,3.978,7.046,8.047,7.046h64.947C475.126,512,478.967,507.664,478.384,502.897z"></path> <path style="fill:#C74B38;" d="M417.767,7.133C417.269,3.061,413.811,0,409.709,0H134.766c-2.529,0-4.913,1.178-6.449,3.187 s-2.049,4.618-1.387,7.059l58.282,214.783l259.27,0.611L417.767,7.133z"></path> <path style="fill:#F7D04A;" d="M383.314,2.738C381.773,0.997,379.559,0,377.235,0H102.292c-4.102,0-7.56,3.061-8.058,7.133 L33.617,502.897c-0.583,4.768,3.258,9.103,8.058,9.103h64.947c4.069,0,7.509-3.012,8.047-7.046l7.72-57.901h128.668l-7.434,55.756 c-0.639,4.795,3.213,9.191,8.047,9.191h64.947c4.103,0,7.56-3.061,8.058-7.133L385.292,9.103 C385.576,6.796,384.856,4.478,383.314,2.738z"></path> <g> <path style="fill:#F3B41B;" d="M102.292,0c-4.102,0-7.56,3.061-8.058,7.133L33.617,502.897c-0.583,4.768,3.258,9.103,8.058,9.103 h64.947c4.069,0,7.509-3.012,8.047-7.046l7.72-57.901h92.162L118.044,0H102.292z"></path> <path style="fill:#F3B41B;" d="M415.781,2.731C414.241,0.994,412.031,0,409.709,0h-33.556c-4.103,0-7.56,3.061-8.058,7.133 l-60.617,495.763c-0.283,2.308,0.438,4.625,1.98,6.366c1.54,1.741,3.754,2.738,6.079,2.738h34.638c4.109,0,7.571-3.07,8.06-7.151 L417.77,9.086C418.046,6.782,417.323,4.467,415.781,2.731z"></path> </g> <g> <polygon style="fill:#666666;" points="311.518,0 253.247,58.272 311.418,58.272 369.689,0 "></polygon> <polygon style="fill:#666666;" points="218.428,0 160.156,58.272 218.327,58.272 276.599,0 "></polygon> <polygon style="fill:#666666;" points="125.337,0 90.895,34.441 87.981,58.272 125.236,58.272 183.508,0 "></polygon> </g> <path style="fill:#C74B38;" d="M288.619,386.436H108.653c-8.429,0-16.138-4.262-20.623-11.399 c-4.485-7.138-4.977-15.933-1.318-23.527l113.122-234.812c4.429-9.194,13.854-14.567,24.029-13.695 c10.167,0.875,18.542,7.782,21.337,17.597l66.845,234.813c2.111,7.414,0.656,15.193-3.988,21.344 C303.411,382.908,296.326,386.436,288.619,386.436z M221.783,119.151c-1.781,0-5.371,0.542-7.323,4.593L101.34,358.557 c-1.758,3.649-0.278,6.7,0.439,7.842c0.718,1.143,2.824,3.799,6.874,3.799h179.966c2.57,0,4.931-1.176,6.478-3.226 c1.549-2.051,2.033-4.644,1.33-7.115l-66.845-234.813c-1.422-4.998-5.804-5.753-7.113-5.866 C222.294,119.164,222.06,119.151,221.783,119.151z"></path> <g> <path style="fill:#EB6836;" d="M191.067,341.518c-7.904,0-14.113-7.292-12.834-15.093l2.129-12.989 c1.16-7.08,7.837-11.879,14.919-10.716c7.079,1.16,11.878,7.84,10.716,14.919l-2.129,12.989 C202.826,336.996,197.316,341.518,191.067,341.518z"></path> <path style="fill:#EB6836;" d="M200.294,285.231c-7.904,0-14.113-7.292-12.834-15.093l12.422-75.772 c1.16-7.08,7.841-11.878,14.919-10.716c7.079,1.16,11.878,7.84,10.716,14.919l-12.422,75.772 C212.053,280.708,206.543,285.231,200.294,285.231z"></path> </g> </g></svg></div>';
            }
        }
        echo "</div>";


        echo "<p class='text-sm text-gray-600'>IP : <strong>{$s->ip}</strong></p>";
        $ports = count($s->ports);
        echo "<p class='text-sm text-gray-600'>Ports : <strong>{$ports}</strong></p>";
        echo "<p class='text-sm text-gray-600'>Emplacement : <strong>{$s->emplacement}</strong></p>";
        echo "<p class='text-sm text-gray-600'>Model : <strong>{$s->model}</strong></p>";

        echo "<div class='switch'>";
        for ($i = 0; $i < $ports; $i++) {
            $port = $s->ports[$i];
            $historyLength = count($port->history);
            $status = ($historyLength > 0 && $port->history[$historyLength - 1]->status == "UP") ? "up" : "down";
            // if port in warning, add a class to the port
            $warningPort = array_filter($warnings, function ($w) use ($i) {
                return $w['switch']['port'] == $i + 1;
            });
            $warningPort = count($warningPort) > 0 ? "warning" : "";
            echo "<div class='port {$status} {$warningPort}' onmouseover='showPortHistory(event, {$port->numero})' onmouseout='hidePortHistory()'>" . $port->numero . "</div>";
        }
        echo "</div>";

        echo "</div>";

        // Affichage des warnings associés
        echo "<div class='bg-white shadow-lg rounded-lg p-6 mb-8'>";
        echo "<h2 class='text-xl font-semibold mb-4'>Warnings</h2>";
        echo "<table class='min-w-full bg-white mb-4'>";
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
                <th class='py-2'>Port</th>
                <th class='py-2'>Action</th>
              </tr></thead>";
        echo "<tbody>";

        foreach ($warnings as $warning) {
            echo "<tr>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['type']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['interconnexion']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['vendor']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['date']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['status']) . "</td>";
            $mac = strtoupper($warning['mac']);
            $mac = str_split($mac, 2);
            $mac = implode(":", $mac);
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($mac) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['name']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['check']['status']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['check']['base']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($warning['switch']['port']) . "</td>";
            echo "<td class='border px-4 py-2'>
                    <button class='bg-green-500 text-white px-2 py-1 rounded' onclick='handleAddToWhitelist(\"{$warning['mac']}\")'>Ajouter</button>
                    <button class='bg-red-500 text-white px-2 py-1 rounded' onclick='handleDelToWhitelist(\"{$warning['mac']}\")'>Supprimer</button>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        ?>
    </div>


    <!-- Popup for port history -->
    <div id="historyPopup" class="history-popup"></div>

    <?php
    // for every ports reverse the history to show the last status first
    foreach ($s->ports as $port) {
        $port->history = array_reverse($port->history);
    }
    setcookie("ports", json_encode($s->ports), time() + 3600, "/");
    $_COOKIE['ports'] = json_encode($s->ports);
    ?>


    <script>
        // get back the ports data from the cookie
        const portHistoryData = JSON.parse('<?php echo $_COOKIE['ports']; ?>');

        let timeout;

        function showPortHistory(event, portId) {
            clearTimeout(timeout);  // Clear any previous timeout to keep the popup open
            const historyPopup = document.getElementById('historyPopup');
            const portData = portHistoryData.find(port => port.numero === portId);

            if (portData) {
                let historyContent = '<h3 class="text-lg font-semibold mb-2">Port ' + portId + ' History</h3>';
                historyContent += '<table><thead><tr><th>Date</th><th>Status</th><th>Verify</th><th>Base</th><th>Interconnexion</th><th>Vendor</th><th>Name</th><th>Mac</th></tr></thead><tbody>';

                portData.history.forEach(entry => {
                    let mac = entry.mac ? entry.mac.toUpperCase().match(/.{1,2}/g).join(':') : null;
                    historyContent += `<tr><td>${entry.date}</td><td>${entry.status}</td><td>${entry.check.status ? (entry.check.status == 1 ? "Oui" : "Non") : '-'}</td><td>${entry.check.base ? entry.check.base : "-"}</td><td>${entry.interconnexion ? 'Oui' : "-"}</td><td>${entry.vendor ? entry.vendor : "-"}</td><td>${entry.name ? entry.name : "-"}</td><td>${entry.mac ? mac : "-"}</td></tr>`;
                });
                historyContent += '</tbody></table>';
                historyPopup.innerHTML = historyContent;
                historyPopup.classList.add('visible');

                // Position the popup
                const portRect = event.target.getBoundingClientRect();
                const popupRect = historyPopup.getBoundingClientRect();
                if (portRect.top + popupRect.height > window.innerHeight) {
                    historyPopup.style.top = `${portRect.top - popupRect.height}px`;
                } else {
                    historyPopup.style.top = `${portRect.bottom}px`;
                }
                if (portRect.left + popupRect.width > window.innerWidth) {
                    historyPopup.style.left = `${portRect.right - popupRect.width}px`;
                } else {
                    historyPopup.style.left = `${portRect.left}px`;
                }
            }

            // Show the popup if not already visible
            if (!historyPopup.classList.contains('visible')) {
                historyPopup.classList.add('visible');
            }
        }

        function hidePortHistory() {
            // Set a timeout to hide the popup only if the mouse leaves both the port and popup
            timeout = setTimeout(() => {
                const historyPopup = document.getElementById('historyPopup');
                historyPopup.classList.remove('visible');
            }, 300);  // 500ms delay before hiding
        }

        document.getElementById('historyPopup').addEventListener('mouseover', () => {
            clearTimeout(timeout);
        });

        document.getElementById('historyPopup').addEventListener('mouseout', () => {
            hidePortHistory();
        });
    </script>
    <!-- Modal for adding to whitelist -->
    <div id="whitelistModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Ajouter à la whitelist</h2>
                <input type="hidden" id="macAddressInput">
                <div class="mb-4">
                    <label for="deviceName" class="block text-gray-700 font-semibold mb-2">Nom du dispositif</label>
                    <input type="text" id="deviceName" name="deviceName" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="deviceType" class="block text-gray-700 font-semibold mb-2">Type de dispositif</label>
                    <select id="deviceType" name="deviceType" class="w-full px-3 py-2 border rounded">
                        <?php
                        // for every white-list name
                        for ($i = 0; $i < count($wl_data['white-list']); $i++) {
                            $type = ucfirst($wl_data['white-list'][$i]['name']);
                            echo "<option value='{$i}'>{$type}</option>";
                        }

                        ?>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded mr-2" onclick="closeModal()">
                        Annuler
                    </button>
                    <button onclick="submitForm()" class="submit-form bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
                </div>
        </div>
    </div>


<?php
include 'templates/component/footer/footer.php';
?>