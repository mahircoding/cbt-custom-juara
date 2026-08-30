<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam\Grade;
use App\Models\Exam\ExamGroup;
use Auth;
use Carbon\Carbon;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Exam\GradeRepository;
use App\Repositories\MasterData\AnnouncementRepository;
use App\Repositories\MasterData\CategoryRepository;
use App\Models\Setting\Setting;
use App\Models\MasterData\Category;
use App\Models\MasterData\News;
use App\Models\Setting\Banner;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository, )
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
        $setting = Setting::first();
        $user = Auth::user()?->loadMissing(['referralLink', 'userCommission']);
        $cpnsCategoryId = '0f4348b6-10ed-4d98-ac73-bc9970dc8b73';
        $kedinasanCategoryId = '1350c742-67be-4841-9cf7-a92a9ce6278a';

        return inertia('User/Dashboard/Index', [
            'banners' => Banner::where('is_active', 1)->orderBy('order', 'ASC')->get(),
            'totalTransactionPending' => number_format($this->transactionRepository->getTotalTransactionPendingByUser()),
            'totalTransactionPaid' => number_format($this->transactionRepository->getTotalTransactionPaidByUser()),
            'totalTransactionDone' => number_format($this->transactionRepository->getTotalTransactionDoneByUser()),
            'totalTransactionFailed' => number_format($this->transactionRepository->getTotalTransactionFailedByUser()),
            'transactions' => $this->transactionRepository->getSummaryTransactionByUser(),
            'announcementSummaries' =>  (new announcementRepository())->getAnnouncementSummaries(),
            'newsSummaries' => News::with('user')
                ->where('is_published', 1)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get(),
            'recentExamGroupUsers' => \App\Models\Exam\ExamGroupUser::where('user_id', Auth::id())
                ->with(['examGroup', 'examGroup.category', 'examGroup.lessonCategory'])
                ->where('is_finished', 1)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get(),
            'recentGrades' => \App\Models\Exam\Grade::where('user_id', Auth::id())
                ->whereNull('exam_group_id')
                ->with(['exam', 'category', 'lessonCategory'])
                ->where('is_finished', 1)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get(),
            'todayLiveClasses' => \App\Models\Material\Classroom::where('status', 'active')
                ->with(['category', 'user'])
                ->whereDate('start_time', '>=', \Carbon\Carbon::today())
                ->orderBy('start_time', 'ASC')
                ->limit(5)
                ->get(),
            'recentTryouts' => ExamGroup::with(['category', 'lessonCategory', 'userAccess'])
                ->whereIn('exam_status', ['active', 'inprogress'])
                ->orderBy('created_at', 'DESC')
                ->limit(6)
                ->get(),
            'cpnsTryoutLeaderboard' => DB::table('grades as g')
                ->join('users as u', 'g.user_id', '=', 'u.id')
                ->where('g.is_finished', 1)
                ->whereNotNull('g.exam_group_id')
                ->where('g.category_id', $cpnsCategoryId)
                ->select(
                    'u.id as user_id',
                    'u.name',
                    'u.code',
                    DB::raw('MAX(g.grade) as best_grade'),
                    DB::raw('AVG(g.grade) as avg_grade'),
                    DB::raw('COUNT(*) as attempt_count'),
                    DB::raw('MAX(g.created_at) as last_attempt_at')
                )
                ->groupBy('u.id', 'u.name', 'u.code')
                ->orderByDesc('best_grade')
                ->orderByDesc('last_attempt_at')
                ->limit(10)
                ->get(),
            'kedinasanTryoutLeaderboard' => DB::table('grades as g')
                ->join('users as u', 'g.user_id', '=', 'u.id')
                ->where('g.is_finished', 1)
                ->whereNotNull('g.exam_group_id')
                ->where('g.category_id', $kedinasanCategoryId)
                ->select(
                    'u.id as user_id',
                    'u.name',
                    'u.code',
                    DB::raw('MAX(g.grade) as best_grade'),
                    DB::raw('AVG(g.grade) as avg_grade'),
                    DB::raw('COUNT(*) as attempt_count'),
                    DB::raw('MAX(g.created_at) as last_attempt_at')
                )
                ->groupBy('u.id', 'u.name', 'u.code')
                ->orderByDesc('best_grade')
                ->orderByDesc('last_attempt_at')
                ->limit(10)
                ->get(),
            'cpnsLeaderboardCategoryId' => $cpnsCategoryId,
            'kedinasanLeaderboardCategoryId' => $kedinasanCategoryId,
            'walletSummary' => [
                'learning_balance' => $user?->account_balance ?? 0,
                'referral_balance' => $user?->userCommission?->current_balance ?? 0,
                'referral_code' => $user?->referralLink?->referral_code,
            ],
            'totalDataInCategories' => optional($setting)->category_access == 1
                ? Category::withCount([
                    'exam as exam_count' => fn($query) => $query->whereNull('exam_group_id'),
                    'ExamGroup', 'module', 'videoModule', 'course', 'classroom'
                ])
                ->where('development_status', 'production')
                ->orderBy('order')
                ->get()
                : (Auth::user() 
                    ? Auth::user()->categories()
                        ->withCount([
                            'exam as exam_count' => fn($query) => $query->whereNull('exam_group_id'),
                            'ExamGroup', 'module', 'videoModule', 'course'
                        ])
                        ->where('development_status', 'production')
                        ->orderBy('order', 'ASC')
                        ->get()
                    : []
                ),
       
        ]);
    }
}
