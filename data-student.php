<?php

session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลนักเรียน</title>
    <link rel="stylesheet" href="data-student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลนักเรียน</h1>
                </div>
                <div class="search-form">
                    <div class="form-group">
                        <label for="search-name">ชื่อ-นามสกุล</label>
                        <input type="text" id="search-name" name="search-name">
                    </div>
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
                        <button type="button">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-student.php'">+ เพิ่มนักเรียน</button>
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
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
                                $stmt = $pdo->prepare("SELECT * FROM student");
                                $stmt->execute();
                                $studentData = $stmt->fetchAll();

                                if (!$studentData) {
                                    echo "ไม่มีข้อมูล";
                                } else {
                                    foreach ($studentData as $student) {



                                ?>
                                        <tr>
                                            <td><?= $student['s_code'] ?></td>
                                            <td><?= $student['fullname'] ?></td>
                                            <td><img src="uploads_student/<?= $student['photo'] ?>" alt="รูปถ่าย"></td>
                                            <td><?= $student['phone'] ?></td>
                                            <td><?= $student['level'] ?></td>
                                            <td>
                                                <a href="edit_student.php?s_id=<?= htmlspecialchars($student['s_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_student_db.php?delete=<?= htmlspecialchars($student['s_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
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