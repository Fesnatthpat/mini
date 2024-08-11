<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['add_subject'])) {
    $subject_group_name = $_POST['subject_group_name'];


    if (empty($subject_group_name)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูล';
        header("location: data-subject_group.php");
        exit;
    } else {
        try {

            $chk_subjectname = $pdo->prepare("SELECT subj_group_name FROM subject_group WHERE subj_group_id = :subj_group_id");
            $chk_subjectname->bindParam(":subj_group_id", $subj_group_id);
            $chk_subjectname->execute();
            $subjectData = $chk_subjectname->fetch(PDO::FETCH_ASSOC);

            if ($subjectData) {
                $_SESSION['warning'] = 'มีข้อมูลในระบบแล้ว';
                header("location: data-subject_group.php");
                exit;
            } else {
                $stmt = $pdo->prepare("INSERT INTO subject_group (subj_group_name) VALUES (:subj_group_name)");
                $stmt->bindParam(":subj_group_name", $subject_group_name);
                $stmt->execute();
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว';
                header("location: data-subject_group.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
