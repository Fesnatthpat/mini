<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $s_id = $_POST['s_id'];
    $s_code = $_POST['s_code'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $photo = $_FILES['photo'];

    $photo2 = $_POST['photo2'];
    $upload = $_FILES['photo']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extention = explode(".", $photo['name']);
        $fileActExt = strtolower(end($extention));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "uploads_student/" . $fileNew;

        if (in_array($fileActExt, $allow)) {
            if ($photo['size'] > 0 && $photo['error'] == 0) {
                move_uploaded_file($photo['tmp_name'], $filePath);
            }
        }
    } else {
        $fileNew = $photo2;
    }

    $sql = $pdo->prepare("UPDATE student SET fullname = :fullname, phone = :phone, photo = :photo WHERE s_id = :s_id");
    $sql->bindParam(":fullname", $fullname);
    $sql->bindParam(":phone", $phone);
    $sql->bindParam(":photo", $fileNew);
    $sql->bindParam(":s_id", $s_id); // Add this line to bind t_id
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: data-student.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: data-student.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("location: data-student.php");
    exit();
}
