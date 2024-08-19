<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อเก็บข้อมูลระหว่างสคริปต์
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูลเพื่อใช้ในการเชื่อมต่อฐานข้อมูล

if (isset($_GET['delete'])) { // ตรวจสอบว่ามีการส่งค่า 'delete' ผ่าน URL หรือไม่
    $delete_id = $_GET['delete']; // เก็บค่า 'delete' ที่ได้รับจาก URL ในตัวแปร $delete_id
    $deletestmt = $pdo->prepare("DELETE FROM schedule WHERE schedule_id = ?"); // เตรียมคำสั่ง SQL สำหรับลบข้อมูลจากตาราง 'room' โดยอ้างอิงจาก 'room_id'
    $deletestmt->execute([$delete_id]); // ดำเนินการคำสั่ง SQL โดยส่งค่า $delete_id ไปยังคำสั่ง SQL

    if ($deletestmt) { // ตรวจสอบว่าการลบข้อมูลสำเร็จหรือไม่
        echo "<script>alert('ลบข้อมูลสำเร็จ');</script>"; // แสดงข้อความแจ้งเตือนว่าการลบข้อมูลสำเร็จ
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ"; // เก็บข้อความสำเร็จไว้ในเซสชัน
        header("refresh:1; url=Tutorial-Schedule.php"); // รีเฟรชหน้าเพื่อนำผู้ใช้กลับไปยังหน้า 'data-classroom.php' หลังจาก 1 วินาที
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากเกิดข้อผิดพลาดในการลบข้อมูล
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล');</script>"; // แสดงข้อความแจ้งเตือนว่าการลบข้อมูลล้มเหลว
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบข้อมูล"; // เก็บข้อความข้อผิดพลาดไว้ในเซสชัน
        header("refresh:1; url=Tutorial-Schedule.php"); // รีเฟรชหน้าเพื่อนำผู้ใช้กลับไปยังหน้า 'data-classroom.php' หลังจาก 1 วินาที
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
} else { // หากไม่พบค่า 'delete' ใน URL
    echo "<script>alert('ไม่พบข้อมูลที่ต้องการลบ');</script>"; // แสดงข้อความแจ้งเตือนว่าไม่พบข้อมูลที่ต้องการลบ
    $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการลบ"; // เก็บข้อความข้อผิดพลาดไว้ในเซสชัน
    header("refresh:1; url=Tutorial-Schedule.php"); // รีเฟรชหน้าเพื่อนำผู้ใช้กลับไปยังหน้า 'data-classroom.php' หลังจาก 1 วินาที
    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
}
?>
