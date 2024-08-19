<?php
session_start(); // เริ่มต้นการทำงานของเซสชัน
require_once 'config/db.php'; // รวมไฟล์การเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // เก็บข้อความข้อผิดพลาดในเซสชัน
    header("location: index.php"); // เปลี่ยนเส้นทางไปที่หน้า index.php
    exit(); // หยุดการทำงานของสคริปต์
}

// รับค่าจากฟอร์มค้นหา
$searchName = isset($_POST['search_name']) ? $_POST['search_name'] : ''; // รับชื่อ-นามสกุลจากฟอร์มค้นหา
$searchSubjectGroup = isset($_POST['search_level']) ? $_POST['search_level'] : ''; // รับกลุ่มวิชาจากฟอร์มค้นหา

// สร้าง SQL query
$sql = "SELECT * FROM teacher WHERE 1"; // สร้างคำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง teacher

if (!empty($searchName)) {
    $sql .= " AND fullname LIKE :searchName"; // เพิ่มเงื่อนไขค้นหาชื่อ-นามสกุล
}
if (!empty($searchSubjectGroup)) {
    $sql .= " AND subject_group = :searchSubjectGroup"; // เพิ่มเงื่อนไขค้นหากลุ่มวิชา
}

$stmt = $pdo->prepare($sql); // เตรียมคำสั่ง SQL สำหรับการ execute

if (!empty($searchName)) {
    $searchNameParam = "%$searchName%"; // สร้างพารามิเตอร์สำหรับการค้นหาชื่อ-นามสกุล
    $stmt->bindParam(':searchName', $searchNameParam); // ผูกพารามิเตอร์กับค่าที่รับมา
}
if (!empty($searchSubjectGroup)) {
    $stmt->bindParam(':searchSubjectGroup', $searchSubjectGroup); // ผูกพารามิเตอร์กับค่าที่รับมา
}

$stmt->execute(); // รันคำสั่ง SQL
$teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากผลลัพธ์เป็นแบบ associative array

// ดึงข้อมูลกลุ่มวิชาทั้งหมด
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลกลุ่มวิชา
$stmt->execute(); // รันคำสั่ง SQL
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากผลลัพธ์เป็นแบบ associative array
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- รองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลคุณครู</title> <!-- ตั้งชื่อหัวข้อของหน้าเว็บ -->
    <link rel="stylesheet" href="teacher.css"> <!-- รวมไฟล์ CSS สำหรับสไตล์ของหน้า -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- รวมไอคอน Font Awesome -->
</head>

