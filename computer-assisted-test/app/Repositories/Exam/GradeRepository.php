<?php

namespace App\Repositories\Exam;

use App\Models\Exam\Grade;
use App\Repositories\Contracts\Exam\GradeInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class GradeRepository extends BaseRepository implements GradeInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Grade;
    }

    public function getAllByUserPaginatedWithParams($params, $limit = 10)
    {
        $search = $params['search'] ?? null;

        $exams = $this->model
            // ->where('is_finished', 1)
            ->where('user_id', auth()->user()->id)
            ->whereNull('exam_group_id')
            ->with(['category', 'lessonCategory', 'lesson', 'exam'])
            ->when($search, function ($query, $search) {
                $query->whereHas('exam', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($limit)
            ->withQueryString();

        return $exams;
    }

    public function getLeaderboardPaginatedWithParams($params, $limit = 10)
    {
        $search = $params['search_participant'] ?? null;
        $base = $this->getLeaderboardBaseQuery($params);

        $leaderboard = DB::query()
            ->fromSub(
                $base->select('user_id', DB::raw('MAX(grade) as score'))->groupBy('user_id'),
                'leaderboards'
            )
            ->join('users', 'users.id', '=', 'leaderboards.user_id')
            ->leftJoin('students', 'students.user_id', '=', 'users.id')
            ->leftJoin('provinces', 'provinces.id', '=', 'students.province_id')
            ->leftJoin('cities', 'cities.id', '=', 'students.city_id')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('users.code', 'like', '%' . $search . '%')
                        ->orWhere('users.email', 'like', '%' . $search . '%')
                        ->orWhere('users.name', 'like', '%' . $search . '%')
                        ->orWhere('users.username', 'like', '%' . $search . '%');
                });
            })
            ->select([
                'leaderboards.user_id',
                'leaderboards.score',
                'users.code',
                'users.email',
                'users.name',
                'users.username',
                'users.photo',
                'provinces.name as province_name',
                'cities.name as city_name',
            ])
            ->orderBy('leaderboards.score', 'DESC')
            ->paginate($limit)
            ->withQueryString();

        return $leaderboard;
    }

    public function getMyLeaderboardRank($params, $userId)
    {
        $base = $this->getLeaderboardBaseQuery($params);

        $myScore = DB::query()
            ->fromSub(
                $base->select('user_id', DB::raw('MAX(grade) as score'))->groupBy('user_id'),
                'leaderboards'
            )
            ->where('user_id', $userId)
            ->value('score');

        if (is_null($myScore)) {
            return null;
        }

        $rank = DB::query()
            ->fromSub(
                $base->select('user_id', DB::raw('MAX(grade) as score'))->groupBy('user_id'),
                'leaderboards'
            )
            ->where('score', '>', $myScore)
            ->count() + 1;

        return [
            'rank' => $rank,
            'score' => $myScore,
        ];
    }

    private function getLeaderboardBaseQuery($params)
    {
        $testType = $params['test_type'] ?? null;
        $categoryId = $params['category_id'] ?? null;
        $lessonCategoryId = $params['lesson_category_id'] ?? null;
        $examGroupId = $params['exam_group_id'] ?? null;

        return $this->model
            ->newQuery()
            ->where('is_finished', 1)
            ->when($testType === 'exam', function ($query) {
                $query->whereNull('exam_group_id');
            })
            ->when($testType === 'tryout', function ($query) {
                $query->whereNotNull('exam_group_id');
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($lessonCategoryId, function ($query, $lessonCategoryId) {
                $query->where('lesson_category_id', $lessonCategoryId);
            })
            ->when($examGroupId, function ($query, $examGroupId) {
                $query->where('exam_group_id', $examGroupId);
            });
    }


    public function getAllByUserWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        $exams = $exams->where(['is_finished' => 1, 'user_id' => $params['user_id']])->whereNull('exam_group_id')->with(['category', 'lessonCategory', 'lesson', 'exam'])->orderBy('created_at', 'DESC')->get();
        return $exams;
    }

    public function find($id)
    {
        return $this->model->with(['user', 'category', 'lessonCategory', 'lesson', 'exam', 'exam.questionTitle.question', 'exam.questionTitle', 'examGroup'])->find($id);
    }

    public function getRankingByExam($params, $examId, $limit = 10, $isFinished = null)
    {
        $rankings = $this->model->query();

        if (!is_null($isFinished)) {
            $rankings->where('is_finished', $isFinished);
        }

        if (isset($params->search) && !empty($params->search)) {
            $rankings->whereHas('user', function ($query) use ($params) {
                $query->where('code', 'like', '%' . $params->search . '%')
                      ->orWhere('email', 'like', '%' . $params->search . '%')
                      ->orWhere('name', 'like', '%' . $params->search . '%')
                      ->orWhere('username', 'like', '%' . $params->search . '%');
            });
        }

        $rankings = $rankings->where('exam_id', $examId)
            ->with(['user.student', 'user.student.province', 'user.student.city'])
            ->orderBy('grade', 'DESC')
            ->paginate($limit);

        $rankings->appends([
            'search' => $params->search,
        ]);

        return $rankings;
    }

    public function getGradeByExam($examId, $isFinished = null)
    {
        $rankings = $this->model->query();

        if($isFinished) {
            $rankings->where('is_finished', $isFinished);
        }

        return $rankings->where('exam_id', $examId)
                        ->with(['user.student', 'user.student.province', 'user.student.city'])
                        ->orderBy('grade', 'DESC')
                        ->cursor();
    }

    public function deleteByExamId($id)
    {
        return $this->model->where('exam_id', $id)->delete();
    }
}
