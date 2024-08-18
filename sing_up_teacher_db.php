<?php
// เริ่มต้นเซสชันเพื่อใช้ตัวแปรเซสชัน
session_start();
require_once 'config/db.php';

// รวมไฟล์ที่มีการตั้งค่าการเชื่อมต่อฐานข้อมูล
require 'config/db.php';

// ตรวจสอบว่าปุ่ม 'signupteacher' ถูกกดในแบบฟอร์มหรือไม่
if (isset($_POST['signupteacher'])) {
    // รับข้อมูลจากการร้องขอ POST
    $t_code = $_POST['t_code']; // รหัสประจำตัวครู
    $fullname = $_POST['fullname']; // ชื่อเต็ม
    $phone = $_POST['phone']; // เบอร์โทรศัพท์
    $subject_group = $_POST['subject_group']; // กลุ่มวิชา
    $photo = $_FILES['photo']; // ข้อมูลไฟล์รูปภาพ
    $username = $_POST['username']; // ชื่อผู้ใช้
    $password = $_POST['password']; // รหัสผ่าน
    $urole = 'user'; // ระบุบทบาทเป็น 'user'

    // ตรวจสอบข้อมูลที่กรอกเข้ามา
    if (empty($t_code)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสประจำตัว'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($fullname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($phone)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($subject_group)) {
        $_SESSION['error'] = 'กรุณาเลือกกลุ่มวิชา'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($photo['name'])) {
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($username)) {
        $_SESSION['error'] = 'กรุณากรอก Username'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอก Password'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'กรุณากรอกรหัสให้มีความยาว 5-20 ตัวอักษร'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
        exit(); // ออกจากสคริปต์
    } else {
        try {
            // ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่ในฐานข้อมูลแล้วหรือไม่
            $chk_username = $pdo->prepare("SELECT username FROM teacher WHERE username = :username");
            $chk_username->bindParam(":username", $username); // ผูกค่าชื่อผู้ใช้
            $chk_username->execute(); // รันคำสั่ง SQL
            $row = $chk_username->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลชื่อผู้ใช้

            if ($row) {
                $_SESSION['warning'] = 'มี Username นี้อยู่ในระบบแล้ว'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
                exit(); // ออกจากสคริปต์
            } else {
                // ตรวจสอบและจัดการไฟล์รูปภาพ
                $allow = array('jpg', 'jpeg', 'png'); // อนุญาตเฉพาะไฟล์ประเภท jpg, jpeg, png
                $extention = explode(".", $photo['name']); // แยกนามสกุลไฟล์
                $fileActExt = strtolower(end($extention)); // นามสกุลไฟล์
                $fileNew = rand() . "." . $fileActExt; // สร้างชื่อไฟล์ใหม่
                $filePath = "uploads/" . $fileNew; // ที่อยู่ไฟล์

                // ตรวจสอบประเภทไฟล์
                if (in_array($fileActExt, $allow)) {
                    // ตรวจสอบขนาดและข้อผิดพลาดของไฟล์
                    if ($photo['size'] > 0 && $photo['error'] == 0) {
                        // อัพโหลดไฟล์รูปภาพ
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) {
                            // เข้ารหัสรหัสผ่าน
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            // เตรียมและรันคำสั่ง SQL เพื่อนำข้อมูลไปบันทึกในฐานข้อมูล
                            $stmt = $pdo->prepare("INSERT INTO teacher(t_code, fullname, phone, subject_group, photo, username, password, urole)
                                        VALUES(:t_code, :fullname, :phone, :subject_group, :photo, :username, :password, :urole)");
                            $stmt->bindParam(":t_code", $t_code); // ผูกค่ารหัสประจำตัว
                            $stmt->bindParam(":fullname", $fullname); // ผูกค่าชื่อเต็ม
                            $stmt->bindParam(":phone", $phone); // ผูกค่าเบอร์โทรศัพท์
                            $stmt->bindParam(":subject_group", $subject_group); // ผูกค่ากลุ่มวิชา
                            $stmt->bindParam(":photo", $fileNew); // ผูกค่าไฟล์รูปภาพ
                            $stmt->bindParam(":username", $username); // ผูกค่าชื่อผู้ใช้
                            $stmt->bindParam(":password", $passwordHash); // ผูกค่ารหัสผ่านที่เข้ารหัส
                            $stmt->bindParam(":urole", $urole); // ผูกค่าบทบาท
                            $stmt->execute(); // รันคำสั่ง SQL
                            $_SESSION['success'] = 'สมัครเรียบร้อยแล้ว'; // เก็บข้อความสำเร็จในเซสชัน
                            header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
                            exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                        } else {
                            $_SESSION['error'] = 'การอัพโหลดรูปภาพล้มเหลว'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                            header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
                            exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                        }
                    } else {
                        $_SESSION['error'] = 'ไฟล์รูปภาพไม่ถูกต้อง'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                        header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
                        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                    }
                } else {
                    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง'; // เก็บข้อความแสดงข้อผิดพลาดในเซสชัน
                    header("location: add-teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าเพิ่มครู
                    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                }
            }
        } catch (PDOException $e) {
            // แสดงข้อความข้อผิดพลาดหากเกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
            echo $e->getMessage();
        }
    }
}
?>
