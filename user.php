<?php

session_start(); // เริ่มต้น session เพื่อใช้ข้อมูลผู้ใช้ที่เข้าสู่ระบบ

require_once 'config/db.php'; // เชื่อมต่อกับไฟล์การตั้งค่าฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ตั้งค่าข้อความแสดงข้อผิดพลาด
    header("location: index.php"); // เปลี่ยนเส้นทางผู้ใช้ไปที่หน้า index.php
    exit(); // หยุดการทำงานของสคริปต์หลังจากเปลี่ยนเส้นทาง
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลตารางสอน
$sql = "SELECT s.schedule_id, sj.subject_name, sj.subject_code, sj.subject_id, t.t_id, t.fullname, s.teacher_time, r.room_id, r.room_no, s.teacher_date
        FROM schedule AS s
        JOIN teacher AS t ON s.t_id = t.t_id
        JOIN subject AS sj ON s.subject_id = sj.subject_id
        JOIN room AS r ON s.room_id = r.room_id
        GROUP BY s.schedule_id, sj.subject_name, sj.subject_id, t.t_id, t.fullname, r.room_id, r.room_no";

$stmt = $pdo->prepare($sql); // เตรียมคำสั่ง SQL
$stmt->execute(); // รันคำสั่ง SQL
$scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากคำสั่ง SQL

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="user.css"> <!-- เชื่อมโยงไฟล์ CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- เชื่อมโยง Font Awesome สำหรับไอคอน -->
</head>

<body>
    <div class="container">
        <h2 class="text-1">ตารางสอนคุณครู</h2> <!-- หัวข้อหลัก -->
        <div class="profile-container">
            <div class="profile-con1">
                <div class="profile-con2">
                    <div class="profile-img">
                        <?php
                        // ตรวจสอบว่ามีการเข้าสู่ระบบหรือไม่
                        if (isset($_SESSION['user_login'])) {
                            $user_login = $_SESSION['user_login'];
                        }

                        try {
                            // ดึงข้อมูลของผู้ใช้จากฐานข้อมูลตาม t_id
                            $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?");
                            $stmt->execute([$user_login]);
                            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($userData) {
                                // กำหนดที่อยู่ของรูปภาพโปรไฟล์ หรือใช้รูปภาพเริ่มต้น
                                $photo = !empty($userData['photo']) ? 'uploads/' . htmlspecialchars($userData['photo']) : 'default.png';
                                echo "<img src=\"$photo\" alt=\"Profile Picture\">"; // แสดงรูปภาพโปรไฟล์
                                echo "<h3>" . htmlspecialchars($userData['fullname']) . "</h3>"; // แสดงชื่อเต็มของผู้ใช้
                                echo "<h3>" . htmlspecialchars($userData['t_code']) . "</h3>"; // แสดงรหัสของผู้ใช้
                                echo "<h3>" . htmlspecialchars($userData['urole']) . "</h3>"; // แสดงบทบาทของผู้ใช้
                            } else {
                                echo "<h3>ไม่พบข้อมูล</h3>"; // ข้อความหากไม่พบข้อมูล
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage(); // แสดงข้อความข้อผิดพลาดหากมีข้อผิดพลาดเกิดขึ้น
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
        <div class="box-1">
            <div class="box-2">
                <text class="2">
                    <h2>ตารางสอน ระดับชั้นม.1 ภาคเรียนที่1/2567</h2> <!-- หัวข้อของตารางสอน -->
                </text>
                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th> <!-- คอลัมน์รหัสวิชา -->
                                    <th>ชื่อวิชา</th> <!-- คอลัมน์ชื่อวิชา -->
                                    <th>ครูผู้สอน</th> <!-- คอลัมน์ชื่อครู -->
                                    <th>ห้องเรียน</th> <!-- คอลัมน์ห้องเรียน -->
                                    <th>วันเวลา</th> <!-- คอลัมน์วันเวลา -->
                                    <th>วันที่สอน</th> <!-- คอลัมน์วันที่สอน -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scheduleData as $schedules) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($schedules['subject_code']); ?></td> <!-- แสดงรหัสวิชา -->
                                        <td><?= htmlspecialchars($schedules['subject_name']); ?></td> <!-- แสดงชื่อวิชา -->
                                        <td><?= htmlspecialchars($schedules['fullname']); ?></td> <!-- แสดงชื่อครู -->
                                        <td><?= htmlspecialchars($schedules['room_no']); ?></td> <!-- แสดงห้องเรียน -->
                                        <td><?= htmlspecialchars($schedules['teacher_time']); ?></td> <!-- แสดงวันเวลา -->
                                        <td><?= htmlspecialchars($schedules['teacher_date']); ?></td> <!-- แสดงวันที่สอน -->

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>