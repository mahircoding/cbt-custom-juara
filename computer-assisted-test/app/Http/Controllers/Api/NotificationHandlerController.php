<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\UserMemberCategory;
use App\Repositories\Transaction\TransactionRepository;

class NotificationHandlerController extends Controller
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $payload = $request->getContent();
            $notification = json_decode($payload);
            $signatureKey = hash("sha512", $notification->order_id. $notification->status_code. $notification->gross_amount. config('services.midtrans.serverKey'));
            if ($notification->signature_key != $signatureKey) {
                \Log::info('error midtrans notitification (invalid signature)');
                return response(['message' => 'Invalid signature'], 403);
            }

            $transaction  = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {

                }

            } elseif ($transaction == 'settlement') {
                // \Log::info('success kirim settlement dengan order id = '.$orderId);
                $this->transactionRepository->update((object) ['transaction_status' => 'done'], $orderId);
            } elseif($transaction == 'pending'){
                // \Log::info('success kirim pending dengan order id = '.$orderId);
                // $this->transactionRepository->update((object) ['transaction_status' => 'pending'], $orderId);
            } elseif ($transaction == 'deny') {
                // \Log::info('success kirim deny dengan order id = '.$orderId);
                $this->transactionRepository->update((object) ['transaction_status' => 'failed'], $orderId);
            } elseif ($transaction == 'expire') {
                // \Log::info('success kirim expire dengan order id = '.$orderId);
                $this->transactionRepository->update((object) ['transaction_status' => 'expired'], $orderId);
            }elseif ($transaction == 'cancel') {
                // \Log::info('success kirim cancel dengan order id = '.$orderId);
                $this->transactionRepository->update((object) ['transaction_status' => 'failed'], $orderId);
            }

            return [
                'status' => 'success',
                'notification' => $notification, 
            ];
            
        } catch (\Exception $e) {
            \Log::info('error midtrans notitification = '.$e);
        }
    }
}
