<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อเก็บข้อมูลระหว่างการทำงานของสคริปต์
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูลเพื่อเชื่อมต่อกับฐานข้อมูล

if (isset($_POST['update'])) { // ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มที่มีปุ่ม 'update' หรือไม่
    $s_id = $_POST['s_id']; // เก็บค่า 's_id' จากฟอร์มในตัวแปร $s_id
    $s_code = $_POST['s_code']; // เก็บค่า 's_code' จากฟอร์มในตัวแปร $s_code
    $fullname = $_POST['fullname']; // เก็บค่า 'fullname' จากฟอร์มในตัวแปร $fullname
    $phone = $_POST['phone']; // เก็บค่า 'phone' จากฟอร์มในตัวแปร $phone
    $photo = $_FILES['photo']; // เก็บข้อมูลไฟล์รูปภาพจากฟอร์มในตัวแปร $photo

    $photo2 = $_POST['photo2']; // เก็บค่า 'photo2' จากฟอร์มในตัวแปร $photo2 ซึ่งเป็นชื่อไฟล์รูปภาพเดิม
    $upload = $_FILES['photo']['name']; // เก็บชื่อไฟล์รูปภาพที่ผู้ใช้เลือกจะอัพโหลดในตัวแปร $upload

    if ($upload != '') { // ตรวจสอบว่าผู้ใช้ได้เลือกไฟล์รูปภาพใหม่หรือไม่
        $allow = array('jpg', 'jpeg', 'png'); // กำหนดประเภทไฟล์ที่อนุญาตให้ใช้งาน
        $extention = explode(".", $photo['name']); // แยกส่วนของนามสกุลไฟล์ออกจากชื่อไฟล์
        $fileActExt = strtolower(end($extention)); // แปลงนามสกุลไฟล์เป็นตัวพิมพ์เล็ก
        $fileNew = rand() . "." . $fileActExt; // สร้างชื่อไฟล์ใหม่ด้วยหมายเลขสุ่มและนามสกุลไฟล์
        $filePath = "uploads_student/" . $fileNew; // กำหนดเส้นทางที่ไฟล์จะถูกอัพโหลด

        if (in_array($fileActExt, $allow)) { // ตรวจสอบว่านามสกุลไฟล์อยู่ในรายการที่อนุญาตหรือไม่
            if ($photo['size'] > 0 && $photo['error'] == 0) { // ตรวจสอบขนาดไฟล์และสถานะข้อผิดพลาด
                move_uploaded_file($photo['tmp_name'], $filePath); // ย้ายไฟล์ที่อัพโหลดไปยังเส้นทางที่กำหนด
            }
        }
    } else { // หากผู้ใช้ไม่เลือกไฟล์ใหม่
        $fileNew = $photo2; // ใช้ชื่อไฟล์รูปภาพเดิม
    }

    // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูลในตาราง 'student'
    $sql = $pdo->prepare("UPDATE student SET fullname = :fullname, phone = :phone, photo = :photo WHERE s_id = :s_id");
    $sql->bindParam(":fullname", $fullname); // ผูกค่าของตัวแปร $fullname กับพารามิเตอร์ :fullname
    $sql->bindParam(":phone", $phone); // ผูกค่าของตัวแปร $phone กับพารามิเตอร์ :phone
    $sql->bindParam(":photo", $fileNew); // ผูกค่าของตัวแปร $fileNew (ชื่อไฟล์รูปภาพ) กับพารามิเตอร์ :photo
    $sql->bindParam(":s_id", $s_id); // ผูกค่าของตัวแปร $s_id กับพารามิเตอร์ :s_id
    $sql->execute(); // ดำเนินการคำสั่ง SQL

    if ($sql) { // ตรวจสอบว่าการดำเนินการคำสั่ง SQL สำเร็จหรือไม่
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว'; // เก็บข้อความสำเร็จไว้ในเซสชัน
        header("location: data-student.php"); // นำผู้ใช้กลับไปยังหน้า 'data-student.php'
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากการดำเนินการคำสั่ง SQL ล้มเหลว
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้'; // เก็บข้อความข้อผิดพลาดไว้ในเซสชัน
        header("location: data-student.php"); // นำผู้ใช้กลับไปยังหน้า 'data-student.php'
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
} else { // หากไม่มีการส่งข้อมูลจากฟอร์มที่มีปุ่ม 'update'
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง'; // เก็บข้อความข้อผิดพลาดไว้ในเซสชัน
    header("location: data-student.php"); // นำผู้ใช้กลับไปยังหน้า 'data-student.php'
    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
}
?>
