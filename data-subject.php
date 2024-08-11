<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    header("location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT subj_group_name FROM subject_group");
$stmt->execute();
$subjectGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลรายวิชา</title>
    <link rel="stylesheet" href="data-subject.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลรายวิชา</h1>
                </div>
                <div class="search-form">
                    <div class="form-group">
                        <label for="search-name">ชื่อวิชา</label>
                        <input type="text" id="search-name" name="search-name">
                    </div>
                    <div class="form-group">
                        <label for="search-level">ระดับชั้น</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="1">ม.1</option>
                            <option value="2">ม.2</option>
                            <option value="3">ม.3</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-level">กลุ่มวิชาที่สอน</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกกลุ่มวิชา</option>
                            <?php foreach ($subjectGroups as $group) { ?>
                                <option value="<?php echo htmlspecialchars($group['subj_group_name']); ?>">
                                    <?php echo htmlspecialchars($group['subj_group_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-subject.php'">+ เพิ่มวิชา</button>
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
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
                                    <th>รหัสวิชา</th>
                                    <th>ชื่อวิชา</th>
                                    <th>ปกหนังสือ</th>
                                    <th>กลุ่มรายวิชา</th>
                                    <th>ระดับชั้น</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM subject");
                                $stmt->execute();
                                $subjectData = $stmt->fetchAll();

                                if (!$subjectData) {
                                    echo "ไม่มีข้อมูล";
                                } else {
                                    foreach ($subjectData as $subject) {


                                ?>
                                        <tr>
                                            <td><?= $subject['subject_code'] ?></td>
                                            <td><?= $subject['subject_name'] ?></td>
                                            <td><img width="40px" src="uploads_subject2/<?= $subject['photo'] ?>" alt="รูปถ่าย"></td>
                                            <td><?= $subject['subject_group'] ?></td>
                                            <td><?= $subject['level'] ?></td>
                                            <td>
                                                <a href="edit_subject.php?subject_id=<?= htmlspecialchars($subject['subject_id']); ?>"><i class="fa-solid fa-pen"></i></a> |
                                                <a href="delete_subject_db.php?delete=<?= htmlspecialchars($subject['subject_id']); ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');"><i class="fa-solid fa-trash"></i></a>
                                            </td>
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