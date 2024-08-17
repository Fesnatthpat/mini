<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อเก็บข้อมูลระหว่างการทำงานของสคริปต์
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูลเพื่อเชื่อมต่อกับฐานข้อมูล

if (isset($_POST['update'])) { // ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มที่มีปุ่ม 'update' หรือไม่
    $building_id = $_POST['building_id']; // เก็บค่า 'building_id' จากฟอร์มในตัวแปร $building_id
    $building_name = $_POST['building_name']; // เก็บค่า 'building_name' จากฟอร์มในตัวแปร $building_name

    $sql = $pdo->prepare("UPDATE building SET building_name = :building_name, building_id = :building_id WHERE building_id = :building_id"); // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูลในตาราง 'building'
    $sql->bindParam(":building_id", $building_id); // ผูกค่าของตัวแปร $building_id กับพารามิเตอร์ :building_id
    $sql->bindParam(":building_name", $building_name); // ผูกค่าของตัวแปร $building_name กับพารามิเตอร์ :building_name
    $sql->execute(); // ดำเนินการคำสั่ง SQL

    if ($sql) { // ตรวจสอบว่าการดำเนินการคำสั่ง SQL สำเร็จหรือไม่
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว'; // เก็บข้อความสำเร็จไว้ในเซสชัน
        header("location: building.php"); // นำผู้ใช้กลับไปยังหน้า 'building.php'
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากการดำเนินการคำสั่ง SQL ล้มเหลว
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้'; // เก็บข้อความข้อผิดพลาดไว้ในเซสชัน
        header("location: building.php"); // นำผู้ใช้กลับไปยังหน้า 'building.php'
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    }
}
?>
