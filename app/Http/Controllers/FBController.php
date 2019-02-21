<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pimax\FbBotApp;

class FBController extends Controller
{
    public function webHook()
    {
//        $verify_token = "bb041372d8aa65d06c854cc2060f36f9"; // Verify token
        $verify_token = "EAAFW9Ba2rfkBAAZBrAvzjpO8jreCJoseP1hRSDctsrAHT0xCZBtZBdQyHVQdZCFX9Vjtq9zAKa60JiJObZA1ZAz124tNggGeMih4mZAcqSaB1HuuW32wJj0gt7hj4QD7GLNGPgFuDk0mlNuY9CEHEauSMxfo7NFC3b6PLo7LMZCzuvaZCZAmyS8gZAa"; // Verify token
        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
            echo $_REQUEST['hub_challenge'];
        }
    }
}

