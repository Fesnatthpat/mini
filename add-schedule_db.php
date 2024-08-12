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

    if (empty($semester)) {
        $_SESSION['error'] = 'กรุณากรอกภาคเรียน';
        header("location: add-schedule.php");
        exit();
    } else if (empty($academic_year)) {
        $_SESSION['error'] = 'กรุณากรอกปีการศึกษา';
        header("location: add-schedule.php");
        exit();
    } else if (empty($subject_id)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา';
        header("location: add-schedule.php");
        exit();
    } else if (empty($level)) {
        $_SESSION['error'] = 'กรุณากรอกระดับ';
        header("location: add-schedule.php");
        exit();
    } else if (empty($t_id)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสอาจารย์';
        header("location: add-schedule.php");
        exit();
    } else if (empty($room_id)) {
        $_SESSION['error'] = 'กรุณากรอกหมายเลขห้อง';
        header("location: add-schedule.php");
        exit();
    } else if (empty($teacher_date)) {
        $_SESSION['error'] = 'กรุณากรอกวันสอน';
        header("location: add-schedule.php");
        exit();
    } else if (empty($teacher_time)) {
        $_SESSION['error'] = 'กรุณากรอกเวลาเรียน';
        header("location: add-schedule.php");
        exit();
    } else {
        try {
            $chk_subjectname = $pdo->prepare("SELECT subject_code FROM subject WHERE subject_code = :subject_code");
            $chk_subjectname->bindParam(":subject_code", $subject_code);
            $chk_subjectname->execute();
            $subjectData = $chk_subjectname->fetch(PDO::FETCH_ASSOC);

            if ($subjectData) {
                $_SESSION['warning'] = 'มีข้อมูลในระบบแล้ว';
                header("location: add-schedule.php");
                exit;
            } else {
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
                header("location: Tutorial-Schedule.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>
