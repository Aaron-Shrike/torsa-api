<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dates,$names)
    {
    
        $this->date =  $dates;
        $this->name = $names;
        //$this->date = $dates['contrasenia'];
        //$this -> date = Crypt::decryptString($dates['contrasenia']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo Trabajador')->view('emails.TestEmail');
    }
}
