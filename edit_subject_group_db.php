<?php
// เริ่มต้นเซสชันเพื่อเข้าถึงตัวแปรเซสชัน
session_start();

// รวมไฟล์การตั้งค่าฐานข้อมูล
require_once 'config/db.php';

// ตรวจสอบว่าปุ่ม 'update' ถูกกดในแบบฟอร์มหรือไม่
if (isset($_POST['update'])) {
    // ดึงข้อมูลจากการร้องขอ POST
    $subj_group_id = $_POST['subj_group_id']; // รับค่า ID ของกลุ่มวิชาจากฟอร์ม
    $subj_group_name = $_POST['subj_group_name']; // รับชื่อกลุ่มวิชาใหม่จากฟอร์ม

    try {
        // เตรียมคำสั่ง SQL สำหรับการอัปเดตข้อมูลกลุ่มวิชา
        // ใช้เครื่องหมายแทนที่ (placeholders) สำหรับพารามิเตอร์เพื่อลดความเสี่ยงจาก SQL Injection
        $sql = $pdo->prepare("UPDATE subject_group SET subj_group_name = :subj_group_name WHERE subj_group_id = :subj_group_id");
        
        // ผูกพารามิเตอร์กับคำสั่ง SQL
        // การผูกพารามิเตอร์นี้จะช่วยให้ค่าที่ป้อนไม่ถูกโจมตี
        $sql->bindParam(":subj_group_name", $subj_group_name);
        $sql->bindParam(":subj_group_id", $subj_group_id);
        
        // เรียกใช้คำสั่ง SQL ที่เตรียมไว้
        $sql->execute();

        // ตรวจสอบว่าการดำเนินการมีผลกระทบต่อแถวข้อมูลหรือไม่
        // rowCount() จะคืนค่าจำนวนแถวที่ได้รับผลกระทบ
        if ($sql->rowCount() > 0) {
            // ถ้ามีการอัปเดตข้อมูลสำเร็จ
            $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
            header("Location: data-subject_group.php"); // เปลี่ยนเส้นทางไปยังหน้าที่แสดงข้อมูลกลุ่มวิชา
        } else {
            // ถ้าไม่พบข้อมูลที่ต้องการแก้ไข
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการแก้ไข';
            header("Location: edit_subject_group.php"); // เปลี่ยนเส้นทางกลับไปที่หน้าที่แก้ไขข้อมูลกลุ่มวิชา
        }
        exit(); // ออกจากสคริปต์เพื่อหยุดการดำเนินการเพิ่มเติม
    } catch (PDOException $e) {
        // จัดการข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
        header("Location: edit_subject_group.php"); // เปลี่ยนเส้นทางกลับไปที่หน้าที่แก้ไขข้อมูลกลุ่มวิชา
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
}
?>
