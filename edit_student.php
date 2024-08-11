<?php
session_start();
require_once 'config/db.php';

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data

if (isset($_GET['s_id'])) {
    $s_id = $_GET['s_id'];
    $stmt = $pdo->prepare("SELECT * FROM student WHERE s_id = ?");
    $stmt->execute([$s_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}



?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักเรียน</title>
    <link rel="stylesheet" href="edit_student.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">แก้ไขข้อมูลนักเรียน</h1>
                <hr>
                <form action="edit_student_db.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" value="<?= htmlspecialchars($data['s_id']); ?>" name="s_id">
                        <label for="s_code">รหัสประจำตัว</label>
                        <input type="text" value="<?= htmlspecialchars($data['s_code']); ?>" name="s_code">
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อ-นามสกุล</label>
                        <input type="text" value="<?= htmlspecialchars($data['fullname']); ?>" name="fullname">
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทร</label>
                        <input type="text" value="<?= htmlspecialchars($data['phone']); ?>" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <select id="level" name="level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="1">ม.1</option>
                            <option value="2">ม.2</option>
                            <option value="3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">รูปถ่าย</label>
                        <input type="file" id="imgInput" name="photo">
                        <img id="previewImg" src="uploads_student/<?= htmlspecialchars($data['photo']); ?>" alt="">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" value="<?= htmlspecialchars($data['username']); ?>" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" value="<?= htmlspecialchars($data['password']); ?>" name="password">
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