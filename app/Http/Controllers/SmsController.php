<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MobileNumbers;
use App\Models\SmsDraft;
use App\Services\smsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SmsController extends Controller
{
    public function allsms()
    {
        $depositNumbers = MobileNumbers::where('type', 'new_deposit')->get();
        $customers      = Customer::select('id', 'contact_name', 'phone', 'company_name')->get();
        $drafts         = SmsDraft::where('type', 'general')->where('is_active', 1) ->get();

        return view('pages.sms.allSms', compact('depositNumbers', 'customers', 'drafts'));
    }

    public function sendAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->message;

        $numbers = config('carbiddata.depositNumbers');

        foreach (array_chunk($numbers, 50) as $batch) {
            foreach ($batch as $number) {
                (new smsService())->info($number, $message);
            }
            sleep(1);
        }

        return back();
    }

    public function sendRecipient(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'number'  => 'required|string|regex:/^5\d{8}$/',
        ]);


        (new smsService())->info($request->number, $request->message);

        return back();
    }

    public function sendSelected(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'phone'   => 'required|array',
            'phone.*' => 'required|string|regex:/^5\d{8}$/',
        ]);


        foreach ($request->phone as $number) {
            (new smsService())->info($number, $request->message);
        }


        return back();
    }

    public function selectDraftHtmx(Request $request)

    {
        $draft = SmsDraft::where('id', request('draft'))->first();
       return view('pages.htmx.htmxSmsDraft', compact('draft'));
    }

    public function drafts()
    {
        $draftsSystem  = SmsDraft::where('type', null)->get();
        $draftsGeneral = SmsDraft::where('type', 'general')
            ->get();


        return view('pages.sms.smsDrafts', compact('draftsSystem', 'draftsGeneral'));
    }

    public function storeDraft(Request $request)
    {
        if ($request->type === 'general') {
            $request->validate([
                'draft'              => 'required|string',
                'action_description' => 'required|string',
            ]);

            $draft                     = new SmsDraft();
            $draft->draft              = $request->draft;
            $draft->type               = 'general';
            $draft->action_description = $request->action_description;
            $draft->save();

            return back();
        }


        $request->validate([
            'draft'              => 'required|string',
            'action_name'        => 'required|string',
            'action_description' => 'required|string',
        ]);

        $draft                     = new SmsDraft();
        $draft->draft              = $request->draft;
        $draft->action_name        = $request->action_name;
        $draft->action_description = $request->action_description;

        $draft->save();

        return back();
    }

    public function activateDraft(Request $request)
    {
        $draft = SmsDraft::where('id', request('id'))->first();

        if ($draft->is_active == 1) {
            $draft->is_active = 0;
            $draft->save();
        } else {
            $draft->is_active = 1;
            $draft->save();
        }

        return back();
    }

    public function updateDraft(Request $request)
    {
        $draft = SmsDraft::where('id', request('id'))->first();

        $request->validate([
            'draft'              => 'required|string',
            'action_description' => 'required|string',
        ]);

        $draft->draft              = $request->draft;
        $draft->action_description = $request->action_description;

        $draft->save();

        return back();
    }

    public function deleteDraft(Request $request)
    {
        $draft = SmsDraft::where('id', request('id'))->first();
        $draft->delete();

        return back();
    }

    public function clearInvalids()
    {
        Cache::forget('invalidPhones');

        return back();
    }

    public function updateDepositNumber(Request $request)
    {
        $request->validate([
            'numbers'   => 'required|array',
            'numbers.*' => 'required|string|regex:/^5\d{8}$/',
            'name'      => 'required|array',
            'name.*'    => 'required|string',
        ]);

        $oldnumbers = MobileNumbers::where('type', 'new_deposit')->get();

        if ($oldnumbers->isNotEmpty()) {
            foreach ($oldnumbers as $oldnumber) {
                $oldnumber->delete();
            }
        }

        foreach ($request->numbers as $key => $number) {
            $newnumber           = new MobileNumbers();
            $newnumber->number   = $number;
            $newnumber->employee = $request->name[$key];
            $newnumber->type     = 'new_deposit';
            $newnumber->save();
        }

        return back();
    }


}
