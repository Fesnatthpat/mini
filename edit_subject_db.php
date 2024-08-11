<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $subject_id = $_POST['subject_id'];
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $subject_group = $_POST['subject_group'];
    $photo = $_FILES['photo'];

    $photo2 = $_POST['photo2'];
    $upload = $_FILES['photo']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extention = explode(".", $photo['name']);
        $fileActExt = strtolower(end($extention));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "uploads_subject2/" . $fileNew;

        if (in_array($fileActExt, $allow)) {
            if ($photo['size'] > 0 && $photo['error'] == 0) {
                move_uploaded_file($photo['tmp_name'], $filePath);
            }
        }
    } else {
        $fileNew = $photo2;
    }

    $sql = $pdo->prepare("UPDATE subject SET subject_name = :subject_name, subject_group = :subject_group, photo = :photo WHERE subject_id = :subject_id");
    $sql->bindParam(":subject_name", $subject_name);
    $sql->bindParam(":subject_group", $subject_group);
    $sql->bindParam(":photo", $fileNew);
    $sql->bindParam(":subject_id", $subject_id); // Add this line to bind t_id
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: data-subject.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: data-subject.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("location: data-subject.php");
    exit();
}
