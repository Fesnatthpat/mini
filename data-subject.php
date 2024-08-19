<?php
session_start(); // เริ่มต้นเซสชันเพื่อใช้จัดการข้อมูลการเข้าสู่ระบบ
require_once 'config/db.php'; // รวมไฟล์ฐานข้อมูลเพื่อใช้เชื่อมต่อฐานข้อมูล

// ตรวจสอบสิทธิ์การเข้าถึง ถ้าไม่มีการเข้าสู่ระบบให้เปลี่ยนเส้นทางไปยังหน้า index.php
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // ตั้งค่า error message
    header("location: index.php"); // เปลี่ยนเส้นทางไปยังหน้า login
    exit(); // หยุดการทำงานของสคริปต์
}

// รับค่าจากฟอร์มค้นหา ถ้าไม่ได้ค่าจะใช้ค่าว่างเป็นค่าเริ่มต้น
$searchName = isset($_POST['search_name']) ? $_POST['search_name'] : '';
$searchLevel = isset($_POST['search_level']) ? $_POST['search_level'] : '';
$searchGroup = isset($_POST['search_group']) ? $_POST['search_group'] : '';

// ดึงข้อมูลกลุ่มวิชาจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute(); // รันคำสั่ง SQL
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดและจัดเก็บใน array

// สร้าง SQL query สำหรับค้นหาข้อมูลวิชา
$sql = "SELECT * FROM subject WHERE 1=1";

// เพิ่มเงื่อนไขการค้นหาตามค่าที่ผู้ใช้กรอก
if (!empty($searchName)) {
    $sql .= " AND subject_name LIKE :searchName"; // เงื่อนไขค้นหาชื่อวิชา
}
if (!empty($searchLevel)) {
    $sql .= " AND level = :searchLevel"; // เงื่อนไขค้นหาระดับชั้น
}
if (!empty($searchGroup)) {
    $sql .= " AND subject_group = :searchGroup"; // เงื่อนไขค้นหากลุ่มวิชา
}

$stmt = $pdo->prepare($sql); // เตรียมคำสั่ง SQL

// ผูกค่าพารามิเตอร์กับ SQL query
if (!empty($searchName)) {
    $searchNameParam = "%$searchName%"; // สร้างพารามิเตอร์สำหรับการค้นหาชื่อวิชา
    $stmt->bindParam(':searchName', $searchNameParam); // ผูกพารามิเตอร์กับคำสั่ง SQL
}
if (!empty($searchLevel)) {
    $stmt->bindParam(':searchLevel', $searchLevel); // ผูกพารามิเตอร์กับคำสั่ง SQL
}
if (!empty($searchGroup)) {
    $stmt->bindParam(':searchGroup', $searchGroup); // ผูกพารามิเตอร์กับคำสั่ง SQL
}

$stmt->execute(); // รันคำสั่ง SQL
$subjectData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลวิชาที่ค้นหา

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- รองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลรายวิชา</title> <!-- ชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="data-subject.css"> <!-- รวมไฟล์ CSS สำหรับการจัดรูปแบบ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- รวม Font Awesome สำหรับไอคอน -->
</head>

