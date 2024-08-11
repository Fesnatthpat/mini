<?php
session_start();
require 'config/db.php';

if (isset($_POST['signin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $_SESSION['error'] = 'กรุณาใส่ username';
        header("location: index.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณาใส่ password';
        header("location: index.php");
        exit();
    } else {
        try {
            $chk_data = $pdo->prepare("SELECT * FROM teacher WHERE username = :username");
            $chk_data->bindParam(":username", $username);
            $chk_data->execute();
            $row = $chk_data->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    if ($row['urole'] == 'admin') {
                        $_SESSION['admin_login'] = $row['t_id'];
                        header("location: home.php");
                        exit();
                    } else {
                        $_SESSION['user_login'] = $row['t_id'];
                        header("location: user.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'รหัสผ่านผิด';
                    header("location: index.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = 'ไม่มีข้อมูลในระบบ';
                header("location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
