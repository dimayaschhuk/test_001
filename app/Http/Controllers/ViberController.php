<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Viber\Client;

class ViberController extends Controller
{

    public function setWebhook(){
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $webhookUrl = 'https://www.dimayashchuk.icu/viber_bot'; //
        try {
            $client = new Client([ 'token' => $apiKey ]);
            $result = $client->setWebhook($webhookUrl);
            echo "Success!\n";
        } catch (Exception $e) {
            echo "Error: ". $e->getError() ."\n";
        }
    }

    public function webHook(){

    }
}
