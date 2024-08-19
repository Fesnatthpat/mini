<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM building"); 
    $stmt->execute();//ประมวลผลคำสั่ง SQL
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);//ดึงข้อมูลทั้งหมดจากผลลัพธ์ของคำสั่ง SQL และจัดเก็บในรูปแบบของอาร์เรย์
} catch (PDOException $e) { //จะจับข้อผิดพลาดที่เกิดขึ้นระหว่างการเชื่อมต่อหรือการทำงานกับฐานข้อมูล
    echo "Error: " . $e->getMessage();
}

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลอาคารเรียน</title>
    <link rel="stylesheet" href="data-subject_group.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลอาคารเรียน</h1>
                </div>
                <form action="add_building_db.php" method="POST" class="search-form">
                    <div class="form-group">
                        <label for="name_building">ชื่ออาคารเรียน</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                        <input type="text" id="name_building" name="name_building">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="add_building">บันทึกข้อมูล</button>
                    </div>
                </form>
                <div class="btn-con">
                    <!-- <button class="add-student-button" onclick="window.location.href='add-building.php'">+ เพิ่มอาคารเรีบน</button> -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                </div>
                <div class="group-form1">
                    <div class="group-form2">
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert-danger">
                                <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert-success">
                                <?php
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                            </div>
                        <?php } ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ชื่ออาคาร</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($buildings as $building) { // เปลี่ยนเป็น $buildings 
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($building['building_name']); // ใช้ building_name 
                                            ?></td>
                                        <td>
                                            <a href="edit_building.php?building_id=<?= htmlspecialchars($building['building_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                            <a href="delete_building_db.php?delete=<?= htmlspecialchars($building['building_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
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