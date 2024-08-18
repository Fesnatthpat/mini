<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ดึงข้อมูลกลุ่มวิชาทั้งหมด
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute();
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ตรวจสอบค่าการค้นหาและสร้างคำสั่ง SQL
$searchName = isset($_POST['search_name']) ? '%' . $_POST['search_name'] . '%' : '%';
$searchLevel = $_POST['search_level'] ?? '';
$sql = "SELECT * FROM teacher WHERE fullname LIKE :searchName" . (!empty($searchLevel) ? " AND subject_group = :searchLevel" : '');

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':searchName', $searchName, PDO::PARAM_STR);
if (!empty($searchLevel)) $stmt->bindParam(':searchLevel', $searchLevel, PDO::PARAM_STR);

$stmt->execute();
$teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลคุณครู</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลคุณครู</h1>
                </div>
                <form class="search-form" method="POST" action="search_teacher.php">
                    <div class="form-group">
                        <label for="search_name">ชื่อ-นามสกุล</label>
                        <input type="text" name="search_name">
                    </div>
                    <div class="form-group">
                        <label for="search_level">กลุ่มวิชาที่สอน</label>
                        <select name="search_level">
                            <option value="">เลือกกลุ่มวิชา</option>
                            <?php foreach ($subjectGroups as $group): ?>
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>">
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <div class="btn-con">
                    <button class="out-student-button" onclick="window.location.href='teacher.php'">ออก</button>
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
                                    <th>กลุ่มวิชาที่สอน</th>
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
                                <?php if (!$teacherData): ?>
                                    <tr>
                                        <td colspan="6">ไม่มีข้อมูล</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($teacherData as $teacher): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($teacher['t_code']); ?></td>
                                            <td><?= htmlspecialchars($teacher['fullname']); ?></td>
                                            <td><img width="40px" src="uploads/<?= htmlspecialchars($teacher['photo']); ?>" alt="รูปถ่าย"></td>
                                            <td><?= htmlspecialchars($teacher['phone']); ?></td>
                                            <td><?= htmlspecialchars($teacher['subject_group']); ?></td>
                                            <td>
                                                <a href="edit_teacher.php?t_id=<?= htmlspecialchars($teacher['t_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_tracher_db.php?delete=<?= htmlspecialchars($teacher['t_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
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