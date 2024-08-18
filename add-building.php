<!DOCTYPE html>
<html lang="th"> <!-- กำหนดภาษาเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 เพื่อรองรับภาษาไทย -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ตั้งค่า viewport สำหรับการรองรับอุปกรณ์เคลื่อนที่ -->
    <title>เพิ่มอาคารเรียน</title> <!-- กำหนดชื่อเรื่องของหน้าเว็บ -->
    <link rel="stylesheet" href="edit_subject_group.css"> <!-- นำเข้าชุดสไตล์ CSS สำหรับการออกแบบหน้าเว็บ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- นำเข้าฟอนต์ไอคอนจาก Font Awesome -->
</head>

<body> <!-- เริ่มต้นการใช้เนื้อหาของหน้าเว็บ -->
    <div class="form-container"> <!-- กล่องคอนเทนเนอร์หลักสำหรับฟอร์ม -->yt[gihotf]
        <div class="box1"> <!-- กล่องสำหรับการจัดตำแหน่งหลัก -->
            <div class="box2"> <!-- กล่องที่อยู่ภายใน box1 -->
                <h1 class="text-edit">เพิ่มอาคารเรียน</h1> <!-- หัวเรื่องหลักของฟอร์ม -->
                <hr> <!-- เส้นคั่นเพื่อแยกหัวเรื่องกับเนื้อหา -->
                <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับการจัดกลุ่มของป้ายและเลือก -->
                    <label for="level">เลือกอาคารเรียน</label> <!-- ป้ายสำหรับเลือกอาคารเรียน -->
                    <select id="level" name="level"> <!-- เมนูดรอปดาวน์สำหรับเลือกอาคารเรียน -->
                        <option value="">เลือกอาคารเรียน</option> <!-- ตัวเลือกเริ่มต้นในเมนูดรอปดาวน์ -->
                        <option value="A">A</option> <!-- ตัวเลือกอาคาร A -->
                        <option value="B">B</option> <!-- ตัวเลือกอาคาร B -->
                        <option value="C">C</option> <!-- ตัวเลือกอาคาร C -->
                    </select>
                </div>
                <div class="btn-con"> <!-- กลุ่มปุ่มสำหรับการดำเนินการ -->
                    <div class="btn-submit"> <!-- กล่องปุ่มสำหรับการบันทึก -->
                        <button type="submit">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับการบันทึกข้อมูล -->
                    </div>
                    <div class="btn-out"> <!-- กล่องปุ่มสำหรับการออก -->
                        <button onclick="window.location.href='building.php'">ออก</button> <!-- ปุ่มสำหรับการออกไปยังหน้า 'building.php' -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body> <!-- สิ้นสุดเนื้อหาของหน้าเว็บ -->
</html> <!-- ปิดแท็ก HTML -->
