<?php
session_start(); // เริ่มต้นการทำงานของเซสชัน
require_once 'config/db.php'; // รวมไฟล์การเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; // เก็บข้อความข้อผิดพลาดในเซสชัน
    header("location: index.php"); // เปลี่ยนเส้นทางไปที่หน้า index.php
    exit(); // หยุดการทำงานของสคริปต์
}

// ดึงข้อมูลอาคารจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM building"); // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลอาคาร
$stmt->execute(); // รันคำสั่ง SQL
$buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดเป็น associative array

// รับค่าที่ส่งมาจากฟอร์ม
$searchRoomNo = isset($_POST['searchroom_no']) ? $_POST['searchroom_no'] : ''; // รับหมายเลขห้องจากฟอร์ม
$searchBuilding = isset($_POST['search_building']) ? $_POST['search_building'] : ''; // รับอาคารจากฟอร์ม
$searchFloot = isset($_POST['search_floot']) ? $_POST['search_floot'] : ''; // รับชั้นจากฟอร์ม

// สร้าง SQL Query พร้อมเงื่อนไขการค้นหา
$sql = "SELECT * FROM room WHERE 1=1"; // สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องทั้งหมด
$params = []; // อาร์เรย์สำหรับเก็บพารามิเตอร์ค้นหา

if (!empty($searchRoomNo)) {
    $sql .= " AND room_no LIKE :room_no"; // เพิ่มเงื่อนไขค้นหาหมายเลขห้อง
    $params['room_no'] = "%$searchRoomNo%"; // กำหนดค่าพารามิเตอร์ค้นหาหมายเลขห้อง
}

if (!empty($searchBuilding)) {
    $sql .= " AND building = :building"; // เพิ่มเงื่อนไขค้นหาอาคาร
    $params['building'] = $searchBuilding; // กำหนดค่าพารามิเตอร์ค้นหาอาคาร
}

if (!empty($searchFloot)) {
    $sql .= " AND floot = :floot"; // เพิ่มเงื่อนไขค้นหาชั้น
    $params['floot'] = $searchFloot; // กำหนดค่าพารามิเตอร์ค้นหาชั้น
}

$stmt = $pdo->prepare($sql); // เตรียมคำสั่ง SQL สำหรับการ execute
$stmt->execute($params); // รันคำสั่ง SQL พร้อมพารามิเตอร์ค้นหา
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดเป็น associative array
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- รองรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>ข้อมูลห้องเรียน</title> <!-- ตั้งชื่อหัวข้อของหน้าเว็บ -->
    <link rel="stylesheet" href="data-classroom.css"> <!-- รวมไฟล์ CSS สำหรับสไตล์ของหน้า -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- รวมไอคอน Font Awesome -->
</head>

