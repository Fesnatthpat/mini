<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $subj_group_id = $_POST['subj_group_id'];
    $subj_group_name = $_POST['subj_group_name'];

    $sql = $pdo->prepare("UPDATE subject_group SET subj_group_name = :subj_group_name, subj_group_id = :subj_group_id WHERE subj_group_id = :subj_group_id");
    $sql->bindParam(":subj_group_name", $subj_group_name);;
    $sql->bindParam(":subj_group_id", $subj_group_id); // Add this line to bind t_id
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: data-subject_group.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: edit_subject_group.php");
        exit();
    }
}