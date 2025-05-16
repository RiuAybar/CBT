<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenidaCrearPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token, $subject, $user, $mensaje;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token, $subject, $mensaje)
    {
        $this->user = $user;
        $this->token = $token;
        $this->subject = $subject;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function build()
    {
        $url = url("/login/registrar/password/{$this->token}?email=" . urlencode($this->user->email));

        return $this->subject('Bienvenido, configura tu contraseña')
            ->markdown('emails.usuarios.bienvenida')
            ->with([
                'user' => $this->user,
                'url' => $url,
                'mensaje' => $this->mensaje,
            ]);
    }
}
