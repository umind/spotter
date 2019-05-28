<?php

namespace App\Mail;

use PDF;
use Session;
use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuctionWonMail extends Mailable
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

        $email = $this->from('sale@spotter.ch', 'Spotter')
                        ->subject(__('app.you_won_mail_subject'))
                        ->view('emails.auction_won', with([
                                                        'event' => $this->event, 
                                                        'ad' => $this->ad,
                                                        'user' => $this->user
                                                    ]));

        $pdf = PDF::loadView('pdf.invoice', [
                                            'event' => $this->event, 
                                            'ad' => $this->ad,
                                            'bid' => $this->bid,
                                            'user' => $this->user,
                                        ]);

        $email->attachData($pdf->output(), 'spotter-invoice.pdf');

        return $email;
    }
}
