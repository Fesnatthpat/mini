<?php
session_start(); // เริ่มต้นเซสชันเพื่อจัดการกับข้อมูลเซสชันของผู้ใช้

require_once 'config/db.php'; // รวมไฟล์การเชื่อมต่อฐานข้อมูล

try {
    $stmt = $pdo->prepare("SELECT * FROM building"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง building
    $stmt->execute(); // เรียกใช้คำสั่ง SQL
    $buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดในรูปแบบ associative array
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // แสดงข้อผิดพลาดหากเกิดปัญหาในการดึงข้อมูล
}

// ตรวจสอบว่าเซสชัน 'admin_login' มีการตั้งค่าอยู่หรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ตั้งค่า error ในเซสชันหากไม่มีสิทธิ์เข้าถึง
    header("location: index.php"); // เปลี่ยนเส้นทางไปยังหน้า index.php
    exit(); // หยุดการทำงานของสคริปต์หลังจากเปลี่ยนเส้นทาง
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ตั้งค่าการแสดงผลบนอุปกรณ์มือถือ -->
    <title>เพิ่มห้องเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-subject.css"> <!-- ลิงก์ไปยังไฟล์ CSS สำหรับจัดรูปแบบหน้าเว็บ -->
</head>

<body>

    <div class="form-container"> <!-- คอนเทนเนอร์หลักสำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องหลักที่จัดตำแหน่งฟอร์ม -->
            <div class="box2"> <!-- กล่องย่อยที่ใช้สำหรับจัดรูปแบบฟอร์ม -->
                <h1 class="text-teacher">เพิ่มห้องเรียน</h1> <!-- หัวเรื่องหลักของฟอร์ม -->
                <hr> <!-- เส้นคั่นระหว่างหัวเรื่องกับฟอร์ม -->
                <form action="add_classroom_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับการเพิ่มข้อมูลห้องเรียน -->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger"> <!-- กล่องแสดงข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['error']; // แสดงข้อความข้อผิดพลาด
                            unset($_SESSION['error']); // ลบข้อผิดพลาดจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบว่ามีข้อความสำเร็จในเซสชันหรือไม่ -->
                        <div class="alert-success"> <!-- กล่องแสดงข้อความสำเร็จ -->
                            <?php
                            echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                            unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบว่ามีข้อความเตือนในเซสชันหรือไม่ -->
                        <div class="alert-warning"> <!-- กล่องแสดงข้อความเตือน -->
                            <?php
                            echo $_SESSION['warning']; // แสดงข้อความเตือน
                            unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชันหลังจากแสดงผล
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนหมายเลขห้อง -->
                        <label for="student-id">หมายเลขห้อง</label> <!-- ป้ายสำหรับหมายเลขห้อง -->
                        <input type="text" id="room_no" name="room_no"> <!-- ช่องกรอกข้อมูลหมายเลขห้อง -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการเลือกอาคารเรียน -->
                        <label for="building">อาคารเรียน</label> <!-- ป้ายสำหรับอาคารเรียน -->
                        <select id="building" name="building"> <!-- เมนูดรอปดาวน์สำหรับเลือกอาคารเรียน -->
                            <option value="">เลือกอาคารเรียน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($buildingData as $building) { ?> <!-- วนลูปผ่านข้อมูลอาคารเรียน -->
                                <option value="<?php echo htmlspecialchars($building['building_name']); ?>"> <!-- ตัวเลือกอาคารเรียน -->
                                    <?php echo htmlspecialchars($building['building_name']); ?> <!-- แสดงชื่ออาคารเรียน -->
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการเลือกชั้น -->
                        <label for="floot">ชั้น</label> <!-- ป้ายสำหรับชั้น -->
                        <select id="floot" name="floot"> <!-- เมนูดรอปดาวน์สำหรับเลือกชั้น -->
                            <option value="">เลือกชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="1">1</option> <!-- ตัวเลือกชั้น 1 -->
                            <option value="2">2</option> <!-- ตัวเลือกชั้น 2 -->
                            <option value="3">3</option> <!-- ตัวเลือกชั้น 3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการอัปโหลดรูปห้องเรียน -->
                        <label for="photo">รูปห้องเรียน</label> <!-- ป้ายสำหรับรูปห้องเรียน -->
                        <input type="file" id="photo" name="photo"> <!-- ช่องอัปโหลดไฟล์รูปภาพ -->
                    </div>
                    <div class="btn-con"> <!-- กลุ่มปุ่ม -->
                        <div class="btn-submit"> <!-- กล่องปุ่มบันทึก -->
                            <button type="submit" name="add_classroom">บันทึกข้อมูล</button> <!-- ปุ่มบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องปุ่มออก -->
                            <button type="button" onclick="history.back()">ออก</button> <!-- ปุ่มกลับไปที่หน้าเดิม -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>