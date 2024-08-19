<?php
session_start(); // เริ่มต้นเซสชัน

require_once 'config/db.php'; // นำเข้าไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    // ถ้าไม่ใช่ผู้ดูแลระบบ ให้แสดงข้อความแจ้งเตือนและเปลี่ยนเส้นทางไปที่หน้า index.php
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit(); // หยุดการทำงานของสคริปต์หลังจากเปลี่ยนเส้นทาง
}

// ตรวจสอบว่ามีค่า schedule_id ถูกส่งมาหรือไม่
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id']; // เก็บค่า schedule_id จาก URL

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลตารางสอนที่เกี่ยวข้องตาม schedule_id
    $stmt = $pdo->prepare("SELECT s.schedule_id, sj.subject_name, sj.subject_code, sj.subject_id, t.t_id, t.fullname, s.teacher_time, r.room_id, r.room_no, s.semester, s.academic_year, s.level, s.teacher_date
        FROM schedule AS s
        JOIN teacher AS t ON s.t_id = t.t_id
        JOIN subject AS sj ON s.subject_id = sj.subject_id
        JOIN room AS r ON s.room_id = r.room_id
        WHERE s.schedule_id = ?");
    $stmt->execute([$schedule_id]); // รันคำสั่ง SQL ด้วยค่า schedule_id
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // เก็บผลลัพธ์ที่ดึงมาในตัวแปร $data
}

