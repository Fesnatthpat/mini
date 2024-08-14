<!DOCTYPE html>
<html lang="th"> <!-- กำหนดประเภทเอกสาร HTML และภาษาของเอกสารเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสอักขระเป็น UTF-8 เพื่อรองรับการแสดงผลข้อความได้หลากหลาย -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนดการแสดงผลให้พอดีกับขนาดหน้าจอของอุปกรณ์มือถือ -->
    <title>เพิ่มกลุ่มวิชา</title> <!-- กำหนดชื่อเรื่องของเอกสารที่จะปรากฏในแท็บของเบราว์เซอร์ -->
    <link rel="stylesheet" href="edit_subject_group.css"> <!-- นำเข้ารูปแบบ CSS จากไฟล์ edit_subject_group.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- นำเข้าฟอนต์ไอคอน Font Awesome จาก CDN -->
</head>

<body> <!-- เริ่มต้นส่วนเนื้อหาหลักของเอกสาร -->

    <div class="form-container"> <!-- กล่องหลักที่บรรจุฟอร์ม -->
        <div class="box1"> <!-- กล่องที่จัดตำแหน่งฟอร์มให้อยู่กลางหน้าจอ -->
            <div class="box2"> <!-- กล่องที่บรรจุฟอร์มจริง -->
                <h1 class="text-edit">เพิ่มกลุ่มวิชา</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นคั่นระหว่างหัวข้อและฟอร์ม -->
                <div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มที่บรรจุช่องกรอกข้อมูล -->
                        <label for="student-id">กลุ่มวิชา</label> <!-- ป้ายชื่อของช่องกรอกข้อมูล -->
                        <input type="text" id="student-id" name="student-id"> <!-- ช่องกรอกข้อมูล -->
                    </div>
                    <div class="btn-con"> <!-- กลุ่มปุ่ม -->
                        <div class="btn-submit"> <!-- กล่องสำหรับปุ่มบันทึก -->
                            <button type="submit">บันทึกข้อมูล</button> <!-- ปุ่มบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องสำหรับปุ่มออก -->
                            <button onclick="window.location.href='data-subject_group.php'">ออก</button> <!-- ปุ่มออกที่จะเปลี่ยนเส้นทางไปยัง data-subject_group.php -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body> <!-- สิ้นสุดส่วนเนื้อหาหลักของเอกสาร -->
</html> <!-- สิ้นสุดเอกสาร HTML -->
