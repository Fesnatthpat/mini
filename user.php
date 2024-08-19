<?php

session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT s.schedule_id, sj.subject_name, sj.subject_code, sj.subject_id, t.t_id, t.fullname, s.teacher_time, r.room_id, r.room_no, s.teacher_date
        FROM schedule AS s
        JOIN teacher AS t ON s.t_id = t.t_id
        JOIN subject AS sj ON s.subject_id = sj.subject_id
        JOIN room AS r ON s.room_id = r.room_id
        GROUP BY s.schedule_id, sj.subject_name, sj.subject_id, t.t_id, t.fullname, r.room_id, r.room_no";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="user.css">
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
        <div class="box-1">
            <div class="box-2">
                <text class="2">
                    <h2>ตารางสอน ระดับชั้นม.1 ภาคเรียนที่1/2567</h2> <br>
                </text>
                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th>
                                    <th>ชื่อวิชา</th>
                                    <th>ครูผู้สอน</th>
                                    <th>ห้องเรียน</th>
                                    <th>วันเวลา</th>
                                    <th>วันที่สอน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scheduleData as $schedules) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($schedules['subject_code']); ?></td>
                                        <td><?= htmlspecialchars($schedules['subject_name']); ?></td>
                                        <td><?= htmlspecialchars($schedules['fullname']); ?></td>
                                        <td><?= htmlspecialchars($schedules['room_no']); ?></td>
                                        <td><?= htmlspecialchars($schedules['teacher_time']); ?></td>
                                        <td><?= htmlspecialchars($schedules['teacher_date']); ?></td>

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