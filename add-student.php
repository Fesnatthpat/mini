<?php
session_start(); //เริ่มต้นเซสชัน
require_once 'config/db.php'; //จะรวมไฟล์ 'db.php' ที่อยู่ในโฟลเดอร์ 'config' เข้ามาในสคริปต์ปัจจุบัน

if (!isset($_SESSION['admin_login'])) { //ตรวจสอบการเข้าถึง ว่าเป็นแอดมินหรือไม่
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php"); //การเปลี่ยนเส้นทางส่งผู้ใช้ไปยังหน้า index.php
    exit; //ใช้เพื่อป้องกันไม่ให้โค้ดที่อยู่หลัง exit() ทำงานต่อ
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ตั้งค่าการแสดงผลบนอุปกรณ์เคลื่อนที่ -->
    <title>ข้อมูลนักเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-student.css"> <!-- ลิงก์ไปยังไฟล์ CSS สำหรับจัดรูปแบบหน้าเว็บ -->
</head>

<body>

    <div class="form-container"> <!-- คอนเทนเนอร์หลักสำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องหลักที่จัดตำแหน่งฟอร์ม -->
            <div class="box2"> <!-- กล่องย่อยที่ใช้สำหรับจัดรูปแบบฟอร์ม -->
                <h1 class="text-teacher">ข้อมูลนักเรียน</h1> <!-- หัวเรื่องหลักของฟอร์ม -->
                <hr>
                <form action="sign_up_student_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับการเพิ่มข้อมูลห้องเรียน -->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger"> <!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด-->
                            <?php
                            echo $_SESSION['error']; // แสดงข้อความในเซสชันที่มีคีย์ 'error'
                            unset($_SESSION['error']); // ลบข้อมูลที่มีคีย์ 'error' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert-success">
                            <?php
                            echo $_SESSION['success'];// แสดงข้อความในเซสชันที่มีคีย์ 'success'
                            unset($_SESSION['success']);// ลบข้อมูลที่มีคีย์ 'success' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <div class="alert-warning">
                            <?php
                            echo $_SESSION['warning'];// แสดงข้อความในเซสชันที่มีคีย์ 'warning'
                            unset($_SESSION['warning']);// ลบข้อมูลที่มีคีย์ 'warning' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="student-id">รหัสประจำตัว</label> <!-- สร้างป้ายสำหรับใส่ข้อมูล -->
                        <input type="text" id="s_code" name="s_code"> <!-- สร้างกล่องข้อความเพื่อ input ไปเก็บไว้ใน s_code -->
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อ-นามสกุล</label> <!-- สร้างป้ายสำหรับใส่ข้อมูล -->
                        <input type="text" id="fullname" name="fullname"> <!-- สร้างกล่องข้อความเพื่อ input ไปเก็บไว้ใน fullname -->
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label> <!-- สร้างป้ายสำหรับใส่ข้อมูล -->
                        <input type="text" id="phone" name="phone"> <!-- สร้างกล่องข้อความเพื่อ input ไปเก็บไว้ใน phone -->
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label> <!-- สร้างป้ายหัวข้อ -->
                        <select id="level" name="level"> <!--สร้างฟิลด์เลือกระดับชั้น-->
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label> <!-- สร้างป้ายหัวข้อ -->
                        <p>image/gif, image/jpeg, image/png</p>
                        <input type="file" id="imgInput" name="photo" accept="image/gif, image/jpeg, image/png"> <!-- สร้าง input สำหรับการส่งไฟล์ โดยจำกัดนามสกุลไฟล์เป็น .gif .jpeg .png -->
                        <img id="previewImg"> <!-- โชว์ตัวอย่างของภาพ -->
                        
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"> <!--ให้ผู้ใช้กรอก username-->
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"> <!--ให้ผู้ใช้กรอก username-->
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="signupstudent">บันทึกข้อมูล</button> <!-- สร้างปุ่ม เพื่อบันทึกและส่งข้อมูล -->
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