<?php
session_start();
require 'config/db.php';

if (isset($_POST['signupteacher'])) {
    $t_code = $_POST['t_code'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $subject_group = $_POST['subject_group'];
    $photo = $_FILES['photo'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $urole = 'user';

    if (empty($t_code)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสประจำตัว';
        header("location: add-teacher.php");
        exit();
    } else if (empty($fullname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: add-teacher.php");
        exit();
    } else if (empty($phone)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์';
        header("location: add-teacher.php");
        exit();
    } else if (empty($subject_group)) {
        $_SESSION['error'] = 'กรุณาเลือกกลุ่มวิชา';
        header("location: add-teacher.php");
        exit();
    } else if (empty($photo['name'])) {
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป';
        header("location: add-teacher.php");
        exit();
    } else if (empty($username)) {
        $_SESSION['error'] = 'กรุณากรอก Username';
        header("location: add-teacher.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอก Password';
        header("location: add-teacher.php");
        exit();
    } else if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'กรุณากรอกรหัสให้มีความยาว 5-20 ตัวอักษร';
        header("location: add-teacher.php");
        exit();
    } else {
        try {
            $chk_username = $pdo->prepare("SELECT username FROM teacher WHERE username = :username");
            $chk_username->bindParam(":username", $username);
            $chk_username->execute();
            $row = $chk_username->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION['warning'] = 'มี Username นี้อยู่ในระบบแล้ว';
                header("location: add-teacher.php");
                exit();
            } else {
                $allow = array('jpg', 'jpeg', 'png');
                $extention = explode(".", $photo['name']);
                $fileActExt = strtolower(end($extention));
                $fileNew = rand() . "." . $fileActExt;
                $filePath = "uploads/" . $fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($photo['size'] > 0 && $photo['error'] == 0) {
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("INSERT INTO teacher(t_code, fullname, phone, subject_group, photo, username, password, urole)
                                        VALUES(:t_code, :fullname, :phone, :subject_group, :photo, :username, :password, :urole)");
                            $stmt->bindParam(":t_code", $t_code);
                            $stmt->bindParam(":fullname", $fullname);
                            $stmt->bindParam(":phone", $phone);
                            $stmt->bindParam(":subject_group", $subject_group);
                            $stmt->bindParam(":photo", $fileNew);
                            $stmt->bindParam(":username", $username);
                            $stmt->bindParam(":password", $passwordHash);
                            $stmt->bindParam(":urole", $urole);
                            $stmt->execute();
                            $_SESSION['success'] = 'สมัครเรียบร้อยแล้ว';
                            header("location: add-teacher.php");
                            exit();
                        } else {
                            $_SESSION['error'] = 'การอัพโหลดรูปภาพล้มเหลว';
                            header("location: add-teacher.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = 'ไฟล์รูปภาพไม่ถูกต้อง';
                        header("location: add-teacher.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง';
                    header("location: add-teacher.php");
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>