<?php

namespace App\Mail;

use App\Models\Complaint; // เรียกใช้ Model Complaint
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewComplaintNotification extends Mailable
{
    use Queueable, SerializesModels;

    // ประกาศตัวแปรสาธารณะ เพื่อให้หน้าเว็บ (View) ของอีเมลเอาข้อมูลไปใช้ได้
    public $complaint;

    /**
     * Create a new message instance.
     */
    public function __construct(Complaint $complaint)
    {
        // รับข้อมูลคำร้องเข้ามา แล้วเก็บไว้ในตัวแปร $this->complaint
        $this->complaint = $complaint;
    }

    /**
     * Get the message envelope. (หัวข้ออีเมล)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // กำหนดหัวข้ออีเมลที่แอดมินจะเห็น
            subject: 'มีคำร้องใหม่: ' . $this->complaint->subject,
        );
    }

    /**
     * Get the message content definition. (เนื้อหาอีเมล)
     */
    public function content(): Content
    {
        return new Content(
            // ระบุไฟล์ Blade View ที่จะเป็นหน้าตาของอีเมล
            view: 'emails.new_complaint',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}