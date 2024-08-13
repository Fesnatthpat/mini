<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}
// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT DISTINCT s.subject_id, s.subject_code, s.subject_name, t.t_id, t.fullname
        FROM subject AS s
        CROSS JOIN teacher AS t";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลตารางสอน</title>
    <link rel="stylesheet" href="data-student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลตารางสอน</h1>
                </div>
                <div class="search-form">
                    <div class="form-group">
                        <label for="search-level">ระดับชั้น</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="1">ม.1</option>
                            <option value="2">ม.2</option>
                            <option value="3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-semester">ภาคเรียนที่</label>
                        <select id="search-semester" name="search-semester">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-year">ปีการศึกษา</label>
                        <select id="search-year" name="search-year">
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-schedule.php'">+ เพิ่มตารางสอน</button>
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                </div>
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
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                                        <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                                        <td><?= htmlspecialchars($subject['fullname']); ?></td>
                                        <td>--- ห้องเรียน ---</td>
                                        <td>--- วันเวลา ---</td>
                                        <td>
                                            <a href="edit_schedule.php?schedule_id=<?= htmlspecialchars($subject['subject_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                            <a href="delete_schedule_db.php?delete=<?= htmlspecialchars($subject['subject_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
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