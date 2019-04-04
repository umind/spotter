<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleSoldMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $ad;
    public $bid;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $ad, $bid, $user)
    {
        $this->event = $event;
        $this->ad = $ad;
        $this->bid = $bid;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('info@spotter.ch', 'Spotter')
                        ->subject(__('app.article_sold'))
                        ->view('emails.article_sold', with([
                                                        'event' => $this->event, 
                                                        'ad' => $this->ad,
                                                        'bid' => $this->user,
                                                        'user' => $this->user,
                                                    ]));

    }
}
