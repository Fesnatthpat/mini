<?php

session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_login'])) {
    header("location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <h2 class="text-1">ตารางสอนคุณครู</h2>
        <div class="profile-container">
            <div class="profile-con1">
                <div class="profile-con2">
                    <div class="profile-img">
                        <?php
                        if (isset($_SESSION['user_login'])) {
                            $user_login = $_SESSION['user_login'];
                        }

                        try {
                            $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?");
                            $stmt->execute([$user_login]);
                            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($userData) {
                                $photo = !empty($userData['photo']) ? 'uploads/' . htmlspecialchars($userData['photo']) : 'default.png'; // กำหนด default รูปภาพ
                                echo "<img src=\"$photo\" alt=\"Profile Picture\">";
                                echo "<h3>" . htmlspecialchars($userData['fullname']) . "</h3>";
                                echo "<h3>" . htmlspecialchars($userData['t_code']) . "</h3>";
                                echo "<h3>" . htmlspecialchars($userData['urole']) . "</h3>";
                            } else {
                                echo "<h3>ไม่พบข้อมูล</h3>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                        <!-- ฟอร์มสำหรับ logout -->
                        <form action="logout.php" method="post">
                            <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>