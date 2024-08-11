<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
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
        <h2 class="text-1">ระบบบริหารจัดการข้อมูล</h2>
        <nav class="nav-con">
            <ul class="menu-con">
                <li><a href="teacher.php">ข้อมูลคุณครู</a></li>
                <li><a href="data-student.php">ข้อมูลนักเรียน</a></li>
                <li><a href="data-subject.php">ข้อมูลรายวิชา</a></li>
                <li><a href="data-classroom.php">ข้อมูลห้องเรียน</a></li>
                <li><a href="Tutorial-Schedule.php">ข้อมูลตารางสอน</a></li>
                <li><a href="data-subject_group.php">ข้อมูลกลุ่มวิชา</a></li>
                <li><a href="building.php">ข้อมูลอาคารเรียน</a></li>
            </ul>
        </nav>

        <div class="profile-container">
            <div class="profile-con1">
                <div class="profile-con2">
                    <div class="profile-img">
                        <?php
                        if (isset($_SESSION['admin_login'])) {
                            $admin_login = $_SESSION['admin_login'];
                        }

                        try {
                            $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?");
                            $stmt->execute([$admin_login]);
                            $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($adminData) {
                                $photo = !empty($adminData['photo']) ? 'uploads/' . htmlspecialchars($adminData['photo']) : 'default.png'; // กำหนด default รูปภาพ
                                echo "<img src=\"$photo\" alt=\"Profile Picture\">";
                                echo "<h3>" . htmlspecialchars($adminData['fullname']) . "</h3>";
                                echo "<h3>" . htmlspecialchars($adminData['t_code']) . "</h3>";
                                echo "<h3>" . htmlspecialchars($adminData['urole']) . "</h3>";
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