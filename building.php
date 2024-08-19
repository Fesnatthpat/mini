<?php
session_start(); 
/* เริ่มต้นหรือเรียกใช้เซสชันเพื่อจัดการข้อมูลการเข้าสู่ระบบและข้อมูลอื่นๆ ที่เกี่ยวข้องกับผู้ใช้ */

require_once 'config/db.php'; 
/* เชื่อมต่อกับไฟล์ที่มีการตั้งค่าการเชื่อมต่อฐานข้อมูล */

try {
    $stmt = $pdo->prepare("SELECT * FROM building"); 
    /* เตรียมคำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง building */
    $stmt->execute(); 
    /* รันคำสั่ง SQL ที่เตรียมไว้ */
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    /* รับข้อมูลทั้งหมดจากคำสั่ง SQL ในรูปแบบของอาร์เรย์ที่มีแต่ค่าแอสโซซิเอทิฟ */
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); 
    /* แสดงข้อความข้อผิดพลาดหากเกิดข้อผิดพลาดในการทำงานกับฐานข้อมูล */
}

if (!isset($_SESSION['admin_login'])) { 
    /* ตรวจสอบว่าไม่มีการเข้าสู่ระบบด้วยสิทธิ์ผู้ดูแลระบบ */
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; 
    /* กำหนดข้อความข้อผิดพลาดในเซสชัน */
    header("location: index.php"); 
    /* เปลี่ยนเส้นทางไปยังหน้า index.php */
    exit(); 
    /* หยุดการทำงานของสคริปต์ PHP ต่อไป */
}
?>

<!DOCTYPE html> 
<!-- ประกาศเอกสาร HTML -->
<html lang="th"> 
<!-- ระบุภาษาของเอกสารเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> 
    <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- ตั้งค่า viewport ให้ปรับขนาดตามขนาดของหน้าจออุปกรณ์ -->
    <title>ข้อมูลอาคารเรียน</title> 
    <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="data-subject_group.css"> 
    <!-- เชื่อมต่อกับไฟล์ CSS สำหรับการออกแบบหน้าเว็บ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- เชื่อมต่อกับ Font Awesome สำหรับไอคอนต่างๆ -->
</head>

<body>

    <div class="container"> 
    <!-- กล่องคอนเทนเนอร์หลัก -->
        <div class="box-1"> 
        <!-- กล่องที่ใช้สำหรับการจัดรูปแบบ -->
            <div class="box-2"> 
            <!-- กล่องที่ใช้สำหรับการจัดรูปแบบเพิ่มเติม -->
                <div class="text-1"> 
                <!-- กล่องที่ใช้สำหรับข้อความ -->
                    <h1>ข้อมูลอาคารเรียน</h1> 
                    <!-- หัวข้อหลักของหน้าเว็บ -->
                </div>
                <form action="add_building_db.php" method="POST" class="search-form">
                <!-- ฟอร์มสำหรับเพิ่มข้อมูลอาคารเรียน -->
                    <div class="form-group"> 
                    <!-- กลุ่มฟอร์มสำหรับจัดกลุ่มช่องกรอกข้อมูล -->
                        <label for="name_building">ชื่ออาคารเรียน</label> 
                        <!-- ป้ายชื่อสำหรับช่องกรอกข้อมูล -->
                        <input type="text" id="name_building" name="name_building"> 
                        <!-- ช่องกรอกข้อมูลสำหรับชื่ออาคารเรียน -->
                    </div>

                    <div class="form-group"> 
                    <!-- กลุ่มฟอร์มสำหรับปุ่มบันทึกข้อมูล -->
                        <button type="submit" name="add_building">บันทึกข้อมูล</button> 
                        <!-- ปุ่มส่งข้อมูลฟอร์ม -->
                    </div>
                </form>
                <div class="btn-con">
                <!-- กลุ่มปุ่มสำหรับการดำเนินการเพิ่มเติม -->
                    <!-- <button class="add-student-button" onclick="window.location.href='add-building.php'">+ เพิ่มอาคารเรีบน</button> -->
                    <!-- ปุ่มที่ถูกคอมเมนต์ออก -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                    <!-- ปุ่มสำหรับออกจากหน้าเว็บและกลับไปที่หน้า home.php -->
                </div>
                <div class="group-form1">
                <!-- กล่องที่ใช้สำหรับการจัดรูปแบบตาราง -->
                    <div class="group-form2">
                    <!-- กล่องที่ใช้สำหรับการจัดรูปแบบตารางเพิ่มเติม -->
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert-danger">
                            <!-- แสดงข้อผิดพลาด -->
                                <?php
                                echo $_SESSION['error']; 
                                /* แสดงข้อความข้อผิดพลาด */
                                unset($_SESSION['error']); 
                                /* ลบข้อความข้อผิดพลาดจากเซสชันหลังจากแสดงแล้ว */
                                ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert-success">
                            <!-- แสดงข้อความสำเร็จ -->
                                <?php
                                echo $_SESSION['success']; 
                                /* แสดงข้อความสำเร็จ */
                                unset($_SESSION['success']); 
                                /* ลบข้อความสำเร็จจากเซสชันหลังจากแสดงแล้ว */
                                ?>
                            </div>
                        <?php } ?>
                        <table>
                        <!-- สร้างตารางเพื่อแสดงข้อมูล -->
                            <thead>
                            <!-- ส่วนหัวของตาราง -->
                                <tr>
                                    <th>ชื่ออาคาร</th> 
                                    <!-- หัวข้อคอลัมน์ชื่ออาคาร -->
                                    <th>การจัดการ</th> 
                                    <!-- หัวข้อคอลัมน์การจัดการ -->
                                </tr>
                            </thead>
                            <tbody>
                            <!-- เนื้อหาของตาราง -->
                                <?php foreach ($buildings as $building) { // เปลี่ยนเป็น $buildings 
                                ?>
                                    <tr>
                                    <!-- แถวของข้อมูลในตาราง -->
                                        <td><?php echo htmlspecialchars($building['building_name']); // ใช้ building_name 
                                            ?></td>
                                            <!-- ข้อมูลชื่ออาคารที่ดึงจากฐานข้อมูล -->
                                        <td>
                                            <a href="delete_building_db.php?delete=<?= htmlspecialchars($building['building_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');">
                                            <!-- ลิงก์สำหรับลบข้อมูลอาคาร โดยมีการยืนยันการลบ -->
                                                <i class="fa-solid fa-trash"></i></a>
                                                <!-- ไอคอนถังขยะจาก Font Awesome -->
                                        </td>
                                    </tr>
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