<body>

    <div class="container"> <!-- กล่องหลักของหน้า -->
        <div class="box-1"> <!-- กล่องย่อยที่ 1 -->
            <div class="box-2"> <!-- กล่องย่อยที่ 2 -->
                <div class="text-1"> <!-- กล่องสำหรับข้อความหัวเรื่อง -->
                    <h1>ข้อมูลห้องเรียน</h1> <!-- หัวข้อหลักของหน้า -->
                </div>
                <form action="" method="POST" class="search-form"> <!-- ฟอร์มสำหรับค้นหาข้อมูลห้องเรียน -->
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="searchroom_no">หมายเลขห้อง</label> <!-- ป้ายกำกับสำหรับช่องหมายเลขห้อง -->
                        <input type="text" name="searchroom_no" value="<?= htmlspecialchars($searchRoomNo) ?>"> <!-- ช่องป้อนข้อมูลหมายเลขห้อง -->
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_building">อาคาร</label> <!-- ป้ายกำกับสำหรับช่องอาคาร -->
                        <select name="search_building"> <!-- ช่องเลือกระดับชั้น -->
                            <option value="">เลือกอาคาร</option> <!-- ตัวเลือกเริ่มต้น -->
                            <?php foreach ($buildingData as $building) { ?> <!-- ลูปเพื่อแสดงตัวเลือกอาคาร -->
                                <option value="<?php echo htmlspecialchars($building['building_name']); ?>" <?= $searchBuilding == $building['building_name'] ? 'selected' : '' ?>>
                                    <?php echo htmlspecialchars($building['building_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการป้อนข้อมูล -->
                        <label for="search_floot">ชั้น</label> <!-- ป้ายกำกับสำหรับช่องชั้น -->
                        <select name="search_floot"> <!-- ช่องเลือกระดับชั้น -->
                            <option value="">เลือกชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="1" <?= $searchFloot == "1" ? 'selected' : '' ?>>1</option> <!-- ตัวเลือกระดับชั้น 1 -->
                            <option value="2" <?= $searchFloot == "2" ? 'selected' : '' ?>>2</option> <!-- ตัวเลือกระดับชั้น 2 -->
                            <option value="3" <?= $searchFloot == "3" ? 'selected' : '' ?>>3</option> <!-- ตัวเลือกระดับชั้น 3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับปุ่มค้นหา -->
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button> <!-- ปุ่มค้นหาพร้อมไอคอน -->
                    </div>
                </form>

                <div class="btn-con"> <!-- กล่องสำหรับปุ่มเพิ่มเติม -->
                    <button type="button" class="add-student-button" onclick="window.location.href='add-classroom.php'">+ เพิ่มห้องเรียน</button> <!-- ปุ่มเพิ่มห้องเรียน -->
                    <button type="button" class="out-student-button" onclick="window.location.href='home.php'">ออก</button> <!-- ปุ่มออก -->
                </div>

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

                <div class="group-form1"> <!-- กล่องสำหรับข้อมูลตาราง -->
                    <div class="group-form2"> <!-- กล่องย่อยสำหรับข้อมูลตาราง -->
                        <table> <!-- เริ่มต้นตาราง -->
                            <thead> <!-- ส่วนหัวของตาราง -->
                                <tr> <!-- แถวหัวของตาราง -->
                                    <th>หมายเลขห้อง</th> <!-- หัวข้อของคอลัมน์หมายเลขห้อง -->
                                    <th>รูปถ่ายห้องเรียน</th> <!-- หัวข้อของคอลัมน์รูปถ่ายห้องเรียน -->
                                    <th>อาคารเรียน</th> <!-- หัวข้อของคอลัมน์อาคารเรียน -->
                                    <th>ชั้น</th> <!-- หัวข้อของคอลัมน์ชั้น -->
                                    <th>การจัดการ</th> <!-- หัวข้อของคอลัมน์การจัดการ -->
                                </tr>
                            </thead>
                            <tbody> <!-- ส่วนเนื้อหาของตาราง -->
                                <?php if (empty($roomData)) { ?> <!-- ตรวจสอบว่ามีข้อมูลห้องเรียนหรือไม่ -->
                                    <tr>
                                        <td colspan="5">ไม่พบข้อมูล</td> <!-- แสดงข้อความถ้าไม่มีข้อมูล -->
                                    </tr>
                                <?php } else { ?> <!-- ถ้ามีข้อมูลห้องเรียน -->
                                    <?php foreach ($roomData as $rooms) { ?> <!-- ลูปเพื่อแสดงข้อมูลห้องเรียน -->
                                        <tr>
                                            <td><?= htmlspecialchars($rooms['room_no']) ?></td> <!-- แสดงหมายเลขห้อง -->
                                            <td><img width="40px" src="uploads_classroom/<?= htmlspecialchars($rooms['photo']) ?>" alt="รูปถ่าย"></td> <!-- แสดงรูปถ่ายห้องเรียน -->
                                            <td><?= htmlspecialchars($rooms['building']) ?></td> <!-- แสดงอาคารเรียน -->
                                            <td><?= htmlspecialchars($rooms['floot']) ?></td> <!-- แสดงชั้น -->
                                            <td> <!-- คอลัมน์การจัดการ -->
                                                <a href="edit_classroom.php?room_id=<?= htmlspecialchars($rooms['room_id']); ?>"><i class="fa-solid fa-pen"></i></a> <!-- ลิงค์สำหรับแก้ไขข้อมูลห้องเรียน -->
                                                |
                                                <a href="delete_classroom_db.php?delete=<?= htmlspecialchars($rooms['room_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a> <!-- ลิงค์สำหรับลบข้อมูลห้องเรียน -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table> <!-- จบตาราง -->
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>