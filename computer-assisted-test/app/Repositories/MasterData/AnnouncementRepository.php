<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Announcement;
use App\Repositories\Contracts\MasterData\AnnouncementInterface;
use App\Repositories\BaseRepository;
use Auth;

class AnnouncementRepository extends BaseRepository implements AnnouncementInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Announcement();
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $announcements = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $announcements->where('title', 'like', '%' . $params->search . '%');

        if(Auth::user()->level == 3) {
            $announcements->where('user_id', Auth::user()->id);
        }

        $announcements = $announcements->with('user')->orderBy('created_at', 'DESC')->paginate($limit);

        $announcements->appends([
            'search' => $params->search,
        ]);

        return $announcements;
    }

    public function getAnnouncementSummaries($limit = 10)
    {
        $announcements = $this->model->orderBy('created_at', 'DESC')->limit($limit)->get();
        return $announcements;
    }

    public function find($id)
    {
        return $this->model->with('user')->find($id);
    }
}
