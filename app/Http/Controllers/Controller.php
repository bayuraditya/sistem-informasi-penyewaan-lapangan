<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
date_default_timezone_set('Asia/Shanghai');

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function updatePendingPayment(){
        //reset pending ke exp yg tidak payment
        $now = new DateTime();
        $nowDate = $now->format('Y-m-d H:i:s');
        $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
        foreach($transactionCreated as $tr){
            // $transaction = Transaction::where('created_at', $tr)->get();
            $transactionId = Transaction::where('created_at', $tr)->value('id');
            // $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+5 seconds'));
            $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
            // echo $tr['id'] . ' : '. $expireTime . '<br>' ;
            if($expireTime < $nowDate){
                $transactionData = [
                    'payment_status' => 'expire'
                ];
                $transaction = Transaction::find($transactionId);;
                $transaction->update($transactionData);
            }
        }
   }
}
