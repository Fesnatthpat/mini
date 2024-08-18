<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ตรวจสอบค่าการค้นหาและสร้างคำสั่ง SQL
$searchName = isset($_POST['search_name']) ? '%' . $_POST['search_name'] . '%' : '%';
$searchLevel = $_POST['search_level'] ?? '';

$sql = "SELECT * FROM student WHERE fullname LIKE :searchName";

if (!empty($searchLevel)) {
    $sql .= " AND level = :searchLevel";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':searchName', $searchName, PDO::PARAM_STR);

if (!empty($searchLevel)) {
    $stmt->bindParam(':searchLevel', $searchLevel, PDO::PARAM_STR);
}

$stmt->execute();
$studentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลนักเรียน</title>
    <link rel="stylesheet" href="data-student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลนักเรียน</h1>
                </div>
                <form action="search_student.php" method="POST" class="search-form">
                    <div class="form-group">
                        <label for="search_name">ชื่อ-นามสกุล</label>
                        <input type="text" name="search_name">
                    </div>
                    <div class="form-group">
                        <label for="search_level">ระดับชั้น</label>
                        <select name="search_level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-student.php'">+ เพิ่มนักเรียน</button>
                    <button class="out-student-button" onclick="window.location.href='data-student.php'">ออก</button>
                </div>

                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสประจำตัว</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>รูปถ่าย</th>
                                    <th>เบอร์โทร</th>
                                    <th>ระดับชั้น</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert-danger"><?= $_SESSION['error'];
                                                                unset($_SESSION['error']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert-success"><?= $_SESSION['success'];
                                                                unset($_SESSION['success']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['warning'])): ?>
                                    <div class="alert-warning"><?= $_SESSION['warning'];
                                                                unset($_SESSION['warning']); ?></div>
                                <?php endif; ?>
                                <?php if (!$studentData): ?>
                                    <tr>
                                        <td colspan="6">ไม่มีข้อมูล</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($studentData as $student): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($student['s_code']); ?></td>
                                            <td><?= htmlspecialchars($student['fullname']); ?></td>
                                            <td><img src="uploads_student/<?= htmlspecialchars($student['photo']); ?>" alt="รูปถ่าย" width="40px"></td>
                                            <td><?= htmlspecialchars($student['phone']); ?></td>
                                            <td><?= htmlspecialchars($student['level']); ?></td>
                                            <td>
                                                <a href="edit_student.php?s_id=<?= htmlspecialchars($student['s_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_student_db.php?delete=<?= htmlspecialchars($student['s_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>