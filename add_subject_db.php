<?php
session_start();
require 'config/db.php';

if (isset($_POST['add_subject'])) {
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $level = $_POST['level'];
    $subject_group = $_POST['subject_group'];
    $photo = $_FILES['photo'];

    if (empty($subject_code)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา';
        header("location: add-subject.php");
        exit();
    } else if (empty($subject_name)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อวิชา';
        header("location: add-subject.php");
        exit();
    } else if (empty($level)) {
        $_SESSION['error'] = 'กรุณากรอกระดับชั้น';
        header("location: add-subject.php");
        exit();
    } else if (empty($subject_group)) {
        $_SESSION['error'] = 'กรุณาเลือกกลุ่มวิชา';
        header("location: add-subject.php");
        exit();
    } else if (empty($photo['name'])) {
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป';
        header("location: add-subject.php");
        exit();
    } else {
        try {
            $chk_subject2name = $pdo->prepare("SELECT subject_name FROM subject WHERE subject_name = :subject_name");
            $chk_subject2name->bindParam(":subject_name", $subject_name);
            $chk_subject2name->execute();
            $data_subject2 = $chk_subject2name->fetch(PDO::FETCH_ASSOC);

            if ($data_subject2) {
                $_SESSION['warning'] = 'มีชื่อวิชานี้อยู่ในระบบแล้ว';
                header("location: add-subject.php");
                exit();
            } else {
                $allow = array('jpg', 'jpeg', 'png');
                $extention = explode(".", $photo['name']);
                $fileActExt = strtolower(end($extention));
                $fileNew = rand() . "." . $fileActExt;
                $filePath = "uploads_subject2/" . $fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($photo['size'] > 0 && $photo['error'] == 0) {
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) {
                            $stmt = $pdo->prepare("INSERT INTO subject (subject_code, subject_name, level, subject_group, photo)
                                        VALUES(:subject_code, :subject_name, :level, :subject_group, :photo)");
                            $stmt->bindParam(":subject_code", $subject_code);
                            $stmt->bindParam(":subject_name", $subject_name);
                            $stmt->bindParam(":level", $level);
                            $stmt->bindParam(":subject_group", $subject_group);
                            $stmt->bindParam(":photo", $fileNew);
                            $stmt->execute();
                            $_SESSION['success'] = 'เพิ่มวิชาเรียบร้อยแล้ว';
                            header("location: add-subject.php");
                            exit();
                        } else {
                            $_SESSION['error'] = 'การอัพโหลดรูปภาพล้มเหลว';
                            header("location: add-subject.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = 'ไฟล์รูปภาพไม่ถูกต้อง';
                        header("location: add-subject.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
                    header("location: add-subject.php");
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
