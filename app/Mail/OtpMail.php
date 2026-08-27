<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $otp;
    public $reason;

    public function __construct($otp, $reason = 'xác thực tài khoản')
    {
        $this->otp = $otp;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mã xác nhận OTP từ CozyHNA',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }
}
