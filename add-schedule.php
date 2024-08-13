<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

try {
    // ดึงข้อมูลระดับชั้น
    $stmt = $pdo->prepare("SELECT level FROM student GROUP BY level");
    $stmt->execute();
    $datlevel = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลกลุ่มวิชา
    $stmt = $pdo->prepare("SELECT subject_id, subject_name  FROM subject");
    $stmt->execute();
    $subjectData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลชื่อครู
    $stmt = $pdo->prepare("SELECT t_id, fullname FROM teacher");
    $stmt->execute();
    $datateacher = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลหมายเลขห้อง
    $stmt = $pdo->prepare("SELECT room_id, room_no FROM room");
    $stmt->execute();
    $dataroom = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
                <form action="add-schedule_db.php" method="POST">
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert-danger"><?php echo $_SESSION['error'];
                                                    unset($_SESSION['error']); ?></div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert-success"><?php echo $_SESSION['success'];
                                                    unset($_SESSION['success']); ?></div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <div class="alert-warning"><?php echo $_SESSION['warning'];
                                                    unset($_SESSION['warning']); ?></div>
                    <?php } ?>

                    <div class="form-group">
                        <label for="semester">ภาคเรียนที่</label>
                        <select name="semester" required>
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="academic_year">ปีการศึกษา</label>
                        <select name="academic_year" required>
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
                            <?php foreach ($subjectData as $subjects) { ?>
                                <option value="<?php echo htmlspecialchars($subjects['subject_id']); ?>">
                                    <?php echo htmlspecialchars($subjects['subject_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <select name="level" required>
                            <option value="">เลือกระดับชั้น</option>
                            <?php foreach ($datlevel as $levels) { ?>
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
                        <label for="teacher_date">วัน</label>
                        <select name="teacher_date" required>
                            <option value="">เลือกวัน</option>
                            <option value="วันจันทร์">วันจันทร์</option>
                            <option value="วันอังคาร">วันอังคาร</option>
                            <option value="วันพุธ">วันพุธ</option>
                            <option value="วันพฤหัสบดี">วันพฤหัสบดี</option>
                            <option value="วันศุกร์">วันศุกร์</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="teacher_time">เวลา</label>
                        <input type="text" name="teacher_time" required>
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