<?php
include 'templates/component/navbar/navbar.php';
include 'templates/component/header/header.php';
?>

<script>
    $(document).ready(function () {
        // Search functionality
        $(".recherche-switch").on("input", function () {
            var value = $(this).val();
            $.ajax({
                url: "templates/component/search/switch.php?value=" + encodeURIComponent(value),
                type: "POST",
                success: function (data) {
                    $(".c-switch").html(data);
                }
            });
        });
    });

    // Toggle the add switch form
    function addSwitch(event) {
        const addSwitchForm = document.getElementById('addSwitchForm');
        addSwitchForm.classList.toggle('hidden');

        // Position the form
        const buttonRect = event.target.getBoundingClientRect();
        const formRect = addSwitchForm.getBoundingClientRect();
        addSwitchForm.style.top = buttonRect.top + formRect.height > window.innerHeight ? `${buttonRect.top - formRect.height}px` : `${buttonRect.bottom}px`;
        addSwitchForm.style.left = buttonRect.left + formRect.width > window.innerWidth ? `${buttonRect.right - formRect.width}px` : `${buttonRect.left}px`;
    }

    // Submit new switch data
    function submitSwitch() {
        const name = document.getElementById('switchName').value;
        const ip = document.getElementById('switchIP').value;
        const location = document.getElementById('switchLocation').value;
        const model = document.getElementById('switchModel').value;
        const community = document.getElementById('switchCommunity').value;

        // Hide and clear the form
        document.getElementById('addSwitchForm').classList.add('hidden');
        document.getElementById('switchName').value = '';
        document.getElementById('switchIP').value = '';
        document.getElementById('switchLocation').value = '';
        document.getElementById('switchModel').value = '';
        document.getElementById('switchCommunity').value = '';

        // Send data to server
        $.ajax({
            url: "templates/component/add/switch.php",
            type: "POST",
            data: {
                name: name,
                ip: ip,
                location: location,
                model: model,
                community: community
            },
            success: function () {
                window.location.reload(); // Reload page to reflect changes
            }
        });
    }
</script>

<style>
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
        background-color: #7f8c9d;
        width: 25px;
        height: 15px;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
    }

    .port.up {
        background-color: #48bb78; /* Green for active ports */
    }

    .port.down {
        background-color: #948484; /* Red for inactive ports */
    }

    .port.warning {
        background-color: #f6ad55; /* Orange for warning ports */
    }

    .container {
        margin-left: 15rem;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .btn-supprimer {
        background-color: #f56565; /* Red background */
        color: white;
    }

    .btn-ajouter {
        background-color: #3182ce; /* Blue background */
        color: white;
    }

    .btn svg {
        fill: currentColor;
    }

    .switch-bubble {
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 8px;
        padding: 16px;
        position: absolute;
        z-index: 1000;
    }

    .switch-bubble input {
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 8px;
        border-radius: 4px;
        width: 100%;
        margin-bottom: 8px;
        color: white;
    }

    .hidden {
        display: none;
    }

    .relative {
        position: relative;
    }

    .absolute {
        position: absolute;
    }

    .inset-y-0 {
        top: 0;
        bottom: 0;
    }

    .right-0 {
        right: 0;
    }

    .flex {
        display: flex;
    }
</style>

<body class="bg-gray-100 text-gray-900">
<div class="container mx-auto p-4 flex justify-center items-center">
    <!-- Button to delete switches -->
    <button class="btn btn-supprimer mr-2" onclick="deleteSwitch()">
        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 6H5H21" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M8 6V4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V6M19 6V20C19 20.5523 18.5523 21 18 21H6C5.44772 21 5 20.5523 5 20V6H19Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>

    <!-- Search input -->
    <div class="relative w-full max-w-md">
        <input type="text" placeholder="Recherche..." class="recherche-switch w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
    </div>

    <!-- Button to add a switch -->
    <button class="btn btn-ajouter ml-2" onclick="addSwitch(event)">
        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 5V19M5 12H19" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
</div>

<!-- Form to add a new switch -->
<div id="addSwitchForm" class="switch-bubble hidden">
    <input type="text" id="switchName" placeholder="Name">
    <input type="text" id="switchIP" placeholder="IP">
    <input type="text" id="switchLocation" placeholder="Emplacement">
    <input type="text" id="switchModel" placeholder="Model">
    <input type="text" id="switchCommunity" placeholder="Community">
    <button class="btn btn-ajouter mt-2" onclick="submitSwitch()">Ajouter</button>
</div>

<!-- Container for displaying switches -->
<div class="container c-switch mx-auto p-4">
    <?php include('templates/component/home/switch.php'); ?>
</div>

<?php
include 'templates/component/footer/footer.php';
?>
