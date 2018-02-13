<?php

namespace Logit\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Logit\Routine;
use Logit\User;

class ShareRoutine extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The routine instance.
     *
     * @var routine
     */
    public $routine;

    /**
     * The sender instance.
     *
     * @var sender
     */
    public $sender;

    /**
     * The reciever instance.
     *
     * @var reciever
     */
    public $reciever;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Routine $routine, User $sender, User $reciever)
    {
        $this->routine = $routine;
        $this->sender = $sender;
        $this->reciever = $reciever;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.shareRoutine')
                    ->subject($this->sender->name . ' has shared a routine with you!');
    }
}
