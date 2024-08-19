<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) { //ตรวจสอบว่าเซสชันที่ชื่อ admin_login ถูกตั้งค่าหรือไม่ ถ้าไม่ระบบจะทำตามคำสั่งภายใน
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php"); //กลับไปยังหน้า index
    exit; //จบการทำงาน เพื่อไม่ให้ทำคำสั่งอื่นซ้ำ
}

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group"); //เตรียมคำสั่ง SQL เพื่อเลือกชื่อกลุ่มวิชา (subj_group_name) จากตาราง subject_group
    $stmt->execute(); //ประมวลผลคำสั่ง SQL
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC); //ดึงข้อมูลทั้งหมดจากผลลัพธ์ของคำสั่ง SQL และจัดเก็บในรูปแบบของอาร์เรย์ที่มีคีย์เป็นชื่อคอลัมน์ (FETCH_ASSOC).
} catch (PDOException $e) { //จะจับข้อผิดพลาดที่เกิดขึ้นระหว่างการเชื่อมต่อหรือการทำงานกับฐานข้อมูล
    echo "Error: " . $e->getMessage();
}




?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลนักเรียน</title>
    <link rel="stylesheet" href="add-subject.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">ข้อมูลรายวิชา</h1>
                <hr>
                <form action="add_subject_db.php" method="POST" enctype="multipart/form-data"> <!--สร้างแบบฟอร์ม ส่งข้อมูลไปยัง add_subject_db.php โดยส่งแบบ POST-->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-danger"> <!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['error']; // แสดงข้อความในเซสชันที่มีคีย์ 'error'
                            unset($_SESSION['error']); // ลบข้อมูลที่มีคีย์ 'error' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['success'])) { ?><!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-success"><!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['success']; // แสดงข้อความในเซสชันที่มีคีย์ 'success'
                            unset($_SESSION['success']); // ลบข้อมูลที่มีคีย์ 'success' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['warning'])) { ?><!-- ตรวจสอบว่ามีข้อผิดพลาดในเซสชันหรือไม่ -->
                        <div class="alert-warning"><!-- กล่องแสดงข้อผิดพลาด เมื่อเกิดข้อผิดพลาด -->
                            <?php
                            echo $_SESSION['warning']; // แสดงข้อความในเซสชันที่มีคีย์ 'warning'
                            unset($_SESSION['warning']); // ลบข้อมูลที่มีคีย์ 'warning' ออกจากเซสชัน
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="student-id">รหัสวิชา</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" id="subject_code" name="subject_code">
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อวิชา</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" id="subject_name" name="subject_name">
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <select id="level" name="level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">กลุ่มวิชา</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <select id="subject_group" name="subject_group">
                            <option value="">เลือกกลุ่มวิชา</option> 
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">ปกหนังสือ</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="file" id="imgInput" name="photo">
                        <img id="previewImg"> <!--โชว์ตัวอย่างรูป-->
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="add_subject">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                        <button type="button" onclick="history.back()">ออก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="preview_img.js"></script>
</body>

</html>