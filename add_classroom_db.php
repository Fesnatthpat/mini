<?php
session_start();
require 'config/db.php';

if (isset($_POST['add_classroom'])) {
    $room_no = $_POST['room_no'];
    $building = $_POST['building'];
    $floot = $_POST['floot'];
    $photo = $_FILES['photo'];

    if (empty($room_no)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา';
        header("location: add-classroom.php");
        exit();
    } else if (empty($building)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อวิชา';
        header("location: add-classroom.php");
        exit();
    } else if (empty($floot)) {
        $_SESSION['error'] = 'กรุณากรอกระดับชั้น';
        header("location: add-classroom.php");
        exit();
    } else if (empty($photo['name'])) {
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป';
        header("location: add-classroom.php");
        exit();
    } else {
        try {
            $chk_roomname = $pdo->prepare("SELECT room_no FROM room WHERE room_no = :room_no");
            $chk_roomname->bindParam(":room_no", $room_no);
            $chk_roomname->execute();
            $roomData = $chk_roomname->fetch(PDO::FETCH_ASSOC);

            if ($roomData) {
                $_SESSION['warning'] = 'มีชื่อวิชานี้อยู่ในระบบแล้ว';
                header("location: add-classroom.php");
                exit();
            } else {
                $allow = array('jpg', 'jpeg', 'png');
                $extention = explode(".", $photo['name']);
                $fileActExt = strtolower(end($extention));
                $fileNew = rand() . "." . $fileActExt;
                $filePath = "uploads_classroom/" . $fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($photo['size'] > 0 && $photo['error'] == 0) {
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) {
                            $stmt = $pdo->prepare("INSERT INTO room (room_no, building, floot, photo)
                                        VALUES(:room_no, :building, :floot, :photo)");
                            $stmt->bindParam(":room_no", $room_no);
                            $stmt->bindParam(":building", $building);
                            $stmt->bindParam(":floot", $floot);
                            $stmt->bindParam(":photo", $fileNew);
                            $stmt->execute();
                            $_SESSION['success'] = 'เพิ่มวิชาเรียบร้อยแล้ว';
                            header("location: add-classroom.php");
                            exit();
                        } else {
                            $_SESSION['error'] = 'การอัพโหลดรูปภาพล้มเหลว';
                            header("location: add-classroom.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = 'ไฟล์รูปภาพไม่ถูกต้อง';
                        header("location: add-classroom.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
                    header("location: add-classroom.php");
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
