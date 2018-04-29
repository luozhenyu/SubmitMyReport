<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Assignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'sub_problem', 'deadline', 'description', 'owner_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scoredSubmissions()
    {
        return $this->submissions()->whereHas('mark');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myScoredSubmissions()
    {
        return $this->submissions()->whereHas('mark', function (Builder $query) {
            $query->where('owner_id', Auth::user()->id);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'assignment_file');
    }

    public function loginSubmissions($logined = null)
    {
        $logined = $logined ?? Auth::user();
        return $this->submissions()
            ->where('owner_id', $logined->id);
    }

    public function getHumanDeadlineAttribute()
    {
        $date = Carbon::createFromTimeString($this->deadline);

        if ($date->year === Carbon::now()->year) {
            return $date->format("m-d H:i");
        }

        return $date->format("Y-m-d H:i");
    }
}
