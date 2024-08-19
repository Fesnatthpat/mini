let imgInput = document.getElementById('imgInput'); // ดึงอิลิเมนต์ HTML ที่มี ID เป็น 'imgInput' ซึ่งเป็น input type="file"
let previewImg = document.getElementById('previewImg'); // ดึงอิลิเมนต์ HTML ที่มี ID เป็น 'previewImg' ซึ่งเป็นแท็ก <img> สำหรับแสดงตัวอย่างภาพ

imgInput.onchange = evt => { // ตั้งค่าอีเวนต์ที่เกิดขึ้นเมื่อผู้ใช้เลือกไฟล์ใหม่ใน input
    const [file] = imgInput.files; // ดึงไฟล์แรกจากรายการไฟล์ที่เลือก (input type="file" อนุญาตให้เลือกหลายไฟล์ แต่ที่นี่ใช้ไฟล์แรก)
    if (file) { // ตรวจสอบว่าไฟล์ถูกเลือกหรือไม่
        previewImg.src = URL.createObjectURL(file); // สร้าง URL ชั่วคราวสำหรับไฟล์ภาพและตั้งค่าให้เป็นค่า src ของแท็ก <img> เพื่อแสดงตัวอย่างภาพ
    }
}
