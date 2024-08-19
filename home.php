<?php
session_start(); // เริ่มต้นเซสชันสำหรับใช้ข้อมูลระหว่างการใช้งาน
require_once 'config/db.php'; // เรียกไฟล์การเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) { // เช็คว่าเซสชัน 'admin_login' ถูกตั้งค่าหรือไม่
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ถ้าไม่ถูกตั้งค่า ให้ตั้งค่าเซสชัน 'error' เพื่อเก็บข้อความแสดงข้อผิดพลาด
    header("location: index.php"); // เปลี่ยนเส้นทางไปที่หน้า index.php
    exit(); // ยุติการทำงานของสคริปต์
}
?>

<!DOCTYPE html>
<html lang="en"> <!-- กำหนดภาษาเริ่มต้นของเอกสารเป็นภาษาอังกฤษ -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนดขนาดมุมมองสำหรับหน้าจอ -->
    <title>ระบบบริหารจัดการข้อมูล</title> <!-- กำหนดชื่อของหน้าเว็บ -->
    <link rel="stylesheet" href="home.css"> <!-- ลิงก์ไปยังไฟล์ CSS สำหรับการจัดรูปแบบ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- ลิงก์ไปยังไฟล์ CSS ของ Font Awesome เพื่อใช้ไอคอน -->
</head>

<body>
    <div class="container"> <!-- เริ่มต้น container หลัก -->
        <h2 class="text-1">ระบบบริหารจัดการข้อมูล</h2> <!-- แสดงข้อความหัวข้อหลัก -->
        <nav class="nav-con"> <!-- เริ่มต้นการนำทาง -->
            <ul class="menu-con"> <!-- เริ่มต้นรายการเมนู -->
                <li><a href="teacher.php">ข้อมูลคุณครู</a></li> <!-- ลิงก์ไปยังหน้า teacher.php -->
                <li><a href="data-student.php">ข้อมูลนักเรียน</a></li> <!-- ลิงก์ไปยังหน้า data-student.php -->
                <li><a href="data-subject.php">ข้อมูลรายวิชา</a></li> <!-- ลิงก์ไปยังหน้า data-subject.php -->
                <li><a href="data-classroom.php">ข้อมูลห้องเรียน</a></li> <!-- ลิงก์ไปยังหน้า data-classroom.php -->
                <li><a href="Tutorial-Schedule.php">ข้อมูลตารางสอน</a></li> <!-- ลิงก์ไปยังหน้า Tutorial-Schedule.php -->
                <li><a href="data-subject_group.php">ข้อมูลกลุ่มวิชา</a></li> <!-- ลิงก์ไปยังหน้า data-subject_group.php -->
                <li><a href="building.php">ข้อมูลอาคารเรียน</a></li> <!-- ลิงก์ไปยังหน้า building.php -->
            </ul>
        </nav>

        <div class="profile-container"> <!-- เริ่มต้นส่วนของโปรไฟล์ -->
            <div class="profile-con1"> <!-- เริ่มต้นกล่องโปรไฟล์ -->
                <div class="profile-con2"> <!-- เริ่มต้นส่วนหัวของโปรไฟล์ -->
                    <div class="profile-img"> <!-- เริ่มต้นส่วนแสดงรูปโปรไฟล์ -->
                        <?php
                        if (isset($_SESSION['admin_login'])) { // ตรวจสอบว่ามีการตั้งค่าเซสชัน 'admin_login' หรือไม่
                            $admin_login = $_SESSION['admin_login']; // เก็บค่าเซสชัน 'admin_login' ในตัวแปร $admin_login
                        }

                        try {
                            $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลคุณครูจากฐานข้อมูลโดยใช้ t_id
                            $stmt->execute([$admin_login]); // ดำเนินการคำสั่ง SQL พร้อมส่งค่า $admin_login เป็นพารามิเตอร์
                            $adminData = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลของคุณครูจากฐานข้อมูลและเก็บไว้ในตัวแปร $adminData

                            if ($adminData) { // ถ้าพบข้อมูลคุณครูในฐานข้อมูล
                                $photo = !empty($adminData['photo']) ? 'uploads/' . htmlspecialchars($adminData['photo']) : 'default.png'; // ถ้าข้อมูลรูปภาพมีค่า ให้ใช้รูปนั้น ถ้าไม่มีกำหนดเป็นรูป 'default.png'
                                echo "<img src=\"$photo\" alt=\"Profile Picture\">"; // แสดงรูปโปรไฟล์
                                echo "<h3>" . htmlspecialchars($adminData['fullname']) . "</h3>"; // แสดงชื่อเต็มของคุณครู
                                echo "<h3>" . htmlspecialchars($adminData['t_code']) . "</h3>"; // แสดงรหัสคุณครู
                                echo "<h3>" . htmlspecialchars($adminData['urole']) . "</h3>"; // แสดงบทบาทของคุณครู
                            }
                        } catch (PDOException $e) { // ถ้ามีข้อผิดพลาดในการดึงข้อมูลจากฐานข้อมูล
                            echo "Error: " . $e->getMessage(); // แสดงข้อความข้อผิดพลาด
                        }
                        ?>
                        <!-- ฟอร์มสำหรับ logout -->
                        <form action="logout.php" method="post"> <!-- ฟอร์มเพื่อทำการออกจากระบบ -->
                            <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button> <!-- ปุ่ม Logout -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>