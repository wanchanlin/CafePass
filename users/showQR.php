<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Get user's total points
$user_id = $_SESSION['id'];
$points_query = "SELECT SUM(points_earned) as total_points FROM user_visits WHERE user_id = ?";
global $conn;
if (!$conn) {
    die("Database connection failed");
}

$points_stmt = $conn->prepare($points_query);
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

// Get user's QR code
$query = "SELECT qr_code FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user doesn't have a QR code or result is null, generate one
if (!$user || !$user['qr_code']) {
    // Generate unique QR code data
    $qr_data = "user_" . $user_id . "_" . time();

    // Update user's QR code in database
    $update_query = "UPDATE users SET qr_code = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $update_stmt->bind_param("si", $qr_data, $user_id);
    $update_stmt->execute();

    // Fetch the updated user data
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Get user's recent check-ins
$checkins_query = "SELECT v.*, c.name as cafe_name, c.address 
                  FROM user_visits v 
                  INNER JOIN cafes c ON v.cafe_id = c.id 
                  WHERE v.user_id = ? 
                  ORDER BY v.visit_date DESC 
                  LIMIT 5";

$checkins_stmt = $conn->prepare($checkins_query);
if (!$checkins_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$checkins_stmt->bind_param("i", $user_id);
$checkins_stmt->execute();
$checkins = $checkins_stmt->get_result();
if (!$checkins) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}
$recent_checkins = $checkins;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My QR Code - Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
    <!-- Include QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
</head>

<body class="bg-gray-50">
    <?php include('../reusable/userDbNav.php'); ?>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto gap-4 flex flex-row">
            <!-- Sidebar -->
            <?php include('../reusable/userSidebar.php'); ?>

            <div>
                <!-- Points Summary -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">My QR Code</h2>
                            <p class="mt-1 text-sm text-gray-500">Show this QR code to earn points at cafes</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="max-w-md mx-auto text-center">
                            <?php if ($user['qr_code']): ?>
                                <div class="bg-white p-4 rounded-lg shadow-sm inline-block">
                                    <img src="../images/users/<?php echo htmlspecialchars($user['qr_code']); ?>.png"
                                        alt="User QR Code"
                                        class="w-64 h-64">
                                </div>
                                <p class="mt-4 text-sm text-gray-500">
                                    Show this QR code to cafe staff to earn points for your visit.
                                </p>
                                <div class="mt-6">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium">


                                        <a href="../images/users/<?php echo htmlspecialchars($user['qr_code']); ?>.png"
                                            download="cafe-pass-qr.png">
                                            Download QR Code
                                        </a>
                                </div>
                                </button>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v4m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No QR Code Available</h3>
                                    <p class="mt-1 text-sm text-gray-500">Please contact support to get your QR code.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-8 bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">How to Use Your QR Code</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">
                                        Visit any participating cafe and show your QR code to the staff.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">
                                        Staff will scan your code and add points to your account.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">
                                        Points will be automatically added to your total.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>

    <script>
        // Generate QR Code
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo htmlspecialchars($user['qr_code']); ?>",
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Function to download QR code
        function downloadQR() {
            const canvas = document.querySelector("#qrcode canvas");
            const image = canvas.toDataURL("image/png");
            const link = document.createElement("a");
            link.download = "cafepass-qr.png";
            link.href = image;
            link.click();
        }
    </script>

</body>

</html>