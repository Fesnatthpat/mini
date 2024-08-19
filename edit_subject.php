<?php
session_start();
// เริ่มต้นการทำงานของ session เพื่อใช้เก็บข้อมูลการเข้าสู่ระบบของผู้ใช้งาน

require_once 'config/db.php';
// นำเข้าไฟล์ที่มีการตั้งค่าการเชื่อมต่อกับฐานข้อมูล

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    // เตรียมคำสั่ง SQL สำหรับดึงชื่อกลุ่มวิชาทั้งหมดจากตาราง subject_group
    $stmt->execute();
    // รันคำสั่ง SQL ที่เตรียมไว้
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // ดึงข้อมูลทั้งหมดที่ได้จากการรัน SQL และเก็บไว้ในตัวแปร $subjectGroups
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    // ถ้ามีข้อผิดพลาดในการเชื่อมต่อฐานข้อมูลหรือรันคำสั่ง SQL จะจับข้อผิดพลาดและแสดงข้อความ
}

$data = null;
// กำหนดค่าเริ่มต้นให้ตัวแปร $data เป็น null เพื่อใช้ในการเช็คว่ามีข้อมูลหรือไม่

if (isset($_GET['subject_id'])) {
    // ตรวจสอบว่ามีการส่งค่า subject_id ผ่าน URL มาหรือไม่
    $subject_id = $_GET['subject_id'];
    // เก็บค่า subject_id ที่ได้จาก URL ไว้ในตัวแปร $subject_id
    $stmt = $pdo->prepare("SELECT * FROM subject WHERE subject_id = ?");
    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูลรายวิชาโดยใช้ subject_id เป็นตัวกรอง
    $stmt->execute([$subject_id]);
    // รันคำสั่ง SQL พร้อมกับส่งค่า subject_id ที่ได้รับเข้ามาในคำสั่ง
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // ดึงข้อมูลที่ได้จากการรัน SQL และเก็บไว้ในตัวแปร $data
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <!-- กำหนดรูปแบบการเข้ารหัสของหน้าเว็บเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ทำให้เว็บรองรับการแสดงผลบนอุปกรณ์เคลื่อนที่ -->
    <title>แก้ไขรายวิชา</title>
    <!-- กำหนดชื่อของหน้าเว็บ -->
    <link rel="stylesheet" href="edit_subject.css">
    <!-- เชื่อมโยงกับไฟล์ CSS ที่ใช้จัดรูปแบบหน้าเว็บ -->
</head>

<body>

    <div class="form-container">
        <!-- เริ่มต้นคอนเทนเนอร์สำหรับฟอร์ม -->
        <div class="box1">
            <!-- กล่องที่ใช้จัดเลย์เอาต์ภายในคอนเทนเนอร์ -->
            <div class="box2">
                <!-- กล่องภายในอีกชั้นหนึ่งที่มีการจัดเลย์เอาต์เพิ่มเติม -->
                <h1 class="text-teacher">แก้ไขรายวิชา</h1>
                <!-- หัวข้อของฟอร์มที่แสดงชื่อ "แก้ไขรายวิชา" -->
                <hr>
                <!-- เส้นแบ่งเพื่อความสวยงาม -->
                <form action="edit_subject_db.php" method="POST" enctype="multipart/form-data">
                    <!-- เริ่มต้นฟอร์มที่มีการกำหนดว่าเมื่อส่งข้อมูลแล้วจะไปที่ไฟล์ edit_subject_db.php และสามารถอัปโหลดไฟล์ได้ -->
                    <div class="form-group">
                        <!-- กลุ่มของฟอร์มสำหรับจัดการ input field ต่างๆ -->
                        <input type="hidden" value="<?= htmlspecialchars($data['subject_id']); ?>" name="subject_id">
                        <!-- ช่องสำหรับเก็บค่า subject_id ในรูปแบบซ่อนอยู่ เพื่อใช้ในการอัปเดตข้อมูล -->
                        <label for="subject_code">รหัสวิชา</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์รหัสวิชา -->
                        <input type="text" value="<?= htmlspecialchars($data['subject_code']); ?>" name="subject_code">
                        <!-- ช่องกรอกข้อมูลสำหรับรหัสวิชา โดยมีค่าเริ่มต้นที่มาจากฐานข้อมูล -->
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                        <!-- ช่องสำหรับเก็บชื่อไฟล์ภาพเดิมในรูปแบบซ่อนอยู่ -->
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อวิชา</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ชื่อวิชา -->
                        <input type="text" value="<?= htmlspecialchars($data['subject_name']); ?>" name="subject_name">
                        <!-- ช่องกรอกข้อมูลสำหรับชื่อวิชา โดยมีค่าเริ่มต้นที่มาจากฐานข้อมูล -->
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ระดับชั้น -->
                        <select id="level" name="level">
                        <option><?= htmlspecialchars($data['level']); ?></option>
                            <!-- เมนูแบบเลือกสำหรับเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option>
                            <!-- ตัวเลือกเริ่มต้นให้เลือกว่าจะเลือกระดับชั้น -->
                            <option value="1">ม.1</option>
                            <!-- ตัวเลือกสำหรับระดับชั้น ม.1 -->
                            <option value="2">ม.2</option>
                            <!-- ตัวเลือกสำหรับระดับชั้น ม.2 -->
                            <option value="3">ม.3</option>
                            <!-- ตัวเลือกสำหรับระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชา</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์กลุ่มวิชา -->
                        <select name="subject_group">
                            <!-- เมนูแบบเลือกสำหรับเลือกกลุ่มวิชา -->
                            <option><?= htmlspecialchars($data['subject_group']); ?></option>
                            <!-- ตัวเลือกที่แสดงกลุ่มวิชาปัจจุบันที่ถูกเลือก -->
                            <?php foreach ($subjectGroups as $group) { ?>
                                <!-- วนลูปผ่านทุกกลุ่มวิชาและสร้างตัวเลือกในเมนู -->
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>">
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                                <!-- สร้างตัวเลือกสำหรับกลุ่มวิชานั้น ๆ -->
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">ปกหนังสือ</label>
                        <!-- ป้ายชื่อสำหรับฟิลด์ปกหนังสือ -->
                        <input type="file" id="imgInput" name="photo">
                        <!-- ช่องสำหรับอัปโหลดไฟล์ภาพปกหนังสือ -->
                        <img id="previewImg" src="uploads_subject2/<?= htmlspecialchars($data['photo']); ?>" alt="">
                        <!-- แสดงภาพปกหนังสือปัจจุบันในฟอร์ม -->
                    </div>
                    <div class="btn-con">
                        <!-- คอนเทนเนอร์สำหรับจัดปุ่มต่าง ๆ ในฟอร์ม -->
                        <div class="btn-submit">
                            <!-- คอนเทนเนอร์สำหรับปุ่มบันทึกข้อมูล -->
                            <button type="submit" name="update">บันทึกข้อมูล</button>
                            <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out">
                            <!-- คอนเทนเนอร์สำหรับปุ่มออกจากหน้า -->
                            <button type="button" onclick="window.location.href='data-subject.php'">ออก</button>
                            <!-- ปุ่มสำหรับออกจากหน้าและไปที่หน้า data-subject.php -->
                        </div>
                    </div>
                </form>
                <!-- ปิดแท็กฟอร์ม -->
            </div>
            <!-- ปิดแท็ก box2 -->
        </div>
        <!-- ปิดแท็ก box1 -->
    </div>
    <!-- ปิดแท็ก form-container -->
    <script src="preview_img.js"></script>
    <!-- นำเข้าไฟล์ JavaScript สำหรับแสดงภาพตัวอย่างเมื่ออัปโหลดรูป -->
</body>

</html>