<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsDraft;
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

        $code  = $dataArray['code'];
        $phone = $dataArray['phone'];

        if ($code === '-7') {
            if (Cache::get('invalidPhones')) {
                $array = Cache::get('invalidPhones');
            } else {
                $array = []; // Initialize as an empty array if the cache doesn't exist
            }
            $array[] = $phone;
            Cache::put('invalidPhones', $array, 86400); // Cache for 1 day (86400 seconds)
        }
        if (isset($dataArray['transaction_id'])) {
            $transactionId = $dataArray['transaction_id'];
        }
    }

    public function newCarAdded(string $number, $car)
    {
        $draft = SmsDraft::where('action_name', 'newCarAdded')->first();

        if ($draft && $draft->is_active === 1) {
            $message = str_replace("CAR-NAME", $car->make_model_year, $draft->draft);
//            $message  = str_replace(["CAR-NAME","VIN"], [$car->make_model_year, $car->vin], $draft->draft);

            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function carLoaded(string $number, $car, $container_id)
    {
        $draft = SmsDraft::where('action_name', 'carLoaded')->first();

        if ($draft && $draft->is_active === 1) {
            $message  = str_replace(["CAR-NAME", "CONTAINER","VIN"], [$car->make_model_year, $container_id, $car->vin], $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function containerOpenedd(string $number, $car)
    {
        $draft = SmsDraft::where('action_name', 'containerOpened')->first();


        if ($draft && $draft->is_active === 1) {
            $message  = str_replace("CAR-NAME", $car->make_model_year, $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function newDepositCustomer(string $number, $balance)
    {
        $draft = SmsDraft::where('action_name', 'newDepositCustomer')->first();

        if ($draft && $draft->is_active === 1) {
            $message  = str_replace("DEPOSIT-AMOUNT", $balance->amount, $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function depositConfirmationCustomer(string $number, $balance)
    {
        $draft = SmsDraft::where('action_name', 'confirmDeposit')->first();

        if ($draft && $draft->is_active === 1) {
            $message  = str_replace("DEPOSIT-AMOUNT", $balance->amount, $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function readyForPickup(string $number, $car)
    {
        $draft = SmsDraft::where('action_name', 'readyForPickup')->first();

        if ($draft && $draft->is_active === 1) {
            $message  = str_replace("CAR-NAME", $car->make_model_year, $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }
    }

    public function deposit(string $number, $customer, $balance)
    {
        $draft = SmsDraft::where('action_name', 'newDepositUs')->first();


        if ($draft && $draft->is_active === 1) {
            $message  = str_replace(["DEALER", "FULL-NAME", "DEPOSIT-AMOUNT"], [$customer->company_name, $balance->full_name, $balance->amount], $draft->draft);
            $response = Http::asForm()->get('http://146.255.253.42:7782/submit', [
                'username'   => $this->username,
                'password'   => $this->password,
                'client_id'  => $this->client_id,
                'service_id' => $this->service_id,
                'phone'      => '995'.$number,
                'text'       => $message,
                'from'       => 'CARBIDPRO',
                'route'      => 'smsc',
            ]);
        }


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