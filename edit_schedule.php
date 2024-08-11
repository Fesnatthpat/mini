<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขตารางสอน</title>
    <link rel="stylesheet" href="edit_schedule.css">
</head>

<body>

    <div class="form-container">
        <div class="box1">
            <div class="box2">
                <h1 class="text-teacher">แก้ไขตารางสอน</h1>
                <hr>
                <div>
                    <div class="form-group">
                        <label for="name">ภาคเรียนที่1</label>
                        <select id="level" name="level">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">ปีการศึกษา</label>
                        <select id="level" name="level">
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">วิชา</label>
                        <select id="level" name="level">
                            <option value="">เลือกวิชา</option>
                            <option value="">ภาษา</option>
                            <option value="">คณิตศาสตร์</option>
                            <option value="">เคมี</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">ระดับชั้น</label>
                        <select id="level" name="level">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">ครูผู้สอน</label>
                        <select id="level" name="level">
                            <option value="">เลือกครูผู้สอน</option>
                            <option value="">ครูเต้ย</option>
                            <option value="">ครูแบม</option>
                            <option value="">ครูวิว</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">ห้องเรียน</label>
                        <select id="level" name="level">
                            <option value="">เลือกห้องเรียน</option>
                            <option value="">A001</option>
                            <option value="">B002</option>
                            <option value="">C003</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">วัน</label>
                        <select id="level" name="level">
                            <option value="">เลือกวัน</option>
                            <option value="">วันจันทร์</option>
                            <option value="">วันอังคาร</option>
                            <option value="">วันพุธ</option>
                            <option value="">วันพฤหัสบดี</option>
                            <option value="">วันศุกร์</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-name">เวลา</label>
                        <input type="text" id="search-name" name="search-name">
                    </div>
                    <div class="btn-con">
                        <div class="btn-submit">
                            <button type="submit">บันทึกข้อมูล</button>
                        </div>
                        <div class="btn-out">
                            <button onclick="window.location.href='Tutorial-Schedule.php'">ออก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>