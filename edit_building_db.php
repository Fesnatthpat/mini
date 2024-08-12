<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['update'])) {
    $building_id = $_POST['building_id'];
    $building_name = $_POST['building_name'];

    $sql = $pdo->prepare("UPDATE building SET building_name = :building_name, building_id = :building_id WHERE building_id = :building_id");
    $sql->bindParam(":building_id", $building_id);;
    $sql->bindParam(":building_name", $building_name);
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        header("location: building.php");
        exit();
    } else {
        $_SESSION['error'] = 'ไม่สามารถแก้ไขข้อมูลได้';
        header("location: building.php");
        exit();
    }
}