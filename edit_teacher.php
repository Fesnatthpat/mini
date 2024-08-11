<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    $stmt->execute();
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data

if (isset($_GET['t_id'])) {
    $t_id = $_GET['t_id'];
    $stmt = $pdo->prepare("SELECT * FROM teacher WHERE t_id = ?");
    $stmt->execute([$t_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขคุณครู</title>
    <link rel="stylesheet" href="edit_teacher.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">แก้ไขคุณครู</h1>
                <hr>
                <form action="edit_teacher_db.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" value="<?= htmlspecialchars($data['t_id']); ?>" name="t_id">
                        <label for="t_code">รหัสประจำตัว</label>
                        <input type="text" value="<?= htmlspecialchars($data['t_code']); ?>" name="t_code">
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                    </div>
                    <div class="form-group">
                        <label for="fullname">ชื่อ-นามสกุล</label>
                        <input type="text" value="<?= htmlspecialchars($data['fullname']); ?>" name="fullname">
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" value="<?= htmlspecialchars($data['phone']); ?>" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject_group">กลุ่มวิชาที่สอน</label>
                        <select id="subject_group" name="subject_group">
                            <option><?= htmlspecialchars($data['subject_group']); ?></option>
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>">
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label>
                        <input type="file" id="imgInput" name="photo">
                        <!-- <?= htmlspecialchars($data['photo']); ?> -->
                        <img id="previewImg" src="uploads/<?= htmlspecialchars($data['photo']); ?>" alt="">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" value="<?= htmlspecialchars($data['username']); ?>" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" value="<?= htmlspecialchars($data['password']); ?>" id="password" name="password">
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

</html>