<?php

session_start(); // เริ่มต้นการใช้งานเซสชัน PHP

require_once 'config/db.php'; // นำเข้าการเชื่อมต่อฐานข้อมูลจากไฟล์ 'config/db.php'

if (!isset($_SESSION['admin_login'])) {
    // ตรวจสอบว่าเซสชัน 'admin_login' มีอยู่หรือไม่
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    // ตั้งค่าข้อความข้อผิดพลาดหากไม่มีสิทธิ์เข้าถึง
    header("location: index.php");
    // เปลี่ยนเส้นทางผู้ใช้ไปยังหน้า index.php
    exit();
    // หยุดการทำงานของสคริปต์หลังจากเปลี่ยนเส้นทาง
}

// เตรียมคำสั่ง SQL เพื่อดึงข้อมูลชื่อกลุ่มวิชาจากตาราง subject_group
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute();
// รันคำสั่ง SQL ที่เตรียมไว้
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
// ดึงข้อมูลทั้งหมดจากผลลัพธ์ในรูปแบบอาร์เรย์เชิงสมาคม

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <!-- กำหนดการเข้ารหัสอักขระเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ตั้งค่าการแสดงผลสำหรับหน้าจอขนาดต่างๆ -->
    <title>เพิ่มคุณครู</title>
    <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-teacher.css">
    <!-- นำเข้าภาพรวมของ CSS สำหรับการจัดรูปแบบหน้าเว็บ -->
</head>

<body>

    <div class="form-container">
        <!-- กล่องสำหรับบรรจุฟอร์ม -->
        <div class="box1">
            <!-- กล่องที่ช่วยจัดตำแหน่งฟอร์ม -->
            <div class="box2">
                <!-- กล่องภายในสำหรับการจัดรูปแบบ -->
                <h1 class="text-teacher">ข้อมูลคุณครู</h1>
                <!-- หัวข้อของฟอร์ม -->
                <hr>
                <!-- เส้นคั่นระหว่างหัวข้อและฟอร์ม -->
                <form action="sing_up_teacher_db.php" enctype="multipart/form-data" method="post">
                    <!-- ฟอร์มที่ส่งข้อมูลไปยัง 'sing_up_teacher_db.php' ด้วยวิธี POST -->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger">
                            <!-- กล่องสำหรับแสดงข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['error'];
                            // แสดงข้อความข้อผิดพลาด
                            unset($_SESSION['error']);
                            // ลบข้อผิดพลาดจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <!-- ตรวจสอบว่ามีข้อความสำเร็จในเซสชันหรือไม่ -->
                        <div class="alert-success">
                            <!-- กล่องสำหรับแสดงข้อความสำเร็จ -->
                            <?php
                            echo $_SESSION['success'];
                            // แสดงข้อความสำเร็จ
                            unset($_SESSION['success']);
                            // ลบข้อความสำเร็จจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <!-- ตรวจสอบว่ามีข้อความเตือนในเซสชันหรือไม่ -->
                        <div class="alert-warning">
                            <!-- กล่องสำหรับแสดงข้อความเตือน -->
                            <?php
                            echo $_SESSION['warning'];
                            // แสดงข้อความเตือน
                            unset($_SESSION['warning']);
                            // ลบข้อความเตือนจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <!-- กลุ่มของฟิลด์ฟอร์ม -->
                        <label for="t_code">รหัสประจำตัว</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์รหัสประจำตัว -->
                        <input type="text" name="t_code">
                        <!-- ช่องกรอกข้อมูลสำหรับรหัสประจำตัว -->
                    </div>
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ชื่อ-นามสกุล -->
                        <input type="text" name="fullname">
                        <!-- ช่องกรอกข้อมูลสำหรับชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์เบอร์โทร -->
                        <input type="text" name="phone">
                        <!-- ช่องกรอกข้อมูลสำหรับเบอร์โทร -->
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชาที่สอน</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์กลุ่มวิชาที่สอน -->
                        <select id="subject_group" name="subject_group">
                            <!-- เมนูเลือกสำหรับกลุ่มวิชาที่สอน -->
                            <option value="">เลือกกลุ่มวิชา</option>
                            <!-- ตัวเลือกแรกในเมนูที่ไม่เลือกกลุ่มวิชา -->
                            <?php foreach ($subjectGroups as $group) { ?>
                                <!-- วนลูปผ่านกลุ่มวิชาแต่ละกลุ่มที่ดึงมาจากฐานข้อมูล -->
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                    <!-- แสดงชื่อกลุ่มวิชาในเมนูเลือก -->
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์รูปถ่าย -->
                        <input type="file" id="imgInput" name="photo">
                        <!-- ช่องอัปโหลดไฟล์รูปถ่าย -->
                        <img id="previewImg">
                        <!-- รูปภาพพรีวิวของรูปถ่ายที่อัปโหลด -->
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ Username -->
                        <input type="text" name="username">
                        <!-- ช่องกรอกข้อมูลสำหรับ Username -->
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ Password -->
                        <input type="password" name="password">
                        <!-- ช่องกรอกข้อมูลสำหรับ Password -->
                    </div>
                    <div class="btn-con">
                        <!-- กล่องสำหรับปุ่ม -->
                        <div class="btn-submit">
                            <button name="signupteacher">บันทึกข้อมูล</button>
                            <!-- ปุ่มสำหรับส่งข้อมูลฟอร์ม -->
                        </div>
                        <div class="btn-out">
                            <button type="button" onclick="history.back()">ออก</button>
                            <!-- ปุ่มสำหรับกลับไปที่หน้าก่อนหน้า -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script>
    <!-- นำเข้าสคริปต์สำหรับพรีวิวรูปภาพ -->
</body>

</html>