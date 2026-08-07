<?php

namespace App\Mail;

use App\Models\OrderComplaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;

    public function __construct(OrderComplaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Phản Hồi Khiếu Nại Đơn Hàng ' . ($this->complaint->order->code ?? ''),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
