<?php
$mac = $_POST['mac'];

$wl_json = file_get_contents("../../../data/json/white_list.json");
$wl_data = json_decode($wl_json, true);

// Iterate through each category in the white-list
foreach ($wl_data['white-list'] as $categoryKey => $category) {
    // Iterate through each item in the wl (whitelist) of the current category
    foreach ($category['wl'] as $itemKey => $item) {
        // Check if the current item's mac matches the mac to be deleted
        if ($item['mac'] == $mac) {
            // Remove the item from the wl
            unset($wl_data['white-list'][$categoryKey]['wl'][$itemKey]);
            // Re-index the array to remove any gaps
            $wl_data['white-list'][$categoryKey]['wl'] = array_values($wl_data['white-list'][$categoryKey]['wl']);
            break 2; // Exit both loops once the item is found and removed
        }
    }
}

// Save the updated white-list back to the JSON file
file_put_contents("../../../data/json/white_list.json", json_encode($wl_data));

?>
