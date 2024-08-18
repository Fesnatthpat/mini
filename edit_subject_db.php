<?php
// เริ่มต้นเซสชันเพื่อใช้ตัวแปรเซสชัน
session_start();

// รวมไฟล์ที่มีการตั้งค่าการเชื่อมต่อฐานข้อมูล
require_once 'config/db.php';

// ตรวจสอบว่าปุ่ม 'update' ถูกกดในแบบฟอร์มหรือไม่
if (isset($_POST['update'])) {
    // รับข้อมูลจากการร้องขอ POST
    $subject_id = $_POST['subject_id']; // รับ ID ของวิชาจากฟอร์ม
    $subject_code = $_POST['subject_code']; // รับรหัสวิชาจากฟอร์ม
    $subject_name = $_POST['subject_name']; // รับชื่อวิชาจากฟอร์ม
    $subject_group = $_POST['subject_group']; // รับกลุ่มวิชาจากฟอร์ม
    $photo = $_FILES['photo']; // รับข้อมูลไฟล์ภาพจากฟอร์ม

    $photo2 = $_POST['photo2']; // รับชื่อไฟล์ภาพเก่าจากฟอร์ม
    $upload = $_FILES['photo']['name']; // รับชื่อไฟล์ภาพใหม่

    // ตรวจสอบว่ามีการอัพโหลดไฟล์ใหม่หรือไม่
    if ($upload != '') {
        // กำหนดประเภทไฟล์ที่อนุญาต
        $allow = array('jpg', 'jpeg', 'png');
        // แยกนามสกุลของไฟล์ภาพ
        $extention = explode(".", $photo['name']);
        $fileActExt = strtolower(end($extention)); // แปลงนามสกุลเป็นตัวพิมพ์เล็ก
        $fileNew = rand() . "." . $fileActExt; // สร้างชื่อไฟล์ใหม่ที่สุ่ม
        $filePath = "uploads_subject2/" . $fileNew; // กำหนดที่อยู่ในการเก็บไฟล์

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

    // เตรียมคำสั่ง SQL สำหรับการอัปเดตข้อมูลวิชา
    // ใช้เครื่องหมายแทนที่ (placeholders) สำหรับพารามิเตอร์เพื่อป้องกัน SQL Injection
    $sql = $pdo->prepare("UPDATE subject SET subject_name = :subject_name, subject_group = :subject_group, photo = :photo WHERE subject_id = :subject_id");
    
    // ผูกพารามิเตอร์กับคำสั่ง SQL
    $sql->bindParam(":subject_name", $subject_name);
    $sql->bindParam(":subject_group", $subject_group);
    $sql->bindParam(":photo", $fileNew); // ใช้ชื่อไฟล์ใหม่
    $sql->bindParam(":subject_id", $subject_id); // ผูก ID ของวิชา

    // รันคำสั่ง SQL
    $sql->execute();

    // ตรวจสอบว่าการดำเนินการสำเร็จหรือไม่
    if ($sql) {
        // ถ้าอัปเดตข้อมูลสำเร็จ
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("Location: data-subject.php"); // เปลี่ยนเส้นทางไปยังหน้าที่แสดงข้อมูลวิชา
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else {
        // ถ้าไม่สามารถอัปเดตข้อมูลได้
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("Location: data-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าที่แสดงข้อมูลวิชา
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
} else {
    // ถ้าไม่มีไฟล์ภาพที่อัพโหลดหรือประเภทไฟล์ไม่ถูกต้อง
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("Location: data-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าที่แสดงข้อมูลวิชา
    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
}
?>
