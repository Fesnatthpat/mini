<?php
session_start();
require_once 'config/db.php';

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $pdo->prepare("DELETE FROM teacher WHERE t_id = ?");
    $deletestmt->execute([$delete_id]);

    if ($deletestmt) {
        echo "<script>alert('ลบข้อมูลสำเร็จ');</script>";
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        header("refresh:1; url=teacher.php");
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล');</script>";
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบข้อมูล";
        header("refresh:1; url=teacher.php");
        exit();
    }
} else {
    echo "<script>alert('ไม่พบข้อมูลที่ต้องการลบ');</script>";
    $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการลบ";
    header("refresh:1; url=teacher.php");
    exit();
}
