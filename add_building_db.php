<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อใช้ในการเก็บข้อมูลระหว่างการทำงานของสคริปต์

require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูล เพื่อเตรียมเชื่อมต่อกับฐานข้อมูล

if (isset($_POST['add_building'])) { // ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์มที่มีปุ่มชื่อ 'add_building' หรือไม่
    $name_building = $_POST['name_building']; // เก็บค่าชื่ออาคารที่ผู้ใช้ป้อนมาในตัวแปร $name_building

    if (empty($name_building)) { // ตรวจสอบว่าผู้ใช้ได้กรอกชื่ออาคารหรือไม่
        $_SESSION['error'] = 'กรุณากรอกข้อมูล'; // หากผู้ใช้ไม่ได้กรอกชื่ออาคาร จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: building.php"); // จากนั้นเปลี่ยนเส้นทางกลับไปยังหน้า building.php
        exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากผู้ใช้กรอกข้อมูลแล้ว
        try {
            $chk_building_name = $pdo->prepare("SELECT building_name FROM building WHERE building_name = :building_name"); // เตรียมคำสั่ง SQL เพื่อเช็คว่าชื่ออาคารนี้มีอยู่ในฐานข้อมูลหรือไม่
            $chk_building_name->bindParam(":building_name", $name_building); // ผูกค่าชื่ออาคารที่ผู้ใช้กรอกเข้ากับพารามิเตอร์ในคำสั่ง SQL
            $chk_building_name->execute(); // ดำเนินการคำสั่ง SQL
            $buildingData = $chk_building_name->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลจากผลลัพธ์ของคำสั่ง SQL

            if ($buildingData) { // ตรวจสอบว่าพบชื่ออาคารในฐานข้อมูลหรือไม่
                $_SESSION['warning'] = 'มีข้อมูลในระบบแล้ว'; // หากพบชื่ออาคาร จะแสดงข้อความเตือนผ่านเซสชัน
                header("location: building.php"); // จากนั้นเปลี่ยนเส้นทางกลับไปยังหน้า building.php
                exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            } else { // หากไม่พบชื่ออาคาร
                $stmt = $pdo->prepare("INSERT INTO building (building_name) VALUES (:building_name)"); // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลชื่ออาคารลงในฐานข้อมูล
                $stmt->bindParam(":building_name", $name_building); // ผูกค่าชื่ออาคารที่ผู้ใช้กรอกเข้ากับพารามิเตอร์ในคำสั่ง SQL
                $stmt->execute(); // ดำเนินการคำสั่ง SQL เพื่อเพิ่มข้อมูลในฐานข้อมูล
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว'; // แสดงข้อความสำเร็จผ่านเซสชัน
                header("location: building.php"); // จากนั้นเปลี่ยนเส้นทางกลับไปยังหน้า building.php
                exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            }
        } catch (PDOException $e) {
            echo $e->getMessage(); // หากเกิดข้อผิดพลาดระหว่างการทำงาน จะแสดงข้อความผิดพลาด
        }
    }
}
?>
