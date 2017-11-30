<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Group;
use App\Models\Submission;

class Assignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    public function User()
    {
        return $this->belongTo(User::class,'user_id');
    }

    public function Group()
    {
        return $this->belongTo(Group::class,'group_id');
    }

    public function Submissions()
    {
        return $this->hasMany(Submission::class,'assignment_id');
    }
}
