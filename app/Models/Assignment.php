<?php

namespace App\Models;

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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scoredSubmissions()
    {
        return $this->submissions()->where('mark_user_id', null);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'assignment_file');
    }

    public function loginSubmissions()
    {
        return $this->submissions()
            ->where('owner_id', Auth::user()->id);
    }

    public function getDeadlineAttribute($value)
    {
        $date = Carbon::createFromTimeString($value);

        if ($date->year === Carbon::now()->year) {
            return $date->format("m-d H:i");
        }

        return $date->format("Y-m-d H:i");
    }
}
