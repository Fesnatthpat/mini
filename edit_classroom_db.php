<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $room_id = $_POST['room_id'];
    $room_no = $_POST['room_no'];
    $building = $_POST['building'];
    $floot = $_POST['floot'];
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

    $sql = $pdo->prepare("UPDATE room SET room_no = :room_no, building = :building, photo = :photo WHERE room_id = :room_id");
    $sql->bindParam(":room_no", $room_no);
    $sql->bindParam(":building", $building);
    $sql->bindParam(":photo", $fileNew);
    $sql->bindParam(":room_id", $room_id); // Add this line to bind t_id
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: data-classroom.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: edit_classroom.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
    header("location: edit_classroom.php");
    exit();
}
