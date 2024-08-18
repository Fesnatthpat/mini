<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อเก็บข้อมูลที่จำเป็นในการทำงานระหว่างสคริปต์
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูลเพื่อใช้ในการเชื่อมต่อฐานข้อมูล

if (isset($_POST['add_schedule'])) { // ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์มที่มีปุ่มชื่อ 'add_schedule' หรือไม่
    $semester = $_POST['semester']; // เก็บค่าภาคเรียนที่ผู้ใช้ป้อนมาในตัวแปร $semester
    $academic_year = $_POST['academic_year']; // เก็บค่าปีการศึกษาที่ผู้ใช้ป้อนมาในตัวแปร $academic_year
    $subject_id = $_POST['subject_id']; // เก็บค่ารหัสวิชาที่ผู้ใช้ป้อนมาในตัวแปร $subject_id
    $level = $_POST['level']; // เก็บค่าระดับที่ผู้ใช้ป้อนมาในตัวแปร $level
    $t_id = $_POST['t_id']; // เก็บค่ารหัสอาจารย์ที่ผู้ใช้ป้อนมาในตัวแปร $t_id
    $room_id = $_POST['room_id']; // เก็บค่าหมายเลขห้องที่ผู้ใช้ป้อนมาในตัวแปร $room_id
    $teacher_date = $_POST['teacher_date']; // เก็บค่าวันสอนที่ผู้ใช้ป้อนมาในตัวแปร $teacher_date
    $teacher_time = $_POST['teacher_time']; // เก็บค่าเวลาเรียนที่ผู้ใช้ป้อนมาในตัวแปร $teacher_time

    // ตรวจสอบการกรอกข้อมูล
    if (empty($semester)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลภาคเรียนหรือไม่
        $_SESSION['error'] = 'กรุณากรอกภาคเรียน'; // หากไม่ได้กรอกภาคเรียน จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($academic_year)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลปีการศึกษาหรือไม่
        $_SESSION['error'] = 'กรุณากรอกปีการศึกษา'; // หากไม่ได้กรอกปีการศึกษา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($subject_id)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลรหัสวิชาหรือไม่
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา'; // หากไม่ได้กรอกรหัสวิชา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($level)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลระดับหรือไม่
        $_SESSION['error'] = 'กรุณากรอกระดับ'; // หากไม่ได้กรอกระดับ จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($t_id)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลรหัสอาจารย์หรือไม่
        $_SESSION['error'] = 'กรุณากรอกรหัสอาจารย์'; // หากไม่ได้กรอกรหัสอาจารย์ จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($room_id)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลหมายเลขห้องหรือไม่
        $_SESSION['error'] = 'กรุณากรอกหมายเลขห้อง'; // หากไม่ได้กรอกหมายเลขห้อง จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($teacher_date)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลวันสอนหรือไม่
        $_SESSION['error'] = 'กรุณากรอกวันสอน'; // หากไม่ได้กรอกวันสอน จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } elseif (empty($teacher_time)) { // ตรวจสอบว่าผู้ใช้ได้กรอกข้อมูลเวลาเรียนหรือไม่
        $_SESSION['error'] = 'กรุณากรอกเวลาเรียน'; // หากไม่ได้กรอกเวลาเรียน จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
    } else { // หากข้อมูลทั้งหมดถูกกรอกครบถ้วนแล้ว
        try {
            // ตรวจสอบความซ้ำซ้อนของข้อมูลตารางสอน
            $chk_schedule = $pdo->prepare("SELECT * FROM schedule WHERE semester = :semester AND academic_year = :academic_year AND subject_id = :subject_id AND level = :level AND t_id = :t_id AND room_id = :room_id AND teacher_date = :teacher_date AND teacher_time = :teacher_time");
            // เตรียมคำสั่ง SQL เพื่อเช็คว่ามีข้อมูลตารางสอนนี้อยู่ในฐานข้อมูลแล้วหรือไม่
            $chk_schedule->bindParam(":semester", $semester); // ผูกค่าภาคเรียนกับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":academic_year", $academic_year); // ผูกค่าปีการศึกษากับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":subject_id", $subject_id); // ผูกค่ารหัสวิชากับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":level", $level); // ผูกค่าระดับกับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":t_id", $t_id); // ผูกค่ารหัสอาจารย์กับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":room_id", $room_id); // ผูกค่าหมายเลขห้องกับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":teacher_date", $teacher_date); // ผูกค่าวันสอนกับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->bindParam(":teacher_time", $teacher_time); // ผูกค่าเวลาเรียนกับพารามิเตอร์ในคำสั่ง SQL
            $chk_schedule->execute(); // ดำเนินการคำสั่ง SQL
            $existingSchedule = $chk_schedule->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลจากผลลัพธ์ของคำสั่ง SQL

            if ($existingSchedule) { // ตรวจสอบว่าพบข้อมูลตารางสอนในฐานข้อมูลหรือไม่
                $_SESSION['warning'] = 'ข้อมูลตารางสอนนี้มีอยู่แล้ว'; // หากพบข้อมูลตารางสอน จะแสดงข้อความเตือนผ่านเซสชัน
            } else { // หากไม่พบข้อมูลตารางสอนในฐานข้อมูล
                // บันทึกข้อมูลลงฐานข้อมูล
                $stmt = $pdo->prepare("INSERT INTO schedule (semester, academic_year, subject_id, level, t_id, room_id, teacher_date, teacher_time) VALUES (:semester, :academic_year, :subject_id, :level, :t_id, :room_id, :teacher_date, :teacher_time)");
                // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลตารางสอนใหม่ลงในฐานข้อมูล
                $stmt->bindParam(":semester", $semester); // ผูกค่าภาคเรียนกับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":academic_year", $academic_year); // ผูกค่าปีการศึกษากับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":subject_id", $subject_id); // ผูกค่ารหัสวิชากับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":level", $level); // ผูกค่าระดับกับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":t_id", $t_id); // ผูกค่ารหัสอาจารย์กับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":room_id", $room_id); // ผูกค่าหมายเลขห้องกับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":teacher_date", $teacher_date); // ผูกค่าวันสอนกับพารามิเตอร์ในคำสั่ง SQL
                $stmt->bindParam(":teacher_time", $teacher_time); // ผูกค่าเวลาเรียนกับพารามิเตอร์ในคำสั่ง SQL
                $stmt->execute(); // ดำเนินการคำสั่ง SQL เพื่อเพิ่มข้อมูลในฐานข้อมูล
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว';
                header("location: Tutorial-Schedule.php"); // แสดงข้อความสำเร็จผ่านเซสชัน
            }
        } catch (PDOException $e) { // จับข้อผิดพลาดที่เกิดขึ้นจากการทำงานกับฐานข้อมูล
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage(); // แสดงข้อความข้อผิดพลาดพร้อมรายละเอียดของข้อผิดพลาดผ่านเซสชัน
        }
    }

    header("location: add-schedule.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-schedule.php
    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
}
