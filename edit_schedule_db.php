<?php
// เริ่มต้นเซสชันเพื่อเข้าถึงตัวแปรเซสชัน
session_start();

// รวมไฟล์การตั้งค่าฐานข้อมูล
require_once 'config/db.php';

if (isset($_POST['update'])) {
    // ดึงข้อมูลจากการร้องขอ POST
    $schedule_id = $_POST['schedule_id'];
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    $subject_id = $_POST['subject_id'];
    $level = $_POST['level'];
    $t_id = $_POST['t_id'];
    $room_id = $_POST['room_id'];
    $teacher_date = $_POST['teacher_date'];
    $teacher_time = $_POST['teacher_time'];

    try {
        // เตรียมคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $sql = $pdo->prepare("UPDATE schedule SET semester = :semester, academic_year = :academic_year, subject_id = :subject_id, level = :level , t_id = :t_id, room_id = :room_id, teacher_date = :teacher_date, teacher_time = :teacher_time WHERE schedule_id = :schedule_id");

        // ผูกพารามิเตอร์กับคำสั่ง SQL
        $sql->bindParam(":semester", $semester);
        $sql->bindParam(":academic_year", $academic_year);
        $sql->bindParam(":subject_id", $subject_id);
        $sql->bindParam(":level", $level);
        $sql->bindParam(":t_id", $t_id);
        $sql->bindParam(":room_id", $room_id);
        $sql->bindParam(":teacher_date", $teacher_date);
        $sql->bindParam(":teacher_time", $teacher_time);
        $sql->bindParam(":schedule_id", $schedule_id); // เพิ่มการผูกพารามิเตอร์นี้

        // เรียกใช้คำสั่ง SQL ที่เตรียมไว้
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
            header("Location: Tutorial-Schedule.php");
            exit(); // ควรหยุดการทำงานหลังจาก redirect
        } else {
            $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการแก้ไข';
            header("Location: edit_schedule.php");
            exit(); // ควรหยุดการทำงานหลังจาก redirect
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
        header("Location: edit_schedule.php");
        exit(); // ควรหยุดการทำงานหลังจาก redirect
    }
}