<body>

    <div class="container"> <!-- กล่องหลักของหน้า -->
        <div class="box-1"> <!-- กล่องย่อยที่ 1 -->
            <div class="box-2"> <!-- กล่องย่อยที่ 2 -->
                <div class="text-1"> <!-- กล่องสำหรับข้อความหัวเรื่อง -->
                    <h1>ข้อมูลคุณครู</h1> <!-- หัวข้อหลักของหน้า -->
                </div>
                <form class="search-form" method="POST" action=""> <!-- ฟอร์มสำหรับค้นหาข้อมูล -->
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_name">ชื่อ-นามสกุล</label> <!-- ป้ายกำกับสำหรับช่องชื่อ-นามสกุล -->
                        <input type="text" name="search_name" value="<?= htmlspecialchars($searchName); ?>"> <!-- ช่องป้อนข้อมูลชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_level">กลุ่มวิชาที่สอน</label> <!-- ป้ายกำกับสำหรับช่องกลุ่มวิชา -->
                        <select name="search_level"> <!-- ช่องเลือกกลุ่มวิชา -->
                            <option value="">เลือกกลุ่มวิชา</option> <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($subjectGroups as $group) { ?> <!-- ลูปเพื่อสร้างตัวเลือกจากข้อมูลกลุ่มวิชา -->
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>" <?= $searchSubjectGroup == htmlspecialchars($group['subj_group_name']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับปุ่มค้นหา -->
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button> <!-- ปุ่มค้นหาพร้อมไอคอน -->
                    </div>
                </form>

                <div class="btn-con"> <!-- กล่องสำหรับปุ่มเพิ่มเติม -->
                    <button class="add-student-button" onclick="window.location.href='add-teacher.php'">+ เพิ่มคุณครู</button> <!-- ปุ่มเพิ่มคุณครู -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button> <!-- ปุ่มออก -->
                </div>

                <div class="group-form1"> <!-- กล่องสำหรับข้อมูลตาราง -->
                    <div class="group-form2"> <!-- กล่องย่อยสำหรับข้อมูลตาราง -->
                        <table> <!-- เริ่มต้นตาราง -->
                            <thead> <!-- ส่วนหัวของตาราง -->
                                <tr> <!-- แถวหัวของตาราง -->
                                    <th>รหัสประจำตัว</th> <!-- หัวข้อคอลัมน์รหัสประจำตัว -->
                                    <th>ชื่อ-นามสกุล</th> <!-- หัวข้อคอลัมน์ชื่อ-นามสกุล -->
                                    <th>รูปถ่าย</th> <!-- หัวข้อคอลัมน์รูปถ่าย -->
                                    <th>เบอร์โทร</th> <!-- หัวข้อคอลัมน์เบอร์โทร -->
                                    <th>กลุ่มวิชาที่สอน</th> <!-- หัวข้อคอลัมน์กลุ่มวิชา -->
                                    <th>การจัดการ</th> <!-- หัวข้อคอลัมน์การจัดการ -->
                                </tr>
                            </thead>
                            <tbody> <!-- ส่วนเนื้อหาของตาราง -->
                                <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อความข้อผิดพลาดในเซสชันหรือไม่ -->
                                    <div class="alert-danger"> <!-- กล่องข้อความข้อผิดพลาด -->
                                        <?php
                                        echo $_SESSION['error']; // แสดงข้อความข้อผิดพลาด
                                        unset($_SESSION['error']); // ลบข้อความข้อผิดพลาดจากเซสชัน
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['success'])) { ?> <!-- ตรวจสอบว่ามีข้อความสำเร็จในเซสชันหรือไม่ -->
                                    <div class="alert-success"> <!-- กล่องข้อความสำเร็จ -->
                                        <?php
                                        echo $_SESSION['success']; // แสดงข้อความสำเร็จ
                                        unset($_SESSION['success']); // ลบข้อความสำเร็จจากเซสชัน
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['warning'])) { ?> <!-- ตรวจสอบว่ามีข้อความเตือนในเซสชันหรือไม่ -->
                                    <div class="alert-warning"> <!-- กล่องข้อความเตือน -->
                                        <?php
                                        echo $_SESSION['warning']; // แสดงข้อความเตือน
                                        unset($_SESSION['warning']); // ลบข้อความเตือนจากเซสชัน
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php
                                if (!$teacherData) { // ตรวจสอบว่ามีข้อมูลของครูหรือไม่
                                    echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>"; // แสดงข้อความถ้าไม่มีข้อมูล
                                } else {
                                    foreach ($teacherData as $teacher) { // ลูปเพื่อแสดงข้อมูลของครูแต่ละคน
                                ?>
                                        <tr> <!-- แถวข้อมูลของครู -->
                                            <td><?= htmlspecialchars($teacher['t_code']); ?></td> <!-- รหัสประจำตัวของครู -->
                                            <td><?= htmlspecialchars($teacher['fullname']); ?></td> <!-- ชื่อ-นามสกุลของครู -->
                                            <td><img width="40px" src="uploads/<?= htmlspecialchars($teacher['photo']); ?>" alt="รูปถ่าย"></td> <!-- รูปถ่ายของครู -->
                                            <td><?= htmlspecialchars($teacher['phone']); ?></td> <!-- เบอร์โทรของครู -->
                                            <td><?= htmlspecialchars($teacher['subject_group']); ?></td> <!-- กลุ่มวิชาที่ครูสอน -->
                                            <td> <!-- คอลัมน์การจัดการ -->
                                                <a href="edit_teacher.php?t_id=<?= htmlspecialchars($teacher['t_id']); ?>"><i class="fa-solid fa-pen"></i></a> <!-- ลิงค์สำหรับแก้ไขข้อมูลครู -->
                                                |
                                                <a href="delete_teacher_db.php?delete=<?= htmlspecialchars($teacher['t_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a> <!-- ลิงค์สำหรับลบข้อมูลครู -->
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table> <!-- จบตาราง -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>