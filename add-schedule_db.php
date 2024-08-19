<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['add_schedule'])) {
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    $subject_id = $_POST['subject_id'];
    $level = $_POST['level'];
    $t_id = $_POST['t_id'];
    $room_id = $_POST['room_id'];
    $teacher_date = $_POST['teacher_date'];
    $teacher_time = $_POST['teacher_time'];

    // ตรวจสอบการกรอกข้อมูลว่ามีการกรอกแล้วหรือยัง
    if (empty($semester)) {
        $_SESSION['error'] = 'กรุณากรอกภาคเรียน';
    } elseif (empty($academic_year)) {
        $_SESSION['error'] = 'กรุณากรอกปีการศึกษา';
    } elseif (empty($subject_id)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา';
    } elseif (empty($level)) {
        $_SESSION['error'] = 'กรุณากรอกระดับ';
    } elseif (empty($t_id)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสอาจารย์';
    } elseif (empty($room_id)) {
        $_SESSION['error'] = 'กรุณากรอกหมายเลขห้อง';
    } elseif (empty($teacher_date)) {
        $_SESSION['error'] = 'กรุณากรอกวันสอน';
    } elseif (empty($teacher_time)) {
        $_SESSION['error'] = 'กรุณากรอกเวลาเรียน';
    } else {
        try {
            // ตรวจสอบความซ้ำซ้อนของข้อมูล
            $chk_schedule = $pdo->prepare("SELECT * FROM schedule WHERE semester = :semester AND academic_year = :academic_year AND subject_id = :subject_id AND level = :level AND t_id = :t_id AND room_id = :room_id AND teacher_date = :teacher_date AND teacher_time = :teacher_time");
            $chk_schedule->bindParam(":semester", $semester);
            $chk_schedule->bindParam(":academic_year", $academic_year);
            $chk_schedule->bindParam(":subject_id", $subject_id);
            $chk_schedule->bindParam(":level", $level);
            $chk_schedule->bindParam(":t_id", $t_id);
            $chk_schedule->bindParam(":room_id", $room_id);
            $chk_schedule->bindParam(":teacher_date", $teacher_date);
            $chk_schedule->bindParam(":teacher_time", $teacher_time);
            $chk_schedule->execute();
            $existingSchedule = $chk_schedule->fetch(PDO::FETCH_ASSOC);

            if ($existingSchedule) {
                $_SESSION['warning'] = 'ข้อมูลตารางสอนนี้มีอยู่แล้ว';
            } else {
                // บันทึกข้อมูลลงฐานข้อมูล
                $stmt = $pdo->prepare("INSERT INTO schedule (semester, academic_year, subject_id, level, t_id, room_id, teacher_date, teacher_time) VALUES (:semester, :academic_year, :subject_id, :level, :t_id, :room_id, :teacher_date, :teacher_time)");
                $stmt->bindParam(":semester", $semester);
                $stmt->bindParam(":academic_year", $academic_year);
                $stmt->bindParam(":subject_id", $subject_id);
                $stmt->bindParam(":level", $level);
                $stmt->bindParam(":t_id", $t_id);
                $stmt->bindParam(":room_id", $room_id);
                $stmt->bindParam(":teacher_date", $teacher_date);
                $stmt->bindParam(":teacher_time", $teacher_time);
                $stmt->execute();
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
        }
    }

    header("location: add-schedule.php");
    exit();
}
?>
