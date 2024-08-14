<?php
session_start(); // เริ่มต้นเซสชันเพื่อใช้ตัวแปรเซสชัน
require_once 'config/db.php'; // นำเข้าการเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ตั้งค่าข้อความผิดพลาดถ้าผู้ใช้ไม่ใช่ผู้ดูแลระบบ
    header("location: index.php"); // เปลี่ยนเส้นทางไปที่หน้า index.php
    exit; // ออกจากสคริปต์เพื่อป้องกันการทำงานต่อ
}

?>

<!DOCTYPE html>
<html lang="th"> <!-- กำหนดภาษาของเอกสารเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสอักขระเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนดการตั้งค่าการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลนักเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-student.css"> <!-- เชื่อมโยงกับไฟล์ CSS สำหรับจัดรูปแบบ -->
</head>

<body>

    <div class="form-container"> <!-- กล่องหลักที่บรรจุฟอร์ม -->
        <div class="box1"> <!-- กล่องสำหรับการจัดตำแหน่งแนวตั้ง -->
            <div class="box2"> <!-- กล่องย่อยสำหรับฟอร์ม -->
                <h1 class="text-teacher">ข้อมูลนักเรียน</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นขอบเพื่อแยกส่วน -->
                <form action="sign_up_student_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับส่งข้อมูลไปยัง sign_up_student_db.php -->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบถ้ามีข้อความผิดพลาดในเซสชัน -->
                        <div class="alert-danger"> <!-- แสดงข้อความผิดพลาด -->
                            <?php
                            echo $_SESSION['error']; // แสดงข้อความผิดพลาด
                            unset($_SESSION['error']); // ลบข้อความผิดพลาดจากเซสชันหลังจากแสดงแล้ว
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบถ้ามีข้อความสำเร็จในเซสชัน -->
                        <div class="alert-success"> <!-- แสดงข้อความสำเร็จ -->
                            <?php
                            echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                            unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชันหลังจากแสดงแล้ว
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบถ้ามีข้อความเตือนในเซสชัน -->
                        <div class="alert-warning"> <!-- แสดงข้อความเตือน -->
                            <?php
                            echo $_SESSION['warning']; // แสดงข้อความเตือน
                            unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชันหลังจากแสดงแล้ว
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับรหัสประจำตัว -->
                        <label for="student-id">รหัสประจำตัว</label> <!-- ป้ายชื่อสำหรับรหัสประจำตัว -->
                        <input type="text" id="s_code" name="s_code"> <!-- ช่องกรอกรหัสประจำตัว -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับชื่อ-นามสกุล -->
                        <label for="name">ชื่อ-นามสกุล</label> <!-- ป้ายชื่อสำหรับชื่อ-นามสกุล -->
                        <input type="text" id="fullname" name="fullname"> <!-- ช่องกรอกชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเบอร์โทร -->
                        <label for="phone">เบอร์โทร</label> <!-- ป้ายชื่อสำหรับเบอร์โทร -->
                        <input type="text" id="phone" name="phone"> <!-- ช่องกรอกเบอร์โทร -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                        <label for="level">ระดับชั้น</label> <!-- ป้ายชื่อสำหรับระดับชั้น -->
                        <select id="level" name="level"> <!-- เมนูเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="ม.1">ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="ม.2">ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="ม.3">ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับรูปถ่าย -->
                        <label for="photo">รูปถ่าย</label> <!-- ป้ายชื่อสำหรับรูปถ่าย -->
                        <p>image/gif, image/jpeg, image/png</p> <!-- ข้อความบอกประเภทไฟล์ที่สามารถอัปโหลด -->
                        <input type="file" id="photo" name="photo" accept="image/gif, image/jpeg, image/png"> <!-- ช่องอัปโหลดไฟล์ที่ยอมรับเฉพาะประเภทภาพที่กำหนด -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับ Username -->
                        <label for="username">Username</label> <!-- ป้ายชื่อสำหรับ Username -->
                        <input type="text" id="username" name="username"> <!-- ช่องกรอก Username -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับ Password -->
                        <label for="password">Password</label> <!-- ป้ายชื่อสำหรับ Password -->
                        <input type="password" id="password" name="password"> <!-- ช่องกรอก Password -->
                    </div>
                    <div class="btn-con"> <!-- กลุ่มปุ่มสำหรับบันทึกข้อมูลและออก -->
                        <div class="btn-submit"> <!-- กล่องสำหรับปุ่มบันทึก -->
                            <button type="submit" name="signupstudent">บันทึกข้อมูล</button> <!-- ปุ่มบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องสำหรับปุ่มออก -->
                            <button onclick="window.location.href='data-student.php'">ออก</button> <!-- ปุ่มออกที่เปลี่ยนเส้นทางไปยัง data-student.php -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
