<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\V1\Transaction as TransactionOld;
use App\Models\V1\Exam as ExamOld;
use App\Models\V1\ExamGroup as ExamGroupOld;
use App\Models\V1\Voucher as VoucherOld;
use App\Models\V1\AccountBalance as AccountBalanceOld;
use App\Models\Transaction\Transaction;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamGroup;
use App\Models\MemberCategory;
use App\Models\UserAccess;
use App\Models\Transaction\Voucher;

class MigrationController extends Controller
{
    public function transaction()
    {
        $transactionOlds = TransactionOld::whereNull('is_migrated')->limit(10000)->get();

        $totalUpdate = 0;
        foreach ($transactionOlds as $transactionOld) {
            $transaction = Transaction::find($transactionOld->id);
            if (!$transaction) {
                $transactionOld->update(['is_migrated' => -1]);
                continue;
            }

            $updated = false;

            DB::beginTransaction();

            try {
                if ($transactionOld->exam_group_id) {
                    $transaction->update([
                        'item_type' => \App\Models\Exam\ExamGroup::class,
                        'item_id' => $transactionOld->exam_group_id
                    ]);

                    if($transactionOld->transaction_status == 'done') {
                        $userAccessExists = DB::table('user_accesses')
                        ->where('transaction_id', $transactionOld->id)
                        ->exists();

                        if(!$userAccessExists) {
                            UserAccess::create([
                                'user_id' => $transaction->user_id,
                                'transaction_id' => $transaction->id,
                                'access_type' => $transaction->item_type,
                                'access_id' => $transaction->item_id,
                            ]);

                        }
                    }

                    $updated = true;
                }

                if ($transactionOld->exam_id) {
                    $transaction->update([
                        'item_type' => \App\Models\Exam\Exam::class,
                        'item_id' => $transactionOld->exam_id
                    ]);

                    if($transactionOld->transaction_status == 'done') {
                        $userAccessExists = DB::table('user_accesses')
                        ->where('transaction_id', $transactionOld->id)
                        ->exists();

                        if(!$userAccessExists) {
                            UserAccess::create([
                                'user_id' => $transaction->user_id,
                                'transaction_id' => $transaction->id,
                                'access_type' => $transaction->item_type,
                                'access_id' => $transaction->item_id,
                            ]);
                        }
                    } 

                    $updated = true;
                }

                if($transactionOld->member_categories) {
                    $categories = json_decode($transactionOld->member_categories, true);

                    if ($transactionOld->transaction_status == 'done') {
                        foreach ($categories as $categoryName) {
                            $memberCategory = MemberCategory::where('name', $categoryName)->first();
                            if ($memberCategory) {
                                $exists = DB::table('member_category_user')
                                    ->where('transaction_id', $transactionOld->id)
                                    ->exists();
            
                                if (!$exists) {
                                    DB::table('member_category_user')->insert([
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                        'user_id' => $transactionOld->user_id,
                                        'member_category_id' => $memberCategory->id,
                                        'category_id' => $transactionOld->category_id, 
                                        'transaction_id' => $transactionOld->id, 
                                        'expired_date' => $transactionOld->expired_date,
                                    ]);
                                }
                            }
                        }
                    }

                    $name = null;
                    if (preg_match('/^(.*?)\s*Masa Aktif/i', $transactionOld->description, $matches)) {
                        $name = trim($matches[1]);
                    }

                    $voucher = VoucherOld::where([
                        'period_type' => $transactionOld->period_type,
                        'active_period' => $transactionOld->active_period,
                        'name' => $name,
                    ])->first();

                    $transaction->update([
                        'item_type' => \App\Models\Transaction\Voucher::class,
                        'item_id' => $voucher ? $voucher->id : null
                    ]);

                    $updated = true;
                }

                $transactionOld->update(['is_migrated' => $updated]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // \Log::info($e);
                $transactionOld->update(['is_migrated' => -1]);
            }

            $totalUpdate++;
        }

        return response()->json([
            'message' => 'Batch migrasi '.$totalUpdate.' data selesai.',
            'sisa_belum_migrasi' => TransactionOld::whereNull('is_migrated')->count()
        ]);
    }

    public function examMemberCategory()
    {
        $oldExams = ExamOld::whereNotNull('member_categories')->get();

        foreach ($oldExams as $oldExam) {
            $exam = Exam::find($oldExam->id);
            if (!$exam) {
                continue;
            }
    
            $categories = json_decode($oldExam->member_categories, true);
            if (!is_array($categories)) {
                continue;
            }
    
            foreach ($categories as $categoryName) {
                $memberCategory = MemberCategory::where('name', $categoryName)->first();
                if ($memberCategory) {
                    $exists = DB::table('exam_member_category')
                        ->where('exam_id', $exam->id)
                        ->where('member_category_id', $memberCategory->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('exam_member_category')->insert([
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'exam_id' => $exam->id,
                            'member_category_id' => $memberCategory->id
                        ]);
                    }
                }
            }
        }
    
        return response()->json([
            'message' => 'Migrasi relasi exam-member-category selesai.',
        ]);
    }

    public function examGroupMemberCategory()
    {
        $oldExamGroups = ExamGroupOld::whereNotNull('member_categories')->get();

        foreach ($oldExamGroups as $oldExamGroup) {
            $examGroup = ExamGroup::find($oldExamGroup->id);
            if (!$examGroup) {
                continue;
            }
    
            $categories = json_decode($oldExamGroup->member_categories, true);
            if (!is_array($categories)) {
                continue;
            }
    
            foreach ($categories as $categoryName) {
                $memberCategory = MemberCategory::where('name', $categoryName)->first();
                if ($memberCategory) {
                    $exists = DB::table('exam_group_member_category')
                        ->where('exam_group_id', $examGroup->id)
                        ->where('member_category_id', $memberCategory->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('exam_group_member_category')->insert([
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'exam_group_id' => $examGroup->id,
                            'member_category_id' => $memberCategory->id
                        ]);
                    }
                }
            }
        }
    
        return response()->json([
            'message' => 'Migrasi relasi exam-group-member-category selesai.',
        ]);
    }

    public function topupBalance()
    {
        $accountBalances = AccountBalanceOld::whereNull('is_migrated')->limit(10000)->get();

        $totalUpdate = 0;

        foreach($accountBalances as $accountBalance) {
            Transaction::create([
                'created_at' => $accountBalance->created_at,
                'updated_at' => $accountBalance->updated_at,
                'user_id' => $accountBalance->user_id,
                'item_type' => $accountBalance->voucher_code_id ? \App\Models\Transaction\VoucherCode::class : 'TopupBalance',
                'item_id' => $accountBalance->voucher_code_id ?? null,
                'code' => $accountBalance->code,
                'description' => $accountBalance->voucher_code_id ? 'Reedem Saldo Rp.'. number_format($accountBalance->top_up_balance, 2, ",", ".") : 'Top Up Saldo Rp.' . number_format($accountBalance->top_up_balance, 2, ",", "."),
                'payment_method' => $accountBalance->voucher_code_id ? 'not_payment_method' : 'manual_transfer',
                'total_payment' => $accountBalance->top_up_balance,
                'transaction_status' => $accountBalance->transaction_status,
            ]);

            $accountBalance->update(['is_migrated' => 1]);

            $totalUpdate++;
        }

        return response()->json([
            'message' => 'Batch migrasi '.$totalUpdate.' data selesai.',
            'sisa_belum_migrasi' => AccountBalanceOld::whereNull('is_migrated')->count()
        ]);
    }
}
