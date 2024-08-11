<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
    $stmt->execute();
    $subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
                <form action="add_subject_db.php" method="POST" enctype="multipart/form-data">
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
                        <label for="student-id">รหัสวิชา</label>
                        <input type="text" id="subject_code" name="subject_code">
                    </div>
                    <div class="form-group">
                        <label for="name">ชื่อวิชา</label>
                        <input type="text" id="subject_name" name="subject_name">
                    </div>
                    <div class="form-group">
                        <label for="level">ระดับชั้น</label>
                        <select id="level" name="level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">กลุ่มวิชา</label>
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
                        <label for="photo">ปกหนังสือ</label>
                        <input type="file" id="photo" name="photo" accept="image/gif, image/jpeg, image/png">
                    </div>
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

</body>

</html>