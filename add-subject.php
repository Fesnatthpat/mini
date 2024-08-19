<?php
session_start();
/* เริ่มต้นการจัดการเซสชันของ PHP */

require_once 'config/db.php';
/* รวมไฟล์การเชื่อมต่อฐานข้อมูล ซึ่งมีการกำหนดตัวแปร $pdo สำหรับการเชื่อมต่อ */

if (!isset($_SESSION['admin_login'])) {
    /* ตรวจสอบว่าเซสชัน 'admin_login' ถูกตั้งค่าไว้หรือไม่ */
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    /* ถ้าไม่มีการตั้งค่า เซสชัน 'admin_login' จะตั้งค่าข้อความผิดพลาด */
    header("location: index.php");
    /* เปลี่ยนเส้นทางไปยังหน้าหลัก (index.php) */
    exit;
    /* ออกจากการทำงานของสคริปต์เพื่อป้องกันการดำเนินการต่อไป */
}

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    /* เตรียมคำสั่ง SQL เพื่อดึงข้อมูลชื่อกลุ่มวิชา */
    $stmt->execute();
    /* ทำการรันคำสั่ง SQL */
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* ดึงข้อมูลทั้งหมดจากผลลัพธ์และจัดเก็บในตัวแปร $subjectGroups */
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    /* ถ้ามีข้อผิดพลาดในการเชื่อมต่อหรือรันคำสั่ง SQL แสดงข้อผิดพลาด */
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- กำหนดให้หน้าเว็บรองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลนักเรียน</title>
    <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="add-subject.css">
    <!-- เชื่อมโยงไฟล์ CSS สำหรับจัดรูปแบบ -->
</head>

<body>

    <div class="form-container">
        <!-- กล่องหลักสำหรับฟอร์ม -->
        <div class="box1">
            <!-- กล่องสำหรับการจัดตำแหน่งฟอร์มให้อยู่กลางหน้า -->
            <div class="box2">
                <!-- กล่องสำหรับฟอร์ม -->
                <h1 class="text-teacher">ข้อมูลรายวิชา</h1>
                <!-- หัวข้อของฟอร์ม -->
                <hr>
                <!-- เส้นแบ่ง -->
                <form action="add_subject_db.php" method="POST" enctype="multipart/form-data">
                    <!-- ฟอร์มสำหรับส่งข้อมูลไปยัง add_subject_db.php ด้วยวิธี POST และสามารถอัปโหลดไฟล์ได้ -->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert-danger">
                            <?php
                            echo $_SESSION['error'];
                            /* แสดงข้อความผิดพลาดจากเซสชัน 'error' */
                            unset($_SESSION['error']);
                            /* ลบข้อความผิดพลาดจากเซสชันหลังจากแสดงแล้ว */
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert-success">
                            <?php
                            echo $_SESSION['success'];
                            /* แสดงข้อความสำเร็จจากเซสชัน 'success' */
                            unset($_SESSION['success']);
                            /* ลบข้อความสำเร็จจากเซสชันหลังจากแสดงแล้ว */
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?>
                        <div class="alert-warning">
                            <?php
                            echo $_SESSION['warning'];
                            /* แสดงข้อความเตือนจากเซสชัน 'warning' */
                            unset($_SESSION['warning']);
                            /* ลบข้อความเตือนจากเซสชันหลังจากแสดงแล้ว */
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <!-- กลุ่มฟอร์มสำหรับรหัสวิชา -->
                        <label for="student-id">รหัสวิชา</label>
                        <!-- ป้ายชื่อสำหรับรหัสวิชา -->
                        <input type="text" id="subject_code" name="subject_code">
                        <!-- ช่องกรอกข้อมูลสำหรับรหัสวิชา -->
                    </div>
                    <div class="form-group">
                        <!-- กลุ่มฟอร์มสำหรับชื่อวิชา -->
                        <label for="name">ชื่อวิชา</label>
                        <!-- ป้ายชื่อสำหรับชื่อวิชา -->
                        <input type="text" id="subject_name" name="subject_name">
                        <!-- ช่องกรอกข้อมูลสำหรับชื่อวิชา -->
                    </div>
                    <div class="form-group">
                        <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                        <label for="level">ระดับชั้น</label>
                        <!-- ป้ายชื่อสำหรับระดับชั้น -->
                        <select id="level" name="level">
                            <!-- รายการเลือกสำหรับระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option>
                            <!-- ตัวเลือกเริ่มต้น -->
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- กลุ่มฟอร์มสำหรับกลุ่มวิชา -->
                        <label for="subject_group">กลุ่มวิชา</label>
                        <!-- ป้ายชื่อสำหรับกลุ่มวิชา -->
                        <select id="subject_group" name="subject_group">
                            <!-- รายการเลือกสำหรับกลุ่มวิชา -->
                            <option value="">เลือกกลุ่มวิชา</option>
                            <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                    <!-- แสดงชื่อกลุ่มวิชาและป้องกันการโจมตี XSS -->
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- กลุ่มฟอร์มสำหรับการอัปโหลดภาพปกหนังสือ -->
                        <label for="photo">ปกหนังสือ</label>
                        <!-- ป้ายชื่อสำหรับการอัปโหลดภาพ -->
                        <input type="file" id="imgInput" name="photo">
                        <!-- ช่องอัปโหลดไฟล์ -->
                        <img id="previewImg">
                        <!-- ช่องแสดงภาพพรีวิว -->
                    </div>
                    <div class="btn-con">
                        <!-- กลุ่มของปุ่ม -->
                        <div class="btn-submit">
                            <!-- ปุ่มสำหรับการบันทึกข้อมูล -->
                            <button type="submit" name="add_subject">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                            <!-- ปุ่มสำหรับการออก -->
                            <button type="button" onclick="history.back()">ออก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script>
    <!-- เชื่อมโยงไฟล์ JavaScript สำหรับการพรีวิวภาพ -->
</body>

</html>