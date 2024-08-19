<?php
session_start(); // เริ่มต้นการทำงานของ session เพื่อให้สามารถจัดการข้อมูล session ได้
require_once 'config/db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// เตรียมคำสั่ง SQL เพื่อดึงข้อมูลชื่อกลุ่มวิชาจากตาราง subject_group
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute(); // รันคำสั่ง SQL ที่เตรียมไว้
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดมาในรูปแบบของ associative array และเก็บไว้ในตัวแปร $subjectGroups

// ตรวจสอบว่ามีการส่งค่า t_id มาทาง URL หรือไม่
if (isset($_GET['t_id'])) {
    $t_id = $_GET['t_id']; // เก็บค่า t_id ที่ส่งมาทาง URL ไว้ในตัวแปร $t_id
    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลคุณครูจากตาราง teacher ตาม t_id ที่ได้รับ
    $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?");
    $stmt->execute([$t_id]); // รันคำสั่ง SQL พร้อมกับส่งค่า $t_id เพื่อใช้ในเงื่อนไข
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลที่ตรงกันเพียงหนึ่งรายการมาเก็บไว้ในตัวแปร $data
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดชุดอักขระเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนดการแสดงผลให้รองรับบนอุปกรณ์มือถือ -->
    <title>แก้ไขคุณครู</title> <!-- ชื่อหน้าต่างเบราว์เซอร์ -->
    <link rel="stylesheet" href="edit_teacher.css"> <!-- เชื่อมต่อไฟล์ CSS สำหรับจัดรูปแบบ -->
</head>

<body>

    <div class="form-container"> <!-- Container สำหรับฟอร์ม -->
        <div class="box1"> <!-- กล่องหลักสำหรับจัดรูปแบบเนื้อหา -->
            <div class="box2"> <!-- กล่องย่อยภายในที่ใช้จัดวางเนื้อหาเพิ่มเติม -->
                <h1 class="text-teacher">แก้ไขคุณครู</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นแบ่งด้านล่างหัวข้อ -->
                <form action="edit_teacher_db.php" method="post" enctype="multipart/form-data"> <!-- เริ่มต้นฟอร์มสำหรับส่งข้อมูลไปยังไฟล์ PHP เพื่อบันทึกข้อมูล -->
                    <div class="form-group">
                        <!-- input ซ่อนสำหรับเก็บ t_id -->
                        <input type="hidden" value="<?= htmlspecialchars($data['t_id']); ?>" name="t_id">
                        <label for="t_code">รหัสประจำตัว</label> <!-- ป้ายชื่อสำหรับรหัสประจำตัว -->
                        <input type="text" value="<?= htmlspecialchars($data['t_code']); ?>" name="t_code"> <!-- input สำหรับกรอกรหัสประจำตัว -->
                        <!-- input ซ่อนสำหรับเก็บชื่อไฟล์รูปภาพเดิม -->
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                    </div>
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label> <!-- ป้ายชื่อสำหรับชื่อ-นามสกุล -->
                        <input type="text" value="<?= htmlspecialchars($data['fullname']); ?>" name="fullname"> <!-- input สำหรับกรอกชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label> <!-- ป้ายชื่อสำหรับเบอร์โทร -->
                        <input type="text" value="<?= htmlspecialchars($data['phone']); ?>" name="phone"> <!-- input สำหรับกรอกเบอร์โทร -->
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชาที่สอน</label> <!-- ป้ายชื่อสำหรับกลุ่มวิชาที่สอน -->
                        <select id="subject_group" name="subject_group"> <!-- select สำหรับเลือกกลุ่มวิชาที่สอน -->
                            <option><?= htmlspecialchars($data['subject_group']); ?></option> <!-- แสดงกลุ่มวิชาปัจจุบันที่เลือกไว้ -->
                            <!-- ลูปผ่านข้อมูลกลุ่มวิชาที่ดึงมาจากฐานข้อมูลและแสดงใน select -->
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>">
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label> <!-- ป้ายชื่อสำหรับรูปถ่าย -->
                        <input type="file" id="imgInput" name="photo"> <!-- input สำหรับอัปโหลดรูปถ่ายใหม่ -->
                        <img id="previewImg" src="uploads/<?= htmlspecialchars($data['photo']); ?>" alt=""> <!-- แสดงพรีวิวรูปถ่ายที่อัปโหลด -->
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label> <!-- ป้ายชื่อสำหรับ Username -->
                        <input type="text" value="<?= htmlspecialchars($data['username']); ?>" name="username"> <!-- input สำหรับกรอก Username -->
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label> <!-- ป้ายชื่อสำหรับ Password -->
                        <input type="password" value="<?= htmlspecialchars($data['password']); ?>" name="password"> <!-- input สำหรับกรอก Password -->
                    </div>
                    <div class="btn-con"> <!-- Container สำหรับปุ่ม -->
                        <div class="btn-submit"> <!-- ปุ่มบันทึกข้อมูล -->
                            <button type="submit" name="update">บันทึกข้อมูล</button> <!-- เมื่อคลิกปุ่มนี้ จะส่งข้อมูลฟอร์มไปยังไฟล์ edit_teacher_db.php -->
                        </div>
                        <div class="btn-out"> <!-- ปุ่มออก -->
                            <button type="button" onclick="window.location.href='teacher.php'">ออก</button> <!-- เมื่อคลิกปุ่มนี้ จะพาผู้ใช้กลับไปยังหน้า teacher.php -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script> <!-- ลิงก์ไปยังไฟล์ JavaScript สำหรับพรีวิวรูปภาพ -->
</body>

</html>
