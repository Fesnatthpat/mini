<?php
session_start(); // เริ่มต้นการใช้งาน session เพื่อจัดการการเข้าสู่ระบบและข้อมูลที่เก็บระหว่างหน้าเว็บ
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าการเชื่อมต่อฐานข้อมูล

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data เป็น null เพื่อใช้เก็บข้อมูลนักเรียน

if (isset($_GET['s_id'])) { // ตรวจสอบว่ามีการส่งค่า 's_id' มาหรือไม่
    $s_id = $_GET['s_id']; // รับค่า 's_id' จาก URL และเก็บไว้ในตัวแปร $s_id
    $stmt = $pdo->prepare("SELECT * FROM student WHERE s_id = ?"); // เตรียมคำสั่ง SQL เพื่อเลือกข้อมูลนักเรียนตาม 's_id'
    $stmt->execute([$s_id]); // ทำการรันคำสั่ง SQL โดยส่งค่า $s_id เป็นพารามิเตอร์
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลนักเรียนและเก็บไว้ในตัวแปร $data
}
?>

<!DOCTYPE html>
<html lang="th"> <!-- กำหนดภาษาของเอกสารเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 เพื่อรองรับตัวอักษรไทย -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ปรับขนาดให้เหมาะสมกับอุปกรณ์ที่ใช้ -->
    <title>แก้ไขข้อมูลนักเรียน</title> <!-- กำหนดชื่อของหน้าเว็บ -->
    <link rel="stylesheet" href="edit_student.css"> <!-- เชื่อมโยงไฟล์ CSS เพื่อสไตล์ของหน้าเว็บ -->
</head>

<body>
    <div class="form-container"> <!-- คอนเทนเนอร์หลักสำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องสำหรับการจัดการเลย์เอาต์ของฟอร์ม -->
            <div class="box2"> <!-- กล่องรองสำหรับการจัดรูปแบบของฟอร์ม -->
                <h1 class="text-teacher">แก้ไขข้อมูลนักเรียน</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นแนวนอนแยกหัวข้อออกจากฟอร์ม -->
                <form action="edit_student_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับแก้ไขข้อมูลนักเรียน -->
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับข้อมูลซ่อน -->
                        <input type="hidden" value="<?= htmlspecialchars($data['s_id']); ?>" name="s_id"> <!-- ฟิลด์ที่ซ่อนสำหรับรหัสนักเรียน -->
                        <label for="s_code">รหัสประจำตัว</label> <!-- ป้ายชื่อสำหรับรหัสประจำตัว -->
                        <input type="text" value="<?= htmlspecialchars($data['s_code']); ?>" name="s_code"> <!-- ฟิลด์กรอกข้อมูลสำหรับรหัสประจำตัว -->
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2"> <!-- ฟิลด์ที่ซ่อนสำหรับรูปถ่าย -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับชื่อ-นามสกุล -->
                        <label for="name">ชื่อ-นามสกุล</label> <!-- ป้ายชื่อสำหรับชื่อ-นามสกุล -->
                        <input type="text" value="<?= htmlspecialchars($data['fullname']); ?>" name="fullname"> <!-- ฟิลด์กรอกข้อมูลสำหรับชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเบอร์โทร -->
                        <label for="phone">เบอร์โทร</label> <!-- ป้ายชื่อสำหรับเบอร์โทร -->
                        <input type="text" value="<?= htmlspecialchars($data['phone']); ?>" name="phone"> <!-- ฟิลด์กรอกข้อมูลสำหรับเบอร์โทร -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                        <label for="level">ระดับชั้น</label> <!-- ป้ายชื่อสำหรับระดับชั้น -->
                        <select id="level" name="level"> <!-- เมนูดรอปดาวน์สำหรับเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกเริ่มต้นให้ผู้ใช้เลือก -->
                            <option value="1">ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="2">ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="3">ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับรูปถ่าย -->
                        <label for="photo">รูปถ่าย</label> <!-- ป้ายชื่อสำหรับรูปถ่าย -->
                        <input type="file" id="imgInput" name="photo"> <!-- ฟิลด์อัพโหลดไฟล์สำหรับรูปถ่าย -->
                        <img id="previewImg" src="uploads_student/<?= htmlspecialchars($data['photo']); ?>" alt=""> <!-- แสดงภาพพรีวิวรูปถ่าย -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับ Username -->
                        <label for="username">Username</label> <!-- ป้ายชื่อสำหรับ Username -->
                        <input type="text" value="<?= htmlspecialchars($data['username']); ?>" name="username"> <!-- ฟิลด์กรอกข้อมูลสำหรับ Username -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับ Password -->
                        <label for="password">Password</label> <!-- ป้ายชื่อสำหรับ Password -->
                        <input type="password" value="<?= htmlspecialchars($data['password']); ?>" name="password"> <!-- ฟิลด์กรอกข้อมูลสำหรับ Password -->
                    </div>
                    <div class="btn-con"> <!-- คอนเทนเนอร์สำหรับปุ่ม -->
                        <div class="btn-submit"> <!-- กล่องสำหรับปุ่มบันทึกข้อมูล -->
                            <button type="submit" name="update">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องสำหรับปุ่มออก -->
                            <button type="button" class="out-student-button" onclick="window.location.href='data-student.php'">ออก</button> <!-- ปุ่มสำหรับกลับไปยังหน้าแสดงข้อมูลนักเรียน -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script> <!-- เชื่อมโยงไฟล์ JavaScript สำหรับการแสดงพรีวิวรูปถ่าย -->
</body>

</html>
