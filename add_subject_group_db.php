<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อเก็บข้อมูลที่ต้องการคงอยู่ระหว่างการทำงานของสคริปต์
require_once 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูล เพื่อเตรียมเชื่อมต่อกับฐานข้อมูล

if (isset($_POST['add_subject'])) { // ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์มที่มีปุ่มชื่อ 'add_subject' หรือไม่
    $subject_group_name = $_POST['subject_group_name']; // เก็บค่าชื่อกลุ่มวิชาที่ผู้ใช้ป้อนมาในตัวแปร $subject_group_name

    if (empty($subject_group_name)) { // ตรวจสอบว่าผู้ใช้ได้กรอกชื่อกลุ่มวิชาหรือไม่
        $_SESSION['error'] = 'กรุณากรอกข้อมูล'; // หากผู้ใช้ไม่ได้กรอกชื่อกลุ่มวิชา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: data-subject_group.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า data-subject_group.php
        exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากผู้ใช้กรอกข้อมูลครบถ้วนแล้ว
        try {

            $chk_subjectname = $pdo->prepare("SELECT subj_group_name FROM subject_group WHERE subj_group_id = :subj_group_id"); // เตรียมคำสั่ง SQL เพื่อเช็คว่ามีชื่อกลุ่มวิชานี้อยู่ในฐานข้อมูลหรือไม่
            $chk_subjectname->bindParam(":subj_group_id", $subj_group_id); // ผูกค่า ID ของกลุ่มวิชากับพารามิเตอร์ในคำสั่ง SQL
            $chk_subjectname->execute(); // ดำเนินการคำสั่ง SQL
            $subjectData = $chk_subjectname->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลจากผลลัพธ์ของคำสั่ง SQL

            if ($subjectData) { // ตรวจสอบว่าพบชื่อกลุ่มวิชาในฐานข้อมูลหรือไม่
                $_SESSION['warning'] = 'มีข้อมูลในระบบแล้ว'; // หากพบชื่อกลุ่มวิชา จะแสดงข้อความเตือนผ่านเซสชัน
                header("location: data-subject_group.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า data-subject_group.php
                exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            } else { // หากไม่พบชื่อกลุ่มวิชาในฐานข้อมูล
                $stmt = $pdo->prepare("INSERT INTO subject_group (subj_group_name) VALUES (:subj_group_name)"); // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลกลุ่มวิชาลงในฐานข้อมูล
                $stmt->bindParam(":subj_group_name", $subject_group_name); // ผูกค่าชื่อกลุ่มวิชากับพารามิเตอร์ในคำสั่ง SQL
                $stmt->execute(); // ดำเนินการคำสั่ง SQL เพื่อเพิ่มข้อมูลในฐานข้อมูล
                $_SESSION['success'] = 'เพิ่มข้อมูลเรียบร้อยแล้ว'; // แสดงข้อความสำเร็จผ่านเซสชัน
                header("location: data-subject_group.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า data-subject_group.php
                exit; // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            }
        } catch (PDOException $e) { // จับข้อผิดพลาดที่เกิดขึ้นจากการทำงานกับฐานข้อมูล
            echo $e->getMessage(); // แสดงข้อความข้อผิดพลาด
        }
    }
}
