<?php
session_start();
require_once 'config/db.php';

if (isset($_POST['add_building'])) {
    $name_building = $_POST['name_building'];

    if (empty($name_building)) {
        $_SESSION['error'] = 'กรุณากรอกข้อมูล';
        header("location: building.php");
        exit;
    } else {
        try {
            // ตรวจสอบว่ามีชื่ออาคารนี้อยู่ในฐานข้อมูลหรือไม่
            $chk_building_name = $pdo->prepare("SELECT building_name FROM building WHERE building_name = :building_name");
            $chk_building_name->bindParam(":building_name", $name_building);
            $chk_building_name->execute();
            $buildingData = $chk_building_name->fetch(PDO::FETCH_ASSOC);

            if ($buildingData) {
                $_SESSION['warning'] = 'มีข้อมูลในระบบแล้ว';
                header("location: building.php");
                exit;
            } else {
                // เพิ่มข้อมูลลงในฐานข้อมูล
                $stmt = $pdo->prepare("INSERT INTO building (building_name) VALUES (:building_name)");
                $stmt->bindParam(":building_name", $name_building);
                $stmt->execute();
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว';
                header("location: building.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>
