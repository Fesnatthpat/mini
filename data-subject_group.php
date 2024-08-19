<?php
// เริ่มต้นเซสชันเพื่อจัดการข้อมูลเซสชัน
session_start();
// รวมไฟล์การตั้งค่าฐานข้อมูล
require_once 'config/db.php';

try {
    // เตรียมการคำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง subject_group
    $stmt = $pdo->prepare("SELECT * FROM subject_group");
    // ดำเนินการคำสั่ง SQL
    $stmt->execute();
    // ดึงข้อมูลทั้งหมดจากผลลัพธ์ในรูปแบบ associative array
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // หากเกิดข้อผิดพลาดในการเชื่อมต่อหรือการดำเนินการ SQL จะแสดงข้อความข้อผิดพลาด
    echo "Error: " . $e->getMessage();
}

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบเป็นผู้ดูแลหรือไม่
if (!isset($_SESSION['admin_login'])) {
    // หากไม่ได้เข้าสู่ระบบ ผู้ใช้จะไม่สามารถเข้าถึงหน้านี้ได้
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    // เปลี่ยนเส้นทางไปยังหน้า index.php
    header("location: index.php");
    // หยุดการดำเนินการของสคริปต์
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<!-- เริ่มต้นเอกสาร HTML และกำหนดภาษาเป็นไทย -->

<head>
    <meta charset="UTF-8">
    <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ตั้งค่า viewport สำหรับการแสดงผลบนอุปกรณ์เคลื่อนที่ -->
    <title>ข้อมูลกลุ่มวิชา</title>
    <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="data-subject_group.css">
    <!-- เชื่อมโยงไปยังไฟล์ CSS สำหรับการจัดรูปแบบ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- เชื่อมโยงไปยัง Font Awesome สำหรับไอคอน -->
</head>

<body>
    <!-- เริ่มต้นเนื้อหาของหน้าเว็บ -->

    <div class="container">
        <!-- กล่องหลักของหน้าเว็บ -->
        <div class="box-1">
            <!-- กล่องที่ใช้สำหรับจัดตำแหน่งเนื้อหาภายใน -->
            <div class="box-2">
                <!-- กล่องที่ใช้สำหรับการจัดการการจัดรูปแบบ -->
                <div class="text-1">
                    <!-- กล่องสำหรับจัดตำแหน่งข้อความ -->
                    <h1>ข้อมูลกลุ่มวิชา</h1>
                    <!-- หัวเรื่องของหน้า -->
                </div>
                <form action="add_subject_group_db.php" method="POST" class="search-form">
                    <!-- ฟอร์มสำหรับการเพิ่มกลุ่มวิชา -->
                    <div class="form-group">
                        <!-- กลุ่มของฟอร์ม -->
                        <label for="search-name">ชื่อวิชา</label>
                        <!-- ป้ายชื่อของช่องกรอกข้อมูล -->
                        <input type="text" id="search-name" name="subject_group_name">
                        <!-- ช่องกรอกข้อมูลสำหรับชื่อวิชา -->
                    </div>
                    <div class="form-group">
                        <!-- กลุ่มของฟอร์ม -->
                        <button type="submit" name="add_subject">บันทึกข้อมูล</button>
                        <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                    </div>
                </form>
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert-danger" role="alert">
                        <!-- กล่องแจ้งเตือนข้อผิดพลาด -->
                        <?php
                        echo $_SESSION['error'];
                        // แสดงข้อความข้อผิดพลาดจากเซสชัน
                        unset($_SESSION['error']);
                        // ลบข้อความข้อผิดพลาดออกจากเซสชัน
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert-success" role="alert">
                        <!-- กล่องแจ้งเตือนความสำเร็จ -->
                        <?php
                        echo $_SESSION['success'];
                        // แสดงข้อความความสำเร็จจากเซสชัน
                        unset($_SESSION['success']);
                        // ลบข้อความความสำเร็จออกจากเซสชัน
                        ?>
                    </div>
                <?php } ?>
                <div class="btn-con">
                    <!-- กล่องสำหรับปุ่ม -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                    <!-- ปุ่มออกที่เชื่อมโยงไปยังหน้า home.php -->
                </div>

                <div class="group-form1">
                    <!-- กล่องสำหรับจัดรูปแบบตาราง -->
                    <div class="group-form2">
                        <!-- กล่องที่ใช้สำหรับจัดการการจัดรูปแบบ -->
                        <table>
                            <!-- เริ่มต้นตาราง -->
                            <thead>
                                <!-- ส่วนหัวของตาราง -->
                                <tr>
                                    <!-- แถวหัวของตาราง -->
                                    <th>กลุ่มวิชา</th>
                                    <!-- หัวคอลัมน์สำหรับชื่อกลุ่มวิชา -->
                                    <th>การจัดการ</th>
                                    <!-- หัวคอลัมน์สำหรับการจัดการ -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ส่วนของเนื้อหาตาราง -->
                                <?php foreach ($subjects as $subject) { ?>
                                    <tr>
                                        <!-- แถวของข้อมูลในตาราง -->
                                        <td><?php echo htmlspecialchars($subject['subj_group_name']); ?></td>
                                        <!-- แสดงชื่อกลุ่มวิชาโดยใช้ htmlspecialchars() เพื่อป้องกันการโจมตี XSS -->
                                        <td>
                                            <a href="delete_subject_group_db.php?delete=<?= htmlspecialchars($subject['subj_group_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');">
                                                <!-- ลิงก์สำหรับลบกลุ่มวิชา -->
                                                <i class="fa-solid fa-trash"></i>
                                                <!-- ไอคอนถังขยะจาก Font Awesome -->
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- ทำซ้ำสำหรับกลุ่มวิชาทั้งหมดที่ดึงมาจากฐานข้อมูล -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>