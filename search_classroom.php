<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ดึงข้อมูลอาคารจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT DISTINCT building_name FROM building");
$stmt->execute();
$buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบค่าการค้นหาและสร้างคำสั่ง SQL
$searchroomno = isset($_POST['searchroom_no']) ? '%' . $_POST['searchroom_no'] . '%' : '%';
$searchbuilding = $_POST['search_building'] ?? '';
$searchfloot = $_POST['search_floot'] ?? '';
$sql = "SELECT * FROM room WHERE room_no LIKE :searchroom_no";

if (!empty($searchbuilding)) {
    $sql .= " AND building = :searchbuilding";
}

if (!empty($searchfloot)) {
    $sql .= " AND floot = :search_floot";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':searchroom_no', $searchroomno, PDO::PARAM_STR);

if (!empty($searchbuilding)) {
    $stmt->bindParam(':search_building', $searchbuilding, PDO::PARAM_STR);
}

if (!empty($searchfloot)) {
    $stmt->bindParam(':search_floot', $searchfloot, PDO::PARAM_INT);  // ใช้ PARAM_STR ถ้าคอลัมน์เป็นชนิด VARCHAR
}

$stmt->execute();
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลห้องเรียน</title>
    <link rel="stylesheet" href="data-classroom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลห้องเรียน</h1>
                </div>
                <form action="search_classroom.php" method="POST" class="search-form">
                    <div class="form-group">
                        <label for="search-name">หมายเลขห้อง</label>
                        <input type="text" name="searchroom_no">
                    </div>
                    <div class="form-group">
                        <label for="search-level">อาคาร</label>
                        <select id="building" name="search_building">
                            <option value="">เลือกอาคาร</option>
                            <?php foreach ($buildingData as $building) { ?>
                                <option value="<?php echo htmlspecialchars($building['building_name']); ?>">
                                    <?php echo htmlspecialchars($building['building_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-level">ชั้น</label>
                        <select name="search_floot">
                            <option value="">เลือกชั้น</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-classroom.php'">+ เพิ่มห้องเรียน</button>
                    <button class="out-student-button" onclick="window.location.href='data-classroom.php'">ออก</button>
                </div>
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
                <?php if (isset($_SESSION['warning'])) { ?>
                    <div class="alert-warning">
                        <?php
                        echo $_SESSION['warning'];
                        unset($_SESSION['warning']);
                        ?>
                    </div>
                <?php } ?>
                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>หมายเลขห้อง</th>
                                    <th>รูปถ่ายห้องเรียน</th>
                                    <th>อาคารเรียน</th>
                                    <th>ชั้น</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM room");
                                $stmt->execute();
                                $roomData = $stmt->fetchAll();

                                if (!$roomData) {
                                    echo "ไม่มีข้อมูล";
                                } else {
                                    foreach ($roomData as $rooms) {



                                ?>
                                        <tr>
                                            <td><?= $rooms['room_no'] ?></td>
                                            <td><img src="uploads_classroom/<?= $rooms['photo'] ?>" alt="รูปถ่าย"></td>
                                            <td><?= $rooms['building'] ?></td>
                                            <td><?= $rooms['floot'] ?></td>
                                            <td>
                                                <a href="edit_classroom.php?room_id=<?= htmlspecialchars($rooms['room_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_classroom_db.php?delete=<?= htmlspecialchars($rooms['room_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
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