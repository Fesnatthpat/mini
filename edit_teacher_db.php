<?php
// เริ่มต้นเซสชันเพื่อใช้ตัวแปรเซสชัน
session_start();

// รวมไฟล์ที่มีการตั้งค่าการเชื่อมต่อฐานข้อมูล
require_once 'config/db.php';

// ตรวจสอบว่าปุ่ม 'update' ถูกกดในแบบฟอร์มหรือไม่
if (isset($_POST['update'])) {
    // รับข้อมูลจากการร้องขอ POST
    $t_id = $_POST['t_id']; // รับ ID ของครูจากฟอร์ม
    $t_code = $_POST['t_code']; // รับรหัสครูจากฟอร์ม
    $fullname = $_POST['fullname']; // รับชื่อเต็มของครูจากฟอร์ม
    $phone = $_POST['phone']; // รับหมายเลขโทรศัพท์ของครูจากฟอร์ม
    $subject_group = $_POST['subject_group']; // รับกลุ่มวิชาของครูจากฟอร์ม
    $photo = $_FILES['photo']; // รับข้อมูลไฟล์ภาพจากฟอร์ม

<<<<<<< HEAD
    $photo2 = $_POST['photo2']; //รูปเดิม
    $upload = $_FILES['photo']['name']; //รูปภาพใหม่
=======
    $photo2 = $_POST['photo2']; // รับชื่อไฟล์ภาพเก่าจากฟอร์ม
    $upload = $_FILES['photo']['name']; // รับชื่อไฟล์ภาพใหม่
>>>>>>> b16e91a90d2b86c224476a1822247aa3fb2cffcd

    // ตรวจสอบว่ามีการอัพโหลดไฟล์ใหม่หรือไม่
    if ($upload != '') {
        // กำหนดประเภทไฟล์ที่อนุญาต
        $allow = array('jpg', 'jpeg', 'png');
        // แยกนามสกุลของไฟล์ภาพ
        $extention = explode(".", $photo['name']);
        $fileActExt = strtolower(end($extention)); // แปลงนามสกุลเป็นตัวพิมพ์เล็ก
        $fileNew = rand() . "." . $fileActExt; // สร้างชื่อไฟล์ใหม่ที่สุ่ม
        $filePath = "uploads/" . $fileNew; // กำหนดที่อยู่ในการเก็บไฟล์

        // ตรวจสอบว่านามสกุลไฟล์อยู่ในรายการที่อนุญาต
        if (in_array($fileActExt, $allow)) {
            // ตรวจสอบขนาดและสถานะของไฟล์
            if ($photo['size'] > 0 && $photo['error'] == 0) {
                // ย้ายไฟล์จากพื้นที่ชั่วคราวไปยังที่อยู่ที่กำหนด
                move_uploaded_file($photo['tmp_name'], $filePath);
            }
        }
    } else {
        // ถ้าไม่มีการอัพโหลดไฟล์ใหม่ ให้ใช้ไฟล์เดิม
        $fileNew = $photo2;
    }

    // เตรียมคำสั่ง SQL สำหรับการอัปเดตข้อมูลครู
    // ใช้เครื่องหมายแทนที่ (placeholders) สำหรับพารามิเตอร์เพื่อป้องกัน SQL Injection
    $sql = $pdo->prepare("UPDATE teacher SET fullname = :fullname, phone = :phone, subject_group = :subject_group, photo = :photo WHERE t_id = :t_id");
    
    // ผูกพารามิเตอร์กับคำสั่ง SQL
    $sql->bindParam(":fullname", $fullname);
    $sql->bindParam(":phone", $phone);
    $sql->bindParam(":subject_group", $subject_group);
<<<<<<< HEAD
    $sql->bindParam(":photo", $fileNew);
    $sql->bindParam(":t_id", $t_id);
=======
    $sql->bindParam(":photo", $fileNew); // ใช้ชื่อไฟล์ใหม่
    $sql->bindParam(":t_id", $t_id); // ผูก ID ของครู

    // รันคำสั่ง SQL
>>>>>>> b16e91a90d2b86c224476a1822247aa3fb2cffcd
    $sql->execute();

    // ตรวจสอบว่าการดำเนินการสำเร็จหรือไม่
    if ($sql) {
        // ถ้าอัปเดตข้อมูลสำเร็จ
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("Location: teacher.php"); // เปลี่ยนเส้นทางไปยังหน้าที่แสดงข้อมูลครู
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else {
        // ถ้าไม่สามารถอัปเดตข้อมูลได้
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("Location: edit_teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าที่แก้ไขข้อมูลครู
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
} else {
    // ถ้าไม่มีไฟล์ภาพที่อัพโหลดหรือประเภทไฟล์ไม่ถูกต้อง
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("Location: edit_teacher.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าที่แก้ไขข้อมูลครู
    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
}
?>
