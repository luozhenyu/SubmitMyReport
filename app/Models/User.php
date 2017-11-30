<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Group;
use App\Models\Assignment;
use App\Models\Submission;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Groups()
    {
        return $this->hasMany(Group::class,'creator_id');
    }

    public function Assignments()
    {
        return $this->hasMany(Assignment::class,'user_id');
    }


    public function Submissions()
    {
        return $this->hasMany(Submission::class,'user_id');
    }
}
