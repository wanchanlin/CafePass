<?php
require_once '../reusable/connection.php';
require_once 'db_helper.php';
session_start();

// Check if user is logged in and is a cafe owner
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'cafe_owner') {
    header("Location: /capstone/CoffeePass/login.php");
    exit();
}

// Get cafe owner's information
$owner_id = $_SESSION['id'];
$owner = getCafeOwner($owner_id);

// Get cafe information
$cafe = getCafe($owner_id);

// Handle QR code scan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_data'])) {
    $qr_data = $_POST['qr_data'];
    
    // Extract user ID from QR code data
    $user_id = intval($qr_data);
    
    if ($user_id > 0) {
        // Record the visit
        $insert_query = "INSERT INTO user_visits (user_id, cafe_id, points_earned) 
                        VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $points = 10; // Default points for a visit
        $stmt->bind_param("iis", $user_id, $cafe['id'], $points);
        
        if ($stmt->execute()) {
            $success_message = "Visit recorded successfully!";
            $recent_scans = [
                [
                    'user_id' => $user_id,
                    'visit_date' => date('Y-m-d H:i:s'),
                    'points' => $points
                ]
            ];
        } else {
            $error_message = "Error recording visit.";
        }
    } else {
        $error_message = "Invalid QR code data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-100">
    <?php include '../reusable/userDbNav.php'; ?>

    <div class="flex flex-row gap-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include '../reusable/cafeSideBar.php'; ?>    
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Scan QR Code</h1>
                <a href="dashboard.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Scanner Container -->
            <div class="max-w-md mx-auto">
                <div id="reader" class="w-full aspect-square mb-4"></div>
                <div id="result" class="text-center text-gray-600"></div>
            </div>

            <!-- Recent Scans -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Scans</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-center text-gray-500">
                        No recent scans
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle the scanned code
            console.log(`Code scanned = ${decodedText}`);
            
            // Send the scanned code to the server
            fetch('process-scan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    code: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('result').innerHTML = `
                        <div class="text-green-600">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <p class="mt-2">${data.message}</p>
                        </div>
                    `;
                } else {
                    document.getElementById('result').innerHTML = `
                        <div class="text-red-600">
                            <i class="fas fa-times-circle text-2xl"></i>
                            <p class="mt-2">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').innerHTML = `
                    <div class="text-red-600">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                        <p class="mt-2">Error processing scan</p>
                    </div>
                `;
            });
        }

        function onScanFailure(error) {
            // Handle scan failure
            console.warn(`QR code scan error: ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html> 