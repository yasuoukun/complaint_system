<!DOCTYPE html>
<html>
<head><title>ผลการพิจารณา</title></head>
<body>
    <h2>เรียน คุณ {{ $complaint->first_name }} {{ $complaint->last_name }}</h2>
    <p>เรื่อง: {{ $complaint->subject }}</p>
    
    <p>ทางเทศบาลได้พิจารณาคำร้องของท่านแล้ว ผลคือ: 
       <strong style="color: {{ $complaint->status == 'approved' ? 'green' : 'red' }}">
           {{ $complaint->status == 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ / ต้องแก้ไข' }}
       </strong>
    </p>

    <p><strong>ความคิดเห็น/นัดหมายจากเจ้าหน้าที่:</strong></p>
    <p style="background-color: #eee; padding: 10px;">
        {{ $complaint->admin_notes }}
    </p>

    <p>ขอบคุณครับ</p>
</body>
</html>