<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM building");
    $stmt->execute();
    $buildingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
    <title>เพิ่มห้องเรียน</title>
    <link rel="stylesheet" href="add-subject.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">เพิ่มห้องเรียน</h1>
                <hr>
                <form action="add_classroom_db.php" method="POST" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="student-id">หมายเลขห้อง</label>
                        <input type="text" id="room_no" name="room_no">
                    </div>
                    <div class="form-group">
                        <label for="building">อาคารเรียน</label>
                        <select id="building" name="building">
                            <option value="">เลือกอาคารเรียน</option>
                            <?php foreach ($buildingData as $building) { ?>
                                <option value="<?php echo htmlspecialchars($building['building_name']); ?>">
                                    <?php echo htmlspecialchars($building['building_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floot">ชั้น</label>
                        <select id="floot" name="floot">
                            <option value="">เลือกชั้น</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปห้องเรียน</label>
                        <input type="file" id="photo" name="photo">
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="add_classroom">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                        <button type="button" onclick="history.back()">ออก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>