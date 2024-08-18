<?php
session_start(); // เริ่มต้นเซสชัน PHP เพื่อใช้ในการเก็บข้อมูลระหว่างการทำงานของสคริปต์
require 'config/db.php'; // นำเข้าไฟล์การตั้งค่าฐานข้อมูล เพื่อเตรียมเชื่อมต่อกับฐานข้อมูล

if (isset($_POST['add_subject'])) { // ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์มที่มีปุ่มชื่อ 'add_subject' หรือไม่
    $subject_code = $_POST['subject_code']; // เก็บค่ารหัสวิชาที่ผู้ใช้ป้อนมาในตัวแปร $subject_code
    $subject_name = $_POST['subject_name']; // เก็บค่าชื่อวิชาที่ผู้ใช้ป้อนมาในตัวแปร $subject_name
    $level = $_POST['level']; // เก็บค่าระดับชั้นที่ผู้ใช้ป้อนมาในตัวแปร $level
    $subject_group = $_POST['subject_group']; // เก็บค่ากลุ่มวิชาที่ผู้ใช้เลือกในตัวแปร $subject_group
    $photo = $_FILES['photo']; // เก็บไฟล์รูปภาพที่ผู้ใช้อัพโหลดมาในตัวแปร $photo

    if (empty($subject_code)) { // ตรวจสอบว่าผู้ใช้ได้กรอกรหัสวิชาหรือไม่
        $_SESSION['error'] = 'กรุณากรอกรหัสวิชา'; // หากผู้ใช้ไม่ได้กรอกรหัสวิชา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else if (empty($subject_name)) { // ตรวจสอบว่าผู้ใช้ได้กรอกชื่อวิชาหรือไม่
        $_SESSION['error'] = 'กรุณากรอกชื่อวิชา'; // หากผู้ใช้ไม่ได้กรอกชื่อวิชา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else if (empty($level)) { // ตรวจสอบว่าผู้ใช้ได้กรอกรดับชั้นหรือไม่
        $_SESSION['error'] = 'กรุณากรอกระดับชั้น'; // หากผู้ใช้ไม่ได้กรอกรดับชั้น จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else if (empty($subject_group)) { // ตรวจสอบว่าผู้ใช้ได้เลือกกลุ่มวิชาหรือไม่
        $_SESSION['error'] = 'กรุณาเลือกกลุ่มวิชา'; // หากผู้ใช้ไม่ได้เลือกกลุ่มวิชา จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else if (empty($photo['name'])) { // ตรวจสอบว่าผู้ใช้ได้อัพโหลดไฟล์รูปภาพหรือไม่
        $_SESSION['error'] = 'กรุณาเพิ่มไฟล์รูป'; // หากผู้ใช้ไม่ได้อัพโหลดไฟล์รูปภาพ จะแสดงข้อความข้อผิดพลาดผ่านเซสชัน
        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
    } else { // หากผู้ใช้กรอกข้อมูลครบถ้วนแล้ว
        try {
            $chk_subject2name = $pdo->prepare("SELECT subject_name FROM subject WHERE subject_name = :subject_name"); // เตรียมคำสั่ง SQL เพื่อเช็คว่ามีชื่อวิชานี้อยู่ในฐานข้อมูลหรือไม่
            $chk_subject2name->bindParam(":subject_name", $subject_name); // ผูกค่าชื่อวิชาที่ผู้ใช้กรอกเข้ากับพารามิเตอร์ในคำสั่ง SQL
            $chk_subject2name->execute(); // ดำเนินการคำสั่ง SQL
            $data_subject2 = $chk_subject2name->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลจากผลลัพธ์ของคำสั่ง SQL

            if ($data_subject2) { // ตรวจสอบว่าพบชื่อวิชาในฐานข้อมูลหรือไม่
                $_SESSION['warning'] = 'มีชื่อวิชานี้อยู่ในระบบแล้ว'; // หากพบชื่อวิชา จะแสดงข้อความเตือนผ่านเซสชัน
                header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
                exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
            } else { // หากไม่พบชื่อวิชา
                $allow = array('jpg', 'jpeg', 'png'); // กำหนดประเภทไฟล์รูปภาพที่อนุญาตให้อัพโหลด
                $extention = explode(".", $photo['name']); // แยกชื่อไฟล์และนามสกุลไฟล์รูปภาพออกจากกัน
                $fileActExt = strtolower(end($extention)); // แปลงนามสกุลไฟล์รูปภาพให้เป็นตัวพิมพ์เล็ก
                $fileNew = rand() . "." . $fileActExt; // สร้างชื่อไฟล์ใหม่โดยใช้ตัวเลขสุ่มและนามสกุลไฟล์
                $filePath = "uploads_subject2/" . $fileNew; // กำหนดเส้นทางสำหรับเก็บไฟล์รูปภาพที่อัพโหลด

                if (in_array($fileActExt, $allow)) { // ตรวจสอบว่านามสกุลไฟล์รูปภาพอยู่ในประเภทที่อนุญาตหรือไม่
                    if ($photo['size'] > 0 && $photo['error'] == 0) { // ตรวจสอบขนาดและข้อผิดพลาดของไฟล์รูปภาพ
                        if (move_uploaded_file($photo['tmp_name'], $filePath)) { // ย้ายไฟล์รูปภาพไปยังเส้นทางที่กำหนด
                            $stmt = $pdo->prepare("INSERT INTO subject (subject_code, subject_name, level, subject_group, photo)
                                        VALUES(:subject_code, :subject_name, :level, :subject_group, :photo)"); // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลวิชาลงในฐานข้อมูล
                            $stmt->bindParam(":subject_code", $subject_code); // ผูกค่ารหัสวิชากับพารามิเตอร์ในคำสั่ง SQL
                            $stmt->bindParam(":subject_name", $subject_name); // ผูกค่าชื่อวิชากับพารามิเตอร์ในคำสั่ง SQL
                            $stmt->bindParam(":level", $level); // ผูกค่าระดับชั้นกับพารามิเตอร์ในคำสั่ง SQL
                            $stmt->bindParam(":subject_group", $subject_group); // ผูกค่ากลุ่มวิชากับพารามิเตอร์ในคำสั่ง SQL
                            $stmt->bindParam(":photo", $fileNew); // ผูกชื่อไฟล์รูปภาพใหม่กับพารามิเตอร์ในคำสั่ง SQL
                            $stmt->execute(); // ดำเนินการคำสั่ง SQL เพื่อเพิ่มข้อมูลในฐานข้อมูล
                            $_SESSION['success'] = 'เพิ่มวิชาเรียบร้อยแล้ว'; // แสดงข้อความสำเร็จผ่านเซสชัน
                            header("location: data-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
                            exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                        } else { // หากการย้ายไฟล์รูปภาพล้มเหลว
                            $_SESSION['error'] = 'การอัพโหลดรูปภาพล้มเหลว'; // แสดงข้อความข้อผิดพลาดผ่านเซสชัน
                            header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
                            exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                        }
                    } else { // หากไฟล์รูปภาพมีขนาดไม่ถูกต้องหรือเกิดข้อผิดพลาด
                        $_SESSION['error'] = 'ไฟล์รูปภาพไม่ถูกต้อง'; // แสดงข้อความข้อผิดพลาดผ่านเซสชัน
                        header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
                        exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                    }
                } else { // หากนามสกุลไฟล์รูปภาพไม่ถูกต้อง
                    $_SESSION['error'] = 'ประเภทไฟล์รูปภาพไม่ถูกต้อง'; // แสดงข้อความข้อผิดพลาดผ่านเซสชัน
                    header("location: add-subject.php"); // เปลี่ยนเส้นทางกลับไปยังหน้า add-subject.php
                    exit(); // หยุดการทำงานของสคริปต์เพื่อไม่ให้ดำเนินการต่อไป
                }
            }
        } catch (PDOException $e) { // จับข้อผิดพลาดที่เกิดขึ้นจากการทำงานกับฐานข้อมูล
            echo $e->getMessage(); // แสดงข้อความข้อผิดพลาด
        }
    }
}
