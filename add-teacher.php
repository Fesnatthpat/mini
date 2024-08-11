<?php

session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    $stmt->execute();
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

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
    <title>เพิ่มคุณครู</title>
    <link rel="stylesheet" href="add-teacher.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">ข้อมูลคุณครู</h1>
                <hr>
                <form action="sing_up_teacher_db.php" enctype="multipart/form-data" method="post">
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
                    <div class="form-group">
                        <label for="t_code">รหัสประจำตัว</label>
                        <input type="text" id="t_code" name="t_code">
                    </div>
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label>
                        <input type="text" id="fullname" name="fullname">
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชาที่สอน</label>
                        <select id="subject_group" name="subject_group">
                            <option value="">เลือกกลุ่มวิชา</option>
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label>
                        <input type="file" id="photo" name="photo">
                    </div>
                    <div class="form-group"> <label for="username">Username</label>
                        <input type="text" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button name="signupteacher">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                        <button type="button" onclick="history.back()">ออก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>