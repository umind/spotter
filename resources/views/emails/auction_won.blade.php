@php
	if ($user->title = '0') {
		$greetings = 'Sehr geehrter Herr ' . $user->last_name;
	} else if ($user->title == '1') {
		$greetings = 'Sehr geerte Frau ' . $user->last_name;
	} else {
		$greetings = 'Sehr geehrte Damen und Herren';
	}
@endphp

<p>{{ $greetings }}</p>
<br>
<p>Sie haben bei der Auktion ({{ $event->title }}) den Artikel ({{ $ad->title }}) gewonnen.</p>
<br>
<p>Bitte den Rechnungsbetrag innert 48h überweisen.</p>
<p>Bei einer persönlichen Abholung innert 7 Tagen dürfen Sie auch bar bezahlen.</p>
<br>
<p>Unbedingt die Fristen gemäss AGB einhalten</p>
<p>Danke für den Einkauf</p>
<br>
<p>Ihr LUIS-B Team</p>