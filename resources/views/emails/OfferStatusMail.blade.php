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

        Hi {{$customer_name}},<br /><br />

        @if(isset($is_admin) && $is_admin == 1)
            @if($status == 1)
                Offer accepted. Details are as below<br /><br />
            @elseif($status == 2)
                Offer rejected. Details are as below<br /><br />
            @endif
        @else
            @if($status == 1)
                Congratulations! Your offer having below details has been accepted by owner.<br /><br />
            @elseif($status == 2)
                Sorry! Your offer having below details has been rejected by owner.<br /><br />
            @endif
        @endif

        Type : <strong>{{$asset_type}}</strong> <br />
        Asset : <strong>{{$asset_name}}</strong> <br />
        Listed Price : <strong>{{$asset_price}}</strong> <br />
        Offered Price : <strong>{{$customer_price}}</strong> <br /><br />

        @if(isset($is_admin) && $is_admin == 1)
            <a href="{{$url}}">Click Here</a> to view property detail.<br />
        @else
            <a href="{{url('my/offers')}}">Click Here</a> to view your offers.<br />
        @endif

        <br />
		Thanks,<br />
        Loquare <br />
        info@loquare.com
    </div>
</body>
</html>