<body>

    <div class="container"> <!-- Container สำหรับจัดการรูปแบบ -->
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลรายวิชา</h1> <!-- หัวข้อของหน้า -->
                </div>
                <form action="" method="POST" class="search-form"> <!-- ฟอร์มค้นหาข้อมูลวิชา -->
                    <div class="form-group">
                        <label for="search_name">ชื่อวิชา</label> <!-- ป้ายชื่อฟอร์ม -->
                        <input type="text" name="search_name" value="<?= htmlspecialchars($searchName); ?>"> <!-- กล่องข้อความสำหรับกรอกชื่อวิชา -->
                    </div>
                    <div class="form-group">
                        <label for="search_level">ระดับชั้น</label> <!-- ป้ายชื่อฟอร์ม -->
                        <select name="search_level"> <!-- เลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกว่าง -->
                            <option value="ม.1" <?= $searchLevel == 'ม.1' ? 'selected' : ''; ?>>ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="ม.2" <?= $searchLevel == 'ม.2' ? 'selected' : ''; ?>>ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="ม.3" <?= $searchLevel == 'ม.3' ? 'selected' : ''; ?>>ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search_group">กลุ่มวิชาที่สอน</label> <!-- ป้ายชื่อฟอร์ม -->
                        <select name="search_group"> <!-- เลือกกลุ่มวิชา -->
                            <option value="">เลือกกลุ่มวิชา</option> <!-- ตัวเลือกว่าง -->
                            <?php foreach ($subjectGroups as $group): ?> <!-- วนลูปผ่านกลุ่มวิชา -->
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>" <?= $searchGroup == $group['subj_group_name'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option> <!-- ตัวเลือกกลุ่มวิชา -->
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button> <!-- ปุ่มค้นหา -->
                    </div>
                </form>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-subject.php'">+ เพิ่มวิชา</button> <!-- ปุ่มเพิ่มวิชา -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button> <!-- ปุ่มออก -->
                </div>

                <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่าเซสชันมีข้อผิดพลาดหรือไม่ -->
                    <div class="alert-danger">
                        <?php
                        echo $_SESSION['error']; // แสดงข้อความข้อผิดพลาด
                        unset($_SESSION['error']); // ลบข้อความข้อผิดพลาดหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบว่าเซสชันมีข้อความสำเร็จหรือไม่ -->
                    <div class="alert-success">
                        <?php
                        echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                        unset($_SESSION['success']); // ลบข้อความสำเร็จหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบว่าเซสชันมีข้อความเตือนหรือไม่ -->
                    <div class="alert-warning">
                        <?php
                        echo $_SESSION['warning']; // แสดงข้อความเตือน
                        unset($_SESSION['warning']); // ลบข้อความเตือนหลังจากแสดงแล้ว
                        ?>
                    </div>
                <?php } ?>

                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th> <!-- หัวข้อคอลัมน์สำหรับรหัสวิชา -->
                                    <th>ชื่อวิชา</th> <!-- หัวข้อคอลัมน์สำหรับชื่อวิชา -->
                                    <th>ปกหนังสือ</th> <!-- หัวข้อคอลัมน์สำหรับปกหนังสือ -->
                                    <th>กลุ่มรายวิชา</th> <!-- หัวข้อคอลัมน์สำหรับกลุ่มรายวิชา -->
                                    <th>ระดับชั้น</th> <!-- หัวข้อคอลัมน์สำหรับระดับชั้น -->
                                    <th>การจัดการ</th> <!-- หัวข้อคอลัมน์สำหรับการจัดการ -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!$subjectData) { // ถ้าไม่มีข้อมูล
                                    echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>"; // แสดงข้อความไม่มีข้อมูล
                                } else {
                                    foreach ($subjectData as $subject) { // วนลูปผ่านข้อมูลวิชาที่ค้นหา
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($subject['subject_code']); ?></td> <!-- แสดงรหัสวิชา -->
                                            <td><?= htmlspecialchars($subject['subject_name']); ?></td> <!-- แสดงชื่อวิชา -->
                                            <td><img width="40px" src="uploads_subject2/<?= htmlspecialchars($subject['photo']); ?>" alt="รูปถ่าย"></td> <!-- แสดงรูปปกหนังสือ -->
                                            <td><?= htmlspecialchars($subject['subject_group']); ?></td> <!-- แสดงกลุ่มวิชา -->
                                            <td><?= htmlspecialchars($subject['level']); ?></td> <!-- แสดงระดับชั้น -->
                                            <td>
                                                <a href="edit_subject.php?subject_id=<?= htmlspecialchars($subject['subject_id']); ?>"><i class="fa-solid fa-pen"></i></a> | <!-- ลิงก์ไปยังหน้าแก้ไขข้อมูลวิชา -->
                                                <a href="delete_subject_db.php?delete=<?= htmlspecialchars($subject['subject_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a> <!-- ลิงก์ไปยังหน้าลบข้อมูลวิชา -->
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>