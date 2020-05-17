<?php

namespace App\Console\Commands;

use App\Input;
use App\InputValues;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class InputsValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inputsValues:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get values of the inputs nodes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $inputs = Input::with('node')->get();
        while(true){
            foreach ($inputs as $key => $input){
                try {
                    $res = $client->request('GET', $input->node->ip, ['connect_timeout' => 5]);
                    if ($res->getStatusCode() == 200) {
                        $obj = json_decode($res->getBody(), true);
                        $obj['input_id'] = $input->id;
                        $obj['value'] = $obj['Irms'];
                        unset($obj['Irms']);
                        InputValues::create($obj);
                    }
                }catch (ConnectException $ex){

                }catch (ClientException $ex){

                }catch (BadResponseException $ex){

                }catch (Exception $e){

                }
            }
            sleep(1);
        }
    }
}