// ดึงข้อมูลกลุ่มวิชาทั้งหมด
$stmt = $pdo->prepare("SELECT subject_name, subject_id FROM subject"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลวิชา
$stmt->execute(); // รันคำสั่ง SQL
$subject = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บผลลัพธ์ทั้งหมดในตัวแปร $subject

// ดึงข้อมูลคุณครูทั้งหมด
$stmt = $pdo->prepare("SELECT fullname, t_id FROM teacher"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลคุณครู
$stmt->execute(); // รันคำสั่ง SQL
$teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บผลลัพธ์ทั้งหมดในตัวแปร $teacherData

// ดึงข้อมูลห้องเรียนทั้งหมด
$stmt = $pdo->prepare("SELECT room_no, room_id FROM room"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลห้องเรียน
$stmt->execute(); // รันคำสั่ง SQL
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บผลลัพธ์ทั้งหมดในตัวแปร $roomData
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

                <!-- แสดงข้อความผิดพลาด, ข้อความสำเร็จ, หรือข้อความเตือนถ้ามี -->
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert-danger">
                        <?php
                        echo $_SESSION['error']; // แสดงข้อความผิดพลาด
                        unset($_SESSION['error']); // ลบข้อความผิดพลาดจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>

                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert-success">
                        <?php
                        echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                        unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>

                <?php if (isset($_SESSION['warning'])) { ?>
                    <div class="alert-warning">
                        <?php
                        echo $_SESSION['warning']; // แสดงข้อความเตือน
                        unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชันหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>

                <!-- ฟอร์มสำหรับเพิ่มตารางสอน -->
                <form action="" method="POST">
                    <!-- กลุ่มฟอร์มสำหรับภาคเรียน -->
                    <div class="form-group">
                        <label for="semester">ภาคเรียนที่1</label>
                        <select id="level" name="semester">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1" <?= ($data['semester'] == 1) ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?= ($data['semester'] == 2) ? 'selected' : ''; ?>>2</option>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับปีการศึกษา -->
                    <div class="form-group">
                        <label for="academic_year">ปีการศึกษา</label>
                        <select id="level" name="academic_year">
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567" <?= ($data['academic_year'] == 2567) ? 'selected' : ''; ?>>2567</option>
                            <option value="2566" <?= ($data['academic_year'] == 2566) ? 'selected' : ''; ?>>2566</option>
                            <option value="2565" <?= ($data['academic_year'] == 2565) ? 'selected' : ''; ?>>2565</option>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับวิชา -->
                    <div class="form-group">
                        <label for="subject_id">วิชา</label>
                        <select id="level" name="subject_id">
                            <option value="">เลือกวิชา</option>
                            <?php foreach ($subject as $subjects) { ?>
                                <option value="<?= htmlspecialchars($subjects['subject_id']); ?>" <?= ($data['subject_id'] == $subjects['subject_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($subjects['subject_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                    <div class="form-group">
                        <label for="name">ระดับชั้น</label>
                        <select id="level" name="level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1" <?= ($data['level'] == 'ม.1') ? 'selected' : ''; ?>>ม.1</option>
                            <option value="ม.2" <?= ($data['level'] == 'ม.2') ? 'selected' : ''; ?>>ม.2</option>
                            <option value="ม.3" <?= ($data['level'] == 'ม.3') ? 'selected' : ''; ?>>ม.3</option>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับครูผู้สอน -->
                    <div class="form-group">
                        <label for="t_id">ครูผู้สอน</label>
                        <select id="level" name="t_id">
                            <option value="">เลือกครูผู้สอน</option>
                            <?php foreach ($teacherData as $teachers) { ?>
                                <option value="<?= htmlspecialchars($teachers['t_id']); ?>" <?= ($data['t_id'] == $teachers['t_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($teachers['fullname']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับห้องเรียน -->
                    <div class="form-group">
                        <label for="room_id">ห้องเรียน</label>
                        <select id="level" name="room_id">
                            <option value="">เลือกห้องเรียน</option>
                            <?php foreach ($roomData as $rooms) { ?>
                                <option value="<?= htmlspecialchars($rooms['room_id']); ?>" <?= ($data['room_id'] == $rooms['room_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($rooms['room_no']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับวันเรียน -->
                    <div class="form-group">
                        <label for="teacher_date">วัน</label>
                        <select id="level" name="teacher_date">
                            <option value="">เลือกวัน</option>
                            <option value="จันทร์" <?= ($data['teacher_date'] == 'จ.') ? 'selected' : ''; ?>>จันทร์</option>
                            <option value="อังคาร" <?= ($data['teacher_date'] == 'อ.') ? 'selected' : ''; ?>>อังคาร</option>
                            <option value="พุธ" <?= ($data['teacher_date'] == 'พ.') ? 'selected' : ''; ?>>พุธ</option>
                            <option value="พฤหัสบดี" <?= ($data['teacher_date'] == 'พฤ.') ? 'selected' : ''; ?>>พฤหัสบดี</option>
                            <option value="ศุกร์" <?= ($data['teacher_date'] == 'ศ.') ? 'selected' : ''; ?>>ศุกร์</option>
                        </select>
                    </div>

                    <!-- กลุ่มฟอร์มสำหรับเวลาเรียน -->
                    <div class="form-group">
                        <label for="teacher_time">เวลา</label>
                        <select type="time" name="teacher_time" value="<?= htmlspecialchars($data['teacher_time']); ?>"> <!-- input สำหรับเลือกเวลา -->
                            <option value="08:00 - 09:00 น.">08:00 - 09:00 น.</option>
                            <option value="09:00 - 10:00 น.">09:00 - 10:00 น.</option>
                            <option value="10:00 - 11:00 น.">10:00 - 11:00 น.</option>
                            <option value="11:00 - 12:00 น.">11:00 - 12:00 น.</option>
                            <option value="12:00 - 13:00 น.">12:00 - 13:00 น.</option>
                            <option value="13:00 - 14:00 น.">13:00 - 14:00 น.</option>
                            <option value="14:00 - 15:00 น.">14:00 - 15:00 น.</option>
                            <option value="15:00 - 16:00 น.">15:00 - 16:00 น.</option>
                        </select>
                    </div>

                    <!-- ปุ่มบันทึกข้อมูล -->
                    <div class="btn-con"> <!-- กลุ่มปุ่มสำหรับบันทึกข้อมูลและออก -->
                        <div class="btn-submit"> <!-- กล่องปุ่มบันทึกข้อมูล -->
                            <button type="submit" name="add_schedule">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องปุ่มออก -->
                            <button type="button" onclick="window.location.href='Tutorial-Schedule.php'">ออก</button> <!-- ปุ่มสำหรับออกจากฟอร์มและไปยังหน้า Tutorial-Schedule.php -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>