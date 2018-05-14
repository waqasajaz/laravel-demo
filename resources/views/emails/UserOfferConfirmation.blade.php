<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Calibri; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
	<div style="padding: 4% 20%; font-size:18px;">
		<h1 style="text-align:center;">LQUARE</h1>

        Hi {{$name}},<br /><br />

        Congratulations! Your offer has been received to us successfully. Your offer details are as below.<br /><br />

        Asset : <strong>{{$asset}}</strong> <br />
        Payment Method : <strong>{{$payment_method}}</strong> <br />
        Offered Price : <strong>{{$offered_price}}</strong> <br />
        Your Detail : <strong>{{$your_detail}}</strong> <br />
        @if($visit_schedule != '')
        Visit Schedule : <strong>{{$visit_schedule}}</strong> <br />
        @endif
        @if($rental_period != '')
        Rental Period : <strong>{{$rental_period}}</strong> <br />
        @endif
        @if($type == 'SALE')
            Buyer Info : <strong>{{$buyer_info}}</strong> <br />
        @elseif($type == 'RENT')
            Renter Info : <strong>{{$buyer_info}}</strong> <br />
        @endif
        Signature Schedule : <strong>{{$signature_schedule}}</strong> <br />

        <br />
		Thanks,<br />
        Loquare <br />
        info@loquare.com
    </div>
</body>
</html>