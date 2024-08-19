<?php
session_start();
require_once 'config/db.php';

$stmt = $pdo->prepare("SELECT building_name FROM building");
$stmt->execute();
$buildingData = $stmt->fetchAll();

$data = null; // กำหนดค่าเริ่มต้นให้ตัวแปร $data

if (isset($_GET['building_id'])) {
    $building_id = $_GET['building_id'];
    $stmt = $pdo->prepare("SELECT * FROM building WHERE building_id = ?");
    $stmt->execute([$building_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขอาคารเรียน</title>
    <link rel="stylesheet" href="edit_subject_group.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<div class="form-container">
    <div class="box1">
        <div class="box2">
            <h1 class="text-edit">แก้ไขอาคารเรียน</h1>
            <hr>
            <form action="edit_building_db.php" method="POST">
                <div class="form-group">
                    <label for="building">เลือกอาคารเรียน</label> <!--สร้างป้ายข้อความที่ระบุถึงข้อมูลที่ผู้ใช้ควรกรอกในฟิลด์กรอกข้อมูล-->
                    <select >
                        <option><?= htmlspecialchars($data['building_name']); ?></option>
                        <?php foreach ($buildingData as $buildings) { ?>
                            <option value="<?= htmlspecialchars($buildings['building_name']); ?>">
                                <?= htmlspecialchars($buildings['building_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
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

            <body>


</html>