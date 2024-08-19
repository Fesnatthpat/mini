<?php
session_start(); // เริ่มต้นเซสชันสำหรับใช้จัดการข้อมูลระหว่างผู้ใช้
require_once 'config/db.php'; // เรียกไฟล์การเชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en"> <!-- กำหนดภาษาเริ่มต้นของเอกสารเป็นภาษาอังกฤษ -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนดให้หน้าเว็บแสดงผลอย่างถูกต้องบนอุปกรณ์ทุกชนิด -->
    <title>Document</title> <!-- กำหนดชื่อของหน้าเว็บ -->
    <link rel="stylesheet" href="style.css"> <!-- ลิงก์ไปยังไฟล์ CSS สำหรับการจัดรูปแบบหน้าเว็บ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- ลิงก์ไปยังไฟล์ CSS ของ Font Awesome เพื่อใช้ไอคอนต่างๆ -->
</head>

<body>
    <div class="container"> <!-- เริ่มต้น container หลัก -->
        <div class="box-con1"> <!-- เริ่มต้นกล่องหลักสำหรับจัดวางเนื้อหา -->
            <div class="box-con2"> <!-- เริ่มต้นกล่องย่อยภายใน container หลัก -->

                <div class="items-1"></div> <!-- กล่องเปล่าสำหรับวางเนื้อหาหรือองค์ประกอบเพิ่มเติม (ถ้ามี) -->
                <form action="sign_in_db.php" method="POST"> <!-- ฟอร์มสำหรับส่งข้อมูลล็อกอินไปยังไฟล์ sign_in_db.php ด้วยวิธี POST -->
                    <div class="input-con"> <!-- เริ่มต้นกล่องสำหรับจัดวางอินพุตของฟอร์ม -->
                        <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีเซสชัน 'error' หรือไม่ -->
                            <div class="alert-danger" role="alert"> <!-- ถ้ามีข้อผิดพลาด แสดงกล่องข้อความเตือน -->
                                <?php
                                echo $_SESSION['error']; // แสดงข้อความข้อผิดพลาดที่เก็บในเซสชัน 'error'
                                unset($_SESSION['error']); // ลบค่าเซสชัน 'error' หลังจากแสดงผลแล้ว
                                ?>
                            </div>
                        <?php } ?>
                        <h1 class="text-login">Log In</h1> <!-- แสดงหัวข้อ "Log In" สำหรับฟอร์มล็อกอิน -->
                        <div class="input-1"> <!-- เริ่มต้นกล่องสำหรับอินพุตฟิลด์ -->
                            <input type="text" name="username" placeholder="Username" /> <!-- ฟิลด์สำหรับกรอกชื่อผู้ใช้ -->
                        </div>
                        <div class="input-1"> <!-- กล่องสำหรับฟิลด์รหัสผ่าน -->
                            <input type="password" name="password" placeholder="Password" /> <!-- ฟิลด์สำหรับกรอกรหัสผ่าน -->
                        </div>
                        <button type="submit" name="signin" class="btn-login">Login</button> <!-- ปุ่มสำหรับส่งข้อมูลล็อกอิน -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>