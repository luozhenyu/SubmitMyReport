<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'submission_file');
    }

    /**
     * 提交的批改人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mark_user()
    {
        return $this->belongsTo(User::class, 'mark_user_id');
    }

    /**
     * 提交是否批改
     * @return bool
     */
    public function corrected()
    {
        return (boolean)$this->mark_user;
    }

    public function getMarkAttribute($value)
    {
        return is_null($value) ? null : json_decode($value);
    }

    public function getAverageScoreAttribute($value)
    {
        return is_null($value) ? null : round($value, 2);
    }
}
