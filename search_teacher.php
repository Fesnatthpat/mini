<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// รับค่าการค้นหาจากฟอร์ม
$search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';
$search_level = isset($_POST['search_level']) ? $_POST['search_level'] : '';

try {
    // ดึงข้อมูลกลุ่มวิชาทั้งหมด
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    $stmt->execute();
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // สร้างคำสั่ง SQL สำหรับการค้นหา
    $query = "SELECT * FROM teacher WHERE 1=1";
    $params = [];

    if (!empty($search_name)) {
        $query .= " AND fullname LIKE :search_name";
        $params[':search_name'] = "%$search_name%";
    }

    if (!empty($search_level)) {
        $query .= " AND subject_group = :search_level";
        $params[':search_level'] = $search_level;
    }

    $query .= " ORDER BY fullname";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลคุณครู</title>
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลคุณครู</h1>
                </div>
                


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
                                <?php if (isset($_SESSION['error'])) { ?>
                                    <div class="alert-danger">
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['success'])) { ?>
                                    <div class="alert-success">
                                        <?php
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['warning'])) { ?>
                                    <div class="alert-warning">
                                        <?php
                                        echo $_SESSION['warning'];
                                        unset($_SESSION['warning']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php


                                if (!$teacherData) {
                                    echo "ไม่มีข้อมูล";
                                } else {
                                    foreach ($teacherData as $teacher) {
                                ?>
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
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>