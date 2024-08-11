<?php
session_start();
require_once 'config/db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM subject_group");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>ข้อมูลกลุ่มวิชา</title>
    <link rel="stylesheet" href="data-subject_group.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลกลุ่มวิชา</h1>
                </div>
                <form action="add_subject_group_db.php" method="POST" class="search-form">
                    <div class="form-group">
                        <label for="search-name">ชื่อวิชา</label>
                        <input type="text" id="search-name" name="subject_group_name">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="add_subject">บันทึกข้อมูล</button>
                    </div>
                </form>
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert-success" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php } ?>
                <div class="btn-con">
                    <!-- <button class="add-student-button" onclick="window.location.href='add-subject_group.php'">+ กลุ่มวิชา</button> -->
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                </div>

                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>กลุ่มวิชา</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subject['subj_group_name']); ?></td>
                                        <td>
                                            <a href="#">Edit</a> |
                                            <a href="#">Delete</a>
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