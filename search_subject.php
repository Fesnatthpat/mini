<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit();
}

// ดึงกลุ่มรายวิชาจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute();
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบค่าการค้นหาและสร้างคำสั่ง SQL
$searchName = isset($_POST['search_name']) ? '%' . $_POST['search_name'] . '%' : '%';
$searchLevel = $_POST['search_level'] ?? '';
$searchGroup = $_POST['search_group'] ?? '';  // แก้ไขจาก $_POST['search_level'] เป็น $_POST['search_group']

$sql = "SELECT * FROM subject WHERE subject_name LIKE :searchName";

if (!empty($searchLevel)) {
    $sql .= " AND level = :searchLevel";
}

if (!empty($searchGroup)) {
    $sql .= " AND subject_group = :searchGroup";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':searchName', $searchName, PDO::PARAM_STR);

if (!empty($searchLevel)) {
    $stmt->bindParam(':searchLevel', $searchLevel, PDO::PARAM_STR);
}

if (!empty($searchGroup)) {
    $stmt->bindParam(':searchGroup', $searchGroup, PDO::PARAM_STR);  // ตรวจสอบว่าใช้ PARAM_INT ถ้าคอลัมน์เป็น INT
}

$stmt->execute();
$subjectData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลรายวิชา</title>
    <link rel="stylesheet" href="data-subject.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลรายวิชา</h1>
                </div>
                <form action="search_subject.php" method="POST" class="search-form">
                    <div class="form-group">
                        <label for="search_name">ชื่อวิชา</label>
                        <input type="text" name="search_name">
                    </div>
                    <div class="form-group">
                        <label for="search_level">ระดับชั้น</label>
                        <select name="search_level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="ม.1">ม.1</option>
                            <option value="ม.2">ม.2</option>
                            <option value="ม.3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search_group">กลุ่มวิชาที่สอน</label>
                        <select id="search_group" name="search_group">
                            <option value="">เลือกกลุ่มวิชา</option>
                            <?php foreach ($subjectGroups as $group): ?>
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <div class="btn-con">
                    <button type="button" class="add-student-button" onclick="window.location.href='add-subject.php'">+ เพิ่มวิชา</button>
                    <button type="button" class="out-student-button" onclick="window.location.href='data-subject.php'">ออก</button>
                </div>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-danger">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-success">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['warning'])): ?>
                    <div class="alert-warning">
                        <?php echo $_SESSION['warning'];
                        unset($_SESSION['warning']); ?>
                    </div>
                <?php endif; ?>
                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th>
                                    <th>ชื่อวิชา</th>
                                    <th>ปกหนังสือ</th>
                                    <th>กลุ่มรายวิชา</th>
                                    <th>ระดับชั้น</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$subjectData): ?>
                                    <tr>
                                        <td colspan="6">ไม่มีข้อมูล</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($subjectData as $subject): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                                            <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                                            <td><img width="40px" src="uploads_subject2/<?= htmlspecialchars($subject['photo']); ?>" alt="รูปถ่าย"></td>
                                            <td><?= htmlspecialchars($subject['subject_group']); ?></td>
                                            <td><?= htmlspecialchars($subject['level']); ?></td>
                                            <td>
                                                <a href="edit_subject.php?subject_id=<?= htmlspecialchars($subject['subject_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_subject_db.php?delete=<?= htmlspecialchars($subject['subject_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
