<?php

namespace App\Http\Controllers;

use App\Ad;
use PDF;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
   	public function getInvoice($ad_id)
	{
		$user = auth()->user();
		$ad = Ad::findOrFail($ad_id);
        $wonBid = $ad->bids()->where('is_accepted', 1)->firstOrFail();

		$this->authorize('view-invoice', [$ad, $wonBid]);

        $pdf = PDF::loadView('pdf.invoice', ['ad' => $ad, 'bid' => $wonBid, 'user' => $wonBid->user]);

        return $pdf->stream();
	}
}
