<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Bjrnblm\Messagebird\Messagebird;
use MessageBird\Client;
use MessageBird\Common\HttpClient;
use MessageBird\Resources\Chat\Message;
use MessageBird\Resources\Chat\Platform;
use MessageBird\Common;

class LsupportController extends Controller
{
    public function __construct()
    {

    }

    function index()
    {
    	$client = new Client('xLDZTSgHUkW8fMnO856yCUzck');
		$messagebird = new Messagebird($client);
	    $balance = $messagebird->getBalance();

	    $message = $messagebird->createMessage("MessageBird",["+919537729484"], "Hello Darpan How Are you? This message is from Messagebird Loquare.");


	    echo "<pre>";
	    print_r($balance);
	    print_r($message);
	    die;
    }
}
