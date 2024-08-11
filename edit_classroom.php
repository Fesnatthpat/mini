<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT building FROM room");
    $stmt->execute();
    $dataroom = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $stmt = $pdo->prepare("SELECT * FROM room WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มห้องเรียน</title>
    <link rel="stylesheet" href="edit_classroom.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">เพิ่มห้องเรียน</h1>
                <hr>
                <form action="edit_classroom_db.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" value="<?= htmlspecialchars($data['room_id']); ?>" name="room_id">
                        <label for="room_no">หมายเลขห้อง</label>
                        <input type="text" value="<?= htmlspecialchars($data['room_no']); ?>" name="room_no">
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                    </div>
                    <div class="form-group">
                        <label for="building">อาคารเรียน</label>
                        <select name="building">
                            <option><?= htmlspecialchars($data['building']); ?></option>
                            <?php foreach ($dataroom as $rooms) { ?>
                                <option value="<?= htmlspecialchars($rooms['building']); ?>">
                                    <?= htmlspecialchars($rooms['building']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floot">ชั้น</label>
                        <select name="floot" >
                            <option><?= htmlspecialchars($data['floot']); ?></option>
                            <?php foreach ($dataroom as $rooms) { ?>
                                <option value="<?= htmlspecialchars($rooms['floot']); ?>">
                                    <?= htmlspecialchars($rooms['floot']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปห้องเรียน</label>
                        <input type="file" id="imgInput" name="photo">
                        <img id="previewImg" src="uploads_classroom/<?= htmlspecialchars($data['photo']); ?>" alt="">
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit" name="update">บันทึกข้อมูล</button>
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