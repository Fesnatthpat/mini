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

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];
    $stmt = $pdo->prepare("SELECT * FROM subject WHERE subject_id = ?");
    $stmt->execute([$subject_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรายวิชา</title>
    <link rel="stylesheet" href="edit_subject.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">แก้ไขรายวิชา</h1>
                <hr>
                <form action="edit_subject_db.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" value="<?= htmlspecialchars($data['subject_id']); ?>" name="subject_id">
                        <label for="subject_code">รหัสวิชา</label>
                        <input type="text" value="<?= htmlspecialchars($data['subject_code']); ?>" name="subject_code">
                        <input type="hidden" value="<?= htmlspecialchars($data['photo']); ?>" name="photo2">
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อวิชา</label>
                        <input type="text" value="<?= htmlspecialchars($data['subject_name']); ?>" name="subject_name">
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
                        <label for="subject_group">กลุ่มวิชา</label>
                        <select name="subject_group">
                        <option><?= htmlspecialchars($data['subject_group']); ?></option>
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?= htmlspecialchars($group['subj_group_name']); ?>">
                                    <?= htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">ปกหนังสือ</label>
                        <input type="file" id="imgInput" name="photo">
                        <img id="previewImg" src="uploads_subject2/<?= htmlspecialchars($data['photo']); ?>" alt="">
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