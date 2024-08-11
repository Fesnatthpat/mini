<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $t_id = $_POST['t_id'];
    $t_code = $_POST['t_code'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $subject_group = $_POST['subject_group'];
    $photo = $_FILES['photo'];

    $photo2 = $_POST['photo2'];
    $upload = $_FILES['photo']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extention = explode(".", $photo['name']);
        $fileActExt = strtolower(end($extention));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "uploads/" . $fileNew;

        if (in_array($fileActExt, $allow)) {
            if ($photo['size'] > 0 && $photo['error'] == 0) {
                move_uploaded_file($photo['tmp_name'], $filePath);
            }
        }
    } else {
        $fileNew = $photo2;
    }

    $sql = $pdo->prepare("UPDATE teacher SET fullname = :fullname, phone = :phone, subject_group = :subject_group, photo = :photo WHERE t_id = :t_id");
    $sql->bindParam(":fullname", $fullname);
    $sql->bindParam(":phone", $phone);
    $sql->bindParam(":subject_group", $subject_group);
    $sql->bindParam(":photo", $fileNew);
    $sql->bindParam(":t_id", $t_id); // Add this line to bind t_id
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: teacher.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: edit_teacher.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("location: edit_teacher.php");
    exit();
}
