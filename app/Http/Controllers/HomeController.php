<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Customer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        /*$request->user()->authorizeRoles(['user', 'admin']);
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $number = env('TWILIO_NUMBER');
        $twilio = new Client($sid, $token);*/

        /*
         * Prueba mensaje con variable
         */
        /*$message = $twilio->messages->create(
            "whatsapp:+5213328063089", // to
            [
                "contentSid" => "HXd8a15553889ab324865c71917d67acf7",
                "from" => "MG984ef00c2c32d86d280506bb893d8e48",
                "contentVariables" => json_encode([
                    "nombre" => "Fede",
                ]),
            ]
        );*/

        /*
         * Prueba mensaje multimedia
         */
        /*$message = $twilio->messages->create(
            "whatsapp:+5213328063089", // to
            [
                "contentSid" => "HX271bbddb1baf91fa338b988ab424ad0e",
                "from" => "MG984ef00c2c32d86d280506bb893d8e48",
            ]
        );*/

        return view('home');
    }
}
