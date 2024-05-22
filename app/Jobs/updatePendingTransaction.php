<?php

namespace App\Jobs;

use App\Models\Transaction;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class updatePendingTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = new DateTime();
        $nowDate = $now->format('Y-m-d H:i:s');
        $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
        foreach($transactionCreated as $tr){
            // $transaction = Transaction::where('created_at', $tr)->get();
            $transactionId = Transaction::where('created_at', $tr)->value('id');
            $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
            // echo $now . '<br>'. $expireTime . '<br>';
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
