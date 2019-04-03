<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <title>Invoice</title>

    <style>
        html,
        body {
            margin: 0;
            width: 100%;
            height: 100%;
            line-height: 0.4;
            font-size: 13px;
			font-family: 'Open Sans', sans-serif;
        }

        .wrapper {
            width: 80%;
            margin: 50px auto;
        }

        .logo {
            display: block;
            height: 20px;
        }

        .info {
            display: block;
            float: left;
            margin-bottom: 20px;
        }

        .info>p {
            text-align: left;
        }

		.client-nr {
			font-size: 12px;
		}

		.city-zip {
			margin-top: 17px;
			margin-bottom: 50px;
		}

        .mid_part {
            margin-top: 30px;
            margin-bottom: 30px;
            clear: both;
            width: 60%;
        }

        .nobold {
            font-weight: normal;
        }

        .bold {
        	font-weight: 700;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        /*td,
        th {
            border: 1px solid #e0e4f6;
            text-align: unset;
            padding: 8px;
        }*/

        td:hover,
        th:hover {
            background: #f8fafc;
        }

        .left_info {
            display: inline-block;
            font-weight: normal;
        }

        ::selection {
            background: #373d54;
            color: #fff;
        }

        .h4 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        .address p {
            font-size: 13px;
            font-style: italic;
        }

        .receipt-table td {
            vertical-align: middle;
            text-align: center;
            font-size: 13px;
            padding: 10px 0px;
            border-top: 1px solid #e0e4f6;
            line-height: 1.2;
            white-space: nowrap;
        }

        .receipt-table th {
            text-align: center;
            font-size: 13px;
            padding: 10px 0px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .calc td {
            border: none;
            line-height: 0.2;
        }

        .calc:first-child td {
            padding-top: 30px;
            border-top: 1px solid #e0e4f6;
        }

        .calc:last-child td {
            font-size: 14px;
            font-weight: 600;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    

{{--     @php
        $first_name = $orders['deliveryOrder']->user->first_name;
        $last_name = $orders['deliveryOrder']->user->last_name;
        $full_name =  $first_name . ' ' . $last_name;

        

        $hasDeliveryOrder = isset($orders['deliveryOrder']);
        $hasPickupOrder = isset($orders['pickupOrder']);

        $orderNumber = "";

        if ($hasDeliveryOrder == true && $hasPickupOrder == true) {
            $orderNumber = $orders['deliveryOrder']->id."/".$orders['pickupOrder']->id;
        } elseif ($hasDeliveryOrder == true && $hasPickupOrder == false) {
            $orderNumber = $orders['deliveryOrder']->id;
        } elseif ($hasDeliveryOrder == false && $hasPickupOrder == true) {
            $orderNumber = $orders['pickupOrder']->id;
        }

        $shipping_price = (float)\App\Models\Database\Configuration::getConfiguration('delivery_price');

        if ($hasDeliveryOrder == true )
            $orderForAddresses = 'deliveryOrder';
        else
            $orderForAddresses = 'pickupOrder';

        $billing = $orders[$orderForAddresses]->billing_address;
        $shipping = is_null($orders[$orderForAddresses]->shipping_address) ? $orders[$orderForAddresses]->billing_address : $orders[$orderForAddresses]->shipping_address;

        $billing_zip_city = $billing->postcode . ' ' . $billing->city;
        $shipping_zip_city = $shipping->postcode . ' ' . $shipping->city;
    @endphp --}}

    @php
		setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
    @endphp

    <div class="wrapper order-pdf">
        <div class="header">
            <div class="info company-info">
            	<img src="{{ asset('uploads/logo/spotter_logo.png') }}" class="logo">
            </div>
            <div class="customer-data" style="text-align: right;">
                <p>LUIS B GmbH</p>
                <p>Zürcherstr. 102</p>
                <p>8852 Altendorf</p>
                <p>+41 55 442 33 11</p>
                <p>info@spotter.ch</p>
            </div>
        </div>
    
        <div class="mid_part">
            <table>
                <tr>
                    <th style="border: none;">
                        <div class="left_info address">
							<p class="client-nr">K-Nr. {{ $user->id }}</p>
			                <br>
			                <p>{{ $user->getTitle() }}</p>
			                <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p>{{ $user->address }}</p>
                            <p class="bold city-zip">{{ $user->zip_code }} {{ $user->city }}</p>
			                <br><br><br>
			                <p>Altendorf, {{ strftime("%d. %m. %Y") }}</p>
			                <br>
			                <p class="bold">Rechnung Nr. {{ $ad->invoice->id }}</p>
                        </div>
                    </th>
                </tr>
            </table>
        </div>

        <div style="overflow-x: auto">
            <table class="receipt-table">
				
				@php 
					$price = $bid->won_bid_amount;
					$vat = $price * 7.7 / 100;
				@endphp

                <tr>
                    <th>Art-Nr.</th>
                    <th>Los-Nr.</th>
                    <th>Artikel</th>
                    <th>Zuschlag/VP</th>
                    <th>Menge</th>
                    <th>Preis</th>
                </tr>
                
                <tr class="products">
                    <td>{{ $ad->auction_no }}</td>
                    <td>{{ $ad->bid_no }}</td>
                    <td>{{ $ad->title }}</td>
                    <td>{{ $price }} CHF</td>
                    <td>1</td>
                    <td>{{ number_format($price, 2) }} CHF</td>
                </tr>

                <tr class="calc">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right calc">Summe</td>
                    <td>{{ number_format($price, 2) }} CHF</td>
                </tr>
                <tr class="calc">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right calc">MwSt. 7.7%</td>
                    <td>{{ number_format($vat, 2) }} CHF</td>
                </tr>
                <tr class="calc">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right calc">Rechnungstotal</td>
                    <td>{{ number_format($price + $vat, 2) }} CHF</td>
                </tr>
            </table>
        </div>
			
		<br><br><br><br><br>
        <p>Zahlbar innert 7 Tagen.</p>
        <br>
        <p>Abholfristen:</p>
        <p>- No-Limit-Versteigerungen 7 Tagen</p>
        <p>- Auktionen 14 Tage</p>

        <br><br><br><br><br><br>

        <p>Bankverbindung:</p>
        <p>Raiffeisenbank, 8001, Zürich</p>
        <p>IBAN: CH64 8080 8005 4664 1513 4</p>
        <p>SWIFT-BIC: RAIFCH22</p>

        <br>

        <p>zugunsten von</p>
        <p>LUIS B GmbH, 8002 Zürich</p>
    </div>
</body>

</html>