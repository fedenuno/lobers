<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use App\Models\Multivende;
use App\Models\Stock;
use App\Models\Sold;
use DB;

class UpStockCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upd:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el stock en multivende de todo producto que tenga movimientos de venta en tienda y/o shopify.';
    protected $configuracion = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $configuracion = Multivende::all()->toArray();
        foreach ($configuracion as $key => $value) {
            $this->configuracion[$value['nombre']] = $value['valor'];
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $this->checkToken();

        echo '>>'.date('d/m/Y H:i:s')."\n\r";

        $parametros = ['headers' => ['Authorization' => "Bearer {$this->configuracion['token']}"],
                       'verify'  => false];
        $client = new Client();
        $inicio = date('Y-m-d H:i:s', strtotime(date('Y-m-d')."-2 days")); 
        $fin = date('Y-m-d 23:59:59');
        
        $pagina = 1;

        $pedidos = [];
        do {
            $uri = "https://app.multivende.com/api/m/{$this->configuracion['merchantid']}/checkouts/light/p/$pagina?_sold_at_from=$inicio&_sold_at_to=$fin";
            $response = $client->get($uri, $parametros);
            $resultado = json_decode($response->getBody()->getContents(), true);

            foreach ($resultado['entries'] as $key => $value) {
                $pedidos[] = $value['_id'];
            }

            if($resultado['pagination']['next_page'] > 0) {
                $pagina = $resultado['pagination']['next_page'];
            } else {
                $pagina = null;
            }
        } while($pagina != null);

        $vendido_en_shopify = [];
        foreach ($pedidos as $key => $value) {
            $uri = "https://app.multivende.com/api/checkouts/$value";
            $response = $client->get($uri, $parametros);
            $resultado = json_decode($response->getBody()->getContents(), true);
            foreach ($resultado['CheckoutItems'] as $ke => $va) {
                if(!array_key_exists($va['code'], $vendido_en_shopify)) {
                    $vendido_en_shopify[$va['code']] = $va['count'];
                } else {
                    $vendido_en_shopify[$va['code']] += $va['count'];
                }
            }
        }

        $datos = Sold::all('code', 'amount')->toArray();
        $vendido = [];
        foreach ($datos as $key => $value) {
            $vendido[$value['code']] = $value['amount'];
        }
        print_r($vendido);
        print_r($vendido_en_shopify);
        dd('>>sopas');

        $existencias = Stock::all();
        $cont = 0;
        foreach ($existencias as $key => $value) {
            try {
                $nueva_existencia = $value->amount<=0?0:$value->amount;
                if(isset($vendido_en_shopify[$value->code])) {
                    // A la existencia actual en NAV se le resta lo vendido en shopify
                    $nueva_existencia = $value->amount-$vendido_en_shopify[$value->code];

                    // Se evalua el mínimo para tener respaldo a fallos
                    if($nueva_existencia <= 0) {
                        $nueva_existencia = 0;
                    }
                }

                // Actualizar inventario
                $parametros = ['json'    => ['amount' => $nueva_existencia],
                               'headers' => ['Authorization' => "Bearer {$this->configuracion['token']}"],
                               'verify'  => false];
                $client = new Client();  
                $uri = "https://app.multivende.com/api/product-stocks/stores-and-warehouses/{$this->configuracion['almacen']}/product-versions/{$value->code}/set";
                $response = $client->post($uri, $parametros);
                $resultado = json_decode($response->getBody()->getContents(), true);
                $cont++;
            } catch (RequestException $e) {
                $resultado = Psr7\Message::toString($e->getRequest());
                if ($e->hasResponse()) {
                    $resultado = Psr7\Message::toString($e->getResponse());
                }
            }
        }

        echo '>>'.date('d/m/Y H:i:s')."\n\r";
        echo sizeof($existencias).'/'.$cont;
    }

    public function checkToken() {
        $fecha1 = new \DateTime($this->configuracion['tiempo']);
        $fecha2 = new \DateTime(date('Y-m-d H:i:s'));

        $intervalo = $fecha1->diff($fecha2);
        if((int) $intervalo->format('%H') >= 5) {
            try {
                $parametros = ['json'   => ['client_id'     => $this->configuracion['client_id'],
                                            'client_secret' => $this->configuracion['client_secret'],
                                            'grant_type'    => 'refresh_token',
                                            'refresh_token' => $this->configuracion['refreshtoken']],
                               'verify' => false];
                $client = new Client();  
                $uri = 'https://app.multivende.com/oauth/access-token';
                $response = $client->post($uri, $parametros);
                $resultado = json_decode($response->getBody()->getContents(), true);

                $token = null;
                $refreshtoken = null;
                if($resultado['token'] != '') {
                    $token = $resultado['token'];
                    $refreshtoken = $resultado['refreshToken'];
                }

                Multivende::where('nombre', '=', 'token')->update(['valor' => $token]);
                Multivende::where('nombre', '=', 'refreshtoken')->update(['valor' => $refreshtoken]);
                Multivende::where('nombre', '=', 'tiempo')->update(['valor' => date('Y-m-d H:i:s')]);

                $configuracion = Multivende::all()->toArray();
                foreach ($configuracion as $key => $value) {
                    $this->configuracion[$value['nombre']] = $value['valor'];
                }
            } catch (RequestException $e) {
                $resultado = Psr7\Message::toString($e->getRequest());
                if ($e->hasResponse()) {
                    $resultado = Psr7\Message::toString($e->getResponse());
                }
            }
        }
    }
}