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

if (isset($_GET['subj_group_id'])) {
    $subj_group_id = $_GET['subj_group_id'];
    $stmt = $pdo->prepare("SELECT * FROM subject_group WHERE subj_group_id = ?");
    $stmt->execute([$subj_group_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขกลุ่มวิชา</title>
    <link rel="stylesheet" href="edit_subject_group.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<div class="form-container">
    <div class="box1">
        <div class="box2">
            <h1 class="text-edit">แก้ไขกลุ่มวิชา</h1>
            <hr>
            <form action="edit_subject_group_db.php" method="POST">
                <div class="form-group">
                    <label for="subject_group">กลุ่มวิชา</label>
                    <input type="hidden" value="<?= htmlspecialchars($data['subj_group_id']); ?>" name="subj_group_id">
                    <input type="text" value="<?= htmlspecialchars($data['subj_group_name']); ?>" name="subj_group_name">
                </div>
                <div class="btn-con">
                    <div class="btn-submit">
                        <button type="submit" name="update" >บันทึกข้อมูล</button>
                    </div>
                    <div class="btn-out">
                        <button type="button" onclick="history.back()">ออก</button>
                    </div>
                </div>
            </form>

            <body>