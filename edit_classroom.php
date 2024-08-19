<?php
session_start(); // เริ่มต้นการจัดการเซสชันสำหรับผู้ใช้
require_once 'config/db.php'; // นำเข้าการเชื่อมต่อฐานข้อมูลจากไฟล์ 'config/db.php'

// เตรียมคำสั่ง SQL เพื่อดึงชื่ออาคารเรียนจากตาราง 'building'
$stmt = $pdo->prepare("SELECT building_name FROM building");
$stmt->execute(); // ดำเนินการคำสั่ง SQL
$buildingData = $stmt->fetchAll(); // ดึงข้อมูลทั้งหมดจากคำสั่ง SQL

try {
    // พยายามเตรียมคำสั่ง SQL เพื่อดึงชั้นจากตาราง 'room'
    $stmt = $pdo->prepare("SELECT floot FROM room");
    $stmt->execute(); // ดำเนินการคำสั่ง SQL
    $dataroom = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากคำสั่ง SQL
} catch (PDOException $e) {
    // หากเกิดข้อผิดพลาดในคำสั่ง SQL ให้แสดงข้อความข้อผิดพลาด
    echo "Error: " . $e->getMessage();
}

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data

// ตรวจสอบว่ามีการส่งค่า 'room_id' มาใน URL หรือไม่
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id']; // ดึงค่า 'room_id' จาก URL
    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลของห้องที่ระบุด้วย 'room_id'
    $stmt = $pdo->prepare("SELECT * FROM room WHERE room_id = ?");
    $stmt->execute([$room_id]); // ดำเนินการคำสั่ง SQL โดยส่งค่า 'room_id'
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลห้องที่ตรงกับ 'room_id'
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสอักขระเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ตั้งค่าการแสดงผลสำหรับหน้าจอขนาดต่างๆ -->
    <title>เพิ่มห้องเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="edit_classroom.css"> <!-- เชื่อมต่อกับไฟล์ CSS สำหรับการจัดแต่งหน้าเว็บ -->
</head>

<body>

    <div class="form-container"> <!-- คอนเทนเนอร์หลักสำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องหลักที่ใช้ Flexbox สำหรับจัดตำแหน่ง -->
            <div class="box2"> <!-- กล่องย่อยสำหรับฟอร์ม -->
                <h1 class="text-teacher">เพิ่มห้องเรียน</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นแบ่ง -->
                <form action="edit_classroom_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับส่งข้อมูลไปยัง 'edit_classroom_db.php' -->
                    <div class="form-group"> <!-- กลุ่มฟอร์มแรก -->
                        <input type="hidden" value="<?= htmlspecialchars($data['room_id']); ?>" name="room_id"> <!-- ช่องข้อมูลที่ซ่อนเพื่อส่ง 'room_id' -->
                        <label for="room_no">หมายเลขห้อง</label> <!-- ป้ายชื่อสำหรับช่องหมายเลขห้อง -->
                        <input type="text" value="<?= htmlspecialchars($data['room_no']); ?>" name="room_no"> <!-- ช่องกรอกหมายเลขห้อง -->
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2"> <!-- ช่องข้อมูลที่ซ่อนเพื่อส่งข้อมูลภาพ -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเลือกอาคารเรียน -->
                        <label for="building">อาคารเรียน</label> <!-- ป้ายชื่อสำหรับช่องอาคารเรียน -->
                        <select name="building"> <!-- เมนูดรอปดาวน์สำหรับเลือกอาคารเรียน -->
                            <option><?= htmlspecialchars($data['building']); ?></option> <!-- ตัวเลือกที่เลือกในตอนนี้ -->
                            <?php foreach ($buildingData as $buildings) { ?>
                                <option value="<?= htmlspecialchars($buildings['building_name']); ?>">
                                    <?= htmlspecialchars($buildings['building_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเลือกชั้น -->
                        <label for="floot">ชั้น</label> <!-- ป้ายชื่อสำหรับช่องชั้น -->
                        <select name="floot"> <!-- เมนูดรอปดาวน์สำหรับเลือกชั้น -->
                            <option><?= isset($data['floot']) ? htmlspecialchars($data['floot']) : ''; ?></option> <!-- ตัวเลือกที่เลือกในตอนนี้ -->
                            <?php foreach ($dataroom as $floot) { ?>
                                <option value="<?= htmlspecialchars($floot['floot']); ?>">
                                    <?= htmlspecialchars($floot['floot']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับอัปโหลดรูปภาพ -->
                        <label for="photo">รูปห้องเรียน</label> <!-- ป้ายชื่อสำหรับช่องรูปภาพ -->
                        <input type="file" id="imgInput" name="photo"> <!-- ช่องอัปโหลดไฟล์รูปภาพ -->
                        <img id="previewImg" src="uploads_classroom/<?= htmlspecialchars($data['photo']); ?>" alt=""> <!-- แสดงภาพตัวอย่างของรูปภาพที่เลือก -->
                    </div>
                    <div class="btn-con"> <!-- คอนเทนเนอร์สำหรับปุ่ม -->
                        <div class="btn-submit"> <!-- กลุ่มปุ่มสำหรับบันทึกข้อมูล -->
                            <button type="submit" name="update">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับส่งฟอร์ม -->
                        </div>
                        <div class="btn-out"> <!-- กลุ่มปุ่มสำหรับออก -->
                            <button type="button" onclick="window.location.href='data-classroom.php'">ออก</button> <!-- ปุ่มสำหรับกลับไปยังหน้าหลัก -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script> <!-- เชื่อมต่อกับไฟล์ JavaScript สำหรับแสดงตัวอย่างรูปภาพ -->
</body>

</html>