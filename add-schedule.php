<?php
session_start();
require_once 'config/db.php';

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ดึงข้อมูลกลุ่มวิชาทั้งหมด
$stmt = $pdo->prepare("SELECT subject_name, subject_id FROM subject");
$stmt->execute();
$subject = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลคุณครูทั้งหมด
$stmt = $pdo->prepare("SELECT fullname, t_id FROM teacher");
$stmt->execute();
$teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT room_no, room_id FROM room");
$stmt->execute();
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html> <!-- ประกาศว่าเอกสารนี้เป็น HTML5 -->
<html lang="th"> <!-- กำหนดภาษาเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนด viewport สำหรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>เพิ่มตารางสอน</title> <!-- กำหนดชื่อเอกสารที่จะแสดงบนแท็บเบราว์เซอร์ -->
    <link rel="stylesheet" href="add-schedule.css"> <!-- เชื่อมโยงไปยังไฟล์ CSS สำหรับการจัดรูปแบบ -->
</head>

<body>

    <div class="form-container"> <!-- กล่องหลักที่บรรจุฟอร์ม -->
        <div class="box1"> <!-- กล่องใหญ่ที่จัดตำแหน่งเนื้อหากลางหน้าจอ -->
            <div class="box2"> <!-- กล่องย่อยที่บรรจุฟอร์มและเนื้อหา -->
                <h1 class="text-teacher">ข้อมูลตารางสอน</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นแบ่ง -->
                <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบถ้ามีข้อความผิดพลาดในเซสชัน -->
                    <div class="alert-danger"> <!-- แสดงข้อความผิดพลาด -->
                        <?php
                        echo $_SESSION['error']; // แสดงข้อความผิดพลาด
                        unset($_SESSION['error']); // ลบข้อความผิดพลาดจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบถ้ามีข้อความสำเร็จในเซสชัน -->
                    <div class="alert-success"> <!-- แสดงข้อความสำเร็จ -->
                        <?php
                        echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                        unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบถ้ามีข้อความเตือนในเซสชัน -->
                    <div class="alert-warning"> <!-- แสดงข้อความเตือน -->
                        <?php
                        echo $_SESSION['warning']; // แสดงข้อความเตือน
                        unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>
                <form action="add-schedule_db.php" method="POST">
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับภาคเรียน -->
                        <label for="semester">ภาคเรียนที่1</label> <!-- ป้ายชื่อสำหรับเมนูเลือกภาคเรียน -->
                        <select id="level" name="semester"> <!-- เมนูเลือกภาคเรียน -->
                            <option value="">เลือกภาคเรียน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="1">1</option> <!-- ตัวเลือกภาคเรียนที่ 1 -->
                            <option value="2">2</option> <!-- ตัวเลือกภาคเรียนที่ 2 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับปีการศึกษา -->
                        <label for="academic_year">ปีการศึกษา</label> <!-- ป้ายชื่อสำหรับเมนูเลือกปีการศึกษา -->
                        <select id="level" name="academic_year"> <!-- เมนูเลือกปีการศึกษา -->
                            <option value="">เลือกปีการศึกษา</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="2567">2567</option> <!-- ตัวเลือกปีการศึกษา 2567 -->
                            <option value="2566">2566</option> <!-- ตัวเลือกปีการศึกษา 2566 -->
                            <option value="2565">2565</option> <!-- ตัวเลือกปีการศึกษา 2565 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับวิชา -->
                        <label for="subject_id">วิชา</label> <!-- ป้ายชื่อสำหรับเมนูเลือกวิชา -->
                        <select id="level" name="subject_id"> <!-- เมนูเลือกวิชา -->
                            <option value="">เลือกวิชา</option> <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($subject as $subjects) { ?>
                                <option value="<?php echo htmlspecialchars($subjects['subject_id']); ?>">
                                    <?php echo htmlspecialchars($subjects['subject_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                        <label for="name">ระดับชั้น</label> <!-- ป้ายชื่อสำหรับเมนูเลือกระดับชั้น -->
                        <select id="level" name="level"> <!-- เมนูเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="ม.1">ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="ม.2">ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="ม.3">ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับครูผู้สอน -->
                        <label for="t_id">ครูผู้สอน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกครูผู้สอน -->
                        <select id="level" name="t_id"> <!-- เมนูเลือกครูผู้สอน -->
                            <option value="">เลือกระดับชั้น</option>
                            <?php foreach ($teacherData as $teachers) { ?>
                                <option value="<?php echo htmlspecialchars($teachers['t_id']); ?>">
                                    <?php echo htmlspecialchars($teachers['fullname']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับห้องเรียน -->
                        <label for="room_id">ห้องเรียน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกห้องเรียน -->
                        <select id="level" name="room_id"> <!-- เมนูเลือกห้องเรียน -->
                            <option value="">เลือกห้องเรียน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($roomData as $rooms) { ?>
                                <option value="<?php echo htmlspecialchars($rooms['room_id']); ?>">
                                    <?php echo htmlspecialchars($rooms['room_no']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับวัน -->
                        <label for="teacher_date">วัน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกวัน -->
                        <select id="level" name="teacher_date"> <!-- เมนูเลือกวัน -->
                            <option value="">เลือกวัน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="วันจันทร์">วันจันทร์</option> <!-- ตัวเลือกวันจันทร์ -->
                            <option value="วันอังคาร">วันอังคาร</option> <!-- ตัวเลือกวันอังคาร -->
                            <option value="วันพุธ">วันพุธ</option> <!-- ตัวเลือกวันพุธ -->
                            <option value="วันพฤหัสบดี">วันพฤหัสบดี</option> <!-- ตัวเลือกวันพฤหัสบดี -->
                            <option value="วันศุกร์">วันศุกร์</option> <!-- ตัวเลือกวันศุกร์ -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเวลา -->
                        <label for="teacher_time">เวลา</label> <!-- ป้ายชื่อสำหรับช่องกรอกเวลา -->
                        <input type="text" id="search-name" name="teacher_time"> <!-- ช่องกรอกข้อมูลเวลา -->
                    </div>
                    <div class="btn-con"> <!-- กลุ่มปุ่มสำหรับบันทึกข้อมูลและออก -->
                        <div class="btn-submit"> <!-- กล่องปุ่มบันทึกข้อมูล -->
                            <button type="submit" name="add_schedule">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องปุ่มออก -->
                            <button type="button" onclick="window.location.href='Tutorial-Schedule.php'">ออก</button> <!-- ปุ่มสำหรับออกจากฟอร์มและไปยังหน้า Tutorial-Schedule.php -->
                        </div>
                    </div>
                    </fด>
            </div>
        </div>
    </div>

</body>

</html>