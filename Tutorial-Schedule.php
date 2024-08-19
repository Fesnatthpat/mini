<?php
session_start(); // เริ่มต้นเซสชันเพื่อใช้ข้อมูลการเข้าสู่ระบบ
require_once 'config/db.php'; // รวมไฟล์ฐานข้อมูลเพื่อเชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าเซสชันมีการเข้าสู่ระบบของผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ตั้งค่าข้อความผิดพลาด
    header("location: index.php"); // เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
    exit(); // หยุดการทำงานของสคริปต์
}

// รับค่าที่ผู้ใช้กรอกในฟอร์มค้นหา ถ้าไม่ได้กรอกจะใช้ค่าเริ่มต้นเป็นค่าว่าง
$searchLevel = isset($_POST['search_level']) ? $_POST['search_level'] : '';
$searchSemester = isset($_POST['search_semester']) ? $_POST['search_semester'] : '';
$searchYear = isset($_POST['search_year']) ? $_POST['search_year'] : '';

// สร้างคำสั่ง SQL สำหรับค้นหาข้อมูลตารางสอน
$sql = "SELECT s.schedule_id, sj.subject_name, sj.subject_code, sj.subject_id, t.t_id, t.fullname, s.teacher_time, r.room_id, r.room_no
        FROM schedule AS s
        JOIN teacher AS t ON s.t_id = t.t_id
        JOIN subject AS sj ON s.subject_id = sj.subject_id
        JOIN room AS r ON s.room_id = r.room_id
        WHERE 1";

// เพิ่มเงื่อนไขการค้นหาตามค่าที่ผู้ใช้กรอก
if (!empty($searchLevel)) {
    $sql .= " AND s.level = :searchLevel"; // เพิ่มเงื่อนไขสำหรับระดับชั้น
}
if (!empty($searchSemester)) {
    $sql .= " AND s.semester = :searchSemester"; // เพิ่มเงื่อนไขสำหรับภาคเรียน
}
if (!empty($searchYear)) {
    $sql .= " AND s.academic_year = :searchYear"; // เพิ่มเงื่อนไขสำหรับปีการศึกษา
}

// เตรียมคำสั่ง SQL เพื่อป้องกัน SQL Injection
$stmt = $pdo->prepare($sql);

// ผูกค่าพารามิเตอร์กับคำสั่ง SQL ถ้ามีค่า
if (!empty($searchLevel)) {
    $stmt->bindParam(':searchLevel', $searchLevel);
}
if (!empty($searchSemester)) {
    $stmt->bindParam(':searchSemester', $searchSemester);
}
if (!empty($searchYear)) {
    $stmt->bindParam(':searchYear', $searchYear);
}

// รันคำสั่ง SQL และดึงข้อมูลตารางสอน
$stmt->execute();
$scheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากการค้นหา

// ดึงข้อมูลชื่ออาคารจากฐานข้อมูล
$sqlBuildings = "SELECT DISTINCT building_name FROM building"; // สร้างคำสั่ง SQL เพื่อดึงชื่ออาคารที่ไม่ซ้ำ
$stmt = $pdo->prepare($sqlBuildings); // เตรียมคำสั่ง SQL
$stmt->execute(); // รันคำสั่ง SQL
$buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลชื่ออาคารทั้งหมด

// กำหนดข้อความหัวข้อสำหรับแสดงผล
$title = 'ข้อมูลตารางสอน'; // ข้อความหัวข้อเริ่มต้น
if (!empty($searchLevel) && !empty($searchSemester) && !empty($searchYear)) {
    $title = "ตารางสอน ภาคเรียนที่{$searchSemester} ปีการศึกษา{$searchYear} ระดับชั้น {$searchLevel}";
} elseif (!empty($searchLevel) && !empty($searchSemester)) {
    $title = "ตารางสอน ภาคเรียนที่{$searchSemester} ระดับชั้น {$searchLevel}";
} elseif (!empty($searchLevel) && !empty($searchYear)) {
    $title = "ตารางสอน ปีการศึกษา{$searchYear} ระดับชั้น {$searchLevel}";
} elseif (!empty($searchSemester) && !empty($searchYear)) {
    $title = "ตารางสอน ภาคเรียนที่{$searchSemester} ปีการศึกษา{$searchYear}";
} elseif (!empty($searchLevel)) {
    $title = "ตารางสอน ระดับชั้น {$searchLevel}";
} elseif (!empty($searchSemester)) {
    $title = "ตารางสอน ภาคเรียนที่{$searchSemester}";
} elseif (!empty($searchYear)) {
    $title = "ตารางสอน ปีการศึกษา{$searchYear}";
}
?>

<!DOCTYPE html>
<html lang="th"> <!-- กำหนดภาษาเป็นไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- รองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลตารางสอน</title> <!-- ชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="data-student.css"> <!-- รวมไฟล์ CSS สำหรับการจัดรูปแบบ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- รวม Font Awesome สำหรับไอคอน -->
</head>

