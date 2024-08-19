<!DOCTYPE html> <!--ประกาศว่าเอกสารนี้เป็น HTML5 ช่วยให้เบราว์เซอร์รู้จักวิธีการตีความเอกสาร-->
<html lang="th"> <!--เป็นการเลือกใช้ภาษาไทย-->

<head>
    <meta charset="UTF-8"> <!--ระบุการเข้ารหัสของเอกสารเป็น UTF-8 ซึ่งรองรับหลายภาษา-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--ควบคุมการแสดงผลบนอุปกรณ์-->
    <title>เพิ่มอาคารเรียน</title> <!--ตั้งชื่อแท็บ-->
    <link rel="stylesheet" href="edit_subject_group.css"> <!--ลิงก์ไปยังหน้าไฟล์ CSS ที่ใช้จัดรูปแบบหน้าเว็บ-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!--ลิงก์ไปยัง CSS ของฟอนต์-->
</head>
<div class="form-container"> <!--จัดกลุ่มเนื้อหาเพื่อให้ง่ายต่อการจัดรูปแบบ-->
        <div class="box1"> <!--จัดกลุ่มเนื้อหาเพื่อให้ง่ายต่อการจัดรูปแบบ-->
            <div class="box2"> <!--จัดกลุ่มเนื้อหาเพื่อให้ง่ายต่อการจัดรูปแบบ-->
                <h1 class="text-edit">เพิ่มอาคารเรียน</h1> 
                <hr>
                <div class="form-group">
                        <label for="level">เลือกอาคารเรียน</label> <!--ป้ายกำกับสำหรับฟิลด์เลือก-->
                        <select id="level" name="level"> <!--ฟิลด์เลือก (drop-down) ใช้ id และ name เพื่อระบุและส่งข้อมูล-->
                            <option value="">เลือกอาคารเรียน</option> <!--ตัวเลือกเริ่มต้นในฟิลด์เลือก-->
                            <option value="">A</option>
                            <option value="">B</option>
                            <option value="">C</option>
                        </select>
                    </div>
                    <div class="btn-con"> <!--กลุ่มของปุ่มใช้คลาส btn-con สำหรับจัดรูปแบบ-->
                        <div class="btn-submit"> <!--กล่องสำหรับปุ่มบันทึกข้อมูล-->
                            <button  type="submit">บันทึกข้อมูล</button> <!--ปุ่มสำหรับการการส่งข้อมูลของฟอร์ม-->
                        </div>
                        <div class="btn-out"> <!--กล่องสำหรับปุ่มออก-->
                            <button onclick="window.location.href='building.php'">ออก</button> <!--ปุ่มที่เมื่อคลิกจะพาผู้ใช้ไปยังหน้าที่ระบุ-->
                        </div>
<body>
