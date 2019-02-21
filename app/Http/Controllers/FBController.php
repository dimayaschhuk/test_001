<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pimax\FbBotApp;

class FBController extends Controller
{
    public function webHook()
    {
        $verify_token = "bb041372d8aa65d06c854cc2060f36f9"; // Verify token
        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
            echo $_REQUEST['hub_challenge'];
        }
    }
}
