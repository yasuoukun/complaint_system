<!DOCTYPE html>
<html>
<head>
    <title>แจ้งเตือนคำร้องใหม่</title>
</head>
<body>
    <h2>เรียน ผู้ดูแลระบบ (Admin)</h2>
    
    <p>มีคำร้องใหม่ถูกส่งเข้ามาในระบบ รายละเอียดดังนี้:</p>
    
    <ul>
        <li><strong>เรื่อง:</strong> {{ $complaint->subject }}</li>
        <li><strong>ผู้ร้อง:</strong> {{ $complaint->first_name }} {{ $complaint->last_name }}</li>
        <li><strong>เบอร์โทร:</strong> {{ $complaint->phone_number }}</li>
        <li><strong>วันที่ส่ง:</strong> {{ $complaint->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>เนื้อหาโดยย่อ:</p>
    <p style="background-color: #f3f3f3; padding: 10px;">
        {{ $complaint->details }}
    </p>

    <p>กรุณาเข้าสู่ระบบเพื่อตรวจสอบและดำเนินการ: <a href="{{ route('admin.complaints.index') }}">คลิกที่นี่เพื่อเข้าสู่ระบบ</a></p>
</body>
</html>