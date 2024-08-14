<!DOCTYPE html> <!-- ประกาศว่าเอกสารนี้เป็น HTML5 -->
<html lang="th"> <!-- กำหนดภาษาเป็นภาษาไทย -->

<head>
    <meta charset="UTF-8"> <!-- กำหนดการเข้ารหัสตัวอักษรเป็น UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- กำหนด viewport สำหรับการแสดงผลบนอุปกรณ์มือถือ -->
    <title>เพิ่มตารางสอน</title> <!-- กำหนดชื่อเอกสารที่จะแสดงบนแท็บเบราว์เซอร์ -->
    <link rel="stylesheet" href="add-schedule.css"> <!-- เชื่อมโยงไปยังไฟล์ CSS สำหรับการจัดรูปแบบ -->
</head>

<body>

    <div class="form-container"> <!-- กล่องหลักที่บรรจุฟอร์ม -->
        <div class="box1"> <!-- กล่องใหญ่ที่จัดตำแหน่งเนื้อหากลางหน้าจอ -->
            <div class="box2"> <!-- กล่องย่อยที่บรรจุฟอร์มและเนื้อหา -->
                <h1 class="text-teacher">ข้อมูลตารางสอน</h1> <!-- หัวข้อของฟอร์ม -->
                <hr> <!-- เส้นแบ่ง -->
                <div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับภาคเรียน -->
                        <label for="name">ภาคเรียนที่1</label> <!-- ป้ายชื่อสำหรับเมนูเลือกภาคเรียน -->
                        <select id="level" name="level"> <!-- เมนูเลือกภาคเรียน -->
                            <option value="">เลือกภาคเรียน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="1">1</option> <!-- ตัวเลือกภาคเรียนที่ 1 -->
                            <option value="2">2</option> <!-- ตัวเลือกภาคเรียนที่ 2 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับปีการศึกษา -->
                        <label for="level">ปีการศึกษา</label> <!-- ป้ายชื่อสำหรับเมนูเลือกปีการศึกษา -->
                        <select id="level" name="level"> <!-- เมนูเลือกปีการศึกษา -->
                            <option value="">เลือกปีการศึกษา</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="2567">2567</option> <!-- ตัวเลือกปีการศึกษา 2567 -->
                            <option value="2566">2566</option> <!-- ตัวเลือกปีการศึกษา 2566 -->
                            <option value="2565">2565</option> <!-- ตัวเลือกปีการศึกษา 2565 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับวิชา -->
                        <label for="name">วิชา</label> <!-- ป้ายชื่อสำหรับเมนูเลือกวิชา -->
                        <select id="level" name="level"> <!-- เมนูเลือกวิชา -->
                            <option value="">เลือกวิชา</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="">ภาษา</option> <!-- ตัวเลือกวิชา ภาษา -->
                            <option value="">คณิตศาสตร์</option> <!-- ตัวเลือกวิชา คณิตศาสตร์ -->
                            <option value="">วิทยาศาสตร์</option> <!-- ตัวเลือกวิชา วิทยาศาสตร์ -->
                            <option value="">สังคมศาสตร์</option> <!-- ตัวเลือกวิชา สังคมศาสตร์ -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับระดับชั้น -->
                        <label for="name">ระดับชั้น</label> <!-- ป้ายชื่อสำหรับเมนูเลือกระดับชั้น -->
                        <select id="level" name="level"> <!-- เมนูเลือกระดับชั้น -->
                            <option value="">เลือกระดับชั้น</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="1">ม.1</option> <!-- ตัวเลือกระดับชั้น ม.1 -->
                            <option value="2">ม.2</option> <!-- ตัวเลือกระดับชั้น ม.2 -->
                            <option value="3">ม.3</option> <!-- ตัวเลือกระดับชั้น ม.3 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับครูผู้สอน -->
                        <label for="name">ครูผู้สอน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกครูผู้สอน -->
                        <select id="level" name="level"> <!-- เมนูเลือกครูผู้สอน -->
                            <option value="">เลือกครูผู้สอน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="">ครูเต้ย</option> <!-- ตัวเลือกครูผู้สอน ครูเต้ย -->
                            <option value="">ครูแบม</option> <!-- ตัวเลือกครูผู้สอน ครูแบม -->
                            <option value="">ครูวิว</option> <!-- ตัวเลือกครูผู้สอน ครูวิว -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับห้องเรียน -->
                        <label for="name">ห้องเรียน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกห้องเรียน -->
                        <select id="level" name="level"> <!-- เมนูเลือกห้องเรียน -->
                            <option value="">เลือกห้องเรียน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="">A001</option> <!-- ตัวเลือกห้องเรียน A001 -->
                            <option value="">B002</option> <!-- ตัวเลือกห้องเรียน B002 -->
                            <option value="">C003</option> <!-- ตัวเลือกห้องเรียน C003 -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับวัน -->
                        <label for="name">วัน</label> <!-- ป้ายชื่อสำหรับเมนูเลือกวัน -->
                        <select id="level" name="level"> <!-- เมนูเลือกวัน -->
                            <option value="">เลือกวัน</option> <!-- ตัวเลือกเริ่มต้น -->
                            <option value="">วันจันทร์</option> <!-- ตัวเลือกวันจันทร์ -->
                            <option value="">วันอังคาร</option> <!-- ตัวเลือกวันอังคาร -->
                            <option value="">วันพุธ</option> <!-- ตัวเลือกวันพุธ -->
                            <option value="">วันพฤหัสบดี</option> <!-- ตัวเลือกวันพฤหัสบดี -->
                            <option value="">วันศุกร์</option> <!-- ตัวเลือกวันศุกร์ -->
                        </select>
                    </div>
                    <div class="form-group"> <!-- กลุ่มฟอร์มสำหรับเวลา -->
                        <label for="search-name">เวลา</label> <!-- ป้ายชื่อสำหรับช่องกรอกเวลา -->
                        <input type="text" id="search-name" name="search-name"> <!-- ช่องกรอกข้อมูลเวลา -->
                    </div>
                    <div class="btn-con"> <!-- กลุ่มปุ่มสำหรับบันทึกข้อมูลและออก -->
                        <div class="btn-submit"> <!-- กล่องปุ่มบันทึกข้อมูล -->
                            <button type="submit">บันทึกข้อมูล</button> <!-- ปุ่มสำหรับบันทึกข้อมูล -->
                        </div>
                        <div class="btn-out"> <!-- กล่องปุ่มออก -->
                            <button onclick="window.location.href='Tutorial-Schedule.php'">ออก</button> <!-- ปุ่มสำหรับออกจากฟอร์มและไปยังหน้า Tutorial-Schedule.php -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
