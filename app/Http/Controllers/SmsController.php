<?php

namespace App\Http\Controllers;

use App\Models\MobileNumbers;
use App\Models\SmsDraft;
use App\Services\smsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SmsController extends Controller
{
    public function allsms(){

        $depositNumbers=MobileNumbers::where('type','new_deposit')->get();

        return view('pages.sms.allSms',compact('depositNumbers'));
    }

    public function sendAll(Request $request){

        $request->validate([
            'message'=>'required|string'
        ]);

        $message=$request->message;

        $numbers=config('carbiddata.depositNumbers');

        foreach (array_chunk($numbers, 50) as $batch){
            foreach($batch as $number){
                (new smsService())->info($number,$message);
            }
            sleep(1);
        }

        return back();

    }

    public function sendRecipient(Request $request){

        $request->validate([
            'message'=>'required|string',
            'number' => 'required|string|regex:/^5\d{8}$/',
        ]);


        (new smsService())->info($request->number,$request->message);

        return back();

    }

    public function selectedsms(){


        return view('pages.sms.selectedSms');

    }

    public function sendSelected(){


        return view('pages.sms.selectedSms');

    }

    public function drafts(){

        $drafts=SmsDraft::all();

        return view('pages.sms.smsDrafts',compact('drafts'));
    }

    public function storeDraft(Request $request){

        $request->validate([
            'draft'=>'required|string',
            'action_name'=>'required|string'
        ]);

        $draft=new SmsDraft();
        $draft->draft=$request->draft;
        $draft->action_name=$request->action_name;

        $draft->save();

        return back();

    }

    public function activateDraft(){

    }

    public function updateDraft(){

    }

    public function deleteDraft(){

    }

    public function clearInvalids(){
        Cache::forget('invalidPhones');
        return back();
    }

    public  function updateDepositNumber(Request $request){

        $request->validate([
            'numbers'=>'required|array',
            'numbers.*'=>'required|string|regex:/^5\d{8}$/',
            'name'=>'required|array',
            'name.*'=>'required|string'
        ]);

        $oldnumbers=MobileNumbers::where('type','new_deposit')->get();

        if ($oldnumbers->isNotEmpty()) {
            foreach ($oldnumbers as $oldnumber) {
                $oldnumber->delete();
            }
        }

        foreach ($request->numbers as $key => $number) {
            $newnumber=new MobileNumbers();
            $newnumber->number=$number;
            $newnumber->employee=$request->name[$key];
            $newnumber->type='new_deposit';
            $newnumber->save();
        }

        return back();

    }
}
