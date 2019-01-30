<?php

namespace App\Jobs;

use Mail;
use App\Mail\AuctionWonMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuctionWonMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $auction;
    protected $user;
    protected $bid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $auction, $bid, $user)
    {
        $this->event = $event;
        $this->auction = $auction;
        $this->bid = $bid;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new AuctionWonMail(
                                                    $this->event, 
                                                    $this->auction, 
                                                    $this->bid, 
                                                    $this->user
                                                ));
    }
}
