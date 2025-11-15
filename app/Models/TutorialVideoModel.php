<?php

namespace App\Models;

use CodeIgniter\Model;

class TutorialVideoModel extends Model
{
    protected $table = 'tutorial_videos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'video_url', 'category', 'created_at'];
}