<body>

    <div class="container"> <!-- Container สำหรับจัดการรูปแบบ -->
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h2><?= htmlspecialchars($title); ?></h2> <!-- แสดงหัวข้อที่กำหนด -->
                </div>
                <form action="" method="POST" class="search-form"> <!-- ฟอร์มค้นหาข้อมูลตารางสอน -->
                    <div class="form-group">
                        <label for="search-level">ระดับชั้น</label> <!-- ป้ายชื่อฟอร์ม -->
                        <select id="search-level" name="search_level"> <!-- เลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกว่าง -->
                            <option value="ม.1" <?= $searchLevel == 'ม.1' ? 'selected' : ''; ?>>ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="ม.2" <?= $searchLevel == 'ม.2' ? 'selected' : ''; ?>>ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="ม.3" <?= $searchLevel == 'ม.3' ? 'selected' : ''; ?>>ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-semester">ภาคเรียนที่</label> <!-- ป้ายชื่อฟอร์ม -->
                        <select id="search-semester" name="search_semester"> <!-- เลือกภาคเรียน -->
                            <option value="">เลือกภาคเรียน</option> <!-- ตัวเลือกว่าง -->
                            <option value="1" <?= $searchSemester == '1' ? 'selected' : ''; ?>>1</option> <!-- ตัวเลือกภาคเรียน 1 -->
                            <option value="2" <?= $searchSemester == '2' ? 'selected' : ''; ?>>2</option> <!-- ตัวเลือกภาคเรียน 2 -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-year">ปีการศึกษา</label> <!-- ป้ายชื่อฟอร์ม -->
                        <select id="search-year" name="search_year"> <!-- เลือกปีการศึกษา -->
                            <option value="">เลือกปีการศึกษา</option> <!-- ตัวเลือกว่าง -->
                            <option value="2567" <?= $searchYear == '2567' ? 'selected' : ''; ?>>2567</option> <!-- ตัวเลือกปีการศึกษา 2567 -->
                            <option value="2566" <?= $searchYear == '2566' ? 'selected' : ''; ?>>2566</option> <!-- ตัวเลือกปีการศึกษา 2566 -->
                            <option value="2565" <?= $searchYear == '2565' ? 'selected' : ''; ?>>2565</option> <!-- ตัวเลือกปีการศึกษา 2565 -->
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button> <!-- ปุ่มค้นหา -->
                    </div>
                </form>

                <div class="btn-con"> <!-- Container สำหรับปุ่มจัดการ -->
                    <button class="add-student-button" onclick="window.location.href='add-schedule.php'">+ เพิ่มตารางสอน</button> <!-- ปุ่มเพิ่มตารางสอน -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button> <!-- ปุ่มออก -->
                </div>
                <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อความผิดพลาดในเซสชันหรือไม่ -->
                    <div class="alert-danger"> <!-- แสดงข้อความผิดพลาด -->
                        <?php
                        echo $_SESSION['error']; // แสดงข้อความผิดพลาด
                        unset($_SESSION['error']); // ลบข้อความผิดพลาดจากเซสชัน
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบว่ามีข้อความสำเร็จในเซสชันหรือไม่ -->
                    <div class="alert-success"> <!-- แสดงข้อความสำเร็จ -->
                        <?php
                        echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                        unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชัน
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบว่ามีข้อความเตือนในเซสชันหรือไม่ -->
                    <div class="alert-warning"> <!-- แสดงข้อความเตือน -->
                        <?php
                        echo $_SESSION['warning']; // แสดงข้อความเตือน
                        unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชัน
                        ?>
                    </div>
                <?php } ?>
                <div class="group-form1">
                    <div class="group-form2">
                        <table> <!-- ตารางแสดงข้อมูลตารางสอน -->
                            <thead> <!-- ส่วนหัวของตาราง -->
                                <tr>
                                    <th>รหัสวิชา</th> <!-- หัวข้อคอลัมน์รหัสวิชา -->
                                    <th>ชื่อวิชา</th> <!-- หัวข้อคอลัมน์ชื่อวิชา -->
                                    <th>ครูผู้สอน</th> <!-- หัวข้อคอลัมน์ครูผู้สอน -->
                                    <th>ห้องเรียน</th> <!-- หัวข้อคอลัมน์ห้องเรียน -->
                                    <th>วันเวลา</th> <!-- หัวข้อคอลัมน์วันเวลา -->
                                    <th>การจัดการ</th> <!-- หัวข้อคอลัมน์การจัดการ -->
                                </tr>
                            </thead>
                            <tbody> <!-- ส่วนเนื้อหาของตาราง -->
                                <?php if (empty($scheduleData)) { ?> <!-- ตรวจสอบว่ามีข้อมูลตารางสอนหรือไม่ -->
                                    <tr>
                                        <td colspan="6">ไม่มีข้อมูล</td> <!-- แสดงข้อความถ้าไม่มีข้อมูล -->
                                    </tr>
                                <?php } else { ?> <!-- ถ้ามีข้อมูล -->
                                    <?php foreach ($scheduleData as $schedules) { ?> <!-- วนลูปแสดงข้อมูลตารางสอน -->
                                        <tr>
                                            <td><?= htmlspecialchars($schedules['subject_code']); ?></td> <!-- แสดงรหัสวิชา -->
                                            <td><?= htmlspecialchars($schedules['subject_name']); ?></td> <!-- แสดงชื่อวิชา -->
                                            <td><?= htmlspecialchars($schedules['fullname']); ?></td> <!-- แสดงชื่อครู -->
                                            <td><?= htmlspecialchars($schedules['room_no']); ?></td> <!-- แสดงหมายเลขห้อง -->
                                            <td><?= htmlspecialchars($schedules['teacher_time']); ?></td> <!-- แสดงวันเวลา -->
                                            <td>
                                                <a href="edit_schedule.php?schedule_id=<?= htmlspecialchars($schedules['schedule_id']); ?>"><i class="fa-solid fa-pen"></i></a> | <!-- ลิงก์ไปยังหน้าการแก้ไขข้อมูลตารางสอน -->
                                                <a href="delete_schedule_db.php?delete=<?= htmlspecialchars($schedules['schedule_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a> <!-- ลิงก์ไปยังหน้าการลบข้อมูลตารางสอน -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>