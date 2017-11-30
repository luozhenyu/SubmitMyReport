<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assignment;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function User()
    {
        return $this->belongTo(User::class,'creator_id');
    }

    public function Assignments()
    {
        return $this->hasMany(Assignment::class,'group_id');
    }

    public function belongsToManyUser()
    {
        return $this->belongsToMany(User::class,'Group_User','group_id','user_id')->withPivot('isAdministrator');
    }
}

