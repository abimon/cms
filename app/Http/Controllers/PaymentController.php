<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use App\Models\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function generateToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $res = Http::withBasicAuth($consumer_key, $consumer_secret)
            ->get($url);
        $response = json_decode($res, true);
        return $response['access_token'];
    }
    public function lipaNaMpesaPassword()
    {
        $passkey = env('MPESA_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp = date('YmdHis');
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }
    public function Callback($id)
    {
        $res = request();
        Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        // if ($res['Body']['stkCallback']['ResultCode'] == 0) {
        $message = $res['Body']['stkCallback']['ResultDesc'];
        $amount = $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
        $TransactionId = $res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
        $phne = $res['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
        Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        Mpesa::create([
            'TransactionType' => 'Paybill',
            'payment_id' => $id,
            'TransAmount' => $amount,
            'MpesaReceiptNumber' => $TransactionId,
            'TransactionDate' => date('d-m-Y'),
            'PhoneNumber' => '+' . $phne,
            'response' => $message
        ]);
        // if ($amount >= 2) {
        $payments = Payment::where('payment_code', $id)->get();
        foreach ($payments as $payment) {
            $payment->status = 'paid';
            $payment->save();
        }
        Log::channel('mpesaSuccess')->info('Payment successful for Payment Code: ' . $id);
        $response = new Response();
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }
    function Pay($amount, $contact, $id)
    {
        $url = (env('MPESA_ENV') == 'live') ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $data = [
            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $contact,
            'PartyB' => env('MPESA_SHORT_CODE'),
            'PhoneNumber' => $contact,
            'CallBackURL' => 'https://churchms.apektechinc.com/api/payment/callback/' . $id,
            'AccountReference' => 'Payment for Receipt ' . $id,
            'TransactionDesc' => 'Payment for Receipt ' . $id,
        ];
        $response = Http::withToken($this->generateToken())
            ->post($url, $data);
        $res = $response->json();
        return $res;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Superadmin') {
            $payments = Payment::all();
        } else {
            $payments = Payment::where('user_id', Auth::user()->id)->get();
        }
        if (request()->is('api/*')) {
            return response()->json(['payments' => $payments]);
        }
        return view('account.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $code = strtoupper(uniqid());
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'method' => 'required',
            'phone' => 'required'
        ]);
        if (!$validate) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate], 400);
        }
        foreach (request('items') as $item) {
            Payment::create([
                'user_id' => request('user_id'),
                'account_id' => $item[0],
                'payment_code' => $code,
                'amount' => $item[1],
                'payment_method' => request('method'),
                'status' => $item['status'] ?? 'pending',
            ]);
        }
        $this->Pay(request('amount'), request('phone'), $code);
        if (request()->is('api/*')) {
            return response()->json(['message' => 'Payment initiated successfully', 'code' => $code], 200);
        }
        return back()->with('success', 'Payment initiated successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $payment = Payment::findOrFail($id);
        if(request('user_id')!=null){
            $payment->user_id=request('user_id');
        }
        if(request('account_id')!=null){
            $payment->account_id=request('account_id');
        }
        if(request('payment_code')!=null){
            $payment->payment_code=request('payment_code');
        }
        if(request('amount')!=null){
            $payment->amount=request('amount');
        }
        if(request('payment_method')!=null){
            $payment->payment_method=request('payment_method');
        }
        if(request('status')!=null){
            $payment->status=request('status');
        }
        $payment->update();
        if(request()->is('api/*')){
            return response()->json(['message'=>'Payment updated successfully'],200);
        }
        return back()->with('success','Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
