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
$searchLevel = isset($_POST['search_level']) ? $_POST['search_level'] : ''; // รับระดับชั้นจากฟอร์มค้นหา

// สร้าง SQL query
$sql = "SELECT * FROM student WHERE 1=1"; // สร้างคำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง student

if (!empty($searchName)) {
    $sql .= " AND fullname LIKE :searchName"; // เพิ่มเงื่อนไขค้นหาชื่อ-นามสกุล
}
if (!empty($searchLevel)) {
    $sql .= " AND level = :searchLevel"; // เพิ่มเงื่อนไขค้นหาระดับชั้น
}

$stmt = $pdo->prepare($sql); // เตรียมคำสั่ง SQL สำหรับการ execute

if (!empty($searchName)) {
    // แปลง $searchName เป็นค่า `%$searchName%` เฉพาะในการค้นหา
    $searchNameParam = "%$searchName%";
    $stmt->bindParam(':searchName', $searchNameParam); // ผูกพารามิเตอร์ค้นหาชื่อ-นามสกุล
}
if (!empty($searchLevel)) {
    $stmt->bindParam(':searchLevel', $searchLevel); // ผูกพารามิเตอร์ค้นหาระดับชั้น
}

$stmt->execute(); // รันคำสั่ง SQL
$studentData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดจากผลลัพธ์เป็นแบบ associative array
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- รองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลนักเรียน</title> <!-- ตั้งชื่อหัวข้อของหน้าเว็บ -->
    <link rel="stylesheet" href="data-student.css"> <!-- รวมไฟล์ CSS สำหรับสไตล์ของหน้า -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- รวมไอคอน Font Awesome -->
</head>

<body>

    <div class="container"> <!-- กล่องหลักของหน้า -->
        <div class="box-1"> <!-- กล่องย่อยที่ 1 -->
            <div class="box-2"> <!-- กล่องย่อยที่ 2 -->
                <div class="text-1"> <!-- กล่องสำหรับข้อความหัวเรื่อง -->
                    <h1>ข้อมูลนักเรียน</h1> <!-- หัวข้อหลักของหน้า -->
                </div>
                <form method="POST" action="" class="search-form"> <!-- ฟอร์มสำหรับค้นหาข้อมูล -->
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_name">ชื่อ-นามสกุล</label> <!-- ป้ายกำกับสำหรับช่องชื่อ-นามสกุล -->
                        <input type="text" name="search_name" value="<?= htmlspecialchars($searchName); ?>"> <!-- ช่องป้อนข้อมูลชื่อ-นามสกุล -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_level">ระดับชั้น</label> <!-- ป้ายกำกับสำหรับช่องระดับชั้น -->
                        <select name="search_level"> <!-- ช่องเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="ม.1" <?= $searchLevel == 'ม.1' ? 'selected' : ''; ?>>ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="ม.2" <?= $searchLevel == 'ม.2' ? 'selected' : ''; ?>>ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="ม.3" <?= $searchLevel == 'ม.3' ? 'selected' : ''; ?>>ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับปุ่มค้นหา -->
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button> <!-- ปุ่มค้นหาพร้อมไอคอน -->
                    </div>
                </form>

                <div class="btn-con"> <!-- กล่องสำหรับปุ่มเพิ่มเติม -->
                    <button class="add-student-button" onclick="window.location.href='add-student.php'">+ เพิ่มนักเรียน</button> <!-- ปุ่มเพิ่มนักเรียน -->
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
                                    <th>ระดับชั้น</th> <!-- หัวข้อคอลัมน์ระดับชั้น -->
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
                                if (!$studentData) { // ตรวจสอบว่ามีข้อมูลของนักเรียนหรือไม่
                                    echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>"; // แสดงข้อความถ้าไม่มีข้อมูล
                                } else {
                                    foreach ($studentData as $student) { // ลูปเพื่อแสดงข้อมูลของนักเรียนแต่ละคน
                                ?>
                                        <tr> <!-- แถวข้อมูลของนักเรียน -->
                                            <td><?= htmlspecialchars($student['s_code']); ?></td> <!-- รหัสประจำตัวของนักเรียน -->
                                            <td><?= htmlspecialchars($student['fullname']); ?></td> <!-- ชื่อ-นามสกุลของนักเรียน -->
                                            <td><img src="uploads_student/<?= htmlspecialchars($student['photo']); ?>" alt="รูปถ่าย" width="40"></td> <!-- รูปถ่ายของนักเรียน -->
                                            <td><?= htmlspecialchars($student['phone']); ?></td> <!-- เบอร์โทรของนักเรียน -->
                                            <td><?= htmlspecialchars($student['level']); ?></td> <!-- ระดับชั้นของนักเรียน -->
                                            <td> <!-- คอลัมน์การจัดการ -->
                                                <a href="edit_student.php?s_id=<?= htmlspecialchars($student['s_id']); ?>"><i class="fa-solid fa-pen"></i></a> <!-- ลิงค์สำหรับแก้ไขข้อมูลนักเรียน -->
                                                |
                                                <a href="delete_student_db.php?delete=<?= htmlspecialchars($student['s_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a> <!-- ลิงค์สำหรับลบข้อมูลนักเรียน -->
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