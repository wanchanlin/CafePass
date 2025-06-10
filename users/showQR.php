<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Get user's QR code
$user_id = $_SESSION['id'];
$query = "SELECT qr_code FROM users WHERE id = ?";
$stmt = $connect->prepare($query);
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
    $update_stmt = $connect->prepare($update_query);
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
$checkins_stmt = $connect->prepare($checkins_query);
$checkins_stmt->bind_param("i", $user_id);
$checkins_stmt->execute();
$recent_checkins = $checkins_stmt->get_result();
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
        <div class="max-w-7xl mx-auto">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <!-- QR Code Section -->
                <div class="md:col-span-1">
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">My QR Code</h3>
                        <div class="flex flex-col items-center">
                            <div id="qrcode" class="p-4 bg-white rounded-lg shadow-sm mb-4"></div>
                            <p class="text-sm text-gray-500 text-center mb-4">
                                Show this QR code to cafe staff to check in and earn points
                            </p>
                            <button onclick="downloadQR()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Download QR Code
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Check-ins -->
                <div class="mt-8 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Recent Check-ins</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($recent_checkins) > 0): ?>
                                <?php while ($checkin = mysqli_fetch_assoc($recent_checkins)): ?>
                                    <div class="p-6">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($checkin['cafe_name']); ?>
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($checkin['address']); ?>
                                                </p>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    Checked in on <?php echo date('M d, Y h:i A', strtotime($checkin['visit_date'])); ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?php echo $checkin['points_earned']; ?> points earned
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No check-ins yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Visit a cafe and show your QR code to check in.</p>
                                </div>
                            <?php endif; ?>
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