<?php
session_start();
require 'config/db.php';

if (isset($_POST['signupstudent'])) {
    $s_code = $_POST['s_code'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $level = $_POST['level'];
    $photo = $_FILES['photo'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $urole = 'student';

    if (empty($s_code)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสประจำตัว';
        header("location: add-student.php");
        exit();
    } else if (empty($fullname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: add-student.php");
        exit();
    } else if (empty($phone)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์';
        header("location: add-student.php");
        exit();
    } else if (empty($level)) {
        $_SESSION['error'] = 'กรุณาเลือกกลุ่มวิชา';
        header("location: add-student.php");
        exit();
    } else if (empty($photo['name'])) {
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป';
        header("location: add-student.php");
        exit();
    } else if (empty($username)) {
        $_SESSION['error'] = 'กรุณากรอก Username';
        header("location: add-student.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอก Password';
        header("location: add-student.php");
        exit();
    } else if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'กรุณากรอกรหัสให้มีความยาว 5-20 ตัวอักษร';
        header("location: add-student.php");
        exit();
    } else {
        try {
            $chk_username = $pdo->prepare("SELECT username FROM student WHERE username = :username");
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
                $filePath = "uploads_student/" . $fileNew;

                if (in_array($fileActExt, $allow)) {
                    if ($photo['size'] > 0 && $photo['error'] == 0) {
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("INSERT INTO student (s_code, fullname, phone, level, photo, username, password, urole)
                                                    VALUES(:s_code, :fullname, :phone, :level, :photo, :username, :password, :urole)");
                            $stmt->bindParam(":s_code", $s_code);
                            $stmt->bindParam(":fullname", $fullname);
                            $stmt->bindParam(":phone", $phone);
                            $stmt->bindParam(":level", $level);
                            $stmt->bindParam(":photo", $fileNew);
                            $stmt->bindParam(":username", $username);
                            $stmt->bindParam(":password", $passwordHash);
                            $stmt->bindParam(":urole", $urole);
                            $stmt->execute();
                            $_SESSION['success'] = 'สมัครเรียบร้อยแล้ว';
                            header("location: add-student.php");
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
