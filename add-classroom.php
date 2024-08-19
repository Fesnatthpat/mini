<?php
session_start();
require_once 'config/db.php'; //จะรวมไฟล์ 'db.php' ที่อยู่ในโฟลเดอร์ 'config' เข้ามาในสคริปต์ปัจจุบัน

//การดึงข้อมูลจากฐานข้อมูล
try {
    $stmt = $pdo->prepare("SELECT * FROM building"); //เตรียมคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง building
    $stmt->execute(); //ทำการรันคำสั่ง SQL
    $buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); //การจัดการข้อผิดพลาด หากเกิดข้อผิดพลาดในการดำเนินการคำสั่ง SQL จะถูกจับใน catch และแสดงข้อความข้อผิดพลาด
}
//ตรวจสอบการเข้าถึง ว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php"); //การเปลี่ยนเส้นทางส่งผู้ใช้ไปยังหน้า index.php
    exit(); //ใช้เพื่อป้องกันไม่ให้โค้ดที่อยู่หลัง exit() ทำงานต่อ
}




?>

<!DOCTYPE html>
<html lang="th"> <!-- กำหนดภาษาเป็นภาษาไทย -->

<head>
<meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ตั้งค่าการแสดงผลบนอุปกรณ์เคลื่อนที่ -->
    <title>เพิ่มห้องเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-subject.css"> <!-- ลิงก์ไปยังไฟล์ CSS สำหรับจัดรูปแบบหน้าเว็บ -->

</head>

<body>

<div class="form-container"> <!-- คอนเทนเนอร์หลักสำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องหลักที่จัดตำแหน่งฟอร์ม -->
            <div class="box2"> <!-- กล่องย่อยที่ใช้สำหรับจัดรูปแบบฟอร์ม -->
                <h1 class="text-teacher">เพิ่มห้องเรียน</h1> <!-- หัวเรื่องหลักของฟอร์ม -->
                <hr> 
                <form action="add_classroom_db.php" method="POST" enctype="multipart/form-data"> <!-- ฟอร์มสำหรับการเพิ่มข้อมูลห้องเรียน -->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger"> <!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->

                            <?php
                            echo $_SESSION['error']; // แสดงข้อความในเซสชันที่มีคีย์ 'error'
                            unset($_SESSION['error']); // ลบข้อมูลที่มีคีย์ 'error' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success']))  { ?> 
                        <div class="alert-success">
                            <?php
                            echo $_SESSION['success']; // แสดงข้อความในเซสชันที่มีคีย์ 'success'
                            unset($_SESSION['success']); // ลบข้อมูลที่มีคีย์ 'success' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <div class="alert-warning">
                            <?php
                            echo $_SESSION['warning']; // แสดงข้อความในเซสชันที่มีคีย์ 'warning'
                            unset($_SESSION['warning']); // ลบข้อมูลที่มีคีย์ 'warning' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="student-id">หมายเลขห้อง</label> <!--แสดงป้ายชื่อสำหรับฟิลด์หมายเลขห้อง-->
                        <input type="text" id="room_no" name="room_no">
                    </div>
                    <div class="form-group">
                        <label for="building">อาคารเรียน</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <select id="building" name="building"> <!--สร้างเมนูดรอปดาวน์สำหรับเลือกอาคารเรียน-->
                            <option value="">เลือกอาคารเรียน</option>
                            <?php foreach ($buildingData as $building) { ?>
                                <option value="<?php echo htmlspecialchars($building['building_name']); ?>"> <!--วนลูปผ่านข้อมูลอาคารเรียนและสร้าง <option> สำหรับแต่ละอาคาร-->
                                    <?php echo htmlspecialchars($building['building_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floot">ชั้น</label><!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <select id="floot" name="floot"> <!--สร้างเมนูดรอปดาวน์สำหรับเลือกชั้นเรียน-->
                            <option value="">เลือกชั้น</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปห้องเรียน</label><!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="file" id="imgInput" name="photo"> <!--สร้างฟิลด์อัปโหลดไฟล์รูปภาพ-->
                        <img id="previewImg"> <!--ช่องสำหรับแสดงตัวอย่างรูปภาพ-->
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="add_classroom">บันทึกข้อมูล</button> <!--ปุ่มสำหรับส่งฟอร์ม โดยชื่อปุ่มคือ 'add_classroom'-->
                        </div>
                        <div class="btn-out">
                        <button type="button" onclick="history.back()">ออก</button> <!--ปุ่มสำหรับกลับไปยังหน้าก่อนหน้านี้เมื่อคลิก-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script> <!--เชื่อมต่อและโหลดไฟล์ JavaScript ที่มีชื่อว่า preview_img.js เข้ากับเอกสาร HTML-->
</body>