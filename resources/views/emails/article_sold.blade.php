<p>Hallo Admin</p>
<br>
<p>Folgender Artikel wurde gekauft:</p>
<br>
<p>Auktion: {{ $event->title }}</p>
<p>Artikel: {{ $ad->title }}</p>
<p>Art-Nr.: {{ $ad->auction_no }}</p>
<p>Los-Nr.: {{ $ad->bid_no }}</p>
<p>Preis: {{ number_format($bid->won_bid_amount * 7.7 / 100 , 2) }} CHF</p>
<br>