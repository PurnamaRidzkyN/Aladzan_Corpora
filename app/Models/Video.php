<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_group_id',
        'title',
        'description',
        'thumbnail_id',
        'video_id',
    ];

    public function group()
    {
        return $this->belongsTo(VideoGroup::class, 'video_group_id');
    }
}
