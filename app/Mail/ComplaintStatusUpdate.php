<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function envelope(): Envelope
    {
        $statusText = $this->complaint->status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ/แก้ไข';
        return new Envelope(
            subject: 'แจ้งผลการพิจารณาคำร้อง: ' . $statusText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_status', // เดี๋ยวเราไปสร้างไฟล์นี้กัน
        );
    }
}