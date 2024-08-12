<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM subject_group");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มตารางสอน</title>
    <link rel="stylesheet" href="add-schedule.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">ข้อมูลตารางสอน</h1>
                <hr>
                <form action="add-schedule_db.php" method="POST" >
                    <div class="form-group">
                        <label for="semester">ภาคเรียนที่</label>
                        <select name="semester">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="academic_year">ปีการศึกษา</label>
                        <select name="academic_year">
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_id">วิชา</label>
                        <select name="subject_id">
                            <option value="">เลือกวิชา</option>
                            <option value="">ภาษา</option>
                            <option value="">คณิตศาสตร์</option>
                            <option value="">วิทยาศาสตร์</option>
                            <option value="">สังคมศาสตร์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <select id="level" name="level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="1">ม.1</option>
                            <option value="2">ม.2</option>
                            <option value="3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="t_id">ครูผู้สอน</label>
                        <select name="t_id">
                            <option value="">เลือกครูผู้สอน</option>
                            <option value="">ครูเต้ย</option>
                            <option value="">ครูแบม</option>
                            <option value="">ครูวิว</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room_id">ห้องเรียน</label>
                        <select name="room_id">
                            <option value="">เลือกห้องเรียน</option>
                            <option value="">A001</option>
                            <option value="">B002</option>
                            <option value="">C003</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teacher_date">วัน</label>
                        <select name="teacher_date">
                            <option value="">เลือกวัน</option>
                            <option value="">วันจันทร์</option>
                            <option value="">วันอังคาร</option>
                            <option value="">วันพุธ</option>
                            <option value="">วันพฤหัสบดี</option>
                            <option value="">วันศุกร์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-name">เวลา</label>
                        <input type="text" id="search-name" name="search-name">
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="add_schedule" >บันทึกข้อมูล</button>
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