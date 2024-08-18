<?php
// เริ่มต้นเซสชันเพื่อใช้ตัวแปรเซสชัน
session_start();
require_once 'config/db.php';
// รวมไฟล์ที่มีการตั้งค่าการเชื่อมต่อฐานข้อมูล
require 'config/db.php';


// ตรวจสอบว่าปุ่ม 'signin' ถูกกดในแบบฟอร์มหรือไม่
if (isset($_POST['signin'])) {
    // รับข้อมูลจากการร้องขอ POST และลบช่องว่างที่ไม่จำเป็น
    $username = trim($_POST['username']); // รับชื่อผู้ใช้จากฟอร์ม
    $password = trim($_POST['password']); // รับรหัสผ่านจากฟอร์ม

    // ตรวจสอบว่ามีการใส่ชื่อผู้ใช้หรือไม่
    if (empty($username)) {
        $_SESSION['error'] = 'กรุณาใส่ username'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: index.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
        exit(); // ออกจากสคริปต์
    } else if (empty($password)) {
        // ตรวจสอบว่ามีการใส่รหัสผ่านหรือไม่
        $_SESSION['error'] = 'กรุณาใส่ password'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: index.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
        exit(); // ออกจากสคริปต์
    } else {
        try {
            $chk_data = $pdo->prepare("SELECT * FROM teacher 
            WHERE username = :username");
            $chk_data->bindParam(":username", $username);
            $chk_data->execute();
            $row = $chk_data->fetch(PDO::FETCH_ASSOC);
            // เตรียมคำสั่ง SQL เพื่อตรวจสอบข้อมูลผู้ใช้
            $chk_data = $pdo->prepare("SELECT * FROM teacher WHERE username = :username");
            $chk_data->bindParam(":username", $username); // ผูกค่าชื่อผู้ใช้
            $chk_data->execute(); // รันคำสั่ง SQL
            $row = $chk_data->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลผู้ใช้


            // ตรวจสอบว่ามีข้อมูลผู้ใช้ในฐานข้อมูลหรือไม่
            if ($row) {
                // ตรวจสอบรหัสผ่านที่ป้อนเข้ามาว่าตรงกับรหัสผ่านที่เข้ารหัสในฐานข้อมูลหรือไม่
                if (password_verify($password, $row['password'])) {
                    // ตรวจสอบบทบาทของผู้ใช้
                    if ($row['urole'] == 'admin') {
                        $_SESSION['admin_login'] = $row['t_id']; // เก็บ ID ผู้ใช้ในเซสชันสำหรับผู้ดูแลระบบ
                        header("location: home.php"); // เปลี่ยนเส้นทางไปยังหน้าหลักของผู้ดูแลระบบ
                        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                    } else {
                        $_SESSION['user_login'] = $row['t_id']; // เก็บ ID ผู้ใช้ในเซสชันสำหรับผู้ใช้ทั่วไป
                        header("location: user.php"); // เปลี่ยนเส้นทางไปยังหน้าหลักของผู้ใช้
                        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                    }
                } else {
                    // ถ้ารหัสผ่านไม่ตรงกัน
                    $_SESSION['error'] = 'รหัสผ่านผิด'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                    header("location: index.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
                    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                }
            } else {
                // ถ้าไม่พบข้อมูลในระบบ
                $_SESSION['error'] = 'ไม่มีข้อมูลในระบบ'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                header("location: index.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
                exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            }
        } catch (PDOException $e) {
            // แสดงข้อความข้อผิดพลาดหากเกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
