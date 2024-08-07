<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use Twilio\Rest\Client;

class SendCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de publicidad a whatsapp mediante twilio.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $number = env('TWILIO_NUMBER');
        $twilio = new Client($sid, $token);

        $clientes = Customer::select('id','movil')->where('estatus', '=', 1)->skip(0)->take(80)->get()->toArray();
        foreach ($clientes as $key => $value) {
            $message = $twilio->messages->create("whatsapp:+521{$value['movil']}",
                                                 ["contentSid" => "HX271bbddb1baf91fa338b988ab424ad0e",
                                                  "from"       => "MG984ef00c2c32d86d280506bb893d8e48",]);
            Customer::where('id', '=', $value['id'])->update(['estatus' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
}