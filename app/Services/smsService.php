<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class smsService
{

    public $username = 'carbid_2024';
    public $password = 'j!2tzpU7AB9m';
    public $client_id = 'CARBIDPRO_IN';
    public $service_id = 'service_id';


    public function info(string $number, string $text)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => $text,
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);



        $responseBody = $response->body();

        // Convert XML string to a PHP object
        $xmlObject = simplexml_load_string($responseBody);
        // Convert PHP object to an array (optional)
        $dataArray = json_decode(json_encode($xmlObject), true);

        $code          = $dataArray['code'];
        $phone         = $dataArray['phone'];

        if($code=== '-7'){
            if (Cache::get('invalidPhones')) {
                $array = Cache::get('invalidPhones');
            } else {
                $array = []; // Initialize as an empty array if the cache doesn't exist
            }
            $array[] = $phone;
            Cache::put('invalidPhones', $array, 86400); // Cache for 1 day (86400 seconds)
        }
        if (isset($dataArray['transaction_id'])){
            $transactionId = $dataArray['transaction_id'];
        }
    }

    public function newCarAdded(string $number, $car)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'The vehicle ( '.$car->make_model_year.' ) has been added to the cabinet. Please pay the amount on time',
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function carLoaded(string $number, $car, $container_id)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => ' Your vehicle ( '.$car->make_model_year.')  is loaded in a container #'.$container_id,
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);

    }

    public function containerOpenedd(string $number, $car)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'Container for ( '.$car->make_model_year.' ) is Opened',
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function newDepositCustomer(string $number, $balance)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'Your payment of ( '.$balance->amount.' )  been sent. Please wait for confirmation',
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function depositConfirmationCustomer(string $number, $balance)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'Your payment of ( '.$balance->amount.' ) has been confirmed',
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function readyForPickup(string $number, $car)
    {

        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'Your vehicle ( '.$car->make_model_year.' ) is ready for pickup',
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function deposit(string $number, $customer, $balance)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
            'username'   => $this->username,
            'password'   => $this->password,
            'client_id'  => $this->client_id,
            'service_id' => $this->service_id,
            'phone'      => '995'.$number,
            'text'       => 'ახალი ჩარიცხვა '."\n".
                'დილერი: '.$customer->company_name."\n".
                'სახელი: '.$balance->full_name."\n"."\n".

                'თარიღი: '.now()->format('d-m-Y')."\n".
                'თანხა: '.$balance->amount,
            'from'       => 'CARBIDPRO',
            'route'      => 'smsc',
        ]);
    }

    public function statusCheck($transaction)
    {
        $response = Http::asForm()->get('http://146.255.253.42:7782/report', [
            'username'         => $this->username,
            'password'         => $this->password,
            'client_id'        => $this->client_id,
            'service_id'       => $this->service_id,
            'route'            => 'smsc',
            'p_transaction_id' => $transaction,

        ]);

        return $response->body();
    }


}