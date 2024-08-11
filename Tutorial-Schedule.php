<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลตารางสอน</title>
    <link rel="stylesheet" href="data-student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <div class="box-1">
            <div class="box-2">
                <div class="text-1">
                    <h1>ข้อมูลตารางสอน</h1>
                </div>
                <div class="search-form">
                    <div class="form-group">
                        <label for="search-level">ระดับชั้น</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกระดับชั้น</option>
                            <option value="1">ม.1</option>
                            <option value="2">ม.2</option>
                            <option value="3">ม.3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-level">ภาคเรียนที่</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกภาคเรียน</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-level">ปีการศึกษา</label>
                        <select id="search-level" name="search-level">
                            <option value="">เลือกปีการศึกษา</option>
                            <option value="2567">2567</option>
                            <option value="2566">2566</option>
                            <option value="2565">2565</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button">ค้นหา <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <div class="btn-con">
                    <button class="add-student-button" onclick="window.location.href='add-schedule.php'">+ เพิ่มตารางสอน</button>
                    <button class="out-student-button" onclick="window.location.href='home.php'">ออก</button>
                </div>
                <text class="2">
                    <h2>ตารางสอน ระดับชั้นม.1 ภาคเรียนที่1/2567</h2> <br>
                </text>
                <div class="group-form1">
                    <div class="group-form2">
                        <table>
                            <thead>
                                <tr>
                                    <th>รหัสวิชา</th>
                                    <th>ชื่อวิชา</th>
                                    <th>ครูผู้สอน</th>
                                    <th>ห้องเรียน</th>
                                    <th>วันเวลา</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S001</td>
                                    <td>เคมี 2</td>
                                    <td>ครูแบมพาจี้</td>
                                    <td>A101</td>
                                    <td>จ.08.00-10.00น.</td>
                                    <td><a href="edit_schedule.php"><i class="fa-solid fa-pen"></i></a> | <a href="#"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td>S002</td>
                                    <td>ภาษาไทย</td>
                                    <td>ครูวิวแหงนฟ้า</td>
                                    <td>A204</td>
                                    <td>พ.09.00-12.00น.</td>
                                    <td><a href="edit_schedule.php"><i class="fa-solid fa-pen"></i></a> | <a href="#"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td>S003</td>
                                    <td>คณิตศาสตร์</td>
                                    <td>ครูเต้ยนักซิ่ง</td>
                                    <td>C401</td>
                                    <td>อ.13.00-15.00น.</td>
                                    <td><a href="edit_schedule.php"><i class="fa-solid fa-pen"></i></a> | <a href="#"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td>S004</td>
                                    <td>ภาษายุโรป</td>
                                    <td>ครูข้าวแซงคิว</td>
                                    <td>D805</td>
                                    <td>ศ.13.00-15.00น.</td>
                                    <td><a href="edit_schedule.php"><i class="fa-solid fa-pen"></i></a> | <a href="#"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>