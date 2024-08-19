<?php
session_start(); //การเริ่มต้น session
require_once 'config/db.php'; //การเรียกไฟล์ db.php ช่วยให้เชื่อมต่อฐานข้อมูลได้ง่ายและป้องกันการเรียกไฟล์ซ้ำ

if (!isset($_SESSION['admin_login'])) { //ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบในฐานะผู้ดูแลระบบหรือไม่
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php"); //ใช้ฟังก์ชัน header() เพื่อเปลี่ยนเส้นทางผู้ใช้ไปยังหน้า index.php
    exit(); //ใช้เพื่อป้องกันไม่ให้โค้ดที่อยู่หลัง exit() ทำงานต่อ
}

try { //try-catch เพื่อจัดการข้อผิดพลาดที่อาจเกิดขึ้นขณะทำงานกับฐานข้อมูล
    // ดึงข้อมูลระดับชั้น
    $stmt = $pdo->prepare("SELECT level FROM student GROUP BY level"); //เพื่อดึงข้อมูลระดับชั้น level จากตาราง student โดยการจัดกลุ่มระดับชั้นเพื่อให้ได้เฉพาะระดับชั้นที่ไม่ซ้ำกัน
    $stmt->execute(); //การรันคำสั่ง SQL 
    $datlevel = $stmt->fetchAll(PDO::FETCH_ASSOC); //ดึงข้อมมูลทั้งหมดที่ได้จากการรัน มาจัดเก็บในในตัวแปร

    // ดึงข้อมูลกลุ่มวิชา
    $stmt = $pdo->prepare("SELECT subject_id, subject_name  FROM subject"); //เพื่อดึงข้อมูล subject_id, subject_name จากตาราง subject
    $stmt->execute(); //การรันคำสั่ง SQL 
    $subjectData = $stmt->fetchAll(PDO::FETCH_ASSOC); //ดึงข้อมมูลทั้งหมดที่ได้จากการรัน มาจัดเก็บในในตัวแปร

    // ดึงข้อมูลชื่อครู
    $stmt = $pdo->prepare("SELECT t_id, fullname FROM teacher"); //เพื่อดึงข้อมูล t_id, fullname จากตาราง teacher
    $stmt->execute(); //การรันคำสั่ง SQL 
    $datateacher = $stmt->fetchAll(PDO::FETCH_ASSOC); //ดึงข้อมมูลทั้งหมดที่ได้จากการรัน มาจัดเก็บในในตัวแปร

    // ดึงข้อมูลหมายเลขห้อง
    $stmt = $pdo->prepare("SELECT room_id, room_no FROM room"); //เพื่อดึงข้อมูล room_id, room_no จากตาราง room
    $stmt->execute(); //การรันคำสั่ง SQL
    $dataroom = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { //ถ้าเกิดข้อผิดพลาด catch จะถูกเรียกใช้ ซึ่งในที่นี้คือการแสดงข้อความข้อผิดพลาด
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มตารางสอน</title>
    <link rel="stylesheet" href="add-schedule.css"> <!--ลิงก์ไปยัง CSS เพื่อจัดรูปแบบ-->
</head>

<body>
    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">ข้อมูลตารางสอน</h1>
                <hr>
                <form action="add-schedule_db.php" method="POST"> <!--ถ้ามีการกดส่งข้อมูล ข้อมูลจะถูกส่งไปยังไฟล์ add-schedule_db.php โดยใช้วิธี POST-->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert-danger"><?php echo $_SESSION['error']; //ใช้เพื่อแสดงข้อความข้อผิดพลาดที่ถูกเก็บไว้ใน session
                                                    unset($_SESSION['error']); ?></div> <!--ลบค่าของ session เพื่อไม่ให้แสดงซ้ำเมื่อรีเฟรช-->
                    <?php } ?> <!--ปิดบล็อก if ของ PHP -->
                    <?php if (isset($_SESSION['success'])) { ?> <!--ตรวจสอบว่ามีตัวแปร session ที่ชื่อว่า success ถูกตั้งค่าไว้หรือไม่ -->
                        <div class="alert-success"><?php echo $_SESSION['success'];
                                                    unset($_SESSION['success']); ?></div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <div class="alert-warning"><?php echo $_SESSION['warning']; //ใช้เพื่อแสดงข้อความสำเร็จที่ถูกเก็บไว้ใน session
                                                    unset($_SESSION['warning']); ?></div> <!--ลบค่าของ session เพื่อไม่ให้แสดงซ้ำเมื่อรีเฟรช-->
                    <?php } ?> <!--ปิดบล็อก if ของ PHP -->

                    <div class="form-group"> <!--สร้างคลาส form-group เพื่อไว้ถูก CSS เรียกใช้-->
                        <label for="semester">ภาคเรียนที่</label> <!-- for="semester" เชื่อมโยงป้ายชื่อกับช่องเลือก -->
                        <select name="semester" required> <!--สร้างกล่องดรอปดาวน์ (dropdown) สำหรับให้ผู้ใช้เลือกภาคเรียน-->
                            <option value="">เลือกภาคเรียน</option> 
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="academic_year">ปีการศึกษา</label> 
                        <select name="academic_year" required> <!--สร้างกล่องดรอปดาวน์ (dropdown) สำหรับให้ผู้ใช้เลือกปีการศึกษา-->
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject_group">วิชา</label>
                        <select name="subject_id">
                            <option value="">เลือกกลุ่มวิชา</option>
                            <?php foreach ($subjectData as $subjects) { ?> <!--การเริ่มต้นลูป foreach ซึ่งจะวนผ่านแต่ละรายการใน $subjectData ซึ่งเป็นตัวแปรที่เก็บข้อมูลกลุ่มวิชาที่ดึงมาจากฐานข้อมูล.-->
                                <option value="<?php echo htmlspecialchars($subjects['subject_id']); ?>"> <!--ค่า (value) ของตัวเลือกจะถูกตั้งค่าเป็น subject_id ของวิชานั้น-->
                                    <?php echo htmlspecialchars($subjects['subject_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <select name="level" required>
                            <option value="">เลือกระดับชั้น</option>
                            <?php foreach ($datlevel as $levels) { ?> <!--การเริ่มต้นลูป foreach ซึ่งจะวนผ่านแต่ละรายการใน $datlevel ซึ่งเป็นตัวแปรที่เก็บข้อมูลกลุ่มวิชาที่ดึงมาจากฐานข้อมูล.-->
                                <option value="<?php echo htmlspecialchars($levels['level']); ?>">
                                    <?php echo htmlspecialchars($levels['level']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="t_id">ครูผู้สอน</label>
                        <select name="t_id" required>
                            <option value="">เลือกครูผู้สอน</option>
                            <?php foreach ($datateacher as $teachers) { ?>
                                <option value="<?php echo htmlspecialchars($teachers['t_id']); ?>">
                                    <?php echo htmlspecialchars($teachers['fullname']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="room_id">ห้องเรียน</label>
                        <select name="room_id" required>
                            <option value="">เลือกห้องเรียน</option>
                            <?php foreach ($dataroom as $rooms) { ?>
                                <option value="<?php echo htmlspecialchars($rooms['room_id']); ?>">
                                    <?php echo htmlspecialchars($rooms['room_no']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="teacher_date">วัน</label> <!--เชื่อมโยงป้ายชื่อกับช่องเลือกที่มี name หรือ id เท่ากับ "teacher_date". การคลิกที่ป้ายชื่อจะทำให้กล่องดรอปดาวน์ได้รับการโฟกัส-->
                        <select name="teacher_date" required> <!--required บังคับให้ผู้ใช้ต้องเลือกวันจากดรอปดาวน์ก่อนที่จะสามารถส่งฟอร์มได้-->
                            <option value="">เลือกวัน</option>
                            <option value="วันจันทร์">วันจันทร์</option>
                            <option value="วันอังคาร">วันอังคาร</option>
                            <option value="วันพุธ">วันพุธ</option>
                            <option value="วันพฤหัสบดี">วันพฤหัสบดี</option>
                            <option value="วันศุกร์">วันศุกร์</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="teacher_time">เวลา</label> <!--กำหนดป้ายชื่อ (label) สำหรับช่องกรอกข้อมูล (input)-->
                        <input type="text" name="teacher_time" required> <!--เชื่อมโยงป้ายชื่อกับช่องกรอกข้อมูลที่มี name หรือ id เท่ากับ "teacher_time"-->
                    </div>

                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="add_schedule">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                            <button type="button" onclick="history.back()">ออก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>