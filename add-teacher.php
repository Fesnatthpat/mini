<?php

session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group"); //เตรียมคำสั่ง SQL เพื่อเลือกชื่อกลุ่มวิชา (subj_group_name) จากตาราง subject_group
    $stmt->execute(); //ประมวลผลคำสั่ง SQL
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC); //ดึงข้อมูลทั้งหมดจากผลลัพธ์ของคำสั่ง SQL และจัดเก็บในรูปแบบของอาร์เรย์ที่มีคีย์เป็นชื่อคอลัมน์ (FETCH_ASSOC).
} catch (PDOException $e) { //จะจับข้อผิดพลาดที่เกิดขึ้นระหว่างการเชื่อมต่อหรือการทำงานกับฐานข้อมูล
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
                <form action="sing_up_teacher_db.php" enctype="multipart/form-data" method="post"> <!--สร้างแบบฟอร์ม ส่งข้อมูลไปยัง sing_up_teacher_db.php โดยส่งแบบ POST-->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger"> <!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['error']; // แสดงข้อความในเซสชันที่มีคีย์ 'error'
                            unset($_SESSION['error']); // ลบข้อมูลที่มีคีย์ 'error' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert-success"> <!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['success']; // แสดงข้อความในเซสชันที่มีคีย์ 'success'
                            unset($_SESSION['success']); // ลบข้อมูลที่มีคีย์ 'success' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?><!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-warning">
                            <?php
                            echo $_SESSION['warning']; // แสดงข้อความในเซสชันที่มีคีย์ 'warning'
                            unset($_SESSION['warning']); // ลบข้อมูลที่มีคีย์ 'warning' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="t_code">รหัสประจำตัว</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" name="t_code">
                    </div>
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" id="fullname" name="fullname">
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชาที่สอน</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
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
                        <label for="photo">รูปถ่าย</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="file" id="imgInput" name="photo">
                        <img id="previewImg">
                    </div>
                    <div class="form-group"> <label for="username">Username</label>
                        <input type="text" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
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
    <script src="preview_img.js"></script>
</body>

</html>